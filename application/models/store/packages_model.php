<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Packages_model extends CI_Model 
{
	private $__package_type__ = array(
		'02' => 'Customer Supplied Package',
		'01' => 'UPS Letter',
		'03' => 'Tube',
		'04' => 'PAK',
		'21' => 'UPS Express Box',
		'24' => 'UPS 25KG Box',
		'25' => 'UPS 10KG Box',
		'30' => 'Pallet',
		'2a' => 'Small Express Box',
		'2b' => 'Medium Express Box',
		'2c' => 'Large Express Box'
	);	
	public function add_loadData()
	{
		$ong_chu = $this ->lib ->__loadBoss__();
		$okey = !empty($_GET['okey'])?$_GET['okey']:'';
		$arr_packages = array();
		$re = $this ->db->query("select id,pkey from packages where okey = '$okey'");
		foreach($re->result_array() as $row){
			$id = $row['id'];
			$re_1 = $this ->db->query("select product_id,qty from packages_items where package_id = $id");
			foreach($re_1->result_array() as $row_1){
				$check_exit = false;
				for($i = 0; $i < count($arr_packages); $i++){
					if($arr_packages[$i]['product_id'] == $row_1['product_id']){
						$arr_packages[$i]['qty'] += $row_1['qty'];
						$check_exit = true;
						break;	
					}	
				}
				if($check_exit == false){
					$arr_packages[] = $row_1;		
				}
			}	
		}
		$arr_products = array();
		$re = $this ->db->query("select orderid from orders where okey = '$okey'");
		if($re->num_rows()>0){
			$row = $re->row_array();
			$orderid = $row['orderid'];
			$sql_manufacturer = '';
			$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
			if($this ->author ->objlogin ->role['rid'] == MANUFACTURER){
				$sql_manufacturer = "and items.uid = ".$ong_chu;
				$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
			}
			$arrPromotions = array();
			$re_1 = $this ->db->query($sql_orders_promotions);
			foreach($re_1->result_array() as $row_1){
				$arrPromotions[] = $row_1;	
			}
			$re_1 = $this ->db->query("select order_detais.id,order_detais.itemid,order_detais.quality,items.itm_name,items.itm_model,items.itm_key, items.product_type from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = $orderid $sql_manufacturer order by order_detais.id ASC");
			foreach($re_1->result_array() as $row_1){
				if($row_1['product_type'] != 0) continue;
				$odetail = $row_1['id'];
				$qty_refund = 0;
				$temp_query = $this ->db->query("select sum(qty) as num_qty from odetail_return where odetail = $odetail and status = 1");
				if($temp_query ->num_rows() >0){
					$temp_row = $temp_query ->row_array();
					$qty_refund = $temp_row['num_qty'];
						
				}
				$row_1['quality'] -= $qty_refund;
				if($row_1['quality'] < 0) $row_1['quality'] = 0;
				$arr_products[] = $row_1;
				for($i = 0; $i < count($arrPromotions); $i++){
					if($arrPromotions[$i]['promo_type'] == 2 && $arrPromotions[$i]['product_key'] == $row_1['itm_key']){
						$result_qty = 0;
						$qty_buy = $row_1['quality'];
						if($qty_buy >= $arrPromotions[$i]['minqty']){
							$bac_qty = 0;
							if($arrPromotions[$i]['minqty'] > 0)
								$bac_qty = floor($qty_buy / $arrPromotions[$i]['minqty']);
							$result_qty = $bac_qty * $arrPromotions[$i]['freeqty'];
						}
						if($result_qty <= 0) $result_qty = 0;
						$arrPromotions[$i]['result_qty'] = $result_qty;
					}		
				}
			}
			if(count($arrPromotions) > 0){//0
				foreach($arrPromotions as $promotions){
					if($promotions['promo_type'] == 2){
						$qty = $promotions['result_qty'];
						$itm_model = '';
						$itm_name = '';
						$itemid = 0;
						$re_ = $this ->db->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
						if($row_ = db_fetch_array($re_)){
							$itm_name = $row_['itm_name'];
							$itm_model = $row_['itm_model'];
							$itemid = $row_['itm_id'];		
						}
						if($this ->author ->objlogin ->role['rid'] == MANUFACTURER){
							if($ong_chu == $promotions['manufacturer_id']){
								if(count($arr_products) > 0){
									for($i = 0; $i < count($arr_products); $i++){
										if($arr_products[$i]['itemid'] == $itemid){
											$arr_products[$i]['quality'] += $qty;
										}	
									}
								}else{
									$arr_products[] = array(
										'itemid' => $itemid,
										'quality' => $qty,
										'itm_name' => $itm_name,
										'itm_model' => $itm_model
									);
								}
							}	
						}else{
							if(count($arr_products) > 0){
								for($i = 0; $i < count($arr_products); $i++){
									if($arr_products[$i]['itemid'] == $itemid){
										$arr_products[$i]['quality'] += $qty;
									}	
								}
							}else{
								$arr_products[] = array(
									'itemid' => $itemid,
									'quality' => $qty,
									'itm_name' => $itm_name,
									'itm_model' => $itm_model
								);
							}
						}	
					}
				}
			}//0
		}
		$new_packages = array();
		for($i = 0; $i < count($arr_products); $i++){
			$check_exit = false;
			for($j = 0; $j < count($arr_packages); $j++){
				if($arr_products[$i]['itemid'] == $arr_packages[$j]['product_id']){
					if($arr_products[$i]['quality'] > $arr_packages[$j]['qty']){
						$row = $arr_products[$i];
						$row['quality'] = $arr_products[$i]['quality'] - $arr_packages[$j]['qty'];
						$new_packages[] = $row;
					}
					$check_exit = true;	
					break;	
				}	
			}
			if($check_exit == false){
				$new_packages[] = $arr_products[$i];	
			}
		}
		return $new_packages;
	}//end add_loadData function
	
	public function add_postPackages()
	{
		$error = '';
		if(isset($_POST['packages']) && is_array($_POST['packages']) && count($_POST['packages']) > 0){
			$okey = (isset($_POST['okey']))?$_POST['okey']:'';
			if($okey != ''){
				foreach($_POST['packages'] as $package){
					$pkey = $this ->lib ->GeneralRandomReferralCode(10);
					$re = $this ->db ->query("select id from packages where pkey = '$pkey'");
					while($re ->num_rows() >0){
						$pkey = $this ->lib ->GeneralRandomReferralCode(10);
						$re = $this ->db ->query("select id from packages where pkey = '$pkey'");
					}
					$data_package = array(
						'pkey' => $pkey,
						'okey' => $okey	
					);
					$this ->db->insert("packages", $data_package);
					$id = $this ->db->insert_id();
					if(is_numeric($id) && $id > 0){
						if(isset($package['items']) && is_array($package['items']) && count($package['items']) > 0){
							foreach($package['items'] as $item){
								$data_item = array(
									'package_id' => $id,
									'product_id' => $item['itemid'],
									'qty' => $item['qty']	
								);
								$this ->db->insert("packages_items", $data_item);	
								$pitem = $this ->db->insert_id();
							}	
						}	
					}else{
						$error = 'Can not insert to database.';	
					}
				}	
			}	
		}
		return array('error'=>$error);
	}//end add_postPackages function
	
	public function edit_loadData()
	{
		$ong_chu = $this ->lib ->__loadBoss__();
		$okey = !empty($_GET['okey'])?$_GET['okey']:'';
		$pkey = !empty($_GET['pkey'])?$_GET['pkey']:'';
		$arr_packages = array();
		$re = $this ->db->query("select id from packages where pkey = '$pkey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$id = $row['id'];
			$sql_manufacturer = '';
			if($this ->author ->objlogin ->role['rid'] == MANUFACTURER){
				$sql_manufacturer = "and items.uid = ".$ong_chu;
			}
			$re_1 = $this ->db->query("select packages_items.id,packages_items.product_id,packages_items.qty as quality,items.itm_model,items.itm_name from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
			foreach($re_1 ->result_array() as $row_1){
				$arr_packages[] = $row_1;	
			}	
		}
		return $arr_packages;
	}//end edit_loadData function
	
	public function edit_postPackages()
	{
		$error = '';
		if(isset($_POST['packages_items']) && is_array($_POST['packages_items']) && count($_POST['packages_items']) > 0){
			$okey = (isset($_POST['okey']))?$_POST['okey']:'';
			if($okey != ''){
				foreach($_POST['packages_items'] as $packages_items){
					$this ->db->update("packages_items", array('qty'=>$packages_items['qty']), "id = ".$packages_items['id']);
				}		
			}	
		}
		return array('error'=>$error);
	}//end edit_postPackages function
	
	public function delete_item()
	{
		if(isset($_POST['id']) && is_numeric($_POST['id'])){
			$re = $this ->db->query("select package_id from packages_items where id = ".$_POST['id']);
			if($re->num_rows() >0){
				$row = $re ->row_array();
				$id = $row['package_id'];	
				$this ->db->query("delete from packages_items where id = ".$_POST['id']);
				$count_items =0;
				$temp_query = $this ->db ->query("select count(id) as count_id from packages_items where package_id = $id");
				if($temp_query ->num_rows()>0){
					$temp_row  = $temp_query ->row_array();
					$count_items = $temp_row['count_id'];
				}
				if($count_items <= 0)
					$this ->db->query("delete from packages where id = '$id'");		
			}
		}
		return array();///why return array?
	}//end delete_item function
	
	public function packages_loadObj()
	{
		$edit = 'no';
		$del = 'yes';
		$ship = 'no';
		if($this ->author ->isAccessPerm("packages", "edit")){
			$edit = 'yes';
		}
		if($this ->author ->isAccessPerm("packages", "delete")){
			$del = 'yes';
		}
		if($this ->author ->isAccessPerm("shipments", "add")){
			$ship = 'yes';
		}
		$roles = $this->author->objlogin->role;
		$ong_chu = $this ->lib ->__loadBoss__();
		$okey = isset($_POST['okey'])?$_POST['okey']:'';
		$arr_packages = array();
		$re = $this ->db ->query("select * from packages where okey = '$okey'");
		foreach($re ->result_array() as $row){
			$id = $row['id'];
			
			$Products = '';
			$sql_manufacturer = '';
			if($roles['rid'] == MANUFACTURER){
				$sql_manufacturer = "and items.uid = ".$ong_chu;
			}
			$re_1 = $this ->db ->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
			foreach($re_1 ->result_array() as $row_1){
				$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
			}
			if($Products != ''){
				$Products = substr($Products, 0, strlen($Products)-4);
			}elseif($Products == '') continue;
			$row['Products'] = $Products;
			
			$cancel_shipment = 'no';
			$shipment_ID = $row['shipment_ID'];
			$re_ = $this ->db ->query("select id,shipping_method from shipments where skey = '$shipment_ID' and okey = '$okey'");
			if($re_ ->num_rows() >0){
				$row_ = $re_ ->row_array();
				$cancel_shipment = 'yes';
				if($row_['shipping_method'] == 1) $row['package_type']	= isset($this ->__package_type__[$row['package_type']])?$this -> __package_type__[$row['package_type']]:$row['package_type'];
			}else{
				$row['shipment_ID'] = null;	
			}
			
			$row['cancel_shipment'] = $cancel_shipment;
			$row['edit'] = $edit;
			$row['del'] = $del;
			$row['ship'] = $ship;
			$arr_packages[] = $row;	
		}
		return $arr_packages;
	}//end packages_loadObj function
	
	public function delete_package()
	{
		if(!$this ->author ->isAccessPerm("packages", "delete")){
			return false;
		}
		$roles = $this ->author ->objlogin ->role;
		$ong_chu = $this ->lib -> __loadBoss__();
		$pkey = isset($_POST['pkey'])?$_POST['pkey']:'';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$re = $this ->db ->query("select id from packages where pkey = '$pkey'");
			if($re ->num_rows() >0){
				$row = $re ->row_array();
				$id = $row['id'];
				$re_1 = $this ->db ->query("select packages_items.id from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
				foreach($re_1 ->result_array() as $row_1){
					$this ->db ->query("delete from packages_items where id = ".$row_1['id']);	
				}
				$count_items = $this ->database ->db_result("select count(id) from packages_items where package_id = $id");
				if($count_items <= 0)
					$this ->db ->query("delete from packages where pkey = '$pkey'");		
			}	
		}else{
			$re = $this ->db ->query("select id from packages where pkey = '$pkey'");
			if($re ->num_rows() >0){
				$row = $re ->row_array();
				$id = $row['id'];
				$this ->db ->query("delete from packages_items where package_id = $id");	
			}
			$this ->db ->query("delete from packages where pkey = '$pkey'");		
		}
		return true;
	}//end delete_package function
	
	public function opackages()
	{
		$okey = !empty($_GET['okey'])?$_GET['okey']:'';
		$roles = $this->author->objlogin->role;
		$ong_chu = $this ->lib ->__loadBoss__();
		$str_bt = '';
		if($this ->author ->isAccessPerm("packages", "add")){
			$arr_packages = array();
			$re = $this ->db ->query("select id,pkey from packages where okey = '$okey'");
			foreach($re->result_array() as $row){
				$id = $row['id'];
				$re_1 = $this ->db ->query("select product_id,qty from packages_items where package_id = $id");
				foreach($re_1->result_array() as $row_1){
					$check_exit = false;
					for($i = 0; $i < count($arr_packages); $i++){
						if($arr_packages[$i]['product_id'] == $row_1['product_id']){
							$arr_packages[$i]['qty'] += $row_1['qty'];
							$check_exit = true;
							break;	
						}	
					}
					if($check_exit == false){
						$arr_packages[] = $row_1;		
					}
				}	
			}
			$arr_products = array();
			$re = $this ->db ->query("select orderid from orders where okey = '$okey'");
			if($re ->num_rows() >0){
				$row = $re ->row_array();
				$orderid = $row['orderid'];
				$sql_manufacturer = '';
				$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
				if($roles['rid'] == MANUFACTURER){
					$sql_manufacturer = "and items.uid = ".$ong_chu;
					$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
				}
				$arrPromotions = array();
				$re_1 = $this ->db ->query($sql_orders_promotions);
				foreach($re_1 ->result_array() as $row_1){
					$arrPromotions[] = $row_1;	
				}
				$re_1 = $this ->db ->query("select order_detais.id,order_detais.itemid,order_detais.quality,items.itm_name,items.itm_model,items.itm_key,items.product_type from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = $orderid $sql_manufacturer order by order_detais.id ASC");
				foreach($re_1 ->result_array() as $row_1){
					$odetail = $row_1['id'];
					$qty_refund = $this ->database ->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");
					$row_1['quality'] -= $qty_refund;
					if($row_1['quality'] < 0) $row_1['quality'] = 0;
					if($row_1['product_type'] != 0) continue;
					$arr_products[] = $row_1;
					
					for($i = 0; $i < count($arrPromotions); $i++){
						if($arrPromotions[$i]['promo_type'] == 2 && $arrPromotions[$i]['product_key'] == $row_1['itm_key']){
							$result_qty = 0;
							$qty_buy = $row_1['quality'];
							if($qty_buy >= $arrPromotions[$i]['minqty']){
								$bac_qty = 0;
								if($arrPromotions[$i]['minqty'] > 0)
									$bac_qty = floor($qty_buy / $arrPromotions[$i]['minqty']);
								$result_qty = $bac_qty * $arrPromotions[$i]['freeqty'];
							}
							if($result_qty <= 0) $result_qty = 0;
							$arrPromotions[$i]['result_qty'] = $result_qty;
						}		
					}
				}
				if(count($arrPromotions) > 0){//0
					foreach($arrPromotions as $promotions){
						if($promotions['promo_type'] == 2){
							$qty = $promotions['result_qty'];
							$itm_model = '';
							$itm_name = '';
							$itemid = 0;
							$re_ = $this ->db ->query("select itm_id,itm_name,itm_model from items where itm_key = '".$promotions['itm_key']."'");
							if($re_ ->num_rows() >0){
								$row_ = $re_ ->row_array();
								$itm_name = $row_['itm_name'];
								$itm_model = $row_['itm_model'];
								$itemid = $row_['itm_id'];		
							}
							if($roles['rid'] == MANUFACTURER){
								if($ong_chu == $promotions['manufacturer_id']){
									if(count($arr_products) > 0){
										for($i = 0; $i < count($arr_products); $i++){
											if($arr_products[$i]['itemid'] == $itemid){
												$arr_products[$i]['quality'] += $qty;
											}	
										}
									}else{
										$arr_products[] = array(
											'itemid' => $itemid,
											'quality' => $qty,
											'itm_name' => $itm_name,
											'itm_model' => $itm_model
										);
									}
								}	
							}else{
								if(count($arr_products) > 0){
									for($i = 0; $i < count($arr_products); $i++){
										if($arr_products[$i]['itemid'] == $itemid){
											$arr_products[$i]['quality'] += $qty;
										}	
									}
								}else{
									$arr_products[] = array(
										'itemid' => $itemid,
										'quality' => $qty,
										'itm_name' => $itm_name,
										'itm_model' => $itm_model
									);
	
								}
							}	
						}
					}
				}//0
			}
			$new_packages = array();
			for($i = 0; $i < count($arr_products); $i++){
				$check_exit = false;
				for($j = 0; $j < count($arr_packages); $j++){
					if($arr_products[$i]['itemid'] == $arr_packages[$j]['product_id']){
						if($arr_products[$i]['quality'] > $arr_packages[$j]['qty']){
							$row = $arr_products[$i];
							$row['quality'] = $arr_products[$i]['quality'] - $arr_packages[$j]['qty'];
							$new_packages[] = $row;
						}
						$check_exit = true;	
						break;	
					}	
				}
				if($check_exit == false){
					$new_packages[] = $arr_products[$i];	
				}
			}
			if(count($new_packages) > 0){
				$str_bt = '<input type="button" value="Create packages" class="button" onclick="Create_packages()" />';	
			}
		}
		return $str_bt;
	}//end opackages function
}//end Packages_model class