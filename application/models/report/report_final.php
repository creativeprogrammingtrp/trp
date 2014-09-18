<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_final extends CI_Model {
	
	var $oid = 0;
	var $okey = '';
	
	var $arr_manufacturers = array();
	var $arrPromotions = array();
	var $arr_orders_handling = array();
	var $ItemDetails = array();
	var $order_status_level = array();
	var $item_type = 0; 	//1:donation 
	
	var $sql_customer = '';
	var $key_word_sql = '';
	var $status_sql = '';
	var $month_sql = '';
	var $year_sql = '';
	var $sql_uid_clients = '';
	var $sql_manufacturer = '';
	var $sql_promotion = '';
	
	var $sale_rep_setting = array();
	var $dataChart = array();
	
	//View List
	public function ViewList($page,$colum,$sortby){
		return json_encode($this->load_order($page,$colum,$sortby));
	}
	function setOkey($okey){
		$this->okey = $okey;	
	}
	function check_valid(&$varible){
		if(isset($varible) && trim($varible) != ''){
			return $this->lib->escape($varible);	
		}
		return '';
	}
	
	function loadSale_rep_setting(){
		$this->sale_rep_setting = $this->system->get_sysvals('sale_rep_setting', array());	
	}
	
	function set_sql_customer($ukey){
		if($this->check_valid($ukey) != ''){
			$uid = $this->database->db_result("select uid from users where ukey = '$ukey'");
			if(is_numeric($uid) && $uid > 0){
				$this->sql_customer = ' and orders.user_id = '.$uid;	
			}
		}	
	}
	
	function set_key_word_sql($key_word){
		if($this->check_valid($key_word) != ''){
			$key_word_ = '';
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_ .= " and (";
						$key_word_ .= " orders.okey like '%$key%'";
						$key_word_ .= " or orders.shipping_name like '%$key%'";
						$key_word_ .= " or orders.shipping_address like '%$key%'";
						$key_word_ .= " or orders.shipping_city like '%$key%'";
						$key_word_ .= " or orders.shipping_state like '%$key%'";
						$key_word_ .= " or orders.shipping_zip like '%$key%'";
						$key_word_ .= " or orders.shipping_phone like '%$key%'";
						$key_word_ .= " or orders.billing_name like '%$key%'";
						$key_word_ .= " or orders.billing_address like '%$key%'";
						$key_word_ .= " or orders.billing_city like '%$key%'";
						$key_word_ .= " or orders.billing_state like '%$key%'";
						$key_word_ .= " or orders.billing_zip like '%$key%'";
						$key_word_ .= " or orders.billing_phone like '%$key%'";
						$key_word_ .= " or orders.billing_email like '%$key%'";
						$key_word_ .= " ) ";	
					}
				}
				$this->key_word_sql = $key_word_;	
			}
		}	
	}
	function set_status_sql($status){
		if($this->check_valid($status) != '' && is_numeric($this->check_valid($status))){
			$this->status_sql = $status; //;" and orders.status = '".$status."'";		
		}	
	}
	function set_month_sql($month){
		if($this->check_valid($month) != '' && is_numeric($this->check_valid($month))){
			$this->month_sql = " and MONTH(orders.order_date) = '".$month."'";
		}
	}
	function set_year_sql($year){
		if($this->check_valid($year) != '' && is_numeric($this->check_valid($year))){
			$this->year_sql = " and YEAR(orders.order_date) = '".$year."'";
		}
	}
	function set_sql_uid_clients(){
		$this->sql_uid_clients = " AND orders.user_id = ".$this->author->objlogin->uid;
	}
	function set_sql_manufacturer(){
		$ong_chu = $this->lib->__loadBoss__();
		$this->sql_manufacturer = " and items.uid = ".$ong_chu;
		$this->sql_promotion = " and manufacturer_id = ".$ong_chu;		
	}
	function load_order($page, $col, $sortby_){
		
		$this->loadSale_rep_setting();
		
		$roles = $this->author->objlogin->role;
		
		$num_per_pager = NUMROWPERPAGE;
		$limit = $num_per_pager*($page-1);
		
		$__order_status__ = $this->config->item('__order_status__');
		$__refund_reason__ = $this->config->item('__refund_reason__');
		
		if($roles['rid'] == MANUFACTURER){
			$this->set_sql_manufacturer();
		}elseif($roles['rid'] != ADMINISTRATOR && $roles['rid'] != NWMANAGEMENT){
			$this->set_sql_uid_clients();
		}
		
		$sort_type = '';
		$sort_by = 'desc';
		
		if($sortby_ == 0) $sort_by = 'asc';
		
		switch($col){
			case 0:
				$sort_type = 'A.okey';	
				break;
			case 1:
				$sort_type = 'A.status';	
				break;
			case 2:
				$sort_type = 'A.order_date';	
				break;
		}
		
		$order_auto = array();
		$re_auto = $this->db->query("select distinct oid from orders_auto_delivery");
		
		foreach($re_auto->result_array() as $key => $row){
			$order_auto[] = $row;
		}
                
		//Error here
		//$sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name,orders.r_ordernum from orders where orders.orderid NOT IN('".$order_auto."') and orders.status <> -1 " . $this->sql_uid_clients.$this->month_sql.$this->year_sql.$this->sql_customer.$this->key_word_sql." ) as A";
                
                $sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name,orders.r_ordernum from orders left join orders_auto_delivery on orders.orderid =orders_auto_delivery.oid  where orders.status <> -1 " . $this->sql_uid_clients.$this->month_sql.$this->year_sql.$this->sql_customer.$this->key_word_sql." ) as A"; //Fix error here 
		
		//$sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name,orders.r_ordernum from orders where orders.orderid NOT IN(select distinct oid from orders_auto_delivery) and orders.status <> -1 " . $this->sql_uid_clients.$this->month_sql.$this->year_sql.$this->sql_customer.$this->key_word_sql." ) as A";
		
		$sql_2 = " (select distinct order_detais.orderid from order_detais join items on order_detais.itemid = items.itm_id where 1=1 ".$this->sql_manufacturer.") as B";
		
		$max_sql = "select count(A.orderid) ";
		$max_sql .= " from ".$sql_1." join ".$sql_2." on A.orderid = B.orderid ";
                
		$maxlength = $this->database->db_result($max_sql);
             
		$max_sql = "select * ";
		$max_sql .= " from ".$sql_1." join ".$sql_2." on A.orderid = B.orderid ";
		$max_sql .= " ORDER BY $sort_type $sort_by limit $limit,".$num_per_pager;
		
		$data = array();
		$query = $this->db->query($max_sql);
		foreach($query->result_array() as $row){
			$okey = $row['okey'];
			$this->oid = $this->database->db_result("select orderid from orders where okey = $okey");
			
			$order = new order_detail($row, $this->sale_rep_setting);
			$order->calculate_total();
			$min_level = $order->min_level;
                        
			if($this->status_sql != -1)
				if($min_level != $this->status_sql) continue;
			
			$charities_commission = 0;
			$vendor = 0;
			$employee = 0;
			$merchant_cost = 0;
			$member = 0;
			$subtotal = $order->subtotal;
			$tax = $order->tax;
			$shipping_fee = $order->shipping_fee;
			
			$order_total = (float)($subtotal + $tax + $shipping_fee);
			$grossfit = 0;
			if($min_level >= 3){
				$charities_commission = $order->getCharityCommission();
				$vendor = $order->getVenderCommission();
				$employee = $order->getEmployCommission();
				$merchant_cost = round($order->merchant_cost,2);
				$member = $order->getMemberCommission();
				$grossfit = $order_total - ($charities_commission + $vendor + $employee + $member + $merchant_cost);
			}
			$data[] = array(
				'okey'=>$okey,
				'order_date' => gmdate("m/d/Y", strtotime($row['order_date'])),
				'order_date_format'=>gmdate("m/d/Y", $order->order_date_int),
				'status' => (int)$min_level,
				'status_format' => isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
				'subtotal' => $subtotal,
				'order_tax' => $tax,
				'shipping_fee' => $shipping_fee,
				'order_total' => $order_total,
				//'refund' => $order_refund,
				'charities_commission' => $charities_commission,
				'vendor' => $vendor,
				'employee' => $employee,
				'merchant_cost' => $merchant_cost,
				'member' => $member,
				'grossfit' => $grossfit
				//'cancel' => $cancel_action
			);
		}
		return array('data'=>$data, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page, 'rid'=>(int)$roles['rid']);
	}
	
	public function loadAmountRefund2($refundID,$rid=ADMINISTRATOR,$uid){
		$okey = '';
		$re = $this->db->query("select * from orders_return where id = $refundID");
		if($row = $re->row_array()){
			$okey = $row['okey'];
			$re_1 = $this->db->query("select * from odetail_return where rid = $refundID order by id ASC");
			foreach($re_1->result_array() as $row_1){
				$dataRefund[] = $row_1;	
			}	
		}
		$subtotal = 0;
		$total = 0;
		$tax = 0;
		$shipping_fee = 0;
		$Shipping_Discounts = 0;
		$oid = 0;
		$tax_pecen = 0;
		$base_price = 0;
		$order_date = 0;
		$total_price_buy = 0;
		$r = $this->db->query("SELECT orderid,shipping_fee,order_tax FROM orders WHERE okey = '$okey'");
		if($row = $r->row_array()){
			$oid 				= $row['orderid'];
			$tax_pecen			= $row["order_tax"];
		}
		$total_ship_buy = 0;
		$total_ship_refund = 0;
		
		$sql_manufacturer = '';
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		if($rid == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$uid;
			$sql_orders_promotions .= " and manufacturer_id = ".$uid;
		}
		$arrPromotions = array();
		$re = $this->db->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		
		$order_detais = array();
		$strSql = "SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.duration_refund,items.duration_type_refund,items.charge_refund,items.charge_refund_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC";
		$r = $this->db->query($strSql);
		foreach($r->result_array() as $row){
			$max_qty = $row['quality'];
			$re_2 = $this->db->query("select qty from odetail_return where odetail = '".$row['id']."' and status = 1");
			foreach($re_2->result_array() as $row_2){
				$max_qty -= $row_2['qty'];	
			}
			if($max_qty < 0) $max_qty = 0;
			$qty_buy = $row['quality'] = $max_qty;
			$qty_refund = 0;
			for($r_ = 0; $r_ < count($dataRefund); $r_++){
				if($dataRefund[$r_]['odetail'] == $row['id']){
					if($dataRefund[$r_]['status'] == 1){
						$qty_buy = $row['quality'] = $dataRefund[$r_]['max_qty'];
					}
					$qty_refund = ($dataRefund[$r_]['qty'] > $qty_buy)?$qty_buy:$dataRefund[$r_]['qty'];
					$qty_buy -= $qty_refund;
					break;	
				}	
			}
			$row['qty_buy'] = $qty_buy;
			$row['qty_refund'] = $qty_refund;
			$order_detais[] = $row;	
		}
		$count = 0;
		$count_shipping_free = 0;
		$count_shipping_free_2 = 0;
		foreach($order_detais as $row){
			$count ++;
			$itemid = $row["itemid"];
			$odetail = $row['id'];
			
			$itemprice_buy = $itemprice_old = $last_itemprice = $row['last_itemprice'];
			if($rid == MANUFACTURER){
				$itemprice_buy = $itemprice_old = $last_itemprice = $row['last_cost'];	
			}
			$ship_per_item_old = $last_shipping = $row['last_shipping'] * $row['quality'];
			$ship_buy = $ship_buy_last = $row["qty_buy"] * $row['last_shipping'];
			$ship_refund = $row["qty_refund"] * $row['last_shipping'];	
			
			$check_shipping_free = false;
			$check_shipping_free_2 = false;
			$arr_show_promotions = array();
			$free_product_row = '';
			if(count($arrPromotions) > 0){
				foreach($arrPromotions as $promotions){
					if($promotions['itm_key'] == $row['itm_key']){//0
						switch($promotions['promo_type']){
							case 1:
								for($r_ = 0; $r_ < count($order_detais); $r_++){
									if($order_detais[$r_]['itm_key'] == $promotions['product_key']){
										if($order_detais[$r_]['quality'] >= $promotions['minqty']){
											if($promotions['discount_type'] == 0){
												$itemprice_old -= $last_itemprice * $promotions['discount'] / 100;
											}else{
												$itemprice_old -= $promotions['discount'];
											}
											if($order_detais[$r_]['qty_buy'] >= $promotions['minqty']){
												if($promotions['discount_type'] == 0){
													$itemprice_buy -= $last_itemprice * $promotions['discount'] / 100;
												}else{
													$itemprice_buy -= $promotions['discount'];
												}	
											}	
										}
										break;	
									}	
								}
								break;
							case 3:
								for($r_ = 0; $r_ < count($order_detais); $r_++){
									if($order_detais[$r_]['itm_key'] == $promotions['product_key']){
										if($order_detais[$r_]['quality'] >= $promotions['minqty']){
											$check_shipping_free = true;
											if($promotions['discount_type'] == 0){
												$ship_per_item_old -= $last_shipping * $promotions['discount'] / 100;
											}else{
												$ship_per_item_old -= $promotions['discount'];
											}
											if($order_detais[$r_]['qty_buy'] >= $promotions['minqty']){
												if($promotions['discount_type'] == 0){
													$ship_buy -= $ship_buy_last * $promotions['discount'] / 100;
												}else{
													$ship_buy -= $promotions['discount'];	
												}
												$check_shipping_free_2 = true;	
											}		
										}
										break;	
									}	
								}
								break;	
							case 4:
								for($r_ = 0; $r_ < count($order_detais); $r_++){
									if($order_detais[$r_]['itm_key'] == $promotions['product_key']){
										if($order_detais[$r_]['quality'] >= $promotions['minqty']){
											$check_shipping_free = true;
											$ship_per_item_old = 0;
											if($order_detais[$r_]['qty_buy'] >= $promotions['minqty']){
												$ship_buy = 0;
												$check_shipping_free_2 = true;	
											}	
										}
										break;	
									}	
								}
								break;		
						}
					}//0
				}
			}
			if($ship_per_item_old <= 0){
				$ship_per_item_old = 0;
				if($check_shipping_free == true) $count_shipping_free ++;	
			}
			if($ship_buy <= 0){
				$ship_buy = 0;
				if($check_shipping_free_2 == true) $count_shipping_free_2 ++;	
			}
			$shipping_fee += $ship_per_item_old;
			$total_ship_buy += $ship_buy;
			$total_ship_refund += $ship_refund;
			
			if($itemprice_old < 0) $itemprice_old = 0;
			$itemprice_old = round($itemprice_old, 2);
			$price_old = round($itemprice_old * $row['quality'], 2);
			$total += $price_old;
			
			if($itemprice_buy < 0) $itemprice_buy = 0;
			$itemprice_buy = round($itemprice_buy, 2);
			$price_buy = round($row["qty_buy"] * $itemprice_buy, 2);
			$total_price_buy += $price_buy;
			$total_price = $price_old - $price_buy;
			$subtotal += $total_price;
		}
		$tax_old = round($tax_pecen * $total / 100, 2);
		$tax_buy = round($tax_pecen * $total_price_buy / 100, 2);
		$tax_return = $tax_old - $tax_buy; 
		
		$Shipping_Discounts = round($total_ship_buy + $total_ship_refund, 2) - round($shipping_fee, 2);
		if($Shipping_Discounts != 0){
			$subtotal -= $Shipping_Discounts;	
		}
		$subtotal += $tax_return;
		if($subtotal < 0) $subtotal = 0;
		return $subtotal;
	}
	
	function load_data_chart(){
		$year = $this->input->post('year')?$this->input->post('year'):(int)date("Y");
		$type = $this->input->post('type')?$this->input->post('type'):0;
		
		$this->loadSale_rep_setting();
		$roles = $this->author->objlogin->role;
		
		$__order_status__ = $this->config->item('__order_status__');
		$__refund_reason__ = $this->config->item('__refund_reason__');
		
		if($roles['rid'] == MANUFACTURER){
			$this->set_sql_manufacturer();
		}elseif($roles['rid'] != ADMINISTRATOR && $roles['rid'] != NWMANAGEMENT){
			$this->set_sql_uid_clients();
		}
		
		$order_auto = array();
		$re_auto = $this->db->query("select distinct oid from orders_auto_delivery");
		
		foreach($re_auto->result_array() as $key => $row){
			$order_auto[] = $row;
		}
		
		$sql_type = '';
		if($type == 0) $sql_type = " and YEAR(orders.order_date) = '".$year."'";
		if($type == 1) $sql_type = " and YEAR(orders.order_date) <= '".$year."' AND YEAR(orders.order_date) >= '".($year - 11)."'";
		
		$str_auto = "(0";
		for ($i = 0; $i<count($order_auto);$i++)
		{
			$str_auto .= ",".$order_auto[$i]['oid'];
		}
		$str_auto .= ")";
		$sql_1 = " (select distinct orders.orderid,orders.okey, MONTH(orders.order_date) as month,YEAR(orders.order_date) as year,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name,orders.r_ordernum from orders where orders.orderid NOT IN('".$str_auto."') and orders.status <> -1 " .$sql_type." ) as A";
		$sql_2 = " (select distinct order_detais.orderid from order_detais join items on order_detais.itemid = items.itm_id where 1=1 ".$this->sql_manufacturer.") as B";
		
		$max_sql = "select * ";
		$max_sql .= " from ".$sql_1." join ".$sql_2." on A.orderid = B.orderid ";
		$data = array();
		$query = $this->db->query($max_sql);
		foreach($query->result_array() as $row){
			$okey = $row['okey'];
			$this->oid = $this->database->db_result("select orderid from orders where okey = $okey");
			$order = new order_detail($row, $this->sale_rep_setting);
			$order->calculate_total();
			$min_level = $order->min_level;
			$charities_commission = 0;
			$vendor = 0;
			$employee = 0;
			$member = 0;
			$subtotal = $order->subtotal;
			$tax = $order->tax;
			$shipping_fee = $order->shipping_fee;
			$merchant_cost = 0;
			$order_total = 0;
			$grossfit = 0;
     
			if($min_level >= 3){
				$charities_commission = $order->getCharityCommission();
				$vendor = $order->getVenderCommission();
				$employee = $order->getEmployCommission();
				$merchant_cost = round($order->merchant_cost,2);
				$member = $order->getMemberCommission();
				$order_total = (float)($subtotal + $tax + $shipping_fee);
				$grossfit = $order_total - ($charities_commission + $vendor + $employee + $member + $merchant_cost);
			}
			$check = false;
			for($i = 0; $i < count($this->dataChart); $i++){
				if($this->dataChart[$i]['month'] == $row['month'] && $this->dataChart[$i]['year'] == $row['year']){
					$check = true;
					$this->dataChart[$i]['year'] = $row['year'];
					$this->dataChart[$i]['month'] = $row['month'];
					$this->dataChart[$i]['total'] += $order_total;
					$this->dataChart[$i]['charities_commission'] += $charities_commission;
					$this->dataChart[$i]['vendor'] += $vendor;
					$this->dataChart[$i]['employee'] += $employee;
					$this->dataChart[$i]['merchant_cost'] += $merchant_cost;
					$this->dataChart[$i]['member'] += $member;
					$this->dataChart[$i]['grossfit'] += $grossfit;
					break;
				}
			}
			if(!$check){
				$this->dataChart[] = array(
					'year' => $row['year'],
					'month' => $row['month'],
					'total' => $order_total,
					'charities_commission' => $charities_commission,
					'vendor' => $vendor,
					'employee' => $employee,
					'merchant_cost' => $merchant_cost,
					'member' => $member,
					'grossfit' => $grossfit
				);	
			}
		}
		return array('dataChart'=>$this->dataChart);
	}
}

