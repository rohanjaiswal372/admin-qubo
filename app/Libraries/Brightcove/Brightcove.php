<?php namespace App\Libraries\Brightcove;

use \Config;
use  App\Libraries\Brightcove\BCMAPICache;
use  App\Libraries\Brightcove\BCMAPI;

class Brightcove
{

	public static function video($video_id)
	{
		if (empty($video_id))
			return;
		$Brightcove = new BrightcoveAPI();
		return $Brightcove->find("video_by_id", ["video_id" => $video_id, "media_delivery" => "http"]);
	}


	public static function videoURL($video_id)
	{
		if (empty($video_id))
			return;
		return self::video($video_id)->FLVURL;
	}

	public static function videoStillURL($video_id)
	{
		if (empty($video_id))
			return;
		if (isset(self::video($video_id)->videoStillURL))
			return self::video($video_id)->videoStillURL;
		return NULL;
	}

	public static function videoThumbnailURL($video_id)
	{
		if (empty($video_id))
			return;
		if (isset(self::video($video_id)->thumbnailURL))
			return self::video($video_id)->thumbnailURL;
		return NULL;
	}

	public static function upload($params)
	{
		$Brightcove = new BrightcoveAPI();
		return $Brightcove->createVideo($params["file"], $params["meta"]);
	}

	public static function delete($brightcove_id)
	{
		/// delete from brightcove
		$Brightcove = new BrightcoveAPI();
		return $Brightcove->deleteVideo($brightcove_id);
	}

	public static function videoCustomFields($video_id)
	{
		if (empty($video_id))
			return;
		$Brightcove = new BrightcoveAPI();
		return $Brightcove->find("video_by_id", ["video_id" => $video_id, "media_delivery" => "http", "video_fields" => 'customFields']);
	}

	public static function updateCustomFields($fields, $brightcove_id)
	{
		$params = ['id' => $brightcove_id, 'customFields' => $fields];

		$Brightcove = new BrightcoveAPI();
		return $Brightcove->updateVideo($params, 'update_video');

	}

	public function createThumbnail($brightcove_id)
	{
		if (empty($brightcove_id))
			return;
		$Brightcove = new BrightcoveAPI();
		return $Brightcove->createImage($brightcove_id);
	}

	public static function update($params, $method)
	{
		if (empty($params))
			return;
		$Brightcove = new BrightcoveAPI();
		return $Brightcove->updateVideo($params, $method);

	}
}

class BrightcoveCache extends BCMAPICache
{

	static $location;
	static $extension;
	static $port;
	static $type;
	static $time;

	public function __construct()
	{

		self::$location = storage_path("brightcove/");
		self::$extension = ".c";
		self::$port = 11211;
		self::$type = "file";
		self::$time = 10800; //seconds
		parent::__construct(self::$type, self::$time, self::$location, self::$extension, self::$port);
	}

	public static function set($key, $data)
	{

		parent::set($key, $data);
		$cached_filename = parent::$location . md5($key) . parent::$extension;
		//May need to upload to primary server if this is the secondary
		return $cached_filename;
	}

	public static function get($key)
	{
		$jsonString = parent::get($key);
		return (!empty($jsonString)) ? json_decode($jsonString) : "";
	}

}

class BrightcoveAPI extends BCMAPI
{

	public $cache;

	public function __construct()
	{
		$this->cache = new BrightcoveCache();
		$this->token_read = Config::get("brightcove.api_token_read");
		$this->token_write = Config::get("brightcove.api_token_write");
		parent::__construct($this->token_read, $this->token_write);
	}

	public function makeCurlRequest($request)
	{
		$url = (($this->secure) ? 'https://' : 'http://') . $this->url_write;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, function () {
			usleep(100000);
			return 0;
		});
		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);

	}

	public function deleteVideo($brightcove_id)
	{
		$request = [];
		$post = [];
		$params = [];

		$params['token'] = $this->token_write;
		$params['video_id'] = $brightcove_id;
		$post['method'] = 'delete_video';
		$post['params'] = $params;

		$request['json'] = json_encode($post);

		$json = $this->makeCurlRequest($request);
		if (isset($json->error)) {
			return $json->error;
		} else {
			return $json;
		}
	}


	public function updateVideo($meta = NULL, $method)
	{
		$request = [];
		$post = [];
		$params = [];
		$video = [];

		foreach ($meta as $key => $value) {
			$video[$key] = $value;
		}
		$params['token'] = $this->token_write;
		$params['video'] = $video;

		$post['method'] = $method;
		$post['params'] = $params;

		$request['json'] = json_encode($post);

		//		dd($request['json']);

		$json = $this->makeCurlRequest($request);
		if (isset($json->error)) {
			return $json->error;
		} else {
			return $json;
		}
	}

	public function createImage($type = 'video', $file = NULL, $meta, $id = NULL, $ref_id = NULL, $resize = TRUE)
	{

		//createImage($type = 'video', $file = NULL, $meta, $id = NULL, $ref_id = NULL, $resize = TRUE)
	}

	public function createVideo($file = NULL, $meta, $options = NULL)
	{
		//See documentation at http://support.brightcove.com/en/video-cloud/docs/media-write-api-php-example-upload-video

		$request = [];
		$post = [];
		$params = [];
		$video = [];

		if (is_null($options)) {
			$params["encode_to"] = "MP4";
			$params["create_multiple_renditions"] = "True";
		}

		foreach ($meta as $key => $value) {
			$video[$key] = $value;
		}
		$params['token'] = $this->token_write;
		$params['video'] = $video;

		$post['method'] = 'create_video';
		$post['params'] = $params;

		$request['json'] = json_encode($post);

		if ($file) {
			$request['file'] = new \CURLFile($file, 'video/mp4', basename($file));
		}

		// Responses are transfered in JSON, decode into PHP object
		$json = $this->makeCurlRequest($request);

		// Check request error code and re-call createVideo if request
		// returned a 213 error. A 213 error occurs when you have
		// exceeded your allowed number of concurrent write requests
		if (isset($json->error)) {
			if ($json->error->code == 213) {
				return $this->createVideo($file, $meta);
			} else {
				return $json->error;
			}
		} else {
			return $json;
		}
	}


}

