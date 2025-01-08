<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'user_permissions';
	protected $primaryKey = "username";
	
	public function info(){
		return $this->hasOne("App\UserPermissionLevel","username","permission");
	}	
	
}
