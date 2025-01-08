<?php  namespace App\Libraries\ChannelFinder;

Class ChannelFinder {

	const api_endpoint = 'http://ionfeeds.channelfinder.net/xml/template/'; # api_endpoint url
	const source = 'Test'; # source - or id for channelfinder.net
	public static $private_key = null; # will hold our private key
	public static $error = array();
	
	function __construct()
	{
		
	}
	
	public static function init() {
		
	  if (is_null(self::$private_key) || empty(self::$private_key)){
		$call = self::api_endpoint.'IONTV_GetID.xml?source='.self::source;
		$result = self::request($call);
		
		if(empty($result->kpiid))
		{
			self::$error[] = 'Could not create kprivate_key code. Check your source.';
			return "empty";
		} else {
			self::$private_key = $result->kpiid;
		}	
	  }	
	}
	
	public static function getProviders($zipcode)
	{
		self::init();
		$call = self::api_endpoint.'IONTV_zip.xml?zip='.$zipcode.'&kprivate_key='.self::$private_key;
			
		$listings = self::request($call);
		$new_listings = array();
		if(count($listings->provider) > 0)
		{	
			foreach($listings->provider as $listing)
			{
				$new_listings[] = $listing;
			}
			return $new_listings;			
			
		}
		else
		{
			$this->error[] = 'No listings returned.';
			return false;
		}
	}
	
	public static function getProviderMessage($provider_id) {	
		self::init();
		$call = self::api_endpoint.'IONTV_ProviderMessage.xml?clientid='.$provider_id.'&kprivate_key='.self::$private_key;
		$result = self::request($call);
		return $result->clientinfo;
	}

	public static function submitEmail($email)
	{
		if(empty($email))
		{
			return false;
		}
		self::init();
		$call = self::api_endpoint.'IONTV_newsletter.xml?email='.$email.'&kprivate_key='.self::$private_key;
		$result = self::request($call);
		
		if($result->newsletter->result == 'success')
		{
			return true;
		}
		else
		{
			$this->error[] = $result->newsletter->message;
			return false;
		}
	}
	
	public static function request($call)
	{
		# if you want to use a CURL option instead of file_get_contents
		# uncomment the below code.
				
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $call);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    $output = curl_exec($ch);
	    curl_close($ch);
		return new \SimpleXMLElement($output,null,false);
	}
	
	function __debug()
	{
		if(count($error) > 0)
		{
			foreach($error as $er)
			{
				echo '-'.$er.'<br />';
			}
		}
	}
	
}


?>