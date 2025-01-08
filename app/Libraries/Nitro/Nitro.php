<?php 

/**
 * Nitro
 *
 * A wrapper for Nitro API
 *
 * @author Aaron Connelly
 * @version 0.1
 */

namespace App\Libraries\Nitro;

use \Config;
use Exception;
use Session;
use Log;
use App\Setting;
use Cache;
use Carbon;

class Nitro {
	
	# api information
	var $secret_key = '';
	var $api_key = '';
	var $connection_url = '';
	var $data_type = 'json'; # how will data be returned by Bunchball, xml is another valid resource however this class only supports json
	
	# session information user specific
	var $session_key = null;
	var $uid = '';
	var $fname = 'Visitor';
	var $lname = '';
	var $default_user_id = '!aabcd123!23#MjXSKJ9389AmEJ#!';
	var $anon = false;
	
	# whitelist for providers to give points to upon login
	var $provider_whitelist = [];
	
	# random
	var $reset_flag = '';
	
	function __construct( $insiders = false, $config = null ){
		# TODO: Put in config overrides
		$this->api_key = config('nitro.api_key');
		$this->secret_key = config('nitro.nitro_secret');
		$this->connection_url = config('nitro.api_endpoint');
		$this->deafult_user_id = config('nitro.default_user_id');
		$this->provider_whitelist = config('nitro.provider_whitelist');
	}
	
	function setApiKeys($data){
		$this->secret_key = $data['secret_key'];
		$this->api_key = $data['api_key'];
		$this->connection_url = $data['connection_url'];
	}
	
	function getUID(){

		# see if we have a saved / logged in uid
		$saved_uid = Session::get('nitro_uid');
		if( !empty($saved_uid) && !is_null($saved_uid) ){
			return $saved_uid;
		}elseif( !empty($this->uid) && !is_null($this->uid) ){
			return $this->uid;
		}else{
			# we have nothing, so send back generic / anon uid
			return md5($this->default_user_id);

			# TODO: Old code had login here, with session key collection, not sure that is the way to do it so leaving out for now.
		}
	}
	
	/**
	 * getSessionKey
	 * this function pulls the logged in user session key which is required by Nitro to send and receive information.
	 *
	 * @response  string  session key
	 */
	function getSessionKey(){

		$session_key = Session::get('nitro_session_key');

		if( !empty($session_key) && !is_null($session_key) ){
			return $session_key;
		}else{
			if( !empty($this->session_key ) ){
				return $this->session_key;
			}else{
				# login user in default
				$this->login(null);
				return $this->session_key;
			}
			return '';
		}
	}
	
	function getSignature(){
		$time = time();
		$unencryptedSignature = $this->api_key.$this->secret_key.$time.$this->getUID();
		$length = strlen($unencryptedSignature);
		$unencryptedSignature = $unencryptedSignature.$length;
  		$signature = md5($unencryptedSignature);
  		$result = array('time' => $time, 'signature' => $signature);
  		return $result;
	}
	
	/**
	 * isAnonymous
	 * checks to see if the user is anon or not. We only want to log actions for active users
	 *
     * @response  boolean
	 */
	function isAnonymous(){
		$anon = Session::get('nitro_anonymous');
		if( !is_null($anon) && $anon ){
			return true;
		}
		return false;
	}
	
	/**
	 * logout
	 * logs the user out only via sessions
	 */
	function logout(){
		Session::forget('nitro_anonymous');
		Session::forget('nitro_session_key');
		Session::forget('nitro_uid');
		Session::save();
		
		$this->uid = '';
		$this->session_key = '';
	}
	
	function __uniqueHash(){
		$rand = (rand(0,100000)/2)-100+(rand(5000,10000));
		$rand = str_replace('a', 2, $rand);
		$rand = str_replace('Z', 8, $rand);
		$rand = str_replace('D', 00, $rand);
		
		$hash = md5(md5(time().$rand.'-'.$rand.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['HTTP_USER_AGENT']).$this->secret_hash_key);
		return $hash;
	}
	
