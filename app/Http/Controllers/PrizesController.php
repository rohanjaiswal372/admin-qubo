<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Prize;
use Auth;
use App\Image;

class PrizesController extends Controller
{

    public $view_base = 'prizes';

	
   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("ion_lounge")) {
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
        $data = Prize::orderBy('title', 'asc')->get();
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
        
        $prize = new Prize;
        $prize->title = $request->title;
        $prize->stock = $request->stock;
        $prize->points = $request->points;
        
        if( $request->active == 'on' ){
            $prize->active = 1;
        }else{
            $prize->active = 0;
        }

        $prize->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $file_name = '/insiders/lounge/prizes/prize-'.$prize->id.'-image.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Prize', 'object_id' => $prize->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $file_name] );
            }
        }

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
        $item = Prize::with('images')->findOrFail($id);
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
        $prize = Prize::findOrFail($id);
        $prize->title = $request->title;
        $prize->stock = $request->stock;
        $prize->points = $request->points;
        
        if( $request->active == 'on' ){
            $prize->active = 1;
        }else{
            $prize->active = 0;
        }

        $prize->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $file_name = '/insiders/lounge/prizes/prize-'.$prize->id.'-image.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Prize', 'object_id' => $prize->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $file_name] );
            }
        }

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

    }
}
