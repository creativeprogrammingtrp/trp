<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appearance_model extends CI_Model {


	public function View(){
		$query = $this->db->query('SELECT * FROM sysvals WHERE sysval_title = "_themes_"');
		if($query->num_rows() > 0)
			return $query->result();
		else return '';
	}	

	public function Update($data, $id){
		if(empty($id)) {
			$query = $this->db->insert('sysvals', $data);
			if($query !== 0) return 1;
			else return 0;
		} else {
			$query = $this->db->update('sysvals', $data, array('sysval_id' => $id));
			if($query !== 0) return 1;
			else return 0;
		}
	}


}