<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB;
use \Config;

class SocialPost extends Model
{
    protected $table = 'social_posts';
	protected $primaryKey = 'id';	
	protected $dates = ['date'];

	public static function about($keyword = null){
		if(is_null($keyword)) self::select();
		return self::select($keyword);
	}
	
	public function url(){
		return Config::get("social")[$this->type_id."_url"];
	}
	
	public function scopeLatest($query){
		$query->orderBy("date","desc")->get();
	}
	
	public function scopeSelect($query, $keywords = null){
		if(!is_null($keywords)){
			if(is_array($keywords)){
				foreach($keywords as $keyword){
					$query->where("content","like","%{$keyword}%");
				}
			}else if (is_string($keywords)) $query->where("content","like","%{$keywords}%");
		}
		$query->orderBy("date","desc")->get();
	}
	
	public function contentHasURL(){
		
	}
	
	public function contentURL(){
		
	}
}
