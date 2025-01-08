<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \URL;
use \DB;
use \Croppa;
use \Cache;
use \Storage;
use \Config;
use \cURL;
use \Exception;
use \Request;
use \File;
use \App\Image;
use \Image as InterventionImage;

class Thumbnail extends Model
{
    protected $table = 'thumbnails';
	protected $primaryKey = 'id';
	
	public static function get($image_id,$width,$height){
		if(is_null($image_id)||is_null($width)||is_null($height)) return;
		return self::select($image_id,$width,$height)->first();
	}

	public function scopeSelect($query, $image_id, $width, $height){
		$query->where("image_id","=",$image_id)
			  ->where("width","=",$width)
			  ->where("height","=",$height);
	}
	
	public function source(){
		return $this->hasOne("App\Image","id","image_id");
	}

    public static function make($image,$width,$height,$position = null){


        if(!in_array($position,['top-left','top','top-right','left','center','right','bottom-left','bottom','bottom-right'])) $position = null;

        $image_extension = File::extension($image->url);
        $image_filename = str_replace(".{$image_extension}","",basename($image->url));
        $image_path = parse_url($image->url)["path"];
        $cropped_image_path = public_path("uploads/{$image_filename}-{$width}x{$height}.{$image_extension}");

        // open and resize an image file
        $img =  InterventionImage::make(image($image->url))->fit($width,$height,function($constraint){
            //$constraint->upsize();
        },$position);

        // save file as png with medium quality
        $img->save($cropped_image_path);


        $params["width"] = $width;
        $params["height"] = $height;
        $params["image_id"] = $image->id;
        $params["file"] = $cropped_image_path;
        $params["destination"] = dirname($image_path)."/".basename($params["file"]);
        return self::upload($params);

    }
    public static function upload( $data ){
        # TODO: Check for auth

        $disk = Storage::disk("rackspace");
        $disk_path  = $disk->getDriver()->getAdapter()->getPathPrefix();

        if(isset($data['file'])){
            $data['file'] = str_replace("/","\\",$data['file']);
        }

        $result = null;

        try{
            if(File::exists($data["file"])){
                $result = $disk->put($data['destination'], File::get($data['file']));
                File::delete($data["file"]);
            }
        }catch(Exception $e){

        }


        if($result){

            $thumbnail = Thumbnail::get($data['image_id'],$data['width'],$data['height']);

            if(!$thumbnail){

                try{

                    # store in image system
                    $thumbnail = new Thumbnail;
                    $thumbnail->image_id = $data['image_id'];
                    $thumbnail->width = !is_null($data['width']) ? $data['width'] : "";
                    $thumbnail->height = !is_null($data['height']) ? $data['height'] : "";
                    $thumbnail->url = $data['destination'];

                    $thumbnail->save();

                }catch(\Exception $e){
                    return;
                }

            }

            return $thumbnail;
        }

    }
	
//	public static function make($image,$width,$height,$croppa_args = null){
//
//		//see https://github.com/BKWLD/croppa for more options
//		$croppa_args = is_null($croppa_args) ?  array('quadrant' => 'T') : $croppa_args;
//		$croppa_args = (is_string($croppa_args) && in_array(strtoupper($croppa_args),["T","B","L","R","C"])) ? array('quadrant' => strtoupper($croppa_args) ) : $croppa_args;
//
//		if( is_string($croppa_args) && in_array($croppa_args ,["resize"])) {
//			$croppa_args = array($croppa_args);
//		}
//
//		$cloud_storage = Storage::disk("rackspace");
//		$disk_path  = $cloud_storage->getDriver()->getAdapter()->getPathPrefix();
//		$image_path = parse_url($image->url)["path"];
//		$image_filename = basename($image_path);
//		$image_extension = File::extension($image_filename);
//		$temp_image_path = "/uploads/".str_replace(".".$image_extension,"-".uniqid()."-".sha1($image_filename).".".$image_extension, $image_filename);
//
//		$handler = fopen(public_path().$temp_image_path, "w") or die("can't open file");
//		fwrite($handler,$cloud_storage->get($image_path));
//		fclose($handler);
//
//		$cropped_image_path = Croppa::url($temp_image_path,$width,$height,$croppa_args);
//		cURL::newRequest('get', URL::to($cropped_image_path))->send();
//
//		if(File::exists(public_path().$temp_image_path)){
//			File::delete(public_path().$temp_image_path);
//		}
//
//		$params["width"] = $width;
//		$params["height"] = $height;
//		$params["image_id"] = $image->id;
//		$params["file"] = public_path().strtok($cropped_image_path, '?');
//		$params["destination"] = dirname($image_path)."/".basename($params["file"]);
//		return self::upload($params);
//
//	}
	
//    public static function upload( $data ){
//        # TODO: Check for auth
//
//		$disk = Storage::disk("rackspace");
//		$disk_path  = $disk->getDriver()->getAdapter()->getPathPrefix();
//
//		if(isset($data['file'])){
//			$data['file'] = str_replace("/","\\",$data['file']);
//		}
//
//		$result = $disk->put($data['destination'], File::get($data['file']));
//
//		if(File::exists($data["file"])){
//			File::delete($data["file"]);
//		}
//
//		if($result){
//
//			$thumbnail = Thumbnail::get($data['image_id'],$data['width'],$data['height']);
//
//			if(!$thumbnail){
//				# store in image system
//				$thumbnail = new Thumbnail;
//				$thumbnail->image_id = $data['image_id'];
//				$thumbnail->width = $data['width'];
//				$thumbnail->height = $data['height'];
//				$thumbnail->url = $data['destination'];
//				$thumbnail->save();
//			}
//
//			return $thumbnail;
//		}
//
//    }
	
	public function delete(){
		$disk = Storage::disk("rackspace");
		$disk->delete($this->url);
		DB::table('thumbnails')->where("id","=",$this->id)->delete();
	}	
}
