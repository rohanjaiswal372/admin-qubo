<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Contest;
use Auth;
use DB;
use Excel;

class ContestsController extends Controller
{
    public $view_base = 'contests';

    public function __construct()
    {
        if (Auth::check() && !Auth::user()->hasPermission("contests")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Contest::orderBy('start_date', 'DESC')->get();
        return view($this->view_base . '.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        flash()->success('New Contest Created');
        return back();
    }



    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $contest = Contest::get($slug);
        //$singularity_referer = $consest->singularity_referrer;
        $contest_table = $contest->database.'.dbo.'.$contest->contest_table;
        $entries = DB::table($contest_table)->orderBy($contest->date_referrer,"desc")->paginate(25);
		$columns = array_keys(get_object_vars($entries[0]));
        return view($this->view_base.'.results', ["columns"=>$columns,"entries"=>$entries,"contest"=>$contest] );
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
        $item = Contest::findOrFail($id);
        return view($this->view_base.'.edit')->with(['item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $contest = Contest::findOrFail($id);
        $contest->save($request->all());
        flash()->success("The contest has been updated");
        return back();

    }


    /**
     * Export Excel file
     *
     * @return self
     */
    public function getExport($slug)
    {
		
		ini_set('memory_limit','1G');		
		
		$contest = Contest::get($slug);
			
        Excel::create($contest->name, function($excel) use ($contest) {
            $excel->sheet('entries', function($sheet) use ($contest) {	
				$entries = $contest->entries();
				$columns = array_keys($entries[0]->getAttributes());
                $sheet->loadView($this->view_base.'.export', ["columns"=>$columns,"entries"=>$entries,"contest"=>$contest] );
            });

        })->download('xls');

    }
	
    public function destroy($contest,$id)
    {
        $contest = Contest::get($contest);

        $contest_table = $contest->database.'.dbo.'.$contest->contest_table;
        $entry = DB::table($contest_table)->where("id","=",$id);
        $entry->delete();
        flash()->error('Contest has been removed');
        return back();
    }
}
