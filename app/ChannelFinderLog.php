<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \URL;
use \Agent;

class ChannelFinderLog extends Model
{
    protected $table = 'channel_finder_logs';
    protected $primaryKey = 'id';

    public function __construct(){
        $this->user_agent =  $_SERVER["HTTP_USER_AGENT"];
    }


    public static function add($zipcode =null){

        if(is_null($zipcode)) return;

        $location = "";
        $lat = "";
        $lng = "";
        $geocode = @file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address={$zipcode}&sensor=false");
        if(!empty($geocode)){
            $geocode_json = json_decode($geocode);
            $location = $geocode_json->results[0]->formatted_address;
            $lat = $geocode_json->results[0]->geometry->location->lat;
            $lng = $geocode_json->results[0]->geometry->location->lng;
        }

        $agent = new Agent();

        $log = new ChannelFinderLog();
        $log->smartphone = ($agent->isMobile()) ? 1: 0;
        $log->device_name = ($agent->isMobile()) ? strtolower($agent->platform()) : "browser";
        $log->zipcode = $zipcode;
        $log->location = $location;
        $log->lat = $lat;
        $log->lng = $lng;
        $log->save();
    }


}