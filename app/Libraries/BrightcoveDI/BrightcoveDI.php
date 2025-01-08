<?php namespace App\Libraries\BrightcoveDI;

use Config;
use \stdClass;

class BrightcoveDI
{
    public static function upload($video_id, $params)
    {
        $brightcove = new BrightcoveDIAPI();
        return $brightcove->createVideo($video_id,
            isset($params["file"])? $params["file"]  : null,
            isset($params["meta"])? $params["meta"]  : null,
            isset($params['options'])? $params['options'] : null);
    }
}

class BrightcoveDIAPI extends \App\Libraries\BrightcoveDI\BCDIAPI {

    protected $account_info;


    public function createVideo($video_id, $file = NULL, $meta, $options = NULL)
    {
        $request_type = $options['ingest_type'];
        /*
        Request types:
             pull_options : Add video (pull-based)
             push_options : Add video (upload source files)
             pull_replace_options : Replace video (pull-based)
             push_replace_options : Replace video (upload source files)
             retranscode_options : Retranscode video
        */
        $this->account_info                 = new stdClass();
        $this->account_info->account_id     = Config::get("brightcove.account_id");
        $this->account_info->client_id      = Config::get("brightcove.CLIENT_ID");  // CLIENT_ID
        $this->account_info->client_secret  = Config::get("brightcove.CLIENT_SECRET");  // CLIENT_SECRET
        $account_data                       = json_encode($this->account_info);

    $callbacks = site_url("/upload/complete/");

        $video_metadata = '{"name":"'.$meta['name'].'","description": "'.$meta['shortDescription'].'","tags": ["'.$meta['customFields']['videoType'].'"]}';

        $params = ['profile' => "videocloud-default-v1", "callbacks" => [$callbacks]];

        if($request_type == 'pull_options' || $request_type == 'pull_replace_options' )
            $params += ["master" => ["url"=> video($file)]];

        if(isset($options['image'])){
            $params +=  ["capture-images" => false];
            $params += ['poster' => ["url" => $options['image'], "width" => 640, "height" => 360], 'thumbnail' => ["url" => $options['image'], "width" => 640, "height" => 360]];
        }
        else $params +=  ["capture-images" => true];

//        $push_ingest_data = '{"profile": "videocloud-default-v1","capture-images": true,"callbacks": ["'.$callbacks.'"]}';
//        $pull_ingest_data = '{"profile": "videocloud-default-v1","capture-images": true,"master": {"url": "'.$file.'"},"callbacks": ["'.$callbacks.'"]}';
        $push_ingest_data = json_encode($params);
        $pull_ingest_data = json_encode($params);

        $retranscode_data = '{"profile": "videocloud-default-v1","capture-images": false,"master": { "use_archived_master": true },"callbacks": ["'.$callbacks.'"]}';

        // for push-based ingest
        $file_paths = '{"video": "'.$file.'"}';
       // if(!is_null($options['image']))
       // $file_paths = '{"video": "'.$file.'","poster": "'.$options['image'].'","thumbnail": "'.$options['image'].'"}';  // send through images (Stills) if the object has them prior

        // data sets
        $data_sets = new stdClass();
        // pull request options
        $data_sets->pull_options = new stdClass();
        $data_sets->pull_options->video_options = $video_metadata;
        $data_sets->pull_options->ingest_options = $pull_ingest_data;

        // push request options
        $data_sets->push_options = new stdClass();
        $data_sets->push_options->file_paths = $file_paths;
        $data_sets->push_options->video_options = $video_metadata;
        $data_sets->push_options->ingest_options = $push_ingest_data;
//        $data_sets->push_options->text_tracks = $text_tracks;

        // pull replace request options
        $data_sets->pull_replace_options = new stdClass();
        $data_sets->pull_replace_options->video_id = $video_id;
        $data_sets->pull_replace_options->video_options = $video_metadata;
        $data_sets->pull_replace_options->ingest_options = $pull_ingest_data;

        // push replace request options
        $data_sets->push_replace_options = new stdClass();
        $data_sets->push_replace_options->video_id = $video_id;
        $data_sets->push_replace_options->ingest_options = $pull_ingest_data;
        $data_sets->push_replace_options->file_paths = $file_paths;

        // retranscode request options
        $data_sets->retranscode_options = new stdClass();
        $data_sets->retranscode_options->video_id = $video_id;
        $data_sets->retranscode_options->ingest_options = $retranscode_data;

        $bcdi = new BCDIAPI($account_data);

        // make a request - change data param to test other operations
        $request_data = $data_sets->$request_type;

        // Create a try/catch
        try {
            // make request
            $responses = $bcdi->ingest_request($request_data);
        } catch(Exception $error) {
            // Handle our error
            echo $error;
            die();
        }
//        echo '<p>Processing complete</p>';
//        echo '<h3 style="font-family:sans-serif;">CMS Response (will be NULL for replace/retranscode requests)</h3>';
//        echo '<pre>'.json_encode($responses->cms, JSON_PRETTY_PRINT).'</pre>';
//        echo '<h3 style="font-family:sans-serif;">DI Response</h3>';
//        echo '<pre>'.json_encode($responses->di, JSON_PRETTY_PRINT).'</pre>';

        return $responses;

    }

}