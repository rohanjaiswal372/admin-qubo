<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CampaignStatusType extends Model
{
    protected $table = 'campaign_status_types';
    protected $primaryKey = 'id';
    public $incrementing = false;

}

