<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
	protected $primaryKey = 'id';

	function prizes(){
		return $this->hasMany('App\OrderPrize', 'order_id', 'id')->with('prize');
	}

}	
