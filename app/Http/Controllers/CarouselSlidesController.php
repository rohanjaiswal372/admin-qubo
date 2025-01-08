<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Carousel;
use Auth;
use App\Image;
use App\CarouselSlide;
use App\Show;
use App\CarouselSlideSchedule;
use File;

class CarouselSlidesController extends Controller
{

    public $view_base = 'carousel-slides';

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
    public function index($id = null)
    {
        $data = CarouselSlide::where('active',1)->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $show_select = Show::where('type_id', 'show')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $movie_select = Show::where('type_id', 'movie')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $selects = ['Shows' => $show_select, 'Movies' => $movie_select];
        
        return view($this->view_base.'.create')->with(['shows' => $selects]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $slide = new CarouselSlide;
        $slide->title = $request->title;
        $slide->show_id = $request->show_id;
        $slide->url = str_replace(["http://qubo.com/","http://www.qubo.com/"],"/",$request->url);
        $slide->headline = $request->headline;
        $slide->tagline = $request->tagline;
        $slide->headline_align = $request->headline_align;
        $slide->text_color = $request->text_color;
		
        if( $request->active == 'on' ){
            $slide->active = 1;
        }else{
            $slide->active = 0;
        }
		
		if( $request->dynamic == '1' && $slide->show_id ){
            $slide->dynamic = 1;
			CarouselSlide::where("show_id",$slide->show_id)->update(["dynamic" => 0]);
        }else{
            $slide->dynamic = 0;
        }
		
        if( $request->pull_next_air == 'on' ){
            $slide->pull_next_air = 1;
        }else{
            $slide->pull_next_air = 0;
        }


        $slide->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
				
				//detect the image dimemsions to determine if it is a web or mobile image
				// web images should be  1920 x 700.   1920/700 = 2.74285714286
				// mobile images should be  1536 x 2045.  1536/2045 = 0.75110024449
				$image_info = getimagesize($request->file('image'));
				
                $file_name = '/carousels/'.strtotime("now")."-".sha1($request->file('image')->getClientOriginalName()).".".$request->file('image')->getClientOriginalExtension();
				
				$size_ratio = (int) round($image_info[0]/$image_info[1],5);
				
				
                Image::upload( ['model' => 'App\CarouselSlide',
								'object_id' => $slide->id,
								'type_id'=> ($size_ratio > 0) ? "default":"mobile" ,
								'type' => 'upload',
								'file' => $request->file('image'),
								'destination' => $file_name] );
            }
        }

        return redirect('/carousel-slides');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {   
        $slide = CarouselSlide::with('images')->with('schedules')->findOrFail($id);

        $show_select = Show::where('type_id', 'show')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $movie_select = Show::where('type_id', 'movie')->where('active', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $selects = ['Shows' => $show_select, 'Movies' => $movie_select];


        return view($this->view_base.'.edit')->with(['slide' => $slide, 'shows' => $selects]);
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
        $slide = CarouselSlide::findOrFail($id);
        $slide->title = $request->title;
        $slide->show_id = $request->show_id;
        $slide->url = str_replace(["http://qubo.com/","http://www.qubo.com/"],"/",$request->url);
        $slide->headline = $request->headline;
        $slide->tagline = $request->tagline;
        $slide->headline_align = $request->headline_align;
        $slide->text_color = $request->text_color;		
        
		if( $request->active == 'on' ){
            $slide->active = 1;
        }else{
            $slide->active = 0;
        }
		
		if( $request->dynamic == '1' && $slide->show_id ){
            $slide->dynamic = 1;
			CarouselSlide::where("show_id",$slide->show_id)->update(["dynamic" => 0]);
        }else{
            $slide->dynamic = 0;
        }		
		
        if( $request->pull_next_air == 'on' ){
            $slide->pull_next_air = 1;
        }else{
            $slide->pull_next_air = 0;
        }
        
       /** $start_end_check = $request->start_end_time;
        if( !empty($start_end_check) ) {
            $times = explode('-', $request->start_end_time);
            $slide->starts_at = date('Y-m-d H:i:s', strtotime(trim($times[0])));
            $slide->ends_at = date('Y-m-d H:i:s', strtotime(trim($times[1])));
        }**/

        $slide->save();

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
				
				//detect the image dimemsions to determine if it is a web or mobile image
				// web images should be  1920 x 700.   1920/700 = 2.74285714286
				// mobile images should be  1536 x 2045.  1536/2045 = 0.75110024449
				$image_info = getimagesize($request->file('image'));
				
                $file_name = '/carousels/'.strtotime("now")."-".sha1($request->file('image')->getClientOriginalName()).".".$request->file('image')->getClientOriginalExtension();
				
				$size_ratio = (int) round($image_info[0]/$image_info[1],5);				
				
                Image::upload(['model' => 'App\CarouselSlide',
								'object_id' => $slide->id,
								'type_id'=> ($size_ratio > 0) ? "default":"mobile" ,
								'type' => 'upload',
								'file' => $request->file('image'),
								'destination' => $file_name]);
            }
        }

        return redirect('/carousel-slides/edit/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $slide = CarouselSlide::findOrFail($id);
        $slide->delete();
        flash()->success('The slide has been removed from the schedule.');
        return redirect()->back();
    }

    public function show($id)
    {
        $carousel = Carousel::findOrFail($id);
        $slides = CarouselSlide::with('show')->where('carousel_id', $id)->get();
        return view($this->view_base.'.show')->with(['carousel' => $carousel, 'slides' => $slides]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroySchedule($id)
    {
        $slide = CarouselSlideSchedule::findOrFail($id);
        $slide->delete();
        return redirect()->back();

    }
}