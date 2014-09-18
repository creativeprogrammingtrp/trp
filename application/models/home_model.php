<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model 
{
	public function update_home()
	{
		$error = '';
		$title = ($this ->input ->post("title"))?$this ->lib ->escape($this ->input ->post("title")):'';
		$home = array
		(
			'name_content'	=> 'home',
			'title' => $title,
			'content' => $this ->lib ->FCKToSQL($this ->input ->post('content'))
		);
		$result = $this ->database ->db_result("select id from web_content where name_content = 'home'");
		if($result > 0)
			$this ->db ->update('web_content', $home,"name_content = 'home'");
		else
			$this ->db ->insert('web_content', $home);
		return $error;	
	}
	
	public function load_content()
	{
		$title = '';
		$body = '';
		$re = $this ->db ->query("SELECT * FROM web_content WHERE name_content = 'home'");
		if($re -> num_rows() >0)
		{
			$row = $re ->row_array();	
			$title = $row['title'];
			$body = $this ->lib ->SQLToFCK($row['content']);	
		}
		$data = array
		(
			"content" => $body
		);
		return $data;	
	}
	
        
}