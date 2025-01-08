<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignAdvertisement extends Model
{
	use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'campaign_advertisements';
    protected $primaryKey = 'id';
    protected $fillable = ['advertisement_id'];


    public function ad()
    {
        return $this->hasOne('App\Advertisement','id','advertisement_id');
    }

    public function campaign(){

        return $this->belongsTo('App\Campaign', 'campaign_id');

    }

}
