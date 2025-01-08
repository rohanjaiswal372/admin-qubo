<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use App\Image;
use App\Video;
use App\Sponsor;
use App\Advertisement;
use App\AdvertisementPlatform;
use App\AdvertisementCategory;
use App\AdvertisementType;
use App\Campaign;
use App\CampaignItem;
use App\Post;
use App\GridCell;
use Cache;
use View;
use Carbon;
use Excel;
use cURL;

class AdvertisementController extends Controller
{
    /**
     * @var string
     */
    public $view_base = 'advertisements';

    /**
     * AdvertisementController constructor.
     */
    public function __construct()
    {
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
    public function index()
    {
        // Cache::forget('Advertisements');
        if (Cache::has('Advertisements')) {
            $data = Cache::get('Advertisements');
        } else {
            $data = Advertisement::orderBy('ends_at', 'desc')->whereBetween("ends_at", [Carbon::now(), Carbon::now()->addYear()])->paginate(100);
            Cache::put('Advertisements', $data, Carbon::now()->addHours(1));
        }
        return view($this->view_base . '.index')->with(['items' => $data, "expired" => FALSE, 'sponsors' => $this->getSponsors($data)]);
    }

    public function getPreview($id)
    {
        $ad = Advertisement::find($id);

        switch (true) {
            case (in_array($ad->platform->slug, ['mobile', 'ipad'])):
                return view($this->view_base . '.previews.' . $ad->platform->slug)->with(compact('ad'));
                break;
            case (in_array($ad->platform->slug, ['website'])):
                $login = config("windows-authentication");
                $response = curl_get(dev_site_url($ad->remote_preview_path, $auth = true, ($_SERVER["HTTPS"] == "on") ? true : false), $login['username'], $login['password']);
                return $response;
                break;

        }

    }

    /**
     * @param $data
     * @return array
     */
    private function getSponsors($data)
    {
        $sponsors_unique = [];
        foreach ($data as $ad) {
            $sponsors_unique[] = $ad->sponsor->name;
        }
        return array_unique($sponsors_unique);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getExpired($id = NULL)
    {
        $data = Advertisement::expired()->paginate(100);
        return view($this->view_base . '.index')->with(['items' => $data, "expired" => TRUE, 'sponsors' => $this->getSponsors($data)]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function show(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $data = Advertisement::orderBy('ends_at', 'desc')->whereBetween("ends_at", [Carbon::parse($start_date)->format('m/d/Y g:i A'), Carbon::parse($end_date)->format('m/d/Y g:i A')])->paginate(100);
        flash()->success('New Dates have been selected');
        $results = view($this->view_base . '.results')->with(['items' => $data, "expired" => FALSE, 'sponsors' => $this->getSponsors($data)])->render();
        return json_encode(['success' => TRUE, 'results' => $results]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($campaignID = null)
    {
        (!is_null($campaignID)) ? $campaignSelected = Campaign::findOrFail($campaignID) : $campaignSelected = null;
        return view($this->view_base . '.create')->with($this->getSelectors())->with(compact('campaignSelected'));
    }

    /**
     * @param $id
     * @return Response
     */
    public function copy($id)
    {

        $ad = Advertisement::findOrFail($id);
        $ad_copy = $ad->replicate();
        $ad_copy->misc_content = "(copy)" . $this->buildName($ad_copy);
        $ad_copy->save();
        Cache::forget('Advertisements');
        flash()->success('Ad has been duplicated');
        return redirect('/advertisements');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
           'ad.category_id' => 'required',
            'ad.morphable_id' => 'required',
            'ad.sponsor_id' => 'required',
            'start_end_time' =>'required'
        ]);

        $exsisting_ad = (isset($request->ad['id']))? true : false;

        if ($exsisting_ad){
            return $this->update($request,$request->ad['id']);
        }
        else {
            $ad = new Advertisement;
        }

        if ($request->ad) {

            if ($request->active == "1")
                $ad->active = 1; else $ad->active = 0;

            foreach ($request->ad as $property => $value) {
                $ad->$property = (!is_array($value)) ? $value : end($value);
            }

            if (!is_numeric($ad->sponsor_id)) {
                $sponsor = new Sponsor;
                $sponsor->name = $ad->sponsor_id;
                $sponsor->url = filter_var($ad->url, FILTER_VALIDATE_URL);
                $sponsor->save();
                $ad->sponsor_id = $sponsor->id;

            } else {

                $sponsor = Sponsor::findorFail($ad->sponsor_id);

                if ($sponsor && $sponsor->url = "") {
                    $sponsor->url = filter_var($ad->url, FILTER_VALIDATE_URL);
                    $sponsor->save();
                }
            }
            if($request->hasFile('sponsor-logo')){
                $this->uploadImage($request,$sponsor,'sponsor-logo','logo');
            }
        }

        $start_end_check = $request->start_end_time;
        if (!empty($start_end_check)) {
            $times = explode('-', $request->start_end_time);
            $ad->starts_at = date('Y-m-d H:i:s', strtotime(trim($times[0])));
            $ad->ends_at = date('Y-m-d H:i:s', strtotime(trim($times[1])));
        }

//         $ad->save();

        if (!empty($request->platform)) {

            $platforms = $request->platform;

            for ($i = 0; $i < count($platforms); $i++) {
                $debug[] = $platforms[$i];
                $new_plat = $ad->replicate();
                $new_plat->misc_content = $ad->sponsor->name . ' on ' . $ad->name . '(' . $ad->platform->name . '|' . ucfirst(camel_case($ad->type_id)) . '): ' . Carbon::parse($ad->starts_at)->format('m/d') . ' - ' . Carbon::parse($ad->ends_at)->format('m/d');
                $new_plat->category_id = ($request->ad['category_id'][$i])? $request->ad['category_id'][$i] : 1;
                $category = AdvertisementCategory::find($new_plat->category_id);
                $new_plat->morphable_type = $category->morphable_type;
                $new_plat->morphable_id = ($request->ad['morphable_id'][$i]) ? $request->ad['morphable_id'][$i] : 0;
                $new_plat->type_id = $request->ad['type_id'][$i];
                $new_plat->alignment = (!is_null($request->ad['alignment'][$i])) ? $request->ad['alignment'][$i] : " ";
                $new_plat->url = $request->ad['url'];
                $new_plat->save();
                $ad->delete();


                if ($request->campaign_id) {
                    $this->makeCampaignItem($request,$new_plat);  //create campaign item attach to campaign and also advertised item;
                }

                if ($request->file('image')) {
                    $images = array_reverse($request->file('image'));

                    if (is_array($images)) {
                        $image = $images[$i];
                    } else $image = $images;

                    if (!is_null($image)) {
                        $extension = strtolower($image->getClientOriginalExtension());
                        if (in_array($extension, config('image.image_types'))) {
                            $destination = '/advertisements/' . $new_plat->id . '/' . uniqid('', FALSE) . "-" . preg_replace('/\s+/', '', $image->getClientOriginalName());
                            Image::upload(['model' => 'App\Advertisement', 'object_id' => $new_plat->id, 'file' => $image, 'destination' => $destination, 'extension' => $extension, 'size' => config('image.max-logo-size')]);
                        }
                    }
                }
            }
        }

        //does the video already exsist
        if ($request->brightcove_id && $request->platform == 4) {
            $video = new Video;
            $video->videoable_id = $new_plat->id;
            $video->videoable_type = 'App\Advertisement';
            $video->type_id = 'default';
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        }
        Cache::forget('Advertisements');
        flash()->success(count($platforms) . " Ads have been created.");
        return redirect(route($this->view_base . '.edit',$new_plat->id));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Advertisement::findOrFail($id);
        $sponsor = Sponsor::findOrFail($item->sponsor_id);
        # adjust time's for single field display
        $item->start_end_time = date('m/d/Y g:i A', strtotime($item->starts_at)) . ' - ' . date('m/d/Y g:i A', strtotime($item->ends_at));
        return view($this->view_base . '.edit')->with(compact('item', 'sponsor'))->with($this->getSelectors());
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     */
    public function updateDates(Request $request, $id)
    {

        $ad = Advertisement::findOrFail($id);
        $ad->starts_at = Carbon::parse($request->start_date)->format('m/d/Y g:i A');
        $ad->ends_at = Carbon::parse($request->end_date)->format('m/d/Y g:i A');
        $ad->save();
        flash()->success('Ad has been updated');
        return json_encode(['success' => TRUE, 'ad' => $ad]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);

        if ($request->ad) {

            if ($request->active == "1")
                $ad->active = 1; else $ad->active = 0;

            foreach ($request->ad as $property => $value) {
                $ad->$property = (!is_array($value)) ? $value : end($value);
            }

            $cat_id = ($request->ad["category_id"]) ? $request->ad["category_id"][0] : $request->ad["category_id"];

            $category = AdvertisementCategory::find($cat_id);
            $ad->category_id = $cat_id;
            $ad->morphable_type = $category->morphable_type;

            $ad->misc_content = $this->buildName($ad);


            if (!is_numeric($ad->sponsor_id)) {
                $sponsor = new Sponsor;
                $sponsor->name = $ad->sponsor_id;
                $sponsor->url = filter_var($ad->url, FILTER_VALIDATE_URL);
                $sponsor->link_target = $ad->link_target;
                $sponsor->save();
                $ad->sponsor_id = $sponsor->id;

            } else {

                $sponsor = Sponsor::findorFail($ad->sponsor_id);

                if ($sponsor && $sponsor->url = "") {
                    $sponsor->url = filter_var($ad->url, FILTER_VALIDATE_URL);
                    $sponsor->link_target = $ad->link_target;
                    $sponsor->save();
                }
            }
            if($request->hasFile('sponsor-logo')){
                $this->uploadImage($request,$sponsor,'sponsor-logo','logo');
            }
        }

        $start_end_check = $request->start_end_time;
        if (!empty($start_end_check)) {
            $times = explode('-', $request->start_end_time);
            $ad->starts_at = date('Y-m-d H:i:s', strtotime(trim($times[0])));
            $ad->ends_at = date('Y-m-d H:i:s', strtotime(trim($times[1])));
        }

        $ad->save();

       $this->makeCampaignItem($request,$ad);  //create campaign item attach to campaign and also advertised item;

       //does the video already exsist


        if (is_null($ad->video) && $request->brightcove_id) {
            $video = new Video;
            $video->videoable_id = $ad->id;
            $video->videoable_type = 'App\Advertisement';
            $video->type_id = 'default';
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        } elseif ($ad->video && ($ad->video->brightcove_id != $request->brightcove_id)) {
            $video = Video::findOrFail($ad->video->id);
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        }


        if ($request->file('image')) {
            # image upload
            $extension = strtolower($request->file('image')->getClientOriginalExtension());
            if (in_array($extension, config('image.image_types'))) {
                $destination = '/advertisements/' . $ad->id . '/' . uniqid('', FALSE) . "-" . preg_replace('/\s+/', '', ($request->file('image')->getClientOriginalName()));
                Image::upload(['model' => 'App\Advertisement', 'object_id' => $ad->id, 'file' => $request->file('image'), 'destination' => $destination, 'extension' => $extension, 'size' => config('image.max-logo-size')]);
            } else {
                flash()->error("File Type [" . $extension . "] NOT Accepted for an ad image");
                return redirect(route($this->view_base . '.edit', $ad->id));
            }

        }

        Cache::forget('Advertisements');
        flash()->success("Ads have been updated.");
        return redirect(route($this->view_base . '.edit', $ad->id));
    }

    /**
     * Export Excel file
     *
     * @return self
     */
    public function export($type, $start_date, $end_date)
    {

        ini_set('memory_limit', '1G');
        $ads = Advertisement::orderBy('id', 'asc')->whereBetween('ends_at', [$start_date, $end_date])->get(['id', 'url', 'headline', 'tagline', 'starts_at', 'ends_at', 'pod_title']);

        Excel::create('ION_AdvertismentSchedule-' . date('m-d-Y_H.i.s'), function ($excel) use ($ads) {
            $excel->sheet('entries', function ($sheet) use ($ads) {
                $entries = $ads;
                $columns = array_keys($entries[0]->getAttributes());
                $sheet->loadView('templates.export', ["columns" => $columns, "entries" => $entries, "ads" => $ads]);
            });

        })->download($type);

    }

    public function getSelectors()
    {
        $categories = AdvertisementCategory::all();
        $platforms = AdvertisementPlatform::all();
        $types = AdvertisementType::all();
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        $sponsors = Sponsor::orderBy("name")->get();
        return compact('categories', 'campaigns', 'platforms', 'sponsors', 'types');
    }


    /**
     * @param $id
     * @return string
     */
    public function refreshVideo($id)
    {
        $item = Advertisement::findOrFail($id);
        flash()->success('Video has been updated');
        $html = view('templates.partials.videoform')->with(['item' => $item])->render();
        return json_encode(['html' => $html]);
    }

    /**
     * @param $ad
     * @return string
     */
    public function buildName($ad)
    {

        return $ad->sponsor->name . ' on ' . $ad->advertisedItem->name . ' | ' .
            $ad->platform->name .
            (($ad->advertisedItem->type_id != null) ? ' | ' . ucfirst(camel_case($ad->advertisedItem->type_id)) : '') .
            ' | ' .
            (($ad->type) ? $ad->type->name : "Default") .
            ' | ' .
            Carbon::parse($ad->starts_at)->format('m/d') . ' - ' . Carbon::parse($ad->ends_at)->format('m/d');
    }

    /**
     * @return mixed
     */
    public function updateMisc()
    {
        $ads = Advertisement::active()->get();

        foreach ($ads as $ad) {
            $this->buildName($ad);
            $ad->save();
        }
        return ($ads);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $ad = Advertisement::findOrFail($id);

        if ($ad->campaignItem) {
            $item = CampaignItem::findOrFail($ad->campaignItem->id);
            $item->delete();
        }

        if ($ad->video) {
            $video = Video::findOrFail($ad->video->id);
            $video->remove($video->brightcove_id);
            $ad->delete();
            flash()->error('This advertisement and video has been removed. ');
        } else {
            $ad->delete();
            flash()->error('This advertisement has been removed. ');
        }
        Cache::forget('Advertisements');

        return redirect(route($this->view_base . '.index'));
    }

    private function uploadImage($request, $object, $file_name ='image', $type_id = 'default') {
        $image = $request->file($file_name);
        $extension =$image->getClientOriginalExtension();
        if(Image::upload(
            ['model' => get_class($object),
             'object_id' => $object->id,
             'file' => $request->file($file_name),
                $destination = '/advertisements/' . $object->id . '/' . uniqid('', FALSE) . "-" . preg_replace('/\s+/', '', ($request->file($file_name)->getClientOriginalName())),
             'type_id' => $type_id,
             'extension' => $extension,
                'size' => config('image.max-logo-size')
            ]))
            return TRUE;
        else return FALSE;
    }

    public function makeCampaignItem($request,$ad){

        CampaignItem::make($request, $ad);

        if($ad->morphable_type == "App\Post"){// attached advertised post to the campaign when creating an Ad for it.
            $new_request =  new Request;
            $new_request->campaign_id = $request->campaign_id;
            $post = Post::findOrFail($ad->morphable_id);
            CampaignItem::make($new_request,$post);
        }
        elseif($ad->morphable_type == "App\GridCell"){
            $new_request =  new Request;
            $new_request->campaign_id = $request->campaign_id;
            $pod = GridCell::findOrFail($ad->morphable_id);
            CampaignItem::make($new_request,$pod);
        }
    }
    

    /**
     * @param $platform_id
     * @param null $selected_item_id
     * @return mixed
     */
    public function getCategoryIdSelector($platform_id = 1, $selected_item_id = NULL)
    {
        $items = AdvertisementCategory::where("platform_id", $platform_id)->get();
        return View::make("advertisements.selectors.category-id", ["items" => $items, "selected_item_id" => $selected_item_id]);
    }

    /**
     * @param $type_id
     * @param null $selected_item_id
     * @param null $extra_param
     * @return mixed
     */
    public function getMorphableIdSelector($type_id = 1, $selected_item_id = NULL, $extra_param = NULL)
    {

        $category = AdvertisementCategory::find($type_id);
        $class = new $category->morphable_type();
        $query = call_user_func_array([$class, 'whereRaw'], [" 1=1 "]);
        $items = collect();

        switch ($type_id) {
            case 1: //Show
            case 9:
            case 15:
                $items = $query->where("type_id", "show")->orderBy("name")->get();
                break;
            case 2: //Movie
                $items = $query->movies()->orderBy("name")->get();
                break;
            case 3: //Page
                $items = $query->orderBy("title")->get();
                break;
            case 4: //Pod a.k.a GridCell
                $items = $query->orderBy("name")->get();
                break;
            case 5: //Recipe - ION Kitchen
                $items = $query->orderBy("title")->get();
                break;
            case 6: //Post - ION Kitchen
                $items = $query->kitchen()->recent()->orderBy("title")->get();
                break;
            case 7: //Post - ION @ Home
                $items = $query->home()->recent()->orderBy("title")->get();
                break;
            case 8: //Post - ION @ Home Project
                $items = $query->projects()->recent()->orderBy("title")->get();
                break;
            case 10: //Mobile App & iPad Views
            case 16:
                $items = $query->orderBy('name')->get();
                break;
            case 11: //Episodes for Mobile App & iPad
            case 14:
                $items = $query->where("airdate", ">=", Carbon::yesterday())->where("airdate", "<=", Carbon::now()->addDays(15))->orderBy("airdate")->get();
                break;
            case 12: //Post - Give Hope (Good Works & Partners)
                $items = $query->where("type_id", "good-works")->orderBy("title")->get();
                break;
            case 13: //Tip - ION @ Home
                $items = $query->orderBy("created_at", "desc")->get();
                break;
        }

        return View::make("advertisements.selectors.morphable-id", ["items" => $items, "selected_item_id" => $selected_item_id, "category" => $category]);

    }

}
