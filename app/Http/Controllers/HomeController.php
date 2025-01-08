<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Show;
use Carbon;
use Session;
use App\Stat;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{
	
	public function __construct()
	{
		$this->middleware("auth.ion");
	}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     *
     */
    public function index()
    {
        $stats = new Stat;
        $stats->users = $stats->usersCount();
        $stats->shows = $stats->showsCount();
        $new_shows = Show::where('new', 1)->get();
        return view('home')->with(compact('stats','new_shows'));
    }

}
