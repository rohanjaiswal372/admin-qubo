<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Ad;
use Auth;
use View;
use App\NielsenSource;

class NielsenController extends Controller
{

	public $view_base = 'nielsen';

    public function __construct()
    {
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
        $data = NielsenSource::orderBy('id', 'asc')->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }
    /**
     * Show the form for editing a new source.
     *
     * @return Response
     */
    public function edit($id)
    {
    	
    	$item = NielsenSource::findOrFail($id);
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
        $source = NielsenSource::findOrFail($id);
        $source->id = $request->id;
		$source->description = $request->description;
        $source->save();
        return redirect(route('nielsen.index'));
    }
    
     /**
     * Show the form for creating a new source.
     *
     * @return Response
     */
    public function create()
    {
        return view($this->view_base.'.create');
    }
    /**
     * Store a newly created source in storage.
     *
     * @param  Request  $request
     * @return Response
     */
     public function store(Request $request)
    {
        
        $source = new NielsenSource;
        $source->id = $request->id;
        $source->description = $request->description;
        
        $source->save();
        return redirect(route('nielsen.index'));
    }
    
    
     public function destroy($id)
    {
    	$id = NielsenSource::find($id);
    	$id ->delete();
    	return redirect(route('nielsen.index'));
        # archive?
    }
    
}