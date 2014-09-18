<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class general {
    var $CI;
	private $general_setting = array(
		array(
			'id' => 1,
			'minimum_purchased' => 0,
			'limit_time_purchase' => 1,
			'units_purchase' => 1,
			'number_of_level' => 1,
			'direct_sponsor' => 1,
			'to_be_active' => 0,
			'units_active' => 1,
			'date_apply' => "2013-08-01",
			'minimum_payment' => 0,
			'limit_time_payment' => 0,
			'units_payment' => 0,
			'time_purchase_actived' => 0,
			'units_time_purchase' => 0,
			'date_update' => "2013-08-01",
			'date_holding_account' => 20
		)
	);
    function __construct() {
        $this->CI = & get_instance();
		$this->get_all_id_general_setting();
    }
	
	function get_all_id_general_setting(){
		$query = $this->CI->db->query("SELECT * FROM general_setting order by id desc");
		if ($query->num_rows() > 0){
			$this->general_setting = array();
			foreach($query->result_array() as $row){
				foreach($row as $key => $value){
					if($key != 'date_update' && $key != 'date_apply')
						$row[$key] = (float)$value;	
				}
				 $this->general_setting[$row['id']] = $row;
			}
		}
	}
	
	public function getItemGeneralSetting($com_set_id){
		if(isset($this->general_setting[$com_set_id])) return $this->general_setting[$com_set_id];	
	}
	
	public function getLastGeneralSetting(){
		foreach($this->general_setting as $key => $arr){
			return $this->general_setting[$key];			
		}	
	}
}