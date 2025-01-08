<?php namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Cache;
use \Storage;
use \Config;
use \Tmbd;
use \cURL;
use \DB;
use \Carbon;
use App\Image;
use View;

use \App\Show;
use \App\Cast;

class CastController extends Controller {

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
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


	public function index($show_id = null){
        if($show_id != null){
            $show = Show::get($show_id);
            return view('shows.casts.index')->with('show', $show);
        }
        else return redirect('shows');

	}

	public function media($cast_id){
        $cast = Cast::findOrFail($cast_id);
		$object_id_selector = $cast->show->casts->pluck("name","id")->toArray();
		$media_type_selector = Cast::image_types()->pluck("name","id")->toArray();
        return view('shows.casts.media.create')->with([ "imageable_type"=>"App\Cast",
														"object_id" =>$cast_id,
														"object_id_selector" =>$object_id_selector,
														"media_type_selector"=>$media_type_selector,
                                                        "cast" => $cast]);

	}

    public function create($show_id){
		$show_id_selector = [ "Shows" => Show::series()->get()->pluck("name","id")->toArray(),
							  "Movies" => Show::movies()->get()->pluck("name","id")->toArray()];
        return view('shows.casts.create')->with(["show_id_selector"=>$show_id_selector,"show_id"=>$show_id]);
    }

	public function store(Request $request){
        $cast = new Cast;
        $cast->name = $request->name;
		$cast->slug = str_slug($request->name);
        $cast->real_name = $request->real_name;
        $cast->sort_order = $request->sort_order;
        $cast->show_id = $request->show_id;
        $cast->description = $request->description;
//        $cast->facebook_handle = $request->facebook_handle;
//        $cast->twitter_handle = $request->twitter_handle;
//        $cast->instagram_handle = $request->instagram_handle;
        if ($request->active == 'on') {
            $cast->active = 1;
        } else {
            $cast->active = 0;
        }
        $show = Show::findOrFail($request->show_id);

        if( $request->file('image') ) {
            $this->uploadCastImage($request->file('image'),$show,$cast);
        }
        if( $request->file('image_sm') ) {
            $this->uploadCastImage($request->file('image'),$show,$cast);
        }
        $cast->save();
        flash()->success('Cast Member has been created');
        return redirect("shows/casts/{$cast->id}/edit");
	}

    function uploadCastImage($file, $show,$cast)
    {
            if( in_array($file->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $destination = '/shows/'.$show->name.'/casts/'.$cast->name.'-image-'.time().'.'.$file->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Cast', 'object_id' => $cast->id, 'file' => $file, 'destination' => $destination,
                                'type_id' => 'default'] );

        }

    }
    function getCastImages($id){
        $cast = Cast::findOrFail($id);

        return View::make("shows.partials.images", ["item" => $cast]);
    }
    function getRemoveImages($id, $itemid){
        Image::findOrFail($id)->delete();
        $cast = Cast::findOrFail($itemid);

        return View::make("shows.partials.images", ["item" => $cast]);
    }


    public function update(Request $request, $id)
    {
        $cast = Cast::findOrFail($id);

        $cast->name = $request->name;
        $cast->real_name = $request->real_name;
		$cast->slug = str_slug($request->name);
        $cast->show_id = $request->show_id;
        $cast->sort_order = $request->sort_order;
        $cast->description = $request->description;
        if ($request->active == 'on') {
            $cast->active = 1;
        } else {
            $cast->active = 0;
        }

        $cast->save();
        return redirect("shows/casts/{$cast->show->id}");
    }

	public function edit($id)
	{
		$cast = Cast::findOrFail($id);
        $object_id_selector = $cast->show->casts->pluck("name","id")->toArray();
        $media_type_selector = Cast::image_types()->pluck("name","id")->toArray();
		$show_id_selector = [
            "Shows" => Show::series()->get()->pluck("name","id")->toArray(),
			"Movies" => Show::movies()->get()->pluck("name","id")->toArray()
        ];

		return view('shows.casts.edit')->with([
            "cast"=>$cast,
            "imageable_type"=>"App\Cast",
            "show_id_selector"=>$show_id_selector,
            "object_id" =>$cast->id,
            "object_id_selector" =>$object_id_selector,
            "media_type_selector"=>$media_type_selector
        ]);
	}

	public function show($id)
	{
	    $cast = Cast::findOrFail($id);
	    //return view('shows.index')->withShows($show);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cast = Cast::findOrFail($id);
        $cast->delete();

        flash()->error("This Cast member has been removed");

        return redirect()->back();
    }


}