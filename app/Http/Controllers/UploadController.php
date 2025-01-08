<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Auth;
use App\Show;
use App\Cast;
use App\Episode;
use App\ImageType;
use App\Image;
use App\VideoType;
use App\Video;
use App\DocumentType;
use App\Document;
use App\CarouselSlide;
use App\Campaign;
use Session;
use Input;
use Config;
use Response;
use File;
use BrightcoveAPI;
use Storage;
use App\ActivityLog;
use App\Notifications\ActivityLogged;

class UploadController extends Controller
{

    public function upload(){

        $chunking = Config::get('fineuploader.chunking.enabled');

        set_time_limit(0);
        ignore_user_abort(1);
        ini_set("memory_limit","2G");
        ini_set("upload_max_filesize","500M");
        ini_set("post_max_size","500M");
        $extension = "";
        $filename = "";
        $params = "";

        if(Input::hasFile('qqfile')){

            if($chunking){

                $extension = "chunk";
                $directory =  config('fineuploader.chunking.directory');
                $filename  =  Input::get("qquuid")."-".Input::get("qqpartindex")."-".(time()*1000).".".$extension;
                Session::put('fineuploader.extension',File::extension(Input::get("qqfilename")));

            }else{

                $extension =  Input::file('qqfile')->getClientOriginalExtension();
                $directory =  Config::get('fineuploader.upload.directory');
                $filename  =  "file-".Input::get("qquuid").".".$extension;
            }

            Input::file('qqfile')->move($directory,$filename);

            //Send the file to its final destination
            if(Input::has("media_type") && Input::has("object_id")){

                $object_id = Input::get("object_id");
                $media_type = Input::get("media_type");
                $config = null;

                switch(strtolower($extension)){
                    case "mp4":
                    case "mov":
                    case "webm":
                        $config =  VideoType::find($media_type);
                        break;
                    case "eps":
                    case "doc":
                    case "docx":
                    case "pdf":
                    case "txt":
                    case "svg":
                    case "ai":
                    case "psd":
                    case "xls":
                    case "xlsx":
                        $config = DocumentType::find(1);
                        break;

                    case "png":
                    case "jpg":
                    case "jpeg":
                    case "gif":
                    default:
                        $config =  ImageType::find($media_type);
                        break;
                }

                switch($config->disk){
                    case "local":
                        //do stuff locally
                        break;
                    case "rackspace":

                        if(get_class($config) == "App\VideoType"){
                            if($config->videoable_type == "App\CarouselSlide"){
                                $object = CarouselSlide::find($object_id);
                            }

                            $params =["model"=>$config->videoable_type,
                                      "object_name"=>$this->getFriendlyName($object),
                                      "object_description"=> ($object->description)? urlencode(str_limit($object->description, $limit = 100, $end = '...')) : urlencode($config->videoable_type),
                                      "title" => $object->title,
                                      "type_id"=>$config->type_id,
                                      "file"=>$directory.$filename,
                                      "scope"=>$config->disk,
                                      "destination"=> "videos/".str_slug($filename)."-".uniqid().".".File::extension($filename),
                                      "object_id"=>$object_id,
                                      "profile" => 'video-carousel',
                                      "customFields" => $object->comscore_fields];

                            $video = Video::upload($params);

                        }else if(get_class($config) == "App\ImageType"){

                            $params = ["model" => $config->imageable_type,
                                       "type_id" => $config->type_id,
                                       "file" => $directory . $filename,
                                       "destination" => $this->getImageUploadDestination($object_id, $config, $filename),
                                       "object_id" => $object_id,
                                       "extension"=> strtolower($extension)];

                            $image = Image::upload($params);

                            if ($image && Input::has("create_thumbnail") && Input::get("create_thumbnail") == "true") {
                                $thumbnail_crop_options = !empty(Input::get("thumbnail_crop_options")) ? Input::get("thumbnail_crop_options") : "C";
                                $thumbnail_width = $config->thumbnail_width;
                                $thumbnail_height = $config->thumbnail_height;
                                $thumbnail = $image->thumbnail($thumbnail_width, $thumbnail_height, $thumbnail_crop_options);
                            }
                        }else if(get_class($config) == "App\DocumentType"){

                            $params = ["model" => $config->documentable_type,
                                       "type_id" => $config->type_id,
                                       "file" => $directory . $filename,
                                       "filename" => Input::file('qqfile')->getClientOriginalName(),
                                       "destination" => $this->getImageUploadDestination($object_id, $config, $filename),
                                       "object_id" => $object_id,
                                       "extension"=> strtolower($extension)];

                            if(!Document::upload($params)){
                                dd("upload Failed");
                            }
                        }
                        return Response::json(array('success' => true , 'filename' => $filename, "message" => "Upload successful", "file" => Input::get("qqfilename"),  "params" => $params));


                        break;
                    case "brightcove":

                        $object = call_user_func_array(array( ($config->videoable_type) , 'find'), [$object_id] );

                        if($object->videos){
                            $object->videos()->delete();
                        }

                        if($object->video){
                            $object->video->delete();
                        }

                        $params =["model"=>$config->videoable_type,
                                  "type_id"=>$config->type_id,
                                  "title" => ($object->title)? urlencode($object->title) : "",
                                  "object_name"=>$this->getFriendlyName($object),
                                  "scope"=>$config->disk,
                                  "object_description"=> ($object->description)? urlencode(str_limit($object->description, $limit = 100, $end = '...')) : urlencode($config->videoable_type),
                                  "file"=>$directory.$filename,
                                  "destination"=> "videos/".str_slug($filename)."-".uniqid().".".File::extension($filename),
                                  "object_id"=>$object_id,
                                  "profile" => 'videocloud-default-v1',
                                  "customFields" => $object->comscore_fields];

                        if($config->videoable_type == "App\Post" && $object->imageDefault()->first()){
                            $params += ['image' => image($object->imageDefault()->first()->url) ];
                        }

                        $video = Video::upload($params);

                        if($video){
                            return Response::json(array('success' => true , 'filename' => $filename, "message" => "Upload successful", "file" => Input::get("qqfilename"), "params" => $params));
                        }else{
                            return Response::json(array('error' => true , 'filename' => "","message" => "Upload Failed" ));
                        }

                        break;
                }

            }else if( Input::has("object_id") && Input::has("bulk") && Input::get("bulk") == "true"){


                $object_id = Input::get("object_id");
                $config = VideoType::find(2); //Get Episode Type
                $show = Show::find($object_id);

                $new_object = Episode::where("show_id","=",$show->id)->where("episode_number","=",basename(Input::get("qqfilename"),".mp4"))->first();

                if(!is_null($new_object)){
                    if($new_object->videos){
                        $new_object->videos()->delete();
                    }

                    $params =["model"=>$config->videoable_type,
                              "type_id"=>$config->type_id,
                              "object_name"=>$new_object->name,
                              "object_description"=>str_limit($new_object->description, $limit = 100, $end = '...'),
                              "file"=>$directory.$filename,
                              "object_id"=>$new_object->id];

                    $video = Video::upload($params);

                    if($video){
                        return Response::json(array('success' => true , 'filename' => $filename, "message" => "Upload successful", "file" => Input::get("qqfilename") ));
                    }else{
                        return Response::json(array('error' => true , 'filename' => "","message" => "Upload Failed" ));
                    }
                }
            }

        }else{
            return Response::json(array('error' => true , 'filename' => "" ,"message" => "Upload Failed" ));
        }

    }

