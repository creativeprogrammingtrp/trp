<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_term extends CI_Model 
{
	private $termContentName = 'term';
	public function loadContentByName()
	{
		$content = $this->database->db_result("SELECT content FROM web_content WHERE name_content ='$this->termContentName' LIMIT 1");
		if(!$content)
			return "";
		return $this ->lib->SQLToFCK($content);	
	}//function loadContentByName
	public function updatetermContent()
	{
		$data = array
		(
			'name_content'	=> $this->termContentName,
			'title' 		=> '',
			'content' 		=> ($this->input->post('content'))?$this->lib->FCKToSQL($this->input->post('content')):'',
		);
		$checkExist = $this ->database ->db_result("SELECT id FROM web_content WHERE name_content = '$this->termContentName'");
		if(!is_numeric($checkExist) || $checkExist<=0)
			$this->db->insert('web_content', $data);
		else
			$this->db->update('web_content', $data,"name_content = '$this->termContentName'");
		return 1;
	}//function updatetermContent
}//class M_term