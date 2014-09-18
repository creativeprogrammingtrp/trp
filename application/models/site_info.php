<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_info extends CI_Model {
	
	public function View(){
		$query = $this->db->query('select * from site_info');
		if($query->num_rows() > 0)
			return $query->result();
		else return '';
	}

	public function Save($data) {
		$this->db->query('TRUNCATE site_info');
		$query = $this->db->insert('site_info', $data);
		if($query !== 0) return 1;
		else return -1;
	}
}