<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    use Traits\ActivityLog\ActivityLogTrait;
    
    protected $table = 'banners';
	protected $primaryKey = 'id';
	protected $fillable = ['title', 'path', 'active'];
	
	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }
	public function image(){
	    return $this->morphOne('App\Image', 'imageable')->orderBy('id','desc');
    }
}
