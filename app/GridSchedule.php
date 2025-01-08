<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class GridSchedule extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'grid_schedules';
	protected $primaryKey = 'id';
	protected $dates = ["starts_at"];
	
	public function grid(){
		return $this->hasOne("App\Grid","id","grid_id");
	}
	
	public static function pageIds(){
		return  self::where('morphable_type','App\Page')->select('morphable_id')->distinct()->get()->pluck('morphable_id')->toArray();
	}
	
	public static function showIds(){
		return  GridSchedule::where('morphable_type','App\Show')->select('morphable_id')->distinct()->get()->pluck('morphable_id')->toArray();
	}
	
	public static function indexes($item){
		return	self::where("morphable_id",$item->id)
					->where("morphable_type",get_class($item))
					->select("sort_order")
					->orderBy("sort_order","asc")
					->distinct()
					->get()
					->pluck("sort_order")->toArray();
	}
	
	public static function dates($item,$index = 0){
		$dates =  [Carbon::today()];
		$future_dates = self::where("morphable_id",$item->id)
							->where("morphable_type",get_class($item))
							->where("starts_at",">",Carbon::today())
							->where("sort_order",$index)
							->select("starts_at")
							->distinct()
							->orderBy("starts_at","asc")
							->get()
							->pluck("starts_at")->toArray();
		return !empty($future_dates) ? array_merge($dates,$future_dates) : $dates;
	}
}
