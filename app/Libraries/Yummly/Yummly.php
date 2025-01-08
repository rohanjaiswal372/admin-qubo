<?php

/**
 * Yummly
 * Pulls and organizes yummly recipes for ION
 */

namespace App\Libraries\Yummly;

use Config;
use Log;

class Yummly {

	var $api_url = '';
	var $yummly_id = '';
	var $yummly_key = '';

	function __construct(){http://api.yummly.com/v1/api/
		$this->api_url = config('yummly.api_url');
		$this->yummly_id = config('yummly.yummly_id');
		$this->yummly_key = config('yummly.yummly_key');
	}

	function recipe( $id ){

		if( empty($id) ){
			return false;
		}

		$ch = curl_init($this->api_url.'recipe/'.$id.'?_app_id='.$this->yummly_id.'&_app_key='.$this->yummly_key);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$result = curl_exec($ch);
		$result = json_decode($result);
		return $result;
	}
}

