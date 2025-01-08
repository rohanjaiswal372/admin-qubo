<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Game;
use Auth;
use App\Image;

class GamesController extends Controller
{

    public $view_base = 'games';

	
   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("games")) {
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
        $data = Game::orderBy('title', 'asc')->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    public function show(){
        return redirect(route($this->view_base.'.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $game_tags = Game::allTags()->get();
        return view($this->view_base.'.create')->with(compact('game_tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $game = new Game;
        $game->title = $request->title;
        $game->embed_id = ($request->embed_id)?$request->embed_id : "";
        $game->scope = ($request->scope)? strtolower($request->scope): "qubo";
        $game->description = $request->description;
        if( empty($request->path) ){
            $game->path = str_slug($request->title);
        }else{
            $game->path = $request->path;
        }
        $game->sort_order = $request->sort_order;
        if( $request->active == 'on' ){
            $game->active = 1;
        }else{
            $game->active = 0;
        }

        $game->save();

        if($request->tags){
               $game->tag($request->tags);
        }

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $destination = '/games/'.$game->path.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Game', 'object_id' => $game->id, 'file' => $request->file('image'), 'destination' => $destination] );
            }
        }

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
        $item = Game::with('imagePrimary')->findOrFail($id);
        $game_tags = $item->allTags()->get();
        return view($this->view_base.'.edit')->with(compact('item','game_tags'));
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
        $game = Game::findOrFail($id);
        $game->title = $request->title;
        $game->description = $request->description;
        $game->embed_id = $request->embed_id;
        $game->scope = $request->scope;
        $game->sort_order = $request->sort_order;
        if( $request->active == 'on' ){
            $game->active = 1;
        }else{
            $game->active = 0;
        }
        if( empty($request->path) ){
            $game->path = str_slug($request->title);
        }else{
            $game->path = $request->path;
        }
        $game->save();

        if($request->tags){
               $game->setTags(implode(',', $request->tags));
        }

        if( $request->file('image') ){
            # image upload
            if( in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif']) ){
                $destination = '/games/'.$game->path.'-image-'.time().'.'.$request->file('image')->getClientOriginalExtension();
                Image::upload( ['model' => 'App\Game', 'object_id' => $game->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $destination] );
            }
        }

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
        $game = Game::findOrFail($id);
        //remove all associated tags
        $game->untag();
        $game->delete();
        flash()->error('The game, '.$game->title.' has been deleted');
        return redirect(route($this->view_base.'.index'));

    }
}