class order_detail extends Report_final{
	var $order_detail = array();
	var $oid = 0;
	var $okey = '';
	
	var $subtotal = 0;
	var $tax = 0;
	var $shipping_fee = 0;
	var $itm_merchant_cost_percent = 0;
	var $merchant_cost = 0;
	var $min_level = 0;
	var $order_date_int = 0;
	
	var $sale_rep_setting = array();
	
	function __construct($order_detail, $sale_rep_setting){
		$this->order_detail = $order_detail;
		$this->sale_rep_setting = $sale_rep_setting;
		
		$this->oid = (int)$this->order_detail['orderid'];
		$this->okey = $this->order_detail['okey'];
	}
	
	function getItmMerchantCostPercent($itemid){
		$percent = 0;
		$percent = $this->database->db_result("select commission from commission_charities join items on commission_charities.pkey = items.itm_key where items.itm_id ='".$itemid."' and commission_charities.orderid = '".$this->oid."' and commission_charities.rid = '-1'");
		return $percent;		
	}
	
	function getEmployCommission(){
		$charities_commission = 0;
		$re = $this->db->query("select commission_charities.commission,order_detais.itemprice,order_detais.quality from commission_charities join order_detais on order_detais.id = commission_charities.odetail where commission_charities.rid = 0 and commission_charities.orderid = ".$this->oid);
		foreach($re->result_array() as $row){
			$price = $row['itemprice'] * $row['quality'];
			$charities_commission += round($row['commission'] * $price / 100, 2);
		}
		return $charities_commission;
	}
	
