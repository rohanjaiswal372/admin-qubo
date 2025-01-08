<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Carbon;
use Session;
use App\Grid;
use App\GridLayout;
use App\GridCellSchedule;

class GridsController extends Controller
{

    public $view_base = 'grids';

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
        $data = Grid::with('layout')->orderBy('title', 'asc')->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $grid_layouts = GridLayout::all();
        return view($this->view_base.'.create')->with(['grid_layouts' => $grid_layouts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {   
        $grid = new Grid;
        $grid->title = $request->title;
        $grid->display_title = $request->display_title;
        $grid->layout_id = $request->layout_id;

		
        if( $request->active == 'on' ){
            $grid->active = 1;
        }else{
            $grid->active = 0;
        }
        $grid->save();

        return redirect(route($this->view_base.'.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Grid::get($id);
        $grid_layouts = GridLayout::all();
        return view($this->view_base.'.edit')->with(['item' => $item, 'grid_layouts' => $grid_layouts]);
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

        //$menu->products()->detach($product_id);

        $grid = Grid::findOrFail($id);
        $grid->title = $request->title;
        $grid->layout_id = $request->layout_id;
        $grid->display_title = $request->display_title;
          		
        if( $request->active == 'on' ){
            $grid->active = 1;
        }else{
            $grid->active = 0;
        }

      
        $grid->save();
        return redirect(route($this->view_base.'.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $grid = Grid::findOrFail($id);
        $grid->delete();

        return redirect()->back();
    }
	
	/**
     * Add a new slide from the display page
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function addPods(Request $request, $id)
    {
        $grid = Grid::findOrFail($id);
        
		foreach($request->cell_id as $key => $cell_id){
			
			
			if($cell_id > 0){
				
				$grid_schedule = new GridCellSchedule;

				# save fields
				$grid_schedule->grid_id = $id;
				$grid_schedule->sort_order = $request->sort_order[$key];
				$grid_schedule->cell_id = $request->cell_id[$key];
				$grid_schedule->starts_at = Carbon::parse($request->starts_at[$key]);
				
				try{
					$grid_schedule->save();
				}catch(Exception $e){
					echo($e->getMessage());
				}				
				
			}

		}

        Session::flash('alert_success', 'The pods have been added.');

        return redirect('/pods/'.$id);
    }
    
	
    public function removePod($id)
    {
        $scheduled_pod = GridCellSchedule::findOrFail($id);
        $scheduled_pod->delete();
        return redirect()->back();

    }	
	
	
}
