<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Advertisement;
use \App\AdvertisementCategory;
use \Carbon;
use \URL;


class Page extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'pages';
	protected $primaryKey = 'id';
	protected $fillable = ['title', 'path', 'content'];
	
	public function getUrlAttribute(){
		return  str_replace(["https","dev-admin","admin"],["http","www","www"],URL::to("/".$this->path));		
	}
	
	public static function get($id = null){
		if(is_null($id)) $id = \Request::path();
		return self::select($id)->first();
	}	
	
	public function scopeSelect($query, $param){
		if(is_numeric($param)){
			$query->where("id","=",$param);
		}else{
			$query->where("path","=",$param);
		}
	}

	public function colors(){
		return $this->morphMany("App\Color","colorable");
	}
	
	public function sponsor_logo(){
        $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																   ->where("ends_at",">=", Carbon::now())
																   ->where("type_id",1)
																   ->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->pluck("id")->toArray())
																   ->orderBy("id","desc");
	    if (\App::environment('production')) $query->where("active",1);
		return $query;
	}
	
	public function sponsor_banner(){
         $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																	->where("ends_at",">=", Carbon::now())
																	->where("type_id",2)
																    ->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->pluck("id")->toArray())
																	->orderBy("id","desc");
																	
    	if (\App::environment('production')) $query->where("active",1);
		return $query;
	}
	
	public function ads(){
        $query =  $this->morphMany('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																	->where("ends_at",">=", Carbon::now())
																    ->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->pluck("id")->toArray())
																	->orderBy("id","desc");
																	
		if (\App::environment('production')) $query->where("active",1);
		return $query;
	}	

	public function grid($index = 0, $date = null){
		if( is_null($date) || get_class($date) != "Carbon\Carbon"){
			$date = (is_null($date)) ? Carbon::today()->format("Y-m-d") : Carbon::parse($date);
		} 
			
		$schedule =  GridSchedule::where("morphable_id",$this->id)
								->where("morphable_type",get_class($this))
								->where("sort_order",$index)
								->where("starts_at","<=",$date)
								->orderBy("starts_at","desc")
								->first();
								
		$grid =  ($schedule && $schedule->grid) ?  $schedule->grid->fetch() : null;
		if(!is_null($grid)) $grid->schedule_id = $schedule->id;
		return $grid;
	}
	
	public function grids( $date = null){
		
		$grids = collect();
		
		for($i = 0;$i < 5;$i++){
			$this_grid =  $this->grid($i);
			if($this_grid){
				$grids->push($this_grid);
			}
		}

		return $grids;
	}
}
