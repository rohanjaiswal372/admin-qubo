<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\ChannelFinderLog;
use App\Station;
use Auth;
use DB;
use Carbon;
use Cache;

/* Station Locations - use Station */

class ChannelFinderController extends Controller
{
    public $view_base = 'channel-finder';

    public function __construct()
    {
        if (Auth::check() && !Auth::user()->hasPermission("channel_finder")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($year = null){

        if(is_null($year)) $year = date('Y');		
        return view($this->view_base . ".index")->with($this->get_charts($year));

    }

    private function get_charts($year)
    {
				
        ini_set('memory_limit', '1G');
	
		$stations_data = Station::get();
        $map_years_data =  array_column(ChannelFinderLog::select(DB::raw('distinct YEAR(created_at) as value'))->orderBy("value","desc")->get()->toArray(),"value");

		$dates = Carbon::create($year);
		$data = ChannelFinderLog::select(['lat','lng'])->whereBetween("created_at", [$dates->startOfYear()->toDateString(), $dates->endOfYear()->toDateString()])->get();

        $device_types = ChannelFinderLog::select(DB::raw('distinct device_name as value'))->get();

		$counts = [];

		foreach ($device_types as $device_type => $value) {

			$count = ChannelFinderLog::whereBetween("created_at", [$dates->startOfYear()->toDateString(), $dates->endOfYear()->toDateString()])->select('device_name')->where('device_name', '=', $value["value"])->count();
			if ($count > 25) $counts[] = ["y" => $count, "name" => $value['value']];

		}

        return ['data' => $data, 'stations_data' => $stations_data, 'map_years' => $map_years_data, 'this_year' => $year, 'chart' => $counts];
    }



}