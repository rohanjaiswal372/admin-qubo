<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;
use Storage;

class ImagesController extends Controller
{

    public $view_base = 'images';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function delete($id)
    {
        $image = Image::findOrFail($id);
        # remove it
        $image->delete();
//
//        $disk = Storage::disk("rackspace");
//        $result = $disk->exists($image['url']);
//        if( $result ){
//            $disk->delete($image['url']);
//        }

        return redirect()->back();
    }
}
