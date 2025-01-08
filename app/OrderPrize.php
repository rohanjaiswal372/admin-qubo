<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPrize extends Model
{
    protected $table = 'order_prizes';
	protected $primaryKey = 'id';
	protected $fillable = ['order_id', 'prize_id', 'quantity', 'points'];

	public function prize(){
		return $this->belongsTo('App\Prize', 'prize_id', 'id');
	}

}	
