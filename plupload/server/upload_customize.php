<?php
$targetDir = '../uploads/';
require_once("server.php");
$result = array();
$width = 0;
$height = 0;
$file_id = '';
$error = '';
if(is_file($targetDir . $fileName)){
	$tmp_name = $targetDir . $fileName;
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
	if($check_img == true && $img != false){
		$width = imageSX($img);
		$height = imageSY($img);
		$_SESSION['ext'] = $ext;
		if (!isset($_SESSION["file_info"])) {
			$_SESSION["file_info"] = array();
		}
		$farbe_body_src=imagecolorallocatealpha($img,255,255,255,127); 
		imagecolortransparent($img, $farbe_body_src); 
		imagefill($img, 0, 0, $farbe_body_src);
//		imagealphablending($img, true);
		imagesavealpha($img, true);
		
		ob_start();	
		if(strcasecmp($ext,"jpg") == 0)
			imagejpeg($img, null, 100);
		elseif (strcasecmp($ext, "gif") == 0)
			imagegif($img);
		elseif (strcasecmp($ext,"png") == 0)
			imagepng($img);	
		$imagevariable = ob_get_contents();
		ob_end_clean();
		
		$file_id = md5($fileName + rand()*100000);
		$_SESSION["file_info"][$file_id] = $imagevariable;
	//	$_SESSION["file_upload"][$file_id] = array('content' => $imagevariable, 'ext' => $ext);		
	}else{
		$error = 'Can not get resource image.';	
	}
	unlink($tmp_name);
	$result = array('success'=>true, 'width'=>$width, 'height'=>$height, 'error'=>$error, 'file_id'=>$file_id, 'fileName'=>$tmp_name);
}else{
	$result = array('error' => 'Could not save uploaded file.');	
}
echo json_encode($result);
?>