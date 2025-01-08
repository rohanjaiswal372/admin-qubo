<?php

namespace App{
	
	use Illuminate\Database\Eloquent\Model;
	use \URL;

	class Contest extends Model
	{
		protected $table = 'Contests.dbo.contest_tables';
		protected $primaryKey = 'id';


		public static function get($id = null){
			if(is_null($id)) return;
			return self::select($id)->first();
		}	
		
		public function scopeSelect($query, $param){
			if(is_numeric($param)){
				$query->where("id","=",$param);
			}else{
				$query->where("slug","=",$param);
			}
		}	
		
		public function entries(){
			$data = new \App\Contest\Data();
			return  $data->get((($this->database)? $this->database.".dbo." :"Contest.dbo.").$this->contest_table)->whereRaw(" 1=1 ")->get();
		}
		
	}
	
}



namespace App\Contest{
	
	use Illuminate\Database\Eloquent\Model;	
	
	class Data extends Model
	{
		protected $table = '';
		protected $primaryKey = 'id';
		
		public function get($table){
			$this->table = $table;
			return $this;
		}
		
	}
	
}