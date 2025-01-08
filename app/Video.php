<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Brightcove;
use BrightcoveCMS;
use BrightcoveDI;
use Storage;
use File;
use URL;
use Cache;
use Bugsnag;
use Illuminate\Support\Facades\Log;

class Video extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'videos';
    protected $primaryKey = 'id';

    public function brightcove(){
        return BrightCoveCMS::video($this->brightcove_id);;
    }

    public function sources(){
        return BrightCoveCMS::videoSources($this->brightcove_id);
    }

    public function getRemoteSourceAttribute(){
        if($this->sources()){
            foreach($this->sources() as $idx => $source){
                if(property_exists($source, 'remote') && $source->remote == "true" ) return  $this->sources()[$idx]->src;
            }
            return $this->sources()[1]->src;
        }
        return null;
    }

    public function videoable(){
        return $this->morphTo();
    }

    public function url(){
        return $this->url;
    }

    public function getUrlAttribute(){
        if( get_class($this->videoable) == "App\Show" ){
            return URL::to("show/".$this->videoable->slug."/videos/");
        }else if(get_class($this->videoable)  =="App\Episode" ){
            return url("show/".$this->videoable->show->slug."/videos/".$this->videoable->episode_number);
        }
    }

    public function thumbnail(){
        if($this->brightcove_id) {
            $brightcove = BrightcoveCMS::videoThumbnailURL($this->brightcove_id);
            return $brightcove;
        }
        return null;
    }

    public function still(){
        if($this->brightcove_id) {
            $brightcove = BrightcoveCMS::videoStillURL($this->brightcove_id);
            return $brightcove;
        }
        return null;
    }

    public static function upload($data, $type = 'push_options' ){
        $params = self::getParams($data,$type);
        return self::sendToBrightcove($params,$data);
    }


    public static function getParams($data, $option) {
        return [
            'meta' =>
                [
                    "name" => $data["object_name"],
                    "description" =>
                        ($data["object_description"] != '')
                            ? str_limit($data['object_description'], 250) : $data['object_name'],                                                    "custom_fields" => $data['customFields']
                ],
            'file' =>
                ($option == "pull_options" || $option == "pull_replace_options")
                    ? $data["destination"] :  $data["file"],
            'options'=>[
                'profile' => $data['profile'],
                "ingest_type" => $option,
                "images" => [
                    "poster" => ["src" => (isset($data['image'])) ? $data['image'] : ""]
                ]
            ]
        ];
    }

    public static function sendToBrightcove($params,$data){

        //create video object in CMS and get a video_id to pass to Dynamic Ingest API
        $brightcove = BrightcoveDI::upload(null, $params);

        if ($brightcove && $brightcove->cms) {
            $video = new Video;
            $video->brightcove_id = $brightcove->cms->id;
            $video->videoable_id = $data['object_id'];
            $video->videoable_type = $data['model'];
            $video->title = $brightcove->cms->name;
            $video->type_id = (isset($data["type_id"])) ? $data["type_id"] : 'default';
            $video->save();
            return $video;

        } else if (isset($brightcove->error)) {
            throw new Exception($brightcove->error);
        }

    }

    /**
     * @param $brightcove_id
     * @return bool
     */
    public static function remove($brightcove_id){
        //$brightcove = Brightcove::delete($brightcove_id);
        //if ($brightcove) return true;
        //else return false;
    }

}


