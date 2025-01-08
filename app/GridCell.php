<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Advertisement;
use \App\AdvertisementCategory;


class GridCell extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'grid_cells';
	protected $primaryKey = 'id';
		
	public function grid(){
		return $this->hasOne("App\Grid","id","grid_id");
	}

	public function type(){
		return $this->hasOne("App\GridCellType","id","type_id");
	}

	public function creative(){
		return $this->hasOne("App\GridCreative","id","creative_id");
	}

	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }
    public function image(){
        return $this->morphOne('App\Image', 'imageable')->where('type_id','default')->orderBy("id","desc");
    }
    public function hover_image(){
        return $this->morphOne('App\Image', 'imageable')->where('type_id','hover-image')->orderBy("id","desc");
    }
	
	public function show(){
		return $this->hasOne('App\Show', 'id', 'show_id');
	}

	public function ad(){
        $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
																   ->where("ends_at",">=", Carbon::now())
																   ->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->pluck("id")->toArray())
																   ->orderBy("id","desc");
 	    if (\App::environment('production')) $query->where("active",1);
		return $query;  
    }

}
