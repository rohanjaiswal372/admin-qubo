<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon;
use \App\ShowPreview;
use \App\EpisodePreview;
use \App\Show;
use \App\Episode;
use \App\VideoType;
use \Input;
use \App\Video;
use App\Libraries\Brightcove\Brightcove;
use App\Libraries\Brightcove\BrightcoveCMS;
use Auth;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware("auth.ion");

        if (Auth::check() && !Auth::user()->hasPermission("programming")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

    public function index($show_id)
    {
        $Shows = Show::All()->where("type_id", "show");
        $show = Show::find($show_id);
        return view('shows.videos.index')->with(["show" => $show, "Shows" => $Shows]);
    }

    public function media($episode_id = NULL)
    {

        if (is_null($episode_id) && Input::has("show_id")) {
            $show_id = Input::get("show_id");
            $show = Show::find($show_id);
            $episodes = Show::find($show_id)->episodes;
            $media_type_selected = 1;
        } else {
            $episodes = Episode::find($episode_id)->show->episodes;
            $show_id = Episode::find($episode_id)->show->id;
            $show = Show::find($show_id);
            $media_type_selected = 2;
        }

        $show_select = Show::where('type_id', 'show')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $movie_select = Show::where('type_id', 'movie')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $show_id_selector = ['Shows' => $show_select, 'Movies' => $movie_select];

        $object_id_selector = [];
        foreach ($show->episodes as $episode) {
            $object_id_selector[$episode->id] = "EP " . $episode->episode_number . ": " . $episode->name;
        }

        $media_type_selector = VideoType::all()->pluck("name", "id")->toArray();

        return view('shows.videos.media.create')->with(["videoable_type" => ["App\Show", "App\Episode"], "object_id" => $episode_id, "object_id_selector" => $object_id_selector, "show_id" => $show->id, "show_id_selector" => $show_id_selector, "media_type_selected" => $media_type_selected, "media_type_selector" => $media_type_selector]);
    }

    public function mediaBulk($show_id = NULL)
    {

        $show = Show::get($show_id);

        $object_id_selector = Show::series()->get()->pluck("name", "id")->toArray();

        return view('shows.videos.media.create-bulk')->with(["object_id" => (isset($show->id)) ? $show->id : "", "object_id_selector" => $object_id_selector]);
    }


    public function edit($id)
    {
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Origin: "*"');

        $video = Video::findOrFail($id);

        $brightcove_id = $video->brightcove_id;

        $brightcove_info = BrightcoveCMS::video($brightcove_id);

        $brightcove_info->all_time_plays = BrightcoveCMS::videoAnalytics($brightcove_id, 'alltime')->alltime_video_views;


        $last_month_plays = BrightcoveCMS::videoAnalytics($brightcove_id, 'engagement',['from' => Carbon::now()->subMonth(1), 'to' => Carbon::now()])->timeline->values;
        $brightcove_info->last_month_plays = max($last_month_plays);

        $brightcove_video_sources = BrightcoveCMS::videoSources($brightcove_id);

        $brightcove_info->renditions = $brightcove_video_sources;

        $brightcove_info->secureVideo = $brightcove_video_sources[2]->src;


        if (!is_null($brightcove_info)) {
            return view('videos.edit')->with(compact('video'))->with(compact('brightcove_info'));
        } else {
            $debug[]= "This Video has not finished compiling on Brightcove.  Please check back in later.";
            $debug[]= $video->toArray();
            flash()->error("This Video has not finished compiling on Brightcove.  Please check back later.");
            return view('templates.output')->with(compact('debug'));
        }

    }

    public function getSecureVideoURL($brightcove_id){
        $video_url = BrightcoveCMS::videoSources($brightcove_id)[2]->src;
        return $video_url;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // update brightcove video info and or thumbnail

    }

    public function listAll()
    {
        $videos = Video::all();
        return view('videos.index')->with(compact('videos'));
    }

    public function updateVideoFields(Request $request, $id)
    {
        $brightcove_id = $request->brightcove_id;
        $custom_fields = $request->custom_fields;

        $params = ['id' => $brightcove_id, 'name' => $request->name, 'tags' => explode(",", $request->tags), 'shortDescription' => ($request->description) ? $request->description : "", 'longDescription' => ($request->long_description) ? $request->long_description : "", 'customFields' => $custom_fields];

        $response = Brightcove::update($params, 'update_video');
        //$response = Brightcove::updateCustomFields($custom_fields, $brightcove_id); //used this to test just updating custom fields .
        if ($response->result) {
            $video = Video::findOrFail($id);
            $video->updated_at = Carbon::now();
            $video->save();
            $videos = Video::where('updated_at', '>=', Carbon::now()->subMonths(2))->orderBy('updated_at', 'asc')->get();
            flash()->success('Video fields have been updated.');
            return view('videos.index')->with('videos', $videos);
        } else {
            dd($reponse);
        }

    }


    public function processVideos($videoType = 2, $show_id = NULL)
    {
        // used for updating video meta data on Brightcove - Video Type for show/episode  will process all if specific show id is not sent.
        ///EX: https://dev-admin-cmichaels.iontelevision.com/shows/videos/process/2/264    | shows/videos/process/videoType/showid  1= episodes 2= shows
        $this->middleware("auth.ion");
        $debug = [];

        if (Auth::check() && !Auth::user()->hasPermission("brightcove")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        } else {

            $videoType = VideoType::where('id', '=', $videoType)->first();

            if (is_null($show_id)) {
                $shows = Show::series()->active()->get();
            } else {
                $shows = Show::series()->active()->Where('id', '=', $show_id)->get();
            }

            $videoType = str_replace('App\\', '', $videoType->videoable_type);

            if ($videoType == "Episode") {
                $videoType = "Promos";

                foreach ($shows as $show) {
                    $episodes = $show->episodes;
                    echo "<h1>" . $show->name . "Episodes:" . count($episodes) . "</h1>";
                    foreach ($episodes as $episode) {

                        if ($episode->preview && $episode->preview->brightcove()) {

                            $custom_fields = Brightcove::videoCustomFields($episode->preview->brightcove_id);
                            $params = ["id" => $episode->preview->brightcove_id, "name" => $episode->name, "shortDescription" => str_limit($episode->description, 240), "customFields" => ["showName" => ($episode->show->name) ? $episode->show->name : "*null", "videoType" => $videoType, "episodeTitle" => ($episode->name) ? $episode->name : "*null", "episodeNumber" => ($episode->episode_number) ? $episode->episode_number : "*null", "rating" => ($episode->rating) ? $episode->rating : "*null", "season" => ($episode->season) ? $episode->season : "*null"]];

                            if ($custom_fields->customFields) {
                                $brightcove = Brightcove::update($params, "update_video");

                                if ($brightcove->result)
                                    $debug[] = "Episode:" . $episode->episode_number . ": <span style='color:green'>UPDATED</span><br>";
                            } else {
                                $debug[] = "Episode:" . $custom_fields->customFields->episodenumber . ":" . $custom_fields->customFields->episodetitle . "<br>";

                            }

                        }
                    }
                }
            } elseif ($videoType == "Show") {
                $videoType = "Promos";
                foreach ($shows as $show) {

                    if ($show->preview && $show->preview->brightcove()) {
                        $custom_fields = Brightcove::videoCustomFields($show->preview->brightcove_id);
                        $params = ["id" => $show->preview->brightcove_id, "name" => $show->name, "shortDescription" => str_limit($show->description, 240), "customFields" => ["showName" => $show->name, "videoType" => $videoType, "episodeTitle" => "Show Promo", "episodeNumber" => "*null", "rating" => ($show->rating) ? $show->rating : "*null", "season" => ($show->season) ? $show->season : "*null"]];

                        if (!$custom_fields->customFields) {
                            $brightcove = Brightcove::update($params, "update_video");

                            if ($brightcove->result) {
                                $debug[] = "Show:" . $show->name . ": <span style='color:green'>UPDATED</span><br>";
                            } else {
                                $debug[] = $show->name . ": Error Occurred";
                            }
                        } else {
                            $debug[] = "Show:" . $custom_fields->customFields->showname . ":" . $custom_fields->customFields->videotype . "<br>";

                        }

                    }

                }

            }

            return view('templates.output')->with(compact('debug'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $video = Video::findorFail($id);
        $video->delete();
        $brightcove = Video::remove($video->brightcove_id);

        if ($brightcove)
            flash()->error("Video has been deleted from the show page and Brightcove"); else flash()->error("Video has been deleted from the show page");
        return redirect()->back();
    }
}
