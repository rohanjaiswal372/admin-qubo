<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \URL;
use \Carbon;
use \GeoIP;
use \App\Libraries\String\StringUtility;
use Cache;
use Cookie;

class RescanAlert extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'rescan_alerts';
	protected $primaryKey = 'id';
	protected $dates = ['starts_at','ends_at'];
	protected $casts = ['postal_codes'=>'array'];

	
	public function setPathAttribute($value){
        $this->attributes['path'] = str_slug($value);
	}
	
		
	public function setPostalCodesAttribute($value){
		$value = preg_replace('#\s+#',',',trim($value));		
		$value = str_replace(" ",",",$value);
		$value = preg_replace("/,+/", ",", $value);
		$value = implode(",", array_unique(explode(",", $value)));
        $this->attributes['postal_codes'] = "[{$value}]";
	}
	
	
	public function getStartsAndEndDatesAttribute(){
		return date('m/d/Y g:i A', strtotime($this->starts_at)) . ' - ' . date('m/d/Y g:i A', strtotime($this->ends_at));
	}
	
	public function setStartsAndEndDatesAttribute($value){
		
        if (!empty($value)) {
           $dates = explode('-', $value);
           $this->attributes['starts_at']  = Carbon::parse(trim($dates[0]));
           $this->attributes['ends_at'] = Carbon::parse(trim($dates[1]));
        }
	}
	
	public function setNavbarContentAttribute($value){
		$this->attributes['navbar_content'] =  strip_tags($value, '<b><strong><a>');
	}
	
	public function getUrlAttribute(){
		return url("/".$this->path);
	}
	
	public static function get($id = null){
		if(is_null($id)){
			$id = \Request::segment(1) != "rescan" ? \Request::segment(1) : \Request::segment(2);
		}
		$alert = self::select($id)->first();
		$alert = (environment('production') && $alert && !$alert->active) ? null : $alert;
		return $alert;
	}
	
	public function scopeSelect($query, $param){
		$query->where( is_numeric($param) ? "id" : "path","=",$param);		
	}
	
	public function scopeActive($query){
		$query->where("starts_at","<=", Carbon::now())
			  ->where("ends_at",">", Carbon::now())
			  ->whereIn("active",\App::environment('production') ? [1] : [0,1])
			  ->orderBy("id","desc");
	}
	
	
	public function scopeUpcoming($query){
		$query->where('ends_at','>=',Carbon::now())->orderBy('starts_at', 'asc');
	}	
		
	public function scopeExpired($query){
		$query->whereBetween("ends_at", [Carbon::now()->subYear(), Carbon::now()])->orderBy('ends_at', 'desc');
	}		
	
	public function runs($date){
		if(is_string($date)){
			$date = Carbon::parse($date);
		}
		return ( $this->starts_at->lte($date) && $this->ends_at->gt($date) ) ? true : false;
	}
	
	public function getNavbarHiddenAttribute(){
		return in_array('rescan-navbar-'.$this->path, array_keys(Cookie::get())) ? true: false;
	}
		
	public function getModalHiddenAttribute(){
		return in_array('rescan-modal-'.$this->path, array_keys(Cookie::get())) ? true: false;
	}
	
	public function getUserAttribute(){
		
		$ip =  GeoIp::getIp();
		
			if(Cache::has("geoip:{$ip}")){
				$data =  Cache::get("geoip:{$ip}");
			}else{
				
				try{
				
					$data = [];
					foreach(GeoIp::get() as $property => $value){
						$data[StringUtility::slugify($property,'_')] = $value;
					}
					$data['ip'] = $ip;
					Cache::put("geoip:{$ip}",$data,60);
					
				}catch(\Exception $e){
					
					$data = ['postal_code'=>0,'ip'=>$ip];
					Cache::put("geoip:{$ip}",$data,60);
					
				}
				
			}
			
		$data["geo_targeted"] = (isset($data['postal_code']) && $this->hasPostalCode($data['postal_code'])) ? true : false;

		return (object) $data;
		
	}	
	
	public function hasPostalCode($postal_code){
		if(  !is_numeric($postal_code) || is_null($this->postal_codes) || empty($this->postal_codes) ) return false;
		else{
			return in_array($postal_code,$this->postal_codes) ? true : false;
		}
	}
	
	public function video(){
        return $this->morphOne('App\Video', 'videoable')->where("type_id","=","default")->orderBy("id","desc");
	}
	
	public function view(){
		return \App::make("App\Http\Controllers\RescanAlertController")->view();
	}
	
	public  static function notFound(){
		return \App::make("App\Http\Controllers\PageController")->viewNotFound();
	}
    public function getComscoreFieldsAttribute(){
        return [
            "showName" => $this->name,
            "episodeTitle" => $this->title,
            "episodeNumber" => $this->id,
            "videoType" => "Rescan Alert",
            "rating" => "*null",
            "season" => "*null"
        ];
    }

}
