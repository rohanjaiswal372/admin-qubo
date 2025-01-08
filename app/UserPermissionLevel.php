<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermissionLevel extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'user_permission_levels';
	protected $primaryKey = "id";

}
