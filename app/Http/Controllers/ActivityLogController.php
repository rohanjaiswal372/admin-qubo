<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ActivityLog;
use Carbon;

class ActivityLogController extends Controller
{
    
	public $object_labels;
	
	public function __construct(){
		
		$this->object_labels = ["App\Page"=>"a page",
								"App\Post"=>"a blog post",
								"App\Episode"=>"an episode",
								"App\Show"=>"a show",
								"App\Advertisement"=>"an advertisement",
								"App\CampaignAdvertisement"=>"a campaign advertisement",
								"App\CampaignFollower"=>"a campaign follower",
								"App\CampaignStatus"=>"a campaign status",
								"App\Campaign"=>"a campaign",
                                "App\Sponsor"=>"a sponsor",
								"App\Banner"=>"a banner",
								"App\Campain"=>"a campaign",
								"App\Video"=>"a video",
								"App\Recipe"=>"a recipe",
								"App\Cast"=>"a cast member",
								"App\Image"=>"an image",
								"App\Grid"=>"a grid",
								"App\GridCell"=>"a pod",
								"App\GridCellSchedule"=>"a pod schedule",
								"App\GridSchedule"=>"a grid schedule",
								"App\Carousel"=>"a carousel",
								"App\CarouselSlide"=>"a carousel slide",
								"App\CarouselSlideSchedule"=>"a carousel slide schedule",
								"App\User"=>"a user",
								"App\UserPermission"=>"a user permission",
								"App\Grid"=>"a grid",
								"App\GridCell"=>"a grid cell",
								"App\GleamPage"=>"a gleam page",
								"App\RescanAlert"=>"a rescan alert"];
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($date = null)
    {
		$date =  $date ? Carbon::parse($date) : Carbon::today();
		$logs = ActivityLog::ofDate($date)->orderBy("created_at","desc")->paginate(50);
		$grouped_logs = $logs->groupBy('date');
		$object_labels = $this->object_labels;
        return view("activity-logs.index")->with(compact('logs','date','grouped_logs','object_labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
