<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charity_report_model extends CI_Model 
{
	var $arr_dataCharts = array();
	var $arr_years = array();
	var $min_Year = 0;
	var $sql_month = '';
	var $sql_year = '';
	var $sql_paymonth = '';
	var $sql_payyear = '';
	var $sql_raisemonth = '';
	var $sql_raiseyear = '';
	
	function loadPaid(){
		$re_3 = $this ->db ->query("select pay,pay_month as month_pay,pay_year as year_pay from payments where role = 8");
		foreach($re_3 ->result_array() as $row_3){
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
	
	function getCommissionCharities(){
		$re_2 = $this ->db ->query("select orders.orderid as oid,orders.status,orders.okey,commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8");
		foreach($re_2 ->result_array() as $row_2){
			$status_order = 3;
			$packages = $this->get_packages($row_2['okey']);
			$status_order = $this->getOrderStatus($row_2, $packages);
			if($status_order !=3 ) continue;
			$check_ = false;
			$query  = $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
			$_row = $query ->row_array();
			$qty_refund = (count($_row)>0)? $_row['sum_qty'] : 0;
			$qty_buy = $row_2['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$YTD_sales = round($row_2['itemprice']*$qty_buy, 2);
			$YTD_earnings = $row_2['commission'] * $YTD_sales / 100;
                      

			$purchase_date = strtotime($row_2['purchase_date']);
			$month_chart = (int)date("m", $purchase_date);
			$year_chart = (int)date("Y", $purchase_date);
			
			for($i = 0; $i < count($this->arr_dataCharts); $i++){
				if($this->arr_dataCharts[$i]['month'] == $month_chart && $this->arr_dataCharts[$i]['year'] == $year_chart){
					$this->arr_dataCharts[$i]['YTD_earnings'] += (float)($YTD_earnings);
					$check_ = true;
					break;	
				}	
			}
			if($check_ == false){
				$this->arr_dataCharts[] = array(
					'year' => $year_chart,
					'month' => $month_chart,
					'paid' => 0,
					'YTD_earnings' => (float)($YTD_earnings)
				);		
			}	
		}	
	}
	
	function getRaises(){
		$re_2 = $this ->db ->query("select MONTH(raises.date_raise) as month_chart,YEAR(raises.date_raise) as year_chart,raises.raise from raises join charities join users on raises.legal_business_id = charities.legal_business_id and charities.uid = users.uid where users.status = 1 and raises.role = 8");
		foreach($re_2 ->result_array() as $row_2){
			$YTD_earnings = round($row_2['raise'], 2);
			$month_chart = (int)$row_2['month_chart'];
			$year_chart = (int)$row_2['year_chart'];
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
	
	public function loadSalesChart(){
		$this->min_Year = $year = (int)date('Y');
		$this->loadPaid();
		$query_notrust =  $this->db->query("select count(charities.legal_business_id) as count_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.trust = 0");
		$row_notrust = $query_notrust->row_array();
		$maxlength_notrust = ($query_notrust->num_rows() >0) ? $row_notrust['count_id']:0;
		$this->getCommissionCharities();
		$this->getRaises();
		
		$sql = "select users.created,charities.uid,charities.legal_business_id,charities.legal_business_name from charities join users on charities.uid = users.uid where users.status <> -1";
		$re = $this ->db ->query($sql);
		foreach($re->result_array() as $row)
		{
			if($row['created'] != null && is_numeric($row['created'])){
				if($this->min_Year > (int)date("Y", $row['created'])) $this->min_Year = (int)date("Y",$row['created']);		
			}
			$re_2 = $this->db->query("select orderid,okey,order_date,status from orders ORDER BY orderid DESC");
			foreach($re_2->result_array() as $row_2){
				$status_order = $row_2['status'];
				$okey = $row_2['okey'];
				$check_manufacturer = false;
				$subtotal = 0;
				$tax = 0;
				
				$arr_manufacturers = array();
				$re_1 = $this->db->query("select order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,items.itm_key,items.uid,items.product_type,order_detais.tax_persend,order_detais.itemid from order_detais join items on order_detais.itemid = items.itm_id where items.uid = ".$row['uid']." and order_detais.orderid = ".$row_2['orderid']);
				foreach($re_1->result_array() as $row_1){
					$check_exist = false;
					for($m = 0; $m < count($arr_manufacturers); $m++){
						if($arr_manufacturers[$m]['uid'] == $row_1['uid']){
							$arr_manufacturers[$m]['items'][] = $row_1;
							$check_exist = true;
							break;	
						}	
					}
					if($check_exist == false){
						$arr_manufacturers[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
					}	
				}
				if(count($arr_manufacturers) == 0) continue;
				
				for($m = 0; $m < count($arr_manufacturers); $m++){//0
					foreach($arr_manufacturers[$m]['items'] as $row_1){//1
						$itemid = $row_1['itemid'];
						
						$cost = round($row_1["current_cost"] * $row_1["quality"], 2);
						$subtotal += $cost;
					}//1
				}//0
				//$tax = round($tax, 2);
				$amount_ = round($subtotal, 2);
				
				$order_date = strtotime($row_2['order_date']);
				$month_chart = (int)date("m", $order_date);
				$year_chart = (int)date("Y", $order_date);
				if($this->min_Year > $year_chart) $this->min_Year = $year_chart;
				$check_ = false;
				for($i = 0; $i < count($this->arr_dataCharts); $i++){
					if($this->arr_dataCharts[$i]['month'] == $month_chart && $this->arr_dataCharts[$i]['year'] == $year_chart){
						$this->arr_dataCharts[$i]['YTD_earnings'] += (float)$amount_;
						$check_ = true;
						break;	
					}	
				}
				if($check_ == false){
					$this->arr_dataCharts[] = array(
						'year' => $year_chart,
						'month' => $month_chart,
						'paid' => 0,
						'YTD_earnings' => (float)$amount_
					);		
				}	
			}
		}
	
		for($i = $this->min_Year; $i < $year+1; $i++){
			$arr_years[] = (int)$i;	
		}
		return array(
			'objYear' => $arr_years,
			'chart' => $this->arr_dataCharts
		);
	}//end loadSalesChart function
	
	function getOrderStatus($row_2, $packages){
		
		$status_order = $row_2['status'];
		$okey = $row_2['okey'];
		$check_manufacturer = false;
		$subtotal = 0;
		$tax = 0;

		$order_status_level = array();
		
		$arr_manufacturers = array();
		$re_1 = $this->db->query("select order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,items.itm_key,items.uid,items.product_type,order_detais.tax_persend,order_detais.itemid from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = ".$row_2['oid']);
		foreach($re_1->result_array() as $row_1){
			$check_exist = false;
			for($m = 0; $m < count($arr_manufacturers); $m++){
				if($arr_manufacturers[$m]['uid'] == $row_1['uid']){
					$arr_manufacturers[$m]['items'][] = $row_1;
					$check_exist = true;
					break;	
				}	
			}
			if($check_exist == false){
				$arr_manufacturers[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
			}	
		}
//		if(count($arr_manufacturers) == 0) continue;
		
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			foreach($arr_manufacturers[$m]['items'] as $row_1){//1
				$itemid = $row_1['itemid'];
				$qty_ship = 0;
				$qty_par = 0;
				if(count($packages) > 0){
					foreach($packages as $package){
						$items = $package['items'];
						for($i = 0; $i < count($items); $i++){
							if($items[$i]['product_id'] == $itemid){
								if($package['ship'] == 0){
									$qty_par += $items[$i]['qty'];
								}elseif($package['ship'] == 1){
									$qty_ship += $items[$i]['qty'];
                                                                        
								}
								break;	
							}	
						}	
					}	
				}
				$status_item = 1;
				if($row_1['product_type'] == 0){//!=1
					if($qty_ship == $row_1["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
				}else{
					$status_item = 3;
				}	
				$order_status_level[] = $status_item;
			}//1
		}//0
		$min_level = 3;
		$Canceled_status = 0;
		$Refunded_status = 0;
		if(count($order_status_level) > 0){
			foreach ($order_status_level as $level){
				if($level == 4) $Canceled_status = 1;
				elseif($level == 5) $Refunded_status = 1;
				elseif($level < 4){
					if($level < $min_level){
						$min_level = $level;
					}		
				}
			}
		}
		if($status_order == 4) $Canceled_status = 1;
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		return $min_level;
	}
	
	function get_packages($okey){
		$packages = array();
		$re_1 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '".$okey."'");
		foreach($re_1->result_array() as $row_3){
			$ship = 0;
			$re_4 = $this->db->query("select id from shipments where skey = '".$row_3['shipment_ID']."' and okey = '".$okey."'");
			if($re_4->num_rows() > 0){
				$ship = 1;	
			}
			
			$items = array();
			$re_4 = $this->db->query("select product_id,qty from packages_items where package_id = ".$row_3['id']);
			foreach($re_4->result_array() as $row_4){
				$items[] = $row_4;	
			}
			
			$packages[] = array(
				'ship' => $ship,
				'items' => $items
			);
                        
		}
		return $packages;	
	}
	
	function loadAffiliate(){
		$num_per_pager = 20;
		$page = (isset($_POST['page'])&&is_numeric($_POST['page'])&&$_POST['page']>0)?$_POST['page']:1;
		$limit = $num_per_pager*($page-1);
		
		$maxlength = $this->database->db_result("select count(charities.legal_business_id) as nuncharities from charities join users on charities.uid = users.uid where users.status = 1");
               
		$arr_Affiliate = array();
		$sql = "select charities.legal_business_id,charities.legal_business_name,charities.trust,website from charities join users on charities.uid = users.uid where users.status = 1 limit $limit,".$num_per_pager;
		$re = $this ->db ->query($sql);
		foreach($re->result_array() as $row){
			$legal_business_id = $row['legal_business_id'];
			$price = 0;
			$re_2 = $this ->db ->query("select orders.orderid as oid,orders.status,orders.okey,commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id = '$legal_business_id'");
			foreach($re_2 ->result_array() as $row_2){
				$status_order = 3;
				$packages = $this->get_packages($row_2['okey']);
				$status_order = $this->getOrderStatus($row_2, $packages);
				if($status_order != 3) continue;
				
				$query  = $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
				$_row = $query ->row_array();
				$qty_refund = (count($_row)>0)?$_row['sum_qty']:0;		
				$qty_buy = $row_2['quality'] - $qty_refund;
				if($qty_buy < 0) $qty_buy = 0;
				$YTD_sales = round($row_2['itemprice']*$qty_buy, 2);
				$price += $row_2['commission'] * $YTD_sales / 100;
			}
                    
			$query  = $this ->db ->query("select sum(raise) as sum_raise from raises where legal_business_id = '$legal_business_id' and role = 8");
			$_row = $query ->row_array();
			$temp_price = (count($_row)>0)?$_row['sum_raise']:0;	
			$price += $temp_price;
			
			$query  = $this ->db ->query("select sum(pay) as sum_pay from payments where legal_business_id = '$legal_business_id' and role = 8");
			$_row = $query ->row_array();
			$paid = (count($_row)>0)?$_row['sum_pay']:0;
                  
			$row['earnings'] = round($price + $this ->balance_from_order($legal_business_id), 2);//round($price + $this ->balance_from_order($legal_business_id), 2); before code
			$row['paid'] = round($paid, 2);
			$row['website'] = $this ->lib->buildUrlWS($row['website']);
			$arr_Affiliate[] = $row;
		}
		return array('data'=>$arr_Affiliate, 'maxlength'=>(int) $maxlength);
               
	}//end loadAffiliate function
	
	public function loaddata()
	{
		$total_tbaffiliates = 0;
		$total_commission = 0;
		$paid = 0;
		$export_perm = '';
		if($this->author->isAccessPerm('charities_report', 'exportCharities')){
			$export_perm = '<div style="float:right"><input type="submit" class="btn btn-primary" value="Export to Excel" onclick="return Export_to_Excel()"/></div>';		
		}
		
		$arr_charities = array();
		$query = $this ->db ->query("select legal_business_id from charities join users on charities.uid = users.uid where users.status = 1");
		foreach($query ->result_array() as $row){
			$arr_charities[] = $row['legal_business_id'];
			$total_tbaffiliates ++;
			$total_commission += $this ->balance_from_order($row['legal_business_id']);
		}
		
		$re = $this ->db ->query("select orders.orderid as oid, orders.status,orders.okey,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality,commission_charities.legal_business_id from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8");
		foreach($re->result_array() as $row){
			if(!in_array($row['legal_business_id'], $arr_charities)) continue;
			
			$status_order = 3;
			$packages = $this->get_packages($row['okey']);
			$status_order = $this->getOrderStatus($row, $packages);
			if($status_order !=3 ||  $row['oid'] == 13) continue;
			
			$query = $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row['id']." and status = 1");
			$_row = $query ->row_array();
			$qty_refund = $query->num_rows() > 0 ? $_row['sum_qty']:0;
			$qty_buy = $row['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$itemprice = round($row['itemprice']*$qty_buy, 2);
			$total_commission += $row['commission'] * $itemprice / 100;
		}
		
		$query = $this ->db ->query("select sum(raise) as sum_raise from raises where role = 8");
		$_row = $query ->row_array();
		$temp_raise = count($_row)> 0 ? $_row['sum_raise']:0;
		$total_commission += $temp_raise;
		$total_commission = round($total_commission, 2);
		$query = $this ->db ->query("select sum(pay) as sum_pay from payments where role = 8");
		$_row = $query ->row_array();
		$paid  = count($_row)> 0 ? $_row['sum_pay']:0;
		$paid = round($paid, 2);
		$balance = $total_commission - $paid;
                
		$data = array(
			'show_export_button' =>$export_perm,
			'total_affiliate'=>number_format($total_tbaffiliates),
			'total_commission'	=>number_format($total_commission, 2),
			'total_paid'=>number_format($paid, 2),
			'balance'=>$this ->lib ->showMoney($balance, 2),
			'load_month_current'=>"var month_current = parseInt(".(date('m')*1).", 10);",
			'load_year_current'=>"var year_current = parseInt(".date('Y').", 10);",
               
		);
		return $data;
	}//end loaddata function
	
	function details_loaddata($akey='')
	{
		$key = $this ->lib ->escape($akey);
              
		if($this ->author ->objlogin ->role['rid'] == 8 && $key=='')
		{
			$query = $this ->db ->query("select charities.legal_business_id from charities join users on charities.uid = users.uid where users.uid = ".$this ->author ->objlogin->uid);
			$row = $query ->row_array();
			if(count($row)>0) {
				$key = $row['legal_business_id'];	
			}
			unset($query);
			unset($row);
		}
		$payment_perm = '';
		$payment_perm_1= '';
		if($this ->author ->isAccessPerm('Charities_report','payment')){
			//$payment_perm = '<span style="float:left; padding-left:20px"><a href="index.php/report/charities_report/payment/'.$key.'" id="payment_form">Payment</a></span>';	
			$payment_perm_1 = "if(arr_data_chart[i].balance > 0){
								pay_btn = '<a id=\"pay_'+i+'\" href=\"'+url_server__+'report/charities_report/payment/'+charity_key+'/'+arr_data_chart[i].year+'/'+arr_data_chart[i].month+'\" class=\"btn btn-primary cboxElement\" style=\"font-size:10px; padding:3px 6px\">Pay</a>';	
							}";
		}
		if($this ->author ->isAccessPerm('Charities_report','raise')){
			$payment_perm = '<a class="btn btn-primary" style="margin-right:10px;" href="'.$this->system->URL_server__().'report/charities_report/raise/'.$key.'/8" id="raise_form">Raise</a>';			
		}
		$export_perm = '';
		if($this->author->isAccessPerm('charities_report', 'exportCharities'))
		{
			$export_perm = '<div style="float:right"><input type="submit" class="btn btn-primary" value="Export to Excel" onclick="return Export_to_Excel()"/></div>';		
		}		
		$Member_since = '';
		$last_login = '';
		$legal_business_name = '';
		$legal_business_id = '';
		$Address = '';
		$trust = 0;
		$re_1 = $this ->db ->query("select charities.trust,charities.legal_business_name,charities.legal_business_id,charities.address,charities.city,charities.state,charities.zipcode,users.created,users.login from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'");
		$row_1 = $re_1 ->row_array();
		if(count($row_1)>0){
			$Member_since = date("F j, Y", $row_1['created']);
			$last_login = date("F j, Y, g:i a", $row_1['login']);
			$legal_business_name = $row_1['legal_business_name'];
			$legal_business_id = $row_1['legal_business_id'];
			$Address = $row_1['address'].' '.$row_1['city'].', '.$row_1['state'].' '.$row_1['zipcode'];	
			$trust = $row_1['trust'];
		}
		$query = $this ->db ->query("select sum(pay) as sum_pay from payments where legal_business_id = '$key' and role = 8");
		$row = $query ->row_array();
		$paid = (count($row) >0) ? $row['sum_pay']:0;
           
                 $sql_charities = "select charities.uid,charities.trust,charities.legal_business_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$res_charities = $this->db->query($sql_charities);
		if($res_charities->num_rows() > 0){
			$row_charities = $res_charities->row_array(); 
			$res_countOrder = $this->db->query("select count(*) as total_order from orders join order_detais join items on orders.orderid = order_detais.orderid and order_detais.itemid = items.itm_id where items.uid = ".$row_charities['uid']." ORDER BY orders.orderid DESC ");
			$row_return = $res_countOrder->row_array();
			$total_order = $row_return['total_order'];
			$trust = $row_charities['trust'];
       	}  
               
		$total_commission = 0;
		$re_2 = $this ->db ->query("select orders.orderid as oid,orders.status,orders.okey,commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id = '$key'");
		foreach($re_2 ->result_array() as $row_2){
			$status_order = 3;
			$packages = $this->get_packages($row_2['okey']);
			$status_order = $this->getOrderStatus($row_2, $packages);
			if($status_order !=3 ) continue;
							
			//insert cod ehere 
			$query_ = $this ->db ->query("select sum(pay) as sum_pay from payments where role = 8");
			$row_ = $query_ ->row_array();
			$paid_= (count($row_) >0) ? $row_['sum_pay']:0;
			//end insert code here
							
			$query = $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
			$row = $query ->row_array();
			$qty_refund = (count($row) >0) ? $row['sum_qty']:0;
			$qty_buy = $row_2['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$YTD_sales = round($row_2['itemprice']*$qty_buy, 2);
			$commission__ = $row_2['commission'] * $YTD_sales / 100;
          
                        $total_commission += $commission__;
							
			//insert code here
			/*$temp_commission += $row_2['commission'] * $YTD_sales / 100;
			$temp_commission_ = $temp_commission - $paid_;
			$everycommisssion =  $temp_commission_;
			$total_commission = $everycommisssion + $paid;*/
						   
		}
		$total_commission += $this->balance_from_order($key);
              

		$query = $this ->db ->query("select sum(raise) as sum_raise from raises where legal_business_id = '$key' and role = 8");
		$row = $query ->row_array();
		$temp = (count($row) >0) ? $row['sum_raise']:0;
		$total_commission += $temp;
		$total_commission = round($total_commission, 2);
		$paid = round($paid, 2);
		$balance = $total_commission - $paid;
		$balance = round($balance, 2);
         
		$total_order = 0;
		$data = array(
			'Member_since' =>$Member_since,
			'last_login' =>$last_login,
			'legal_business_name' =>$legal_business_name,
			'legal_business_id' =>$legal_business_id,
			'Address' =>$Address,
			'total_commission' =>number_format($total_commission, 2),
			'total_paid' =>number_format($paid, 2),
			'balance' =>($balance >= 0)?'$'.number_format($balance, 2):'$0.00',
			'load_month_current' =>"var month_current = parseInt(".(date('m')*1).", 10);",
			'load_year_current' =>"var year_current = parseInt(".date('Y').", 10);",
			'payment_perm' =>$payment_perm,
			"if('permiss' == 'ok');" => $payment_perm_1,
			'show_export_button' =>$export_perm,
			'key' =>$key,
			'total_order' =>(int)$total_order,
			'trust'=>$trust  
		);
		return $data;
	}//end details_loaddata function
	
	function balance_from_order($key){
		$total_balance = 0;
		$arr_orders = array();
		$maxlength = 0;
		$sql = "select charities.uid,charities.legal_business_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$re = $this->db->query($sql);
		
		if($re->num_rows() > 0){
			$row = $re->row_array(); 	
			$max_sql = "select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name ";
			$max_sql .= "from orders join order_detais join items on orders.orderid = order_detais.orderid and order_detais.itemid = items.itm_id ";
			$max_sql .= "where items.uid = ".$row['uid']." ".$this->sql_year.$this->sql_month." ORDER BY orders.orderid DESC";
			$re_2 = $this->db->query($max_sql);
			foreach($re_2->result_array() as $row_2){
				$status_order = $row_2['status'];
				$okey = $row_2['okey'];
				//$tax_pecen	= $row_2["order_tax"];
				$tax = 0;
				$subtotal = 0;
				$order_status_level = array();

				$arr_manufacturers__ = array();
				$re_1 = $this->db->query("select order_detais.itemid,order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,order_detais.last_shipping,order_detais.tax_persend,items.itm_key,items.uid from order_detais join items on order_detais.itemid = items.itm_id where items.uid = ".$row['uid']." and order_detais.orderid = ".$row_2['orderid']);
				foreach($re_1->result_array() as $row_1){
					$check_exist = false;
					for($m = 0; $m < count($arr_manufacturers__); $m++){
						if($arr_manufacturers__[$m]['uid'] == $row_1['uid']){
							$arr_manufacturers__[$m]['items'][] = $row_1;
							$check_exist = true;
							break;	
						}	
					}
					if($check_exist == false){
						$arr_manufacturers__[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
					}	
				}
				if(count($arr_manufacturers__) == 0) continue;
				
				for($m = 0; $m < count($arr_manufacturers__); $m++){//0
					foreach($arr_manufacturers__[$m]['items'] as $row_1){//1
						$itemid = $row_1['itemid'];
						$amount = round($row_1["current_cost"] * $row_1["quality"], 2);
						$subtotal += $amount;
						$tax += (float)$row_1['tax_persend'] * $amount / 100;
					}//1
				}//0
				$tax = round($tax, 2);
				$row_2['paid'] = (float)$this->database->db_result("select sum(payments_orders.pay) from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 8 and payments.legal_business_id = '".$row['legal_business_id']."' and payments_orders.okey = '$okey'");

				$amount_ = round($subtotal + $tax, 2);
				$total_balance += (float)$amount_;
			}	
		}
		return $total_balance;
	}
	
	function load_check()
	{
		$arr = array();
		$akey = isset($_POST['akey']) ? $_POST['akey'] : '';
		$re = $this ->db ->query("select akey,checks_file from checks where akey = '$akey'");
		foreach($re->result_array() as $row)
		{
			$arr[count($arr)] = $row;	
		}
		return $arr;
	}//end load_check function
	
	function delete_checks()
	{
		$err = '';
		$file_name = isset($_POST['file_name'])?$_POST['file_name']:'';
		$akey = isset($_POST['akey']) ? $_POST['akey'] : '';
		$this ->db ->query("delete from checks where checks_file = '$file_name' and akey = '$akey'");
		if(is_file("data/checks/".$file_name)) unlink("data/checks/".$file_name);
		if(is_file("data/checks/thumb/".$file_name)) unlink("data/checks/thumb/".$file_name);
		return $err;	
	}//end delete_checks function

	function details_loadSalesChart()
	{
		$key = (isset($_POST['akey']) && $_POST['akey'] != '')?$_POST['akey']:'';
		$arr_years = array();
		$arr_dataCharts = array();
		$min_Year = $year = (int)date('Y');
		$trust = 0;
                $temp_pay = 0 ;//insert code here
                $temp_amount = 0 ;//insert code here
		$sql = "select users.created,charities.uid,charities.legal_business_id,charities.legal_business_name,charities.trust from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$re = $this ->db -> query($sql);
		foreach($re ->result_array() as $row)
		{
			$trust = $row['trust'];
			if($row['created'] != null && is_numeric($row['created'])){
				if($min_Year > (int)date("Y", $row['created'])) $min_Year = (int)date("Y", $row['created']);		
			}
			$re_3 = $this ->db ->query("select pay,pay_month as month_pay,pay_year as year_pay from payments where legal_business_id = '".$row['legal_business_id']."' and role = 8");
			foreach($re_3 ->result_array() as $row_3)
			{
                                $temp_pay  += $row_3['pay'];//insert code here 
				$month_chart = (int)$row_3['month_pay'];
				$year_chart = (int)$row_3['year_pay'];
				$check_ = false;
				for($i = 0; $i < count($arr_dataCharts); $i++){
					if($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart){
						$arr_dataCharts[$i]['paid'] += (float)$row_3['pay'];
						$check_ = true;
						break;	
					}	
				}
				if($check_ == false){
					$arr_dataCharts[] = array(
						'year' => $year_chart,
						'month' => $month_chart,
						'paid' => (float)$row_3['pay'],
						'YTD_earnings' => 0
					);		
				}	
			}
                  
			$re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,order_date,status from orders ORDER BY orderid DESC");
			foreach($re_2->result_array() as $row_2){
                        
				$status_order = $row_2['status'];
				$okey = $row_2['okey'];
				$check_manufacturer = false;
				$subtotal = 0;
				$tax = 0;
				
				$order_status_level = array();
				
				$arr_manufacturers = array();
				$re_1 = $this->db->query("select order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,order_detais.last_shipping,items.itm_key,items.uid,order_detais.tax_persend,order_detais.itemid from order_detais join items on order_detais.itemid = items.itm_id where items.uid = ".$row['uid']." and order_detais.orderid = ".$row_2['orderid']);
				foreach($re_1->result_array() as $row_1){
					$check_exist = false;
					for($m = 0; $m < count($arr_manufacturers); $m++){
						if($arr_manufacturers[$m]['uid'] == $row_1['uid']){
							$arr_manufacturers[$m]['items'][] = $row_1;
							$check_exist = true;
							break;	
						}	
					}
					if($check_exist == false){
						$arr_manufacturers[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
					}	
				}
				if(count($arr_manufacturers) == 0) continue;
				
				for($m = 0; $m < count($arr_manufacturers); $m++){//0
					
					foreach($arr_manufacturers[$m]['items'] as $row_1){//1
						$itemid = $row_1['itemid'];
						
						$cost = round($row_1["current_cost"] * $row_1["quality"], 2);
						$subtotal += $cost;
						$tax += round($row_1['tax_persend'] * $cost / 100, 2);	
					}//1
				}//0
				
				$tax = round($tax, 2);
				$amount_ = round($subtotal + $tax, 2);
                                $temp_amount += $amount_;//insert cod here
                              
                                
				$order_date = strtotime($row_2['order_date']);
				$month_chart = (int)date("m", $order_date);
				$year_chart = (int)date("Y", $order_date);
				if($min_Year > $year_chart) $min_Year = $year_chart;
				$check_ = false;
				for($i = 0; $i < count($arr_dataCharts); $i++){
					if($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart){
						$arr_dataCharts[$i]['YTD_earnings'] += (float)$amount_;
						$check_ = true;
						break;	
					}	
				}
				if($check_ == false){
					$arr_dataCharts[] = array(
						'year' => $year_chart,
						'month' => $month_chart,
						'paid' => 0,
						'YTD_earnings' => (float)$amount_                  
					);	
				}	
			}
		}
		
                $temp = 0; //insert code here 
		$re_2 = $this ->db ->query("select orders.orderid as oid,orders.status,orders.okey,MONTH(commission_charities.purchase_date) as month_chart,YEAR(commission_charities.purchase_date) as year_chart,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id = '$key'");
		foreach($re_2 ->result_array() as $row_2)
		{
			$status_order = 3;
			$packages = $this->get_packages($row_2['okey']);
			$status_order = $this->getOrderStatus($row_2, $packages);
			if($status_order !=3 ) continue;
			
			$check_ = false;
			$query =  $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
			$row = $query ->row_array();
			$qty_refund = (count($row) >0) ? $row['sum_qty']:0;
			$qty_buy = $row_2['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$YTD_sales = round($row_2['itemprice']*$qty_buy, 2);
                        $YTD_earnings = $row_2['commission'] * $YTD_sales / 100;
			
			$month_chart = (int)$row_2['month_chart'];
			$year_chart = (int)$row_2['year_chart'];
			for($i = 0; $i < count($arr_dataCharts); $i++){
				if($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart){
					$arr_dataCharts[$i]['YTD_earnings'] += (float)$YTD_earnings ;  
					$check_ = true;
					break;	
				}	
			}
			if($check_ == false){
				$arr_dataCharts[] = array(
					'year' => $year_chart,
					'month' => $month_chart,
					'paid' => 0,
					'YTD_earnings' => (float)$YTD_earnings
				);		
			}	
		}
			
		$re_2 = $this ->db ->query("select MONTH(date_raise) as month_chart,YEAR(date_raise) as year_chart,raise from raises where role = 8 and legal_business_id = '$key'");
		foreach($re_2 ->result_array() as $row_2)
		{
                   
			$check_ = false;
			$YTD_earnings = round($row_2['raise'], 2);
			$month_chart = (int)$row_2['month_chart'];
			$year_chart = (int)$row_2['year_chart'];
			for($i = 0; $i < count($arr_dataCharts); $i++){
				if($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart){
					$arr_dataCharts[$i]['YTD_earnings'] += (float)$YTD_earnings;
					$check_ = true;
					break;	
				}	
			}
			if($check_ == false){
				$arr_dataCharts[] = array(
					'year' => $year_chart,
					'month' => $month_chart,
					'paid' => 0,
					'YTD_earnings' => (float)$YTD_earnings
				);		
			}
		}
		for($i = $min_Year; $i < $year+1; $i++){
			$arr_years[] = (int)$i;	
		}
		return array(
			'objYear' => $arr_years,
			'chart' => $arr_dataCharts
		);
	}//end details_loadSalesChart function
	
	function check_valid(&$varible){
		if(isset($varible) && trim($varible) != ''){
			return $this->lib->escape($varible);	
		}
		return '';
	}
	
	function payment_loadData($key='',$year='',$month='')
	{	
		if($this->check_valid($year) != '' && is_numeric($this->check_valid($year))){
			$this->sql_year = " and YEAR(orders.order_date) = '".$year."'";
			$this->sql_payyear = " and pay_year = $year";
			$this->sql_raiseyear = " and YEAR(raises.date_raise ) = '".$year."'";
		}
		if($this->check_valid($month) != '' && is_numeric($this->check_valid($month))){
			$this->sql_month = " and MONTH(orders.order_date) = '".$month."'";
			$this->sql_paymonth = " and pay_month = $month";
			$this->sql_raisemonth = " and MONTH(raises.date_raise ) = '".$month."'";
		}
		$key = $this ->lib ->escape($key);
		$legal_business_name = '';
		$legal_business_id = '';
		$paid = 0;
		$total = 0;
		
		$sql = "select count(charities.legal_business_id) as count_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.trust = 0";
		$temp_query = $this ->db ->query($sql);
		$temp_row = $temp_query ->row_array();
		$maxlength = count($temp_row)>0 ? $temp_row['count_id']:0;
		$total_commission = 0;
		
		$re_2 = $this ->db ->query("select commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality,orders.status,orders.okey,orders.orderid as oid from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 ".$this->sql_year.$this->sql_month." and commission_charities.legal_business_id = '$key'");
		foreach($re_2 ->result_array() as $row_2)
		{
			$status_order = 3;
			$packages = $this->get_packages($row_2['okey']);
			$status_order = $this->getOrderStatus($row_2, $packages);
			if($status_order !=3 ) continue;
			
			$temp_query = $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
			$temp_row = $temp_query ->row_array();
			$qty_refund = count($temp_row)>0 ? $temp_row['sum_qty']:0;
			$qty_buy = $row_2['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$YTD_sales = round($row_2['itemprice']*$qty_buy, 4);
			$total_commission += round($row_2['commission'] * $YTD_sales / 100, 4);	
		}
		
		$temp_query = $this ->db ->query("select sum(raise) as sum_raise from raises where legal_business_id = '$key' ".$this->sql_raisemonth.$this->sql_raiseyear." and role = 8");
		$temp_row = $temp_query ->row_array();
		$temp_var = count($temp_row)>0 ? $temp_row['sum_raise']:0;
		$total_commission += $temp_var;
		$sql = "select charities.legal_business_id,charities.legal_business_name,charities.address,charities.city,charities.state,charities.zipcode from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row){
			$legal_business_name = $row['legal_business_name'];
			$legal_business_id = $row['legal_business_id'];
			$temp_query = $this ->db ->query("select sum(pay) as sum_pay from payments where legal_business_id = '$key' ".$this->sql_payyear.$this->sql_paymonth." and role = 8");
			$temp_row = $temp_query ->row_array();
			$paid = count($temp_row)>0 ? $temp_row['sum_pay']:0;
		}
		
		$total_commission += $this->balance_from_order($key);
		$total = $total_commission - $paid;
		if($total < 0) $total = 0;
		$total = round($total, 2);	
		
		$data = array(
			'date' =>date("F j, Y, g:i a"),
			'legal_business_name' =>$legal_business_name,
			'legal_business_id' =>$legal_business_id,
			'total' => number_format($total, 2),
			'@total@' =>$total,
			'total_paid' =>number_format($paid, 2),
			'total_commission' =>number_format($total_commission, 2),
			'key' =>$key,
			'pay_month' => $this->check_valid($month),
			'pay_year' => $this->check_valid($year)
		);
		return $data;
	}//end payment_loadData function
	
	function payment_saveObj()
	{
		$error = '';
		$pay = 0;
		$pay_key = '';
		$pay_month = ($this->input->post('pay_month') != '' && is_numeric($this->input->post('pay_month')))?$this->input->post('pay_month'):0;
		$pay_year = ($this->input->post('pay_year') != '' && is_numeric($this->input->post('pay_year')))?$this->input->post('pay_year'):0;
		if($pay_month != 0) $this->sql_paymonth = " and pay_month = $pay_month";
		if($pay_year != 0) $this->sql_payyear = " and pay_year = $pay_year";
		
		if(isset($_POST['key'])){
			$key = $_POST['key'];
			$pay_money = (isset($_POST['pay_money']) && is_numeric($_POST['pay_money']) && $_POST['pay_money'] > 0)?$_POST['pay_money']:0;
			$pay_key = $this ->lib ->GeneralRandomKey(20);
			$re_key = $this ->db ->query("select id from payments where pkey = '$pay_key'");
			foreach($re_key ->result_array() as $row_key)
			{
				$pay_key = $this ->lib ->GeneralRandomKey(20);
				$re_key = $this ->db ->query("select id from payments where pkey = '$pay_key'");
			}
			$datas = array(
				'pkey' => $pay_key,
				'check_number' => $this ->lib ->escape($_POST['check_number']),
				'role' => 8,
				'legal_business_id' => $key,
				'legal_business_name' =>  $this ->lib ->escape($_POST['legal_business_name']),
				'pay' => $pay_money,
				'date_pay' => gmdate("Y-m-d H:i:s"),
				'pay_month' => $pay_month,
				'pay_year' => $pay_year
			);
			$this ->db ->insert("payments", $datas);
			$id =  $this->db->insert_id() ;	
			if(!is_numeric($id)) $error = 'Can not save to database.';
			else{
				$query = $this ->db ->query("select sum(pay) as sum_pay from payments where legal_business_id = '$key' ".$this->sql_paymonth.$this->sql_payyear." and role = 8");
				$row = $query ->row_array();
				
				$pay = count($row)>0? $row['sum_pay']:0;	
			}
		}
		return array('error'=>$error, 'paid'=>$pay, 'pay_key'=>$pay_key);
	}//end payment_saveObj function
	
	function raise_loadData($key='',$role='')
	{
		$key =$this ->lib ->escape($key);
		$role = (is_numeric($role))?$role:0;
		$legal_business_name = '';
		
		switch($role){
			case 0:	//Network Management
				$legal_business_name = "Employees";
				break;
			case 4:	//Network Management
				
				break;
			case 5:	//Manufacturer
				
				break;
			case 6:	//Affiliates
				
				break;
			case 7:	//Content Provider
				
				break;
			case 8:	//Charities
				$sql = "select charities.legal_business_id,charities.legal_business_name from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
				$re = $this ->db ->query($sql);
				
				foreach($re->row_array() as $row)
				{
					$legal_business_name = $row['legal_business_name'];
					$legal_business_name .= '<br>ID# : '.$row['legal_business_id'];
				}
				break;
			case 9:	//Sale Representatives
				
				break;
			case 10: //Play List
				
				break;
			case 12:	//Acquisition Agent
				
				break;
		}
		$data = array(
			'key' =>$key,
			'role' =>$role,
			'date' =>date("F j, Y, g:i a"),
			'legal_business_name' =>$legal_business_name
		);
		return $data;
	}//end raise_loadData function
	
	function raise_saveObj()
	{
		$error = '';
		$pay = 0;
		$pay_key = '';
		if(isset($_POST['key'])){
			$key = $_POST['key'];
			$role = (isset($_POST['role']) && is_numeric($_POST['role']))?$_POST['role']:0;
			$pay_money = (isset($_POST['pay_money']) && is_numeric($_POST['pay_money']) && $_POST['pay_money'] > 0)?$_POST['pay_money']:0;
			$rkey = $this ->lib->GeneralRandomKey(20);
			$re_key = $this ->db ->query("select id from raises where rkey = '$rkey'");
			foreach($re_key ->result_array() as $row_key)
			{
				$rkey = $this ->lib->GeneralRandomKey(20);
				$re_key = $this ->db ->db_query("select id from raises where rkey = '$rkey'");
			}
			$datas = array(
				'rkey' => $rkey,
				'role' => $role,
				'legal_business_id' => $key,
				'raise' => $pay_money,
				'date_raise' => gmdate("Y-m-d H:i:s")
			);
			$this ->db ->insert("raises", $datas);	
			$id = $this ->db ->insert_id();
			if(!is_numeric($id)) $error = 'Can not save to database.';
		}
		return array('error'=>$error);
	}//end raise_saveObj function
	
	public function loadExcelData()
	{
        $total_tbaffiliates = 0;
		$total_commission = 0;
		$paid = 0;
		$query = $this ->db ->query("select count(charities.legal_business_id) as count_id from charities join users on charities.uid = users.uid where users.status <> -1");
		$_row = $query ->row_array();
		$total_tbaffiliates = count($_row)> 0 ? $_row['count_id']:0;

		$re = $this ->db ->query("select orders.orderid as oid,orders.status,orders.okey,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8");
		foreach($re->result_array() as $row)
		{
			$status_order = 3;
			$packages = $this->get_packages($row['okey']);
			$status_order = $this->getOrderStatus($row, $packages);
			if($status_order !=3 ) continue;
			
			$query = $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row['id']." and status = 1");
			$_row = $query ->row_array();
			$qty_refund = count($_row)> 0 ? $_row['sum_qty']:0;
			$qty_buy = $row['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$itemprice = round($row['itemprice']*$qty_buy, 2);
			$total_commission += round($row['commission'] * $itemprice / 100, 2);
		}
		
		$query = $this ->db ->query("select legal_business_id from charities join users on charities.uid = users.uid where users.status <> -1");
		foreach($query ->result_array() as $row){
			$total_commission += $this ->balance_from_order($row['legal_business_id']);
		}
		$query = $this ->db ->query("select sum(raise) as sum_raise from raises where role = 8");
		$_row = $query ->row_array();
		$temp_raise = count($_row)> 0 ? $_row['sum_raise']:0;
		$total_commission += $temp_raise;
		$total_commission = round($total_commission, 2);
		$query = $this ->db ->query("select sum(pay) as sum_pay from payments where role = 8");
		$_row = $query ->row_array();
		$paid  = count($_row)> 0 ? $_row['sum_pay']:0;
		$paid = round($paid, 2);
		$balance = $total_commission - $paid;
		if($balance < 0) $balance = 0;
		$data = array(
			'total_tbaffiliates'=>$total_tbaffiliates,
			'total_commission'=>$total_commission,
			'paid'=>$paid,
			'balance'=>$balance,
			'load_month_current'=>"var month_current = parseInt(".(date('m')*1).", 10);",
			'load_year_current'=>"var year_current = parseInt(".date('Y').", 10);", 
		);
		return $data;

	}//end loadExcelData function
	
	public function loadExcelDataCharity($akey='')
	{
		$key = $this ->lib ->escape($akey);
		
		if($this ->author ->objlogin ->role['rid'] == 8 && $key=='')
		{
			$query = $this ->db ->query("select charities.legal_business_id from charities join users on charities.uid = users.uid where users.uid = ".$this ->author ->objlogin->uid);
			$row = $query ->row_array();
			if(count($row)>0) {
				$key = $row['legal_business_id'];	
			}
			unset($query);
			unset($row);
		}
		$payment_perm = '';
		$payment_perm_1= '';
		if($this ->author ->isAccessPerm('Charities_report','payment')){
			//$payment_perm = '<span style="float:left; padding-left:20px"><a href="index.php/report/charities_report/payment/'.$key.'" id="payment_form">Payment</a></span>';	
			$payment_perm_1 = "if(arr_data_chart[i].balance > 0){
								pay_btn = '<a id=\"pay_'+i+'\" href=\"'+url_server__+'report/charities_report/payment/'+charity_key+'/'+arr_data_chart[i].year+'/'+arr_data_chart[i].month+'\" class=\"button cboxElement\" style=\"font-size:10px; padding:3px 6px\">Pay</a>';	
							}";
		}
		if($this ->author ->isAccessPerm('Charities_report','raise')){
			$payment_perm = '<a class="button" style="line-height:24px; padding:4px 20px; margin-right:10px;" href="index.php/report/charities_report/raise/'.$key.'/8" id="raise_form">Raise</a>';			
		}
		$export_perm = '';
		if($this->author->isAccessPerm('charities_report', 'exportCharities'))
		{
			$export_perm = '<div style="float:right"><input type="submit" class="button" value="Export to Excel" onclick="return Export_to_Excel()"/></div>';		
		}		
		$Member_since = '';
		$last_login = '';
		$legal_business_name = '';
		$legal_business_id = '';
		$Address = '';
		$trust = 0;
		$re_1 = $this ->db ->query("select charities.trust,charities.legal_business_name,charities.legal_business_id,charities.address,charities.city,charities.state,charities.zipcode,users.created,users.login from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'");
		$row_1 = $re_1 ->row_array();
		if(count($row_1)>0){
			$Member_since = date("F j, Y", $row_1['created']);
			$last_login = date("F j, Y, g:i a", $row_1['login']);
			$legal_business_name = $row_1['legal_business_name'];
			$legal_business_id = $row_1['legal_business_id'];
			$Address = $row_1['address'].' '.$row_1['city'].', '.$row_1['state'].' '.$row_1['zipcode'];	
			$trust = $row_1['trust'];
                    
		}
		$query = $this ->db ->query("select sum(pay) as sum_pay from payments where legal_business_id = '$key' and role = 8");
		$row = $query ->row_array();
		$paid = (count($row) >0) ? $row['sum_pay']:0;
		$query =  $this ->db ->query("select count(charities.legal_business_id) as count_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.trust = 0");
		$row = $query ->row_array();
		$maxlength = (count($row) >0) ? $row['count_id']:0;
		$total_commission = 0;
		
		$re_2 = $this ->db ->query("select orders.orderid as oid,orders.status,orders.okey,commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id = '$key'");
		foreach($re_2 ->result_array() as $row_2){
			$status_order = 3;
			$packages = $this->get_packages($row_2['okey']);
			$status_order = $this->getOrderStatus($row_2, $packages);
			if($status_order !=3 ) continue;
			
			$query = $this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
			$row = $query ->row_array();
			$qty_refund = (count($row) >0) ? $row['sum_qty']:0;
			$qty_buy = $row_2['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$YTD_sales = round($row_2['itemprice']*$qty_buy, 2);
			$commission__ = $row_2['commission'] * $YTD_sales / 100;
			$total_commission += round($commission__, 2);	
		}
		
      	$sql_charities = "select charities.uid,charities.trust,charities.legal_business_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$res_charities = $this->db->query($sql_charities);

		$total_order = 0;
		if($res_charities->num_rows() > 0){
			$row_charities = $res_charities->row_array(); 
			$sql_countOrder = $this->db->query("select count(*) as total_order from orders join order_detais join items on orders.orderid = order_detais.orderid and order_detais.itemid = items.itm_id where items.uid = ".$row_charities['uid']." ORDER BY orders.orderid DESC ");
			$row_return = $sql_countOrder->row_array();
			$total_order = $row_return['total_order'];
			$trust = $row_charities['trust'];
		}
          
		$total_commission += $this->balance_from_order($key);
		$query = $this ->db ->query("select sum(raise) as sum_raise from raises where legal_business_id = '$key' and role = 8");
		$row = $query ->row_array();
		$temp = (count($row) >0) ? $row['sum_raise']:0;
		$total_commission += $temp;
		$total_commission = round($total_commission, 2);
		$paid = round($paid, 2);
		$balance = $total_commission - $paid;
		$balance = round($balance, 2); 
		$data = array(
			'Member_since' =>$Member_since,
			'last_login' =>$last_login,
			'legal_business_name' =>$legal_business_name,
			'legal_business_id' =>$legal_business_id,
			'Address' =>$Address,
			'total_commission' =>number_format($total_commission, 2),
			'paid' =>number_format($paid, 2),
			'balance' =>($balance >= 0)?'$'.number_format($balance, 2):'$0.00',
			'total_order'=>(int)$total_order,
			'trust' =>$trust    
		);
		return $data;
               
	}//end loadExcelDataCharity funciton
	
	function loadOrders(){
		$__order_status__ = $this->config->item('__order_status__');
		$page = (isset($_POST['page'])&&is_numeric($_POST['page'])&&$_POST['page']>0)?$_POST['page']:1;
		$key = (isset($_POST['akey']) && $_POST['akey'] != '')?$_POST['akey']:'';
               
		$pay = 'no';
		if($this->author->isAccessPerm('Charities_report','payment')){
			$pay = 'yes';
		}
		$status_sql = '';
		if(isset($_POST['status_order']) && $_POST['status_order'] != ''){
			$status_sql = $_POST['status_order'];		
		}
		$month_sql = '';
		if(isset($_POST['month']) && is_numeric($_POST['month'])){
			$month_sql = " and MONTH(orders.order_date) = '".$_POST['month']."'";
		}
		$year_sql = '';
		if(isset($_POST['year']) && is_numeric($_POST['year'])){
			$year_sql = " and YEAR(orders.order_date) = '".$_POST['year']."'";
		}
		$status_paid_sql = '';
		if(isset($_POST['status_paid']) && $_POST['status_paid'] != ''){
			$status_paid_sql = $_POST['status_paid'];
		}
		$arr_orders = array();
		$maxlength = 0;
		$sql = "select charities.uid,charities.legal_business_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$re = $this->db->query($sql);
		
		if($re->num_rows() > 0){
			$row = $re->row_array(); 
                
			$max_sql = "select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name ";
			$max_sql .= "from orders join order_detais join items on orders.orderid = order_detais.orderid and order_detais.itemid = items.itm_id ";
			$max_sql .= "where items.uid = ".$row['uid']." $month_sql  $year_sql ORDER BY orders.orderid DESC ";
			$re_2 = $this->db->query($max_sql);
			foreach($re_2->result_array() as $row_2){
            
				$status_order = $row_2['status'];
				$okey = $row_2['okey'];
				//$tax_pecen	= $row_2["order_tax"];
				$tax = 0;
				$base_price	= $row_2["shipping_fee"];
				$shipping_fee = 0;
				$subtotal = 0;
				$packages = array();
				$order_status_level = array();
				$re_3 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
				foreach($re_3->result_array() as $row_3){
					$ship = 0;
					$re_4 = $this->db->query("select id from shipments where skey = '".$row_3['shipment_ID']."' and okey = '$okey'");
					if($re_4->num_rows() > 0) {
						$ship = 1;	
					}
					$items = array();
					$re_4 = $this->db->query("select product_id,qty from packages_items where package_id = ".$row_3['id']);
					foreach($re_4->result_array() as $row_4){
						$items[] = $row_4;	
					}
					$packages[] = array(
						'ship' => $ship,
						'items' => $items
					);	
				}
				$arr_manufacturers__ = array();
				$re_1 = $this->db->query("select order_detais.itemid,order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,order_detais.last_shipping,order_detais.tax_persend,items.itm_key,items.uid from order_detais join items on order_detais.itemid = items.itm_id where items.uid = ".$row['uid']." and order_detais.orderid = ".$row_2['orderid']);
				foreach($re_1->result_array() as $row_1){
					$check_exist = false;
					for($m = 0; $m < count($arr_manufacturers__); $m++){
						if($arr_manufacturers__[$m]['uid'] == $row_1['uid']){
							$arr_manufacturers__[$m]['items'][] = $row_1;
							$check_exist = true;
							break;	
						}	
					}
					if($check_exist == false){
						$arr_manufacturers__[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
					}	
				}
				if(count($arr_manufacturers__) == 0) continue;
				$arr_orders_handling = array();
				$re_1 = $this->db->query("select * from orders_handling where oid = ".$row_2['orderid']);
				foreach($re_1->result_array() as $row_1){
					$arr_orders_handling[] = $row_1;	
				}
				for($m = 0; $m < count($arr_manufacturers__); $m++){//0
					$handling_fee_new = $base_price;
					foreach($arr_orders_handling as $oh){
						if($oh['uid'] == $arr_manufacturers__[$m]['uid']){
							$handling_fee_new = $oh['handling'];
							break;	
						}		
					}
					$shipping_rate = $handling_fee_new;
					foreach($arr_manufacturers__[$m]['items'] as $row_1){//1
						$itemid = $row_1['itemid'];
						$qty_ship = 0;
						$qty_par = 0;
						if(count($packages) > 0){
							foreach($packages as $package){
								$items = $package['items'];
								for($i = 0; $i < count($items); $i++){
									if($items[$i]['product_id'] == $itemid){
										if($package['ship'] == 0){
											$qty_par += $items[$i]['qty'];
										}elseif($package['ship'] == 1){
											$qty_ship += $items[$i]['qty'];
										}
										break;	
									}	
								}	
							}	
						}
						$status_item = 1;
						if($qty_ship == $row_1["quality"]){
							$status_item = 3;	
						}elseif($qty_par > 0 || $qty_ship > 0){
							$status_item = 2;		
						}
						$order_status_level[] = $status_item;
						$shipping_rate += round($row_1['last_shipping'] * $row_1["quality"], 2);	
						$amount = round($row_1["current_cost"] * $row_1["quality"], 2);
						$subtotal += $amount;
						$tax += (float)$row_1['tax_persend'] * $amount / 100;
					}//1
					$shipping_fee += round($shipping_rate, 2);
				}//0
				$tax = round($tax, 2);
				$min_level = 3;
				$Canceled_status = 0;
				$Refunded_status = 0;
				if(count($order_status_level) > 0){
					foreach ($order_status_level as $level){
						if($level == 4) $Canceled_status = 1;
						elseif($level == 5) $Refunded_status = 1;
						elseif($level < 4){
							if($level < $min_level){
								$min_level = $level;
							}		
						}
					}
				}
				if($status_order == 4) $Canceled_status = 1;
				if($Canceled_status == 1) $min_level = 4;
				elseif($Refunded_status == 1) $min_level = 5;
				if($status_sql != '' && $status_sql != $min_level) continue;
				
				$shipping_fee = round($shipping_fee, 2);
				$row_2['paid'] = (float)$this->database->db_result("select sum(payments_orders.pay) from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 8 and payments.legal_business_id = '".$row['legal_business_id']."' ");
							
				$amount_ = round($subtotal, 2);
				$total_refund = 0;
				if($status_order == 4){//Cancel
					$total_refund = $amount_;
					$amount_ = 0;
				}
				
				if($status_paid_sql != ''){
					$balance = $amount_ - $row_2['paid'];
					if($status_paid_sql == 0 && $balance <= 0) continue;
					elseif($status_paid_sql == 1 && $row_2['paid'] <= 0) continue;	
				}
				
				$row_2['order_total'] = (float)$amount_;
				$row_2['subtotal'] = (float)$subtotal;
				$row_2['tax'] = (float)$tax;
				$row_2['shipping_fee'] = (float)$shipping_fee;
				$row_2['refund'] = (float)$total_refund;
				$row_2['status_format'] = isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null';
				$row_2['status'] = $min_level;
				$row_2['order_date_format'] = date("m/d/Y", strtotime($row_2['order_date']));
				$arr_orders[] = $row_2;
      
			}	
		}
		return array('maxlength' => (int) $maxlength, 'data' => $arr_orders);
	}
	
}//end Charities class
