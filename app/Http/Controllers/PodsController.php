<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Grid;
use App\GridCell;
use App\GridCellSchedule;
use Carbon;
use App\Show;
use App\Image;
use App\Campaign;
use App\CampaignItem;
use App\Libraries\FastImage\FastImage;

class PodsController extends Controller
{

    public $view_base = 'pods';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware("auth.ion");

        if (Auth::check() && !Auth::user()->hasPermission("content_management")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

    public function index()
    {
        $data = GridCell::orderBy('name', 'asc')->get();
        return view($this->view_base . '.index')->with(['items' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($campaignID = null)
    {
        $item = new GridCell;
        (!is_null($campaignID)) ? $campaignSelected = Campaign::findOrFail($campaignID) : $campaignSelected = null;
        return view($this->view_base . '.create')->with(compact('item','campaignSelected'))->with($this->getSelectors());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */

    public function edit($id)
    {
        $item = GridCell::find($id);
        return view($this->view_base . '.edit')->with(compact('item'))->with($this->getSelectors());
    }

    public function getSelectors(){
        $show_select = Show::where('type_id', 'show')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $movie_select = Show::where('type_id', 'movie')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $selects = ['Shows' => $show_select, 'Movies' => $movie_select];
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        return compact( 'selects', 'campaigns');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $pod = new GridCell;

        $pod->name = $request->name;
        $pod->title = $request->title;
        $pod->headline = $request->headline;
        $pod->path = $request->path;
        $pod->tagline = $request->tagline;
        $pod->show_id = $request->show_id;
        $pod->hyperlink = $request->hyperlink;

        $pod->pull_next_air = ($request->pull_next_air )? 1 : 0;
        $pod->hyperlink_target = ($request->hyperlink_target ) ? 1 :0;

        $pod->save();

        if(isset($request->campaign_id)) CampaignItem::make($request,$pod);

        if ($request->file('image')) {
            # image upload
            if (in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif', 'swf'])) {
                $destination = '/pod-graphics/pod-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Image::upload(['model' => 'App\GridCell', 'object_id' => $pod->id, 'type' => 'upload', 'file' => $request->file('image'), 'destination' => $destination]);
            }
        }
        flash()->success("This pod has been created");
        return redirect('/pods/' . $pod->id . '/edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $pod = GridCell::findOrFail($id);
        $pod->name = $request->name;
        $pod->title = $request->title;
        $pod->headline = $request->headline;
        $pod->path = $request->path;
        $pod->tagline = $request->tagline;
        $pod->show_id = $request->show_id;
        $pod->hyperlink = $request->hyperlink;
        $pod->pull_next_air = ($request->pull_next_air )? 1 : 0;
        $pod->hyperlink_target = ($request->hyperlink_target ) ? 1 :0;

        $pod->save();

        if(isset($request->campaign_id)) CampaignItem::make($request,$pod);

        if ($request->file('image')) {
            # image upload
            if (in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'png', 'gif', 'swf'])) {
                $destination = '/pod-graphics/pod-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Image::upload(['model' => 'App\GridCell', 'object_id' => $pod->id, 'file' => $request->file('image'), 'destination' => $destination]);
            }
        }
        flash()->success("This pod has been updated");
        return redirect('/pods/' . $pod->id . '/edit');
    }

    /**
     * Shows current grid pods
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

        $grid = Grid::get($id);

        $all_pods = GridCell::orderBy('name', 'asc')->get();
        $start_date = Carbon::yesterday();
        $end_date = NULL;

        try {
            $end_date = (GridCellSchedule::last($grid->id)->starts_at->gt(Carbon::today())) ? GridCellSchedule::last($grid->id)->starts_at : Carbon::today();
        }
        catch (\Exception $e) {

        }

        $scheduled_pods = [];

        if (!is_null($end_date)) {
            while ($end_date->gt($start_date)) {
                $start_date->addDays(1);
                $scheduled_pods[$start_date->format("Y-m-d")] = $grid->getCells($start_date);
            }
        }

        return view($this->view_base . '.show')->with(['grid' => $grid, 'all_pods' => $all_pods, "scheduled_pods" => $scheduled_pods]);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function duplicate($id)
    {
        $grid = GridCell::findOrFail($id);
        $new =  $grid->replicate();
        $new->save();
        flash()->success("This pod has been duplicated");
        return redirect('/pods/' . $new->id. '/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $grid = GridCell::findOrFail($id);
        if ($grid->campaignItem) {
            $item = CampaignItem::findOrFail($grid->campaignItem->id);
            $item->delete();
        }

        $grid->delete();
        flash()->error("This pod has been deleted");
        return redirect()->back();
    }
}
