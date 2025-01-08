<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\RescanAlert;
use \Input;
use App\Video;
use Auth;

class RescanAlertController extends Controller
{
   public $view_base = 'rescan-alerts';

   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("rescan_alerts")) {
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
        $data = RescanAlert::upcoming()->paginate(100);
        return view($this->view_base .'.index')->with(['items' => $data,'expired'=>false]);
    }
	
    /**
     * @param null $id
     * @return mixed
     */
    public function getExpired($id = null)
    {
        $data = RescanAlert::expired()->paginate(100);
        return view($this->view_base .'.index')->with(['items' => $data,'expired' => true]);
    }
	
    public function getPreview($id = null)
    {
		$login = config("windows-authentication");
        $page = RescanAlert::find($id);
		return curl_get(dev_site_url( $page->path , $auth = false, ($_SERVER["HTTPS"] == "on" ) ? true : false ),$login['username'],$login['password']);
    }		

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view($this->view_base.'.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        $rescan_alert = new RescanAlert;

        $rescan_alert->path = $request->path;
        $rescan_alert->name = $request->name;
        $rescan_alert->title = $request->title;
        $rescan_alert->content = $request->content;
        $rescan_alert->postal_codes = $request->postal_codes;
        $rescan_alert->modal_title = $request->modal_title;
        $rescan_alert->modal_content = $request->modal_content;
        $rescan_alert->meta_keywords = $request->meta_keywords;
        $rescan_alert->meta_description = $request->meta_description;
        $rescan_alert->starts_and_end_dates = $request->start_end_time;
        $rescan_alert->active = ($request->active) ?  "1":"0";
        $rescan_alert->navbar_active = ($request->navbar_active) ?  "1":"0";
        $rescan_alert->navbar_content = $request->navbar_content;		
        $rescan_alert->navbar_ubiquitous = ($request->navbar_ubiquitous) ?  "1":"0";
        $rescan_alert->content_geo_enabled = ($request->content_geo_enabled) ?  "1":"0";
        $rescan_alert->modal_geo_enabled = ($request->modal_geo_enabled) ?  "1":"0";
        $rescan_alert->modal_active = ($request->modal_active) ?  "1":"0";
			
        $rescan_alert->save();

        //does the video already exsist
        if ($request->brightcove_id) {
            $video = new Video;
            $video->videoable_id = $slide->id;
            $video->videoable_type = 'App\RescanAlert';
            $video->type_id ="default";
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        }
		
        flash()->success('This Rescan Alert has been Created');
        return redirect(route($this->view_base.'.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = RescanAlert::findOrFail($id);
        return view($this->view_base.'.edit')->with(compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $rescan_alert = RescanAlert::findOrFail($id);
       
        $rescan_alert->path = $request->path;
        $rescan_alert->name = $request->name;
        $rescan_alert->title = $request->title;
        $rescan_alert->content = $request->content;
        $rescan_alert->postal_codes = $request->postal_codes;
        $rescan_alert->modal_title = $request->modal_title;
        $rescan_alert->modal_content = $request->modal_content;
        $rescan_alert->meta_keywords = $request->meta_keywords;
        $rescan_alert->meta_description = $request->meta_description;
        $rescan_alert->starts_and_end_dates = $request->start_end_time;
        $rescan_alert->active = ($request->active) ?  "1":"0";
        $rescan_alert->navbar_active = ($request->navbar_active) ?  "1":"0";
        $rescan_alert->navbar_content = $request->navbar_content;		
        $rescan_alert->navbar_ubiquitous = ($request->navbar_ubiquitous) ?  "1":"0";
        $rescan_alert->content_geo_enabled = ($request->content_geo_enabled) ?  "1":"0";
        $rescan_alert->modal_geo_enabled = ($request->modal_geo_enabled) ?  "1":"0";
        $rescan_alert->modal_active = ($request->modal_active) ?  "1":"0";
			
        $rescan_alert->save();

        if (is_null($rescan_alert->video) && $request->brightcove_id) {
            $video = new Video;
            $video->videoable_id = $rescan_alert->id;
            $video->videoable_type = 'App\RescanAlert';
            $video->type_id ="default";
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        }
        elseif($rescan_alert->video && ($rescan_alert->video->brightcove_id != $request->brightcove_id)){
            $video = Video::findOrFail($rescan_alert->video->id);
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        }
       
        flash()->success('This Rescan Alert has been Updated');
        return redirect()->back();
    }

    public function refreshVideo($id)
    {
        $item = RescanAlert::findOrFail($id);
        flash()->success('Video has been updated');
        $html = view('templates.partials.videoform')->with(['item' => $item])->render();
        return json_encode(['success' => true, 'html' => $html]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $rescan_alert = RescanAlert::find($id);
        $rescan_alert->delete();
        flash()->error('This Rescan Alert has been deleted');
        return redirect(route($this->view_base.'.index'));
    }
}
