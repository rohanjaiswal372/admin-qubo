<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Grid;
use App\GridCell;
use App\GridCellLayout;
use App\GridCellPlacement;
use App\Show;
use App\Image;

class GridCellsController extends Controller
{

    public $view_base = 'grid-cells';

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
        $data = GridCell::orderBy('sort_order', 'asc')->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $grid_layouts = GridCellLayout::all();
        $grid_placements = GridCellPlacement::getList();
        return view($this->view_base.'.create')->with(['grid_placements' => $grid_placements, 'grid_layouts' => $grid_layouts]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id, $pod)
    {
        $grid = Grid::with('placements')->with('layout')->findOrFail($id);
        $item = GridCell::with('images')->where('location', $pod)->where('grid_id', $id)->first();
        $show_select = Show::where('type_id', 'show')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $movie_select = Show::where('type_id', 'movie')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $selects = ['Shows' => $show_select, 'Movies' => $movie_select];

        return view($this->view_base.'.edit-new')->with(['item' => $item, 'grid' => $grid,
            'pod_number' => $pod, 'shows' => $selects]);
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {   
        $grid_parent = Grid::findOrFail($request->grid_id);

        $grid = new GridCell;
        $grid->title = $request->title;
        $grid->grid_id = $request->grid_id;
        $grid->location = $request->location;
        $grid->headline = $request->headline;
        $grid->tagline = $request->tagline;
        $grid->show_id = $request->show_id;
        $grid->hyperlink = $request->hyperlink;
        if( $request->pull_next_air == 'on' ){
            $grid->pull_next_air = 1;
        }else{
            $grid->pull_next_air = 0;
        }
        if( $request->hyperlink_target == 'on' ){
            $grid->hyperlink_target = 1;
        }else{
            $grid->hyperlink_target = 0;
        }
        $grid->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif', 'swf']) ){
                $destination = '/pod-graphics/'.$grid_parent->path.'/'.$grid->location.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\GridCell', 'object_id' => $grid->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $destination] );
            }
        }

        return redirect('/grid-cells/'.$request->grid_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $grid_parent = Grid::findOrFail($request->grid_id);

        $grid = GridCell::findOrFail($id);
        $grid->title = $request->title;
        $grid->location = $request->location;
        $grid->headline = $request->headline;
        $grid->tagline = $request->tagline;
        $grid->show_id = $request->show_id;
        $grid->hyperlink = $request->hyperlink;
        if( $request->pull_next_air == 'on' ){
            $grid->pull_next_air = 1;
        }else{
            $grid->pull_next_air = 0;
        }
        if( $request->hyperlink_target == 'on' ){
            $grid->hyperlink_target = 1;
        }else{
            $grid->hyperlink_target = 0;
        }
        $grid->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif', 'swf']) ){
                $destination = '/pod-graphics/'.$grid_parent->path.'/'.$grid->location.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\GridCell', 'object_id' => $grid->id, 'file' => $request->file('image'), 'destination' => $destination] );
            }
        }

        return redirect('/grid-cells/'.$request->grid_id);
    }

    /**
     * Shows current grid pods
     *
     * @param  int $id
     * @return Response
     */
    public function show($id){
        $grid = Grid::with('placements')->with('layout')->with('cells')->findOrFail($id);
        $cells = Grid::cleanCells($grid);
        return view($this->view_base.'.show')->with(['grid' => $grid, 'cells' => $cells]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $grid = GridCell::findOrFail($id);
        $grid->delete();

        return redirect()->back();
    }
}