	///////////// Get Charity Commission////////////////
	function getCharityCommission(){
		$charities_commission = 0;
		$re = $this->db->query("select commission_charities.commission,order_detais.itemprice,order_detais.quality from commission_charities join order_detais on order_detais.id = commission_charities.odetail where commission_charities.rid = 8 and commission_charities.orderid = ".$this->oid);
		foreach($re->result_array() as $row){
			$price = $row['itemprice'] * $row['quality'];
			$charities_commission += $row['commission'] * $price / 100;  
		}
		return $charities_commission; 
	}
	
	///////////// Get Member Commission////////////////
	function getMemberCommission(){
		$total_commission = 0;
		
		$re = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemid,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.oid = ".$this->oid);		
		if ($re->num_rows() > 0){
			foreach($re ->result_array() as $row){
				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1", 0);	
				$qty_buy = (int)$row['quality'] - $qty_refund;
				if($qty_buy < 0) $qty_buy = 0;
				$itemprice = round((float)$row["itemprice"]*$qty_buy, 2);
				$total_commission += round((float)$row['commission'] * $itemprice / 100, 2);
			}	
		}
		//echo $total_commission."<br>";
		return $total_commission;	
	}
	
	///////////////////// Get Vender Commission/////////////////
	function getVenderCommission(){
		$amount_ = 0;
	
		$status_order = (int)$this->order_detail['status'];
		$base_price	= (float)$this->order_detail["shipping_fee"];
		
		$shipping_fee = 0;
		$subtotal = 0;
		
		$packages = $this->get_packages();
		$order_status_level = array();
		
		$arr_manufacturers__ = $this->getOrderManufacturer();
		if(count($arr_manufacturers__) == 0) continue;
		
		$arr_orders_handling = $this->getOrderHandling();
		
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
				if($row_1['product_type'] == 0){
					if($qty_ship == $row_1["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
					$ship_fee = (float)($row_1['last_shipping'] * $row_1["quality"]);
					$shipping_rate += round($ship_fee, 2);
				}else{
					$status_item = 3;
					$shipping_rate = 0;
				}	
				$order_status_level[] = $status_item;	
				$subtotal += round($row_1["current_cost"] * $row_1["quality"], 2); 
			}//1
			$shipping_fee += round($shipping_rate, 2);
		}
		$shipping_fee = round($shipping_fee, 2);
		$tax = $this->getOrderTax(1);
		$amount_ = round($subtotal + $tax + $shipping_fee, 2);
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
		if($min_level != 3) $amount_ = 0;
		return $amount_;	
	}
	
