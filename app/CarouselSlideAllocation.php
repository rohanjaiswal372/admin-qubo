<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class CarouselSlideAllocation extends Model
{
	
	use Traits\ActivityLog\ActivityLogTrait;
	
    protected $table = 'carousel_slide_allocations';
	protected $primaryKey = 'id';
	protected $dates = ["starts_at","created_at","updated_at"];
	protected $fillable = ["carousel_id","limit","starts_at"];
	
	public function carousel(){
		return $this->hasOne("App\Carousel","id","carousel_id");
	}
	
	public function setStartsAtAttribute($value){
		$this->attributes['starts_at'] = (is_string($value)) ? Carbon::parse($value) : $value;
	}
	
}