	function login($user_data = null) {
		
		if( !isset($user_data['uid']) || is_null( $user_data['uid'] ) ){
			# nope - time to go anonymous - stealth mode!
			$uid = md5($this->default_user_id);
			Session::put('nitro_anonymous', true);
			$this->uid = $uid;
			$this->fname = 'Visitor';
			$this->lname = '';
			$this->anon = true;
		} else {
			$uid = md5($user_data['uid']);
			$this->uid = $uid;
			Session::put('nitro_anonymous', false);
			Session::put('nitro_uid', $uid);
			Session::save();
			$this->anon = false;
		}
	
		$signature = $this->getSignature();

      	$request = $this->connection_url.$this->data_type.'/?'.
	               "method=user.login".
	               "&apiKey=".$this->api_key.
	               "&userId=".$this->uid.
	               "&ts=".$signature['time'].
	               "&sig=".$signature['signature'].
      			   "&firstName=".$this->fname.
      			   "&lastName=".$this->lname;
      	$request = file_get_contents($request);
	    $data = json_decode($request);

	    if( $data->Nitro->res == 'err' ){
	    	Log::debug('Error logging user in. UID: '.$this->uid.' | GIGYA UID: '.$user_data['uid']);
	    	return false;
	    }

	    # TODO: Are we getting something good back from Nitro?
	    Session::put('nitro_session_key', $data->Nitro->Login->sessionKey);
		Session::save();

		$this->session_key = $data->Nitro->Login->sessionKey;

		if( !$this->anon ){
			# grab Gigya session
			$gigya_session = Session::get('gigya');
			$gigya_session['points'] = number_format($this->getPoints());
			Session::put('gigya', $gigya_session);
			Session::save();

			$this->logAction('USR_LOGIN');
		    if(isset($user_data['provider']) && in_array($user_data['provider'], $this->provider_whitelist)){
				# good provider - call action
				$this->logAction('LOGIN_'.strtoupper($user_data['provider']));
			}
		}

	    return $this->session_key;   
	}
	
	function avatarLink($return = false){
		if($return){
			return "http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$this->uid."/thumb.png?cat=DEFAULT_AVATAR";
		}else{
			echo "http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$this->uid."/thumb.png?cat=DEFAULT_AVATAR";
		}
	}
	
	function avatarLinkUser($uid, $return = false){
		if($return){
			return "http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$uid."/thumb.png?cat=DEFAULT_AVATAR";
		}else{
			echo "http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$uid."/thumb.png?cat=DEFAULT_AVATAR";
		}
	}
	
	function profilePicture($uid){
		#echo $uid.' - ';
		#$result = sha1_file("http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$uid."/thumb.png?cat=DEFAULT_AVATAR");
		#echo $result;
		#echo '<BR>';
		return "http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$uid."/thumb.png?cat=DEFAULT_AVATAR";
	}
	
	function getPreference($uid, $field){
		
		$request = $this->connection_url.$this->data_type.'/?'.
			            "method=user.getPreference".
						"&sessionKey=".$this->getSessionKey().
						"&name=gigyaID".
						"&userId=".$uid;
		$request = file_get_contents($request);
		$data = json_decode($request);
	  	if($data->Nitro->res == 'err'){
			return false;
		}else{
			return $data->Nitro->userPreferences->UserPreference->value;
		}
	}
	
	function getUserActions($uid = null, $limit = 15){
		if( is_nul($uid) ){
			$uid = $this->getUID();
		}
		$request = $this->connection_url.$this->data_type.'/?'.
			            "method=site.getRecentChallenges".
						"&sessionKey=".$this->getSessionKey().
						"&userIds=".$uid.
						"&returnCount=".$limit;
		
		$request = file_get_contents($request);
		$data = json_decode($request);
	  	if($data->Nitro->res == 'err'){
			return false;
		}else{
			$already_have = array();
			$already_have_other = array();
			$last_key = $key;
			$challenges = array(
				'count' => 0,
				'list' => array());
			$today = date('Y-m-d');
			foreach($data->Nitro->challenges->Challenge as $key => $challenge){
				if($today == date('Y-m-d', $challenge->ts)){
					if(!in_array($challenge->actionPhrase, $already_have)){
						$challenges['list'][] = $challenge;
						$challenges['count']++;
						$already_have[] = $challenge->actionPhrase;	
					}
				}
			}
			
			if($challenges['count'] < 5){
				$diff_count = 5 - $challenges['count'];
				# add some other days
				foreach($data->Nitro->challenges->Challenge as $key => $challenge){
					if($today != date('Y-m-d', $challenge->ts)){
						if(!in_array($challenge->actionPhrase, $already_have_other)){
							if($diff_count == 0) break;
							
							$challenges['list'][] = $challenge;
							$challenges['count']++;
							$already_have_other[] = $challenge->actionPhrase;	
							$diff_count--;
						}
					}
				}
			}
			
			return $challenges;
		}
	}
	
