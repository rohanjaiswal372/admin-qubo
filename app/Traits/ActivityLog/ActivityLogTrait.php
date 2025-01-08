<?php namespace App\Traits\ActivityLog;

use \Auth;
use \Carbon;
use \App\ActivityLog;

trait ActivityLogTrait
{
	public static function boot(){
		
		parent::boot();
		
		static::created(function($object){
			$object->log("create");
		});
		
		static::updating(function($object){
			$object->log("update");
		});
		
		static::deleting(function($object){
			$object->log("delete");
		});
		
	}
	
	public function activity_logs(){
		return $this->morphMany(ActivityLog::class,'morphable');
	}
	
	public function log($action, $user_id = null, $diff = null){
		$user_id = $user_id ?: Auth::id();
		$diff = $diff ?: $this->getActivityDiff();		
		$log = new ActivityLog();
		$log->fill( ["user_id" => $user_id,"action"=>$action] + $diff );
		$this->activity_logs()->save($log);
	}
	
	public function getActivityDiff(){
		$fresh = null;

		try{
			$fresh = $this->fresh() ? $this->fresh()->getAttributes() : null;
		}catch(\Exception $e){}
		
		$changed =  $this->getDirty();		
		$before  = !is_null($fresh) ? json_encode( !empty($changed) ? array_intersect_key($fresh,$changed) : $fresh) :null;
		$after   = json_encode($changed);
		return compact('before','after');
	}
}