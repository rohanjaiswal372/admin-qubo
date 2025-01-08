<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFeedbackSubject extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'user_feedback_subjects';
	protected $primaryKey = "id";

}
