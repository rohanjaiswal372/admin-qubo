<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Show;
use Auth;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("programming")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }
	 
	 
	 
    public function index()
    {
        $movies = Show::where('type_id','Movie')->orderBy("name","asc")->get();
        return view('movies.index')->withMovies($movies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $show = Show::findOrFail($id);
        return view('shows.edit')->with(['show' => $show]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $show = Show::findOrFail($id);
        return view('shows.edit')->with(['show' => $show]);
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
        $movie = Show::findOrFail($id);
        $movie->name = $request->name;
        $movie->headline = $request->headline;
        $movie->slug = $request->slug;
        $movie->short_name = $request->short_name;
        $movie->description = $request->description;
        $movie->broadview_handle = $request->broadview_handle;
        $movie->scope = $request->scope;

        if( $request->active == 'on' ){
            $movie->active = 1;
        }else{
            $movie->active = 0;
        }

        if( $request->featured == 'on' ){
            $movie->featured = 1;
        }else{
            $movie->featured = 0;
        }

        if( $request->holiday == 'on' ){
            $movie->holiday = 1;
        }else{
            $movie->holiday = 0;
        }

        $movie->save();

        return redirect(route('movies.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

	public function media($show_id){
		$object_id_selector = Show::movies()->orderBy("name")->get()->pluck("name","id")->toArray();
		$media_type_selector = Show::image_types()->pluck("name","id")->toArray();
        return view('movies.media.create')->with(["object_id_selector"=>$object_id_selector,
												 "show_id" =>$show_id,
												 "media_type_selector"=>$media_type_selector]);		
		
	}	
	
}
