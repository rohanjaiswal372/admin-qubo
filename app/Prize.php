<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    protected $table = 'prizes';
	protected $primaryKey = 'id';
	protected $fillable = ['title', 'active', 'stock', 'points'];

	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }

}
