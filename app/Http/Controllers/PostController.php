<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \Exception;
use \Request;
use App\Post;


class PostController extends Controller {
    /**
     * Show the form the create a new blog post.
     *
     * @return Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a new blog post.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
        'title' => 'required|max:20',
        'description' => 'required',
		'g-recaptcha-response' => 'required|captcha'
		]);
		
		//dd($request);
		dd(Request::all());
    }


	 
}