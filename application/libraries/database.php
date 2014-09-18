<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Database{
	var $CI;
	
	function __construct(){
		$this->CI =& get_instance();
	}
	
	function db_result($sql, $default_value = FALSE) {
		$result = $this->CI->db->query($sql);
		if ($result->num_rows() > 0){
			$array = $result->row_array();
			foreach($array as $key => $value){
				return $value;	
			}
		}
	  	return $default_value;
	}
}