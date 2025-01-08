<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Banner;
use Auth;
use App\Image;

class BannersController extends Controller
{

    public $view_base = 'banners';

	
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
        $data = Banner::with('image')->orderBy('title', 'asc')->get();
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
        
        $banner = new Banner;
        $banner->title = $request->title;
        $banner->url = $request->url;
        $banner->headline = $request->headline;
        $banner->tagline = $request->tagline;
        $banner->description = $request->description;
        $banner->headline_align = $request->headline_align;
        #if( empty($request->path) ){
        #    $banner->path = str_slug($request->title);
        #}else{
            $banner->path = $request->path;
        #}
        if( $request->active == 'on' ){
            $banner->active = 1;
        }else{
            $banner->active = 0;
        }
        $banner->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $file_name = '/banners/'.$banner->path.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Banner', 'object_id' => $banner->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $file_name] );
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
        $item = Banner::with('images')->findOrFail($id);
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
        $banner = Banner::findOrFail($id);
        $banner->title = $request->title;
        $banner->url = $request->url;
        $banner->headline = $request->headline;
        $banner->tagline = $request->tagline;
        $banner->description = $request->description;
        $banner->headline_align = $request->headline_align;
        if( $request->active == 'on' ){
            $banner->active = 1;
        }else{
            $banner->active = 0;
        }
        #if( empty($request->path) ){
        #    $banner->path = str_slug($request->title);
        #}else{
            $banner->path = $request->path;
        #}
        $banner->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $file_name = '/banners/'.$banner->path.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Banner', 'object_id' => $banner->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $file_name] );
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
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->back();
    }
}
