<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use Auth;
use App\Image;

class OrdersController extends Controller
{

    public $view_base = 'orders';

	
   public function __construct()
    {
		$this->middleware("auth.ion");
	  
        if (Auth::check() && !Auth::user()->hasPermission("ion_lounge")) {
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
        $data = Order::orderBy('created_at', 'desc')->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Order::with('prizes')->findOrFail($id);
        return view($this->view_base.'.edit')->with(['item' => $item]);
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
        $order = Order::findOrFail($id);
        $order->address = $request->address;
        $order->city = $request->city;
        $order->state = $request->state;
        $order->zip = $request->zip;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->full_name = $request->full_name;
        $order->status = $request->status;
        $order->save();

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

    }
}
