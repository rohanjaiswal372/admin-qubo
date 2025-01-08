<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
use Config;
use cURL;
use App\CarouselSlideAllocation;

class Carousel extends Model
{

    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'carousels';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'description', 'position', 'active'];
    public $starts_at = null;

    public function slides($starts_at = null,$ends_at = null){

        if(get_class($starts_at) == "Carbon"){
            $starts_at = $starts_at->format("Y-m-d");
        }else if(is_null($starts_at)){
            $starts_at = Carbon::today()->format("Y-m-d");
        }

        if(get_class($ends_at) == "Carbon"){
            $ends_at = $ends_at->format("Y-m-d");
        }

        $result = $this->hasOne("App\CarouselSlideSchedule","carousel_id","id");

        if(!is_null($starts_at) && !is_null($ends_at)){
            $result->where("starts_at","<=",$starts_at);
            $result->where("ends_at",">=",$ends_at);
        }else{
            if(!is_null($starts_at)){
                $result->where("starts_at","<=",$starts_at);
            }else{
                $result->where("starts_at","<=",Carbon::today());
            }

        }

        return $result->with('slide')->orderBy('sort_order', 'asc')->orderBy('starts_at', 'desc')->orderBy('id','desc');
    }

    public function getLimitAttribute(){
        return $this->getAllocationLimit();
    }

    public function limit($date){
        if(is_null($date)) $date = Carbon::today();
        if(is_string($date)) $date = Carbon::parse($date);
        return $this->getAllocationLimit($date);
    }

    public function getAllocation($date = null){
        if(is_null($date)) $date = Carbon::parse("now");
        return CarouselSlideAllocation::where("carousel_id",$this->id)->where("starts_at",$date)->orderBy("id","desc")->first();
    }

    public function getAllocationLimit($date = null){
        if(is_null($date)) $date = Carbon::parse("now");
        $allocation = $this->getAllocation($date);
        return (int) (($allocation) ? $allocation->limit :  config("carousel.allocation.default_limit"));
    }

    public function getSlides($starts_at = null,$ends_at = null){

        if(get_class($starts_at) == "Carbon"){
            $starts_at = $starts_at->format("Y-m-d");
        }

        if(get_class($ends_at) == "Carbon"){
            $ends_at = $ends_at->format("Y-m-d");
        }

        $results = $this->slides( $starts_at, $ends_at )->get();
        $slides = [];

        $limit = $this->limit($starts_at);

        foreach($results as $key => $result){
            if(!isset($slides[$result->sort_order])){
                $result->slide->schedule_id = $result->id;
                $slides[$result->sort_order] = $result->slide;
                if(count($slides) == $limit) break;
            }
        }

        return $slides;
    }

    public static function get($placement){
        $carousel =   (is_numeric($placement)) ? self::find($placement) : self::where('position', $placement)->first();
        if($carousel) $carousel->slides = $carousel->getSlides();
        return $carousel;
    }

}
