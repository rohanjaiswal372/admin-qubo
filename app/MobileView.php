<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Advertisement;
use \App\AdvertisementCategory;
use \Carbon;


class MobileView extends Model
{
    protected $table = 'IONMobile.dbo.views';
	protected $primaryKey = 'id';
	
	public static function get($id = null){
		if(is_null($id)) return;
		return self::select($id)->first();
	}	
	
	public function scopeSelect($query, $param){
		if(is_numeric($param)){
			$query->where("id","=",$param);
		}else{
			$query->where("slug","=",$param);
		}
	}
	
	
	public function sponsor_logo(){
        $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																   ->where("ends_at",">=", Carbon::now())
																   ->where("type_id",1)
																   ->whereIn('category_id', AdvertisementCategory::where("platform_id",2)->pluck("id")->toArray())
																   ->orderBy("id","desc");
																   
	    if (\App::environment('production')) $query->where("active",1);
		return $query;
	}
	
	public function sponsor_banner(){
		
         $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																	->where("ends_at",">=", Carbon::now())
																	->where("type_id",2)
																	->whereIn('category_id', AdvertisementCategory::where("platform_id",2)->pluck("id")->toArray())
																	->orderBy("id","desc");
																	
    	if (\App::environment('production')) $query->where("active",1);
		return $query;
	}
	
	public function ads(){
        $query =  $this->morphMany('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																	->where("ends_at",">=", Carbon::now())
																	->whereIn('category_id', AdvertisementCategory::where("platform_id",2)->pluck("id")->toArray())
																	->orderBy("id","desc");
																	
		if (\App::environment('production')) $query->where("active",1);
		return $query;
	}	
	
}
