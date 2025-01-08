<?php
// error reporting
error_reporting(E_ERROR);
session_start();
set_time_limit(0);
ignore_user_abort(1);
ini_set("memory_limit","1G");

/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = 'uploads'; // Relative to the root
$root = "C:/inetpub/wwwroot/PromoApprovalNew/";
$json = array();
$json["success"] = false;


//var_dump($_FILES);

//echo($root . $targetFolder);
if (!empty($_FILES["qqfile"])) {

	$file = $_FILES["qqfile"];
	$filename =  preg_replace('/[^(\x20-\x7F)]*/','', $file["name"]);
	$tempFile = $file['tmp_name'];
	$targetPath = $root.$targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' .$filename;
	
	// Validate the file type
	$fileTypes = array('mp4'); // File extensions allowed
	$extension = end(explode('.', $filename));
	
	
	if (in_array($extension , $fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		$json["success"] = true;
		$json["filename"] = $filename;
		$json["message"] = "Upload successful";
		$json["target"] = $targetFile;
		
	} else {
		$json["success"] =false;	
		$json["error"] = "Invalid File Type";
		//$json["preventRetry"] = false;  //To prevent retry
	}
	
}



//header('Content-type: application/json');
echo(json_encode($json));
die();

?>