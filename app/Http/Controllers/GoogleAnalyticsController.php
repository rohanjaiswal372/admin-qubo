<?php namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use \Config;
use App\Libraries\GoogleAnalytics\OAuth as OAuth;
/*
https://github.com/spatie/laravel-analytics
*/

class GoogleAnalyticsController extends Controller {

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

   public function __construct()
    {
		$this->middleware("auth.ion");
	  
		if (Auth::check() && !Auth::user()->hasPermission("google_analytics")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

	public function index(){
		$client_id = config("google-analytics.client-id");
		$client_email = config("google-analytics.client-email");
		$access_token = OAuth::getAccessToken();
		return view('google-analytics.dashboard')->with(compact('access_token','client_id','client_email'));
	}
	
}