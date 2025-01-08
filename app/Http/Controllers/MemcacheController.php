<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\TestClass;
use \Cache;

class MemcacheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        echo TestClass::getInfo();
    }
	
	public static function test(){
				
		$memcache = memcache_connect('localhost', 11211);

		if ($memcache) {
			$memcache->set("str_key", "String to store in memcached");
			$memcache->set("num_key", 123);

			$object = new \StdClass;
			$object->attribute = 'test';
			$memcache->set("obj_key", $object);

			$array = Array('assoc'=>123, 345, 567);
			$memcache->set("arr_key", $array);

			var_dump($memcache->get('str_key'));
			var_dump($memcache->get('num_key'));
			var_dump($memcache->get('obj_key'));
		}
		else {
			echo "Connection to memcached failed";
		}
		
	}
	
	public static function test2(){
		
		$memcache = new \Memcache; // instantiating memcache extension class
		$memcache->connect("localhost",11211); // try 127.0.0.1 instead of localhost 
																				// if it is not working 
	 
		echo "Server's version: " . $memcache->getVersion() . "<br />\n";
	 
		// we will create an array which will be stored in cache serialized
		$testArray = array('horse', 'cow', 'pig');
		$tmp       = serialize($testArray);
	 
		$memcache->add("key", $tmp, false ,30);
	 
		echo "Data from the cache:<br />\n";
		print_r(unserialize($memcache->get("key")));
		
	}
	
	public static function test3(){
		
		Cache::put('my-key', 'my-value', 3);
		$value = Cache::get('my-key');
		
		echo "Data from the cache:<br />\n";
		print($value);
	}
	
	public static function debug(){
		
		
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
     * @return Response
     */
    public function store()
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
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
}
