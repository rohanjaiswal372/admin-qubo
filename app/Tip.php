<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $table = 'tips';
	protected $primaryKey = 'id';
	protected $fillable = ['summary', 'video'];

	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }

    public function imageDefault(){
	    return $this->morphMany('App\Image', 'imageable')->where('type_id', '=', 'default');
    }

	public function video(){
		return $this->morphOne('App\Video', 'videoable');
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
}
