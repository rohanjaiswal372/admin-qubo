<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \URL;
use \App\ImageType;

class Cast extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'casts';
	protected $primaryKey = 'id';	

	
	public static function get($id = null){
		if(is_null($id)) return;
		return self::select($id)->first();
	}
		
	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }
	
    public function image(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","default");
    }
	
    public function pod_image(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","pod");
    }

	public static function image_types(){
		return ImageType::where("imageable_type","=",__CLASS__)->get();
	}		
	
	public function url(){
		$base = ["ION"=>"show/","QUBO"=>"qubo-kids-corner/"];		
		switch($this->show->type_id){
			case "movie":
				$url = null;
			break;
			case "show":
			default:
				$url = URL::to($base[$this->show->scope].$this->show->slug."/cast/".$this->slug);
			break;
		}
		return $url;
	}	
	
	
	public function show(){
		return $this->hasOne("App\Show","id","show_id");
	}	
	
	public function scopeSelect($query, $param){
		if(is_numeric($param)){
			$query->where("id","=",$param);
		}else{
			$query->where("slug","=",$param);
		}
	}	
}
