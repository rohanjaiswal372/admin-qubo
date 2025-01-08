<?php namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use \Cache;
use \Storage;
use \Config;
use \Tmbd;
use \cURL;
use \DB;
use \Carbon;
use \Exception;
use \App\Program;
use \App\Show;
use \App\Cast;
use \App\Image;
use \App\ImageType;

class PhotoController extends Controller {

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

		$show = Show::get( is_null($show_id) ?  "criminal-minds": $show_id );
		$show_id_selector = Show::series()->pluck("name","id")->toArray();
		$image_types = ImageType::where("imageable_type",get_class($show))->get();
		return view('shows.photo-gallery')->with(["show"=>$show,
												  "show_id_selector"=>$show_id_selector,
												  "image_types"=>$image_types]);		
	}
		
	public function episodicIndex($show_id = null){

		$show = Show::get( is_null($show_id) ?  "criminal-minds": $show_id );
		$show_id_selector = Show::series()->pluck("name","id")->toArray();
					
		return view('shows.episodic-photo-gallery')->with(["show"=>$show,"show_id_selector"=>$show_id_selector]);		
	}
	
	public function deleteImage($image_id){
		$image = Image::find($image_id);
		$image->delete();
        flash()->success('This image has been deleted');		
		return redirect()->back();
	}

}