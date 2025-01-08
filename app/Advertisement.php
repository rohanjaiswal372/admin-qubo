<?php

namespace App;

use Carbon;
use URL;

use Illuminate\Database\Eloquent\Model;
use App\AdvertisementPlatform as Platform;

class Advertisement extends Model
{
	
	use Traits\ActivityLog\ActivityLogTrait;
	
    protected $table = 'advertisements';
	protected $primaryKey = 'id';
	
    public function morphable()
    {
        return $this->morphTo();
    }	
	
	public function sponsor(){
		return $this->hasOne("App\Sponsor","id","sponsor_id");
	}
	
	public function category(){
		return $this->hasOne("App\AdvertisementCategory","id","category_id");
	}
	
	public function type(){
		return $this->hasOne("App\AdvertisementType","id","type_id");
	}

    public function campaignItem(){

        return $this->morphOne('App\CampaignItem', 'itemable')->where('itemable_type',get_class($this));
    }

	public function campaign(){
        return $this->morphOne('App\CampaignItem', 'itemable')->where('itemable_type',get_class($this));
	}

	public function getPlatformAttribute(){
		return $this->category->platform;
	}

	public function getAdItemNameAttribute(){

	    $name = $this->category->name. ": ";

        if($this->morphable && $this->morphable->name){
            $name .= str_limit($this->morphable->name,40);
        }
        elseif($this->morphable && $this->morphable->title){
            $name .= str_limit($this->morphable->title,40);
        }
        elseif(get_class($this->morphable) == "App\Program"){
            if($this->morphable && $this->morphable->show){
                if($this->morphable->show->type_id == "show"){
                    $name .= "Show".$this->morphable->show->name.", EP ".$this->morphable->episode->episode_number." ".$this->morphable->episode->name;
                }
                elseif($this->morphable->show->type_id == "movie"){
                    $name .= "Movie:".$this->morphable->show->name;
                }
                $name .= "(".$this->morphable->date()." ".$this->morphable->time().")";
            }
            else{
                $name = "Expired";
            }
        }
         elseif(!$this->morphable) {
             $name .= "This Ad has not been associated with any item";
         }
        return $name;
    }
	
	public function image(){
	    return $this->morphOne('App\Image', 'imageable')->orderBy('id', 'desc');
    }
	
	public function video(){
	    return $this->morphOne('App\Video', 'videoable')->orderBy('id', 'desc');
	}

    public function scopeActive($query){
        $query->whereActive(1);
    }

    public function scopeRecent($query){
        $query->orderBy('starts_at', 'desc')->whereBetween("starts_at", [Carbon::now()->subMonth(), Carbon::now()->addMonths(3)]);
    }

	public function scopeExpired($query){
		$query->orderBy('ends_at', 'desc')->whereBetween("ends_at", [Carbon::now()->subYear(), Carbon::now()]);
	}

	public function getExpiredAttribute(){
		return Carbon::now()->gte(Carbon::parse(date('m/d/Y g:i A', strtotime($this->ends_at))));
	}

	public function getRunningAttribute(){
		return Carbon::now()->gte(Carbon::parse(date('m/d/Y g:i A', strtotime($this->starts_at))));
	}

	public function scopeThisWeek($query){
		$query->where('starts_at', '>', Carbon::now()->subDays(7))->orderBy('created_at', 'desc');
	}

	public function scopeLastWeek($query){
		$query->where('starts_at', '<', Carbon::now()->subDays(7))->orderBy('created_at', 'desc');
	}

	public function advertisedItem(){

		return $this->hasOne($this->morphable_type, 'id', 'morphable_id');
	}

	public function getAdUrlAttribute(){

			return $this->advertisedItem()->url();

	}
	
	public function getPreviewUrlAttribute(){
		if(empty($this->morphable)) return "No Preview Available";
		switch(get_class($this->morphable)){
			case "App\Show":
			case "App\Post":
			case "App\Page":
			case "App\Banner":
					return url("/advertisements/preview/".$this->id);
				break;
			case "App\GridCell":
			case "App\Tip":
			default:
					return "No Preview Available";
			break;
		}
	}
	
	public function previewExists(){
		return (file_exists(public_path('uploads/ion-television-ad-preview-'.$this->id.'.png'))) ? true : false;
	}
	
	public function getRemotePreviewPathAttribute(){
		switch(get_class($this->morphable)){
			case "App\Show":
				$path = "/show/{$this->morphable->slug}/ad-preview/".$this->id;
			break;
			case "App\Post":
			case "App\Page":						
				$path = $this->morphable->base_path.$this->morphable->path."/ad-preview/".$this->id;
			break;
			case "App\GridCell":
            $path = $this->morphable->base_path.$this->morphable->path."/ad-preview/".$this->id;
            break;
            case "App\Banner":
            case "App\Tip":
            $path = "";
            break;
			default:
				$path = "";
			break;
		}		
		return $path;
	}
	
	public function generatePreview(){
		if( in_array($this->platform->slug,['mobile','ipad'])){
			shell_exec("phantomjs --ssl-protocol=any ".public_path()."\\js\\phantomjs\\ion-television-ad-preview.js {$this->id}");	
		}else{
			shell_exec("phantomjs --ssl-protocol=any ".public_path()."\\js\\phantomjs\\ion-television-remote-ad-preview.js ".dev_site_url($this->remote_preview_path, $auth = false, $ssl = true));
		}
	}

	public function getComscoreFieldsAttribute(){
		return [
				"showName" => $this->sponsor->name,  // sponsor name
				"episodeTitle" => $this->category->name,
				"episodeNumber" => $this->id,
				"videoType" => "Client Solutions",
				"rating" => "*null",
				"season" => "*null"
				];
	}		
	
}
