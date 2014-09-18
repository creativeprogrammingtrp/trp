<?php
// This script accepts an ID and looks in the user's session for stored thumbnail data.
// It then streams the data to the browser as an image

// Work around the Flash Player Cookie Bug

@session_start();

$image_id = isset($_GET["id"]) ? $_GET["id"] : false;
if ($image_id === false) {
	exit(0);
}
if(!isset($_SESSION["file_upload"][$image_id]) || !is_array($_SESSION["file_upload"][$image_id])){
	exit(0);
}
$ext = $_SESSION["file_upload"][$image_id]['ext'];

header("Content-type: image/$ext") ;
header("Content-Length: ".strlen($_SESSION["file_upload"][$image_id]['content']));
echo $_SESSION["file_upload"][$image_id]['content'];
exit(0);