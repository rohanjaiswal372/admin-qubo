<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Tags\TaggableTrait;
use Cartalyst\Tags\TaggableInterface;



class Game extends Model implements  TaggableInterface
{
    use Traits\ActivityLog\ActivityLogTrait;
	use TaggableTrait;

    protected $table = 'games';
	protected $primaryKey = 'id';
	protected $fillable = ['title', 'path', 'description', 'sort_order', 'active'];
	
	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }

    public function imagePrimary(){
	    return $this->morphMany('App\Image', 'imageable')->where('type_id', '=', 'primary');
    }
	public function imageDefault(){
		return $this->morphMany('App\Image', 'imageable')->where('type_id', '=', 'default');
	}
	public function scopeActive($query){
		$query->whereActive(1);
	}
	public function getUrlAttribute(){

		$base = "http://dev.qubo.com/";

		switch($this->scope){
			default:
				$url = $base.'games/play/'.$this->id;
				break;
		}
		return $url;
	}

}
