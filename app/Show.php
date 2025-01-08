<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \URL;
use \DB;
use \Session;
use \Carbon;
use \App\ImageType;
use \App\VideoType;


class Show extends Model
{
    protected $table = 'shows';
	protected $primaryKey = 'id';

	public static function get($id = null){
		if(is_null($id)) return;
		return self::select($id)->first();
	}

	public function type(){
		return $this->hasOne("App\ShowType","id","type_id");
	}

	public function casts(){
		return $this->hasMany("App\Cast","show_id","id")->orderBy("sort_order");
	}

	public function colors(){
		return $this->morphMany("App\Color","colorable");
	}

	public function episodes(){
		return $this->hasMany("App\Episode","show_id","id")->orderBy("episode_number");
	}

	public function episode($episode_number){
		return Episode::where("show_id","=",$this->id)
					   ->where("episode_number","=",$episode_number)->first();
	}

	public function programs(){
		return $this->hasMany("App\Program","show_id","id")->orderBy("airdate");
	}

	public function programs_on($date){

	    $date = date("Y-m-d",strtotime($date));
		$start_date = Carbon::parse($date);
		$end_date = Carbon::parse($date)->addDays(1);

		return $this->hasMany("App\Program","show_id","id")
					->where("airdate",">=",$start_date->format("Y-m-d"))
					->where("airdate","<",$end_date->format("Y-m-d")." 06:00:00")
					->orderBy("airdate");
	}

	public function upcoming_program(){
		return $this->hasOne("App\Program","show_id","id")
					->where("show_id","=",$this->id)
					->where("airdate",">=",date("Y-m-d H:i:s",strtotime("-30 min")))
					->orderBy("airdate");
	}

	public function upcoming_program_from($date){

		$start_date = Carbon::parse($date)->format("Y-m-d")." 06:00:00";

		return Program::where("show_id","=",$this->id)
						->where("airdate",">=",$start_date)
						->orderBy("airdate")->first();
	}

	public function upcoming_programs(){
		return $this->hasMany("App\Program","show_id","id")
					->where("show_id","=",$this->id)
					->where("airdate",">=",date("Y-m-d H:i:s",strtotime("-30 min")))
					->orderBy("airdate");
	}

	public function upcoming_programs_until($date){

		$start_date = Carbon::now()->format("Y-m-d H:i:s");
		$end_date = Carbon::parse($date)->addDays(1)->format("Y-m-d")." 06:00:00";

		return Program::where("show_id","=",$this->id)
						->where("airdate",">=",$start_date)
						->where("airdate","<",$end_date)
						->orderBy("airdate")->get();
	}

	public function dynamic_slide(){
		return $this->hasOne("App\CarouselSlide","show_id","id")->where("dynamic",1)->orderBy("updated_at","desc");
	}


    public function getUrlAttribute(){

        switch($this->type_id){
            case "special":
                $url = "about/".$this->slug;
                break;
            case "movie":
                $url = (($this->holiday == 1)?"holiday-movie/":"movie/").$this->slug;
                break;
            case "show":
            default:
                $url = "show/".$this->slug. "/";
                break;
        }
        if($this->active)
            return  live_site_url($url);
        else return dev_site_url($url);

    }

    public function getDevUrlAttribute(){
        return str_replace( site_url(), dev_site_url() ,$this->url);
    }


	public function images(){
	    return $this->morphMany('App\Image', 'imageable');
    }

	public static function image_types(){
		return ImageType::where("imageable_type","=",__CLASS__)->get();
	}

	public function videos(){
	    return $this->morphMany('App\Video', 'videoable');
	}

	public static function video_types(){
		return VideoType::where("videoable_type","=",__CLASS__)->get();
	}

	public function preview(){
        return $this->morphOne('App\Video', 'videoable')->where("type_id","=","default")->orderBy("id", "desc");
	}


    public function banner(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","banner");
    }

	public function logo(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","logo")->orderBy("id","desc");
	}

