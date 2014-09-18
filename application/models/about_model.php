<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class About_model extends CI_Model 
{
	public function update_about()
	{
		$error = '';
		$title = ($this ->input ->post("title"))?$this ->lib ->escape($this ->input ->post("title")):'';
		$abouts = array
		(
			'name_content'	=> 'about',
			'title' => $title,
			'content' => $this ->lib ->FCKToSQL($this ->input ->post('content'))
		);
		$result = $this ->database ->db_result("select id from web_content where name_content = 'about'");
		if($result > 0)
			$this ->db ->update('web_content', $abouts,"name_content = 'about'");
		else
			$this ->db ->insert('web_content', $abouts);
		return $error;	
	}
	
	public function load_content()
	{
		$title = '';
		$body = '';
		$re = $this ->db ->query("select * from web_content where name_content = 'about'");
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