<?php

namespace App\Http\Controllers;

use App\Mail\WeeklyAdUpdates;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Campaign;
use App\CampaignStatus;
use App\CampaignStatusType;
use App\CampaignItem;
use App\CampaignFollower;
use App\Advertisement;
use App\AdvertisementCategory;
use App\Sponsor;
use App\User;
use App\Image;
use App\Document;
use DB;
use Excel;
use PDF;
use Storage;
use Carbon;
use Auth;
use App\Mail\CampaignUpdated;
use App\Mail\WeeklyMeetingUpdates;
use Mail;
use View;

use ZipArchive;

class CampaignsController extends Controller {
    public $view_base = 'campaigns';

    /**
     * CampaignsController constructor.
     */
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
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $items = Campaign::orderBy('ends_at', 'desc')->whereBetween("ends_at", [Carbon::now(), Carbon::now()->addYear()])->get();
        $expired = FALSE;
        return view($this->view_base . '.index')->with(compact('items', 'expired'));
    }

    public function getExpired() {
        $items = Campaign::ended()->paginate(100);
        $expired = TRUE;
        return view($this->view_base . '.index')->with(compact('items', 'expired'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view($this->view_base . '.create')->with($this->getSelectors());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        if ($request->campaign) {
            $campaign = new Campaign;
            foreach ($request->campaign as $property => $value) {
                $campaign->$property = ($property != 'description') ? str_limit($value, 47) : $value;
            }

            if (!is_numeric($request->sponsor_id)) {
                $sponsor = new Sponsor;
                $sponsor->name = $request->sponsor_id;
                $sponsor->url = $request->sponsor['url'];
                $sponsor->link_target = (isset($request->sponsor['link_target'])) ? $request->sponsor['link_target'] : "_blank";
                $sponsor->save();
                $campaign->sponsor_id = $sponsor->id;
            } else {

                $sponsor = Sponsor::findorFail($request->sponsor_id);
                $campaign->sponsor_id = $sponsor->id;
                if ($sponsor && $sponsor->url = "") {
                    $sponsor->url = $request->sponsor['url'];
                    $sponsor->link_target = $request->sponsor['link_target'];
                    $sponsor->save();

                }
            }
            if ($request->hasFile('sponsor-logo')) {
                $this->uploadImage($request, $sponsor, 'sponsor-logo', 'logo');
            }
        }
        $campaign->save();

        if ($campaign) {
            $status = new CampaignStatus;
            $status->campaign_id = $campaign->id;
            $status->status_id = ($request['status_id']) ? $request['status_id'] : 'pending_creatives';
            $status->user_id = $campaign->user_id;
            if ($request['status_id'] == 'approved') {
                $campaign->approved = 1;
                $campaign->approver_id = Auth::user()->id;
            } else {
                $campaign->approved = 0;
                $campaign->approver_id = NULL;
            }
            $campaign->save();
            $status->save();

            if ($request->ads) {

                foreach ($request->ads as $ad) {
                    $campaign_ad = new CampaignItem;
                    $campaign_ad->campaign_id = $campaign->id;
                    $campaign_ad->advertisement_id = $ad;
                    $campaign_ad->save();
                }

            }
            if ($request->campaign_followers) {
                foreach ($request->campaign_followers as $follower) {
                    $campaign_follower = new CampaignFollower;
                    $campaign_follower->campaign_id = $campaign->id;
                    $campaign_follower->user_id = $follower;
                    // check to see if notifications are active and notify the user that they are now a part of this campaign.
                    $campaign_follower->save();
                }
            }
        }

        $this->emailUpdates($campaign->id, 'Created');

        flash()->success("Campaign has been created.");
        return view($this->view_base . '.edit')->with(compact('campaign'))->with($this->getSelectors());
        //        return redirect(route($this->view_base . '.edit'));
    }

    private function uploadImage($request, $object, $file_name = 'image', $type_id = 'default') {
        $image = $request->file($file_name);
        $extension = $image->getClientOriginalExtension();
        if (Image::upload(['model' => get_class($object), 'object_id' => $object->id, 'file' => $request->file($file_name), 'destination' => '/sponsors/' . $object->id . '/' . uniqid('', FALSE) . "-" . preg_replace('/\s+/', '', $image->getClientOriginalName()), 'type_id' => $type_id, 'extension' => $extension]))
            return TRUE; else return FALSE;
    }

    public function items($type = NULL) {

        if (is_null($type))
            $items = CampaignItem::all(); else
            $items = CampaignItem::where('itemable_type', $type)->get();
        return $items;

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $expired = TRUE) {
        if (is_numeric($id))
            $items = Campaign::findOrFail($id)->get(); //else return redirect()->back();
        else {

            $user = User::where('username', 'like', $id)->first();
            if (!is_null($user))
                $items = Campaign::whereUserId($user->id)->get(); else return redirect()->back();
        }
        return view($this->view_base . '.index')->with(compact('items', 'expired'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $campaign = Campaign::findOrFail($id);
        return view($this->view_base . '.edit')->with(compact('campaign'))->with($this->getSelectors());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $campaign = Campaign::findOrFail($id);

        $campaign->user_id = $request->user_id;

        if ($request->campaign) {

            foreach ($request->campaign as $property => $value) {
                $campaign->$property = ($property != 'description') ? str_limit($value, 47) : $value;

            }
        }

        if ($request->sponsor) {
            $sponsor_request = new Sponsor;
            $sponsor_request->sponsor_id = $request->sponsor['sponsor_id'];
            $sponsor_request->url = $request->sponsor['url'];
            $sponsor_request->link_target = (isset($request->sponsor['link_target'])) ? $request->sponsor['link_target'] : "_blank";

            if (!is_numeric($request->sponsor['sponsor_id'])) {
                $sponsor = new Sponsor;
                $sponsor->name = $sponsor_request->sponsor_id;
                $sponsor->url = $sponsor_request->url;
                $sponsor->save();

            } else {
                $sponsor = Sponsor::findorFail($request->sponsor['sponsor_id']);

                if ($sponsor && $sponsor->url = "") {
                    $sponsor->url = $sponsor->url;
                    $sponsor->save();
                }
            }
            if ($request->hasFile('sponsor-logo')) {
                $this->uploadImage($request, $sponsor, 'sponsor-logo', 'logo');
            }
            $campaign->sponsor_id = $sponsor->id;
        }

        $campaign->save();

        if ($request['status_id'] != $campaign->status->status_id) {

            $status = new CampaignStatus;
            $status->campaign_id = $campaign->id;
            $status->status_id = ($request['status_id']) ? $request['status_id'] : 'pending_creatives';
            if ($request['status_id'] == 'approved') {
                $campaign->approved = 1;
                $campaign->approver_id = Auth::user()->id;
            } else {
                $campaign->approved = 0;
                $campaign->approver_id = NULL;
            }
            $campaign->save();
            $status->user_id = $campaign->user_id;
            $status->save();
        }

        if ($request['ads']) {
            $exsisting_ads = $campaign->advertisements->toArray();

            $ad_ids = [];

            foreach ($exsisting_ads as $ads) {
                $ad_ids[] = $ads['advertisement_id'];
            }

            foreach ($request['ads'] as $ad) {
                if (!in_array($ad, $ad_ids)) {
                    $campaign_ad = new CampaignItem;
                    $campaign_ad->campaign_id = $campaign->id;
                    $campaign_ad->advertisement_id = $ad;
                    $campaign_ad->save();
                }
            }
        }

        if ($request['campaign_followers']) {

            $followers_exsisting = CampaignFollower::where('campaign_id', '=', $id)->get();
            //dd($followers_exsisting);
            foreach ($followers_exsisting as $follower) {

                // dd($follower->campaigns()->get());
                $follower->delete();
            }

            $followers = $request['campaign_followers'];
            foreach ($followers as $follower) {
                $campaign_follower = New CampaignFollower;
                $campaign_follower->campaign_id = $campaign->id;
                $campaign_follower->user_id = $follower;
                // check to see if notifications are active and notify the user that they are now a part of this campaign.
                $campaign_follower->save();
            }
        }
        //
        //        }
        //dd($campaign);
        $this->emailUpdates($campaign->id, 'Updated');

        flash()->success("Campaign has been updated.");
        return redirect()->back();
    }

    public function getSelectors() {
        $ads = Advertisement::all();
        $sponsors = Sponsor::orderBy("name")->get();
        $statuses = CampaignStatusType::all();
        $users = User::getGroup('marketing')->get(); // exclude names from selection
        return compact('ads', 'sponsors', 'statuses', 'users');
    }

    /**
     * Export Excel file
     *
     * @return self
     */
    public function export($id, $type = 'xls', $start_date = NULL, $end_date = NULL) {
        ini_set('memory_limit', '1G');

        if (is_null($start_date) && !is_null($id)) {
            $campaign = Campaign::findOrFail($id);
            $title = 'ION_' . $campaign->name . '-Report-' . date('m-d-Y_H.i.s') . '.pdf';
        } else {

            $start_date = Carbon::createFromFormat('m-d-Y', $start_date)->toDateTimeString();
            $end_date = Carbon::createFromFormat('m-d-Y', $end_date)->toDateTimeString();
            $campaign = Campaign::orderBy('ends_at', 'desc')->whereBetween("ends_at", [$start_date, $end_date])->get();
            $title = 'ION_Campaigns-Report-' . $start_date . '|' . $end_date . '.xls';
        }

        if ($type != 'pdf') {
            Excel::create($title, function ($excel) use ($campaign, $title) {

                $excel->setCreator(Auth::user()->fullname);
                $excel->sheet(str_limit($title, 28), function ($sheet) use ($campaign) {
                    $columns = array_keys($campaign->first()->getAttributes());
                    $sheet->loadView('templates.export', ["columns" => $columns, "entries" => $campaign]);
                });

            })->download($type);
        } else {
            $html = $this->getReport($id)->render();
            $pdf = PDF::loadHTML($html);
            return $pdf->stream($title);
        }

    }

    public function getReport($id) {
        $campaign = Campaign::findOrFail($id);

        $ads = $campaign->advertisements;

        //        dd($ads);
        $total_clicks = 0;
        $total_impressions = 0;

        foreach ($ads as $ad) {
            if ($ad->ad) {
                if ($ad->ad->category->platform->slug == "website") {
                    $total_clicks += $ad->ad->click;
                    $total_impressions += $ad->ad->impression;
                }
                if (!$ad->ad->previewExists()) {
                    $ad->ad->generatePreview();
                }
            }
        }
        return view($this->view_base . '.report')->with(compact('ads', 'campaign', 'total_clicks', 'total_impressions'));
    }

    /**
     * @param $id
     * @return array
     */
    function getCampaignSponsor($campaign_id) {
        $campaign = Campaign::findOrFail($campaign_id);
        $starts_at = Carbon::parse($campaign->starts_at)->format('m/d/Y g:i A');
        $ends_at = Carbon::parse($campaign->ends_at)->format('m/d/Y g:i A');
        return ["data" => $campaign->sponsor, "starts_at" => $starts_at, "ends_at" => $ends_at];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAssets($campaign_id) {
        $campaign = Campaign::findOrFail($campaign_id);
        if ($campaign->sponsor)
            $sponsor = $campaign->sponsor; else $sponsor = NULL;
        $campaign_assets = view($this->view_base . '.partials.assets')->with(compact('campaign'))->render();
        return json_encode(['campaign_assets' => $campaign_assets, 'sponsor' => $sponsor, 'times' => ['starts_at' => Carbon::parse($campaign->starts_at)->format('m/d/Y g:i A'), 'ends_at' => Carbon::parse($campaign->ends_at)->format('m/d/Y g:i A')]]);
    }

    /**
     * @param $id
     * @param $campaign
     * @return mixed
     */
    public function getDeleteCampaignAsset($id, $campaign, $type) {
        if ($type == 'image') {
            $image = Image::findorFail($id);
            $image->delete();
            $disk = Storage::disk("rackspace");
            $result = $disk->exists($image->url);
            if ($result) {
                $disk->delete($image->url);
            }
        } elseif ($type == 'document') {
            $document = Document::findorFail($id);
            $document->delete();
            $disk = Storage::disk("rackspace");
            $result = $disk->exists($document->path);
            if ($result) {
                $disk->delete($document->path);
            }
        }

        return $this->getAssets($campaign);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAssignCampaignAsset(Request $request) {

        $campaign = Campaign::findOrFail($request->campaignID);

        if (is_null($request->adID) || $request->adID == "" || !isset($request->adID)) {
            $ad = new Advertisement;
            $ad->morphable_id = $request->morphable_id;
            $category = AdvertisementCategory::find($request->type_id);
            $ad->morphable_type = $category->morphable_type;
            $ad->sponsor_id = $campaign->sponsor->id;
            $ad->save();

        } else {
            $ad = Advertisement::findOrFail($request->adID);
        }

        if ($request->campaignID) {
            CampaignItem::create(['campaign_id' => $campaign->id, 'itemable_id ' => $ad->id, 'itemable_type' => 'App\Advertisement']);
        }

        if (!is_null($ad->image)) {
            DB::table('images')->where("id", "=", $ad->image->id)->delete();
        }

        $img_asset = Image::findOrFail($request->assetID);

        $img_asset->url = str_replace(config('filesystems.disks.rackspace.public_url_ssl') . '/', '', $img_asset->url);  // some older attachments were getting the full url posted in the DB cleaning this out as we go.
        $img_asset->save();

        $image = new Image;
        $image->imageable_id = $ad->id;
        $image->imageable_type = "App\\Advertisement";
        $image->url = $img_asset->url;
        $image->type_id = 'default';
        $image->save();
        $item = (object)[];
        $item->id = $ad->id;
        $item->image = $image;
        return View::make("templates.partials.imageform", ["item" => $item, "sponsor" => ($ad->sponsor) ? $ad->sponsor : NULL]);
    }

    /**
     * @param $id
     * @param $campaign
     * @return mixed
     */
    public function getDownloadCampaignAsset($id, $campaignID) {

        $image = Image::findorFail($id);
        $campaign = Campaign::findOrFail($campaignID);

        $path = str_replace(config('filesystems.disks.rackspace.public_url_ssl'), '', $image->url);
        $extension = pathinfo(image($image->url), PATHINFO_EXTENSION);
        $filename = str_random(4) . '-' . str_slug($campaign->name) . '.' . $extension;
        $file = file_get_contents(image($path));

        $put = file_put_contents(public_path('uploads/' . $filename), $file);
        $zip = new ZipArchive();
        $zip->open($filename, ZipArchive::CREATE);
        $zip->addFile($put, 'filename.zip');
        $zip->close();

        switch (strtolower($extension)) {
            case 'pdf':
                $mime = 'application/pdf';
                break;
            case 'zip':
                $mime = 'application/zip';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            case 'jpeg':
            case 'jpg':
                $mime = 'image/jpg';
                break;
            default:
                $mime = 'application/force-download';
        }

        $headers = ['Content-Disposition' => 'attachment; filename=' . $filename, 'Content-Type' => 'application/zip',];

        if ($put)
            return response()->download(public_path('uploads/' . $filename), $filename, $headers);
    }

    /**
     * @param $id
     * @param $campaign_id
     * @return mixed
     */
    public function detach_ad($ad_id, $campaign_id) {

        $campaign = Campaign::findOrFail($campaign_id);
        $campaign_items = $campaign->items()->get();

        foreach ($campaign_items as $campaign_item) {
            if ($campaign_item->id == $ad_id) {
                $campaign_item->delete();
            }
        }
        return view($this->view_base . '.partials.associated-items')->with(compact('campaign'))->render();
    }

    public function purgeAdAssociations() {
        $campaign_ads = CampaignItem::all();

        $debug = [];

        foreach ($campaign_ads as $ad) {

            $check_ad = Advertisement::find($ad->advertisement_id);
            if (!$check_ad) {
                $debug[] = $ad;
                $remove = CampaignItem::where('itemable_id', $ad->id)->where('itemable_type', 'App\Advertisement')->delete();
                $debug[] = "Ad:" . $ad->id . ' Removed';
            }

        }

        return view('templates.output')->with(compact('debug'));
    }

    /**
     * @param $sponsor_id
     * @return array
     */
    public function getSponsorLogo($sponsor_id) {
        $sponsor = Sponsor::findOrFail($sponsor_id);
        if ($sponsor->logo) {
            return json_encode(['url' => $sponsor->logo->url, 'id' => $sponsor->logo->id]);
        } else return json_encode(["error" => TRUE]);
    }

    public function emailUpdates($id, $action = "") {

        $campaign = Campaign::findOrFail($id);

        $mail = (new CampaignUpdated($campaign, $action))->onQueue('CampaignUpdates');

        $followers = [];

        foreach ($campaign->followers as $follower) {
            if ($follower->user->id != Auth::user()->id)
                $followers[] = $follower->user->email;
        }

        if ($campaign->images) {

            foreach ($campaign->images as $key => $image) {
                $url = str_replace(config('filesystems.disks.rackspace.public_url_ssl'), '', preg_replace('/\s+/', '', $image->url));
                $mail->attach($image->url, ['as' => $url]);
            }
        }
        $user = Auth::user();

        Mail::to($user->email, $user->fullName)->cc($followers)->queue($mail);

        return view('emails.campaign-updated')->with(compact('campaign'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $campaign = Campaign::findorFail($id);
        $this->emailUpdates($campaign->id, 'Deleted/Canceled');
        if ($campaign->status) {
            $statuses = CampaignStatus::where('campaign_id', '=', $id)->get();
            foreach ($statuses as $status) {
                $status->delete();
            }
        }
        if ($campaign->advertisements) {
            $ads = CampaignItem::where('campaign_id', '=', $id)->get();
            foreach ($ads as $ad) {
                $ad->delete();
            }
        }
        if ($campaign->followers) {
            $followers = CampaignFollower::where('campaign_id', '=', $id)->get();
            foreach ($followers as $follower) {
                $follower->delete();
            }
        }

        $campaign->delete();
        flash()->error("This Campaign has been removed");
        return redirect(route($this->view_base . '.index'));
    }

    public function downloadPreview($campaign_id) {

        $campaign = Campaign::find($campaign_id);

        if ($campaign->advertisements->count()) {
            foreach ($campaign->advertisements as $item) {
                if ($item->ad && !$item->ad->previewExists()) {
                    $item->ad->generatePreview();
                }
            }
        }

        return $campaign->downloadPreview();
    }

    public function sendWeeklyUpdates($event_type = 'cron') {

        $starts_today = Campaign::approved()->where('starts_at', Carbon::today())->get();
        $starts_this_week = Campaign::approved()->thisWeek()->get();
        $starts_next_week = Campaign::approved()->nextWeek()->get();
        $recent = Campaign::recent()->get();

        $campaigns = compact('starts_today', 'starts_this_week', 'starts_next_week', 'recent');

        $action = "";
        $followers = [];

        if (environment('production')) {


            foreach ($recent as $campaign) {
                $persons = $campaign->followers;
                foreach ($persons as $follower) {
                    $followers[] = $follower->user->email;
                }
            }
            $followers = array_unique($followers);

        }

        $title = 'Campaigns for the week of ' . Carbon::now()->startOfWeek()->format('M jS') . " to " . Carbon::now()->startOfWeek()->addWeek()->format('M jS, Y');

        if ($event_type == 'cron') {

            $mail = (new WeeklyAdUpdates($title, $campaigns, $action, $followers))->onQueue('WeeklyAdUpdates');

            Mail::to('colinmichaels@ionmedia.com', 'ION Television Admin')->cc($followers)->queue($mail);
        }

        return view('emails.weekly-ad-updates')->with(compact('title', 'starts_today', 'starts_next_week', 'starts_this_week', 'recent', 'followers'));
    }

    public function sendWeeklyMeetingUpdates($event_type = NULL) {


        $starts_today = Campaign::approved()->where('starts_at', Carbon::today())->get();
        $starts_this_week = Campaign::approved()->thisWeek()->get();
        $starts_next_week = Campaign::approved()->nextWeek()->get();
        $recent = Campaign::recent()->get();

        $campaigns = compact('starts_today', 'starts_this_week', 'starts_next_week', 'recent');

        $action = "";
        $followers = [];

        if (environment('production')) {

            $followers[] = ["colinmichaels@ionmedia.com", "kelleekluthe@ionmedia.com"];
            $followers = array_unique($followers);

        }

        $title = 'Campaigns for the week of ' . Carbon::now()->startOfWeek()->format('M jS') . " to " . Carbon::now()->startOfWeek()->addWeek()->format('M jS, Y');

        if ($event_type == 'cron') {

            $mail = (new WeeklyMeetingUpdates($title, $campaigns, $action, $followers))->onQueue('WeeklyMeetingUpdates');


            Mail::to('colinmichaels@ionmedia.com', 'ION Television Admin')->cc($followers)->queue($mail);
        }

        return view('emails.weekly-meeting-updates')->with(compact('title', 'starts_today', 'starts_next_week', 'starts_this_week', 'recent', 'followers'));
    }
}
