<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GridLayout extends Model
{

    use Traits\ActivityLog\ActivityLogTrait;
    
    protected $table = 'grid_layouts';
	protected $primaryKey = 'id';

	public static function getList(){
		$items = GridLayout::all();
		$final_list = [];
		foreach( $items as $item ){
			$final_list[$item->id] = $item->title;
		}
		return $final_list;
	}
}
