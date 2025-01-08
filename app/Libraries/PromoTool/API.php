<?php  namespace App\Libraries\PromoTool;

use \Config;
use \cURL;
use \Carbon;
use \Cache;

Class API {

	const api_endpoint =  "https://api.ionpromotool.com/"; # api_endpoint url
	private static $token = null;
	
	function __construct()
	{

	}
	
	public static function init(){
		self::$token = Config::get('services.promo-tool.key');
	}
	
	public static function getProgrammingSchedule($scope = "qubo"){
		self::init();
		$url = self::api_endpoint."broadview/schedule/{$scope}";
		$response = cURL::newRequest('post', $url, ['token' => self::$token ])->send();
		return  json_decode($response->body);		
		
	}
	
	public static function getPromoScheduleStatistiques($date = null,$sort_order = null){
		self::init();

		$sortPercentages =  function($stat1,$stat2) use ($sort_order){ return ( ($sort_order =="asc") ? $stat1->percentage > $stat2->percentage : $stat1->percentage < $stat2->percentage ) ? true : false; };
		
		$date = is_null($date) ? Carbon::today()->format("Y-m-d") : Carbon::parse($date)->format("Y-m-d");
		
		$url = self::api_endpoint."promos/schedule/{$date}";
		
		$response = cURL::newRequest('post', $url, ['token' => self::$token ])->send();
		
		$results = json_decode($response->body);
				
		if($results->stats){
			usort($results->stats , $sortPercentages );
			foreach($results->stats as $key => $data){
				if(!is_null($data->show)){
					$results->stats[$key]->show->info = \App\Show::where("code",$data->show->code)->first();
				}
			}
			
			return $results->stats;		
		}
		
		return null;
	}
	
	public static function getBroadviewHandles($scope = 'qubo'){
		if(Cache::has("broadview_handles")){
			$handles = Cache::get("broadview_handles");
		}else{
			
			$schedule = self::getProgrammingSchedule($scope);
			$data =	array_values(
								array_filter(
									array_unique(
										array_map(function($value){ 
											if(in_array($value->CATEGORY , ["Regular Programming","Kids Programming"])) return $value->ASS_TITLE; 
										}, $schedule )
									)
								)
						);
						
			$handles =  array_combine($data,$data);
			
			Cache::put("broadview_handles",$handles,60*24);
		}
		
		return $handles;
	}
	
}