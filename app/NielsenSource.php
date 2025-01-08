<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NielsenSource extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;
    
    protected $table = 'nielsen_sources';
	protected $primaryKey = 'id';
	protected $fillable = ['id', 'description'];
	
	public static function get($id = null){
		if(is_null($id)) return;
		$id = str_replace("nielsen/","",$id);
		return self::select($id)->first();
	}
	
	public function url(){
		return "http://iontelevision.com/nielsen/".$this->id;
	}
	
	public function scopeSelect($query, $param){
		$query->where("id","=",$param);
	}
	
	
}
