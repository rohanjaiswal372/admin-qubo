<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Tag;
use View;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id,$tag,$class)
    {
        $appclass = "App\\".ucfirst($class);
        $tags = $appclass::findOrFail($id);
        $tags->setTags($tag);
        $items = $tags->allTags()->get();
        return View::make("tags.partials.list", ["items" => $items, 'class' =>$class]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($class)
    {
        $appclass = "App\\".ucfirst($class);
        $tags = new $appclass;
        $objects = $tags->all();
        $items = $tags->allTags()->get();
        return view('tags.index')->with(compact('items','class','objects'));


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id, $class){

        $tag = Tag::findOrFail($id);
        $tag->delete();
        //flash()->error('Tag has been deleted');
        $appclass = "App\\".ucfirst($class);
        $tags = new $appclass;
        $items = $tags->allTags()->get();
        return View::make("tags.partials.list", ["items" => $items]);



    }


}
