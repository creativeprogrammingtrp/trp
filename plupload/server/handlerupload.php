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

global $catename, $orgfile;
$catename = $_GET["catelogries"];

// Settings
//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
if(!isset($targetDir)) $targetDir = '../uploads/';
session_start();
ini_set("memory_limit", (2*1024)."M");
set_time_limit(3600000);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
$orgfile = $fileName;

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\._]+/', '', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

$width = 0;
$height = 0;

$arr_ext = explode('.', $fileName);
$ext = $arr_ext[count($arr_ext)-1];
$ext = strtolower($ext);

$arr = explode("/", $catename);
$url_dataImg = "../../data";
$i = count($arr) - 1;
$catewrite = $arr[$i];
$targetDir = '../../data/'.$catewrite.'/';
			
// Create target dir
if (!file_exists($targetDir)){
	$oldumask = umask(0) ;
	mkdir( $targetDir, 0777) ;
	umask( $oldumask ) ;
}

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
				while ($buff = fread($in, 100000))
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
			while ($buff = fread($in, 100000))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

/****************************  My Code  ********************************/

if(is_file($targetDir  . $fileName)){
	$tmp_name = $targetDir . $fileName;
	
	$width = 0;
	$height = 0;
	if(in_array($ext, array('jpg','JPG','gif','GIF','png','PNG'))){
		$img = 0;
		if($ext == "jpg")
			$img = imagecreatefromjpeg($tmp_name);
		elseif ($ext == "gif")
			$img = imagecreatefromgif($tmp_name);
		elseif ($ext == "png")
			$img = imagecreatefrompng($tmp_name);
		if(!$img) {
			die( json_encode( array('error' => 'ERROR:could not create image handle') ));
		}
		$width = imageSX($img);
		$height = imageSY($img);
		$arrimgsize = FindDimension($width, $height, 480, 320);

		$width = $arrimgsize[0];
		$height = $arrimgsize[1];
	}
	$result = array('error'=>'', 'filepath' => 'data/'.$catewrite.'/'.$fileName, 'filename' => $fileName, 'ext' => $ext, 'width'=> $width, 'height'=> $height, "cateid" => $catename, 'orgname' => $orgfile);
	if(in_array($ext, array('mp3', 'wav', 'mdi', 'wma'))){
		$result["att"] = $fileName;
		$result["attex"]    = $ext;
		$result["attpath"]  = '';
	}
	else{
		$result["att"] = '';
		$result["attex"]    = '';
		$result["attpath"]  = '';
	}
}else{
	$result = array('message'=> "Could not upload", 'filepath' => '', 'filename' => '', 'ext' => '');
}	
//die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
echo json_encode($result);
function FindDimension($ImageWidth, $ImageHeight, $ContainerWidth, $ContainerHeight)
{
    $result = array($ContainerWidth, floor($ImageHeight * $ContainerWidth / $ImageWidth));
    if ($result[1] > $ContainerHeight)
    {
       $result[1] = $ContainerHeight;
       $result[0] = floor($ImageWidth * $ContainerHeight / $ImageHeight);
    }
    return $result;
}
?>