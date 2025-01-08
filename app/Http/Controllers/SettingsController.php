<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\setting;
use Auth;

class SettingsController extends Controller
{

    public $view_base = 'settings';
	
   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("website_settings")) {
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
        $data = Setting::orderBy('setting', 'asc')->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
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
        
        $setting = new Setting;
        $setting->setting = $request->setting;
        $setting->type = $request->type;
        $setting->save();
        return redirect(route($this->view_base.'.edit', $setting->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Setting::findOrFail($id);
        return view($this->view_base.'.edit')->with(['item' => $item]);
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
        $setting = Setting::findOrFail($id);
        $setting->setting = $request->setting;
        if( $setting->type == 'switch' ){
            if( $request->on_off == 'on' ){
                $setting->on_off = 1;
            }else{
                $setting->on_off = 0;
            }
        }else{
            $setting->value = $request->value;
        }

        $setting->save();
        return redirect(route($this->view_base.'.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        # archive?
    }
}
