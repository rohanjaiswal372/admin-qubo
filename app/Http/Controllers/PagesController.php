<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Page;
use App\Color;
use App\ColorType;
use \Input;
use Auth;

class PagesController extends Controller
{
	
	
   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("content_management")) {
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
        $data = Page::orderBy('title', 'asc')->get();
        return view('pages.index')->with(['items' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $color_types = ColorType::orderBy('sort_order')->get();
        return view('pages.create')->with(compact('color_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        $page = new Page;
        $page->title = $request->title;
        $page->content = $request->content;
        $page->active = ($request->active) ?  "1":"0";
        $page->searchable = ($request->searchable)  ? "1":"0";

        if( !Input::has("path")){
            $page->path = str_slug($page->title);
        }else{
            $page->path = Input::get("path");
        }

        $page->save();

        if($request->colors) {
            foreach ($request->colors as $color) {
                $pagecolor = New Color;
                $pagecolor->type_id = $color['type_id'];
                $pagecolor->code = $color['code'];
                $pagecolor->colorable_id = $page->id;
                $pagecolor->colorable_type = 'App\Page';
                $pagecolor->save();
            }
        }
        flash()->success('Page has been Created');
        return redirect(route('pages.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Page::findOrFail($id);
        $color_types = ColorType::orderBy('sort_order')->get();
        foreach ($color_types as $type) {

            $check = Color::where('colorable_id', '=', $id)->where('type_id', '=', $type->id)->where('colorable_type', '=', 'App\Page')->first();
            if (!$check) {
                $temp_color = new Color;
                $temp_color->type_id = $type->id;
                $temp_color->code = '#ffffff';
                $temp_color->colorable_id = $id;
                $temp_color->colorable_type = 'App\Page';
                $temp_color->save();
            }
        }
        return view('pages.edit')->withItem($item);
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
        $page = Page::findOrFail($id);
        $page->title = $request->title;
        $page->content = $request->content;
        $page->active = ($request->active) ?  "1":"0";
        $page->searchable = ($request->searchable)  ? "1":"0";
        if( !Input::has("path")){
            $page->path = str_slug($page->title);
        }else{
            $page->path = Input::get("path");
        }
        foreach ($request->colors as $color) {

            Color::updateOrCreate(['id' => $color['id']], ['type_id' => $color['type_id'], 'code' => $color['code'], 'colorable_id' => $id ,'colorable_type' => 'App\Page']);
        }
		
        $page->save();
        flash()->success('Page has been Updated');
        return redirect(route('pages.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $id = Page::find($id);
        $id->delete();
        flash()->error('Page has been deleted');
        return redirect(route('pages.index'));
    }
}
