<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Background;
use Auth;
use App\Image;

class BackgroundController extends Controller
{

    public $view_base = 'backgrounds';

	
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
        $data = Background::with('image')->orderBy('title', 'asc')->get();
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
        
        $background = new Background;
        $background->title = $request->title;
   

        $background->path = $request->path;
        
        if( $request->active == 'on' ){
            $background->active = 1;
        }else{
            $background->active = 0;
        }
        $background->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $file_name = '/banners/'.$background->path.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Background', 'object_id' => $background->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $file_name] );
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
        $item = Background::with('images')->findOrFail($id);
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
        $background = Background::findOrFail($id);
        $background->title = $request->title;
       
        if( $request->active == 'on' ){
            $background->active = 1;
        }else{
            $background->active = 0;
        }

        $background->path = $request->path;
        
        $background->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $file_name = '/banners/'.$background->path.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Background', 'object_id' => $background->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $file_name] );
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
        $background = Background::findOrFail($id);
        $background->delete();
        return redirect()->back();
    }
}
