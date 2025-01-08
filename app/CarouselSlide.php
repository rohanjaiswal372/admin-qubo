<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarouselSlide extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'carousel_slides';
	protected $primaryKey = 'id';
	
	public function show(){
		return $this->hasOne("App\Show","id","show_id");
	}
	
	public function image(){
	    return $this->morphOne('App\Image', 'imageable')->where("type_id","default")->orderBy("id","desc");
    }
	
	public function mobile_image(){
	    return $this->morphOne('App\Image', 'imageable')->where("type_id","mobile")->orderBy("id","desc");
	}

	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }

    public function schedules(){
    	return $this->hasMany('App\CarouselSlideSchedule', 'slide_id', 'id');
    }
    public function getComscoreFieldsAttribute(){
        return [
            "showName" => ($this->show)? $this->show->title : $this->title,
            "episodeTitle" => $this->title,
            "episodeNumber" => $this->id,
            "videoType" => "CarouselSlide",
            "rating" => "*null",
            "season" => "*null"
        ];
    }
}
