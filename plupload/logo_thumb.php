<?php
	// This script accepts an ID and looks in the user's session for stored thumbnail data.
	// It then streams the data to the browser as an image
	
	// Work around the Flash Player Cookie Bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	
	session_start();
	
	$image_id = isset($_GET["id"]) ? $_GET["id"] : false;
	$rotate = $_GET['rotate'];
	

	if ($image_id === false) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "No ID";
		exit(0);
	}

	if (!is_array($_SESSION["file_info"]) || !isset($_SESSION["file_info"][$image_id])) {
		header("HTTP/1.1 404 Not found");
		exit(0);
	}
	$ext = $_SESSION['ext'];
	
	$img = imagecreatefromstring($_SESSION["file_info"][$image_id]);
	
	if(isset($_GET['logo']) && $_GET['logo'] == 'yes'){
		$new_width = $width = imageSX($img);
		$new_height = $height = imageSY($img);	
		$NewWidth = 200;
		$Newheight = 200;
		$check = false;
		if($new_width > $new_height){
			if($new_width > $NewWidth){
				$new_width = $NewWidth;
				$new_height = round(($NewWidth*$height)/$width);
				$check = true;
			}
			if($new_height > $Newheight){
				$height_ = $new_height;
				$new_height = $Newheight;
				$new_width = round($Newheight*$new_width/$height_);
				$check = true;
			}
		}else {
			if($new_height > $Newheight){
				$new_height = $Newheight;
				$new_width = round($Newheight*$new_width/$height);
				$check = true;
			}
			if($new_width > $NewWidth){
				$width_ = $new_width;
				$new_width = $NewWidth;
				$new_height = round($NewWidth*$new_height/$width_);
				$check = true;
			}
		}
		if($check){
			$new_img = ImageCreateTrueColor($new_width, $new_height);
		
			@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			$img = $new_img;
		}
	}
	$temp = imagecolorallocatealpha($img, 255,255, 255, 127);
	if($rotate != 0){
		$temp_tang = imagecolorallocatealpha($img, 0,0, 0, 0);
		$img = imagerotate($img, $rotate, $temp);
//		@imagefilledrectangle($img, 0, 0, imageSX($img)-1, imageSY($img)-1, $temp_tang);
	}

	imagecolortransparent($img, $temp); 
	imagefill($img, 0, 0, $temp);
	imagealphablending($img, false);
	imagesavealpha($img, true);
	
	header("Content-type: image/png") ;
	imagepng($img);
	exit(0);