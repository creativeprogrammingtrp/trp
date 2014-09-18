<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model {
	var $okey = '';
	var $oid = 0;
	var $tblcontries = array();
	
	var $shipping_fee = 0;
	var $tax = 0;
	var $subtotal = 0;
	var $tax_pecen = 0;
	var $base_price = 0;
	var $order_status = 1;
	var $order_status_str = '';
	var $packages = array();
	var $order_date = '';
	var $totalPrice = 0;
	var $order_type = 0; 
	
	var $shippingName = '';
	var $shippingAddress = '';
	var $shippingCity = '';
	var $billingName = '';
	var $billingAddress = '';
	var $billingCity = '';
	var $billingEmail = '';
	var $card_number = '';
	var $ship_label = '';
	
	var $arr_manufacturers = array();
	var $arrPromotions = array();
	var $arr_orders_handling = array();
	var $ItemDetails = array();
	var $order_status_level = array();
	var $check_commission = '';
	
	var $refund_row = '';
	
	var $sql_customer = '';
	var $key_word_sql = '';
	var $status_sql = '';
	var $month_sql = '';
	var $year_sql = '';
	var $sql_uid_clients = '';
	var $sql_manufacturer = '';
	var $sql_charities = '';
	var $sql_promotion = '';
	
	
	//View List
	public function ViewList($page,$colum,$sortby){
		return json_encode($this->load_order($page,$colum,$sortby));
	}
	public function ViewListVoucher($page,$colum,$sortby){
		return json_encode($this->load_order_voucher($page,$colum,$sortby));	
	}
	function check_valid(&$varible){
		if(isset($varible) && trim($varible) != ''){
			return $this->lib->escape($varible);	
		}
		return '';
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
			$this->status_sql = " and orders.status = '".$status."'";		
		}	
	}
	function set_status_voucher_sql($status){
		if($this->check_valid($status) != '' && is_numeric($this->check_valid($status))){
			//$this->status_sql = " and C.status = '".$status."'";		
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
	function set_sql_charities(){
		$this->sql_charities = " and items.uid = ".$this->author->objlogin->uid;
	}
	function load_order($page, $col, $sortby_ ){
		
		$roles = $this->author->objlogin->role;
	
		$num_per_pager = NUMROWPERPAGE;
		$limit = $num_per_pager*($page-1);
		
		$__order_status__ = $this->config->item('__order_status__');
                
		$__refund_reason__ = $this->config->item('__refund_reason__');
		
		$check_vendor_login = false;
		
		if($roles['rid'] == MANUFACTURER){
			$check_vendor_login = true;
			$this->set_sql_manufacturer();
		}elseif($roles['rid'] == CHARITY){
			$check_vendor_login = true;
			$this->set_sql_charities();
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
		
		$sql_auto = 0;
		$order_auto = array();
		$re_auto = $this->db->query("select distinct oid from orders_auto_delivery");
		
		foreach($re_auto->result_array() as $key => $row){
			$order_auto[] = $row;
			$sql_auto .= ','.$row['oid'];
		}

		//$sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.shipping_state, orders.status,orders.billing_name,orders.r_ordernum from orders where orders.orderid NOT IN(".$sql_auto.") and orders.status <> -1 " .$this->status_sql. $this->sql_uid_clients.$this->month_sql.$this->year_sql.$this->sql_customer.$this->key_word_sql." ) as A";//before code
                
                $sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.shipping_state, orders.status,orders.billing_name,orders.r_ordernum from orders left join orders_auto_delivery on orders.orderid =orders_auto_delivery.oid  where orders.status <> -1  " .$this->status_sql. $this->sql_uid_clients.$this->month_sql.$this->year_sql.$this->sql_customer.$this->key_word_sql." ) as A";//fix here 
		$sql_2 = " (select distinct order_detais.orderid from order_detais join items on order_detais.itemid = items.itm_id where 1=1  ".$this->sql_manufacturer.$this->sql_charities.") as B ";
                //$sql_2 = "(select distinct order_detais.orderid from order_detais,items where order_detais.itemid = items.itm_id and items.itm_id not in (select voucher.item_id from voucher,items where items.itm_id = voucher.item_id) ".$this->sql_manufacturer.$this->sql_charities.") as B ";
                
		$max_sql = "select count(A.orderid) ";
		$max_sql .= " from ".$sql_1." join ".$sql_2." on A.orderid = B.orderid ";
	
		$maxlength = $this->database->db_result($max_sql);
      
		$max_sql = "select * ";
		$max_sql .= " from ".$sql_1." join ".$sql_2." on A.orderid = B.orderid ";
		$max_sql .= " ORDER BY $sort_type $sort_by limit $limit,".$num_per_pager;
               
		$data = array();
		$query = $this->db->query($max_sql);
		foreach($query->result_array() as $row){
			$check = false;			
			$order_status = $row['status'];
			$okey = $row['okey'];
			$packages = array();
			$order_status_level = array();
			$tracking_number = $this->database->db_result("select tracking_number from shipments where okey = '$okey'");
			if($tracking_number == false) $tracking_number = '';
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
			
			$handling_fee = $row['shipping_fee'];
			$order_refund = array();
			
			$re_refund = $this->db->query("select refund_key,id,refund_date,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
			foreach($re_refund->result_array() as $row_return){
				$check_ok_refund = false;
				$status_refund = 1;
				$del_refund = 1;
				$re_1 = $this->db->query("select odetail_return.odetail,odetail_return.qty,odetail_return.status,odetail_return.max_qty,order_detais.quality from odetail_return join order_detais join items on odetail_return.odetail = order_detais.id and order_detais.itemid = items.itm_id where odetail_return.rid = ".$row_return['id']. $this->sql_manufacturer . $this->sql_charities);
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
			$re_1 = $this->db->query("select order_detais.*,items.itm_key,items.uid,items.product_type,items.origin from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = ".$row['orderid'].$this->sql_manufacturer.$this->sql_charities." order by order_detais.id ASC");
			if($roles['rid'] == Sale_Representatives || $roles['rid'] == AUTHENTICATED_USER){
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
			}else{
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
			}
			if(count($arr_manufacturers) <= 0) continue;
			$arr_orders_handling = array();
			$re_1 = $this->db->query("select * from orders_handling where oid = ".$row['orderid']);
			foreach($re_1->result_array() as $row_1){
				$arr_orders_handling[] = $row_1;	
			}
			$tax = 0;
			$subtotal = 0;
			$shipping_fee = 0;
			for($m = 0; $m < count($arr_manufacturers); $m++){//0
				$handling_fee_new = $handling_fee;
				foreach($arr_orders_handling as $oh){
					if($oh['uid'] == $arr_manufacturers[$m]['uid']){
						$handling_fee_new = $oh['handling'];
						break;	
					}		
				}
				$check_product_items_exist = false;
				$shipping_rate = $handling_fee_new;
				$count_ship_free = count($arr_manufacturers[$m]['items']);
				foreach($arr_manufacturers[$m]['items'] as $row_1){//1
					$itemid = $row_1['itemid'];
					$qty_ship = 0;
					$qty_par = 0;
					if($check_vendor_login == true && $row_1['product_type'] == 2){//
						$memory_items = 0;
						$re_4 = $this->db->query("select status from voucher where item_id = $itemid and order_id = ".$row_1['orderid']);
						foreach($re_4->result_array() as $row_4){
							if($row_4['status'] == 1){
								$memory_items++;
                                          
							}
						}
						if($memory_items > 0){
							$row_1['quality'] = $memory_items;
						}else{
							if($this->check_order_voucher()) $check = true;
							continue;	
						}
					}
					
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
                                      
					if($row_1['product_type'] == 0 ){
						if($qty_ship == $row_1["quality"]){
							$status_item = 3;	
						}elseif($qty_par > 0 || $qty_ship > 0){
							$status_item = 2;		
						}
						$check_product_items_exist = true;
					}else{
						$status_item = 3;	
					}
					$order_status_level[] = $status_item;

					$itm_price 	= ($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY)?$row_1["current_cost"]:$row_1["itemprice"];
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
					}else{
						if($row_1['shipping_fee'] <= 0){
							$row_1['shipping_fee'] = 0;
							if($check_shipping_free == true) $count_ship_free --;	
						}	
						if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
							$shipping_rate += round($row_1['last_shipping'] * $row_1["quality"], 2);	
						}else{
							$shipping_rate += $row_1['shipping_fee'];
						}
						
					}
					$tax += (float)$row_1['tax_persend'] * $amount / 100;
					$subtotal += $amount;	
				}//1  
				if($check_product_items_exist){
					if($roles['rid'] != MANUFACTURER || $roles['rid'] == CHARITY){
						if($shipping_rate == $handling_fee_new && $count_ship_free == 0) $shipping_rate = 0;
					}
					$shipping_fee += round($shipping_rate, 2);
				}
			}//0
			if($check) continue;
			$shipping_fee = round($shipping_fee, 2);
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
			if($order_status == 4) $Canceled_status = 1;
			if($Canceled_status == 1) $min_level = 4;
			elseif($Refunded_status == 1) $min_level = 5;
			
			$order_date_int = strtotime($row['order_date']);
			$refund_action = 'no';
			$cancel_action = 'no';
			if($row['r_ordernum'] != NULL && $row['r_ordernum'] != ''){
				if($roles['rid'] == ADMINISTRATOR || $roles['rid'] == NWMANAGEMENT || $roles['rid'] == MANUFACTURER){
					if($this->lib->getTimeGMT() - $order_date_int <= 30*24*60*60){
						$refund_action = 'yes';
					}
					if($this->lib->getTimeGMT() - $order_date_int <= 3*24*60*60 && $min_level == 1){
						$cancel_action = 'yes';		
					}		
				}
			}

			$data[] = array(
				'okey'=>$okey,
				'billing_name'=>$row['billing_name'],
				'order_date' => $row['order_date'],
				'order_date_format'=>gmdate("l, F j, Y", $order_date_int),
				'tracking'	=> $tracking_number, 
				'status' => (int)$min_level,
				'status_format' => isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
				'order_total' => (float)($subtotal + $tax + $shipping_fee),
				'refund' => $order_refund,
				'refund_action' => $refund_action,
				'cancel' => $cancel_action
			);
		} 
		return array('data'=>$data, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page, 'rid'=>(int)$roles['rid']);
	}
	
	function load_order_voucher($page, $col, $sortby_){
		
		$roles = $this->author->objlogin->role;
		
		$num_per_pager = NUMROWPERPAGE;
		$limit = $num_per_pager*($page-1);
		$__order_status__ = $this->config->item('__order_voucher_status__');
		$__refund_reason__ = $this->config->item('__refund_reason__');
		
		if($roles['rid'] == MANUFACTURER){
			$this->set_sql_manufacturer();
		}elseif($roles['rid'] == CHARITY){
			$this->set_sql_charities();
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
		
		$check_vendor_login = false;
		$manu_sql = '';
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$manu_sql = " where voucher.status = 1";
			$check_vendor_login = true;
		}
                
		//Error sql
		//$sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.shipping_state, orders.status,orders.billing_name,orders.r_ordernum from orders where orders.orderid NOT IN('".$order_auto."') and orders.status <> -1 " . $this->sql_uid_clients.$this->month_sql.$this->year_sql.$this->sql_customer.$this->key_word_sql." ) as A";
		
		$sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.shipping_state, orders.status,orders.billing_name,orders.r_ordernum from orders where orders.orderid NOT IN(select distinct oid from orders_auto_delivery) and orders.status <> -1 " . $this->sql_uid_clients.$this->month_sql.$this->year_sql.$this->sql_customer.$this->key_word_sql." ) as A";
		
		$sql_2 = " (select distinct order_detais.orderid, items.itm_id from order_detais join items on order_detais.itemid = items.itm_id where items.product_type = 2 ".$this->sql_manufacturer.$this->sql_charities.") as B";
		$sql_3 = " (select distinct voucher.order_id, voucher.item_id from voucher $manu_sql ) as C "; 
		
		$max_sql = "select count(A.orderid) ";
		$max_sql .= " from ".$sql_1." join ".$sql_2." join ".$sql_3." on A.orderid = B.orderid and B.orderid = C.order_id WHERE B.itm_id = C.item_id ".$this->status_sql;
		
		$maxlength = $this->database->db_result($max_sql);
		
		$max_sql = "select distinct A.orderid, A.okey,A.order_date,A.order_tax,A.shipping_fee,A.shipping_state, A.status,A.billing_name,A.r_ordernum ";
		$max_sql .= " from ".$sql_1." join ".$sql_2." join ".$sql_3." on A.orderid = B.orderid and B.orderid = C.order_id WHERE 1=1 ".$this->status_sql;
		$max_sql .= " ORDER BY $sort_type $sort_by limit $limit,".$num_per_pager;

		$data = array();
		$query = $this->db->query($max_sql);
		foreach($query->result_array() as $row){
			$order_status = $row['status'];
			$okey = $row['okey'];
			$packages = array();
			$order_status_level = array();

			$order_refund = array();
			
			$re_refund = $this->db->query("select refund_key,id,refund_date,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
			foreach($re_refund->result_array() as $row_return){
				$check_ok_refund = false;
				$status_refund = 1;
				$del_refund = 1;
				$re_1 = $this->db->query("select odetail_return.odetail,odetail_return.qty,odetail_return.status,odetail_return.max_qty,order_detais.quality from odetail_return join order_detais join items on odetail_return.odetail = order_detais.id and order_detais.itemid = items.itm_id where odetail_return.rid = ".$row_return['id']. $this->sql_manufacturer . $this->sql_charities);
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
			$re_1 = $this->db->query("select order_detais.*,items.itm_key,items.uid,items.product_type,items.origin from order_detais join items on order_detais.itemid = items.itm_id where items.product_type = 2 and order_detais.orderid = ".$row['orderid'].$this->sql_manufacturer.$this->sql_charities." order by order_detais.id ASC");
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
			$tax = 0;
			$subtotal = 0;
			
			$arr_redeem = array();
			$arr_redeem['quanlity_redeem'] = 0;
			$arr_redeem['quanlity_no_redeem'] = 0;
			
			for($m = 0; $m < count($arr_manufacturers); $m++){//0
				foreach($arr_manufacturers[$m]['items'] as $row_1){//1
					$itemid = $row_1['itemid'];
					
					$quanlity_redeem = 0;
					$quanlity_no_redeem = 0;
					
					$re_2 = $this->db->query("select status from voucher where order_id = '".$row['orderid']."' and item_id = '$itemid'");
					foreach($re_2->result_array() as $row_2){
						if($row_2['status'] == 1){
							$quanlity_redeem++;
						}
						if($check_vendor_login){
							$order_status_level[] = 1;
						}else{
							$order_status_level[] = $row_2['status'];       
						}
					}
					$quanlity_no_redeem = $row_1["quality"] - $quanlity_redeem;
					$itm_price 	= ($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY)?$row_1["current_cost"]:$row_1["itemprice"];
					
					$arr_redeem['quanlity_redeem'] += $quanlity_redeem;
					$arr_redeem['quanlity_no_redeem'] += $quanlity_no_redeem;
					
					if($check_vendor_login){
						$row_1["quality"] = $quanlity_redeem;
					}
					$amount = round($itm_price * $row_1["quality"], 2);
					$tax += (float)$row_1['tax_persend'] * $amount / 100;
					$subtotal += $amount;	
				}//1
			}//0
			
			$tax = round($tax, 2);
			$min_level = 1;
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
			if($order_status == 4) $Canceled_status = 1;
			if($Canceled_status == 1) $min_level = 4;
			elseif($Refunded_status == 1) $min_level = 5;
			
			$order_date_int = strtotime($row['order_date']);
			$refund_action = 'no';
			$cancel_action = 'no';
			if($row['r_ordernum'] != NULL && $row['r_ordernum'] != ''){
				if($roles['rid'] == ADMINISTRATOR || $roles['rid'] == NWMANAGEMENT || $roles['rid'] == MANUFACTURER){
					if($this->lib->getTimeGMT() - $order_date_int <= 30*24*60*60){
						$refund_action = 'yes';
					}
					if($this->lib->getTimeGMT() - $order_date_int <= 3*24*60*60 && $min_level == 1){
						$cancel_action = 'yes';		
					}		
				}
			}
			$check_vendor = 0;
			if($check_vendor_login == true){ 
				$arr_redeem = array();
				$check_vendor = 1;	
			}
			
			$data[] = array(
				'okey'=>$okey,
				'billing_name'=>$row['billing_name'],
				'order_date' => $row['order_date'],
				'order_date_format'=>gmdate("l, F j, Y", $order_date_int),
				'status' => (int)$min_level,
				'status_format' => isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
				'order_total' => (float)($subtotal + $tax),
				'refund' => $order_refund,
				'refund_action' => $refund_action,
				'cancel' => $cancel_action,
				'arr_redeem' => $arr_redeem,
				'check_vendor' => $check_vendor
			);
		}
		return array('data'=>$data, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page, 'rid'=>(int)$roles['rid']);
	}
	
	private function loadAmountRefund2($refundID,$rid=ADMINISTRATOR,$uid){
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
		if($rid == MANUFACTURER || $rid == CHARITY){
			$sql_manufacturer = "and items.uid = ".$uid;
			$sql_orders_promotions .= " and manufacturer_id = ".$uid;
		}
		$arrPromotions = array();
		$re = $this->db->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		
		$order_detais = array();
		$strSql = "SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.duration_refund,items.duration_type_refund,items.charge_refund,items.charge_refund_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' ". $this->sql_manufacturer.$this->sql_charities." order by order_detais.id ASC";
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
			if($rid == MANUFACTURER || $rid == CHARITY){
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
	
	function setOkey($okey){
		$this->okey = $okey;	
	}
	
	function check_order_voucher(){
		$manu_sql = '';
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$manu_sql = " and items.uid = '".$this->author->objlogin->uid."' ";
		}
		$check = true;
		$re = $this->db->query("select order_detais.itemid from order_detais where order_detais.orderid = '".$this->oid."' ");
		foreach($re->result_array() as $row){
			$product_type = $this->database->db_result("select product_type from items where itm_id = '".$row['itemid']."' $manu_sql");
			if($product_type != 2)
				$check = false;
				//break;
		}
		return $check;
              
	}
	
	function get_order_type($okey){
		$itm_id = $this->database->db_result("select itemid from order_detais join orders on order_detais.orderid = orders.orderid where orders.okey = '$okey'");
		$user_role = $this->database->db_result("select rid from users_roles join items on users_roles.uid = items.uid where items.itm_id = '$itm_id'");
		if($user_role == CHARITY) $this->order_type = 1;
	}
	
	function loadDonateInfo(){
		$this->subtotal = 0;
		$this->__order_status__ = $this->config->item('__order_status__');
		$this->loadCountriesList();
		
		$r = $this->db->query("SELECT * FROM orders WHERE okey = '".$this->okey."'");
		if(	$row = $r->row_array()){
			$this->oid = $row['orderid'];
			$this->order_date	= gmdate("F j, Y",strtotime($row["order_date"]));
			$this->card_number	= $row["card_number"];
			$this->order_status = $row['status'];
			$this->billingName = $row["billing_name"];
			$this->billingAddress = $row["billing_address"];
			$this->billingCity = $row["billing_city"].', '.$row["billing_state"].' '.$row["billing_zip"].'<br>'.(isset($this->tblcontries[$row["billing_country"]])?$this->tblcontries[$row["billing_country"]]:'');
			$this->billingEmail = $row["billing_email"];
			$this->card_number = "XXXXX".$row['card_number'];
		}
		
		$this->loadManufacturers();	
		$this->loadItemDetails();
		$this->loadOrderStatus();	
		$load_myWallet= $this ->load_myWallet($this->okey,$this->subtotal);
		$this->check_commission =(count($load_myWallet)>0)? array($load_myWallet):array();
	}
	
	function loadOrderInfo(){
        $roles = $this->author->objlogin->role;
		if($this->okey == '') return;		
		$this->get_order_type($this->okey);
	
		$this->subtotal = 0;
		$this->__order_status__ = $this->config->item('__order_status__');
		$this->loadCountriesList();
		
		$r = $this->db->query("SELECT * FROM orders WHERE okey = '".$this->okey."'");
		if($row = $r->row_array()){
			$this->oid = $row['orderid'];
           
			$this->tax_pecen	= $row["order_tax"];
			$this->order_date	= gmdate("F j, Y",strtotime($row["order_date"]));
			$this->card_number	= $row["card_number"];
			$this->base_price	= $row['shipping_fee'];
			$this->order_status = $row['status'];
			$this->shippingName = $row["shipping_name"];
			$this->shippingAddress = $row["shipping_address"];
			$this->shippingCity = $row["shipping_city"].', '.$row["shipping_state"].' '.$row["shipping_zip"].'<br>'.(isset($this->tblcontries[$row["shipping_country"]])?$this->tblcontries[$row["shipping_country"]]:'');
			$this->billingName = $row["billing_name"];
			$this->billingAddress = $row["billing_address"];
			$this->billingCity = $row["billing_city"].', '.$row["billing_state"].' '.$row["billing_zip"].'<br>'.(isset($this->tblcontries[$row["billing_country"]])?$this->tblcontries[$row["billing_country"]]:'');
			$this->billingEmail = $row["billing_email"];
			$this->card_number = "XXXXX".$row['card_number'];
			$this->ship_label = $this->database->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");
                       
		}
		$this->loadpackages();
		$this->loadPromotions();
		$this->loadManufacturers(1);
		if(count($this->arr_manufacturers) <= 0) return;
		$this->loadOrderHandling();
                $this->loadOrderStatus();
		$this->loadItemDetails();
		$load_myWallet=$this ->load_myWallet($this->okey,$this->subtotal);
		$this->check_commission =(count($load_myWallet)>0)? array($load_myWallet):array();
            
	}
	
	function loadOrderHandling(){
		$re_1 = $this->db->query("select * from orders_handling where oid = ".$this->oid);
		foreach($re_1->result_array() as $row_1){
			$this->arr_orders_handling[] = $row_1;	
		}	
	}
	
	function loadpackages(){
		$query = $this->db->query("select id,pkey,shipment_ID from packages where okey = '".$this->okey."'");
		if ($query->num_rows() > 0){
			foreach ($query->result_array() as $row_2){
				$ship = 0;
				$query_3 = $this->db->query("select id from shipments where skey = '".$row_2['shipment_ID']."' and okey = '".$this->okey."'");
				if ($query_3->num_rows() > 0){
					$ship = 1;	
				}
				$items = array();
				$query_3 = $this->db->query("select product_id,qty from packages_items where package_id = ".$row_2['id']);
				if ($query_3->num_rows() > 0){
					foreach ($query_3->result_array() as $row_3){
						$items[] = $row_3;		
					}
				}
				$this->packages[] = array(
					'ship' => $ship,
					'items' => $items
				);
			}
		}	
	}
	
	function loadManufacturers($type = 0){
		$re_1 = $this->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.product_type,items.origin FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '".$this->oid."' order by order_detais.id ASC");
		if($type == 0){
			foreach($re_1->result_array() as $row_1){
				$check_exist = false;
				for($m = 0; $m < count($this->arr_manufacturers); $m++){
					if($this->arr_manufacturers[$m]['uid'] == $row_1['uid']){
						$this->arr_manufacturers[$m]['items'][] = $row_1;
						$check_exist = true;
						break;	
					}	
				}
				if($check_exist == false){
					$this->arr_manufacturers[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
				}	
			}	
		}else{
			foreach($re_1->result_array() as $row_1){
				$check_exist = false;
				for($m = 0; $m < count($this->arr_manufacturers); $m++){
					if($this->arr_manufacturers[$m]['uid'] == $row_1['uid']){
						$this->arr_manufacturers[$m]['items'][] = $row_1;
						$check_exist = true;
						break;	
					}	
				}
				if($check_exist == false){
					$this->arr_manufacturers[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
				}
			}	
		}
	}
	
	function loadPromotions(){
		$sql_orders_promotions = "select * from orders_promotions where order_key = '".$this->okey."'";
		$re = $this->db->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$this->arrPromotions[] = $row;	
		}	
	}
	
	function loadOrderStatus(){
		$__order_status__ = $this->config->item('__order_status__');
		$min_level = 3;
		$Canceled_status = 0;
		$Refunded_status = 0;
		if(count($this->order_status_level) > 0){
			foreach ($this->order_status_level as $level){
				if($level == 4) $Canceled_status = 1;
				elseif($level == 5) $Refunded_status = 1;
				elseif($level < 4){
					if($level < $min_level){
						$min_level = $level;
					}		
				}
			}
		}
		if($this->order_status == 4) $Canceled_status = 1;
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		if($this->order_type==1) $min_level = 3;
		
		$this->order_status_str = isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null';
	}
	
	function loadCountriesList(){
		$re = $this->db->query("select * from tblcontries");
		foreach($re->result_array() as $row){
			$this->tblcontries[$row['code']] = $row['name'];	
		}	
	}
	
	function loadItemDetails(){
		for($m = 0; $m < count($this->arr_manufacturers); $m++){//0
			$handling_fee_new = $this->base_price;
			foreach($this->arr_orders_handling as $oh){
				if($oh['uid'] == $this->arr_manufacturers[$m]['uid']){
					$handling_fee_new = $oh['handling'];
					break;	
				}		
			}
			$shipping_rate = $handling_fee_new;
			$count_ship_free = count($this->arr_manufacturers[$m]['items']);
			foreach($this->arr_manufacturers[$m]['items'] as $row){//1
				$this->getItems($row, $count_ship_free, $shipping_rate);
			}
			if($shipping_rate == $handling_fee_new && $count_ship_free == 0) $shipping_rate = 0;
			$this->shipping_fee += $shipping_rate;
		}
		$this->shipping_fee = round($this->shipping_fee, 2);
		$this->tax = round($this->tax, 2);
		$this->totalPrice = $this->subtotal + $this->tax + $this->shipping_fee;
		
		$__refund_reason__ = $this->config->item('__refund_reason__'); 
		$refund_amount = 0;
		$return_str = '';
		$re_refund = $this->db->query("select id,refund_update,refund_type from orders_return where okey = '".$this->okey."' order by id DESC");
		foreach($re_refund->result_array() as $row_return){
			$reason = (isset($__refund_reason__[$row_return['refund_type']]))?$__refund_reason__[$row_return['refund_type']]:'None';
			$refund__ = $this->loadAmountRefund2($row_return['id'], 2, $this->author->objlogin->uid, $this->okey, $this->oid, $this->tax_pecen, $this->base_price);
			if(is_numeric($refund__)){
				$refund__ = $refund__ * (-1);
				$refund_amount += $refund__;
				$return_str .= '<tr>';
				$return_str .= '	<td align="right" valign="middle"><b>'.gmdate("F j, Y g:i A", strtotime($row_return['refund_update'])).' refunded:</b></td>';
				$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this->lib->showMoney($refund__).'</td>';
				$return_str .= '</tr>';
			}
		}
		if($refund_amount != 0){
			$return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
			$return_str .= '<tr>';
			$return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
			$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this->lib->showMoney($this->totalPrice+$refund_amount).'</td>';
			$return_str .= '</tr>';
		}
		$this->refund_row = $return_str;
	}
	
	function getItems($row, &$count_ship_free, &$shipping_rate){
            
                $__order_status__ = $this->config->item('__order_status__');
		$itemid = $row["itemid"];
		$odetail = $row['id'];
		$itm_name = $row['itm_name'];
		$itm_model = $row['itm_model'];
		$origin = '';
		if($row['origin'] != '' && $row['origin'] != null){
			$origin = $this->lib->ConvertToHtml($row['origin']);	
		}
		$arr_file = $this->lib->__loadFileProduct__($itemid);
		$_filename = $arr_file['file'];
		//$order_status_level[] = $row['Status'];
		
		$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");	
		
		//$this->loadOrderStatusLevel($itemid, $row["quality"]);
		$manufacturer = $this->loadManufacturerName($row['uid']);
		$attributes_str = $this->loadAttributes_str($odetail);
		
		$check_shipping_free = false;
		$arr_show_promotions = array();
		$free_product_row = array();
		if(count($this->arrPromotions) > 0){
			foreach($this->arrPromotions as $promotions){
				if($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']){
					$result_qty = $promotions['result_qty'];
					$qty_buy = $row['quality'] - $qty_refund;
					if($qty_buy >= $promotions['minqty']){
						$bac_qty = 0;
						if($promotions['minqty'] > 0)
							$bac_qty = floor($qty_buy / $promotions['minqty']);
						$qty_free = $bac_qty * $promotions['freeqty'];
						$result_qty -= $qty_free;
					}
					if($result_qty <= 0) $result_qty = 0;
					
					$manufacturer_free = $this->loadManufacturerName($promotions['manufacturer_id']);
					
					$itm_model_pro = '';
					$itm_name_pro = '';
					$itemid_pro = 0;
					$re_ = $this->db->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
					foreach($re_->result_array() as $row_){
						$itm_name_pro = $row_['itm_name'];
						$itm_model = $row_['itm_model'];
						$itemid_pro = $row_['itm_id'];		
					}
					$arr_file = $this->lib->__loadFileProduct__($itemid_pro);
					
					$desc_free = '<div style="clear:both"><b>'.$itm_name_pro.'</b><BR><b>Model: </b>'.$itm_model_pro.'</div>';
					$desc_free .= '<div style="clear:both; padding-top:10px">';
					$desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
					$desc_free .= '	<tr>';
					$desc_free .= '		<td align="left" valign="top"><img src="'.$this->system->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
					$desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
					$desc_free .= '	</tr>';
					$desc_free .= '</table>';
					$desc_free .= '</div>';
					$free_product_row[] = array(
						'img' => $this->system->URL_server__() ."shopping/data/img/thumb/".$arr_file['file'],
						'desc' => $desc_free,
						'price' => '0.00',
						'qty_buy' => number_format($promotions['result_qty']),
						'qty_return' => number_format($result_qty),
						'total' => '0.00'
					);	
				}
				if($promotions['itm_key'] == $row['itm_key']){//0
					switch($promotions['promo_type']){
						case 1:
							$arr_show_promotions_step = array('promo_type'=>1, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
							$check_exist_pro = false;
							for($p = 0; $p < count($arr_show_promotions); $p++){
								if($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
									$arr_show_promotions[$p]['discount'] += $promotions['discount'];
									$check_exist_pro = true;
									break;	
								}	
							}
							if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
							break;
						case 3:
							$check_shipping_free = true;
							$arr_show_promotions_step = array('promo_type'=>3, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
							$check_exist_pro = false;
							for($p = 0; $p < count($arr_show_promotions); $p++){
								if($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
									$arr_show_promotions[$p]['discount'] += $promotions['discount'];
									$check_exist_pro = true;
									break;	
								}	
							}
							if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
							break;	
						case 4:
							$check_shipping_free = true;
							$arr_show_promotions_step = array('promo_type'=>4, 'discount_type'=>1, 'discount'=>1);
							$check_exist_pro = false;
							for($p = 0; $p < count($arr_show_promotions); $p++){
								if($arr_show_promotions[$p]['promo_type'] == 4){
									$check_exist_pro = true;
									break;	
								}	
							}
							if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
							break;		
					}
				}//0
			}
		}
		$promotions_ = '';
		for($p = 0; $p < count($arr_show_promotions); $p++){
			switch($arr_show_promotions[$p]['promo_type']){
				case 1:
					$discount_str = '';
					if($arr_show_promotions[$p]['discount_type'] == 0){
						$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
					}else{
						$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
					}
					$promotions_ .= '<div style="clear:both; padding-top:10px">';
					$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
					$promotions_ .= '	<tr>';
					$promotions_ .= '		<td align="left" valign="top"><img src="'.$this->system->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
					$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: '.$discount_str.'</td>';
					$promotions_ .= '	</tr>';
					$promotions_ .= '</table>';
					$promotions_ .= '</div>';
					break;
				case 3:
					$discount_str = '';
					if($arr_show_promotions[$p]['discount_type'] == 0){
						$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
					}else{
						$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
					}
					$promotions_ .= '<div style="clear:both; padding-top:10px">';
					$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
					$promotions_ .= '	<tr>';
					$promotions_ .= '		<td align="left" valign="top"><img src="'.$this->system->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
					$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: '.$discount_str.'</td>';
					$promotions_ .= '	</tr>';
					$promotions_ .= '</table>';
					$promotions_ .= '</div>';
					break;
				case 4:
					$promotions_ .= '<div style="clear:both; padding-top:10px">';
					$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
					$promotions_ .= '	<tr>';
					$promotions_ .= '		<td align="left" valign="top"><img src="'.$this->system->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
					$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
					$promotions_ .= '	</tr>';
					$promotions_ .= '</table>';
					$promotions_ .= '</div>';
					break;
			}
		}
		$roles = $this->author->objlogin->role;
		
		$itemprice = $row['itemprice'];
		$shipping_per_item = $row['shipping_fee'];
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$itemprice = $row['current_cost'];
			$promotions_ = '';
			$free_product_row = array();	
			$shipping_per_item = round($row['last_shipping'] * $row["quality"], 2);	
		}
		$amount = round($itemprice * $row["quality"], 2);
		
		$status_item = 1;
		if($row['product_type'] != 0){
			$count_ship_free --;
			$status_item = 3;
                        //$status_item = 1; //insert code here 
		}else{
			if($row['shipping_fee'] <= 0){
				$row['shipping_fee'] = 0;
				if($check_shipping_free == true) $count_ship_free --;	
			}
			$shipping_rate += $shipping_per_item;	
		}
		
		$this->order_status_level[] = $status_item;
                //insert code here 
                
		$min_level = 3;
		$Canceled_status = 0;
		$Refunded_status = 0;
		if(count($this->order_status_level) > 0){
			foreach ($this->order_status_level as $level){
				if($level == 4) $Canceled_status = 1;
				elseif($level == 5) $Refunded_status = 1;
				elseif($level < 4){
					if($level < $min_level){
						$min_level = $level;
					}		
				}
			}
		}
		if($this->order_status == 4) $Canceled_status = 1;
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		if($this->order_type==1) $min_level = 3;
		
		$this->order_status_str = isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null';
                
                
                
                //end code here 
                
                
		
		//echo $this->tax.'<br>';
		
		$this->tax += (float)$row['tax_persend'] * $amount / 100;
		$this->subtotal += $amount;
		
		$this->ItemDetails[] = array(
			'img' => $this->system->URL_server__() . "shopping/data/img/thumb/".$_filename,
			//'desc' => '<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.$origin.$attributes_str.'</div>'.$promotions_,
                        'desc' => '<div><ul><li>'.$itm_name.'</li><li style="padding-top: 10px">Model:'.$itm_model.'</li><li style="padding-top: 10px">'.$origin.$attributes_str.'</li></ul></div>'.$promotions_,
			'price' => number_format($itemprice, 2),
			'qty_buy' => number_format($row["quality"]),
			'qty_return' => number_format($qty_refund),
			'total' => number_format($amount, 2)
		);	
		$this->ItemDetails = array_merge($this->ItemDetails, $free_product_row);
	}
	
	function loadOrderStatusLevel($itemid, $quality){
		$qty_ship = 0;
		$qty_par = 0;
		if(count($this->packages) > 0){
			foreach($this->packages as $package){
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
		if($qty_ship == $quality){
			$status_item = 3;	
		}elseif($qty_par > 0 || $qty_ship > 0){
			$status_item = 2;		
		}
		$this->order_status_level[] = $status_item;	
	}
	
	function loadManufacturerName($uid){
		$manufacturer = 'My Store';
		$re_manu = $this->db->query("select legal_business_name from manufacturers where uid = ".$uid);
		if($row_manu = $re_manu->row_array()){
			$manufacturer = $row_manu['legal_business_name'];			
		}
		return $manufacturer;	
	}
	
	function loadAttributes_str($odetail){
		$attributes_str = '';
		$re_ = $this->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
		foreach($re_->result_array() as $row_){
			$attributes_str .= '<br><b>' .$row_['label']. ': </b>'.$row_['name'];
			if(is_numeric($row_['price']) && $row_['price'] > 0){
				$attributes_str .= '&nbsp;&nbsp;(+$'.number_format($row_['price'],2).')';	
			}
		}
		return $attributes_str;	
	}
	
	public function admin_load_order($okey='')
	{
		$total = 0;
		$__order_status__ = $this->config->item('__order_status__');
		$__refund_reason__ = $this->config->item('__refund_reason__');
		
		$roles = $this->author->objlogin->role;
		$ong_chu = $this ->lib ->__loadBoss__();
		
		$oid = 0;
		$total = 0;
		$base_price = 0;
		$refund_button = '';
		$packages = array();
		$tblcontries = array();
		
		$_order_number = '';
		$_date = '';
		$_billingName = '';
		$_billingAddress = '';
		$_billingCity = '';
		$_billingPhone = '';
		$_billingEmail = '';
		$_shippingName = '';
		$_shippingAddress = '';
		$_shippingCity = '';
		$_shippingPhone = '';
		$_card_number = '';
		$_ship_label = '';
		$tax = 0;	
		$re = $this ->db->query("select * from tblcontries");
		foreach($re ->result_array() as $row)
		{
			$tblcontries[$row['code']] = $row['name'];	
		}
		$r = $this ->db->query("SELECT * FROM orders WHERE okey = '$okey'");
		if($r -> num_rows() >0){
			$row = $r ->row_array();
			$oid = $row['orderid'];
			$tax = $this ->loadOrderTax($oid,$roles['rid']);
			$tax_pecen			= $row["order_tax"];
			$date				= $row["order_date"];
			$card_number		= $row["card_number"];
			$base_price			= $row['shipping_fee'];
			
			
			$_order_number = $okey;
			$_date = gmdate("m/d/Y",strtotime($date));
			$_billingName = $row["billing_name"];
			$_billingAddress =$row["billing_address"];
			$_billingCity =$row["billing_city"].', '.$row["billing_state"].' '.$row["billing_zip"].', '.(isset($tblcontries[$row["billing_country"]])?$tblcontries[$row["billing_country"]]:'') ;
			$_billingPhone = $row["billing_phone"];
			$_billingEmail =$row["billing_email"] ;
			$_shippingName =$row["shipping_name"] ;
			$_shippingAddress = $row["shipping_address"];
			$_shippingCity = $row["shipping_city"].', '.$row["shipping_state"].' '.$row["shipping_zip"].', '.(isset($tblcontries[$row["shipping_country"]])?$tblcontries[$row["shipping_country"]]:'');
			$_shippingPhone = $row["shipping_phone"];
			$_card_number = 'XXXXX'.$card_number;
			
			$ship_label = $this->database->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");
			$_ship_label = $ship_label;
			if($row['r_ordernum'] != null && $row['r_ordernum'] != ''){
				$order_date_int = strtotime($row['order_date']);
				if($this ->lib ->getTimeGMT() - $order_date_int <= 30*24*60*60){
					$refund_button = '<input type="button" value="Refund" name="submit" class="btn btn-primary" style="margin-left:5px; margin-bottom:5px" onclick="window.location=\'index.php?q=store/orders/refunds/arefund&okey=@okey@\';" />';
				}	
			}
			$re_2 = $this ->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
			foreach($re_2 ->result_array() as $row_2)
			{
				$ship = 0;
				$re_3 = $this ->db->query("select id from shipments where skey = '".$row_2['shipment_ID']."' and okey = '$okey'");
				if($re_3 ->num_rows() >0) $ship =1;
				$items = array();
				$re_3 = $this ->db->query("select product_id,qty from packages_items where package_id = ".$row_2['id']);
				foreach($re_3 ->result_array() as $row_3)
				{
					$items[] = $row_3;	
				}
				$packages[] = array(
					'ship' => $ship,
					'items' => $items
				);	
			}
		}//if($r -> num_rows())
		
		$order_array = array();
		
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		
		$check_vendor_login = false;
		if($roles['rid'] == MANUFACTURER){
			$check_vendor_login = true;
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}	
		if($roles['rid'] == CHARITY){
			$check_vendor_login = true;
		}
		$arrPromotions = array();
		$re = $this ->db->query($sql_orders_promotions);
		foreach($re ->result_array() as $row)
		{
			$arrPromotions[] = $row;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.product_type,items.origin FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
		foreach($re_1 ->result_array() as $row_1)
		{
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
		$re_1 = $this ->db->query("select * from orders_handling where oid = ".$oid);
		foreach($re_1 ->result_array() as $row_1)
		{
			$arr_orders_handling[] = $row_1;	
		}
		$subtotal = 0;
		$shipping_fee = 0;
		$order_status_level = array();
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$handling_fee_new = $base_price;
			foreach($arr_orders_handling as $oh){
				if($oh['uid'] == $arr_manufacturers[$m]['uid']){
					$handling_fee_new = $oh['handling'];
					break;	
				}		
			}
			$check_product_items_exist = false;
			$shipping_rate = $handling_fee_new;
			$count_ship_free = count($arr_manufacturers[$m]['items']);
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$itemid = $row["itemid"];
				$odetail = $row['id'];
				$itm_name = $row['itm_name'];
				$itm_model = $row['itm_model'];
				$qty_ship = 0;
				$qty_par = 0;
				
				$memory_items = 0;
				$re_22 = $this->db->query("select status from voucher where order_id = '".$row['orderid']."' and item_id = '$itemid'");
				foreach($re_22->result_array() as $row_22){
					if($check_vendor_login != true || $row_22['status'] == 1){
						$memory_items++;
					}
				}
				if($memory_items > 0){
					$row["quality"] = $memory_items;
				}
				
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
				if($row['product_type'] == 0 ){ 
					if($qty_ship == $row["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
					$check_product_items_exist = true;
				}else{
					$status_item = 3;	
				}
				
				$order_status_level[] = $status_item;
				
				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");
			
				$arr_file = $this ->lib ->__loadFileProduct__($itemid);
				$_filename = $arr_file['file'];
				
				$manufacturer = 'My Store';
				$re_manu = $this ->db->query("select legal_business_name from manufacturers where uid = ".$row['uid']);
				if($re_manu -> num_rows() >0)
				{
					$row_manu = $re_manu ->row_array();
					$manufacturer = $row_manu['legal_business_name'];			
				}
				
				$attributes_str = '';
				$re_ = $this ->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
				foreach($re_ ->result_array() as $row_)
				{
					$attributes_str .= '<br><b>' .$row_['label']. ': </b>'.$row_['name'];
					if(is_numeric($row_['price']) && $row_['price'] > 0){
						$attributes_str .= '&nbsp;&nbsp;(+$'.number_format($row_['price'],2).')';	
					}
				}
				$check_shipping_free = false;
				$arr_show_promotions = array();
				
				if(count($arrPromotions) > 0){
					foreach($arrPromotions as $promotions){
						if($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']){
							$result_qty = $promotions['result_qty'];
							$qty_buy = $row['quality'] - $qty_refund;
							if($qty_buy >= $promotions['minqty']){
								$bac_qty = 0;
								if($promotions['minqty'] > 0)
									$bac_qty = floor($qty_buy / $promotions['minqty']);
								$qty_free = $bac_qty * $promotions['freeqty'];
								$result_qty -= $qty_free;
							}
							if($result_qty <= 0) $result_qty = 0;
							
							$manufacturer_free = 'My Store';
							$re_manu = $this ->db->query("select legal_business_name from manufacturers where uid = '".$promotions['manufacturer_id']."'");
							if($re_manu -> num_rows() >0)
							{
								$row_manu = $re_manu ->row_array();
								$manufacturer_free = $row_manu['legal_business_name'];			
							}
							
							$itm_model = '';
							$itm_name = '';
							$itemid = 0;
							$re_ = $this ->db->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
							foreach($re_ ->result_array() as $row_){
								$itm_name = $row_['itm_name'];
								$itm_model = $row_['itm_model'];
								$itemid = $row_['itm_id'];		
							}
							
							$arr_file = $this ->lib ->__loadFileProduct__($itemid);
							$_filename = $arr_file['file'];
							
							$desc_free = '<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.'</div>';
							$desc_free .= '<div style="clear:both; padding-top:10px">';
							$desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
							$desc_free .= '	<tr>';
							$desc_free .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
							$desc_free .= '	</tr>';
							$desc_free .= '</table>';
							$desc_free .= '</div>';
							
							$order_array[] = array(
								'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
								'desc' =>$desc_free,
								'total' =>'0.00',
								'price' =>'0.00',
								'qty_return' =>number_format($result_qty),
								'qty_buy' =>number_format($promotions['result_qty']),
							);
							
						}
						if($promotions['itm_key'] == $row['itm_key']){//0
							switch($promotions['promo_type']){
								case 1:
									$arr_show_promotions_step = array('promo_type'=>1, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;
								case 3:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>3, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;	
								case 4:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>4, 'discount_type'=>1, 'discount'=>1);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 4){
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;		
							}
						}//0
					}
				}
				$promotions_ = '';
				for($p = 0; $p < count($arr_show_promotions); $p++){
					switch($arr_show_promotions[$p]['promo_type']){
						case 1:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 3:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 4:
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
					}
				}
				
				if($row['product_type'] != 0){
					$count_ship_free --;		
					$row['shipping_fee'] = 0;
					$row['last_shipping'] = 0;
				}else{
					if($row['shipping_fee'] <= 0){
						$row['shipping_fee'] = 0;
						if($check_shipping_free == true) $count_ship_free --;	
					}	
				}
				
				$itemprice = $row['itemprice'];
				$shipping_per_item = $row['shipping_fee'];
				if($roles['rid'] == MANUFACTURER){
					$itemprice = $row['current_cost'];
					$promotions_ = '';
					$shipping_per_item = round($row['last_shipping'] * $row["quality"], 2);	
				}
				$amount = round($itemprice * $row["quality"], 2);
				$shipping_rate += $shipping_per_item;
				$subtotal += $amount;
				$origin = '';
				if($row['origin'] != '' && $row['origin'] != null){
					$origin = '<br>'.$this ->lib->ConvertToHtml($row['origin']);	
				}
				
				$order_array[] = array(
					'id' =>$itemid,
					'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
					'desc' =>'<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.$origin.$attributes_str.'</div>'.$promotions_,
					'price' =>number_format($itemprice, 2),
					'qty_return' =>number_format($qty_refund),
					'qty_buy' =>number_format($row["quality"]),
					'total' =>number_format($amount, 2),
				);
			}//1
			
			if($check_product_items_exist){
				if($roles['rid'] != MANUFACTURER){
					if($shipping_rate == $handling_fee_new && $count_ship_free == 0) $shipping_rate = 0;
				}
				$shipping_fee += round($shipping_rate, 2);
			}
		}//0
		$shipping_fee = round($shipping_fee, 2);
		$tax = round($tax, 2);
		$total = $subtotal + $tax + $shipping_fee;
		
		$refund_amount = 0;
		$return_str = '';
		$re_refund = $this ->db->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
		foreach($re_refund ->result_array() as $row_return)
		{
			$reason = (isset($__refund_reason__[$row_return['refund_type']]))?$__refund_reason__[$row_return['refund_type']]:'None';
			$refund__ = $this ->loadAmountRefund2($row_return['id'], $roles['rid'], $this->author->objlogin->uid, $okey, $oid, $tax_pecen, $base_price);
			if(is_numeric($refund__)){
				$refund__ = $refund__ * (-1);
				$refund_amount += $refund__;
				$return_str .= '<tr>';
				$return_str .= '	<td align="right" valign="middle"><b>'.gmdate("F j, Y g:i A", strtotime($row_return['refund_update'])).' refunded:</b></td>';
				$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($refund__).'</td>';
				$return_str .= '</tr>';
			}
		}
		if($refund_amount != 0){
			$return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
			$return_str .= '<tr>';
			$return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
			$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($total+$refund_amount).'</td>';
			$return_str .= '</tr>';
		}
		
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
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		
		$load_myWallet=$this ->load_myWallet($okey,$total);
		$check_commission =(count($load_myWallet)>0)? array($load_myWallet):array();	
		return array(
			'check_commission' =>$check_commission,
			'card_number' =>$_card_number,
			'order_number' =>$_order_number,
			'date' =>$_date,
			'billingName' =>$_billingName,
			'billingAddress' =>$_billingAddress,
			'billingCity' =>$_billingCity,
			'billingPhone' =>$_billingPhone,
			'billingEmail' =>$_billingEmail,
			'shippingName' =>$_shippingName,
			'shippingAddress' =>$_shippingAddress,
			'shippingCity' =>$_shippingCity,
			'shippingPhone' =>$_shippingPhone,
			'ship_label' =>$_ship_label,
			'refund_button' =>$refund_button,
			'order_array' =>$order_array,
			'suptotal' =>'$'.number_format($subtotal,2),
			'Total' =>'$'.number_format($total,2),
			'Tax' =>'$'.number_format($tax,2),
			'shipping_fee' =>'$'.number_format($shipping_fee,2),
			'refund_row' =>$return_str,
			'order_status' =>isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
			'okey' =>$okey,
		);
	}//end admin_load_order
	
	public function admin_load_order_voucher($okey='')
	{
		$total = 0;
		$__order_status__ = $this->config->item('__order_voucher_status__');
		$__refund_reason__ = $this->config->item('__refund_reason__');
		
		$roles = $this->author->objlogin->role;
		$ong_chu = $this ->lib ->__loadBoss__();
		
		$oid = 0;
		$total = 0;
		$base_price = 0;
		$refund_button = '';
		$tblcontries = array();
		
		$_order_number = '';
		$_date = '';
		$_billingName = '';
		$_billingAddress = '';
		$_billingCity = '';
		$_billingPhone = '';
		$_billingEmail = '';
		$_card_number = '';
		$tax = 0;	
		$re = $this ->db->query("select * from tblcontries");
		foreach($re ->result_array() as $row)
		{
			$tblcontries[$row['code']] = $row['name'];	
		}
		$r = $this ->db->query("SELECT * FROM orders WHERE okey = '$okey'");
		if($r -> num_rows() >0){
			$row = $r ->row_array();
			$oid = $row['orderid'];
			$tax = $this ->loadOrderTax_voucher($oid,$roles['rid']);
			$date				= $row["order_date"];
			$card_number		= $row["card_number"];
			
			
			$_order_number = $okey;
			$_date = gmdate("m/d/Y",strtotime($date));
			$_billingName = $row["billing_name"];
			$_billingAddress =$row["billing_address"];
			$_billingCity =$row["billing_city"].', '.$row["billing_state"].' '.$row["billing_zip"].', '.(isset($tblcontries[$row["billing_country"]])?$tblcontries[$row["billing_country"]]:'') ;
			$_billingPhone = $row["billing_phone"];
			$_billingEmail =$row["billing_email"] ;
			$_card_number = 'XXXXX'.$card_number;
			
			if($row['r_ordernum'] != null && $row['r_ordernum'] != ''){
				$order_date_int = strtotime($row['order_date']);
				if($this ->lib ->getTimeGMT() - $order_date_int <= 30*24*60*60){
					$refund_button = '<input type="button" value="Refund" name="submit" class="button" style="margin-left:5px; margin-bottom:5px" onclick="window.location=\'index.php?q=store/orders/refunds/arefund&okey=@okey@\';" />';
				}	
			}
		}//if($r -> num_rows())
		
		$order_array = array();
		
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}	
		$arrPromotions = array();
		$re = $this ->db->query($sql_orders_promotions);
		foreach($re ->result_array() as $row)
		{
			$arrPromotions[] = $row;	
		}
		
		$check_vendor_login = false;
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$check_vendor_login = true;
		}

		$arr_manufacturers = array();
		$re_1 = $this ->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.product_type,items.origin FROM order_detais join items on order_detais.itemid = items.itm_id where items.product_type = 2 and order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
		foreach($re_1 ->result_array() as $row_1)
		{
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
		$subtotal = 0;
		$order_status_level = array();
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$itemid = $row["itemid"];
				$odetail = $row['id'];
				$itm_name = $row['itm_name'];
				$itm_model = $row['itm_model'];
				
				$quanlity_redeem = 0;
				$quanlity_no_redeem = 0;
				
				$re_2 = $this->db->query("select status from voucher where order_id = '".$row['orderid']."' and item_id = '$itemid'");
				foreach($re_2->result_array() as $row_2){
					if($row_2['status'] == 1){
						$quanlity_redeem++;
					}
					if($check_vendor_login){
						$order_status_level[] = 1;
					}else
						$order_status_level[] = $row_2['status'];
				}
				
				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");
			
				$arr_file = $this ->lib ->__loadFileProduct__($itemid);
				$_filename = $arr_file['file'];
				
				$manufacturer = 'My Store';
				$re_manu = $this ->db->query("select legal_business_name from manufacturers where uid = ".$row['uid']);
				if($re_manu -> num_rows() >0)
				{
					$row_manu = $re_manu ->row_array();
					$manufacturer = $row_manu['legal_business_name'];			
				}
				
				$attributes_str = '';
				$re_ = $this ->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
				foreach($re_ ->result_array() as $row_)
				{
					$attributes_str .= '<br><b>' .$row_['label']. ': </b>'.$row_['name'];
					if(is_numeric($row_['price']) && $row_['price'] > 0){
						$attributes_str .= '&nbsp;&nbsp;(+$'.number_format($row_['price'],2).')';	
					}
				}
				$check_shipping_free = false;
				$arr_show_promotions = array();
				
				if(count($arrPromotions) > 0){
					foreach($arrPromotions as $promotions){
						if($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']){
							$result_qty = $promotions['result_qty'];
							$qty_buy = $row['quality'] - $qty_refund;
							if($qty_buy >= $promotions['minqty']){
								$bac_qty = 0;
								if($promotions['minqty'] > 0)
									$bac_qty = floor($qty_buy / $promotions['minqty']);
								$qty_free = $bac_qty * $promotions['freeqty'];
								$result_qty -= $qty_free;
							}
							if($result_qty <= 0) $result_qty = 0;
							
							$manufacturer_free = 'My Store';
							$re_manu = $this ->db->query("select legal_business_name from manufacturers where uid = '".$promotions['manufacturer_id']."'");
							if($re_manu -> num_rows() >0)
							{
								$row_manu = $re_manu ->row_array();
								$manufacturer_free = $row_manu['legal_business_name'];			
							}
							
							$itm_model = '';
							$itm_name = '';

							$itemid = 0;
							$re_ = $this ->db->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
							foreach($re_ ->result_array() as $row_){
								$itm_name = $row_['itm_name'];
								$itm_model = $row_['itm_model'];
								$itemid = $row_['itm_id'];		
							}
							
							$arr_file = $this ->lib ->__loadFileProduct__($itemid);
							$_filename = $arr_file['file'];
							
							$desc_free = '<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.'</div>';
							$desc_free .= '<div style="clear:both; padding-top:10px">';
							$desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
							$desc_free .= '	<tr>';
							$desc_free .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
							$desc_free .= '	</tr>';
							$desc_free .= '</table>';
							$desc_free .= '</div>';
							
							$order_array[] = array(
								'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
								'desc' =>$desc_free,
								'total' =>'0.00',
								'price' =>'0.00',
								'qty_return' =>number_format($result_qty),
								'qty_buy' =>number_format($promotions['result_qty']),
							);
							
						}
						if($promotions['itm_key'] == $row['itm_key']){//0
							switch($promotions['promo_type']){
								case 1:
									$arr_show_promotions_step = array('promo_type'=>1, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;
								case 3:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>3, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;	
								case 4:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>4, 'discount_type'=>1, 'discount'=>1);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 4){
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;		
							}
						}//0
					}
				}
				$promotions_ = '';
				for($p = 0; $p < count($arr_show_promotions); $p++){
					switch($arr_show_promotions[$p]['promo_type']){
						case 1:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 3:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 4:
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
					}
				}
				
				$itemprice = $row['itemprice'];
				if($roles['rid'] == MANUFACTURER){
					$itemprice = $row['current_cost'];
					$promotions_ = '';
				}
				if($check_vendor_login){
					$row["quality"] = $quanlity_redeem;	
				}
				$amount = round($itemprice * $row["quality"], 2);
				$subtotal += $amount;
				
				$origin = '';
				if($row['origin'] != '' && $row['origin'] != null){
					$origin = '<br>'.$this ->lib->ConvertToHtml($row['origin']);	
				}
				
				$order_array[] = array(
					'id' =>$itemid,
					'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
					'desc' =>'<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.$origin.$attributes_str.'</div>'.$promotions_,
					'price' =>number_format($itemprice, 2),
					'qty_return' =>number_format($qty_refund),
					'qty_buy' =>number_format($row["quality"]),
					'total' =>number_format($amount, 2),
				);
			}//1
		}//0
		$tax = round($tax, 2);
		$total = $subtotal + $tax;
		
		$refund_amount = 0;
		$return_str = '';
		$re_refund = $this ->db->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
		foreach($re_refund ->result_array() as $row_return)
		{
			$reason = (isset($__refund_reason__[$row_return['refund_type']]))?$__refund_reason__[$row_return['refund_type']]:'None';
			$refund__ = $this ->loadAmountRefund2($row_return['id'], $roles['rid'], $this->author->objlogin->uid, $okey, $oid, $tax_pecen, $base_price);
			if(is_numeric($refund__)){
				$refund__ = $refund__ * (-1);
				$refund_amount += $refund__;
				$return_str .= '<tr>';
				$return_str .= '	<td align="right" valign="middle"><b>'.gmdate("F j, Y g:i A", strtotime($row_return['refund_update'])).' refunded:</b></td>';
				$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($refund__).'</td>';
				$return_str .= '</tr>';
			}
		}
		if($refund_amount != 0){
			$return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
			$return_str .= '<tr>';
			$return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
			$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($total+$refund_amount).'</td>';
			$return_str .= '</tr>';
		}
		
		$min_level = 1;
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
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		
		$load_myWallet=$this ->load_myWallet($okey,$total);
		$check_commission =(count($load_myWallet)>0)? array($load_myWallet):array();	
		return array(
			'check_commission' =>$check_commission,
			'card_number' =>$_card_number,
			'order_number' =>$_order_number,
			'date' =>$_date,
			'billingName' =>$_billingName,
			'billingAddress' =>$_billingAddress,
			'billingCity' =>$_billingCity,
			'billingPhone' =>$_billingPhone,
			'billingEmail' =>$_billingEmail,
			'refund_button' =>$refund_button,
			'order_array' =>$order_array,
			'suptotal' =>'$'.number_format($subtotal,2),
			'Total' =>'$'.number_format($total,2),
			'Tax' =>'$'.number_format($tax,2),
			'refund_row' =>$return_str,
			'order_status' =>isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
			'okey' =>$okey,
		);
	}//end admin_load_order_voucher
	
	private function loadOrderTax($orderid = '',$rid=0)
	{
		$check_vendor_login = false;
		$manu_sql = '';
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$check_vendor_login = true;
			$manu_sql = " and items.uid = '".$this->author->objlogin->uid."' ";
		}
		
		$tax = 0;
		$re = $this ->db ->query("select itm_id,itemprice,last_itemprice,quality,tax_persend,order_detais.current_cost,order_detais.orderid from order_detais join items on order_detais.itemid = items.itm_id where orderid=".$orderid." $manu_sql");
		if($re->num_rows() <=0) return $tax;
		foreach($re ->result_array() as $row){
			if($check_vendor_login){
				$memory_items = 0;
				$re_1 = $this->db->query("select status from voucher where item_id = '".$row['itm_id']."' and order_id = ".$row['orderid']);
				foreach($re_1->result_array() as $row_1){
					if($check_vendor_login == false || $row_1['status'] == 1){
						$memory_items++;	
					}
				}
				if($memory_items > 0){
					$row['quality'] = $memory_items;	
				}
			}
			$cost = ($rid == MANUFACTURER)?$row['current_cost']:$row['itemprice'];
			$tax += ($cost*$row['quality']*$row['tax_persend']/100);
		} 
		return $tax;
	}//end loadOrderTax function
	
	private function loadOrderTax_voucher($orderid = '',$rid=0)
	{
		$check_vendor_login = false;
		$manu_sql = '';
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$check_vendor_login = true;
			$manu_sql = " and items.uid = '".$this->author->objlogin->uid."' ";
		}
		
		$tax = 0;
		$re = $this ->db ->query("select items.itm_id,itemprice,last_itemprice,quality,tax_persend,order_detais.current_cost, order_detais.orderid from order_detais join items on order_detais.itemid = items.itm_id where items.product_type = 2 and orderid=".$orderid." $manu_sql");
		if($re->num_rows() <=0) return $tax;
		foreach($re ->result_array() as $row){
			if($check_vendor_login){
				$memory_items = 0;
				$re_1 = $this->db->query("select status from voucher where item_id = '".$row['itm_id']."' and order_id = ".$row['orderid']);
				foreach($re_1->result_array() as $row_1){
					if($check_vendor_login == false || $row_1['status'] == 1){
						$memory_items++;	
					}
				}
				if($memory_items > 0){
					$row['quality'] = $memory_items;	
				}
			}
			$cost = ($rid == MANUFACTURER)?$row['current_cost']:$row['itemprice'];
			$tax += ($cost*$row['quality']*$row['tax_persend']/100);
		} 
		return $tax;
	}//end loadOrderTax function
	
	public function print_load_order($okey)
	{
		$__order_status__ = $this->config->item('__order_status__');
		
		$roles = $this->author->objlogin->role;
		$oid = 0;
		$total = 0;
		$tax = 0;
		$shipping_fee = 0;
		$subtotal = 0;
		
		$tax_pecen = 0;
		$order_status = 1;
		$base_price = 0;
		
		$billing_Name		= '';
		$billing_Address	= '';
		$billing_City		= '';
		$billing_State		= '';
		$billing_Zip		= '';
		$billing_Phone		= '';
		$billing_Email		= '';

		$shipping_Name		= '';
		$shipping_Address	= '';
		$shipping_City		= '';
		$shipping_State		= '';
		$shipping_Zip		= '';
		$shipping_Phone		= '';
	
		$date				= '';
		$card_number		= '';
		$billingCity		= '';
		$shippingCity		= '';
		$tblcontries = array();
		$re = $this ->db ->query("select * from tblcontries");
		foreach($re ->result_array() as $row){
			$tblcontries[$row['code']] = $row['name'];	
		}
		$packages = array();
		$order_status_level = array();
		$r = $this ->db ->query("SELECT * FROM orders WHERE okey = '$okey'");
		if($r ->num_rows() >0){
			$row = $r ->row_array();
			$oid = $row['orderid'];
			$tax = $this ->loadOrderTax($oid,$roles['rid']);
			$billing_Name		= $row["billing_name"];
			$billing_Address	= $row["billing_address"];
			$billing_City		= $row["billing_city"];
			$billing_State		= $row["billing_state"];
			$billing_Zip		= $row["billing_zip"];
			$billing_Phone		= $row["billing_phone"];
			$billing_Email		= $row["billing_email"];
	
			$shipping_Name		= $row["shipping_name"];
			$shipping_Address	= $row["shipping_address"];
			$shipping_City		= $row["shipping_city"];
			$shipping_State		= $row["shipping_state"];
			$shipping_Zip		= $row["shipping_zip"];
			$shipping_Phone		= $row["shipping_phone"];
	
			$tax_pecen			= $row["order_tax"];
			$date				= $row["order_date"];
			$card_number		= $row["card_number"];
			$order_status		= $row['status'];
			$base_price			= $row['shipping_fee'];
			
			$billingCity = $billing_City.', '.$billing_State.' '.$billing_Zip.', '.(isset($tblcontries[$row["billing_country"]])?$tblcontries[$row["billing_country"]]:'');
			$shippingCity = $shipping_City.', '.$shipping_State.' '.$shipping_Zip.', '.(isset($tblcontries[$row["shipping_country"]])?$tblcontries[$row["shipping_country"]]:'');
			
		
			$ship_label = $this->database->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");
			$re_2 = $this ->db ->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
			foreach($re_2 ->result_array() as $row_2){
				$ship = 0;
				$re_3 = $this ->db ->query("select id from shipments where skey = '".$row_2['shipment_ID']."' and okey = '$okey'");
				if($re_3 ->num_rows() >0){
					$ship = 1;	
				}
				$items = array();
				$re_3 = $this ->db ->query("select product_id,qty from packages_items where package_id = ".$row_2['id']);
				foreach($re_3 ->result_array() as $row_3){
					$items[] = $row_3;	
				}
				$packages[] = array(
					'ship' => $ship,
					'items' => $items
				);	
			}
		}
		$order_array = array();
		$ong_chu = $this ->lib -> __loadBoss__();
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}
		
		$check_vendor_login = false;
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$check_vendor_login = true;
		}
		
		$arrPromotions = array();
		$re = $this ->db ->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db ->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.origin,items.itm_key,items.product_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
		foreach($re_1 -> result_array() as $row_1){
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
		$re_1 = $this ->db ->query("select * from orders_handling where oid = ".$oid);
		foreach($re_1 -> result_array() as $row_1){
			$arr_orders_handling[] = $row_1;	
		}
		$subtotal = 0;
		$shipping_fee = 0;
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$handling_fee_new = $base_price;
			foreach($arr_orders_handling as $oh){
				if($oh['uid'] == $arr_manufacturers[$m]['uid']){
					$handling_fee_new = $oh['handling'];
					break;	
				}		
			}
			$shipping_rate = $handling_fee_new;
			$count_ship_free = count($arr_manufacturers[$m]['items']);
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$itemid = $row["itemid"];
				$odetail = $row['id'];
				$itm_name = $row['itm_name'];
				$itm_model = $row['itm_model'];
				
				if($check_vendor_login){
					$memory_items = 0;
					$re_4 = $this->db->query("select status from voucher where item_id = $itemid and order_id = ".$row['orderid']);
					foreach($re_4->result_array() as $row_4){
						if($check_vendor_login == false || $row_4['status'] == 1){
							$memory_items++;	
						}
					}
					if($memory_items > 0){
						$row['quality'] = $memory_items;
					}
				}
				
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
				if($row['product_type'] == 0){
					if($qty_ship == $row["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
				}else{
					$status_item = 3;	
				}
				$order_status_level[] = $status_item;
				
				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");
			
				$arr_file = $this ->lib ->__loadFileProduct__($itemid);
				$_filename = $arr_file['file'];
				
				$manufacturer = 'My Store';
				$re_manu = $this ->db ->query("select legal_business_name from manufacturers where uid = ".$row['uid']);
				if($re_manu ->num_rows() >0){
					$row_manu = $re_manu ->row_array();
					$manufacturer = $row_manu['legal_business_name'];			
				}
				
				$attributes_str = '';
				$re_ = $this ->db ->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
				foreach($re_ ->result_array() as $row_){
					$attributes_str .= '<br><b>' .$row_['label']. ': </b>'.$row_['name'];
					if(is_numeric($row_['price']) && $row_['price'] > 0){
						$attributes_str .= '&nbsp;&nbsp;(+$'.number_format($row_['price'],2).')';	
					}
				}
				$check_shipping_free = false;
				$arr_show_promotions = array();
				$free_product_row = '';
				if(count($arrPromotions) > 0){
					foreach($arrPromotions as $promotions){
						if($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']){
							$result_qty = $promotions['result_qty'];
							$qty_buy = $row['quality'] - $qty_refund;
							if($qty_buy >= $promotions['minqty']){
								$bac_qty = 0;
								if($promotions['minqty'] > 0)
									$bac_qty = floor($qty_buy / $promotions['minqty']);
								$qty_free = $bac_qty * $promotions['freeqty'];
								$result_qty -= $qty_free;
							}
							if($result_qty <= 0) $result_qty = 0;
							
							$manufacturer_free = 'My Store';
							$re_manu = $this ->db ->query("select legal_business_name from manufacturers where uid = '".$promotions['manufacturer_id']."'");
							if($re_manu ->num_rows() >0){
								$row_manu = $re_manu ->row_array();
								$manufacturer_free = $row_manu['legal_business_name'];			
							}
							
							$itm_model = '';
							$itm_name = '';
							$itemid = 0;
							$re_ = $this ->db ->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
							foreach($re_ ->result_array() as $row_){
								$itm_name = $row_['itm_name'];
								$itm_model = $row_['itm_model'];
								$itemid = $row_['itm_id'];		
							}
							
							$arr_file = $this ->lib ->__loadFileProduct__($itemid);
							$_filename = $arr_file['file'];
							
							$desc_free = '<div style="clear:both"><b>'.$itm_name.'</b><BR><br><b>Model: </b>'.$itm_model.'</div>';
							$desc_free .= '<div style="clear:both; padding-top:10px">';
							$desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
							$desc_free .= '	<tr>';
							$desc_free .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
							$desc_free .= '	</tr>';
							$desc_free .= '</table>';
							$desc_free .= '</div>';
							
							$order_array[] = array(
								'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
								'desc' =>$desc_free,
								'total' =>'0.00',
								'price' =>'0.00',
								'qty_return' =>number_format($result_qty),
								'qty_buy' =>number_format($promotions['result_qty']),
							);
						}
						if($promotions['itm_key'] == $row['itm_key']){//0
							switch($promotions['promo_type']){
								case 1:
									$arr_show_promotions_step = array('promo_type'=>1, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;
								case 3:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>3, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;	
								case 4:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>4, 'discount_type'=>1, 'discount'=>1);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 4){
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;		
							}
						}//0
					}
				}
				$promotions_ = '';
				for($p = 0; $p < count($arr_show_promotions); $p++){
					switch($arr_show_promotions[$p]['promo_type']){
						case 1:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 3:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 4:
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
					}
				}
				$itemprice = ($roles['rid']== MANUFACTURER)? $row['current_cost']:$row['itemprice'];
				$amount = round($itemprice * $row["quality"], 2);
				$subtotal += $amount;
				
				if($row['product_type'] != 0){
					$row['shipping_fee'] = 0;
					$count_ship_free --;		
				}else{
					
					//$tax += $tax_pecen * $amount / 100;
					if($row['shipping_fee'] <= 0){
						$row['shipping_fee'] = 0;
						if($check_shipping_free == true) $count_ship_free --;	
					}
				}
				$shipping_rate += $row['shipping_fee'];	
				
				$origin = '';
				if($row['origin'] != '' && $row['origin'] != null){
					$origin = '<br>'.$this ->lib ->ConvertToHtml($row['origin']);	
				}
				
				$order_array[] = array(
					'id' =>$itemid,
					'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
					'desc' =>'<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.$origin.$attributes_str.'</div>'.$promotions_,
					'price' =>number_format($itemprice, 2),
					'qty_return' =>number_format($qty_refund),
					'qty' =>number_format($row["quality"]),
					'total' =>number_format($amount, 2),
				);
			}//1
			if($shipping_rate == $handling_fee_new && $count_ship_free == 0) $shipping_rate = 0;
			$shipping_fee += round($shipping_rate, 2);
		}//0
		$shipping_fee = round($shipping_fee, 2);
		$tax = round($tax, 2);
		$total = $subtotal + $tax + $shipping_fee;
		
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
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		
		$refund_amount = 0;
		$return_str = '';
		$re_refund = $this ->db ->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
		foreach($re_refund ->result_array() as $row_return){
			$reason = (isset($__refund_reason__[$row_return['refund_type']]))?$__refund_reason__[$row_return['refund_type']]:'None';
			$refund__ = $this ->loadAmountRefund2($row_return['id'], $roles['rid'], $this->author->objlogin->uid, $okey, $oid, $tax_pecen, $base_price);
			if(is_numeric($refund__)){
				$refund__ = $refund__ * (-1);
				$refund_amount += $refund__;
				$return_str .= '<tr>';
				$return_str .= '	<td align="right" valign="middle"><b>'.date("F j, Y g:i A", strtotime($row_return['refund_update'])).' refunded:</b></td>';
				$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($refund__).'</td>';
				$return_str .= '</tr>';
			}
		}
		if($refund_amount != 0){
			$return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
			$return_str .= '<tr>';
			$return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
			$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($total+$refund_amount).'</td>';
			$return_str .= '</tr>';
		}	
		$load_myWallet=$this ->load_myWallet($okey,$total);
		$check_commission =(count($load_myWallet)>0)? array($load_myWallet):array();

		return array(
			'check_commission' =>$check_commission,
			'order_number' =>$okey,
			'date' =>date("m/d/Y",strtotime($date)),
			'billingName' =>$billing_Name,
			'billingAddress' =>$billing_Address,
			'billingCity' =>$billingCity,
			'billingPhone' =>$billing_Phone,
			'billingEmail' =>$billing_Email,
			'shippingName' =>$shipping_Name,
			'shippingAddress' =>$shipping_Address,
			'shippingCity' =>$shippingCity,
			'shippingPhone' =>$shipping_Phone,
			'card_number' =>'xxxxxxxxxxxx'.$card_number,
			'ship_label' =>$ship_label,
			'order_status' =>(isset($oStatus[$order_status]))?$oStatus[$order_status]:'',
			'refund_row' =>$return_str,
			'okey' =>$okey,
			'suptotal' =>'$'.number_format($subtotal,2),
			'Total' =>'$'.number_format($total,2),
			'Tax' =>'$'.number_format($tax,2),
			'shipping_fee' =>'$'.number_format($shipping_fee,2),
			'order_status' =>isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
			'order_array' =>$order_array
		);
	}//end print_load_order function
	
	public function print_load_order_voucher($okey)
	{
		$__order_status__ = $this->config->item('__order_status__');
		
		$roles = $this->author->objlogin->role;
		
		$oid = 0;
		$total = 0;
		$tax = 0;
		$shipping_fee = 0;
		$subtotal = 0;
		
		$tax_pecen = 0;
		$order_status = 1;
		$base_price = 0;
		
		$billing_Name		= '';
		$billing_Address	= '';
		$billing_City		= '';
		$billing_State		= '';
		$billing_Zip		= '';
		$billing_Phone		= '';
		$billing_Email		= '';

		$shipping_Name		= '';
		$shipping_Address	= '';
		$shipping_City		= '';
		$shipping_State		= '';
		$shipping_Zip		= '';
		$shipping_Phone		= '';
	
		$date				= '';
		$card_number		= '';
		$billingCity		= '';
		$shippingCity		= '';
		$tblcontries = array();
		$re = $this ->db ->query("select * from tblcontries");
		foreach($re ->result_array() as $row){
			$tblcontries[$row['code']] = $row['name'];	
		}
		$packages = array();
		$order_status_level = array();
		$r = $this ->db ->query("SELECT * FROM orders WHERE okey = '$okey'");
		if($r ->num_rows() >0){
			$row = $r ->row_array();
			$oid = $row['orderid'];
			$tax = $this ->loadOrderTax_voucher($oid,$roles['rid']);
			$billing_Name		= $row["billing_name"];
			$billing_Address	= $row["billing_address"];
			$billing_City		= $row["billing_city"];
			$billing_State		= $row["billing_state"];
			$billing_Zip		= $row["billing_zip"];
			$billing_Phone		= $row["billing_phone"];
			$billing_Email		= $row["billing_email"];
	
			$shipping_Name		= $row["shipping_name"];
			$shipping_Address	= $row["shipping_address"];
			$shipping_City		= $row["shipping_city"];
			$shipping_State		= $row["shipping_state"];
			$shipping_Zip		= $row["shipping_zip"];
			$shipping_Phone		= $row["shipping_phone"];
	
			$tax_pecen			= $row["order_tax"];
			$date				= $row["order_date"];
			$card_number		= $row["card_number"];
			$order_status		= $row['status'];
			$base_price			= $row['shipping_fee'];
			
			$billingCity = $billing_City.', '.$billing_State.' '.$billing_Zip.', '.(isset($tblcontries[$row["billing_country"]])?$tblcontries[$row["billing_country"]]:'');
			$shippingCity = $shipping_City.', '.$shipping_State.' '.$shipping_Zip.', '.(isset($tblcontries[$row["shipping_country"]])?$tblcontries[$row["shipping_country"]]:'');
			
		
			$ship_label = $this->database->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");
			$re_2 = $this ->db ->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
			foreach($re_2 ->result_array() as $row_2){
				$ship = 0;
				$re_3 = $this ->db ->query("select id from shipments where skey = '".$row_2['shipment_ID']."' and okey = '$okey'");
				if($re_3 ->num_rows() >0){
					$ship = 1;	
				}
				$items = array();
				$re_3 = $this ->db ->query("select product_id,qty from packages_items where package_id = ".$row_2['id']);
				foreach($re_3 ->result_array() as $row_3){
					$items[] = $row_3;	
				}
				$packages[] = array(
					'ship' => $ship,
					'items' => $items
				);	
			}
		}
		$order_array = array();
		$ong_chu = $this ->lib -> __loadBoss__();
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}
		$arrPromotions = array();
		$re = $this ->db ->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		
		$check_vendor_login = false;
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$check_vendor_login = true;
		}
		
		$arr_manufacturers = array();
		$re_1 = $this ->db ->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.origin,items.itm_key,items.product_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
		foreach($re_1 -> result_array() as $row_1){
			$check_exist = false;
			if($row_1['product_type'] == 2){
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
		}
		$arr_orders_handling = array();
		$re_1 = $this ->db ->query("select * from orders_handling where oid = ".$oid);
		foreach($re_1 -> result_array() as $row_1){
			$arr_orders_handling[] = $row_1;	
		}
		$subtotal = 0;
		$shipping_fee = 0;
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$handling_fee_new = $base_price;
			foreach($arr_orders_handling as $oh){
				if($oh['uid'] == $arr_manufacturers[$m]['uid']){
					$handling_fee_new = $oh['handling'];
					break;	
				}		
			}
			$shipping_rate = $handling_fee_new;
			$count_ship_free = count($arr_manufacturers[$m]['items']);
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$itemid = $row["itemid"];
				$odetail = $row['id'];
				$itm_name = $row['itm_name'];
				$itm_model = $row['itm_model'];
				if($check_vendor_login){
					$memory_items = 0;
					$re_4 = $this->db->query("select status from voucher where item_id = $itemid and order_id = ".$row['orderid']);
					foreach($re_4->result_array() as $row_4){
						if($check_vendor_login == false || $row_4['status'] == 1){
							$memory_items++;	
						}
					}
					if($memory_items > 0){
						$row['quality'] = $memory_items;
					}
				}
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
				if($row['product_type'] == 0){
					if($qty_ship == $row["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
				}else{
					$status_item = 3;	
				}
				$order_status_level[] = $status_item;

				$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");
			
				$arr_file = $this ->lib ->__loadFileProduct__($itemid);
				$_filename = $arr_file['file'];
				
				$manufacturer = 'My Store';
				$re_manu = $this ->db ->query("select legal_business_name from manufacturers where uid = ".$row['uid']);
				if($re_manu ->num_rows() >0){
					$row_manu = $re_manu ->row_array();
					$manufacturer = $row_manu['legal_business_name'];			
				}
				
				$attributes_str = '';
				$re_ = $this ->db ->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
				foreach($re_ ->result_array() as $row_){
					$attributes_str .= '<br><b>' .$row_['label']. ': </b>'.$row_['name'];
					if(is_numeric($row_['price']) && $row_['price'] > 0){
						$attributes_str .= '&nbsp;&nbsp;(+$'.number_format($row_['price'],2).')';	
					}
				}
				$check_shipping_free = false;
				$arr_show_promotions = array();
				$free_product_row = '';
				if(count($arrPromotions) > 0){
					foreach($arrPromotions as $promotions){
						if($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']){
							$result_qty = $promotions['result_qty'];
							$qty_buy = $row['quality'] - $qty_refund;
							if($qty_buy >= $promotions['minqty']){
								$bac_qty = 0;
								if($promotions['minqty'] > 0)
									$bac_qty = floor($qty_buy / $promotions['minqty']);
								$qty_free = $bac_qty * $promotions['freeqty'];
								$result_qty -= $qty_free;
							}
							if($result_qty <= 0) $result_qty = 0;
							
							$manufacturer_free = 'My Store';
							$re_manu = $this ->db ->query("select legal_business_name from manufacturers where uid = '".$promotions['manufacturer_id']."'");
							if($re_manu ->num_rows() >0){
								$row_manu = $re_manu ->row_array();
								$manufacturer_free = $row_manu['legal_business_name'];			
							}
							
							$itm_model = '';
							$itm_name = '';
							$itemid = 0;
							$re_ = $this ->db ->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
							foreach($re_ ->result_array() as $row_){
								$itm_name = $row_['itm_name'];
								$itm_model = $row_['itm_model'];
								$itemid = $row_['itm_id'];		
							}
							
							$arr_file = $this ->lib ->__loadFileProduct__($itemid);
							$_filename = $arr_file['file'];
							
							$desc_free = '<div style="clear:both"><b>'.$itm_name.'</b><BR><br><b>Model: </b>'.$itm_model.'</div>';
							$desc_free .= '<div style="clear:both; padding-top:10px">';
							$desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
							$desc_free .= '	<tr>';
							$desc_free .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
							$desc_free .= '	</tr>';
							$desc_free .= '</table>';
							$desc_free .= '</div>';
							
							$order_array[] = array(
								'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
								'desc' =>$desc_free,
								'total' =>'0.00',
								'price' =>'0.00',
								'qty_return' =>number_format($result_qty),
								'qty_buy' =>number_format($promotions['result_qty']),
							);
						}
						if($promotions['itm_key'] == $row['itm_key']){//0
							switch($promotions['promo_type']){
								case 1:
									$arr_show_promotions_step = array('promo_type'=>1, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;
								case 3:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>3, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;	
								case 4:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>4, 'discount_type'=>1, 'discount'=>1);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 4){
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;		
							}
						}//0
					}
				}
				$promotions_ = '';
				for($p = 0; $p < count($arr_show_promotions); $p++){
					switch($arr_show_promotions[$p]['promo_type']){
						case 1:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 3:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 4:
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
					}
				}
				$itemprice = ($roles['rid']== MANUFACTURER)? $row['current_cost']:$row['itemprice'];
				$amount = round($itemprice * $row["quality"], 2);
				$subtotal += $amount;
				
				if($row['product_type'] != 0){
					$row['shipping_fee'] = 0;
					$count_ship_free --;		
				}else{
					
					//$tax += $tax_pecen * $amount / 100;
					if($row['shipping_fee'] <= 0){
						$row['shipping_fee'] = 0;
						if($check_shipping_free == true) $count_ship_free --;	
					}
				}
				$shipping_rate += $row['shipping_fee'];	
				
				$origin = '';
				if($row['origin'] != '' && $row['origin'] != null){
					$origin = '<br>'.$this ->lib ->ConvertToHtml($row['origin']);	
				}
				
				$order_array[] = array(
					'id' =>$itemid,
					'img' =>$this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
					'desc' =>'<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.$origin.$attributes_str.'</div>'.$promotions_,
					'price' =>number_format($itemprice, 2),
					'qty_return' =>number_format($qty_refund),
					'qty' =>number_format($row["quality"]),
					'total' =>number_format($amount, 2),
				);
			}//1
			if($shipping_rate == $handling_fee_new && $count_ship_free == 0) $shipping_rate = 0;
			$shipping_fee += round($shipping_rate, 2);
		}//0
		$shipping_fee = round($shipping_fee, 2);
		$tax = round($tax, 2);
		$total = $subtotal + $tax + $shipping_fee;
		
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
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		
		$refund_amount = 0;
		$return_str = '';
		$re_refund = $this ->db ->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
		foreach($re_refund ->result_array() as $row_return){
			$reason = (isset($__refund_reason__[$row_return['refund_type']]))?$__refund_reason__[$row_return['refund_type']]:'None';
			$refund__ = $this ->loadAmountRefund2($row_return['id'], $roles['rid'], $this->author->objlogin->uid, $okey, $oid, $tax_pecen, $base_price);
			if(is_numeric($refund__)){
				$refund__ = $refund__ * (-1);
				$refund_amount += $refund__;
				$return_str .= '<tr>';
				$return_str .= '	<td align="right" valign="middle"><b>'.date("F j, Y g:i A", strtotime($row_return['refund_update'])).' refunded:</b></td>';
				$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($refund__).'</td>';
				$return_str .= '</tr>';
			}
		}
		if($refund_amount != 0){
			$return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
			$return_str .= '<tr>';
			$return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
			$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($total+$refund_amount).'</td>';
			$return_str .= '</tr>';
		}	
		$load_myWallet=$this ->load_myWallet($okey,$total);
		$check_commission =(count($load_myWallet)>0)? array($load_myWallet):array();
		return array(
			'check_commission' =>$check_commission,
			'order_number' =>$okey,
			'date' =>date("m/d/Y",strtotime($date)),
			'billingName' =>$billing_Name,
			'billingAddress' =>$billing_Address,
			'billingCity' =>$billingCity,
			'billingPhone' =>$billing_Phone,
			'billingEmail' =>$billing_Email,
			'shippingName' =>$shipping_Name,
			'shippingAddress' =>$shipping_Address,
			'shippingCity' =>$shippingCity,
			'shippingPhone' =>$shipping_Phone,
			'card_number' =>'xxxxxxxxxxxx'.$card_number,
			'ship_label' =>$ship_label,
			'order_status' =>(isset($oStatus[$order_status]))?$oStatus[$order_status]:'',
			'refund_row' =>$return_str,
			'okey' =>$okey,
			'suptotal' =>'$'.number_format($subtotal,2),
			'Total' =>'$'.number_format($total,2),
			'Tax' =>'$'.number_format($tax,2),
			'shipping_fee' =>'$'.number_format($shipping_fee,2),
			'order_status' =>isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
			'order_array' =>$order_array
		);
	}//end print_load_order function
	
	private function load_myWallet($okey='',$total=0)
	{
		$re = $this ->db ->query("select * from payments_orders join payments on payments_orders.pkey = payments.pkey where payments.role = ".Sale_Representatives." and payments_orders.okey=".$okey);
		if($re->num_rows() <=0) return array();
		$row = $re->row_array();
		$roles = $this->author->objlogin->role;
		//$this->tax = $this->loadOrderTax_voucher($this->oid,$roles['rid']);
		$total_last = $total;
		$my_wallet = number_format($row['pay'] ,2);
		if($my_wallet > $total) $my_wallet = $total;
                 $total_last += $this->shipping_fee + $this->tax-$my_wallet;	
		return array(
			'my_wallet' =>'$'.number_format($my_wallet,2),
			'total_last' =>'$'.number_format($total_last,2),
		);
	}//end load_myWallet function
	
	public function mailinvoice_load($okey='')
	{
		$total=0;
		$__order_status__ =$this->config->item('__order_status__');
		$roles = $this ->author ->objlogin ->role;
		$ong_chu =$this->lib-> __loadBoss__();
		$oid = 0;
	
		$total = 0;
		$tax = 0;
		$shipping_fee = 0;
		$subtotal = 0;
		
		$tax_pecen = 0;
		$order_status = 1;
		$base_price = 0;
		
		$billing_Name		= '';
		$billing_Address	= '';
		$billing_City		= '';
		$billing_State		= '';
		$billing_Zip		= '';
		$billing_Phone		= '';
		$billing_Email		= '';

		$shipping_Name		= '';
		$shipping_Address	= '';
		$shipping_City		= '';
		$shipping_State		= '';
		$shipping_Zip		= '';
		$shipping_Phone		= '';
	
		$date				= '';
		$card_number		= '';
		$billingCity		= '';
		$shippingCity		= '';
		
		$ship_label ='';
		
		$tblcontries = array();
		$re = $this ->db->query("select * from tblcontries");
		foreach($re ->result_array() as $row){
			$tblcontries[$row['code']] = $row['name'];	
		}
		$packages = array();
		$order_status_level = array();
		$r = $this ->db->query("SELECT * FROM orders WHERE okey = '$okey'");
		if($r->num_rows() >0){
			$row = $r ->row_array();
			$oid = $row['orderid'];
			$tax = $this ->loadOrderTax($oid,$roles['rid']);
			$billing_Name		= $row["billing_name"];
			$billing_Address	= $row["billing_address"];
			$billing_City		= $row["billing_city"];
			$billing_State		= $row["billing_state"];
			$billing_Zip		= $row["billing_zip"];
			$billing_Phone		= $row["billing_phone"];
			$billing_Email		= $row["billing_email"];
	
			$shipping_Name		= $row["shipping_name"];
			$shipping_Address	= $row["shipping_address"];
			$shipping_City		= $row["shipping_city"];
			$shipping_State		= $row["shipping_state"];
			$shipping_Zip		= $row["shipping_zip"];
			$shipping_Phone		= $row["shipping_phone"];
	
			$tax_pecen			= $row["order_tax"];
			$date				= date("m/d/Y",strtotime($row["order_date"]));
			$card_number		= 'xxxxxxxxxxxx'.substr($row["card_number"], strlen($row["card_number"])-4);
			$order_status		= $row['status'];
			$base_price			= $row['shipping_fee'];
			$billingCity = $billing_City.', '.$billing_State.' '.$billing_Zip.', '.(isset($tblcontries[$row["billing_country"]])?$tblcontries[$row["billing_country"]]:'');
			$shippingCity = $shipping_City.', '.$shipping_State.' '.$shipping_Zip.', '.(isset($tblcontries[$row["shipping_country"]])?$tblcontries[$row["shipping_country"]]:'');
			
			$ship_label = $this ->database ->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");
			$re_2 = $this ->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
			foreach($re_2 ->result_array() as $row_2){
				$ship = 0;
				$re_3 = $this ->db->query("select id from shipments where skey = '".$row_2['shipment_ID']."' and okey = '$okey'");
				if($re_3 ->num_rows() >0){
					$row_3 = $re_3 ->row_array();
					$ship = 1;	
				}
				$items = array();
				$re_3 = $this ->db->query("select product_id,qty from packages_items where package_id = ".$row_2['id']);
				foreach($re_3 ->result_array() as $row_3){
					$items[] = $row_3;	
				}
				$packages[] = array(
					'ship' => $ship,
					'items' => $items
				);	
			}
		}
		
		$order_array = array();		
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == 5){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}
		$arrPromotions = array();
		$re = $this ->db->query($sql_orders_promotions);
		foreach($re ->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.product_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
		foreach($re_1 ->result_array() as $row_1){
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
		$re_1 = $this ->db->query("select * from orders_handling where oid = ".$oid);
		foreach($re_1 ->result_array() as $row_1){
			$arr_orders_handling[] = $row_1;	
		}
		$subtotal = 0;
		$shipping_fee = 0;
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$handling_fee_new = $base_price;
			foreach($arr_orders_handling as $oh){
				if($oh['uid'] == $arr_manufacturers[$m]['uid']){
					$handling_fee_new = $oh['handling'];
					break;	
				}		
			}
			$shipping_rate = $handling_fee_new;
			$count_ship_free = count($arr_manufacturers[$m]['items']);
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$itemid = $row["itemid"];
				$odetail = $row['id'];
				$itm_name = $row['itm_name'];
				$itm_model = $row['itm_model'];
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
				if($row['product_type'] == 0){
					if($qty_ship == $row["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
				}else{
					$status_item = 3;	
				}
				$order_status_level[] = $status_item;
				
				$qty_refund = $this ->database ->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");
				$arr_file = $this ->lib ->__loadFileProduct__($itemid);
				$_filename = $arr_file['file'];
				
				$manufacturer = 'My Store';
				$re_manu = $this ->db->query("select legal_business_name from manufacturers where uid = ".$row['uid']);
				if($re_manu ->num_rows() >0){
					$row_manu = $re_manu ->row_array();
					$manufacturer = $row_manu['legal_business_name'];			
				}
				
				$attributes_str = '';
				$re_ = $this ->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
				foreach($re_ ->result_array() as $row_){
					$attributes_str .= '<br><b>' .$row_['label']. ': </b>'.$row_['name'];
					if(is_numeric($row_['price']) && $row_['price'] > 0){
						$attributes_str .= '&nbsp;&nbsp;(+$'.number_format($row_['price'],2).')';	
					}
				}
				$check_shipping_free = false;
				$arr_show_promotions = array();
				$free_product_row = '';
				if(count($arrPromotions) > 0){
					foreach($arrPromotions as $promotions){
						if($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']){
							$result_qty = $promotions['result_qty'];
							$qty_buy = $row['quality'] - $qty_refund;
							if($qty_buy >= $promotions['minqty']){
								$bac_qty = 0;
								if($promotions['minqty'] > 0)
									$bac_qty = floor($qty_buy / $promotions['minqty']);
								$qty_free = $bac_qty * $promotions['freeqty'];
								$result_qty -= $qty_free;
							}
							if($result_qty <= 0) $result_qty = 0;
							
							$manufacturer_free = 'My Store';
							$re_manu = $this ->db->query("select legal_business_name from manufacturers where uid = '".$promotions['manufacturer_id']."'");
							if($re_manu ->num_rows() >0){
								$row_manu = $re_manu ->row_array();
								$manufacturer_free = $row_manu['legal_business_name'];			
							}
							
							$itm_model = '';
							$itm_name = '';
							$itemid = 0;
							$re_ = $this ->db->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
							foreach($re_ ->result_array() as $row_){
								$itm_name = $row_['itm_name'];
								$itm_model = $row_['itm_model'];
								$itemid = $row_['itm_id'];		
							}
							
							$arr_file = $this ->lib ->__loadFileProduct__($itemid);
							$_filename = $arr_file['file'];
                                                     
							
							$desc_free = '<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.'</div>';
							$desc_free .= '<div style="clear:both; padding-top:10px">';
							$desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
							$desc_free .= '	<tr>';
							$desc_free .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
							$desc_free .= '	</tr>';
							$desc_free .= '</table>';
							$desc_free .= '</div>';
							
							$order_array[] = array(
								'img' => $this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
								'desc' => $desc_free,
								'total' => '0.00',
								'price' => '0.00',
								'qty_return' => number_format($result_qty),
								'qty_buy' => number_format($promotions['result_qty']),
							);
						}
						if($promotions['itm_key'] == $row['itm_key']){//0
							switch($promotions['promo_type']){
								case 1:
									$arr_show_promotions_step = array('promo_type'=>1, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;
								case 3:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>3, 'discount_type'=>$promotions['discount_type'], 'discount'=>$promotions['discount']);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']){
											$arr_show_promotions[$p]['discount'] += $promotions['discount'];
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;	
								case 4:
									$check_shipping_free = true;
									$arr_show_promotions_step = array('promo_type'=>4, 'discount_type'=>1, 'discount'=>1);
									$check_exist_pro = false;
									for($p = 0; $p < count($arr_show_promotions); $p++){
										if($arr_show_promotions[$p]['promo_type'] == 4){
											$check_exist_pro = true;
											break;	
										}	
									}
									if($check_exist_pro == false) $arr_show_promotions[] = $arr_show_promotions_step;
									break;		
							}
						}//0
					}
				}
				$promotions_ = '';
				for($p = 0; $p < count($arr_show_promotions); $p++){
					switch($arr_show_promotions[$p]['promo_type']){
						case 1:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 3:
							$discount_str = '';
							if($arr_show_promotions[$p]['discount_type'] == 0){
								$discount_str = number_format($arr_show_promotions[$p]['discount']).'%';	
							}else{
								$discount_str = '$'.number_format($arr_show_promotions[$p]['discount'], 2);	
							}
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: '.$discount_str.'</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
						case 4:
							$promotions_ .= '<div style="clear:both; padding-top:10px">';
							$promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
							$promotions_ .= '	<tr>';
							$promotions_ .= '		<td align="left" valign="top"><img src="'.$this ->system ->__path_to_theme__().'/images/ico-gift.png" border="0" width="20px" /></td>';
							$promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
							$promotions_ .= '	</tr>';
							$promotions_ .= '</table>';
							$promotions_ .= '</div>';
							break;
					}
				}
				
				if($row['shipping_fee'] <= 0){
					$row['shipping_fee'] = 0;
					if($check_shipping_free == true) $count_ship_free --;	
				}
				$itemprice = $row['itemprice'];
				$shipping_rate += $row['shipping_fee'];	
				$amount = round($itemprice * $row["quality"], 2);
				$subtotal += $amount;
				
				$order_array[] = array(
					'id' => $itemid,
					'img' => $this->system->URL_server__() ."shopping/data/img/thumb/".$_filename,
					'desc' => '<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.$attributes_str.'</div>'.$promotions_,
					'price' => number_format($itemprice, 2),
					'qty_return' => number_format($qty_refund),
					'qty' => number_format($row["quality"]),
					'total' => number_format($amount, 2),
				);
			}//1
			if($shipping_rate == $handling_fee_new && $count_ship_free == 0) $shipping_rate = 0;
			$shipping_fee += round($shipping_rate, 2);
		}//0
		$shipping_fee = round($shipping_fee, 2);
		//$tax = round($tax_pecen*$subtotal/100, 2);
		$total = $subtotal + $tax + $shipping_fee;
		
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
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		
		$refund_amount = 0;
		$return_str = '';
		$re_refund = $this ->db->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
		foreach($re_refund->result_array() as $row_return){
			$reason = (isset($__refund_reason__[$row_return['refund_type']]))?$__refund_reason__[$row_return['refund_type']]:'None';
			$refund__ = $this ->loadAmountRefund2($row_return['id'], $roles['rid'], $_SESSION['ses_login']->uid, $okey, $oid, $tax_pecen, $base_price);
			if(is_numeric($refund__)){
				$refund__ = $refund__ * (-1);
				$refund_amount += $refund__;
				$return_str .= '<tr>';
				$return_str .= '	<td align="right" valign="middle"><b>'.date("F j, Y g:i A", strtotime($row_return['refund_update'])).' refunded:</b></td>';
				$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($refund__).'</td>';
				$return_str .= '</tr>';
			}
		}
		if($refund_amount != 0){
			$return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
			$return_str .= '<tr>';
			$return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
			$return_str .= '	<td align="right" valign="middle" style="padding-left:20px">'.$this ->lib ->showMoney($total+$refund_amount).'</td>';
			$return_str .= '</tr>';
		}
		$load_myWallet=$this ->load_myWallet($okey,$total);
		$check_commission =(count($load_myWallet)>0)? array($load_myWallet):array();
		return  array(
			'check_commission' =>$check_commission,
			'order_array' =>$order_array,
			'okey' =>$okey,
			'refund_row' =>$return_str,
			'order_number' => $okey,
			'date' => $date,
			'billingName' => $billing_Name,
			'billingAddress' => $billing_Address,
			'billingCity' => $billingCity,
			'billingPhone' => $billing_Phone ,
			'billingEmail' => $billing_Email,
			'shippingName' => $shipping_Name,
			'shippingAddress' => $shipping_Address,
			'shippingCity' => $shippingCity,
			'shippingPhone' => $shipping_Phone,
			'card_number' => $card_number,
			'ship_label' => $ship_label,
			'suptotal' =>'$'.number_format($subtotal,2),
			'Total' => '$'.number_format($total,2),
			'Tax' =>'$'.number_format($tax,2),
			'shipping_fee' =>'$'.number_format($shipping_fee,2),
			'order_status' => isset($__order_status__[$min_level])?$__order_status__[$min_level]:'null',
		);
	}//end mailinvoice_load function
	
	public function odetails_submitOrder()
	{
		$ong_chu = $this ->lib -> __loadBoss__();
		$order_status = isset($_POST['order_status'])?$_POST['order_status']:1;
		$okey = isset($_POST['okey'])?$_POST['okey']:'';
		$roles = $this ->author ->objlogin ->role;
		$sql_manufacturer = '';
		if($roles['rid'] == 5){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
		}
		$r = $this ->db->query("SELECT orderid FROM orders WHERE okey = '$okey'");
		if($r ->num_rows() >0){
			$row = $r ->row_array();
			$oid = $row['orderid'];
			$strSql = "SELECT order_detais.id FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC";
			$r_2 = $this ->db->query($strSql);
			foreach($r_2 ->result_array() as $row_2){
				$this ->db->where("id",$row_2['id']);
				$this ->db->update("order_detais", array('Status'=>$order_status));			
			}	
		}
		return array('error'=>'');
	}//end odetails_submitOrder function
	
	public function checkDonation($okey='')
	{
		$check = false;
		$re = $this ->db->query("SELECT order_detais.itemid FROM order_detais JOIN orders ON order_detais.orderid = orders.orderid WHERE orders.okey = ".$this ->lib ->escape($okey));
		foreach($re->result_array() as $row){
			$re = $this ->db ->query("select items.product_type, users_roles.rid from items join users_roles on items.uid = users_roles.uid where items.itm_id = ".$row['itemid']);
			$row = $re->row_array();
			if($row['product_type']!=1 || $row['rid'] != CHARITY) return false;
			if(!$check) $check = true;
		}
		return $check;
	}//end checkDonation function
	
	public function checkProductOrder($okey=''){
		$manu_sql = '';
		$roles = $this->author->objlogin->role;
		if($roles['rid'] == MANUFACTURER || $roles['rid'] == CHARITY){
			$manu_sql = " and items.uid = '".$this->author->objlogin->uid."' ";
		}
		
		$re_1 = $this ->db->query("SELECT order_detais.itemid as item_id_ FROM order_detais JOIN orders ON order_detais.orderid = orders.orderid WHERE orders.okey = ".$this ->lib ->escape($okey));
		foreach($re_1->result_array() as $row_1){
			$re = $this ->db ->query("select items.product_type from items where items.itm_id = '".$row_1['item_id_']."' $manu_sql");
			foreach($re->result_array() as $row){
				if($row['product_type'] == 0){
					return true;
				}
			}
		}
		return false;	
	}
}
