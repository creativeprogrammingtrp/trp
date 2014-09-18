<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_brand extends CI_Model 
{
	public function getBrandList()
	{
		$query = $this->db->query("SELECT manufacturers.legal_business_name as m_name FROM manufacturers INNER JOIN users ON manufacturers.uid = users.uid WHERE users.status = 1 ORDER BY manufacturers.legal_business_name ASC");
		return $query->result_array();
	}//function getBrandList
	
}//class M_brand