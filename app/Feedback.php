<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'user_feedbacks';
	protected $primaryKey = 'id';

	protected $fillable = [
		'subject_id',
		'firstname',
		'lastname',
		'email',
		'birthyear',
		'phone',
		'city',
		'state',
		'zipcode',
		'market',
		'channel_number',
		'provider',
		'format',
		'newsletter',
		'message'
	];
	
	public function subject(){
		return $this->hasOne('App\FeedbackSubject', 'id', 'subject_id');
	}
	
}
