<?php  namespace App\Libraries\GoogleAnalytics;

set_include_path(base_path()."\vendor\google\apiclient\src\\" . PATH_SEPARATOR . get_include_path());

require_once 'Google/Client.php';
require_once 'Google/Auth/AssertionCredentials.php';

use \cURL;
use \URL;

class OAuth {
	
	public static function getAccessToken(){
		

		$client_email = config('google-analytics.client-email');
		$private_key = file_get_contents(config('google-analytics.certificate-path'));
		$scopes =  config('google-analytics.scopes');
		$credentials = new \Google_Auth_AssertionCredentials($client_email,$scopes,$private_key);

		$client = new \Google_Client();
		
		$client->setAssertionCredentials($credentials);
		if ($client->getAuth()->isAccessTokenExpired()) {
		  $client->getAuth()->refreshTokenWithAssertion();
		}
		
		$response =  json_decode($client->getAccessToken(),true);
		$response["value"] = $response["access_token"];
		unset($response["access_token"]);
		return (object) $response;
	}
	
}