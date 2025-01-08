<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Advertisement;
use \App\AdvertisementCategory;


class Recipe extends Model
{
    protected $table = 'recipes';
	protected $primaryKey = 'id';
	protected $fillable = ['title', 'path', 'active'];
	
	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }

    public function imageDefault(){
	    return $this->morphMany('App\Image', 'imageable')->where('type_id', '=', 'default');
    }

    public function mealtypes(){
    	return $this->hasOne('App\MealType', 'id', 'meal_type_id');
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
}
