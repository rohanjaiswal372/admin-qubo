<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Search;
use App\Show;

class SearchController extends Controller
{
    public $view_base = 'search';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $searches = Search::orderBy('count', 'DESC')->get();
        return view($this->view_base . '.index')->with(compact('searches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Removes Multiple the specified resource from storage.
     *
     * @param  Request $request
     * @param          $request ->ids -- array of ids to remove
     *
     * @return Response
     */
    public function multipleDestroy(Request $request)
    {

        Search::whereIn('id', $request->ids)->delete();
        $count = count($request->ids);
        flash()->error((($count > 1) ? $count . ' searches have' : $count . ' search has') . ' been removed');
        $data = Search::orderBy('id', 'desc')->paginate(50);
        $results = view($this->view_base . '.index')->with(['items' => $data])->render();
        return json_encode(['success' => true, 'results' => $results]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
