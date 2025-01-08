<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Show;
use \App\GridPlacement;
use Carbon;

class Grid extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'grids';
	protected $primaryKey = 'id';
	/*
	public function cells(){
		return $this->hasMany("App\GridCell","grid_id","id")->with('images')->with('show');
	}
	*/

	public function layout(){
		return $this->hasOne('App\GridLayout', 'id', 'layout_id');
	}

	public function placements(){
		return $this->belongsToMany('App\GridPlacement', 'grid_placement_pivot', 'grid_id', 'placement_id');
	}

	public function cells($starts_at = null){
		if(get_class($starts_at) == "Carbon"){
			$starts_at = $starts_at->format("Y-m-d");
		}
		$result = $this->hasOne("App\GridCellSchedule","grid_id","id");

		if(!is_null($starts_at)){
			$result->where("starts_at","<=",$starts_at);
		}else{
			$result->where("starts_at","<=",Carbon::today());
		}
		return $result->with('cell')->orderBy('sort_order','asc')->orderBy('starts_at', 'desc')->orderBy('id','desc');
	}
	
	public function getCells($starts_at = null){
				
		if(get_class($starts_at) == "Carbon"){
			$starts_at = $starts_at->format("Y-m-d");
		}
		$results = $this->cells($starts_at)->get();		
		$cells = [];
		foreach($results as $key => $result){
			if(!isset($cells[$result->sort_order])){
				$result->cell->schedule_id = $result->id;
				$cells[$result->sort_order] = $result->cell;
			}
		}
		
		//*******
		for($i = 1; $i <= $this->layout->number_of_pods; $i++){
			if(!isset($cells[$i])){
				$cells[$i] = new GridCell;
			}
		}
		//*******
		ksort($cells);
		
		return $cells;
	}
	
	public static function get($id = null, $starts_at = null){
		if(is_null($id)) return;
		$grid =  self::select($id)->first();
		if($grid) $grid->cells = $grid->getCells($starts_at);
		return $grid;
	}
	
	public function scopeSelect($query, $param){
		if(is_numeric($param)){
			$query->where("id","=",$param);
		}else{
			$query->where("slug","=",$param);
		}
	}
	
	public function fetch(){
		$this->cells = $this->getCells();
		return $this;
	}
}
