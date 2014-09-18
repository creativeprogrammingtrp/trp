<?php
$result = array();
/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Settings
$targetDir = '../../resource/business';

session_start();
ini_set("memory_limit", (2*1024)."M");
set_time_limit(3600000);
if(isset($_SESSION['file_upload'])) unset($_SESSION['file_upload']);
// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
$fileName = $file_title = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
$original_filename = $fileName;

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\._]+/', '', $fileName);

$file_id = md5($fileName + rand()*100000);
$width = 0;
$height = 0;

$arr_ext = explode('.', $fileName);
$ext = $arr_ext[count($arr_ext)-1];
       
while (is_file($targetDir.$file_id.'.'.$ext) || is_file($targetDir.$file_id.'.jpg') || isset($_SESSION["file_upload"][$file_id])) {
	$file_id = md5($fileName + rand()*100000);
}
$fileName = $original_filename;//$file_id.'.'.$ext;

// Create target dir
if (!file_exists($targetDir)){
	$oldumask = umask(0) ;
	mkdir( $targetDir, 0777) ;
	umask( $oldumask ) ;
}
$width = 0;
$height = 0;
// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 10000))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
} else {
	// Open temp file
	$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 10000))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

/****************************  My Code  ********************************/

if(is_file($targetDir . DIRECTORY_SEPARATOR . $fileName)){
	$result = array('success'=>true, 'file_id'=>$file_id, 'width'=>$width, 'height'=>$height, 'ext'=>$ext, 'error'=>'', 'original'=>$original_filename);
}else{
	$result = array('error' => 'Could not save uploaded file.');	
}
//die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
echo json_encode($result);
?>