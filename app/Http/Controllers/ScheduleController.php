<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \Cache;
use \Storage;
use \Config;
use \Tmbd;
use \cURL;
use \DB;
use \Str;
use \Carbon;
use \Exception;
use \Request;
use \App\Program;
use \App\Episode;
use \App\Show;
use \App\Cast;
use Auth;

class ScheduleController extends Controller {

    public function __construct()
    {
        $this->middleware("auth.ion");

        if (Auth::check() &&  Auth::user()->hasPermission("admin")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

    public function show($date = NULL){

        $date =  $date ? Carbon::parse($date) : Carbon::today();
        $schedule_in_range = true;
        $today = Carbon::today();
        $last_day = clone $today;
        $this_day = Carbon::parse($date);
        $days = [];
        $debug = [];

        for($i=0;$i<7;$i++){
            $days[] = [$last_day->addDays($i == 0 ? 0 : 1)->toDateString(),
                $last_day->format("D"),
                $last_day->format("M j"),
            ];
        }

        if($this_day->gt($last_day) || $this_day->lt($today)){
            $schedule_in_range = false;
        }

        $data["days"] = $days;
        $data["schedule"] = ($schedule_in_range) ? Program::schedule($date)->get() : null;

        if(!empty($data["schedule"] )){
            foreach($data["schedule"] as $program){
                $out = "<ul style='list-style:none'>";
                $out .= "<li><strong>ID:</strong> ".$program->id."</li>";
                $out .= "<li><strong>Type:</strong> ".$program->show->type->name."</li>";
                $out .= "<li><strong>Name:</strong> ".$program->show->name."</li>";
                $out .= "<li><strong>Time:</strong> ".$program->airdate."</li>";
                if($program->episode->name){
                    $out .= "<li><strong>Title:</strong> ".$program->episode->name."</li>";
                }
                if($program->episode->preview){
                    $out .= "<li><strong>Video:</strong> ".$program->episode->preview->brightcove_id."</li>";
                }
                if($program->episode->description){
                    $out .= "<li><strong>Description:</strong> ".$program->episode->description."</li>";
                }
                $debug[] = $out;
            }
        }

        return view('templates.output')->with(compact('debug'));
    }

    public function importSchedule($debug = false){
        $url = Config::get("ionpromotool")["api_endpoint"]."broadview/schedule/qubo";
        $response = cURL::newRequest('post', $url, ['token' => Config::get("ionpromotool")["api_token"]])->send();
        $results = json_decode($response->body);
        $output = [];

        if(!empty($results)){

            if(!$debug) Program::truncate();

            foreach($results as $data){

                if(in_array($data->CATEGORY,["Paid Programming","Religious Programming"])) continue;
                if(in_array($data->CATEGORY,["Movie"])) $data->ASS_EPISODENUMBER = 0;


                $show = Show::where('broadview_handle','=',$data->ASS_TITLE)->first();

                $program = new Program;
                $program->id = $data->TLN_ID;

                if($show && $show->id){
                    $program->show_id = $show->id;
                }else{

                    //If this is not a paid programming / religious programming
                    //We will need to create a new show and set it to inactive

                    switch($data->CATEGORY){
                        case "Regular Programming":
                        case "Kids Programming":

                            $output[] =("<br/> Show not found for ".$data->ASS_TITLE);
                            $output[] =("<br/> EP = ".$data->ASS_EPISODETITLE." on ". $data->TLN_DATETIME);

                            $show = New Show;
                            $show->name = $this->formattedShowName($data->ASS_TITLE);
                            $show->type_id = (is_null($show->description)) ? "special" : "show";
                            $show->description = $data->ASS_SYNOPSIS;
                            $show->broadview_handle = $data->ASS_TITLE;
                            $show->slug = Str::slug($show->name);
                            $show->active = 1;
                            if(!$debug) $show->save();
                            break;
                        case "Movie":

                            $output[] =("<br/> Movie not found for ".$data->ASS_TITLE);
                            $show = New Show;
                            $show->name = $this->formattedShowName($data->ASS_TITLE);
                            $show->type_id = "movie";
                            $show->description = $data->ASS_SYNOPSIS;
                            $show->broadview_handle = $data->ASS_TITLE;
                            $show->slug = Str::slug($show->name);
                            $show->active = 1;
                            if(!$debug) $show->save();

                            break;
                    }
                }

                $episode = Episode::where('show_id','=',$show->id)
                    ->where('episode_number','=',$data->ASS_EPISODENUMBER)
                    ->first();


                if(empty($episode)) $episode = new Episode;

                $episode->show_id = $show->id;
                $episode->episode_number = !empty($data->ASS_EPISODENUMBER) ? $data->ASS_EPISODENUMBER : 0;
                $episode->season = $this->formattedSeason($data->ASS_EPISODENUMBER);
                $episode->name = !empty($data->ASS_EPISODENUMBER) ? clean_chars($data->ASS_EPISODETITLE) : $this->formattedShowName($data->ASS_TITLE);
                $episode->description = clean_chars($data->ASS_SYNOPSIS);

                $episode->duration = $data->ASS_DURATIONSCHEDULED;
                $episode->rating = $data->RATING;

                try{
                    if(!$debug) $episode->save();
                    else{
                        $output[] = "<h2>".$show->name."</h2><h3>Episode: ".$episode->episode_number." | ".$episode->name."</h3>";
                        $output[] = "<strong>Description:</strong> ".$episode->description;
                    }
                }catch(Exception $e){}


                $program->airdate = Carbon::parse($data->TLN_DATETIME);

                if($debug) $output[] = ($program->airdate);

                $program->show_id = $show->id;
                $program->episode_id = $episode->id;

                if(!$debug) $program->save();
                else $output[] = "Program Created";
            }
        }

        $output[] .= '<h3 class="text-success">Finished  Successfully</h3>';
        $debug = $output;
        return view('templates.output')->with(compact('debug'));
    }
	
	public function formattedSeason($episodeNumber){
		return intval(trim(preg_replace("/[^0-9,.]/","", $episodeNumber)) / 99);
	}
	
	public function formattedShowName($showName){
		$showName = preg_replace('/[\(\[].+[\)\]]/','',strtoupper($showName));
		$pos =  strpos($showName,"; THE");
		if($pos !== false)  {$showName = "THE ".substr($showName,0,$pos);} //APPEND "THE" before
		$pos =  strpos($showName,", THE");
		if($pos !== false)  {$showName = "THE ".substr($showName,0,$pos);} //APPEND "THE" before 				
		$pos =  strpos($showName,"; A");
		if($pos !== false)  {$showName = "A ".substr($showName,0,$pos);} //APPEND "A" before
		$pos =  strpos($showName,", A");
		if($pos !== false)  {$showName = "A ".substr($showName,0,$pos);} //APPEND "A" before						
		$showName = trim(strtolower($showName));	
		return ucwords($showName);
	}

}