	function prizesPurchased($uid){
		$Core = new Core($this->Config["database:contest-writer"]);
		$uid = $Core->quote($uid);
		$query = array(
			'table' => 'iontv_lounge_orders',
			'where' => 'uid='.$uid,
			'select' => 'COUNT(*) AS totalOrders'
		);
		$result = $Core->get($query);
		if(isset($result['totalOrders'])){
			return $result['totalOrders'];
		}
		return null;
	}
	
	function getProfile($uid){
		$Gigya = new Gigya;
		$lounge_room = new LoungeRoom;
		
		if(empty($uid))
			return false;
		
		$uid = trim(strip_tags($uid));
		
		$lounge_url = 'http://dynamic.bunchball.net/assets/canvas/'.$this->canvasPicture($uid);
		$latest_lounge = $lounge_room->latestRoom($uid);
		if($latest_lounge){
			$lounge_url = $latest_lounge;
		}
		
		$points = $this->getPointsProfile($uid);
		$points_today = $this->getPointsProfile($uid, strtotime(date('Y-m-d 00:00:00')));
		$profile['UID'] = $uid;
		$profile['avatar'] = $this->profilePicture($uid);
		$profile['gigyaID'] = $this->getPreference($uid, 'gigyaID');
		$profile['activity'] = $this->getUserActions($uid, 25);
		$profile['points'] = $points->lifetimeBalance;
		$profile['lounge_room'] = $lounge_url;
		$profile['points_today'] = $points_today->points;
		$profile['points_total'] = $points->points;
		$profile['prizes_purchased'] = $this->prizesPurchased($profile['gigyaID']);
		#$profile['activity'] = $this->getUserActions($uid);

		# pull from Gigya
		$profile['gigya'] = $Gigya->getProfile($profile['gigyaID']);
		#$profile['comments'] = $Gigya->makeCall('comments.getUserComments', array('UID' => $profile['gigyaID']));
		$profile['comments'] = $Gigya->getComments($profile['gigyaID'], 5);
		#$profile['reactions'] = $Gigya->makeCall('socialize.getFeed', array('UID' => $profile['gigyaID']));
		
		return $profile;
	}
	
	function getSmallProfile($uid, $extra_small = false){
		$Gigya = new Gigya;
	
		if(empty($uid))
			return false;
		
		$uid = trim(strip_tags($uid));
		$points_today = $this->getPointsProfile($uid, strtotime(date('Y-m-d 00:00:00')));
		$points = $this->getPointsProfile($uid);
		if($extra_small){
			$profile['UID'] = $uid;
			$profile['avatar'] = $this->avatarLinkUser($uid, true);
			$profile['avatar_large'] = $this->profilePicture($uid);
			$profile['points'] = $points->points;
			$profile['points_today'] = $points_today->points;
		}else{
			$profile['UID'] = $uid;
			$profile['avatar'] = $this->avatarLinkUser($uid, true);
			$profile['avatar_large'] = $this->profilePicture($uid);
			$profile['gigyaID'] = $this->getPreference($uid, 'gigyaID');
			$profile['points'] = $points->points;
			$profile['points_today'] = $points_today->points;
			$profile['gigya'] = $Gigya->getProfile($profile['gigyaID'], true);	
		}

		return $profile;
	}
	
	function canvasPicture($uid){
		return $this->api_key.'/'.$uid.'.jpg?cat=MODERN_LOUNGE';
	}
	
	function setAvatar(){
		$Gigya = new Gigya;
		
		$bb_session = $_SESSION[$this->bb_session_key];
		
		if($bb_session['anonymous']){
			return false;
		}
		
		$request = $this->connection_url.$this->data_type.'/?'.
		            "method=user.setPreference".
					"&sessionKey=".$this->getSessionKey().
					"&name=userPhotoUrl".
					"&value=http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$this->getUID().'/thumb.png?cat=DEFAULT_AVATAR';
		$request = file_get_contents($request);
	    $data = json_decode($request);
		if($data->Nitro->res == 'err'){
			return false;
		}else{
			# all good, lets update Gigya as well
			$Gigya->updateAvatar("http://dynamic.bunchball.net/assets/avcat/".$this->api_key.'/'.$this->getUID()."/thumb.png?cat=DEFAULT_AVATAR");
			return true;
		
		}
	}
	
