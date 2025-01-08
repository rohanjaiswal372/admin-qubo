<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermissionGroup extends Model
{
    protected $table = 'user_permission_groups';
	protected $primaryKey = "id";
	
	public function info(){
		return $this->hasOne("App\UserPermissionLevel","id","permission");
	}	
	
	public function group(){
		return $this->hasOne("App\UserGroup","id","user_group");
	}	
	
}
