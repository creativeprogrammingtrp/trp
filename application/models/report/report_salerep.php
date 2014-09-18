<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_salerep extends CI_Model {
	var $arr_dataCharts = array();
	var $min_Year = 0;
	var $sale_rep_setting = array();
	var $tblcontries = array();
	
	function __construct(){
		parent::__construct();
                $this->load->library("general");
	}
	
	function loadSale_rep_setting(){
		//$this->sale_rep_setting = $this->system->get_sysvals('sale_rep_setting', array());
                $this->sale_rep_setting  = $this->general->getLastGeneralSetting();
	}
	
	function loadCountriesList(){
		$re = $this->db->query("select * from tblcontries");
		foreach($re->result_array() as $row){
			$this->tblcontries[$row['code']] = $row['name'];	
		}	
	}
	
	function payment(){
		$content_check = array();
		$this->loadCountriesList();	
		$error = '';
		$pay = 0;
		$pay_key = '';
		$arr_saleRep = (isset($_POST['arr_saleRep']) && is_array($_POST['arr_saleRep']))?$_POST['arr_saleRep']:array();
		$file_id = (isset($_POST['file_id']) && $_POST['file_id'] != '')?$_POST['file_id']:'';
		
		$SIGNATURE_ = '';
		if(isset($_SESSION["file_upload"][$file_id])){
			$SIGNATURE_ = '<img width="330px" border="0" src="'.$this->system->URL_server__().'plupload/thumb.php?id='.$file_id.'">';	
		}
		$this->load->library("num2text");
		if(count($arr_saleRep) > 0){
			foreach($arr_saleRep as $legal_business_id){
				$sql = "select uid,legal_business_fname,legal_business_lname,address,city,state,zipcode,country from representatives where legal_business_id = '$legal_business_id'";
				$re = $this->db->query($sql);
				if($re->num_rows() > 0){
					$row = $re->row_array();
					$earnings = 0;
					$uid = $row['uid'];
					
					$re_2 = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = ".$uid);		
					if($re_2->num_rows() > 0){
						foreach($re_2->result_array() as $row_2){
							$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1", 0);	
							$qty_buy = $row_2['quality'] - $qty_refund;
							if($qty_buy < 0) $qty_buy = 0;
							$itemprice = round($row_2["itemprice"]*$qty_buy, 2);
							$earnings += round($row_2['commission'] * $itemprice / 100, 2);	
						}	
					}
					
					$paid = $this->database->db_result("select sum(pay) from payments where role = ".Sale_Representatives." and legal_business_id = '".$legal_business_id."'", 0);
					$balance = $earnings - $paid;
					if($balance <= 0) continue;
					
					$pay_key = $this->lib->GeneralRandomKey(20);
					$re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
					while($re_key->num_rows() > 0){
						$pay_key = $this->lib->GeneralRandomKey(20);
						$re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
					}
					$datas = array(
						'pkey' => $pay_key,
						'role' => Sale_Representatives,
						'legal_business_id' => $legal_business_id,
						'legal_business_name' => $row['legal_business_fname'].' '.$row['legal_business_lname'],
						'pay' => $balance,
						'date_pay' => gmdate("Y-m-d H:i:s")
					);
					$paymentsID = $this->db->insert("payments", $datas);	
					if(!is_numeric($paymentsID)) $error = 'Can not save to database.';
					$n2s = $this->num2text->convertDolla($balance);
	
					$address = $row['address'].'<br>'.$row['city'].', '.$row['state'].' '.$row['zipcode'].'<br>'.(isset($this->tblcontries[$row['country']])?$this->tblcontries[$row['country']]:$row['country']);
					
					$content_check[] = array(
						'title' => 'Commission',
						'date' => gmdate("m/d/Y"),
						'pay_to' => $row['legal_business_fname'].' '.$row['legal_business_lname'],
						'address' => $address,
						'invoice_number' => sprintf('%03d', $paymentsID),
						'Total' => number_format($balance, 2),
						'n2s' => $n2s,
						'SIGNATURE' => $SIGNATURE_
					);
				}	
			}	
		}
		return $content_check;
	}
	
	function load_representatives_info($legal_business_id){
		$sql = "select uid,legal_business_name,address,city,state,zipcode,country from representatives where legal_business_id = '$legal_business_id'";
		$re = db_query($sql);
		if($row = db_fetch_array($re)){	
		
		}
		return false;
	}
	
	function loadReplist_for_Payment(){
		$this->loadSale_rep_setting();
		
		$Minimum_payment = (isset($this->sale_rep_setting['Minimum_payment']) && is_numeric($this->sale_rep_setting['Minimum_payment']) && $this->sale_rep_setting['Minimum_payment'] >= 0)?(float)$this->sale_rep_setting['Minimum_payment']:0;
		$timeLimits = $this->__getTimeLimits2__();
		
		$arr_Affiliate = array();
		$sql = "select count(representatives.legal_business_id) from representatives join users on representatives.uid = users.uid where users.status <> -1";
		$sql = "select representatives.uid,representatives.legal_business_id,representatives.legal_business_fname,representatives.legal_business_lname from representatives join users on representatives.uid = users.uid where users.status <> -1 ";
		$re = $this->db->query($sql);
		if($re->num_rows() > 0){
			foreach($re->result_array() as $row){
				$earnings = 0;
				$uid = $row['uid'];
				$re_2 = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = ".$uid);		
				if($re_2->num_rows() > 0){
					foreach($re_2->result_array() as $row_2){
						$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1", 0);	
						$qty_buy = $row_2['quality'] - $qty_refund;
						if($qty_buy < 0) $qty_buy = 0;
						$itemprice = round($row_2["itemprice"]*$qty_buy, 2);
						$earnings += round($row_2['commission'] * $itemprice / 100, 2);	
					}	
				}
				$paid = $this->database->db_result("select sum(pay) from payments where role = ".Sale_Representatives." and legal_business_id = '".$row['legal_business_id']."'", 0);
				$balance = $earnings - $paid;
				
				if($balance <= 0 || $balance < $Minimum_payment) continue;
				
				$arr_Affiliate[] = array(
					'id' => $row['legal_business_id'],
					'name' => $row['legal_business_fname'].' '.$row['legal_business_lname'],
					'earnings' => (float)$earnings,
					'paid' => (float)$paid
				);	
			}	
		}
		return array('data'=>$arr_Affiliate);
	}
	
	function __getTimeLimits2__(){
		$from_date = '';
		$to_date = '';
		$date_apply = (isset($this->sale_rep_setting['date_apply']) && $this->sale_rep_setting['date_apply'] != '')?$this->sale_rep_setting['date_apply']:'';
		$limit_time_payment = (isset($this->sale_rep_setting['limit_time_payment']) && is_numeric($this->sale_rep_setting['limit_time_payment']) && $this->sale_rep_setting['limit_time_payment'] > 0)?$this->sale_rep_setting['limit_time_payment']:1;
		$units_payment = (isset($this->sale_rep_setting['units_payment']) && is_numeric($this->sale_rep_setting['units_payment']) && $this->sale_rep_setting['units_payment'] > 0)?$this->sale_rep_setting['units_payment']:1;
		$rang = $limit_time_payment * $units_payment;
		$today = $this->lib->getTimeGMT();
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
	
	function loadchart(){
		$arr_years = array();
		$this->arr_dataCharts = array();
		$this->min_Year = $year = (int)gmdate('Y');
		
		$this->loadPay();
		$this->load_commission_monthly_items();
		
		for($i = $this->min_Year; $i < $year+1; $i++){
			$arr_years[] = (int)$i;	
		}
		
		return array(
			'objYear' => $arr_years,
			'chart' => $this->arr_dataCharts
		);	
	}
	
	function load_commission_monthly_items(){
		$sql = "select users.uid,users.created,representatives.legal_business_id from representatives join users on representatives.uid = users.uid where users.status <> -1";
		$re = $this->db->query($sql);
		if($re->num_rows() > 0){
			foreach($re->result_array() as $row){
				if($row['created'] != null && is_numeric($row['created'])){
					if($this->min_Year > (int)gmdate("Y", $row['created'])) $this->min_Year = (int)gmdate("Y", $row['created']);		
				}
				$uid = $row['uid'];
				$re_2 = $this->db->query("select MONTH(commission_monthly_items.purchase_date) as month_chart,YEAR(commission_monthly_items.purchase_date) as year_chart,commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = ".$uid);		
				if($re_2->num_rows() > 0){
					foreach($re_2->result_array() as $row_2){
						$month_chart = (int)$row_2['month_chart'];
						$year_chart = (int)$row_2['year_chart'];
						$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1", 0);	
						$qty_buy = $row_2['quality'] - $qty_refund;
						if($qty_buy < 0) $qty_buy = 0;
						$itemprice = round($row_2["itemprice"]*$qty_buy, 2);
						$YTD_earnings = round($row_2['commission'] * $itemprice / 100, 2);
						//echo $YTD_earnings."<br>";
						$check_ = false;
						for($i = 0; $i < count($this->arr_dataCharts); $i++){
							if($this->arr_dataCharts[$i]['month'] == $month_chart && $this->arr_dataCharts[$i]['year'] == $year_chart){
								$this->arr_dataCharts[$i]['YTD_earnings'] += (float)$YTD_earnings;
								$check_ = true;
								break;	
							}	
						}
						if($check_ == false){
							$this->arr_dataCharts[] = array(
								'year' => $year_chart,
								'month' => $month_chart,
								'paid' => 0,
								'YTD_earnings' => (float)$YTD_earnings
							);		
						}	
					}	
				}
			}	
		}	
	}
	
	function loadPay(){
		$re_3 = $this->db->query("select pay,MONTH(date_pay) as month_pay,YEAR(date_pay) as year_pay from payments where role = ".Sale_Representatives);
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
						'YTD_earnings' => 0
					);		
				}	
			}	
		}	
	}
	
	function loadRepList(){
		$num_per_pager = 20;
		$page = (isset($_POST['page'])&&is_numeric($_POST['page'])&&$_POST['page']>0)?$_POST['page']:1;
		$limit = $num_per_pager*($page-1);
		
		$arr_Affiliate = array();
		$sql = "select count(representatives.legal_business_id) from representatives join users on representatives.uid = users.uid where users.status <> -1";
		$maxlength = $this->database->db_result($sql, 0);
		$sql = "select representatives.uid,representatives.legal_business_id,representatives.legal_business_fname,representatives.legal_business_lname,representatives.legal_business_name from representatives join users on representatives.uid = users.uid where users.status <> -1 limit $limit,".$num_per_pager;
		$re = $this->db->query($sql);
		if($re->num_rows() > 0){
			foreach($re->result_array() as $row){
				$earnings = 0;
				$uid = $row['uid'];
				$re_2 = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = ".$uid);		
				if($re_2->num_rows() > 0){
					foreach($re_2->result_array() as $row_2){
						$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1", 0);	
						$qty_buy = $row_2['quality'] - $qty_refund;
						if($qty_buy < 0) $qty_buy = 0;
						$itemprice = round($row_2["itemprice"]*$qty_buy, 2);
						$earnings += round($row_2['commission'] * $itemprice / 100, 2);	
					}	
				}
				$paid = $this->database->db_result("select sum(pay) from payments where role = ".Sale_Representatives." and legal_business_id = '".$row['legal_business_id']."'", 0);
				$arr_Affiliate[] = array(
					'id' => $row['legal_business_id'],
					'name' => ($row['legal_business_fname'] == null || $row['legal_business_fname'] == '') ?$row['legal_business_name']: $row['legal_business_fname'].' '.$row['legal_business_lname'],
					'earnings' => (float)$earnings,
					'paid' => (float)$paid
				);			
			}	
		}
		return array('data'=>$arr_Affiliate, 'maxlength'=>(int) $maxlength);	
	}
	
	function parsedata(){
		$total_tbaffiliates = 0;
		$total_commission = 0;
		$paid = 0;
		$balance = 0;
		
		$re2 = $this->db->query("select representatives.uid from representatives join users on representatives.uid = users.uid where users.status <> -1");
		if($re2->num_rows() > 0){
			foreach($re2->result_array() as $row2){
				$total_tbaffiliates ++;
				$uid = $row2['uid'];
				$re = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = ".$uid);
				if($re->num_rows() > 0){
					foreach($re->result_array() as $row){
						$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1", 0);	
						$qty_buy = $row['quality'] - $qty_refund;
						if($qty_buy < 0) $qty_buy = 0;
						$itemprice = round($row["itemprice"]*$qty_buy, 2);
						$total_commission += round($row['commission'] * $itemprice / 100, 2);	
					}	
				}		
			}
		}
		$total_commission = round($total_commission, 2);
		$paid = $this->database->db_result("select sum(pay) from payments where role = ".Sale_Representatives, 0);
		$paid = round($paid, 2);
		$balance = $total_commission - $paid;
		
		$data = array(
			'total_affiliate' => $total_tbaffiliates,
			'total_commission' => number_format($total_commission, 2),
			'total_paid' => number_format($paid, 2),
			'balance' => $this->lib->showMoney($balance, 2),
			'load_month_current' => "var month_current = parseInt(".(gmdate('m')*1).", 10);",
			'load_year_current' => "var year_current = parseInt(".gmdate('Y').", 10);"
		);
		return $data;	
	}
}