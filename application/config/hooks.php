<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
if(!function_exists('call_hook')){
    function call_hook($hook_name, &$params = null){
        $args = array();
        if(isset($params)) {
            $args[] =& $params; // pass by reference to modify in hook funtion
        }
        for($a = 2; $a < func_num_args(); $a++) {
            $args[] = func_get_arg($a); 
        }
        $EXT =& load_class('Hooks');
        return $EXT->_call_hook($hook_name, $args);
    }
}

/*
function getDirFiles($dirPath, &$filesArr, $arr_ext){
	if(!is_dir($dirPath)) return false;
    if($handle = opendir($dirPath)){
        while (false !== ($file = readdir($handle))) {
            if($file != '.' && $file != '..'){
                $fullpath = $dirPath . '/' . $file;
                if(is_file($fullpath)){
					$arr_ = explode(".", $file);
					if(is_array($arr_) && count($arr_) > 0){
						$ext = $arr_[count($arr_)-1];
						if(in_array($ext, $arr_ext)) $filesArr[] = trim(str_replace('../', '', $fullpath));    		
					}              
                }elseif (is_dir($fullpath)){
                    getDirFiles($fullpath, $filesArr, $arr_ext);
                }
            }
        }
        closedir($handle);
    }
}
$arr_class = array();
getDirFiles('application/controllers', $arr_class, array('php','PHP'));

if(count($arr_class) > 0){
	foreach($arr_class as $class){
		$class = str_replace("application/", "", $class);
		$arr__ = explode("/", $class);
		$filename = $arr__[count($arr__)-1];
		$filepath = '';
		for($i = 0; $i < count($arr__)-1; $i++){
			$filepath .= $arr__[$i].'/';	
		}
		if($filepath != ''){
			$filepath = substr($filepath, 0, strlen($filepath)-1);
			$arr__ = explode(".", $filename);
			$hook['perms'][] = array(
				'class' 	=> $arr__[0],
				'function' 	=> 'perms',
				'filename' 	=> $filename,
				'filepath' 	=> $filepath
			);		
		}			
	}
}*/
/* End of file hooks.php */
/* Location: ./application/config/hooks.php */