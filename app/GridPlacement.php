<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GridPlacement extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'grid_placements';
	protected $primaryKey = 'id';

	public static function getList(){
		$items = GridPlacement::all();
		$final_list = [];
		foreach( $items as $item ){
			$final_list[$item->id] = $item->title;
		}
		return $final_list;
	}

	public function grids(){
		return $this->belongsToMany('App\Grid', 'grid_placement_pivot', 'placement_id', 'grid_id')->with('layout')->with('cells');
	}
	
	/*
	public static function get($path){
		$placement = GridPlacement::with('grids')->where('path', $path)->first();
		# we are only going to look at the first GRID pod returned
		# TODO: V2 we can work on a system for which pulls
		if( !$placement){
			return [];
		}

		# pull first
		$grid = $placement->grids->first();
		$cleaned_cells = Grid::cleanCells($grid);
		$cleaned_cells['display_title'] = ($grid) ? $grid->display_title : "";
		$cleaned_cells['layout'] = ($grid) ? $grid->layout->path : null;
				
		return $cleaned_cells;
	}
	*/
}
