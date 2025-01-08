<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GridCreative extends Model
{
    protected $table = 'grid_creatives';
	protected $primaryKey = 'id';
		

	public function type(){
		return $this->hasOne("App\GridCreativeType","id","type_id");
	}	
}
