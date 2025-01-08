<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertisementPlatform extends Model
{
    protected $table = 'advertisement_platforms';
	protected $primaryKey = 'id';

	
	public static function get($id = null){
		if(is_null($id)) $id = \Request::path();
		return self::select($id)->first();
	}	
	
	public function scopeSelect($query, $param){
		if(is_numeric($param)){
			$query->where("id","=",$param);
		}else{
			$query->where("slug","=",$param);
		}
	}
	
	public function instanceOf($slug){
		return ($this->slug == $slug) ? true : false;
	}
	
	public static function id($slug){
		return Platform::get($slug)->id;
	}
}
