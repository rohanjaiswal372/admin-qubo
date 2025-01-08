<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\GridSchedule;
use App\Grid;
use App\Page;
use App\Show;
use Auth;
use Carbon;

class GridPlacementsController extends Controller
{

    public $view_base = 'grid-placements';

	
   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("content_management")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		$pages = Page::whereIn('id',  GridSchedule::pageIds() )->get()->sortBy("path");
		$shows = Show::whereIn('id',  GridSchedule::showIds() )->orderBy("type_id","desc")->orderBy("name")->get();
		$items = $pages->merge($shows);
        return view($this->view_base.'.index')->with(['items' => $items]);
    }
	
	public function show($type){
		
		$item = null;
		switch($type){
			case "page":
				$item = Page::find(\Request::input("id"));
			break;
			case "show":
			case "movie":
				$item = Show::find(\Request::input("id"));
			break;
		}
		
		$indexes =  GridSchedule::indexes($item);
		$dates = [];
		
		foreach($indexes as $index){
			$dates[$index] =   GridSchedule::dates($item,$index);
		}
				
        return view($this->view_base.'.edit')->with(['grids'=>Grid::all()->sortBy('title'),'item' => $item,'indexes'=>$indexes,'dates'=>$dates]);
	}
	
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view($this->view_base.'.create')->with(['grids'=>Grid::all()->sortBy('title')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
		
		$morphableType = function($type){
							 switch($type){
								 case "show":
								 case "movie":
									return "App\\Show";
								 case "page":
									return "App\\Page";
								 break;
							 }
							 return;
						  };
		
        if($request->schedule){
			$grid_schedule = new GridSchedule();
			$grid_schedule->sort_order = $request->schedule["sort_order"];
			$grid_schedule->morphable_type = $morphableType($request->schedule["morphable_type"]);
			$grid_schedule->morphable_id = $request->schedule["morphable_id"];
			$grid_schedule->grid_id = $request->schedule["grid_id"];
			$grid_schedule->starts_at = Carbon::parse($request->schedule["starts_at"]);
			$grid_schedule->save();
			flash()->success("This grid has been scheduled");
		}
        return redirect("grid-placements/".$request->schedule["morphable_type"]."?id=".$request->schedule["morphable_id"]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
		GridSchedule::findOrFail($id)->delete();
		flash()->success("This grid has been removed");
		return redirect()->back();
    }
	
	public function getMorphableIdSelector($type,$selected_item_id){
		
		switch($type){
			case "page":
				$items = Page::all()->sortBy("path");
			break;
			case "show":
				$items = Show::series()->get()->sortBy("name");
			break;
			case "movie":
				$items = Show::movies()->get()->sortBy("name");
			break;
		}
		
		return \View::make("grid-placements.selectors.morphable-id", ["type"=>$type,"items" => $items ,"selected_item_id" => $selected_item_id ] );
	}	
}