    public function getFriendlyName($object){
        switch(get_class($object)){
            case "App\Show":
                $name = $object->name;
                break;
            case "App\Episode":
                $name = $object->show->name."|".$object->name;
                break;
            case "App\Post":
                $name =  'Post | '.$object->type_id.' | '.str_limit($object->title, 20);
                break;
            case "App\CarouselSlide":
                $name =  'Carousel Slide | '.str_limit($object->title, 20);
                break;
            case "App\RescanAlert":
                $name = 'Rescan Alert | '.str_limit($object->title, 20);
                break;
            case "App\Advertisement":
                $name = $object->category->name." - Sponsored by  ".$object->sponsor->name;
                break;
        }

        return $name;
    }

    public function getImageUploadDestination($object_id,$config,$filename){
        $destination = null;
        if(isset($config->imageable_type)) {
            switch ($config->imageable_type) {

                case "App\Show":
                    $show = Show::find($object_id);
                    if ($show->type_id == "show") {
                        $destination = "programs/shows/" . $show->slug . "/" . str_slug($config->name) . "-" . $show->slug . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    } else if ($show->type_id == "movie") {
                        $destination = "programs/movies/" . $show->slug . "/" . str_slug($config->name) . "-" . $show->slug . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    } else if ($show->type_id == "special") {
                        $destination = "programs/specials/" . $show->slug . "/" . str_slug($config->name) . "-" . $show->slug . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    }
                    break;
                case "App\Cast":
                    $cast = Cast::find($object_id);
                    if ($cast->show->type_id == "show") {
                        $destination = "programs/shows/" . $cast->show->slug . "/" . str_slug($config->name) . "-" . $cast->slug . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    } else if ($cast->show->type_id == "movie") {
                        $destination = "programs/movies/" . $cast->show->slug . "/" . str_slug($config->name) . "-" . $cast->slug . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    } else if ($cast->show->type_id == "special") {
                        $destination = "programs/specials/" . $cast->show->slug . "/" . str_slug($config->name) . "-" . $cast->slug . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    }
                    break;
                case "App\Episode":
                    $episode = Episode::find($object_id);
                    if ($episode->show->type_id == "show") {
                        $destination = "programs/shows/" . $episode->show->slug . "/episodes/" . str_slug($config->name) . "-" . $episode->show->slug . "-ep-" . $episode->episode_number . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    }
                    break;
                case "App\Campaign":
                    $campaign = Campaign::find($object_id);
                    $destination = "campaigns/" . str_slug($campaign->name) . "/images/" . str_slug($filename) . "-" . uniqid() . "-" . sha1($filename) . "." . File::extension($filename);
                    break;
                default:

                    break;
            }
        }
        elseif($config->documentable_type){
            switch ($config->documentable_type) {
                case "App\Campaign":
                    $campaign = Campaign::find($object_id);
                    $destination = "campaigns/" . str_slug($campaign->name) . "/documents/" . str_slug($filename) . "-" . uniqid() . "-" . $filename . "." . File::extension($filename);
                    break;
            }
        }
        return $destination;
    }

    public function remoteUploadComplete(Request $request) {
        $log = new ActivityLog();
        $log->fill( ["user_id" => $user_id,"action"=>$action] + $diff );
        $this->activity_logs()->save($log);
        if(Auth::check()) Auth::user()->notify(new ActivityLogged($log));
    }


}
