<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertisementCategory extends Model
{
	use Traits\ActivityLog\ActivityLogTrait;	
	
    protected $table = 'advertisement_categories';
	protected $primaryKey = 'id';

	public function platform(){
		return $this->hasOne("App\AdvertisementPlatform","id","platform_id");
	}
	
}
