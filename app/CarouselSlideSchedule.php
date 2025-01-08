<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarouselSlideSchedule extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'carousel_schedules';
	protected $primaryKey = 'id';
	protected $dates = ["starts_at","ends_at"];
	
	public function slide(){
		return $this->hasOne("App\CarouselSlide","id","slide_id");
	}
		
	public static function last_start_date(){
		return self::orderBy("starts_at","desc")->select("starts_at")->first();
	}
	
	public static function last_end_date(){
		return self::orderBy("ends_at","desc")->select("ends_at")->first();
	}
}
