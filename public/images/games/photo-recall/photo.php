<?php
error_reporting(E_ERROR);
session_start();

header('Cache-Control: max-age=604800');
header('Content-type: image/jpeg');

if(isset($_GET["show"]) && isset($_GET["image"])){

$show = $_GET["show"];
$image = $_GET["image"];

$filepath = "c:/inetpub/wwwroot/api.iontelevision.com/assets/games/photo-recall/photos/{$show}/{$image}";

$max_width = 600;
$max_height = 400;

// Get new dimensions
list($width, $height) = getimagesize($filepath);
$new_height = $max_height;
$new_width =  ceil($new_height*$width / $height); //Maintain aspect ratio

// Resample
$image_p = imagecreatetruecolor($max_width , $max_height);
$image = imagecreatefromjpeg($filepath);
imagecopyresampled($image_p, $image, ceil(($max_width /2) - ($new_width/2)) , 0, 0 ,0, $new_width, $new_height, $width, $height);

// Output
imagejpeg($image_p, null, 100);

}

?>