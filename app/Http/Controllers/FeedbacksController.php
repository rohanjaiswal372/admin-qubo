<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feedback;
use Auth;
use App\FeedbackSubject;
use App\Station;
use Excel;
use DB;
use Carbon;
use StdClass;

class FeedbacksController extends Controller
{

    public $view_base = 'feedbacks';

    public function __construct()
    {
        if (Auth::check() && !Auth::user()->hasPermission("audience_relations")) {
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
        $start_date = Carbon::now()->subDays(7)->toDateString();
        $end_date = Carbon::now()->addDays(1)->toDateString();
        $items = Feedback::with('subject')->orderBy('created_at', 'desc')->whereBetween('created_at', [$start_date, $end_date])->get();
        return view($this->view_base.'.index')->with(['items' => $items]);
    }

    /**
     * show specific date range - ajax request from date pickers
     *
     * @return Response
     */

    public function highlight($string, $term)
    {
        $term = preg_replace('/\s+/', ' ', trim($term));
        $words = explode(' ', $term);

        $highlighted = array();
        foreach ($words as $word) {
            $highlighted[] = "<span class='highlight'>" . $word . "</span>";
        }

        return str_replace($words, $highlighted, $string);
    }

    public function show(Request $request)
    {

        if (isset($request['start_date']) && !empty($request['start_date'])) $start_date = $request['start_date'];
        else $start_date = Carbon::now()->subDays(7)->toDateString();
        if (isset($request['end_date']) && !empty($request['end_date'])) $end_date = $request['end_date'];
        else $end_date = Carbon::now()->toDateString();
        if (isset($request['search'])) $search = $request['search'];
        else $search = "";
        if(isset($request['num_results'])) $num_results = $request['num_results'];
        else $num_results = 25;
        if ($search != "") {
            $data = Feedback::with('subject')->where("message", "like", "%{$search}%")
                ->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'asc')->paginate($num_results);
        } else
            $data = Feedback::with('subject')->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'asc')->paginate($num_results);

        $html = view($this->view_base . '.results')->with(['items' => $data])->with(['start_date' => $start_date])->with(['end_date' => $end_date])->render();

        $html = $this->highlight($html, $search);
        return response()->json(array('success' => true, 'html' => $html));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $subjects = $this->getSubjects();
        $markets = $this->getMarkets();
        return view($this->view_base . '.create', compact('subjects','markets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {

        Feedback::create($request->all());
        flash()->success('New feedback created');

        return redirect(url('audience-relations/feedbacks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $item = Feedback::findOrFail($id);
        $subjects = $this->getSubjects();
        $markets = $this->getMarkets();
        return view($this->view_base . '.edit')->with(compact('item','subjects', 'markets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->subject_id = $request->subject_id;
        $feedback->firstname = $request->firstname;
        $feedback->lastname = $request->lastname;
        $feedback->email = $request->email;
        $feedback->birthyear = $request->birthyear;
        $feedback->city = $request->city;
        $feedback->state = $request->state;
        $feedback->city = $request->city;
        $feedback->state = $request->state;
        $feedback->zipcode = $request->zipcode;
        $feedback->market = $request->market;
        $feedback->provider = $request->provider;
        $feedback->channel_number = $request->channel_number;
        $feedback->format = $request->format;
        $feedback->newsletter = $request->newsletter;
        $feedback->duration = $request->duration;
        $feedback->message = $request->message;
        $feedback->save();
        flash()->success("Feedback has been updated");
        return redirect(route($this->view_base . '.index'));
    }

    /**
     * Export Excel file
     *
     * @return self
     */
    public function export($type, $start_date, $end_date)
    {
        ini_set('memory_limit', '1G');
        $fb = Feedback::orderBy('id', 'asc')->whereBetween('created_at' ,[$start_date,$end_date])->get();

        $feedbacks = new Feedback;

        $feedbacks = collect($feedbacks);

        foreach($fb as $feedback){
            $item = new Feedback;
            $item->id = $feedback->id;
            $item->subject = ($feedback->subject)? $feedback->subject->name : "N/A";
            $item->firstname = $feedback->firstname;
            $item->lastname = $feedback->lastname;
            $item->email = $feedback->email;
            $item->phone = $feedback->phone;
            $item->city = $feedback->city;
            $item->state = $feedback->state;
            $item->zipcode = $feedback->zipcode;
            $item->market = $feedback->market;
            $item->provider = $feedback->provider;
            $item->channel_number = $feedback->channel_number;
            $item->newsletter = ($feedback->newsletter)? "Yes" : "No";
            $item->message = $feedback->message;
            $item->duration = (isset($feedback->duration))? $feedback->duration : "-";
            $item->date = Carbon::parse($feedback->created_at)->format('D m/d g:i a');
            $feedbacks->push($item);
        }

        Excel::create('ION_Audience_Relations_Feedbacks-' . date('m-d-Y_H.i.s'), function ($excel) use ($feedbacks) {
            $excel->sheet('entries', function ($sheet) use ($feedbacks) {
                $columns = ["id","Date","Subject","firstname","lastname", "Email", "Phone", "City","State","Zipcode","Market","Provider","channel_number", "Newsletter","Duration","Message"];
                $sheet->loadView('templates.export', ["columns" => $columns, "entries" => $feedbacks]);
                $sheet->freezeFirstRow();
            });



        })->download($type);

    }


    public function getMarkets()
    {
        $results = Station::orderBy("market")->get();

        foreach ($results as $data) {
            $markets[$data->stationcall] = $data->market . " (" . $data->stationcall . "- TV)";
        }
        return array_merge(["" => "Select One"], $markets);
    }

    public function getSubjects(){

        $subjects =  FeedbackSubject::all()->pluck('name' ,'id')->toArray();
        return array_merge(["" => "Select One"], $subjects);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        flash()->error("Entry <strong>" . $id . "</strong> has been deleted");
        return redirect()->back();
    }
}
