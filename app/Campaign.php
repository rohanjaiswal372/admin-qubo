<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
use PDF;
use Actuallymab\LaravelComment\Commentable;

class Campaign extends Model
{
	use Traits\ActivityLog\ActivityLogTrait,Commentable;

    protected $table = 'campaigns';
    protected $primaryKey = 'id';

    protected $canBeRated = true;
    protected $mustBeApproved = false;


    /*
     *  id
     * sponsor_id
     * description
     * starts_at
     * ends_at
     * updated_at
     * created_at
     * user_id
     * status  -> pending| approved | rejected
     */

    public function morphable()
    {
        return $this->morphTo();
    }

    public function sponsor(){
        return $this->hasOne("App\Sponsor","id","sponsor_id");
    }
    public function scopeApproved($query){
        $query->where("approved",1);
    }
    public function getCanceledAttribute(){
        if($this->status->status_id == "canceled")
            return TRUE;
                else return FALSE;
    }
    public function getPendingCreativesAttribute(){
        if($this->status->status_id == "pending-creatives")
            return TRUE;
        else return FALSE;
    }
    public function getPendingClientAttribute(){
        if($this->status->status_id == "pending-client-approval")
            return TRUE;
        else return FALSE;
    }

    public function getStatusLabelAttribute(){
        switch($this->status->status_id) {
            case('approved'):
                return 'success';
                break;
            case('pending-creatives'):
                return 'info';
                break;
            case('pending-client-approval'):
                return 'warning';
                break;
            case('canceled'):
                return 'danger';
                break;
            default:
                return 'default';
                break;
        }
    }

    public function approver(){
        return $this->hasOne('App\User', 'id', 'approver_id');
    }

    public function advertisements(){
        return $this->hasMany("App\CampaignItem","campaign_id","id");
    }

    public function items(){
        return $this->hasMany("App\CampaignItem","campaign_id","id");
    }

    public function followers(){
        return $this->hasMany("App\CampaignFollower","campaign_id","id");
    }

    public function images(){
        return $this->morphMany('App\Image', 'imageable');
    }
    public function documents(){
        return $this->morphMany('App\Document', 'documentable');
    }

    public function status(){
        return $this->hasOne('App\CampaignStatus','campaign_id','id')->orderBy("updated_at", "desc");
    }

    public function owner(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function getRunningAttribute(){
        return Carbon::now()->gte(Carbon::parse(date('m/d/Y g:i A', strtotime($this->starts_at))));
    }

    public function getExpiredAttribute(){
        return Carbon::now()->gte(Carbon::parse(date('m/d/Y g:i A', strtotime($this->ends_at))));
    }
    public function scopeEnded($query){
        $query->orderBy('ends_at', 'desc')->whereBetween("ends_at", [Carbon::now()->subYear(), Carbon::now()]);
    }

    public function scopeThisWeek($query){
        $query->orderBy('starts_at', 'desc')->whereBetween("starts_at", [Carbon::now()->startOfWeek(), Carbon::now()->startOfWeek()->addDays(6)]);
    }

    public function scopeNextWeek($query){
        $query->orderBy('starts_at', 'desc')->whereBetween("starts_at", [Carbon::now()->startOfWeek()->addDays(7), Carbon::now()->startOfWeek()->addDays(13)]);
    }

    public function scopeRecent($query){
        $query->whereBetween("starts_at", [Carbon::now()->subMonth(), Carbon::now()->addMonths(3)])->orderBy('starts_at', 'asc');
    }
    public function getPreviewDownloadUrlAttribute(){
		return url("campaigns/download-preview/".$this->id);
	}
	
	public function downloadPreview(){
		$campaign = $this;
		$file_name = "campaign-".str_slug($this->name).".pdf";
		$file_path = public_path("uploads/{$file_name}");
		
		try{
			
			if(!file_exists($file_path)){
				$pdf = PDF::loadView('campaigns.previews.index', ["campaign"=>$this] );
			    $pdf->save($file_path);	
			}
			
			return response()->download($file_path, $file_name);
	
		}catch(\Exception $e){
			echo($e->getMessage());
		}
		return null;
	}

}