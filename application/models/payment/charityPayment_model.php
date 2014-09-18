<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CharityPayment_model extends CI_Model 
{
	public function loadData()
	{
		$key = (!empty($_GET['key']))? $_GET['key'] :'';
		
		$legal_business_name = '';
		$legal_business_id = '';
		$paid = 0;
		$total = 0;
		
		$sql = "select count(charities.legal_business_id) from charities join users on charities.uid = users.uid where users.status <> -1 and charities.trust = 0";
		$maxlength = db_result(db_query($sql));
		$total_commission = 0;
		$trust = db_result(db_query("select charities.trust from charities join users on charities.uid = users.uid where charities.legal_business_id = '".$key."'"));	
		if($trust != 1){
			$re_2 = db_query("select commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id is NULL");
			while($row_2 = db_fetch_array($re_2)){
				$qty_refund = db_result(db_query("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1"));
				$qty_buy = $row_2['quality'] - $qty_refund;
				if($qty_buy < 0) $qty_buy = 0;
				$YTD_sales = round($row_2['itemprice']*$qty_buy, 4);
				$total_commission += round($row_2['commission'] * $YTD_sales / (100*$maxlength), 4);	
			}
			$ad_commission = db_result(db_query("select sum(ad_commission.price) from ad_commission join ad_orders on ad_orders.okey = ad_commission.okey where ad_commission.rid = 8"));
			$total_commission += round($ad_commission / $maxlength, 4);
		}
		$re_2 = db_query("select commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 8 and commission_charities.legal_business_id = '$key'");
		while($row_2 = db_fetch_array($re_2)){
			$qty_refund = db_result(db_query("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1"));
			$qty_buy = $row_2['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$YTD_sales = round($row_2['itemprice']*$qty_buy, 4);
			$total_commission += round($row_2['commission'] * $YTD_sales / 100, 4);	
		}
		$total_commission += db_result(db_query("select sum(raise) from raises where legal_business_id = '$key' and role = 8"));
		$sql = "select charities.legal_business_id,charities.legal_business_name,charities.address,charities.city,charities.state,charities.zipcode from charities join users on charities.uid = users.uid where users.status <> -1 and charities.legal_business_id = '$key'";
		$re = db_query($sql);
		while($row = db_fetch_array($re)){
			$legal_business_name = $row['legal_business_name'];
			$legal_business_id = $row['legal_business_id'];
			$paid = db_result(db_query("select sum(pay) from payments where legal_business_id = '$key' and role = 8"));
		}
		$total = $total_commission - $paid;
		if($total < 0) $total = 0;
		$total = round($total, 2);	
		
		$str = str_replace("<!--@date@-->", date("F j, Y, g:i a"), $str);
		$str = str_replace("<!--@legal_business_name@-->", $legal_business_name, $str);
		$str = str_replace("<!--@legal_business_id@-->", $legal_business_id, $str);
		
		$str = str_replace("<!--total-->", number_format($total, 2), $str);
		$str = str_replace("@total@", $total, $str);
		$str = str_replace("<!--@total_paid@-->", number_format($paid, 2), $str);
		$str = str_replace("<!--@total_commission@-->", number_format($total_commission, 2), $str);
		$str = str_replace("@key@", $key, $str);	
	}//end loadData function
}//end CharityPayment_model class
/////// written by Ngoc Lanh /////