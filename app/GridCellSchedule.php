<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GridCellSchedule extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'grid_cell_schedules';
	protected $primaryKey = 'id';
	protected $dates = ["starts_at"];
	
	public function cell(){
		return $this->hasOne("App\GridCell","id","cell_id");
	}
	
	public static function last($grid_id = null){
		if(is_numeric($grid_id)){
			return self::where("grid_id",$grid_id)->orderBy("starts_at","desc")->select("starts_at")->first();
		}
		else return self::orderBy("starts_at","desc")->select("starts_at")->first();
	}
}
