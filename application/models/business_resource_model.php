<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_resource_model extends CI_Model 
{
	public function update_business_resource()
	{
		$error = '';
		$title = ($this ->input ->post("title"))?$this ->lib ->escape($this ->input ->post("title")):'';
		$business = array
		(
			'name_content'	=> 'business',
			'title' => $title,
			'content' => $this ->lib ->FCKToSQL($this ->input ->post('content'))
		);
		$result = $this ->database ->db_result("select id from web_content where name_content = 'business'");
		if($result > 0)
			$this ->db ->update('web_content', $business,"name_content = 'business'");
		else
			$this ->db ->insert('web_content', $business);
		return $error;	
	}
	
	public function load_content()
	{
		$title = '';
		$body = '';
		$re = $this ->db ->query("select * from web_content where name_content = 'business'");
		if($re -> num_rows() >0)
		{
			$row = $re ->row_array();	
			$title = $row['title'];
			$body = $this ->lib ->SQLToFCK($row['content']);	
		}
		$data = array
		(
			"title" => $title,
			"content" => $body
		);
		return $data;	
	}
	
	function load_documents(){
		$data = array();
		$re = $this ->db ->query("SELECT * FROM business_resource_documents");
		foreach($re->result_array() as $row)
		{
			if(trim($row['label']) == "")
				$row['label'] = $row['file_name'];
			$data[] = $row;	
		}
		return $data;
	}
	
	function saveData(){
		if($this->input->post('data') && is_array($this->input->post('data')) && count($this->input->post('data')) > 0){
			$arr = $this->input->post('data');
			$arr_banner = array(
				'file_name' => $this->lib->escape($arr['original'])
			);
			$this->db->insert('business_resource_documents',$arr_banner);
		}
		return $this->load_documents();
	}
	
	function del(){
		if($this->input->post('fid') && trim($this->input->post('fid')) != ''){
			$filename = $this->database->db_result("select file_name from business_resource_documents where id = ".$this->lib->escape($this->input->post('fid')));
			$this->db->where('id',$this->input->post('fid'));
			if($this->db->delete('business_resource_documents') == 1){
				if(is_file('resource/business/'.$filename)){
					unlink('resource/business/'.$filename);	
				}
			}
		}	
		return $this->load_documents();
	}
	
	function edit_label(){
		if($this->input->post('fid') && trim($this->input->post('fid')) != ''){
			$label = $this->lib->escape($this->input->post('label'))?$this->lib->escape($this->input->post('label')):'';
			$this->db->update("business_resource_documents", array('label'=>$label),"id = ".$this->lib->escape($this->input->post('fid')));
		}
		return $this->load_documents();
	}
}