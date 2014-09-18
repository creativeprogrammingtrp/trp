<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Promotype_model extends CI_Model
{
	public function loadObj()
	{		
		$modify = 'no';
		$del = 'no';
		if($this->author->isAccessPerm("promotype","edit")){
			$modify = 'yes';	
		}
		if($this->author->isAccessPerm("promotype","delete")){
			$del = 'yes';	
		}	
		$re = $this->db->query("select * from promotion_type where status <> -1 order by name ASC");
		foreach($re->result_array() as $row){
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//loadObj function
	
	public function saveObj()
	{
		$key = $this ->lib ->GeneralRandomKey(20);
		$re = $this ->db->query("select id from promotion_type where pkey = '$key'");		
		while($re->num_rows() >0){
			$key = $this ->lib ->GeneralRandomKey(20);
			$re = $this ->db->query("select id from promotion_type where pkey = '$key'");
		}
		$error = '';
		$data_ = array(
			'pkey'		=> $key,
			'name' 		=> $this ->lib->escape($_POST['name']),
			'description' => $this ->lib->escape($_POST['description'])		
		);
		$this->db->insert('promotion_type', $data_);
		$id = $this->db->insert_id();
		if(!is_numeric($id) || $id < 0) $error = _error_cannot_insert_db_;
		return array('error' => $error);
	}//saveObj function
	
	public function loadValue($pkey){
		$name = '';	
		$key = '';
		$description = '';
		if(!empty($pkey)){
			$key = $pkey;
			$re = $this ->db->query("select * from promotion_type where pkey = '$key'");
			if($re->num_rows()>0){
				$row = $re->row_array();
				$name = $row['name'];		
				$description = $row['description'];
			}	
		}
		return array(
			'key' =>$key,
			'name' =>$name,
			'description' =>$description,
		);
	}//loadValue function
	
	public function edit_saveObj()
	{
		$key = (isset($_POST['key'])&&$_POST['key']!='')?$_POST['key']:'';
		$error = '';
		$data_ = array(
			'name' 		=> $this ->lib ->escape($_POST['name']),
			'description' => $this ->lib ->escape($_POST['description']),		
		);
		$this ->db->update('promotion_type', $data_, "pkey = '$key'");
		return array('error' => $error);
	}//edit_saveObj function
	
	public function delete_obj()
	{
		if(isset($_POST['key'])){
			$this ->db->update('promotion_type', array('status' => -1), "pkey = '".$_POST['key']."'");
		}
		return $this ->loadObj();
	}//delete_obj function
}//Promotype_model class