<?php

/**
 * Define helper functions
 */

/**
 * Will pull rackspace CDN url from config and apply it to an image
 * Set image second param to true if you want to use the non SSL version of the image
 */
function image( $image , $no_ssl = false){

    if ($_SERVER['SERVER_PORT'] == 443 && $no_ssl == false) {
        $rackspace_url = Config::get('filesystems.disks.rackspace.public_url_ssl');
    }
    else{
        $rackspace_url = Config::get('filesystems.disks.rackspace.public_url');
    }
    //else
    return (filter_var($image, FILTER_VALIDATE_URL)) ? $image : $rackspace_url.'/'.$image;
}

function corp_image( $image ){
    $rackspace_url = config('filesystems.disks.corporate.public_url_ssl');
    return (filter_var($image, FILTER_VALIDATE_URL)) ? $image : $rackspace_url.'/'.$image;
}

function image_size($image){
    list($width, $height) = (@exif_imagetype(image($image)))? getimagesize(image($image)): ['null','null'];
    return ["width" => $width, "height" => $height];
}

function live_site_url($path = ""){
    return site_url($path);
}

function staging_site_url($path = "",$auth = false){
    return site_url($path,"staging",$auth);
}

function dev_site_url($path = "",$auth = false, $ssl = false){
    return site_url($path,"dev",$auth, $ssl);
}

function site_url($path = "", $subdomain = "www", $auth = false, $ssl = false){
    $login = config("windows-authentication");
    $credentials = ($auth) ? "{$login['username']}:{$login['password']}@" : "";
    return (($ssl)?"https":"http")."://{$credentials}{$subdomain}.qubo.com/".$path;
}

function curl_get( $url, $username = false , $password = false ) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    if( $username && $password ) {
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    }

    $buffer = curl_exec($ch);
    curl_close($ch);

    return $buffer;
}

function is_email($string){
    return filter_var($string, FILTER_VALIDATE_EMAIL);
}

/**
 * Generates a placeholder image on the fly
 */
function placeholder(){

    $args = func_get_args();

    $placehold_params = "";
    if (count($args) == 1) {
        if(is_string( $args[0])){
            $placehold_params = $args[0];
        }else if(is_array($args[0])){
            $placehold_params = implode("x",$args[0]);
        }
    }else if(count($args) == 2){
        $placehold_params = implode("x",$args);
    }

    return "https://placehold.it/{$placehold_params}";
}


/**
 * Provides a quick way to access the gigya session within the templates
 */

function user_detail( $field ){

    $approved_fields = ['uid', 'provider', 'firstname', 'email', 'name', 'nickname', 'avatar', 'points', 'thumbnailurl', 'photourl'];

    if( empty($field) ) return '';

    if( !in_array($field, $approved_fields) ){
        return '';
    }

    $detail = Session::get('gigya');
    if( !isset($detail[$field]) ){
        return '';
    }

    return $detail[$field];
}

function sanitize($input){
    return trim(filter_var( strip_tags($input), FILTER_SANITIZE_STRING));
}

function clean_chars($string){

    //    $string =  htmlentities($string, ENT_QUOTES, "UTF-8");

    $find[] = 'â€œ'; // left side double smart quote
    $find[] = 'â€'; // right side double smart quote
    $find[] = 'â€˜'; // left side single smart quote
    $find[] = 'â€™'; // right side single smart quote
    $find[] = 'â€¦'; // elipsis
    $find[] = 'â€”'; // em dash
    $find[] = 'â€“'; // en dash
    $find[] = '™';
    $find[] = 'Ã¢';
    $find[] = 'Â¬';
    $find[] = 'Â¢';
    $find[] = 'ã¢â¬â¦';
    $find[] = 'ã¢â¬â¢';
    $find[] = 'â¬ë';


    $replace[] = '"';
    $replace[] = '';
    $replace[] = "";
    $replace[] = "";
    $replace[] = "...";
    $replace[] = "-";
    $replace[] = "-";
    $replace[] = "'";
    $replace[] = "";
    $replace[] = "'";
    $replace[] = "";
    $replace[] = "...";
    $replace[] = "'";
    $replace[] = "";

    return strip_tags(str_replace($find, $replace, $string));
}


/**
 * Set body class
 * http://laravelsnippets.com/snippets/add-body-classes-helper
 */
function bodyClass(){
    $body_classes = [];
    $class = "";

    foreach ( Request::segments() as $segment ){
        if ( is_numeric( $segment ) || empty( $segment ) ) {
            continue;
        }

        $class .= ! empty( $class ) ? "-" . $segment : $segment;

        array_push( $body_classes, $class );
    }

    return ! empty( $body_classes ) ? implode( ' ', $body_classes ) : NULL;
}

function ordinal( $number ) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. '<sup>th</sup>';
    else
        return $number. '<sup>'.$ends[$number % 10].'</sup>';
}

/**
 * pod_title
 * This will cut the pod title to the right size encase a title is to long.
 */
function pod_title( $title ){
    if( strlen($title) > 18 ){
        return substr($title, 0, 18).'...';
    }
    return $title;
}

/**
 * clean_for_js
 * clean out quotes and other issues that might effect share and comment elements
 */
function clean_for_js($title){
    $title = str_replace('"', '', $title);
    $title = str_replace("'", "\'", $title);
    return $title;
}