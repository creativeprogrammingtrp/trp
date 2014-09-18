<?php
$targetDir = '../../data/ads/';
require_once("server.php");

if(is_file($targetDir . $fileName)){
	$result = array('success'=>true, 'fileName'=>$fileName, 'width'=>$width, 'height'=>$height, 'error'=>'');
}else{
	$result = array('error' => 'Could not save uploaded file.');	
}
echo json_encode($result);
?>