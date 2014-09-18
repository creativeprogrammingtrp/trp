<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends CI_Controller 
{
	 var $allowed_ext = array (

	  // archives
	  'zip' => 'application/zip',
	
	  // documents
	  'pdf' => 'application/pdf',
	  'doc' => 'application/msword',
	  'xls' => 'application/vnd.ms-excel',
	  'ppt' => 'application/vnd.ms-powerpoint',
	  
	  // executables
	  'exe' => 'application/octet-stream',
	
	  // images
	  'gif' => 'image/gif',
	  'png' => 'image/png',
	  'jpg' => 'image/jpeg',
	  'jpeg' => 'image/jpeg',
	
	  // audio
	  'mp3' => 'audio/mpeg',
	  'wav' => 'audio/x-wav',
	
	  // video
	  'wmv' => 'video/mpeg',
	  'mpeg' => 'video/mpeg',
	  'mpg' => 'video/mpeg',
	  'mpe' => 'video/mpeg',
	  'mov' => 'video/quicktime',
	  'avi' => 'video/x-msvideo',
	  'flv' => 'video/x-flv',
	  'swf' => 'application/x-shockwave-flash'
	);


	public function get_file($f='',$fc='')
	{
		if (ALLOWED_REFERRER != '' && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)) 
		{
		  die("Internal server error. Please contact system administrator.");
		}
		
		set_time_limit(0);
		
		if (empty($f)) 
		{
		  die("Please specify file name for download.");
		}
		
		$fname = basename($f);
		$file_path = $f;
		
		$file_path = $this ->find_file(BASE_DIR, $fname, $file_path);
		if (!is_file($file_path)) {
		  die("File does not exist. Make sure you specified correct file name."); 
		}
		
		// file size in bytes
		$fsize = filesize($file_path); 
		
		// file extension
		$fext = strtolower(substr(strrchr($fname,"."),1));
		
		// check if allowed extension
		if (!array_key_exists($fext, $this ->allowed_ext)) {
		  die("Not allowed file type."); 
		}
		
		if ($this ->allowed_ext[$fext] == '') 
		{
		  $mtype = '';
		  
		  if (function_exists('mime_content_type')) {
			$mtype = mime_content_type($file_path);
		  }
		  else if (function_exists('finfo_file')) 
		  {
			$finfo = finfo_open(FILEINFO_MIME); // return mime type
			$mtype = finfo_file($finfo, $file_path);
			finfo_close($finfo);  
		  }
		  if ($mtype == '') 
		  {
			$mtype = "application/force-download";
		  }
		}
		else
		{
		  $mtype = $this ->allowed_ext[$fext];
		}
		
		if (empty($fc)) 
		{
		  $asfname = $fname;
		}
		else
		{
		  $asfname = str_replace(array('"',"'",'\\','/'), '', $fc);
		  if ($asfname === '') $asfname = 'NoName';
		}
		
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: $mtype");
		header("Content-Disposition: attachment; filename=\"$asfname\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . $fsize);
		
		$file = @fopen($file_path,"rb");
		if ($file) {
		  while(!feof($file)) {
			print(fread($file, $fsize));
			flush();
			if (connection_status()!=0) {
			  @fclose($file);
			  die();
			}
		  }
		  @fclose($file);
		}
		
		if (!LOG_DOWNLOADS) die();
		
		$f = @fopen(LOG_FILE, 'a+');
		if ($f) {
		  @fputs($f, gmdate("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
		  @fclose($f);
		}

	}//end index function
	
	private function find_file ($dirname, $fname, $file_path)
	{
		$dir = opendir($dirname);
		while ($file = readdir($dir)) 
		{
		  if (isset($file_path) && $file_path !='' && $file != '.' && $file != '..') 
		  {
			
			if (is_dir($dirname.'/'.$file)) 
			{
			  $this ->find_file($dirname.'/'.$file, $fname, $file_path);
			}
			elseif (file_exists($dirname.'/'.$fname))
			{
				$file_path = $dirname.'/'.$fname;
				break;
			}
		  }
		}
		return $file_path;
	  } // find_file
		
	function perms()
	{
		$perms['Download Resource'] = array('get_file');
		return $perms;		
	}//end perms function
	
}//end Download class