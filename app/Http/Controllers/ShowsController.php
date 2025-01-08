<?php
namespace App\Http\Controllers;

use Auth;

use Cache;
use Storage;
use Config;
use Tmbd;
use cURL;
use DB;
use Carbon;
use Illuminate\Http\Request;
use View;

use Illuminate\Support\Collection;
use App\Video;
use App\Image;
use \App\Show;
use App\Color;
use App\ColorType;
use UserSetting;
use App\Libraries\Brightcove\BrightcoveCMS;

class ShowsController extends Controller
{

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

    public function index()
    {
        $shows= Show::orderBy('active','desc')->get();
        return view('shows.list')->with(compact('shows'));
    }

    public function media($show_id)
    {
        $object_id_selector = Show::series()->get()->pluck("name", "id")->toArray();
        $media_type_selector = Show::image_types()->pluck("name", "id")->toArray();

        # grab episodes
        $show = Show::get($show_id);
        $episodes = $show->episodes()->get();
        $episodes_list = [];
        if (count($episodes) > 0) {
            foreach ($episodes as $epi) {
                $episodes_list[$epi->id] = 'EP ' . $epi->episode_number . ' -  ' . $epi->name;
            }
        }

        return view('shows.media.create')->with(["object_id_selector" => $object_id_selector, "show" => $show, "media_type_selector" => $media_type_selector, "episodes" => $episodes_list]);
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function store(Request $request)
    {
        $rgb = function ($hex) { return sscanf($hex, "#%02x%02x%02x"); };
        $show = new Show;
        $show->name = $request->name;
        $show->short_name = $request->short_name;
        $show->description = $request->description;
        $show->scope = $request->scope;
        $show->subtitle = $request->subtitle;
        $show->broadview_handle = $request->broadview_handle;		
        $show->code =  substr($request->code,0 ,4);
        $show->slug = str_slug($request->name);
        $show->color = $request->color;
        $show->scope = "QUBO";
        if ($request->color) {
            $show->color_rgb = $rgb($request->color);
        }

        $show->position = $request->position;

        $show->type_id = "show";
        if ($request->color) {
            $show->color_rgb = $rgb($request->color);
        }
        if ($request->new == 'on') {
            $show->new = 1;
        } else {
            $show->new = 0;
        }

        if ($request->active == 'on') {
            $show->active = 1;
        } else {
            $show->active = 0;
        }
        $show->save();
        foreach ($request->colors as $color) {
            $showcolor = New Color;
            $showcolor->type_id = $color['type_id'];
            $showcolor->code = $color['code'];
            $showcolor->colorable_id = $show->id;
            $showcolor->colorable_type = 'App\Show';
            $showcolor->save();
        }
        if ($request->brightcove_id) {
            $video = new Video;
            $video->videoable_id = $show->id;
            $video->title = $show->name;
            $video->videoable_type = 'App\Show';
            $video->type_id = 'default';
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        }

        return redirect(route('shows.index'));
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
        $show = Show::findOrFail($id);
        $show->name = $request->name;
        $show->short_name = $request->short_name;
        $show->description = $request->description;
        $show->scope = $request->scope;
        $show->broadview_handle = $request->broadview_handle;		
        $show->code = $request->code;
        $show->slug = $request->slug;
        $show->color = $request->color;
        if ($request->color) {
            $show->color_rgb = $rgb($request->color);
        }
        $show->subtitle = $request->subtitle;
        $show->headline = $request->headline;
        $show->position = $request->position;
        $show->sort_order = (int)$request->sort_order;
        if ($request->new == 'on') {
            $show->new = 1;
        } else {
            $show->new = 0;
        }
        if ($request->active == 'on') {
            $show->active = 1;
        } else {
            $show->active = 0;
        }
        if ($request->holiday == 'on') {
            $show->holiday = 1;
        } else {
            $show->holiday = 0;
        }
        foreach ($request->colors as $color) {

            Color::updateOrCreate(['id' => $color['id']], ['type_id' => $color['type_id'], 'code' => $color['code'], 'colorable_id' => $id ,'colorable_type' => 'App\Show']);
        }


        if ($request->brightcove_id) {
            if (is_null($show->preview)) {
                $video = new Video;
                $video->videoable_id = $id;
                $video->videoable_type = "App\Show";
                $video->type_id = "default";
                $video->brightcove_id = $request->brightcove_id;
                $video->title = $request->name;
                $video->save();
                //Video::create(array_merge(['brightcove_id' => $request->brightcove_id],$video_values));
            } else {
                $video = Video::findOrFail($show->preview->id);
                $video->brightcove_id = $request->brightcove_id;
                $video->title = $request->name;
                $video->save();

            }
        }

        $show->save();

        flash()->success("This Show has been updated");

        return redirect('shows/'.$show->id.'/edit');
    }

    public function getShowImages($id)
    {
        $show = Show::findOrFail($id);
        return view("shows.partials.images", ["item" => $show])->render();
    }

    function getRemoveImages($id, $itemid)
    {
        Image::findOrFail($id)->delete();
        $show = Show::findOrFail($itemid);
        return view("shows.partials.images", ["item" => $show])->render();
    }

    public function create()
    {
        $color_types = ColorType::orderBy('sort_order')->get();
        return view('shows.new')->with(compact('color_types'));
    }

    public function edit($id)
    {
        $show = Show::findOrFail($id);
        $shows = Show::all();
        $color_types = ColorType::orderBy('sort_order')->get();
        $show->video = ($show->preview)? BrightcoveCMS::videoSources($show->preview->brightcove_id)[2]->src : "";

        foreach ($color_types as $type) {
            $check = Color::where('colorable_id', '=', $id)->where('type_id', '=', $type->id)->where('colorable_type', '=', 'App\Show')->first();
            if (!$check) {
                $temp_color = new Color;
                $temp_color->type_id = $type->id;
                $temp_color->code = '#ffffff';
                $temp_color->colorable_id = $id;
                $temp_color->colorable_type = 'App\Show';
                $temp_color->save();
            }
        }

        return view('shows.edit')->withShow($show)->withShows($shows);
    }

    public function featured($id)
    {
        $show = Show::findOrFail($id);
        if ($show->featured) {
            $show->featured = 0;
            $show->save();
            return 'false';
        } else {
            $show->featured = 1;
            $show->save();
            return 'true';
        }

    }

    public function show($id)
    {
        $show = Show::findOrFail($id);
        return $this->index();
    }

    /**
     * @param $brightcove_id
     * @return bool
     */
    public function destroy($id)
    {
        $show = Show::findOrFail($id);
        $show->delete();

        flash()->error("show has been removed");

        return $this->index();

    }

}