<?php namespace App\Traits\Images;

use \Image as InterventionImage;

trait InterventionImageTrait
{
	public function getObjectAttribute(){
       return InterventionImage::make(image($this->url));
	}
	public static function optimize($source,$extension = 'jpg',$quality = 90){
        return InterventionImage::make($source)->encode($extension, $quality);

    }
}
