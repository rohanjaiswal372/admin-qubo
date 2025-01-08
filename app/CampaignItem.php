<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CampaignItem extends Model
{
	use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'campaign_items';
    protected $primaryKey = 'id';
    protected $fillable = ['itemable_id'];

    public function itemable(){

        return $this->morphTo();
    }

    public function ad()
    {
        return $this->hasOne('App\Advertisement','id','itemable_id');
    }

    public function post(){
        return $this->hasOne('App\Post','id','itemable_id');
    }

    public function pod(){
        return $this->hasOne('App\GridCell','id','itemable_id');
    }

    public function campaign(){

        return $this->belongsTo('App\Campaign', 'campaign_id');
    }

    public function getClassAttribute(){
        return substr($this->itemable_type, 4, strlen($this->itemable_type));
    }

    public static function make($request, $item){
        $class= get_class($item);
        if (!$item->campaign && $request->campaign_id) {
            $campaign_ad = CampaignItem::firstOrCreate([
                'itemable_id' => $item->id
            ]);
            $campaign_ad->campaign_id = $request->campaign_id;
            $campaign_ad->itemable_id = $item->id;
            $campaign_ad->itemable_type = $class;
            $campaign_ad->save();
            return $campaign_ad;
        } else {
            if ($request->campaign_id != -1) {
                $campaign_ad = CampaignItem::where('itemable_id', $item->id)->where('itemable_type', $class)->first();
                $campaign_ad->campaign_id = $request->campaign_id;
                $campaign_ad->itemable_id = $item->id;
                $campaign_ad->itemable_type = $class;
                $campaign_ad->save();
                return $campaign_ad;
            } else {
                $campaign_ad = CampaignItem::where('itemable_id', $item->id)->where('itemable_type', $class)->first();
                $campaign_ad->delete();

            }
        }
        return false;
    }
}
