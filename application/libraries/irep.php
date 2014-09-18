<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class irep{
	var $CI;
	function __construct(){
		 $this->CI =& get_instance();
	}
	
	function __loadCountSaleChild__($uid){
		$tbaffiliates_count = $this->CI->database->db_result("select count(tbaffiliates.uid) from tbaffiliates join users on tbaffiliates.uid = users.uid WHERE users.status <> -1 and tbaffiliates.ucreate = $uid");
		$representatives_count = $this->CI->database->db_result("select count(representatives.uid) from representatives join users on representatives.uid = users.uid WHERE users.status <> -1 and representatives.author = $uid");
		return max($tbaffiliates_count, $representatives_count);
	}
	
	function __getTimeLimits__($sale_rep_setting){
		$from_date = '';
		$to_date = '';
		$date_apply = (isset($sale_rep_setting['date_apply']) && $sale_rep_setting['date_apply'] != '')?$sale_rep_setting['date_apply']:'';
		$limit_time_purchase = (isset($sale_rep_setting['limit_time_purchase']) && is_numeric($sale_rep_setting['limit_time_purchase']) && $sale_rep_setting['limit_time_purchase'] > 0)?$sale_rep_setting['limit_time_purchase']:1;
		$units_purchase = (isset($sale_rep_setting['units_purchase']) && is_numeric($sale_rep_setting['units_purchase']) && $sale_rep_setting['units_purchase'] > 0)?$sale_rep_setting['units_purchase']:1;
		$rang = $limit_time_purchase * $units_purchase;
		$today = $this->CI->lib->getTimeGMT();
		if($date_apply != ''){
			$date_apply_int = strtotime($date_apply);
			$from_date = $date_apply_int;
			$to_date = $from_date + $rang*24*60*60 - 1;
			while($to_date < $today){
				$from_date = $to_date+1;
				$to_date = $from_date + $rang*24*60*60 - 1;
			}
		}
		return array('from' => $from_date, 'to' => $to_date);
	}
}