<?php namespace App\Libraries\Brightcove;

use \Config;

/*
 * sources, renditions, fields
 */

class BrightcoveCMS
{
    public static function video($video_id)
    {
        if (empty($video_id)) return;
        $Brightcove = new BrightcoveCMSAPI();
        return $Brightcove->findVideo($video_id, 'videos');  // id, method, option
    }
    public static function videoSources($video_id)
    {
        if (empty($video_id)) return;
        $Brightcove = new BrightcoveCMSAPI();
        return $Brightcove->findVideo($video_id, 'videos','sources');  // id, method, option
    }

    public static function videoCustomFields($video_id){

        if (empty($video_id)) return;
        $Brightcove = new BrightcoveCMSAPI();
        return $Brightcove->findVideo($video_id,'videos')->custom_fields;

    }

    public static function videoAnalytics($video_id, $pre, $range = null){

        if (empty($video_id)) return;
        $Brightcove = new BrightcoveCMSAPI();
        return $Brightcove->getVideoAnalytics($pre,$video_id,'videos', $range);

    }
    public static function videoStillURL($video_id)
    {
        if (empty($video_id))
            return;
        if (isset(self::video($video_id)->images->poster->sources[1]->src))
            return self::video($video_id)->images->poster->sources[1]->src;
        return NULL;
    }

    public static function videoThumbnailURL($video_id)
    {
        if (empty($video_id))
            return;
        if (isset(self::video($video_id)->images->thumbnail->sources[1]->src))
            return self::video($video_id)->images->thumbnail->sources[1]->src;
        return NULL;
    }
}


class BrightcoveCMSAPI
{

    protected $account_id;

    public function get_account_id()
    {
        return Config::get("brightcove.account_id");
    }

    public function makeCurlRequest($url){

        $access_token = $this->getToken();
        if (is_null($access_token))
            return "No Access Token Granted";

        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_POST => FALSE, CURLOPT_RETURNTRANSFER => TRUE, CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_HTTPHEADER => ['Content-type: application/json', "Authorization: Bearer {$access_token}"],//
        ]);
        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }


    public function getVideoAnalytics($pre = 'engagement', $video_id, $method = 'videos', $range = null){

        $api_url = 'https://analytics.api.brightcove.com/v1';

        $url = $api_url . '/'.$pre.'/accounts/' . $this->get_account_id() . '/'.$method.'/' . $video_id;
      //  if(!is_null($option)) $url.= '/'.$option;

        return $this->makeCurlRequest($url);

    }

    public function findVideo($video_id, $method, $option = null)
    {
        //http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/
        //http://docs.brightcove.com/en/video-cloud/cms-api/getting-started/quick-start-cms.html

        $api_url = 'https://cms.api.brightcove.com/v1';

        $url = $api_url . '/accounts/' . $this->get_account_id() . '/'.$method.'/' . $video_id;
        if(!is_null($option)) $url.= '/'.$option;

        return $this->makeCurlRequest($url);

    }

    private function getToken()
    {
        /**
         * proxy for Brightcove RESTful APIs
         * gets an access token, makes the request, and returns the response
         *
         * Method: POST
         * include header: "Content-Type", "application/x-www-form-urlencoded"
         *
         * @post {string} url - the URL for the API request
         * @post {string} [requestType=GET] - HTTP method for the request
         * @post {string} [requestBody=null] - JSON data to be sent with write requests
         *
         * @returns {string} $response - JSON response received from the API
         */
        // CORS enablement
        header("Access-Control-Allow-Origin: *");
        // set up request for access token
        $data = [];
        $client_id = Config::get("brightcove.CLIENT_ID");  // CLIENT_ID
        $client_secret = Config::get("brightcove.CLIENT_SECRET");  // CLIENT_SECRET
        $auth_string = "{$client_id}:{$client_secret}";
        $request = "https://oauth.brightcove.com/v3/access_token?grant_type=client_credentials";
        $ch = curl_init($request);
        curl_setopt_array($ch, [CURLOPT_POST => TRUE, CURLOPT_RETURNTRANSFER => TRUE, CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_USERPWD => $auth_string, CURLOPT_HTTPHEADER => ['Content-type: application/x-www-form-urlencoded',], CURLOPT_POSTFIELDS => $data]);
        $response = curl_exec($ch);
        curl_close($ch);
        // Check for errors
        if ($response === FALSE) {
            return (curl_error($ch));
        }
        // Decode the response
        $responseData = json_decode($response, TRUE);
        $access_token = $responseData["access_token"];
        return $access_token;

    }
}