	public function sponsor_logo(){
        $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())->where("ends_at",">=", Carbon::now())->where("type_id",1)->orderBy("id","desc");
	    if (\App::environment('production')) $query->where("active",1);
		return $query;
	}

	public function sponsor_banner(){
         $query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())->where("ends_at",">=", Carbon::now())->where("type_id",2)->orderBy("id","desc");
    	if (\App::environment('production')) $query->where("active",1);
		return $query;
	}

	public function sponsor_video(){

		$query =  $this->morphOne('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())
			->where("ends_at",">=", Carbon::now())
			->where("type_id",4)
			->whereIn('category_id', AdvertisementCategory::where("platform_id",1)->lists("id")->toArray())
			->orderBy("id","desc");

		if (\App::environment('production')) $query->where("active",1);
		return $query;
	}

	public function ads(){
        $query =  $this->morphMany('App\Advertisement', 'morphable')->where("starts_at","<=",Carbon::now())->where("ends_at",">=", Carbon::now())->orderBy("id","desc");
		if (\App::environment('production')) $query->where("active",1);
		return $query;
	}

	public function episodic_images(){
        return $this->morphMany('App\Image', 'imageable')->where("type_id","=","episodic");
	}

	public function random_episodic_image(){
		if(!is_null($this->episodic_images) && count($this->episodic_images) > 0){
			$random = mt_rand(0,count($this->episodic_images)-1);
			return ( $random >= 0) ? $this->episodic_images[$random] : null;
		}
	}

	public function rotate_episodic_image(){

		$index = Session::has("rotate_image:index") ? Session::get("rotate_image:index")  : 0;

		if(!is_null($this->episodic_images) && count($this->episodic_images) > 0){
			if($index > count($this->episodic_images) -1 ) $index = 0;
			Session::put("rotate_image:index",$index+1);
			return  $this->episodic_images[$index];
		}
	}

	public function mobile_logo(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","mobile-logo")->orderBy("id","desc");
	}

	public function mobile_banner(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","mobile-banner")->orderBy("id","desc");
	}

	public function mobile_schedule_banner(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","mobile-schedule-banner")->orderBy("id","desc");
	}

	public function mobile_schedule_half_hour_banner(){
        return $this->morphMany('App\Image', 'imageable')->where("type_id","=","mobile-schedule-block-30min")->orderBy("id","desc");
	}

	public function mobile_schedule_1_hour_banner(){
        return $this->morphMany('App\Image', 'imageable')->where("type_id","=","mobile-schedule-block-1hr")->orderBy("id","desc");
	}

	public function mobile_schedule_2_hour_banner(){
        return $this->morphMany('App\Image', 'imageable')->where("type_id","=","mobile-schedule-block-2hr")->orderBy("id","desc");
	}

    public function poster(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","poster");
    }

	public function backdrop(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","backdrop");
	}

	public function pod_image(){
        return $this->morphOne('App\Image', 'imageable')->where("type_id","=","pod");
	}

	public function scopeMovies($query){
		$query->where("type_id","=","Movie");
	}

	public function scopeSeries($query){
		$query->where("type_id","=","Show")->orderBy("name");
	}

	public function scopeSpecials($query){
		$query->where("type_id","=","Special");
	}

	public function scopeSelect($query, $param){
		if(is_numeric($param)){
			$query->where("id","=",$param);
		}else{
			$query->where("slug","=",$param);
		}
	}

	public function scopeFeatured($query){
		$query->where("featured",">=",1)->orderBy("featured","desc");
	}

	public function scopeActive($query){
		$query->where("active","=",1);
	}

	public function scopeIon($query){
		$query->where("scope","=","ION");
	}

	public function scopeQubo($query){
		$query->where("scope","=","QUBO");
	}

	public function grid($index = 0, $date = null){
		$date = (is_null($date)) ? Carbon::today()->format("Y-m-d") : Carbon::parse($date);
		$schedule =  GridSchedule::where("morphable_id",$this->id)
								->where("morphable_type",get_class($this))
								->where("sort_order",$index)
								->where("starts_at","<=",$date)
								->orderBy("starts_at","desc")
								->first();

		$grid =  ($schedule && $schedule->grid) ?  $schedule->grid->fetch() : null;
		if(!is_null($grid)) $grid->schedule_id = $schedule->id;
		return $grid;
	}

	public function grids( $date = null){

		$grids = collect();

		for($i = 0;$i < 5;$i++){
			$this_grid =  $this->grid($i);
			if($this_grid){
				$grids->push($this_grid);
			}
		}

		return $grids;
	}
}
