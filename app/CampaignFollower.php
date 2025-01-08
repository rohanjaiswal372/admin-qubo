<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignFollower extends Model
{
	use Traits\ActivityLog\ActivityLogTrait;	
	
    protected $table = 'campaign_followers';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function morphable()
    {
        return $this->morphTo();
    }

    public function user()
        {
            return $this->hasOne('App\User','id','user_id');
        }
    public function campaigns(){

        return $this->hasMany('App\Campaign','id', 'campaign_id')->orderBy('starts_at','asc');
    }

}
