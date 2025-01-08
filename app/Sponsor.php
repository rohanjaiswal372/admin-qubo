<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
	use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'sponsors';

    protected $fillable = ['name'];

	public function ads(){
	    return $this->hasMany('App\Advertisement', 'sponsor_id', 'id')->orderBy("id","desc");
	}

	public function getTotalAdsAttribute(){
		if(!is_null($this->ads))
			return ($this->ads->count() > 0)?  $this->ads->count() : null ;
		return null;
	}

	public function campaigns(){
		return $this->hasMany('App\Campaigns' , 'sponsor_id', 'id')->orderBy('id', 'desc');
	}

	public function images(){
		return $this->morphMany('App\Image', 'imageable');
	}

	public static function image_types(){
		return ImageType::where("imageable_type","=",__CLASS__)->get();
	}

	public function logo(){
		return $this->morphOne('App\Image', 'imageable')->where("type_id","=","logo")->orderBy("id","desc");
	}

//	public function getThumbnailAttribute(){
//		if(!is_null($this->logo))
//			return $this->logo->thumbnail(150,100);
//		return null;
//	}

}
