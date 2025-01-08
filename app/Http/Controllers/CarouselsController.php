<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Carousel;
use Auth;
use App\Image;
use App\CarouselSlide;
use App\CarouselSlideSchedule;
use App\CarouselSlideAllocation;
use App\Show;
use Input;
use Carbon;
use DB;
use Cache;
use Session;

class CarouselsController extends Controller
{

    public $view_base = 'carousels';

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
        $data = Carousel::orderBy('title', 'asc')->get();
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

        $carousel = new Carousel;
        $carousel->title = $request->title;
        $carousel->description = $request->description;
        $carousel->position = $request->position;
        $carousel->description = $request->description;
        if( $request->active  ){
            $carousel->active = 1;
        }else{
            $carousel->active = 0;
        }
        $start_end_check = $request->start_end_time;
        if( !empty($start_end_check) ) {
            $times = explode('-', $request->start_end_time);
            $carousel->starts_at = date('Y-m-d H:i:s', strtotime(trim($times[0])));
            $carousel->ends_at = date('Y-m-d H:i:s', strtotime(trim($times[1])));
        }
        $carousel->save();
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
        $item = Carousel::with('slides')->findOrFail($id);
        $slides = CarouselSlide::where('archived', 0)->orderBy("title")->pluck("title","id")->toArray();
        return view($this->view_base.'.edit')->with(['item' => $item, 'slides' => $slides]);
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
        $carousel = Carousel::findOrFail($id);
        $carousel->title = $request->title;
        $carousel->description = $request->description;
        if( $request->active  ){
            $carousel->active = 1;
        }else{
            $carousel->active = 0;
        }
        $carousel->position = $request->position;

        $schedule = $request->schedule;
        if( count($schedule) > 0 ){
            foreach( $schedule as $slide_id => $slide ){
                if( $slide['slide'] == 0 ) continue;
                if( $slide_id == 'new' ){
                    $slide_new = new CarouselSlideSchedule;
                }else{
                    $slide_new = CarouselSlideSchedule::findOrFail((int)$slide_id);
                }

                # save fields
                $slide_new->carousel_id = $id;
                $slide_new->sort_order = $slide['sort_order'];
                $slide_new->slide_id = $slide['slide'];
                $slide_new->starts_at = date('Y-m-d H:i:s', strtotime($slide['starts_at']));
                $slide_new->save();
            }
        }

        $carousel->save();

        return redirect('/carousels/'.$id.'/edit');
    }

    /**
     * Add a new slide from the display page
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function addSlides(Request $request, $id)
    {
        $carousel = Carousel::findOrFail($id);

        foreach($request->slide_id as $key => $slide_id){


            if($slide_id > 0){

                $slide_schedule = new CarouselSlideSchedule;

                # save fields
                $slide_schedule->carousel_id = $id;
                $slide_schedule->sort_order = $request->sort_order[$key];
                $slide_schedule->slide_id = $request->slide_id[$key];
                $slide_schedule->starts_at = Carbon::parse($request->starts_at[$key]);
                if($request->ends_at[$key]){
                    $slide_schedule->ends_at = Carbon::parse($request->ends_at[$key]);
                }

                try{
                    $slide_schedule->save();
                }catch(Exception $e){
                    echo($e->getMessage());
                }

            }


        }

        Session::flash('alert_success', 'The slides have been added.');

        return redirect('/carousels/display/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id){}

    public function display($id, $platform = null){


        $carousel = Carousel::findOrFail($id);
        $start_date = Carbon::yesterday();
        $end_date = null;

        $last_start_date  = CarouselSlideSchedule::last_start_date();
        $end_date = ($last_start_date && $last_start_date->starts_at->gt(Carbon::today())) ? $last_start_date->starts_at : Carbon::today();

        $all_slides = CarouselSlide::where('archived', 0)->orderBy("created_at", "desc")->limit(100);
        $recent_slides = CarouselSlide::where('archived', 0)->orderBy("id", "desc")->paginate(12);
        $carousels = array();

        while($end_date->gt($start_date)){
            $start_date->addDays(1);
            $carousels[$start_date->format("Y-m-d")] =  [ "slides" => $carousel->getSlides($start_date),
                                                          "id"     => $carousel->id,
                                                          "limit"  => $carousel->limit($start_date) ];
        }

        return view($this->view_base.'.display')->with(compact('carousel','platform','all_slides','carousels','recent_slides'));
    }

    public function updateAllocation(Request $request){

        $error_message = "";

        if($request->has('carousel_id') && $request->has('starts_at') && $request->has('limit')){

            $allocation = new CarouselSlideAllocation;
            $allocation->carousel_id = $request->input('carousel_id');
            $allocation->starts_at = $request->input('starts_at');
            $allocation->limit = $request->input('limit');
            $response = null;

            try{
                $response = $allocation->save();
            }catch(\Exception $e){
                $error_message = $e->getMessage();
            }

            if($response){
                return response()->json(['status' => 1 ,'message' => 'The carousel allocation has been updated successfully']);
            }else{
                return response()->json(['status' => 0 ,'message' => 'Sorry an unexpected error occurred', 'info'=>$error_message]);
            }
        }
        return response()->json(['status' => 0 ,'message' => 'Sorry an unexpected error occurred','info'=>'N/A', 'info'=>$error_message]);
    }

    /*

    NEW

    public function display($id){


        $carousel = Carousel::findOrFail($id);
        $start_date = Carbon::yesterday();
        $end_date = null;
        if($id == 1){
            $last_end_date  = CarouselSlideSchedule::last_end_date();
            $end_date = ($last_end_date && $last_end_date->ends_at->gt(Carbon::parse("next sunday"))) ? $last_end_date->ends_at : Carbon::parse("next sunday");
        }else{
            $last_start_date  = CarouselSlideSchedule::last_start_date();
            $end_date = ($last_start_date && $last_start_date->starts_at->gt(Carbon::today())) ? $last_start_date->starts_at : Carbon::today();
        }
        $all_slides = CarouselSlide::where('archived', 0)->get();
        $carousel_slides = array();

        while($end_date->gt($start_date)){
            $start_date->addDays(1);
            $carousel_slides[$start_date->format("Y-m-d")] = ($id == 1) ?  \CarouselPromoScheduler::getSlides($id,$start_date)  :  $carousel->getSlides($start_date);
        }

        return view($this->view_base.'.display')->with(['carousel' => $carousel, 'all_slides' => $all_slides,'carousel_slides' => $carousel_slides]);
    }


    */

}
