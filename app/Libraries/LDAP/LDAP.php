<?php  namespace App\Libraries\LDAP;

use \Config;
use \cURL;
use \Auth;
use \App\User;
use \Exception;
use \Hash;

class LDAP {
	
	public static function attempt($credentials){
		
		$url = Config::get("ionpromotool")["login_endpoint"];
		$response = cURL::newRequest('post', $url, $credentials )->send();
		$ldap_info = json_decode($response->body);	
			
		if(!empty($ldap_info->user)){
			
			$user = User::where("username","=",str_replace(array("ion\\","ION\\"),array("",""),$credentials["username"]))->first();
			
			if(!$user){
				$user = new User;
			}
			
			$user->username = $ldap_info->user->username;
			$user->firstname = $ldap_info->user->firstname;
			$user->lastname = $ldap_info->user->lastname;
			$user->email = $ldap_info->user->email;
			$user->type_id = "ion";
			$user->password =  Hash::make($credentials["password"]);
			
			try{
				$user->save();
			}catch(Exception $e){
				echo($e->getMessage());
			}

			Auth::loginUsingId($user->id);
			return true;
		}
		
		return false;
		
	}
	
	public static function all(){
		$url = Config::get("ionpromotool")["api_endpoint"]."user/list";
		$token = Config::get("ionpromotool")["api_token"];
		$response = cURL::newRequest('post', $url, ["token"=>$token ] )->send();
		return json_decode($response->body);	
	}
	
	public static function profile($username){
		if(empty($username)) return;
		$url = Config::get("ionpromotool")["api_endpoint"]."user/profile/{$username}";
		$token = Config::get("ionpromotool")["api_token"];
		$response = cURL::newRequest('post', $url, ["token"=>$token ] )->send();
		return json_decode($response->body);	
	}
	
	
}