<?php namespace App\Http\Controllers;

use Auth;

use \Cache;
use \Storage;
use \URL;
use \Input;
use \Config;
use \Tmbd;
use \cURL;
use \DB;
use \Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use \App\Show;
use \App\Episode;
use \App\Video;
use \App\Image;
use \Croppa;
use Brightcove;
use Excel;

class EpisodeController extends Controller
{

	/**
	 * @var string
     */
	public $view_base = 'shows.episodes';

	/**
	 * Show the profile for the given user.
	 *
	 * @param  int $id
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

	/**
	 * @param $show_id
	 * @return mixed
	 */
	public function index($show_id)
	{
		$show = Show::get($show_id);
		return view($this->view_base . '.index')->with('show', $show);
	}

	/**
	 * @param null $episode_id
	 * @return mixed
	 */
	public function media($episode_id = NULL)
	{

		if (is_null($episode_id) && Input::has("show_id")) {
			$show_id = Input::get("show_id");
			$episodes = Show::find($show_id)->episodes;
		} else {
			$episodes = Episode::find($episode_id)->show->episodes;
			$show_id = Episode::find($episode_id)->show->id;
		}

		$show_id_selector = Show::series()->get()->pluck("name", "id")->toArray();

		$object_id_selector = [];
		foreach ($episodes as $episode) {
			$object_id_selector[$episode->id] = "EP " . $episode->episode_number . ": " . $episode->name;
		}
		$media_type_selector = Episode::image_types()->pluck("name", "id")->toArray();
		$crop_options_selector = ["T" => "Top", "B" => "Bottom", "L" => "Left", "R" => "Right", "C" => "Center"];
		return view($this->view_base . '.media.createmedia')->with(
			[
				"imageable_type" => "App\Episode",
				"object_id" => $episode_id,
				"object_id_selector" => $object_id_selector,
				"show_id" => $show_id,
				"show_id_selector" => $show_id_selector,
				"crop_options_selector" => $crop_options_selector,
				"media_type_selector" => $media_type_selector
			]);

	}

	/**
	 * @param Request $request
	 * @return mixed
     */
	public function getEpisodeImages(Request $request)
	{
		$episode = Episode::findOrFail($request->id);
		$html = view($this->view_base . '.partials.images')->with(compact('episode'))->render();
		return response()->json(array('success' => true, 'html' => $html));
	}

	/**
	 * @param null $show_id
	 * @return mixed
	 */
	public function create($show_id)
	{

		$show_id_selector = Show::series()->get()->pluck("name", "id")->toArray();

		(!is_null($show_id)) ? $show = Show::find($show_id) : $show = NULL;

		return view($this->view_base . '.new')->with(compact('show_id_selector'))->with(compact('show'));
	}

	/**
	 * @param Request $request
	 * @return mixed
     */
	public function store(Request $request){

		$show_id = $request->show_id;
		$show = Show::findOrFail($show_id);

		$episode = new Episode;
		$episode->show_id = $show->id;
		$episode->season = $request->season;
		$episode->name = $request->name;
		$episode->episode_number = $request->episode_number;
		$episode->rating = $request->rating;
		if($request->episode['new_episode_starts_at']) {
			$episode->new_episode_starts_at = $request->episode['new_episode_starts_at'];
			$episode->new_episode_ends_at = $request->episode['new_episode_ends_at'];
		}
		$episode->description = $request->description;
		$episode->save();

		if ($request->brightcove_id ) {
			$video = new Video;
			$video->videoable_id = $episode->id;
			$video->title = $episode->name;
			$video->videoable_type = 'App\Episode';
			$video->type_id = 'default';
			$video->brightcove_id = $request->brightcove_id;
			$video->save();
		}

		flash()->success("This episode has been created");
		return redirect('shows/episodes/' . $episode->id . '/edit');

	}

    /**
     * @param $id
     * @return mixed
     */
    public function edit($show_id,$episode_id)
    {
        $episode = Episode::findOrFail($episode_id);
        $show = $episode->show;

        $episode_buttons = ['prev' => $episode->previous()->id, 'next' => $episode->next()->id];

        return view($this->view_base . '.edit')->with(compact('episode_buttons','show','episode'));
    }


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param  int $id
	 * @return Response
	 */
    public function update(Request $request,$show_id , $episode_id)
    {
        $show = Show::findOrFail($show_id);
		$episode = Episode::findOrFail($episode_id);
		$episode->name = $request->episode['name'];
		$episode->season = $request->episode['season'];
		$episode->episode_number = $request->episode['episode_number'];
		$episode->rating = $request->episode['rating'];
		$episode->new_episode_starts_at  = ($request->episode['new_episode_starts_at'] != "")?
			Carbon::parse($request->episode['new_episode_starts_at'])->format('Y-m-d G:ia'):
			null;

		$episode->new_episode_ends_at = ($request->episode['new_episode_ends_at'] != "")?
			Carbon::parse($request->episode['new_episode_ends_at'])->format('Y-m-d G:ia'):
			null;

		$episode->description = $request->episode['description'];

		$episode->published_at = (($request->episode['published_at']) != "")? Carbon::parse($request->episode['published_at'])->format('Y-m-d G:ia') : null;

		//does the video already exsist
		if ($request->episode['brightcove_id']) {
			if (is_null($episode->preview)) {
				$video = new Video;
				$video->videoable_id = $id;
				$video->videoable_type = "App\Episode";
				$video->type_id = "default";
				$video->brightcove_id = $request->episode['brightcove_id'];
				$video->title = $request->episode['name'];
				$video->save();
				//Video::create(array_merge(['brightcove_id' => $request->episode['brightcove_id],$video_values));
			} else {
				$video = Video::findOrFail($episode->preview->id);
				$video->brightcove_id = $request->episode['brightcove_id'];
				$video->title = $request->episode['name'];
				$video->save();

			}
		}
		$episode->save();
		flash()->success("This episode has been updated");
		return redirect('shows/episodes/' . $episode->show->id);
	}

    /**
     * @param null $brightcove_id
     */
    public function updateThumbnail(Request $request, $video_id = null){

		if ($request->file('image')) {
			# image upload
			if (in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif', 'swf'])) {
				$file_name = '/episodes/' . $request->file('image')->getClientOriginalName();
				Image::upload(['model' => 'App\Episode',
							   'object_id' => $video_id,
							   'type' => 'upload',
							   'file' => $request->file('image'),
							   'destination' => $file_name]);
			}
		}

        $brightcove = Brightcove::updateThumbnail($video_id,[
            "file"=> null
        ]);
        dd($brightcove);  //todo:  this is a work in progress.  need to attach the image file to the request.
    }

	/**
	 * Export Excel file
	 *
	 * @return self
	 */
	public function export($type, $show_id = null)
	{

		ini_set('memory_limit', '1G');
		$episodes = (!is_null($show_id))?  Show::find($show_id)->episodes : Show::all()->episodes;
		$show = Show::findOrFail($show_id);

		Excel::create( preg_replace('/\s+/', '',$show->name) . date('m-d-Y_H.i.s'), function ($excel) use ($episodes, $show) {
			$excel->sheet('Episodes List', function ($sheet) use ($episodes, $show) {
				$entries = $episodes;
				$columns = array_keys($entries[0]->getAttributes());
				$sheet->loadView($this->view_base.'.export', ["columns" => $columns, "entries" => $entries, "show" => $show]);
			});

		})->download($type);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$episode = Episode::findOrFail($id);
		$episode->delete();
		flash()->error("Episode <strong>" . $episode->name . "</strong> has been deleted");
		return redirect()->back();
	}

}