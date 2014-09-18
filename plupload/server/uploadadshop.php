<?php
$targetDir = '../uploads/';
require_once("server.php");
$result = array();
$width = 0;
$height = 0;
if(is_file($targetDir . DIRECTORY_SEPARATOR . $fileName)){
	$tmp_name = $targetDir . DIRECTORY_SEPARATOR . $fileName;
	$arr_ext = explode(".", $fileName ); 
	$ext = $arr_ext[count($arr_ext)-1];
	$img = 0;
	$check_img = false;
	if(strcasecmp($ext,"jpg") == 0){
		$img = imagecreatefromjpeg($tmp_name);
		$check_img = true;
	}elseif (strcasecmp($ext, "gif") == 0){
		$img = imagecreatefromgif($tmp_name);
		$check_img = true;
	}elseif (strcasecmp($ext,"png") == 0){
		$img = imagecreatefrompng($tmp_name);
		$check_img = true;
	}
	if($check_img == true){
		$width = imageSX($img);
		$height = imageSY($img);
	}
	$result = array('success'=>true, 'width'=>$width, 'height'=>$height, 'error'=>'', 'fileName'=>$fileName);
}else{
	$result = array('error' => 'Could not save uploaded file.');	
}
echo json_encode($result);
?>