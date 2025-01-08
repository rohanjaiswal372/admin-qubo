<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\GridLayout;
use Auth;

class GridLayoutsController extends Controller
{

    public $view_base = 'grid-layouts';

	
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
        $data = GridLayout::orderBy('title', 'asc')->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view($this->view_base.'.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        $grid_layout = new GridLayout;
        $grid_layout->title = $request->title;
        if( empty($request->path) ){
            $grid_layout->path = str_slug($request->title);
        }else{
            $grid_layout->path = $request->path;
        }
        $grid_layout->save();

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
        $item = GridLayout::findOrFail($id);
        return view($this->view_base.'.edit')->with(['item' => $item]);
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
        $grid_layout = GridLayout::findOrFail($id);
        $grid_layout->title = $request->title;
        $grid_layout->path = $request->path;
        $grid_layout->save();

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
        $grid = GridLayout::findOrFail($id);
        $grid->delete();
        return redirect()->back();
    }
}