	/**
	 * getPoints
	 * this will pull the current number of points from Nitro for the given logged in user.
	 */
	function getPoints(){
		$uid = $this->getUID();
		$session_key = $this->getSessionKey();
		
		if( empty( $uid ) || empty( $session_key ) ){
			return false;			
		}
		
		$request = $this->connection_url.$this->data_type.'/?'.
	               "method=user.getPointsBalance".
	               "&sessionKey=".$session_key;
      	$request = file_get_contents($request);
	    $data = json_decode($request);
	    if( $data->Nitro->res == 'err' ){
	    	Log::debug('Error getting points for user. UID: '.$this->getUID());
	    	return false;
	    }
	    return $data->Nitro->Balance->points;
	}
	
	function getPointsProfile($uid, $today = null){
		if(empty($uid)) return false;
		if(!is_null($today)){
			$today = '&start='.$today;
		}else{
			$today = '';
		}
		$request = $this->connection_url.$this->data_type.'/?'.
	               "method=user.getPointsBalance".
	               "&sessionKey=".$this->getSessionKey().
				   "&userId=".$uid.$today;
      	$request = file_get_contents($request);
      	$data = json_decode($request);
		return $data->Nitro->Balance;
	}

	function debitPoints($points=NULL){
		
		$uid = $this->getUID();
		$session_key = $this->getSessionKey();
		
		if( empty( $uid ) || empty( $session_key ) || empty( $points ) ){
			return false;			
		}
		
		$request = $this->connection_url.$this->data_type.'/?'.
	               "method=user.debitPoints".
				   "&points=".$points.
	               "&sessionKey=".$session_key;
      	$request = file_get_contents($request);
	    $data = json_decode($request);
		return $data->Nitro->User->points;	
	}
	
	/**
	 * logAction
	 * this will log an action into the Nitro system
	 *
	 * @params   string  the action performed
	 * @params   string  meta / additional data that can be sent to nitro
	 * @params   int     custom points for actions like games
	 * @response boolean
	 */

	function logAction($action, $meta = null, $points = null){
		
		$uid = $this->getUID();
		$sessionKey = $this->getSessionKey();
		
		if( empty( $uid ) || empty( $sessionKey ) ){
			return false;			
		}
		
		if( is_array( $action ) ){
			$action = implode(',', $action);
		}
		
		if(!is_null($meta)){
			$meta = "&metadata=".$meta;
		} else {
			$meta = "";	
		}
		
		if(!is_null($points)){
			$points = "&value=".(int)$points;
		} else {
			$points = "";	
		}
		
		if($this->isAnonymous()){
			return false;
		}
		
		# is ION lounge on?
		if(!$this->loungeIsActive()){
			return false;
		}
		
		# generic user?
		if( $uid == md5($this->default_user_id) ){
			return false;
		}
		
		$request = $this->connection_url.$this->data_type.'/?'.
	            "method=user.logAction".
				"&userId=".$this->getUID().
				"&sessionKey=".$this->getSessionKey().
				$meta.
				$points.
				"&tags=".$action;
      	$request = file_get_contents($request);
	    $data = json_decode($request);

		if($data->Nitro->res == 'err'){
			Log::debug('Error logging action for user. UID: '.$this->getUID().' | ACTION: '.$action);
			return false;
		}
		return true;
	}
	
	function logActionUser($action, $user){
		
		if( is_array( $action ) ){
			$action = implode(',', $action);
		}
		
		# is ION lounge on?
		if(!$this->loungeIsActive()){
			return false;
		}
		
		$request = $this->connection_url.$this->data_type.'/?'.
	            "method=user.logAction".
				"&userId=".$user.
				"&sessionKey=".$this->getSessionKey().
				"&tags=".$action;
      	$request = file_get_contents($request);
	    $data = json_decode($request);
	    
		if($data->Nitro->res == 'err'){
			return false;
		}else{
			return true;
		}
	}
	
	function storePurchase($data){
		# a purchase callback has come in, lets store it and match it with a gigya user.
		$d['uid'] = $data['userId'];
		$d['items'] = $data['items'];
		$d['purchase_date'] = date("Y-m-d", time());
		
		$request = $this->connection_url.$this->data_type.'/?'.
	               "method=user.getPreference".
	               "&sessionKey=".$this->getSessionKey().
				   "&userId=".$data['userId'].
				   "&name=firstName";
      	$request = file_get_contents($request);
	    $data = json_decode($request);
	    if($data->Nitro->res == 'err'){
	    	# error with Nitro, so just store their Nitro id
	    	$d['gigya_uid'] = $d['uid'];
	    }else{
	    	$d['gigya_uid'] = $data->Nitro->userPreferences->UserPreference->value;	
	    }
	    
		# save this to a db
		$insert = $this->Core->insert('gaming_purchases', $d);
	
		# send an email.
		# not sure if we have a set place for this but it should notify someone!	
	}
	
