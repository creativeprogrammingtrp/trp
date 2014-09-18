<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class orderlib{
	var $CI;
	function __construct(){
		 $this->CI =& get_instance();
	}
	
	function __getSubTotalOrder__($oid){
		$subtotal = 0;
		$re_1 = $this->CI->db->query("SELECT * FROM order_detais where orderid = '$oid' order by id ASC");
		if($re_1->num_rows() > 0){
			foreach($re_1->result_array() as $row){
				$amount = round($row['itemprice'] * $row["quality"], 2);
				$subtotal += $amount;	
			}	
		}
		return $subtotal;
	}
	
}