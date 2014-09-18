<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Representatives_model extends CI_Model{
	var $repkey = '';
	var $uid = 0;
	
	var $tblcontries = array();
	var $Member_since = '';
	var $last_login = '';
	var $legal_business_name = '';
	var $Address = '';
	
	var $total_commission = 0;
	var $paid = 0;
	var $balance = 0;
	
	var $sale_rep_setting = array();
	var $Direct_sponsor = 0;
	var $Number_of_level = 0;
	var $from_date, $to_date;
	var $data_parse = array();
	
	var $arr_dataCharts = array();
	var $arr_RepUsers = array();
	var $min_Year;
	
	var $objUser = array();
	var $error_saveUser = '';
	var $replace_key = '';
	var $replace_uid = 0;
	
	public function __construct(){
		parent::__construct();
		$this->uid = $this->author->objlogin->uid;
		$this->loadCountriesList();
                $this->load->library("general");
	}
	
	function setRepKey($key){
		if($key != null && trim($key) != ''){
			$this->repkey = $this->lib->escape($key);
			$this->uid = $this->database->db_result("select representatives.uid from representatives join users on representatives.uid = users.uid where representatives.legal_business_id = '".$this->repkey."'");		
		}else{
			if($this->author->objlogin->role['rid'] == Sale_Representatives){
				$this->repkey = $this->database->db_result("select representatives.legal_business_id from representatives join users on representatives.uid = users.uid where users.uid = ".$this->uid);	
			}	
		}	
	}
	
	function setuid($uid){
		$this->uid = $uid;	
	}
	
	function loadSale_rep_setting(){
            //$this->sale_rep_setting = $this->system->get_sysvals('sale_rep_setting', array());
             $this->sale_rep_setting = $this->general->getLastGeneralSetting();    
	}
	
	function loadCountriesList(){
		$re = $this->db->query("select * from tblcontries");
		foreach($re->result_array() as $row){
			$this->tblcontries[$row['code']] = $row['name'];	
		}	
	}
	
	function loadPaid(){
		$this->paid = $this->database->db_result("select sum(pay) from payments where role = ".Sale_Representatives." and legal_business_id = '".$this->repkey."'", 0);
	}
	
	function loadRepInfo(){
		$re_1 = $this->db->query("select representatives.legal_business_fname,representatives.legal_business_lname,representatives.address,representatives.city,representatives.state,representatives.zipcode,representatives.country,users.created,users.login from representatives join users on representatives.uid = users.uid where users.status <> -1 and representatives.legal_business_id = '".$this->repkey."'");
		if ($re_1->num_rows() > 0){
			$row_1 = $re_1->row_array();
			$this->Member_since = gmdate("F j, Y", $row_1['created']);
			$this->last_login = gmdate("F j, Y, g:i a", $row_1['login']);
			$this->legal_business_name = $row_1['legal_business_fname'].' '.$row_1['legal_business_lname'];
			$this->Address = $row_1['address'].' '.$row_1['city'].', '.$row_1['state'].' '.$row_1['zipcode'].', '.((isset($this->tblcontries[$row_1['country']]))?$this->tblcontries[$row_1['country']]:'US');	
		}
	}
	
	function loadAdCommission(){
		$this->total_commission += $this->database->db_result("select sum(ad_commission.price) from ad_commission join ad_orders join tbaffiliates join users on tbaffiliates.uid = users.uid and ad_orders.okey = ad_commission.okey and ad_commission.akey = tbaffiliates.legal_business_id where users.status <> -1 and ad_commission.rid = ".Sale_Representatives." and ad_commission.akey = '".$this->repkey."'", 0);	
	}
	
	function loadCommissionMonthlyItems(){
		$re = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = ".$this->uid);		
		if ($re->num_rows() > 0){
			foreach($re ->result_array() as $row){
				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1", 0);	
				$qty_buy = $row['quality'] - $qty_refund;
				if($qty_buy < 0) $qty_buy = 0;
				$itemprice = round($row["itemprice"]*$qty_buy, 2);
				$this->total_commission += round($row['commission'] * $itemprice / 100, 2);
			}	
		}
	}
	
	function calBalance(){
		$this->balance = $this->total_commission - $this->paid;	
	}
	
	function addnewbt(){
		$add_new = '';
		if($this->author->isAccess('Representatives', 'Add Representative')){
			$count_child = $this->irep->__loadCountSaleChild__($this->author->objlogin->uid);
			if(isset($this->sale_rep_setting['Direct_sponsor']) && $this->sale_rep_setting['Direct_sponsor'] > $count_child){
				$add_new = '<div style="clear:both; margin-bottom:5px; margin-left:10px" align="right"><input type="button" class="btn btn-primary" value="Add Independent Representative" onclick="window.location=\''.$this->system->cleanUrl().'representatives/add/'.$this->repkey.'\';" /></div>';	
			}	
		}
		return $add_new;	
	}
	
	function loadCommissionIR(){
		$this->loadRepInfo();
		$this->loadPaid();
	//	$this->loadAdCommission();
		$this->loadCommissionMonthlyItems();
		$this->calBalance();
		
		$Minimum_purchased = (isset($this->sale_rep_setting['Minimum_purchased']) && is_numeric($this->sale_rep_setting['Minimum_purchased']))?$this->sale_rep_setting['Minimum_purchased']:0;
		$timeLimits = $this->irep->__getTimeLimits__($this->sale_rep_setting);
		$Purchase = 0;
		$Pending_Earnings = 0;
		$to_date_show = '';
		$from_date_show = '';
		if($timeLimits['from'] != '' && $timeLimits['to'] != ''){
			$from_date_show = gmdate("F j, Y, H:i:s", $timeLimits['from']);
			$to_date_show = gmdate("F j, Y, H:i:s", $timeLimits['to']);
			
			$from_date 	= gmdate("Y-m-d H:i:s", $timeLimits['from']);
			$to_date 	= gmdate("Y-m-d H:i:s", $timeLimits['to']);
 
			$re = $this->db->query("select * from commission_monthly where uid = ".$this->uid." and date_add >= '".$from_date."' and date_add <= '".$to_date."'");
			if($re->num_rows() > 0){
				foreach($re->result_array() as $row){
					$Purchase += $this->orderlib->__getSubTotalOrder__($row['oid']);	 
				}	
			}
   
			$re = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 0 and commission_monthly_items.uid = ".$this->uid." and purchase_date >= '".$from_date."' and purchase_date <= '".$to_date."'");		
			if($re->num_rows() > 0){
				foreach($re->result_array() as $row){
					$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1", 0);
					$qty_buy = $row['quality'] - $qty_refund;
					if($qty_buy < 0) $qty_buy = 0;
					$itemprice = round($row["itemprice"]*$qty_buy, 2);
					$Pending_Earnings += round($row['commission'] * $itemprice / 100, 2);	
                                       
				}
			}
		}	
		
		$this->data_parse['Member_since'] = $this->Member_since;
		$this->data_parse['last_login'] = $this->last_login;
		$this->data_parse['legal_business_name'] = $this->legal_business_name;
		$this->data_parse['legal_business_id'] = $this->repkey;
		$this->data_parse['Address'] = $this->Address;
		
		$this->data_parse['total_paid'] = number_format($this->paid, 2);
		$this->data_parse['total_commission'] = number_format($this->total_commission, 2);
		$this->data_parse['balance'] = $this->lib->showMoney($this->balance);
		
		$this->data_parse['from_date'] = strtotime($from_date_show);
		$this->data_parse['to_date'] = strtotime($to_date_show);
		$this->data_parse['todays'] = gmmktime();
		$this->data_parse['Pending_Earnings'] = number_format($Pending_Earnings, 2);
		$this->data_parse['Calendar'] = $from_date_show.'&nbsp;&nbsp;-&nbsp;&nbsp;'.$to_date_show;
		//$this->data_parse['Purchase'] = number_format($Purchase, 2)." / $".number_format($Minimum_purchased, 2);
		$this->data_parse['Purchase'] = number_format($Purchase, 2);
		$this->data_parse['load_month_current'] = "var month_current = parseInt(".(gmdate('m')*1).", 10);";
		$this->data_parse['load_year_current'] = "var year_current = parseInt(".gmdate('Y').", 10);";
		$this->data_parse['repkey'] = $this->repkey;
		$this->loadUpline();
	}
	
	function getUsersSale(){
		$Minimum_purchased = (isset($this->sale_rep_setting['minimum_purchased']) && is_numeric($this->sale_rep_setting['minimum_purchased']))?$this->sale_rep_setting['minimum_purchased']:0;
		$this->Number_of_level = (isset($this->sale_rep_setting['number_of_level']) && is_numeric($this->sale_rep_setting['number_of_level']))?$this->sale_rep_setting['number_of_level']:0;
		$this->Direct_sponsor = (isset($this->sale_rep_setting['direct_sponsor']) && is_numeric($this->sale_rep_setting['direct_sponsor']))?$this->sale_rep_setting['direct_sponsor']:0;
		$timeLimits = $this->irep->__getTimeLimits__($this->sale_rep_setting);
		
		$this->from_date = gmdate("Y-m-d H:i:s");
		$this->to_date = gmdate("Y-m-d H:i:s");
		if($timeLimits['from'] != '' && $timeLimits['to'] != ''){
			$this->from_date 	= gmdate("Y-m-d H:i:s", $timeLimits['from']);
			$this->to_date 	= gmdate("Y-m-d H:i:s", $timeLimits['to']);
		}	
		$current_level = 0;
		if($this->Number_of_level > $current_level){
			$sql = "select users.ukey,users.name,users.uid,users.pass,users.mail,users.status,users.created,users.firstname,users.lastname,users.middlename,";
			$sql .= "representatives.legal_business_id,representatives.legal_business_fname,legal_business_lname,representatives.author,representatives.address,representatives.city,representatives.state,representatives.zipcode,representatives.country,representatives.phone,representatives.fax,representatives.tax ";
			$sql .= " from representatives join users on representatives.uid = users.uid WHERE users.status <> -1 and representatives.author = ".$this->uid." order by representatives.legal_business_fname ASC ";
			$re = $this->db->query($sql);
			if($re->num_rows() > 0){
				foreach($re->result_array() as $row){
					$this->arr_RepUsers[] = array(
						'uid' => (int)$row['uid'],
						'ukey' => $row['ukey'],
						'author' => (int)$row['author'],
						'LBN' => $row['legal_business_fname'].' '.$row['legal_business_lname'],
						'LBI' => $row['legal_business_id'],
						'adr' => $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:''),
						'phone' => $row['phone'],
						'fax' => $row['fax'],
						'tax' => $row['tax'],
						'fn' => $row['firstname'],
						'ln' => $row['middlename'].' '.$row['lastname'],
						'mail' => $row['mail'],
						'level' => (int)$current_level,
						'com' => $this->getCommissionMonthly($row['uid']),
						'pur' => $this->getPurchasedMonthly($row['uid'])
					);
					$this->getUsersSaleChile($row['uid'], $current_level+1);	
				}	
			}
		}
	}
	
	function getUsersSaleChile($uid, $current_level){
		if($this->Number_of_level > $current_level){
			$sql = "select users.ukey,users.name,users.uid,users.pass,users.mail,users.status,users.created,users.firstname,users.lastname,users.middlename,";
			$sql .= "representatives.legal_business_id,representatives.legal_business_fname,legal_business_lname,legal_business_name,representatives.author,representatives.address,representatives.city,representatives.state,representatives.zipcode,representatives.country,representatives.phone,representatives.fax,representatives.tax ";
			$sql .= " from representatives join users on representatives.uid = users.uid WHERE users.status <> -1 and representatives.author = $uid order by representatives.legal_business_fname ASC ";
			$re = $this->db->query($sql);
			if($re->num_rows() > 0){
				foreach($re->result_array() as $row){
					$this->arr_RepUsers[] = array(
						'uid' => (int)$row['uid'],
						'ukey' => $row['ukey'],
						'author' => (int)$row['author'],
						'LBN' => $row['legal_business_fname']!='' ? $row['legal_business_fname'].' '.$row['legal_business_lname'] : $row['legal_business_name'],
						'LBI' => $row['legal_business_id'],
						'adr' => $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:''),
						'phone' => $row['phone'],
						'fax' => $row['fax'],
						'tax' => $row['tax'],
						'fn' => $row['firstname'],
						'ln' => $row['middlename'].' '.$row['lastname'],
						'mail' => $row['mail'],
						'level' => (int)$current_level,
						'com' => $this->getCommissionMonthly($row['uid']),
						'pur' => $this->getPurchasedMonthly($row['uid'])
					);
					$this->getUsersSaleChile($row['uid'], $current_level+1);
				}
			}	
		}
	}
	
	function getCommissionMonthly($upurchase){
		$Pending_Earnings = 0;
		$re = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 0 and commission_monthly_items.uid = ".$this->uid." and commission_monthly_items.upurchase = ".$upurchase." and commission_monthly_items.purchase_date >= '".$this->from_date."' and commission_monthly_items.purchase_date <= '".$this->to_date."'");		
		if($re->num_rows() > 0){
			foreach($re->result_array() as $row){
				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1", 0);	
				$qty_buy = $row['quality'] - $qty_refund;
				if($qty_buy < 0) $qty_buy = 0;
				$itemprice = round($row["itemprice"]*$qty_buy, 2);
				$Pending_Earnings += round($row['commission'] * $itemprice / 100, 2);
			}
		}
		return $Pending_Earnings;
	}
	
	function getPurchasedMonthly($uid){
		$Purchased = 0;
		$re = $this->db->query("select * from commission_monthly where uid = ".$uid." and date_add >= '".$this->from_date."' and date_add <= '".$this->to_date."'");
		if($re->num_rows() > 0){
			foreach($re->result_array() as $row){
				$Purchased += $this->orderlib->__getSubTotalOrder__($row['oid']);		
			}
		}
		return $Purchased;
	}
	
	function loadDataFromPayment(){
		$re_3 = $this->db->query("select pay,MONTH(date_pay) as month_pay,YEAR(date_pay) as year_pay from payments where role = ".Sale_Representatives." and legal_business_id = '".$this->repkey."'");
		if($re_3->num_rows() > 0){
			foreach($re_3->result_array() as $row_3){
				$month_chart = (int)$row_3['month_pay'];
				$year_chart = (int)$row_3['year_pay'];
				if($this->min_Year > $year_chart) $this->min_Year = $year_chart;
				$check_ = false;
				for($i = 0; $i < count($this->arr_dataCharts); $i++){
					if($this->arr_dataCharts[$i]['month'] == $month_chart && $this->arr_dataCharts[$i]['year'] == $year_chart){
						$this->arr_dataCharts[$i]['paid'] += (float)$row_3['pay'];
						$check_ = true;
						break;	
					}	
				}
				if($check_ == false){
					$this->arr_dataCharts[] = array(
						'year' => $year_chart,
						'month' => $month_chart,
						'paid' => (float)$row_3['pay'],
						'YTD_earnings' => 0,
						'YTD_pd' => 0
					);		
				}		
			}
		}	
	}
	function loadDataFromCommission_monthly_items(){
		$re_2 = $this->db->query("select MONTH(commission_monthly_items.purchase_date) as month_chart,YEAR(commission_monthly_items.purchase_date) as year_chart,commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality,commission_monthly_items.personal_discount from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = ".$this->uid);		
		if($re_2->num_rows() > 0){
                  
			foreach($re_2->result_array() as $row_2){
				$month_chart = (int)$row_2['month_chart'];
				$year_chart = (int)$row_2['year_chart'];
				if($this->min_Year > $year_chart) $this->min_Year = $year_chart;
				$check_ = false;
				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1", 0);
				$qty_buy = $row_2['quality'] - $qty_refund;
				if($qty_buy < 0) $qty_buy = 0;
				$itemprice = round($row_2["itemprice"]*$qty_buy, 2);
				$commission = round($row_2['commission'] * $itemprice / 100, 2);
				
				$YTD_pd = 0;
				$YTD_earnings = $commission;
				if($row_2['personal_discount'] == 1) $YTD_pd = $commission;
				
				for($i = 0; $i < count($this->arr_dataCharts); $i++){
					if($this->arr_dataCharts[$i]['month'] == $month_chart && $this->arr_dataCharts[$i]['year'] == $year_chart){
						$this->arr_dataCharts[$i]['YTD_earnings'] += $YTD_earnings;
						$this->arr_dataCharts[$i]['YTD_pd'] += $YTD_pd;
						$check_ = true;
						break;	
					}	
				}
				if($check_ == false){
					$this->arr_dataCharts[] = array(
						'year' => $year_chart,
						'month' => $month_chart,
						'paid' => 0,
						'YTD_earnings' => $YTD_earnings,
						'YTD_pd' => $YTD_pd
					);		
				}
			}
		}	
	}
	
	function loadDataChart($key){
		$this->repkey = $key;
		$this->arr_dataCharts = array();
		$this->min_Year = $year = (int)gmdate('Y');
		$arr_years = array();
		$this->arr_RepUsers = array();
             
		$sql = "select users.uid,users.created,representatives.legal_business_id,representatives.legal_business_fname from representatives join users on representatives.uid = users.uid where users.status <> -1 and representatives.legal_business_id = '".$this->repkey."'";
		$re = $this->db->query($sql);
		if($re->num_rows() > 0){
			$row = $re->row_array();
			$this->uid = $row['uid'];
			if($row['created'] != null && is_numeric($row['created'])){
				if($this->min_Year > (int)gmdate("Y", $row['created'])) $this->min_Year = (int)gmdate("Y", $row['created']);		
			}
			$this->getUsersSale();
			$this->loadDataFromPayment();
			$this->loadDataFromCommission_monthly_items();
		}
		for($i = $this->min_Year; $i < $year+1; $i++){
			$arr_years[] = (int)$i;	
		}
		return array(
			'objYear' => $arr_years,
			'chart' => $this->arr_dataCharts,
			'users' => array('data' => $this->arr_RepUsers, 'Direct_sponsor' => $this->Direct_sponsor, 'Number_of_level' => $this->Number_of_level)
		);	
	}
	
	function loadUpline(){
		$legal_business_id = 'N/A';
		$legal_business_name = $this->system->siteInfo['signature'];
		$full_name = $this->system->siteInfo['sender_name'];
		$address = $this->system->siteInfo['address'];
		$phone = $this->system->siteInfo['phone'];
		$fax = $this->system->siteInfo['fax'];
		$mail = $this->system->siteInfo['email'];
		$user_name = "N/A";
		$sql = "select representatives.author from representatives join users on representatives.uid = users.uid WHERE users.uid = ".$this->uid;
		$re = $this->db->query($sql);
		if($re->num_rows() > 0){
			$row = $re->row_array();
			$role = $this->author->loadRole($row['author']);
			if($role['rid'] == Affiliates){
				$sql = "select users.mail, users.name, users.firstname,users.lastname,";
				$sql .= "tbaffiliates.legal_business_name,tbaffiliates.legal_business_id,tbaffiliates.address,tbaffiliates.city,tbaffiliates.state,tbaffiliates.zipcode,tbaffiliates.country,tbaffiliates.tel,tbaffiliates.fax ";
				$sql .= "from tbaffiliates join users on tbaffiliates.uid = users.uid WHERE users.status <> -1 and tbaffiliates.uid = ".$row['author'];
				$re = $this->db->query($sql);
				if($re->num_rows() > 0){
					$row = $re->row_array();
					$user_name = $row['name'];
					$legal_business_id = $row['legal_business_id'];
					$legal_business_name = $row['legal_business_name'];
					$full_name = $row['firstname'].' '.$row['lastname'];
					$address = $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:'');
					$phone = $row['tel'];
					$fax = $row['fax'];
					$mail = $row['mail'];
				}	
			}elseif($role['rid'] == Sale_Representatives){
				$sql = "select users.mail,users.firstname, users.name, users.lastname,users.middlename,";
				$sql .= "representatives.legal_business_id,representatives.legal_business_fname,representatives.legal_business_lname,representatives.address,representatives.city,representatives.state,representatives.zipcode,representatives.country,representatives.phone,representatives.fax ";
				$sql .= " from representatives join users on representatives.uid = users.uid WHERE users.status <> -1 and representatives.uid = ".$row['author'];
				$re = $this->db->query($sql);
				if($re->num_rows() > 0){
					$row = $re->row_array();
					$user_name = $row['name'];
					$legal_business_id = $row['legal_business_id'];
					$legal_business_name = $row['legal_business_fname'].' '.$row['legal_business_lname'];
					$full_name = $row['firstname'].' '.$row['middlename'].' '.$row['lastname'];
					$address = $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:'');
					$phone = $row['phone'];
					$fax = $row['fax'];
					$mail = $row['mail'];
				}			
			}
		}
		$this->data_parse['legal_business_id_2'] = $legal_business_id;
		$this->data_parse['legal_business_name_2'] = $legal_business_name;
		$this->data_parse['full_name'] = $full_name;
		$this->data_parse['address'] = $address;
		$this->data_parse['phone'] = $phone;
		$this->data_parse['fax'] = $fax;
		$this->data_parse['mail'] = $mail;
		$this->data_parse['user_name'] = $user_name;
		return $this->data_parse;
	}
	
	public function loadAuthors($uid = 0){
		$role = $this ->author ->loadRole();
		$str = '';
		if($role['rid'] != Administrator && $role['rid'] != Network_Management){
			return $str;	
		}
		
		$str .= '<span style="float:left; padding-left:5px">';
		$str .= '	<select id="ucreate" style="color:#AEAEAE; width:205px">';
		$str .= '    	<option value="" style="color:#AEAEAE">All Affiliates</option>';
		
		$re = $this ->db ->query("select distinct representatives.author from representatives join users on representatives.uid = users.uid where users.status <> -1");
		foreach($re ->result_array() as $row){
			$select = '';
			if($row['author'] == $uid) $select = 'selected="selected"';
			
			$ucreate = 'None';
			$re_2 = $this ->db ->query("select legal_business_id,legal_business_name from tbaffiliates where uid = ".$row['author']);
			if($re_2 ->num_rows() >0){
				$row2 = $re_2->row_array();
				$ucreate = $row2['legal_business_name'];
			}else{
				$re_2 = $this ->db ->query("select name from users where uid = ".$row['author']);
				if($re_2 ->num_rows() >0){
					$row2 = $re_2->row_array();
					$ucreate = $row2['name'];	
				}	
			}
			
			$str .= '<option value="'.$row['author'].'" '.$select.'>'.$ucreate.'</option>';	
		}      
		$str .= '    </select>';
		$str .= '</span>';
		return $str;
	}//end loadAuthors function
	
	public function check_user(){
		$return ='You can use this Username';
		$u = $this ->lib ->escape($this ->input ->post("check_user"));
		$sql = $this ->db ->query("select name from users where name = '".$u."'");
		if($sql ->num_rows() >0) 
		{ 
			$return =  'Username exits, please change Username other'; 
		}
		return $return;
	}//end check_user function
	
	function savetbUser(){
		$firstname = (isset($this->objUser['first_name']) && $this->objUser['first_name'] != '')?$this ->lib ->escape($this->objUser['first_name']):'';
		$middlename = (isset($this->objUser['mi']) && $this->objUser['mi'] != '')?$this ->lib ->escape($this->objUser['mi']):'';
		$lastname = (isset($this->objUser['last_name']) && $this->objUser['last_name'] != '')?$this ->lib ->escape($this->objUser['last_name']):'';	
		if($this->error_saveUser != '') return false;
		
		if (!isset($this->objUser['user_name']) || !preg_match('/^\w{5,}$/', $this->lib->escape($this->objUser['user_name']))) 
		{
			$this->error_saveUser = "Invalid Username.";
			return false;
		}
		
		$re = $this->db->get_where('users',array('name'=>$this->objUser['user_name']));
		$re1 = $this->db->get_where('representatives',array('legal_business_id'=>$this->objUser['user_name']));
		if ($re->num_rows() > 0 || $re1->num_rows() > 0)
		{
			$this->error_saveUser = 'Username already exists.';
			return false;
		}
		
		
		$user_randomkey = 'RE'.$this ->lib ->GeneralRandomNumberKey(12);
		$this ->db ->select("uid");
		$re = $this ->db ->get_where("users",array("ukey" => $user_randomkey));
		while($re ->num_rows() >0){
			$user_randomkey = 'RE'.$this ->lib ->GeneralRandomNumberKey(12);
			$this ->db ->select("uid");
			$re = $this ->db ->get_where("users",array("ukey" => $user_randomkey));
		}			
		
		$data = array(
			'ukey'				=> $user_randomkey,
			'pass' 				=> $this ->author ->encode_password(trim($this->objUser["password"])),
			'name' 				=>$this ->lib ->escape($this->objUser['user_name']),
			'mail'				=> $this ->lib ->escape($this->objUser["primary_email"]),
			'firstname'			=> $firstname,
			'lastname' 			=> $lastname,
			'middlename' 		=> $middlename,
			'phone'				=> $this ->lib ->escape($this->objUser["home_phone"]),
			'mobile'			=> $this ->lib ->escape($this->objUser['mobile']),
			'address' 			=> $this ->lib ->escape($this->objUser['street_address']),
			'city'				=> $this ->lib ->escape($this->objUser['city']),
			'country'			=> $this ->lib ->escape($this->objUser['country']),
			'state'				=> $this ->lib ->escape($this->objUser['state']),
			'zipcode'			=> $this ->lib ->escape($this->objUser['zipcode']),	
			'created'			=> $this ->lib ->getTimeGMT(),
			'access'			=> $this ->lib ->getTimeGMT(),
			'login'				=> $this ->lib ->getTimeGMT(),
			'status'			=> $this->objUser['status']
		);
		
		if($this ->replace_uid >0)
		{
			$this ->delete_user();
			$data['uid'] = $this ->replace_uid;
		}
		$this ->db ->insert('users', $data);
		$this->uid = $this ->db ->insert_id();	
		if(!is_numeric($this->uid) || $this->uid <= 0){
			$this->uid = 0;
			$this->error_saveUser = 'Can not insert Users';		
		}
	}
	
	function sendUserMail($sponsor)
	{
		$query = $this ->db ->query("select * from users where uid =".$this->uid);
		if($query->num_rows()<=0) return;
		$row = $query ->row_array();
		$emailAdd = $row['mail'];
		$sponsor_arr = ($sponsor!='first_level')?$this ->getAuthorVar():NULL;
		$variables = array
		(
			'!full_name' =>$row['firstname'].' '.$row['lastname'],
			'!username' => $row['name'],
			'!password'	=>$this ->author ->decode_password($row['pass'])
		);
		if($sponsor_arr!=NULL)
		{
			$variables_author = array
			(
				'!full_name' =>$sponsor_arr['firstname'].' '.$sponsor_arr['lastname'],
				'!Irep_firstname' =>$row['firstname'],
				'!Irep_lastname' =>$row['lastname'],
				'!Irep_ID' => $row['name'],
				'!Irep_mail'	=>$row['mail'],
				'!enroll_date' =>gmdate('m/d/Y',$row['created'])
			);				
		}
		if($sponsor_arr!=NULL)
		{
			$this ->lib ->sendmailtype($sponsor_arr['mail'],__downline_to_upline_confirmation__ , $variables_author);
		}
		$this ->lib ->sendmailtype($emailAdd ,__new_rep_welcome__, $variables);
	}//end sendUserMail function
	
	private function getAuthorVar()
	{
		$sps = $this ->database ->db_result("select author from representatives where uid = ".$this->uid);
		$spsArr = $this ->db ->query("select firstname,lastname,mail from users where uid ='$sps'");
		if(count($spsArr)<=0) return NULL;
		return $spsArr->row_array();		
	}//end getAuthorEmail function
	
	function saveUserRole(){
		if($this->error_saveUser != '') return false;
		
		$users_roles = array(
			'uid' 	=> $this->uid,
			'rid'	=> Sale_Representatives
		);
		$this ->db ->insert('users_roles', $users_roles);
	}
	
	public function checkSsnExist($snn)
	{
		if(trim($snn)=="")
			return false;
		$snn = $this ->lib ->escape($snn);
		$result = $this->database->db_result("SELECT cid FROM representatives WHERE ssn_itin = '$snn' LIMIT 1");
		if($result)
			return true;
		return false;	
	}//function checkSsnExist
	function savetbRepresentatives($sponsor){
		if($this->error_saveUser != '') return false;
		$ssn = (isset($this->objUser['ssn_itin']) && trim($this->objUser['ssn_itin'])!="")?$this->objUser['ssn_itin']:'';
		if($this->checkSsnExist($ssn))
			return false;
		$legal_business_id = $this->lib->GeneralRandomNumberKey(8);
		$this ->db ->select("uid");
		$re = $this ->db ->get_where("representatives",array("legal_business_id" => $legal_business_id));	
		$re1 = $this->db->get_where("users",array("name" => $legal_business_id));
		while($re ->num_rows() >0 || $re1->num_rows() > 0){
			$legal_business_id = $this ->lib ->GeneralRandomNumberKey(8);
			$this ->db ->select("uid");
			$re = $this ->db ->get_where("representatives",array("legal_business_id" => $legal_business_id));	
		}
		$data = array(
			'uid' => $this->uid,
			'legal_business_name' => ($this->objUser['legal_business_name'] && trim($this->objUser['legal_business_name']) !='')? $this ->lib ->escape($this->objUser['legal_business_name']) :'',
			'legal_business_fname' => $this ->lib ->escape($this->objUser['first_name_2']),
			'legal_business_lname' =>  $this ->lib ->escape($this->objUser['last_name_2']),
			'legal_business_id' => $legal_business_id,
			'address' => $this ->lib ->escape($this->objUser['street_address_2']),
			'apartment_2' => $this ->lib ->escape($this->objUser['apartment_suite_floor_2']),
			'city' => $this ->lib ->escape($this->objUser['city_2']),
			'country'	=> $this ->lib ->escape($this->objUser['country_2']),
			'state' => $this ->lib ->escape($this->objUser['state_2']),
			'zipcode' => $this ->lib ->escape($this->objUser['zipcode_2']),	
			'phone' => $this ->lib ->escape($this->objUser['home_phone_2']),
			'ssn_itin' => $this ->lib ->escape($ssn),
			'secondary_mail' => $this ->lib ->escape($this->objUser['secondary_email']),
			'date_of_birth' => gmdate("Y-m-d", strtotime($this->objUser['date_birth'])),
			'apartment' => $this ->lib ->escape($this->objUser['apartment_suite_floor']),
			'same_checked'		=> $this ->lib ->escape($this->objUser['same_checked']),
			'association'	=> $this ->lib ->escape($this->objUser['association']),
		);
		if($sponsor == 'first_level')
		{
			$data['author'] = $this ->author ->objlogin ->uid;	
		}
		else
		{
			$re = $this ->db ->query("select uid from representatives where legal_business_id = '$sponsor'");
			if($re ->num_rows() <=0)
			{
				$re = $this ->db ->query("select uid from tbaffiliates where legal_business_id = '$sponsor' and firstIR = 1");	
			}
			$row = $re->row_array();
			$data['author'] = $row['uid'];
		}
		$this ->db ->insert('representatives', $data);
		$mid = $this ->db ->insert_id();	
		if(!is_numeric($mid) || $mid <= 0){
			$this->error_saveUser = 'Can not insert representatives';
			$this->db->delete('users', array('uid' => $this->uid)); 
			$this->db->delete('users_roles', array('uid' => $this->uid, 'rid'=>Sale_Representatives)); 		
		}
	}
	
	public function saveUser($sponsor)
	{
		$this->error_saveUser = '';
		if(is_array($this ->input ->post("saveUser")) && count($this ->input ->post("saveUser")) > 0)
		{
			$this->objUser = $this ->input ->post("saveUser");
			$ssn = (isset($this->objUser['ssn_itin']) && trim($this->objUser['ssn_itin'])!="")?$this->objUser['ssn_itin']:'';
			if($this->checkSsnExist($ssn))
			{
				$this->error_saveUser = "SSN / Tax ID already exists.";
				return array('error' => $this->error_saveUser);
			}
			
			$this->savetbUser();
			$this->saveUserRole();
			$this->savetbRepresentatives($sponsor);
			$this->sendUserMail($sponsor);
		}//0
		$customer_name = $this ->database ->db_result("select name from users where uid = ".$this ->uid);
		return array('error' => $this->error_saveUser,"customer_name"=>$customer_name);
	}//end saveUser function
	
	public function loadDataUsers($page=1)
	{
		$num_per_pager = 20;
		$page =(is_numeric($page) && $page>0)?$page:1;
		$limit = $num_per_pager*($page-1);
	
		//$sale_rep_setting = $this ->system->get_sysvals('sale_rep_setting',  $this->config->item('sale_rep_setting_default'));
                $sale_rep_setting  = $this->general->getLastGeneralSetting();
		$Direct_sponsor = (isset($sale_rep_setting['Direct_sponsor']) && is_numeric($sale_rep_setting['Direct_sponsor']) && $sale_rep_setting['Direct_sponsor'] >= 0)?$sale_rep_setting['Direct_sponsor']:0;
		
		$roles = $this ->author ->loadRole();
		$modify = 'no';
		$del = 'no';
		$view = 'no';
		if($this->author->isAccessPerm("representatives","repList")){
			$view = 'yes';	
		}
		if($this->author->isAccessPerm("representatives","edit")){
			$modify = 'yes';	
		}
		if($this->author->isAccessPerm("representatives","delete")){
			$del = 'yes';	
		}
		$level_0 = array();
		if($roles['rid'] == Administrator || $roles['rid'] == Network_Management){
			$where = "rid in (".Administrator.",".Network_Management.")";
			$this ->db ->where($where);
			$re = $this ->db ->get("users_roles");
			foreach($re ->result_array() as $row)
			{
				$this ->db ->select("uid");
				$re = $this ->db ->get_where("users",array("uid" =>$row['uid'],"status" =>1));
				foreach($re ->result_array() as $row_2)
				{
					$level_0[] = $row_2['uid'];	
				}
			}
		}else{
			$level_0[] = $this ->author ->objlogin ->uid;
		}
		$str_level_0 = implode(",", $level_0);
		$arrUsers = array();
		$sql = "select users.created,users.status,users.uid,users.ukey,users.name,users.mail,users.pass,users.firstname,users.lastname,";
		$sql .= "tbaffiliates.id,tbaffiliates.legal_business_name,tbaffiliates.legal_business_id,tbaffiliates.ucreate,tbaffiliates.address,tbaffiliates.city,tbaffiliates.state,tbaffiliates.zipcode,tbaffiliates.country,tbaffiliates.tel,tbaffiliates.fax,tbaffiliates.tax ";
		$sql .= "from tbaffiliates join users on tbaffiliates.uid = users.uid WHERE tbaffiliates.firstIR =1 AND tbaffiliates.ucreate in ($str_level_0) order by tbaffiliates.legal_business_name ASC";
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$user_arr = $this->getUsersSaleChile_2($row['uid']);
			if($row['status'] != 1 && count($user_arr) == 0) continue;
			
			$arrUsers[] = array(
				'uid' => (int)$row['uid'],
				'ukey' => $row['ukey'],
				'author' => $row['ucreate'],
				'LBN' => $row['legal_business_name'],
				'LBI' => $row['legal_business_id'],
				'adr' => $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:''),
				'phone' => $row['tel'],
				'fax' => $row['fax'],
				'tax' => $row['tax'],
				'name' => $row['name'],
				'fn' => $row['firstname'],
				'ln' => $row['lastname'],
				'mail' => $row['mail'],
				'pass' => $this ->author ->decode_password($row['pass']),
				'users' => $user_arr,
				'type' => 0,
				'status' => (int)$row['status']
			);	
		}
		$sql = "select users.ukey,users.name,users.uid,users.pass,users.mail,users.status,users.created,users.firstname,users.lastname,";
		$sql .= "representatives.legal_business_id,representatives.legal_business_name,representatives.legal_business_fname,representatives.legal_business_lname,representatives.author,representatives.address,representatives.city,representatives.state,representatives.zipcode,representatives.country,representatives.phone,representatives.fax,representatives.tax,representatives.ssn_itin ";
		$sql .= " from representatives join users on representatives.uid = users.uid WHERE representatives.author in ($str_level_0) order by representatives.legal_business_fname ASC ";
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$user_arr = $this->getUsersSaleChile_2($row['uid']);
			if($row['status'] != 1 && count($user_arr) == 0) continue;
			$arrUsers[] = array(
				'uid' => (int)$row['uid'],
				'ukey' => $row['ukey'],
				'author' => $row['author'],
				'LBN' =>  $row['legal_business_fname'] == '' ?$row['legal_business_name']:$row['legal_business_fname'].' '.$row['legal_business_lname'],
				'LBI' => $row['legal_business_id'],
				'adr' => $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:''),
				'phone' => $row['phone'],
				'fax' => $row['fax'],
				'tax' => $row['ssn_itin'],
				'name' => $row['name'],
				'fn' => $row['firstname'],
				'ln' => $row['lastname'],
				'mail' => $row['mail'],
				'pass' => $this ->author ->decode_password($row['pass']),
				'users' => $user_arr,
				'type' => 1,
				'status' => (int)$row['status'],
				
			);
		}	
		return array('data'=>$arrUsers, 'view'=>$view, 'modify'=>$modify, 'del'=>$del,'Direct_sponsor'=>$Direct_sponsor);
	}//end loadDataUsers function
	
	public function getUsersSaleChile_2($uid)
	{
		$arrUsers = array();
		$sql = "select users.created,users.status,users.uid,users.ukey,users.name,users.mail,users.pass,users.firstname,users.lastname,";
		$sql .= "tbaffiliates.id,tbaffiliates.legal_business_name,tbaffiliates.legal_business_id,tbaffiliates.ucreate,tbaffiliates.address,tbaffiliates.city,tbaffiliates.state,tbaffiliates.zipcode,tbaffiliates.country,tbaffiliates.tel,tbaffiliates.fax,tbaffiliates.tax ";
		$sql .= "from tbaffiliates join users on tbaffiliates.uid = users.uid WHERE tbaffiliates.ucreate = $uid order by tbaffiliates.legal_business_name ASC";
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$user_arr = $this ->getUsersSaleChile_2($row['uid']);
			if($row['status'] != 1 && count($user_arr) == 0) continue;
			$arrUsers[] = array(
				'uid' => (int)$row['uid'],
				'ukey' => $row['ukey'],
				'author' => $row['ucreate'],
				'LBN' => $row['legal_business_fname']!='' ?$row['legal_business_fname'].' '.$row['legal_business_lname']:$row['legal_business_name'],
				'LBI' => $row['legal_business_id'],
				'adr' => $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:''),
				'phone' => $row['tel'],
				'fax' => $row['fax'],
				'tax' => $row['tax'],
				'name' => $row['name'],
				'fn' => $row['firstname'],
				'ln' => $row['lastname'],
				'mail' => $row['mail'],
				'pass' => $this ->author ->decode_password($row['pass']),
				'users' => $user_arr,
				'type' => 0,
				'status' => (int)$row['status']
			);	
		}
		$sql = "select users.ukey,users.name,users.uid,users.pass,users.mail,users.status,users.created,users.firstname,users.lastname,";
		$sql .= "representatives.legal_business_id,representatives.legal_business_name,representatives.legal_business_fname,representatives.legal_business_lname,representatives.author,representatives.address,representatives.city,representatives.state,representatives.zipcode,representatives.country,representatives.phone,representatives.fax,representatives.tax ";
		$sql .= " from representatives join users on representatives.uid = users.uid WHERE representatives.author = $uid order by representatives.legal_business_fname ASC ";
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$user_arr =  $this ->getUsersSaleChile_2($row['uid']);
			if($row['status'] != 1 && count($user_arr) == 0) continue;
			$arrUsers[] = array(
				'uid' => (int)$row['uid'],
				'ukey' => $row['ukey'],
				'author' => $row['author'],
				'LBN' => $row['legal_business_fname']==''?$row['legal_business_name']:$row['legal_business_fname'].' '.$row['legal_business_lname'],
				'LBI' => $row['legal_business_id'],
				'adr' => $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].', '.(isset($this->tblcontries[$row["country"]])?$this->tblcontries[$row["country"]]:''),
				'phone' => $row['phone'],
				'fax' => $row['fax'],
				'tax' => $row['tax'],
				'name' => $row['name'],
				'fn' => $row['firstname'],
				'ln' => $row['lastname'],
				'mail' => $row['mail'],
				'pass' => $this ->author ->decode_password($row['pass']),
				'users' => $user_arr,
				'type' => 1,
				'status' => (int)$row['status']
			);
		}
		return $arrUsers;
	}//end getUsersSaleChile_2 function
	
	public function delete_client()
	{
		$cid = ($this ->input ->post("cid") && $this ->input ->post("cid")!='')? $this ->lib ->escape($this ->input ->post("cid")):'';
		if($cid!='')
		{
			$this ->db ->where("ukey",$cid);
			$this ->db ->update("users", array("status"=>-1));
			$re = $this ->db ->query("select name,pass,mail from users where ukey = '$cid'");
			if($re ->num_rows() >0){
				$row = $re ->row_array();
				$variables_ = array(
					'!username' => $row['name'],
					'!password' => $this ->author ->decode_password($row['pass'])
				);
				//$this ->lib ->sendmailtype($row['mail'], __account_deleted__, $variables_);		
			}		
		}
		return $this ->loadDataUsers();	
	}//end delete_client function
	
	public function checksponsor($sponsor)
	{
		$this ->loadSale_rep_setting();
		$direct_sponsor = $this ->sale_rep_setting['Direct_sponsor'];
		$query = $this ->db ->get_where("representatives",array(
															"legal_business_id" =>$sponsor
															));
		if($query ->num_rows() <=0) 
		{
			$query = $this ->db ->get_where("tbaffiliates",array(
															"legal_business_id" =>$sponsor,
															"firstIR" =>1
															));
			if($query ->num_rows() <=0) 
			{												
				return "sponsor doesn't exists!";
			}
		}
		$row = $query ->row_array();
		$sponsorId = $row['uid'];
		if($sponsorId!=1)
		{
			$query = $this ->db ->query ("select uid from representatives where author = ".$sponsorId);
			$child =0;
			foreach($query ->result_array() as $row)
			{
				$subquery = $this ->db ->query ("select uid from users  where uid = ".$row['uid']." and status = 1");
				if($subquery ->num_rows() ==1) $child++;
			}
			if($child >= $direct_sponsor) 
			{
				return "this sponsor is full!";
			}	
		}
		return '';
	}//end checksponsor function
	
	public function setFirstIR()
	{
		$affID = $this ->lib ->escape($this -> input ->post("affID"));
		$query = $this ->db ->get_where("tbaffiliates",array("legal_business_id" =>$affID));
		if($query ->num_rows() <=0)
		{
			return 'Affiliate ID# does not exist!';	
		}
		$this ->db ->where("legal_business_id",$affID);
		$this ->db ->update("tbaffiliates",array("firstIR"=>1));
		return 'Set first IR successfully';
	}//end setFirstIR function
	
	public function loadRepData($ukey = '')
	{
		if(trim($ukey)=='') return 'no user is specified';
		$ukey = $this ->lib ->escape($ukey);
		$query = $this ->db ->get_where("users",array("ukey" =>$ukey));
		if($query ->num_rows()<=0) return 'invalid user key';
		$data = $query ->row_array();
		$query = $this ->db ->get_where("representatives",array("uid" =>$data['uid']));
		if($query ->num_rows()<=0) return 'user is not a independent representative';
		$row = $query ->row_array();
		$same_checked = isset($row['same_checked']) && $row['same_checked'] ==1 ? $same_checked = 'checked=\"checked\"':'';
		$data['same_checked'] = $same_checked;
		foreach($row as $key =>$val)
		{
			if($key == 'same_checked') continue;
			if($key=='date_of_birth')
			{			
				$data['rep_'.$key] = gmdate("m/d/Y",strtotime($val));
				continue;	
			}
			$data['rep_'.$key] = $val;
		}
		return $data;
	}//end loadRepData function
	
	public function updateRepAccount($uid)
	{
		$this->error_saveUser = '';
		if($this ->input ->post("saveUser")){
			$this ->uid = $uid;
			$this->objUser = $this ->input ->post("saveUser");
			$this->updatetbUser();
			//$this->sendUserMail();
			$this->updatetbRepresentatives();
		}//0
		return array('error' => $this->error_saveUser);	
	}//end updateRepAccount function
	
	private function checkPassword()
	{
		$password = $this ->author ->encode_password(trim($this->objUser["oldPassword"]));
		$query = $this ->db ->get_where("users",array("uid" => $this ->uid,"pass" =>$password));
		if($query ->num_rows() <=0)
		{
			return 'Old password incorrect!';	
		}
		return '';
	}//end checkPassword function
	
	private function updatetbUser(){
		if(isset($this->objUser["oldPassword"]) && trim($this->objUser["oldPassword"])!= '')
		{
			$this ->error_saveUser = $this ->checkPassword();	
		}
		$firstname = (isset($this->objUser['first_name']) && $this->objUser['first_name'] != '')?$this ->lib ->escape($this->objUser['first_name']):'';
		$middlename = (isset($this->objUser['mi']) && $this->objUser['mi'] != '')?$this ->lib ->escape($this->objUser['mi']):'';
		$lastname = (isset($this->objUser['last_name']) && $this->objUser['last_name'] != '')?$this ->lib ->escape($this->objUser['last_name']):'';
		if($this->error_saveUser != '') return false;	
	
		$data = array(
			'mail'				=> $this ->lib ->escape($this->objUser["primary_email"]),
			'firstname'			=> $firstname,
			'lastname' 			=> $lastname,
			'middlename' 		=> $middlename,
			'phone'				=> $this ->lib ->escape($this->objUser["home_phone"]),
			'mobile'			=> $this ->lib ->escape($this->objUser['mobile']),
			'address' 			=> $this ->lib ->escape($this->objUser['street_address']),
			'city'				=> $this ->lib ->escape($this->objUser['city']),
			'country'			=> $this ->lib ->escape($this->objUser['country']),
			'state'				=> $this ->lib ->escape($this->objUser['state']),
			'zipcode'			=> $this ->lib ->escape($this->objUser['zipcode']),
		);
		if(isset($this->objUser["oldPassword"]) && trim($this->objUser["oldPassword"])!= '')
		{
			$data['pass'] = $this ->author ->encode_password(trim($this->objUser["newPassword"]));
		}
		$this ->db ->where("uid",$this ->uid);
		$this ->db ->update('users', $data);
	}//end updatetbUser function
	
	private function updatetbRepresentatives(){
		if($this->error_saveUser != '') return false;
		if (trim($this->lib->escape($this->objUser['ssn_itin']))!="")
		{
			$query = $this->db->query("select * from representatives where uid <> '".$this->uid."' and  ssn_itin = '".trim($this->lib->escape($this->objUser['ssn_itin']))."'");
			if ($query->num_rows()>0)
			{
				$this->error_saveUser = "SSN / Tax ID already exists.";
				return false;
			}
		}	
		$data = array(		
			'legal_business_name' => ($this->objUser['legal_business_name'] && trim($this->objUser['legal_business_name']) !='')? $this ->lib ->escape($this->objUser['legal_business_name']) :'',
			'legal_business_fname' => $this ->lib ->escape($this->objUser['first_name_2']),
			'legal_business_lname' =>  $this ->lib ->escape($this->objUser['last_name_2']),
			'address' => $this ->lib ->escape($this->objUser['street_address_2']),
			'apartment_2' => $this ->lib ->escape($this->objUser['apartment_suite_floor_2']),
			'city' => $this ->lib ->escape($this->objUser['city_2']),
			'country' => $this ->lib ->escape($this->objUser['country_2']),
			'state' => $this ->lib ->escape($this->objUser['state_2']),
			'zipcode' => $this ->lib ->escape($this->objUser['zipcode_2']),	
			'phone' => $this ->lib ->escape($this->objUser['home_phone_2']),
			'payment_preference' => $this ->lib ->escape($this->objUser['payment_preference']),
			'ssn_itin' => $this ->lib ->escape($this->objUser['ssn_itin']),
			'secondary_mail' => $this ->lib ->escape($this->objUser['secondary_email']),
			'date_of_birth' => gmdate("Y-m-d", strtotime($this->objUser['date_birth'])),
			'apartment' => $this ->lib ->escape($this->objUser['apartment_suite_floor']),
			'same_checked'		=> $this ->lib ->escape($this->objUser['same_checked']),
			'association'	=> $this ->lib ->escape($this->objUser['association']),
		);
		$this ->db ->where("uid",$this ->uid);
		$this ->db ->update('representatives', $data);
	}//end updatetbRepresentatives function	
	
	public function no_sponsor_mail()
	{
		$customer =$this ->input ->post("send_email");
		$content = "<h2>Customer's informations:</h2>";
		$content .= '<b>Name:</b> '.$customer['name'].'<br/>';
		$content .= '<b>Email:</b>'.$customer['email'].'<br/>';
		$content .= '<b>Phone #:</b>'.$customer['phone'].'<br/>';
		$this->load->library('email');
		$this->email->from($this->system->siteInfo['email'], $this->system->siteInfo['sender_name']);
		if(!isset($this->system->siteInfo['enroll_email']) || $this->system->siteInfo['enroll_email']=="")
			return false;
		$this->email->to($this->system->siteInfo['enroll_email']);
		$this->email->subject("Enroll New");
		$this->email->message($content);
		$this->email->send();
	}//end no_sponsor_mail function
	
	public function getSponsorByUkey()
	{
		$key = $this ->lib ->escape($this ->replace_key);
		if($key=='') return NULL;
		$query = $this ->db ->query("SELECT uid FROM users WHERE ukey = '$key'");
		$row = $query ->row_array();
		if(count($row)==0) return NULL;
		$this ->replace_uid = $row['uid'];
		$query = $this ->db ->query("SELECT author FROM representatives WHERE uid = ".$row['uid']);
		$row = $query ->row_array();
		if(count($row)==0) return NULL;
		if($row['author']==1) return 'first_level';
		$query = $this ->db ->query("SELECT legal_business_id FROM representatives WHERE uid = ".$row['author']);
		$row = $query ->row_array();
		if(count($row)==0) return NULL;
		return $row['legal_business_id'];
	}//end getsponsor function
	
	private function delete_user()
	{
		$this ->db ->where("uid",$this ->replace_uid);	
		$this ->db ->delete("users");
		$this ->db ->where("uid",$this ->replace_uid);	
		$this ->db ->delete("representatives");
		$this ->db ->where("uid",$this ->replace_uid);	
		$this ->db ->delete("users_roles");
	}//end delete_user function
	
}//end Representative class