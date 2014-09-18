<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assiemail_model extends CI_Model {

	
	public function View(){
		$query = $this->db->query('select * from mailassign');
		if($query->num_rows() > 0){
			return $query->result_array();
		} else return '';
	}
	
	public function addNew($data, $id){
		if(empty($id)){
			$query = $this->db->insert('mailassign', $data);
			if($query !== 0) return 1;
			else return 0;
		} else {
			$query = $this->db->update('mailassign', $data, array('id' => $id));
			if($query !== 0) return 1;
			else return 0;
		}
	}
	
	public function deleteMail($id){
		$query = $this->db->delete('mailassign', array('id' => $id));
		if($query !== 0) return 1;
		else return 0;
	}


}