	function loungeIsActive(){
		# check settings
		$setting = Setting::get('lounge-active');
        return $setting;
	}

	# TODO: CACHE!
	function getPointsLeaders(){
		if ( Cache::has('nitro_point_leaders') ){
			return Cache::get('nitro_point_leaders');
		}

      	$result = $this->makeCall( [
      		'method' 		=> 'site.getPointsLeaders',
      		'criteria' 		=> 'CREDITS',
      		'duration'		=> 'DAY',
      		'returnCount'	=> 9
      		] );

      	if( !$result ) {
      		# return cache version so there is no error
      	}

      	$data = [];
      	foreach( $result->Nitro->leaders->Leader as $leader ){
			if(!empty($leader)){
				$data[] = [
					'display_name' 	=> ( empty($leader->firstName) ) ? 'User' : $leader->firstName,
					'points'		=> (!isset($leader->points)) ? 0 : number_format($leader->points),
					'avatar'		=> (!isset($leader->userId)) ? "":$this->profilePicture($leader->userId)
				];				
			}

      	}

      	Cache::put('nitro_point_leaders', $data, Carbon::now()->addMinutes(15));

      	return $data;
	}

	/**
	 * Pulls the most recent actions from Nitro.
	 *
	 * @return array
	 */
	function getRecentActions(){
		$result = $this->makeCall( [
      		'method' => 'site.getRecentActions',
      		] );

      	if( !$result ) {
      		# return cache version so there is no error
      	}

      	dd($result);
      	return $result;
	}

	/**
	 * Returns challanges the user can perform on the website
	 */
	function getUserChallenges(){
		if ( Cache::has('nitro_get_challenges') ){
			#return Cache::get('nitro_get_challenges');
		}

      	$result = $this->makeCall( [
      		'method' 			=> 'user.getChallengeProgress',
      		'folder' 			=> 'Daily Challenges',
      		'showOnlyTrophies'	=> false,
      		] );

      	if( !$result ) {
      		# return cache version so there is no error
      	}


      	$data = [];
      	foreach( $result->Nitro->challenges->Challenge as $challenge ){
      		if( !isset($challenge->actionUrl) ){
      			$challenge->actionUrl = '';
      		}
      		$data[] = [
      			'name' 			=> $challenge->name,
      			'description'	=> $challenge->description,
      			'points'		=> $challenge->pointAward,
      			'url'			=> $challenge->actionUrl
      		];
      	}

      	Cache::put('nitro_get_challenges', $data, Carbon::now()->addMinutes(15));

      	return $data;
	}

	/**
	 * Returns the most recent challanges completed
	 * 
	 * @return array
	 */
	function getRecentChallenges(){
		if ( Cache::has('nitro_recent_challanges') ){
			return Cache::get('nitro_recent_challanges');
		}

		$request = $this->connection_url.$this->data_type.'/?method=site.getActionFeed&apiKey='.$this->api_key.'&returnCount=10&showActions=false';
		$result = json_decode(file_get_contents($request));
	    if($result->Nitro->res == 'err'){
	    	Log::debug('Error making call to Nitro getRecentChallanges: '.$request);
	    	return false;
	    }

      	$data = [];
      	foreach( $result->Nitro->items->item as $item ){
			$dt = Carbon::createFromTimestamp($item->ts);
      		$data[] = [
      			'display_name' 	=> ( empty($item->firstName) ) ? 'User' : $item->firstName,
      			'message'		=> $item->content,
      			'avatar'		=> $this->profilePicture($item->userId), #TODO: Make an override so we can pull from social as well
      			'when'			=> Carbon::now()->diffForHumans($dt, true).' ago'
      		];
      	}

      	Cache::put('nitro_recent_challanges', $data, Carbon::now()->addMinutes(2));

      	return $data;
	}

	function makeCall( $args ){
		$request = $this->connection_url.$this->data_type.'/?sessionKey='.$this->getSessionKey();
        foreach( $args as $name => $value ){
       		$request .= '&'.$name.'='.urlencode($value);
        }
        $request = file_get_contents($request);
	    $data = json_decode($request);
	    if($data->Nitro->res == 'err'){
	    	Log::debug('Error making call to Nitro: '.json_encode($args));
	    	return false;
	    }
	    return $data;
	}
	
}

?>