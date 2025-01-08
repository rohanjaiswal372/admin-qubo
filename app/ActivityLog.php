<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class ActivityLog extends Model {
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'action', 'before', 'after'];
    protected $dates = ['created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getBeforeAttribute($value) {
        return json_decode($value);
    }

    public function getAfterAttribute($value) {
        return json_decode($value);
    }

    public function getDateAttribute() {
        return $this->created_at->format("m/d/Y");
    }

    public function morphable() {
        return $this->morphTo();
    }

    public function getUrlAttribute() {

        $class = strtolower(substr(get_class($this->morphable),4));

        if($class == "image"){
            $imagetype = substr($this->morphable->imageable_type,4);
            if($imagetype == 'GridCell')
                $link = route('pods.edit', $this->morphable->imageable_id);
            else  $link = route(str_plural(strtolower($imagetype)).'.edit', $this->morphable->imageable_id);

        }
        elseif($class == "video"){
            $videotype = substr($this->morphable->videoable_type,4);
            $link = route(str_plural(strtolower($videotype)).'.edit', $this->morphable->videoable_id);
        }
        elseif($class == "campaignadvertisement"){
            $link = route('campaigns.edit', $this->morphable->campaign_id);
        }
        elseif($class == "campaignstatus"){
            $link = route('campaigns.edit', $this->morphable->campaign_id);
        }
        elseif($class == "campaignfollower"){
            $link = route('campaigns.edit', $this->morphable->campaign_id);
        }
        elseif($class == "gridschedule"){
            $link = route('pods.edit', $this->morphable->id);
        }
        elseif($class == "carouselslideschedule"){
            $link = url('carousels/display/'.$this->morphable->carousel_id);
        }
        else{
            $link = route(str_plural($class).'.edit', $this->morphable->id);
        }
        return $link;
    }

    public function scopeOfDate($query, $date) {
        if (!in_array(get_class($date), ["Carbon", "Carbon/Carbon"])) {
            $date = Carbon::parse($date);
        }
        $starts_at = $date;
        $ends_at = clone $date;
        $ends_at->addDays(1);
        return $query->whereBetween("created_at", [$starts_at, $ends_at]);
    }

    public function scopeBetweenDates($query, $starts_at, $ends_at) {
        if (!in_array(get_class($starts_at), ["Carbon", "Carbon/Carbon"])) {
            $starts_at = Carbon::parse($starts_at);
        }
        if (!in_array(get_class($ends_at), ["Carbon", "Carbon/Carbon"])) {
            $ends_at = Carbon::parse($ends_at);
        }
        return $query->whereBetween("created_at", [$starts_at, $ends_at]);
    }

}
