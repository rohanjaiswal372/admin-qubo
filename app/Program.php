<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB;
use \Carbon;
use \URL;
use \App\Advertisement;
use \App\AdvertisementCategory;


class Program extends Model
{
    protected $table = 'programs';
	protected $primaryKey = 'id';
	protected $dates = ['airdate'];
	
	public function episode(){
		return $this->hasOne("App\Episode","id","episode_id");
	}
	
	public function show(){
		return $this->hasOne("App\Show","id","show_id");
	}
	
	public function scopePrimeTime($query, $date = null){
		$query->where("airdate",">=", date("Y-m-d H:i:s", strtotime(((is_null($date)?"today":$date))." 8:00 PM")))->orderBy("airdate","asc");
	}	
	
	public function scopeSchedule($query, $date = null){
		$start_date = (is_null($date)) ? "today":  $date;
		$end_date   = (is_null($date)) ? "tomorrow": date("Y-m-d", strtotime($start_date." +1 day"));		
		$query->where("airdate",">=", date("Y-m-d",strtotime($start_date))." 06:00:00")
			  ->where("airdate","<=", date("Y-m-d",strtotime($end_date))." 03:00:00")
			  ->orderBy("airdate","asc");		
	}
	
	public function scopeUpcoming($query){		
		$query->where("airdate",">=",date("Y-m-d H:i:s",strtotime("-30 min")))->orderBy("airdate","asc");		
	}
	
	public function scopeMovies($query){		
		$query->whereRaw("programs.show_id in (select id from shows where shows.type_id = 'movie') ");		
	}
	
	public function scopeHolidayMovies($query){	
		$query->whereRaw("programs.show_id in (select id from shows where shows.type_id = 'movie' and shows.holiday =  1) ");		
	}	
	
	public function date($format = null){
		
		$date = "";
		$day  = Carbon::parse($this->airdate->format("m/d/Y"));
		$hour = intval($this->airdate->format("G"));
		$current_hour = Carbon::now()->hour;
		$today = Carbon::today();
		
		if(is_null($format)){
			if( $day->eq(Carbon::today()) && $current_hour>=6){
				$date = ($hour < 18) ? "Today" : "Tonight";
			}else if( $day->eq(Carbon::tomorrow()) || ($day->eq(Carbon::today()) && $current_hour<6) ){
				$date = ($hour < 5) ? "Tonight" : "Tomorrow";
			}else{
				$date = $day->format("D, M j");
			}
		}else{
			$date = $this->airdate->format($format);
		}

		return $date;
	}	
	
	public function time($format = null){
		$time = "";
		$airdate_est = $this->airdate;		
		switch(true){
			case (!is_null($format)):
				$time = $airdate_est->format($format);
			break;
			default:
				$airdate_cst = clone $airdate_est;
				$airdate_cst->setTimezone('America/Chicago');
				$meridiem = ($airdate_est->format("A") == "AM" ) ? "AM" : "";
				$hour = [intval($airdate_est->format("g")),intval($airdate_cst->format("g"))];
				$minute = [intval($airdate_est->format("i")),intval($airdate_cst->format("i"))];
				$time = $hour[0].(($minute[0]>0)?":".$minute[0]:"")."|".$hour[1].(($minute[1]>0)?":".$minute[1]:"")."<span>c</span> <span class='meridiem'>".$meridiem."</span>";
			break;
		}
		return  $time;
	}

    public function date_and_time(){
        return $this->date()." ".$this->time();
    }

    public function getDateAndTimeAttribute(){
		return $this->date()." ".$this->time();
	}	
	
	public function url(){
		$date = ($this->airdate->hour < 6) ? $this->airdate->subDays(1):$this->airdate;
		return URL::to("schedule/".$date->format("Y-m-d")."/".$this->id);
	}
	
	public function isAiring(){
		return ( Carbon::now()->between($this->airdate,$this->airdate->addSeconds($this->episode->duration))) ? true : false;
	}
	
	public static function now(){
		$today = (Carbon::now()->hour < 6) ? Carbon::yesterday()->toDateString() : Carbon::today()->toDateString();
		$programs = Program::schedule($today)->get();
		$program = null;
		foreach($programs as $program){
			if($program->isAiring()) break;
		}
		return $program;
	}
	
	public static function next(){
		$today = (Carbon::now()->hour < 6) ? Carbon::yesterday()->toDateString() : Carbon::today()->toDateString();
		$programs = Program::schedule($today)->get();
		$program = null;
		foreach($programs as $key => $program){
			if($program->isAiring()) break;
		}
		return Program::where("airdate",">",$program->airdate)->orderBy("airdate")->first();
	}
	
	public static function tonight(){
		$today = (Carbon::now()->hour < 6) ? Carbon::yesterday()->toDateString() : Carbon::today()->toDateString();
		$programs = Program::schedule($today)->get();
		
		foreach($programs as $key => $program){
			//echo("<br/>".$program->show->name." ".$program->episode->name." ".$program->time());
		}
		//die();
		
		$program = null;
		$index = 0;
		foreach($programs as $key => $program){
			if($program->airdate->hour >= 20) break;
		}
		return [ $programs[$key], $programs[$key+1] ];
	}

	public function sponsor_logo(){
        $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																   ->where("ends_at",">=", Carbon::now())
																   ->where("type_id",1)
																   ->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->pluck("id")->toArray())
																   ->orderBy("id","desc");
	    if (\App::environment('production')) $query->where("active",1);
		return $query;
	}
	
	public function sponsor_banner(){
         $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																	->where("ends_at",">=", Carbon::now())
																	->where("type_id",2)
																    ->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->pluck("id")->toArray())
																	->orderBy("id","desc");
																	
    	if (\App::environment('production')) $query->where("active",1);
		return $query;
	}
	
	public function ads(){
        $query =  $this->morphMany('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																	->where("ends_at",">=", Carbon::now())
																    ->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->pluck("id")->toArray())
																	->orderBy("id","desc");
																	
		if (\App::environment('production')) $query->where("active",1);
		return $query;
	}	
		
	
}
