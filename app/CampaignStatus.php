<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CampaignStatus extends Model
{
	use Traits\ActivityLog\ActivityLogTrait;	
	
    protected $table = 'campaign_statuses';
    protected $primaryKey = 'id';

    public function statusType(){

        return $this->hasOne('App\CampaignStatusType','id','status_id');

    }
}


