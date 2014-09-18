<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Files{
	var $CI;
	var $filesArr = array();
	var $arr_ext = array();
	
	function __construct($arr_ext=NULL){
		$this->CI =& get_instance();
		if($arr_ext !== NULL){
			$this->arr_ext = $arr_ext;	 
		}
	}
	// Set Ext
	function setArrExt($arr_ext=NULL){
		if($arr_ext !== NULL){
			$this->arr_ext = $arr_ext;	 
		}	
	}
	// Get all file from folder
	function getFiles($dirPath, $arr_ext=NULL){
		$this->filesArr = array();
		if(!is_dir($dirPath)) return $this->filesArr;	
		if($arr_ext !== NULL){
			$this->arr_ext = $arr_ext;	 
		}
		$this->getDirFiles($dirPath);
		return $this->filesArr;	
	}
	function getDirFiles($dirPath){
		if ($handle = opendir($dirPath)){
			while (false !== ($file = readdir($handle))) {
				if($file != '.' && $file != '..'){
					$fullpath = $dirPath . '/' . $file;
					if(is_file($fullpath)){
						$arr_ = explode(".", $file);
						if(is_array($arr_) && count($arr_) > 0){
							$ext = $arr_[count($arr_)-1];
							if(in_array($ext, $this->arr_ext)) $this->filesArr[] = trim(str_replace('../', '', $fullpath));    		
						}              
					}elseif (is_dir($fullpath)){
						$this->getDirFiles($fullpath);
					}
				}
			}
			closedir($handle);
		}
	}
}
?>