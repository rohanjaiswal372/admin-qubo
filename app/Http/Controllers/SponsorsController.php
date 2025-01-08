<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Image;
use App\Sponsor;
use Auth;

class SponsorsController extends Controller {
    public $view_base = 'sponsors';

    public function __construct() {
        $this->middleware("auth.ion");

        if (Auth::check() && !Auth::user()->hasPermission("ads")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $sponsors = Sponsor::all();
        return view($this->view_base . '.index', compact('sponsors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return view($this->view_base . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request) {
        $sponsor = Sponsor::firstOrCreate([
            "name" => $request->name,
            "url" => $request->url
        ]);
        $sponsor->url = $request->url;
        $sponsor->color = $request->color;
        $sponsor->save();

        if ($request->file('image')) {
            $this->uploadImage($sponsor->id, $request->file('image'));
        }
        return redirect(route($this->view_base . '.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.    `
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {
        $sponsor = Sponsor::findOrFail($id);
        return view($this->view_base . '.edit')->with(['sponsor' => $sponsor]);
    }

    private function processLogos($id = NULL) {

        $debug = [];
        if (is_null($id)) {
            $sponsors = Sponsor::all();
            foreach ($sponsors as $sponsor) {
                $ads = $sponsor->ads;
                if (!is_null($ads)) {
                    foreach ($ads as $ad) {
                        if (!is_null($ad->image) && $ad->type_id == "logo") {
                            $img = new Image;
                            $img->url = $ad->image->url;
                            $img->imageable_type = "App\Sponsor";
                            $img->type_id = 'logo';
                            $img->imageable_id = $id;
                            $img->save();
                            $img->thumbnail(125, 50, "C");
                            $debug[] = $img;
                        }
                    }
                }
            }
            $sponsors = Sponsor::all();
            flash()->success($debug);
            return view($this->view_base . '.index', compact('sponsors'));

        } else {
            $sponsor = Sponsor::findOrFail($id);

            $ads = $sponsor->ads;
            if (!is_null($ads)) {
                foreach ($ads as $ad) {
                    if ($ad->type_id == "logo") {
                        $img = new Image;
                        $img->url = $ad->image->url;
                        $img->imageable_type = "App\Sponsor";
                        $img->type_id = 'logo';
                        $img->imageable_id = $id;
                        $img->save();
                        $img->thumbnail(125, 50, "C");
                        $debug[] = $img;
                    }
                }
            }

        }
        $sponsors = Sponsor::all();
        flash()->success('no ads or images found');
        return view($this->view_base . '.index', compact('sponsors'));

    }

    public function uploadImage($id, $image) {
        # image upload
        $extension = strtolower($image->getClientOriginalExtension());
        if (in_array($extension, config('image.image_types'))) {
            $destination = '/sponsors/' . $id . '/' . uniqid('', FALSE) . "-" . preg_replace('/\s+/', '', $image->getClientOriginalName());
            $new_img = Image::upload(['model' => 'App\Sponsor', 'object_id' => $id, 'type_id' => 'logo', 'file' => $image, 'destination' => $destination, 'extension' => $extension, 'size' => config('image.max-logo-size')]);
            return TRUE;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->name = $request->name;
        $sponsor->url = $request->url;
        $sponsor->color = $request->color;
        $sponsor->save();
        if ($request->file('image')) {
            $this->uploadImage($sponsor->id, $request->file('image'));
        }
        flash()->success('The sponsor has been updated');
        return redirect(route($this->view_base . '.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id) {
        $id = Sponsor::find($id);
        $id->delete();
        return redirect(route('sponsors.index'));
    }
}
