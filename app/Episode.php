<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class Episode extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'episodes';
	protected $primaryKey = "id";
	
	public function show(){
		return $this->belongsTo("App\Show");
	}
	
	public function upcoming_program(){		
		return $this->hasOne("App\Program","episode_id","id")
					->where("show_id","=",$this->show_id)
					->where("episode_id","=",$this->id)
					->where("airdate",">=",date("Y-m-d H:i:s",strtotime("-30 min")))
					->orderBy("airdate");		
	}	
	
	public function upcoming_programs(){
		return $this->hasMany("App\Program","episode_id","id")
					->where("show_id","=",$this->show_id)
					->where("episode_id","=",$this->id)
					->where("airdate",">=",date("Y-m-d H:i:s",strtotime("-30 min")))
					->orderBy("airdate");		
	}

	public function getNewAttribute(){

		return (
			Carbon::now()->lte(Carbon::parse($this->new_episode_ends_at)) &&
			Carbon::now()->gte(Carbon::parse($this->new_episode_starts_at)) &&
			(!is_null($this->new_episode_starts_at)) &&
			(!is_null($this->new_episode_ends_at))
		);
	}

	
	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
	}
	
	public static function image_types(){
		return ImageType::where("imageable_type","=",__CLASS__)->get();
	}
	
	public function random_image(){
		if(!is_null($this->images) && count($this->images) > 0){
			$random = mt_rand(0,count($this->images)-1);
			return ( $random >= 0) ? $this->images[$random] : null;
		}
	}	
	
	public function preview(){
        return $this->morphOne('App\Video', 'videoable')->where("type_id","=","default");
	}
    public function next(){
        $next = $this->where('id', '>', $this->id)->orderBy('id','asc')->first();
        if($next) return $next;
        else return $this->orderBy('id','asc')->first();
    }

    public function previous(){
        $previous =  $this->where('id', '<', $this->id)->orderBy('id','desc')->first();
        if($previous) return $previous;
        else return $this->orderBy('id','desc')->first();
    }
	
	public function videos(){
	    return $this->morphOne('App\Video', 'videoable');
	}	
	
	public static function video_types(){
		return ImageType::where("imageable_type","=",__CLASS__)->get();
	}
    public function getComscoreFieldsAttribute(){
        return [
            "showName" => $this->show->name,
            "videoType" => "Promo",   //todo: videoType is hardcoded for now as promo untill we do full episodes and or add a Sponsor Video Type
            "episodeTitle" => $this->name,
            "episodeNumber" => $this->episode_number,
            "rating" => $this->rating,
            "season" => $this->season
        ];
    }
}