	function getOrderTax($type = 0){
		$tax = 0;
		$re = $this ->db ->query("select current_cost,itemprice,last_itemprice,quality,tax_persend from order_detais where orderid=".$this->oid);
		if($re->num_rows() <=0) return $tax;
		if($type == 0){
			foreach($re ->result_array() as $row){
				$tax += ($row['itemprice']*$row['quality']*$row['tax_persend']/100);
			} 
		}else{
			foreach($re ->result_array() as $row){
				$tax += ($row['current_cost']*$row['quality']*$row['tax_persend']/100);
			}	
		}
		return $tax;
	}
	
	function getOrderHandling(){
		$arr_orders_handling = array();
		$re_1 = $this->db->query("select * from orders_handling where oid = ".$this->oid);
		foreach($re_1->result_array() as $row_1){
			$arr_orders_handling[] = $row_1;	
		}
		return $arr_orders_handling;	
	}
	
	function getOrderManufacturer(){
		$arr_manufacturers__ = array();
		$re_1 = $this->db->query("select order_detais.itemid,order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,order_detais.last_shipping,order_detais.tax_persend,items.itm_key,items.uid,items.product_type from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = ".$this->oid);
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
		return $arr_manufacturers__;	
	}
	
	function get_packages(){
		$packages = array();
		$re_1 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '".$this->okey."'");
		foreach($re_1->result_array() as $row_3){
			$ship = 0;
			$re_4 = $this->db->query("select id from shipments where skey = '".$row_3['shipment_ID']."' and okey = '".$this->okey."'");
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
	
	function calculate_total(){
		$roles = $this->author->objlogin->role;
		
		$order_status = $this->order_detail['status'];
		$okey = $this->order_detail['okey'];
		$packages = array();
		$order_status_level = array();
		
		$re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
		foreach($re_2->result_array() as $row_2){
			$ship = 0;
			$re_3 = $this->db->query("select id from shipments where skey = '".$row_2['shipment_ID']."' and okey = '$okey'");
			
			if($re_3->num_rows() > 0){
				$ship = 1;	
			}
			$items = array();
			$re_3 = $this->db->query("select product_id,qty from packages_items where package_id = ".$row_2['id']);
			foreach($re_3->result_array() as $row_3){
				$items[] = $row_3;	
			}
			$packages[] = array(
				'ship' => $ship,
				'items' => $items
			);	
		}
		//$tax_pecen	= $this->order_detail["order_tax"];
		$handling_fee = $this->order_detail['shipping_fee'];
		$order_refund = array();
		
		$re_refund = $this->db->query("select refund_key,id,refund_date,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
		foreach($re_refund->result_array() as $row_return){
			$check_ok_refund = false;
			$status_refund = 1;
			$del_refund = 1;
			$re_1 = $this->db->query("select odetail_return.odetail,odetail_return.qty,odetail_return.status,odetail_return.max_qty,order_detais.quality from odetail_return join order_detais join items on odetail_return.odetail = order_detais.id and order_detais.itemid = items.itm_id where odetail_return.rid = ".$row_return['id']." $this->sql_manufacturer");
			foreach($re_1->result_array() as $row_1){
				if($row_1['qty'] > 0){
					$check_ok_refund = true;
					if($row_1['status'] == 1){
						$del_refund = 0;
					}
					if($row_1['status'] == 0){
						$status_refund = 0;	
					} 	
				}
			}
			if($check_ok_refund == false) continue;
			$reason = (isset($__refund_reason__[$row_return['refund_type']]))?$__refund_reason__[$row_return['refund_type']]:'None';
			$refund_date = '';
			if($row_return['refund_update'] != NULL) $refund_date = gmdate("m/d/Y", strtotime($row_return['refund_update']));
			elseif($row_return['refund_date'] != NULL) $refund_date = gmdate("m/d/Y", strtotime($row_return['refund_date']));
			$refund_status = 'Refund Completed';
			if($status_refund == 0) $refund_status = 'Refund Pending';
			$order_refund[] = array('refund_key'=>$row_return['refund_key'],'reason'=>$reason,'refund_date'=>$refund_date,'status'=>$refund_status,'total'=>$this->lib->showMoney(-($this->loadAmountRefund2($row_return['id'],$roles['rid'],$this->author->objlogin->uid))),'del'=>$del_refund);
		}
		
		$arrPromotions = array();
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey' ".$this->sql_promotion;
		$re_pro = $this->db->query($sql_orders_promotions);
		foreach($re_pro->result_array() as $row_pro){
			$arrPromotions[] = $row_pro;	
		}
		
		$arr_manufacturers = array();
		$re_1 = $this->db->query("select order_detais.*,items.itm_key,items.uid,items.product_type,items.origin from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = ".$this->order_detail['orderid'].$this->sql_manufacturer." order by order_detais.id ASC");
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
		$arr_orders_handling = array();
		$re_1 = $this->db->query("select * from orders_handling where oid = ".$this->order_detail['orderid']);
		foreach($re_1->result_array() as $row_1){
			$arr_orders_handling[] = $row_1;	
		}
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$handling_fee_new = $handling_fee;
			foreach($arr_orders_handling as $oh){
				if($oh['uid'] == $arr_manufacturers[$m]['uid']){
					$handling_fee_new = $oh['handling'];
					break;	
				}		
			}
			$shipping_rate = $handling_fee_new;
			$count_ship_free = count($arr_manufacturers[$m]['items']);
			foreach($arr_manufacturers[$m]['items'] as $row_1){//1
				$itemid = $row_1['itemid'];
				$merchant_cost_percent = $this->getItmMerchantCostPercent($itemid);
				$tax_pecen = $row_1['tax_persend'];
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
				if($row_1['product_type'] == 0){
					if($qty_ship == $row_1["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
				}else{
					$status_item = 3;	
				}
				$order_status_level[] = $status_item;
                          
				$itm_price 	= ($roles['rid'] == MANUFACTURER)?$row_1["current_cost"]:$row_1["itemprice"];
				$check_shipping_free = false;
				if(count($arrPromotions) > 0){//2
					foreach($arrPromotions as $promotions){//3
						if($promotions['itm_key'] == $row_1['itm_key']){//4
							switch($promotions['promo_type']){
								case 3:
									$check_shipping_free = true;
									break;	
								case 4:
									$check_shipping_free = true;
									break;		
							}
						}//4
					}//3
				}//2
				
				$amount = round($itm_price * $row_1["quality"], 2);
				if($row_1['product_type'] != 0){
					$count_ship_free --;
					$this->checkItemDonation($itemid);
					if($this->item_type == 0)
						$this->tax += $tax_pecen * $amount / 100;
				}else{
					if($row_1['shipping_fee'] <= 0){
						$row_1['shipping_fee'] = 0;
						if($check_shipping_free == true) $count_ship_free --;	
					}	
					if($roles['rid'] == MANUFACTURER){
						$shipping_rate += round($row_1['last_shipping'] * $row_1["quality"], 2);	
					}else{
						$shipping_rate += $row_1['shipping_fee'];
					}
					$this->tax += $tax_pecen * $amount / 100;
				}
				$this->subtotal += $amount;
				$this->merchant_cost += (float)($merchant_cost_percent * ($amount+($tax_pecen * $amount/100)+$shipping_rate) / 100);
			}//1
			if($roles['rid'] != MANUFACTURER){
				if($shipping_rate == $handling_fee_new && $count_ship_free == 0) $shipping_rate = 0;
			}
			$this->shipping_fee += round($shipping_rate, 2);
		}//0
		$this->shipping_fee = round($this->shipping_fee, 2);
		$this->tax = round($this->tax, 2);
		$this->min_level = 3;
		$Canceled_status = 0;
		$Refunded_status = 0;
		if(count($order_status_level) > 0){
			foreach ($order_status_level as $level){
				if($level == 4) $Canceled_status = 1;
				elseif($level == 5) $Refunded_status = 1;
				elseif($level < 4){
					if($level < $this->min_level){
						$this->min_level = $level;
					}		
				}
			}
		}
		if($order_status == 4) $Canceled_status = 1;
		if($Canceled_status == 1) $this->min_level = 4;
		elseif($Refunded_status == 1) $this->min_level = 5;	
	}
	
	public function checkItemDonation($itm_id){
		$this->item_type = 0;
		$itm_id = $this->lib->escape($itm_id);
		if($itm_id != ''){
			$user_role = $this->database->db_result("select rid from users_roles join items on users_roles.uid = items.uid where items.itm_id = '$itm_id'");
			if($user_role == CHARITY) $this->item_type = 1;
		}
	}
}