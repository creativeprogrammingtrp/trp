<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charities_model extends CI_Model 
{
	public function update_charities()
	{
		$error = '';
		$title = ($this ->input ->post("title"))?$this ->lib ->escape($this ->input ->post("title")):'';
		$charity = array
		(
			'name_content'	=> 'charity',
			'title' => $title,
			'content' => $this ->lib ->FCKToSQL($this ->input ->post('content'))
		);
		$result = $this ->database ->db_result("select id from web_content where name_content = 'charity'");
		if($result > 0)
			$this ->db ->update('web_content', $charity,"name_content = 'charity'");
		else
			$this ->db ->insert('web_content', $charity);
		return $error;	
	}
	
	public function load_content()
	{
		$title = '';
		$body = '';
		$re = $this ->db ->query("select * from web_content where name_content = 'charity'");
		if($re -> num_rows() >0)
		{
			$row = $re ->row_array();	
			$title = $row['title'];
			$body = $this ->lib ->SQLToFCK($row['content']);	
		}
		$data = array
		(
			"title" => $title,
			"content" => $body
		);
		return $data;	
	}
	
	public function loadCharities()
	{
		$charities = array();
		$maxlength =0;
		$YTD_earnings = 0;
		$query = $this ->db ->query("select count(charities.legal_business_id) as count_charities from charities join users on charities.uid = users.uid where users.status <> -1 and charities.trust = 0");
		if($query ->num_rows() >0) $maxlength = $query ->row() ->count_charities;
		if(!$maxlength) return $charities;
		$re_2 = $this ->db ->query("select commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id is NULL");
		foreach($re_2->result_array() as $row_2)
		{
			$query = $this ->db->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
			$row = $query->row_array();
			$qty_refund  = count($row)>0? $row['sum_qty']:0; 
			$qty_buy = $row_2['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$YTD_sales = round($row_2['itemprice']*$qty_buy, 4);
			$commission = $row_2['commission'] * $YTD_sales / 100;
			$YTD_earnings += round($commission / $maxlength, 4);
		}
		$arr_Affiliate = array();
		$sql = "select charities.website,charities.legal_business_id,charities.legal_business_name,charities.trust,charities.description from charities join users on charities.uid = users.uid where users.status <> -1 and charities.featured = 1";
		$re = $this ->db ->query($sql);
		foreach($re->result_array() as $row)
		{
			$legal_business_id = $row['legal_business_id'];
			$price = 0;
			if($row['trust'] == 0)
				$price = $YTD_earnings;
			
			$re_2 = $this ->db ->query("select commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id = '$legal_business_id'");
			foreach($re_2->result_array() as $row_2)
			{
				$query =$this ->db ->query("select sum(qty) as sum_qty from odetail_return where odetail = ".$row_2['id']." and status = 1");
				$r = $query->row_array();
				$qty_refund  = count($r)>0? $r['sum_qty']:0; 
				$qty_buy = $row_2['quality'] - $qty_refund;
				if($qty_buy < 0) $qty_buy = 0;
				$YTD_sales = round($row_2['itemprice']*$qty_buy, 4);
				$price += round($row_2['commission'] * $YTD_sales / 100, 4);
			}
			$query = $this ->db ->query("select sum(pay) as sum_pay from payments where legal_business_id = '$legal_business_id' and role = ".CHARITY);
			$r = $query->row_array();
			$paid  = count($r)>0? $r['sum_pay']:0; 
			$query = $this ->db ->query("select sum(raise) as sum_raise from raises where legal_business_id = '$legal_business_id' and role = ".CHARITY);
			$r = $query->row_array();
			$price = count($r)>0? $price+$r['sum_raise']:$price;
			
			$price += $this ->balance_from_order($legal_business_id);
			
			$price = (float)($price - $paid);
			if($price < 0) $price = 0;
						
			$re_1 = $this ->db ->query("select cid,akey,checks_file from checks where akey = '$legal_business_id'");
			$checks = array();
			foreach($re_1 ->result_array() as $row_1)
			{
				$checks[] = array
				(
					'cid' =>$row_1['cid'],
					'checks_file' => $this->system->URL_server__()."resource/checks/thumb/".$row_1['checks_file'],
					'checks_rel' => $this->system->URL_server__()."resource/checks/".$row_1['checks_file'],
				);
			}
						
			$charities[] = array
			(
				'legal_business_name' =>$this ->lib ->ConvertToHTML(ucfirst($row['legal_business_name'])),
				'price' =>number_format($price, 2),
				'paid' =>number_format($paid, 2),
				'website' =>$this ->buildUrlWS($row['website']),
				'description' =>$this ->lib ->ConvertToHTML($row['description']),
				'checks' => $checks
			);
		}
		return $charities;
	}//end loadCharities function
	
	function buildUrlWS($host)
	{
		$domain = "";
		if(substr($host,0,7)!= 'http://' && substr($host,0,8)!= 'https://')
			$domain = "http://";
		$domain .= $host;
		return $domain;
	}//end buildUrlWS function
	
	private function balance_from_order($key){
		$total_balance = 0;
		$arr_orders = array();
		$maxlength = 0;
		$sql = "select charities.uid,charities.legal_business_id from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$re = $this->db->query($sql);
		
		if($re->num_rows() > 0){
			$row = $re->row_array(); 
			$max_sql = "select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name ";
			$max_sql .= "from orders join order_detais join items on orders.orderid = order_detais.orderid and order_detais.itemid = items.itm_id ";
			$max_sql .= "where items.uid = ".$row['uid']." ORDER BY orders.orderid DESC";
			$re_2 = $this->db->query($max_sql);
			foreach($re_2->result_array() as $row_2){
				$status_order = $row_2['status'];
				$okey = $row_2['okey'];
				//$tax_pecen	= $row_2["order_tax"];
				$tax = 0;
				
				$shipping_fee = 0;
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
						
						$status_item = 3;
						$order_status_level[] = $status_item;
						
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
}//end Charities class
