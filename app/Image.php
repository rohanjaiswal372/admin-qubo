<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB;
use \Storage;
use \File;
use \Croppa;
use \cURL;
use \App\Thumbnail;

class Image extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;
    use Traits\Images\InterventionImageTrait;

    protected $table = 'images';
	protected $primaryKey = 'id';

    /**
     * Get all of the owning imageable models.
     */

    public function imageable() {
        return $this->morphTo();
    }

    public function getDiskConfigurationAttribute() {
        return config("filesystems.disks.rackspace");
    }

    public function getUrlAttribute($value){
        return (filter_var($value, FILTER_VALIDATE_URL)) ? $value : config('filesystems.disks.rackspace.public_url_ssl').'/'.$value;
    }

    public function getSizeAttribute(){
        list($width, $height) = (@exif_imagetype($this->url))? getimagesize($this->url): ['null','null'];
        return ["width" => $width, "height" => $height];
    }

    /**
     * upload
     * uploads an image and stores it into the system back rackspace and system
     */
    public static function upload($data) {

        $disk = Storage::disk("rackspace");
        $allowed_ext =  config('image.image_types');
        $result = NULL;
        $extension = (isset($data['extension']))? $data['extension'] : 'jpg';
        $quality = (isset($data['quality']))? $data['quality'] : 90;
        if (isset($data["url"])) {
            if (in_array($extension,$allowed_ext )) {
                $new_image = Image::optimize(file_get_contents($data['url']), $extension, $quality);
                $result = $disk->put($data['destination'], $new_image);
            } else return FALSE;
        } else if (isset($data["file"])) {
            if (in_array($extension, $allowed_ext)) {
                $new_image = Image::optimize(File::get($data['file']), $extension, $quality);
                $result = $disk->put($data['destination'], $new_image);
            } else return FALSE;
        }

        if ($result) {
            # store in image system
            $image = new Image;
            $image->imageable_id = $data['object_id'];
            $image->imageable_type = $data['model'];
            $image->url = $data['destination'];
            $image->type_id = (isset($data["type_id"])) ? $data["type_id"] : 'default';
            $image->save();
            return $image;
        }
    }

    public static function make($image,$extension = 'jpg', $quality ='90') {
        return Image::optimize($image,$extension, $quality);
    }

    public function getFileTypeAttribute(){
        if(!is_null(File::extension($this->url)))
            return File::extension($this->url);
        else return 'jpg';
    }
	
	public function delete(){
        $image = Image::findOrFail($this->id);
		DB::table('images')->where("id","=",$this->id)->delete();
        $this->log('delete',\Auth::user()->id);
	}		
	
	public function thumbnail($width,$height,$croppa_args = null){
		//Croppa args can be the following
		// "T" for Top
		// "L" for Left
		// "R" for Right
		// "C" for Center (Default)
		$thumbnail = Thumbnail::get($this->id,$width,$height);
		
		if($thumbnail){
			return $thumbnail;		
		}else{
			$thumbnail = Thumbnail::make($this,$width,$height,$croppa_args);
			return $thumbnail;
		}
	}
	
	public function thumbnails(){
		return $this->hasMany("App\Thumbnail","image_id","id");
	}		
}
