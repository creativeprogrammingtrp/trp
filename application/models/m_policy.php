<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_policy extends CI_Model 
{
	private $policyContentName = 'policy';
	public function loadContentByName()
	{
		$content = $this->database->db_result("SELECT content FROM web_content WHERE name_content ='$this->policyContentName' LIMIT 1");
		if(!$content)
			return "";
		return $this ->lib->SQLToFCK($content);	
	}//function loadContentByName
	public function updatePolicyContent()
	{
		$data = array
		(
			'name_content'	=> $this->policyContentName,
			'title' 		=> '',
			'content' 		=> ($this->input->post('content'))?$this->lib->FCKToSQL($this->input->post('content')):'',
		);
		$checkExist = $this ->database ->db_result("SELECT id FROM web_content WHERE name_content = '$this->policyContentName'");
		if(!is_numeric($checkExist) || $checkExist<=0)
			$this->db->insert('web_content', $data);
		else
			$this->db->update('web_content', $data,"name_content = '$this->policyContentName'");
		return 1;
	}//function updatePolicyContent
}//class M_policy