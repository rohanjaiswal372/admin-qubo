<?php

/**
 * Gigya
 *
 * A simple wrapper for the Gigya SDK
 *
 * @author Aaron Connelly
 * @version 0.1
 */

namespace App\Libraries\Gigya;

use \Config;
use Exception;
use Session;

# require Gigya SDK
require dirname(__FILE__).'/GigyaSDK/GSSDK.php';

use \SigUtils;

class Gigya {

	const VERSION = '0.1';
	protected $gigya_secret = null;
	protected $gigya_api_key = null;
 
	/**
	* Creates a new Gigya object
	*
	* @param string $config, the configuration to use for this request
	* @return void
	*/
	public function __construct($config=array()){
		# TODO: Provide override via config
		$this->gigya_secret = config('gigya.gigya_secret');
		$this->gigya_api_key = config('gigya.gigya_api_key');
	}

	/**
	 * Log a user into our system based on Gigyas return
	 *
	 * @param   object  the users object returned from gigya
	 * @return  boolean
	 */
	public function login($user_object){

		$user_object = json_decode($user_object);

		if( !$user_object ) throw new Exception('Invalid json');

		if( !is_object($user_object) || count($user_object) == 0 ) throw new Exception('Invalid user object');

		# check our signatures from gigya
		if(!SigUtils::validateUserSignature($user_object->UID, $user_object->signatureTimestamp, $this->gigya_secret, $user_object->UIDSignature)){
			throw new Exception('Invalid signature');
		}else{
			# TODO: Check admin panel to make sure this user is valid and can login - check ban.

			# store into gigya session
			$user_array =  array(
				'uid' => $user_object->UID,
				'provider' => $user_object->provider);

			foreach( $user_object->profile as $key => $profile ){
				$user_array[strtolower($key)] = $profile;
			}

			return $user_array;

			# TODO: Provide points for provider login
			#$Nitro->setNewLogin(array('uid' => $user_object->UID, 'nickname' => $user_object->profile->firstName, 'provider' => $user_object->provider));	
		}
	}

	/**
	 * Checks if the user is logged into the Gigya Platform
	 *
	 * @return boolean
	 */
	public static function isLoggedIn(){

		$user = Session::get('gigya');
		if( !is_null($user) && !empty($user) ){
			return true;
		}

		return false;
  	}


}
