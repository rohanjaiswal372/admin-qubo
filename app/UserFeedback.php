<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'user_feedbacks';
	protected $primaryKey = "id";

}
