<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shipments_model extends CI_Model 
{
	
	private $__weight_units__ = array(
		'lb' => 'Pounds',
		'oz' => 'Ounces'
	);
	private $__units_measurement__ = array(
		'in' => 'Inches',
		'ft' => 'Feet',
		'cm' => 'Centimeters',
		'mm'  => 'Millimeters'
	);
	
	private $__UPS_Service__ = array(
		'03' => 'UPS Ground',
		'01' => 'UPS Next Day Air',
		'13' => 'UPS Next Day Air Saver',
		'14' => 'UPS Next Day Air Early AM',
		'02' => 'UPS Second Day Air',
		'59' => 'UPS Second Day Air AM',
		'12' => 'UPS Three-Day Select',
		'11' => 'UPS Standard',
		'07' => 'UPS Worldwide Express',
		'08' => 'UPS Worldwide Expedited',
		'54' => 'UPS Worldwide Express Plus',
		'65' => 'UPS Saver'
	);
	
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
	
	private $__USPS_package_size__ = array(
		'REGULAR' 	=> 'Regular',
		'LARGE'		=> 'Large',
		'OVERSIZE'	=> 'Oversize' 
	);
	private $__USPS_domestic_services__ = array(
		'Library Mail'	=> 'Library Mail',
		'Media Mail'	=> 'Media Mail (For only media product)',
		'Parcel Post'	=> 'Parcel Post',
		'First Class'	=> 'First Class Mail (Weight Not Over 13 oz)',
		'Priority'		=> 'Priority Mail',
		'Express Mail' 	=> 'Express Mail'
	);	
	private $__USPS_international_services__ = array(
		'EXPRESS'		=> 'Express Mail International',
		'PRIORITY'	=> 'Priority Mail International',
		'FIRST CLASS'	=> 'First Class Mail International'
	);
	public function add_load_product()
	{
		$roles = $this ->author ->objlogin ->role;
		$ong_chu = $this ->lib ->__loadBoss__();
		$okey = !empty($_GET['okey'])?$this->lib ->escape($_GET['okey']):'';
		$pkey = !empty($_GET['pkey'])?$this ->lib ->escape($_GET['pkey']):'';
		$arr_packages = array();
		$re = $this ->db->query("select * from packages where okey = '$okey'");
		foreach($re->result_array() as $row){
			$id = $row['id'];
			$shipment_ID = $row['shipment_ID'];
			$re_ = $this ->db->query("select id from shipments where skey = '$shipment_ID' and okey = '$okey'");
			if($re_ ->num_rows() >0){
				$row_ = $re_ ->row_array();
				continue;
			}
			
			$check_package = 0;
			if($row['pkey'] == $pkey) $check_package = 1;
			$row['check_package'] = $check_package;
			$Products = '';
			$sql_manufacturer = '';
			if($roles['rid'] == MANUFACTURER){
				$sql_manufacturer = "and items.uid = ".$ong_chu;
			}
			$re_1 = $this ->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
			foreach($re_1->result_array() as $row_1){
				$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
			}
			if($Products != ''){
				$Products = substr($Products, 0, strlen($Products)-4);
			}elseif($Products == '') continue;
			$row['Products'] = $Products;
			$arr_packages[] = $row;	
		}
		return $arr_packages ;
	}//end add_load_product function
	
	public function add_postPackages()
	{
		$error = '';
		if(isset($_POST['packages']) && is_array($_POST['packages']) && count($_POST['packages']) > 0){
			$okey = (isset($_POST['okey']))?$_POST['okey']:'';
			if($okey != ''){
				foreach($_POST['packages'] as $package){
					$pkey = $this ->lib ->GeneralRandomReferralCode(12);
					$re = $this ->db ->query("select id from packages where pkey = '$pkey'");
					foreach($re ->result_array() as $row){
						$pkey = $this ->lib ->GeneralRandomReferralCode(12);
						$re = $this ->db ->query("select id from packages where pkey = '$pkey'");
					}
					$data_package = array(
						'pkey' => $pkey,
						'okey' => $okey	
					);
					$this ->db ->insert("packages", $data_package);
					$id = $this ->db ->insert_id();
					if(is_numeric($id) && $id > 0){
						if(isset($package['items']) && is_array($package['items']) && count($package['items']) > 0){
							foreach($package['items'] as $item){
								$data_item = array(
									'package_id' => $id,
									'product_id' => $item['itemid'],
									'qty' => $item['qty']	
								);
								$this ->db ->insert("packages_items", $data_item);	
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
	
	public function loadData()
	{
		$roles = $this ->author ->objlogin ->role;
		$ong_chu = $this ->lib ->__loadBoss__();
		$okey = isset($_GET['okey'])?$this ->lib ->escape($_GET['okey']):'';
		$str_bt = '';
		$arr_packages = array();
		$re = $this ->db ->query("select * from packages where okey = '$okey'");
		foreach($re ->result_array() as $row){
			$id = $row['id'];
			$shipment_ID = $row['shipment_ID'];
			$re_ = $this ->db ->query("select id from shipments where skey = '$shipment_ID' and okey = '$okey'");
			if($re_ ->num_rows() >0){
				continue;
			}
			
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
			$arr_packages[] = $row;	
		}
		
		if(count($arr_packages) > 0){
			$str_bt = '<input type="button" value="Make a new shipment" class="button" onclick="shipPackage()" />';	
		}
		return $str_bt;
	}//end loadData function
	
	public function loadObj()
	{
		$edit_0 = 'no';
		$del_0 = 'no';
		$ship = 'no';
		if($this ->author -> isAccessPerm("packages","edit")){
			$edit_0 = 'yes';
		}
		if($this ->author -> isAccessPerm("packages","delete")){
			$del_0 = 'yes';
		}
		$roles = $this ->author ->objlogin ->role;
		$ong_chu = $this ->lib -> __loadBoss__();
		$okey = isset($_POST['okey'])?$_POST['okey']:'';
		$arr_packages = array();
		$tblcontries = array();
		$re = $this ->db ->query("select * from tblcontries");
		foreach($re ->result_array() as $row){
			$tblcontries[$row['code']] = $row['name'];	
		}
		$todate = strtotime('now');
		$re = $this ->db ->query("select id,skey,label,destination_firstname,destination_lastname,destination_address,destination_city,destination_state,destination_zipcode,destination_country,destination_phone as desphone,ship_date,expected_delivery,tracking_number,shipping_method,date_active from shipments where okey = '$okey'");
		foreach($re ->result_array() as $row){
			$edit = $edit_0;
			$del = $del_0;	
			$skey = $row['skey'];
			$check_manufacturer = false;
			$re_1 = $this ->db ->query("select id,pkey from packages where shipment_ID = '$skey' and okey = '$okey'");
			foreach($re_1 ->result_array() as $row_1){
				$id = $row_1['id'];
				$sql_manufacturer = '';
				if($roles['rid'] == MANUFACTURER){
					$sql_manufacturer = "and items.uid = ".$ong_chu;
				}
				$re_2 = $this ->db ->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
				foreach($re_2 ->result_array() as $row_2){
					$check_manufacturer = true;
					break;
				}
				if($check_manufacturer == true) break;
			}
			if($check_manufacturer == false) continue;
			if($row['label'] != '' && $row['label'] != null) $row['label'] = 'yes';
			$row['destination_country'] = isset($tblcontries[$row['destination_country']])?$tblcontries[$row['destination_country']]:$row['destination_country'];
			
			$limit_date = 0;
			$date_active = $row['date_active'];
			if($date_active != null && $date_active != ''){
				$arr_ = explode(" ",$date_active);
				$day_active_1 = strtotime($arr_[0])+2*60*60;
				$day_active_2 = strtotime($arr_[0])+26*60*60;
				$day_active_0 = strtotime($date_active);
				if($day_active_0 <= $day_active_1){
					$limit_date = $day_active_1;
				}else{
					$limit_date = $day_active_2;	
				}
			}
			if($todate >= $limit_date){
				$edit = 'no';
				$del = 'no';		
			}
			$row['edit'] = $edit;
			$row['del'] = $del;
			$arr_packages[] = $row;	
		}
		return $arr_packages;
	}//end loadObj function
	
	public function delete_package()
	{
		$pkey = !empty($_POST['pkey'])?$this ->lib ->escape($_POST['pkey']):'';
		$this ->db ->query("delete from shipments where skey = '$pkey'");	
		return array();
	}//end delete_package function
	
	public function editmanually_loadData()
	{
		$roles = $this ->author ->objlogin ->role;
		$okey = !empty($_GET['okey'])?$this ->lib ->escape($_GET['okey']):'';
		$skey = !empty($_GET['skey'])?$this ->lib ->escape($_GET['skey']):'';
		
		$destination_firstname = '';
		$destination_lastname = '';
		$destination_address = '';
		$destination_city = '';
		$destination_state = '';
		$destination_zipcode = '';
		$destination_phone = '';
		$destination_mail = '';
		$destination_country = '';
		
		$origin_firstname = $this ->author ->objlogin->firstname;
		$origin_lastname = $this ->author ->objlogin->lastname;
		$origin_address = $this ->author ->objlogin->address;
		$origin_city = $this ->author ->objlogin->city;
		$origin_state = $this ->author ->objlogin->state;
		$origin_zipcode = $this ->author ->objlogin->zipcode;
		$origin_phone = $this ->author ->objlogin->phone;
		$origin_mail = $this ->author ->objlogin->mail;
		$origin_country = $this ->author ->objlogin->country;
		
		$shipment_options = '';
		$transaction_ID = '';
		$tracking_number = '';
		$ship_date = '';
		$expected_delivery = '';
		$shipping_cost = '';
		
		$ship_label = '';
		$shipping_fee = 0;
		$base_price = 0;
		$oid = 0;
		$count = 0;
		$count_shipping_free = 0;
		$ong_chu = $this ->lib ->__loadBoss__();
		
		$re = $this ->db->query("select * from shipments where skey = '$skey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$destination_firstname = $row['destination_firstname'];
			$destination_lastname = $row['destination_lastname'];
			$destination_address = $row['destination_address'];
			$destination_city = $row['destination_city'];
			$destination_state = $row['destination_state'];
			$destination_zipcode = $row['destination_zipcode'];
			$destination_phone = $row['destination_phone'];
			$destination_mail = $row['destination_mail'];
			$destination_country = $row['destination_country'];
			
			$origin_firstname = $row['origin_firstname'];
			$origin_lastname = $row['origin_lastname'];
			$origin_address = $row['origin_address'];
			$origin_city = $row['origin_city'];
			$origin_state = $row['origin_state'];
			$origin_zipcode = $row['origin_zipcode'];
			$origin_phone = $row['origin_phone'];
			$origin_mail = $row['origin_mail'];
			$origin_country = $row['origin_country'];
			
			$shipment_options = $row['shipment_options'];
			$transaction_ID = $row['transaction_ID'];
			$tracking_number = $row['tracking_number'];	
			$ship_date = $row['ship_date'];
			$expected_delivery = $row['expected_delivery'];
			$shipping_cost = $row['shipping_cost'];
		}
		
		$re = $this ->db->query("select orderid,shipping_name,shipping_address,shipping_city,shipping_state,shipping_zip,shipping_phone,billing_email,shipping_key,shipping_fee from orders where okey = '$okey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$oid = $row['orderid'];
			$base_price			= $row["shipping_fee"];
			$ship_label = $this ->database->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");	
		}
		
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}else{
			$shipping_fee += $base_price;	
		}
		$arrPromotions = array();
		$re = $this ->db->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("select order_detais.last_shipping,order_detais.quality,items.itm_key from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
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
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$shipping_rate = $base_price;
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$shipping_rate += round($row['last_shipping']*$row['quality'], 2);
			}
			$shipping_fee += round($shipping_rate, 2);
		}//0
		
		$package_arr  = array();
		$packages = array();
		$re = $this ->db->query("select * from packages where shipment_ID = '$skey' and okey = '$okey'");
		foreach($re ->result_array() as $row){
			
			$pkey = $row['pkey'];
			
			$id = $row['id'];
			$Products = '';
			$sql_manufacturer = '';
			if($roles['rid'] == MANUFACTURER){
				$sql_manufacturer = "and items.uid = ".$ong_chu;
			}
			$re_1 = $this ->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
			foreach($re_1 ->result_array() as $row_1){
				$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
			}
			if($Products != ''){
				$Products = substr($Products, 0, strlen($Products)-4);
			}elseif($Products == '') continue;
			
			$packages[] = $pkey;
			
			$weight_units_select = '';
			foreach($this ->__weight_units__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['weight_units']) $select_ = 'selected="selected"';
				$weight_units_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
			
			$_units_measurement_select = '';
			foreach($this ->__units_measurement__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['units_measurement']) $select_ = 'selected="selected"';
				$_units_measurement_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
			
			$package_arr[] = array(
				'height' =>$row['height'],
				'width' =>$row['width'],
				'length' =>$row['length'],
				'_units_measurement_' =>$_units_measurement_select,
				'_weight_units_' =>$weight_units_select,
				'weight' =>$row['weight'],
				'pack_Tracking_number' =>$row['tracking_number'],
				'declared_value' =>$row['declared_value'],
				'package_type' =>$row['package_type'],
				'products' =>$Products,
				'pkey' =>$pkey,
			);
		}
		
		return array(
			'package_arr' =>$package_arr,
			'load_packages' =>"var packages = ".json_encode($packages).";",
			'load_countries' =>"dataCountries = ".json_encode($this ->lib ->__loadDataCountries__()).";",
			'loadOriginCountryValue' =>"if(document.getElementById('origin_country')) document.getElementById('origin_country').value='".$origin_country."';",
			'loadOriginStateValue' =>"if(document.getElementById('origin_state')) document.getElementById('origin_state').value='".$origin_state."';",
			'loadDestinationCountryValue' =>"if(document.getElementById('destination_country')) document.getElementById('destination_country').value='".$destination_country."';",
			'loadDestinationStateValue' =>"if(document.getElementById('destination_state')) document.getElementById('destination_state').value='".$destination_state."';",
			'ship_label' =>$ship_label,
			'shipping_fee' => number_format($shipping_fee, 2),
			'shipment_options' =>$shipment_options,
			'transaction_ID' =>$transaction_ID,
			'tracking_number' =>$tracking_number,
			'ship_date' =>$ship_date,
			'expected_delivery' =>$expected_delivery,
			'shipping_cost' =>$shipping_cost,
			'destination_firstname' =>$destination_firstname,
			'destination_lastname' =>$destination_lastname,
			'destination_address' =>$destination_address,
			'destination_city' =>$destination_city,
			'destination_state' =>$destination_state,
			'destination_zipcode' =>$destination_zipcode,
			'destination_phone' =>$destination_phone,
			'destination_mail' =>$destination_mail,
			'origin_firstname' =>$origin_firstname,
			'origin_lastname' =>$origin_lastname,
			'origin_address' =>$origin_address,
			'origin_city' =>$origin_city,
			'origin_state' =>$origin_state,
			'origin_zipcode' =>$origin_zipcode,
			'origin_phone' =>$origin_phone,
			'origin_mail' =>$origin_mail,
			'skey' =>$skey,
		);
	}//end editmanually_loadData function
	
	public function editmanually_save_shipment()
	{
		$error = '';
		if(isset($_POST['packages']) && is_array($_POST['packages']) && count($_POST['packages']) > 0){	//0
			$okey = (!empty($_POST['okey']))?$this->lib ->escape($_POST['okey']):'';
			$skey = (!empty($_POST['skey']))?$this->lib ->escape($_POST['skey']):'';
			if($okey != ''){	//1
				$data_shipment = array(
					'origin_firstname' => $this ->lib ->escape($_POST['origin_firstname']),
					'origin_lastname' => $this ->lib ->escape($_POST['origin_lastname']),
					'origin_address' => $this ->lib ->escape($_POST['origin_address']),
					'origin_city' => $this ->lib ->escape($_POST['origin_city']),
					'origin_state' => $this ->lib ->escape($_POST['origin_state']),
					'origin_zipcode' => $this ->lib ->escape($_POST['origin_zipcode']),
					'origin_phone' => $this ->lib ->escape($_POST['origin_phone']),
					'origin_mail' => $this ->lib ->escape($_POST['origin_mail']),
					'origin_country' => $this ->lib ->escape($obj_submit['origin_country']),
					'destination_firstname' => $this ->lib ->escape($_POST['destination_firstname']),
					'destination_lastname' => $this ->lib ->escape($_POST['destination_lastname']),
					'destination_address' => $this ->lib ->escape($_POST['destination_address']),
					'destination_city' => $this ->lib ->escape($_POST['destination_city']),
					'destination_state' => $this ->lib ->escape($_POST['destination_state']),
					'destination_zipcode' => $this ->lib ->escape($_POST['destination_zipcode']),
					'destination_phone' => $this ->lib ->escape($_POST['destination_phone']),
					'destination_mail' => $this ->lib ->escape($_POST['destination_mail']),
					'destination_country' => $this ->lib ->escape($obj_submit['destination_country']),
					'shipment_options' => $this ->lib ->escape($_POST['shipment_options']),
					'transaction_ID' => $this ->lib ->escape($_POST['transaction_ID']),
					'tracking_number' => $this ->lib ->escape($_POST['tracking_number']),
					'ship_date' => (trim($_POST['ship_date'])!='')? $_POST['ship_date']:date('m/d/Y',$this->lib->getTimeGMT()),
					'expected_delivery' => trim($_POST['expected_delivery']),
					'shipping_cost' => trim($_POST['shipping_cost'])
				);
				$this ->db ->where("skey",$skey);
				$this ->db->update("shipments", $data_shipment);
				foreach($_POST['packages'] as $package){	//2
					$package_update = array(
						'pkey' => $package[0],
						'package_type' => $package[1],
						'declared_value' => $package[2],
						'tracking_number' => $this ->lib ->escape($_POST['tracking_number']),
						'weight' => $package[3],
						'weight_units' => $package[4],
						'length' => $package[5],
						'width' => $package[6],
						'height' => $package[7],
						'units_measurement' => $package[8]
					);
					$this ->db ->where("pkey",$package[0]);
					$this ->db->update("packages", $package_update);
				}	//2	
			}	//1
		}	//0
		return array('error'=>$error);
	}//end editmanually_save_shipment function
	
	public function editups_loadData()
	{
		$roles = $this ->author ->objlogin ->role;
		$okey = !empty($_GET['okey'])?$this ->lib ->escape($_GET['okey']):'';
		$skey = !empty($_GET['skey'])?$this ->lib ->escape($_GET['skey']):'';
		
		$destination_firstname = '';
		$destination_lastname = '';
		$destination_address = '';
		$destination_city = '';
		$destination_state = '';
		$destination_zipcode = '';
		$destination_phone = '';
		$destination_mail = '';
		$destination_country = '';
		
		$origin_firstname = $this ->author ->objlogin->firstname;
		$origin_lastname = $this ->author ->objlogin->lastname;
		$origin_address = $this ->author ->objlogin->address;
		$origin_city = $this ->author ->objlogin->city;
		$origin_state = $this ->author ->objlogin->state;
		$origin_zipcode = $this ->author ->objlogin->zipcode;
		$origin_phone = $this ->author ->objlogin->phone;
		$origin_mail = $this ->author ->objlogin->mail;
		$origin_country = $this ->author ->objlogin->country;
		
		$shipment_options = '';
		$transaction_ID = '';
		$tracking_number = '';
		$ship_date = '';
		$expected_delivery = '';
		
		$ship_label = '';
		$shipping_fee = 0;
		$base_price = 0;
		$oid = 0;
		$count = 0;
		$access_key = '';
		$UPS_Shipper = '';
		$UPS_userid = '';
		$UPS_Password = '';
		$ong_chu = $this ->lib ->__loadBoss__();
		
		$re = $this ->db->query("select * from shipments where skey = '$skey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$destination_firstname = $row['destination_firstname'];
			$destination_lastname = $row['destination_lastname'];
			$destination_address = $row['destination_address'];
			$destination_city = $row['destination_city'];
			$destination_state = $row['destination_state'];
			$destination_zipcode = $row['destination_zipcode'];
			$destination_phone = $row['destination_phone'];
			$destination_mail = $row['destination_mail'];
			$destination_country = $row['destination_country'];
			
			$origin_firstname = $row['origin_firstname'];
			$origin_lastname = $row['origin_lastname'];
			$origin_address = $row['origin_address'];
			$origin_city = $row['origin_city'];
			$origin_state = $row['origin_state'];
			$origin_zipcode = $row['origin_zipcode'];
			$origin_phone = $row['origin_phone'];
			$origin_mail = $row['origin_mail'];
			$origin_country = $row['origin_country'];
			
			$transaction_ID = $row['transaction_ID'];
			$tracking_number = $row['tracking_number'];	
			$ship_date = $row['ship_date'];
			$expected_delivery = $row['expected_delivery'];
			$access_key = $row['access_key'];
			$UPS_Shipper = $row['UPS_Shipper'];
			$UPS_userid = $row['UPS_userid'];
			$UPS_Password = $row['UPS_Password'];
			
			foreach($this->__UPS_Service__ as $key => $label_){
				$select_ = '';
				if($key == $row['shipment_options']) $select_ = 'selected="selected"';
				$shipment_options .= '<option value="'.$key.'" '.$select_.'>'.$label_.'</option>';	
			}
		}
		
		$re = $this ->db->query("select orderid,shipping_name,shipping_address,shipping_city,shipping_state,shipping_zip,shipping_phone,billing_email,shipping_key,shipping_fee from orders where okey = '$okey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$oid = $row['orderid'];
			$base_price			= $row["shipping_fee"];
			$ship_label = $this ->database ->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");	
		}
		
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}else{
			$shipping_fee += $base_price;	
		}
		$arrPromotions = array();
		$re = $this ->db->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("select order_detais.last_shipping,order_detais.quality,items.itm_key from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
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
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$shipping_rate = $base_price;
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$shipping_rate += round($row['last_shipping']*$row['quality'], 2);
			}
			$shipping_fee += round($shipping_rate, 2);
		}//0
		
		$package_arr = array();
		$packages = array();
		$re = $this ->db->query("select * from packages where shipment_ID = '$skey' and okey = '$okey'");
		foreach($re->result_array() as $row){
			
			$pkey = $row['pkey'];
			
			$id = $row['id'];
			$Products = '';
			$sql_manufacturer = '';
			if($roles['rid'] == MANUFACTURER){
				$sql_manufacturer = "and items.uid = ".$ong_chu;
			}
			$re_1 = $this ->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
			foreach($re_1->result_array() as $row_1){
				$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
			}
			if($Products != ''){
				$Products = substr($Products, 0, strlen($Products)-4);
			}elseif($Products == '') continue;
			
			$packages[] = $pkey;
			
			$weight_units_select = '';
			foreach($this ->__weight_units__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['weight_units']) $select_ = 'selected="selected"';
				$weight_units_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
			
			$_units_measurement_select = '';
			foreach($this->__units_measurement__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['units_measurement']) $select_ = 'selected="selected"';
				$_units_measurement_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
			
			$_package_type_select = '';
			foreach($this ->__package_type__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['package_type']) $select_ = 'selected="selected"';
				$_package_type_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
	
			
			$package_arr[] = array(
				'height' =>$row['height'],
				'width' =>$row['width'],
				'length' =>$row['length'],
				'_units_measurement_' =>$_units_measurement_select,
				'_weight_units_' =>$weight_units_select,
				'weight' =>$row['weight'],
				'pack_Tracking_number' =>$row['tracking_number'],
				'declared_value' =>$row['declared_value'],
				'package_type' =>$_package_type_select,
				'products' =>$Products,
				'pkey' =>$pkey,
			);
		}
		
		return array(		
			"package_arr" =>$package_arr,
			"load_countries" => "dataCountries = ".json_encode($this ->lib ->__loadDataCountries__()).";" ,
			"loadOriginCountryValue" => "if(document.getElementById('origin_country')) document.getElementById('origin_country').value='".$origin_country."';" ,
			"loadOriginStateValue" => "if(document.getElementById('origin_state')) document.getElementById('origin_state').value='".$origin_state."';" ,
			"loadDestinationCountryValue" => "if(document.getElementById('destination_country')) document.getElementById('destination_country').value='".$destination_country."';" ,
			"loadDestinationStateValue" => "if(document.getElementById('destination_state')) document.getElementById('destination_state').value='".$destination_state."';" ,
			"ship_label" => $ship_label,
			"shipping_fee" => number_format($shipping_fee, 2),
			"shipment_options" => $shipment_options,
			"transaction_ID" => $transaction_ID,
			"tracking_number" => $tracking_number,
			"ship_date" => $ship_date,
			"expected_delivery" => $expected_delivery,
			"access_key" => $access_key,
			"UPS_Shipper" => $UPS_Shipper,
			"UPS_userid" => $UPS_userid,
			"UPS_Password" => $UPS_Password,
			"destination_firstname" => $destination_firstname,
			"destination_lastname" => $destination_lastname,
			"destination_address" => $destination_address,
			"destination_city" => $destination_city,
			"destination_state" => $destination_state,
			"destination_zipcode" => $destination_zipcode,
			"destination_phone" => $destination_phone,
			"destination_mail" => $destination_mail,
			"origin_firstname" => $origin_firstname,
			"origin_lastname" => $origin_lastname,
			"origin_address" => $origin_address,
			"origin_city" => $origin_city,
			"origin_state" => $origin_state,
			"origin_zipcode" => $origin_zipcode,
			"origin_phone" => $origin_phone,
			"origin_mail" => $origin_mail,
			"skey" => $skey,
			"load_packages" =>"var packages = ".json_encode($packages).";",
		);
	}//end editups_loadData function
	
	public function editups_save_shipment()
	{
		$error = '';
		if(isset($_POST['packages']) && is_array($_POST['packages']) && count($_POST['packages']) > 0){	//0
			$okey = (!empty($_POST['okey']))?$this ->lib ->escape($_POST['okey']):'';
			$skey = (!empty($_POST['skey']))?$this ->lib ->escape($_POST['skey']):'';
			if($okey != ''){	//1
				$this ->load ->library("ups/ups");
				$this ->load ->library("ups/upsShip");
				$this ->ups ->ups_start(trim($_POST['access_key']), trim($_POST['UPS_userid']), trim($_POST['UPS_Password']));
				$this ->ups->setTemplatePath('../xml/');
				$this ->ups->setTestingMode(0); // Change this to 0 for production
				$this ->upsShip ->upsShip_start($this ->ups);
				
				$this ->upsShip->shipper(array('name' => trim($_POST['origin_firstname']).' '.trim($_POST['origin_lastname']),
							 'phone' => trim($_POST['origin_phone']), 
							 'shipperNumber' => trim($_POST['UPS_Shipper']), 
							 'address1' => trim($_POST['origin_address']), 
							 'address2' => '', 
							 'address3' => '', 
							 'city' => trim($_POST['origin_city']), 
							 'state' => trim($_POST['origin_state']), 
							 'postalCode' => trim($_POST['origin_zipcode']), 
							 'country' => $_POST['origin_country']));
				$this ->upsShip->shipTo(array('companyName' => '', 
							'attentionName' => trim($_POST['destination_firstname']).' '.trim($_POST['destination_lastname']), 
							'phone' => trim($_POST['destination_phone']), 
							'address1' => trim($_POST['destination_address']), 
							'address2' => '', 
							'address3' => '', 
							'city' => trim($_POST['destination_city']), 
							'state' => trim($_POST['destination_state']), 
							'postalCode' => trim($_POST['destination_zipcode']), 
							'countryCode' => $_POST['destination_country']));
				$this ->upsShip->shipFrom(array('companyName' => 'Bellavie Network', 
							'attentionName' => trim($_POST['origin_firstname']).' '.trim($_POST['origin_lastname']), 
							'phone' => trim($_POST['origin_phone']), 
							'address1' => trim($_POST['origin_address']), 
							'address2' => '', 
							'address3' => '', 
							'city' => trim($_POST['origin_city']), 
							'state' => trim($_POST['origin_state']), 
							'postalCode' => trim($_POST['origin_zipcode']), 
							'countryCode' => $_POST['origin_country']));
				foreach($_POST['packages'] as $package){	//2
					$this ->upsShip->package(array('UnitOfDimensions' => $package[8],
						'UnitOfPackageWeight' => $package[4], 
						'weight' => $package[3],
						'code' => $package[1],
						'length' => $package[5],
						'width' => $package[6],
						'height' => $package[7]
					));
				}
				$this ->upsShip->shipment(array('description' => '','serviceType' => $_POST['shipment_options']));
				$this ->upsShip->buildRequestXML();
				$responseArray = $this ->upsShip->responseArray();
				$ShipmentDigest = $responseArray['ShipmentConfirmResponse']['ShipmentDigest']['VALUE'];
				$this ->upsShip->buildShipmentAcceptXML($ShipmentDigest);
				$responseArray = $this ->upsShip->responseArray();
				$htmlImage = $responseArray['ShipmentAcceptResponse']['ShipmentResults']['PackageResults']['LabelImage']['GraphicImage']['VALUE'];
				
				$data_shipment = array(
					'origin_firstname' => $this ->lib->escape($_POST['origin_firstname']),
					'origin_lastname' => $this ->lib->escape($_POST['origin_lastname']),
					'origin_address' => $this ->lib->escape($_POST['origin_address']),
					'origin_city' => $this ->lib->escape($_POST['origin_city']),
					'origin_state' => $this ->lib->escape($_POST['origin_state']),
					'origin_zipcode' => $this ->lib->escape($_POST['origin_zipcode']),
					'origin_phone' => $this ->lib->escape($_POST['origin_phone']),
					'origin_mail' => $this ->lib->escape($_POST['origin_mail']),
					'origin_country' => $this ->lib->escape($_POST['origin_country']),
					'destination_firstname' => $this ->lib->escape($_POST['destination_firstname']),
					'destination_lastname' => $this ->lib->escape($_POST['destination_lastname']),
					'destination_address' => $this ->lib->escape($_POST['destination_address']),
					'destination_city' => $this ->lib->escape($_POST['destination_city']),
					'destination_state' => $this ->lib->escape($_POST['destination_state']),
					'destination_zipcode' => $this ->lib->escape($_POST['destination_zipcode']),
					'destination_phone' => $this ->lib->escape($_POST['destination_phone']),
					'destination_mail' => $this ->lib->escape($_POST['destination_mail']),
					'destination_country' => $this ->lib->escape($_POST['destination_country']),
					'shipment_options' => $this ->lib->escape($_POST['shipment_options']),
					'transaction_ID' => $this ->lib->escape($_POST['transaction_ID']),
					'tracking_number' => $this ->lib->escape($_POST['tracking_number']),
					'ship_date' => (trim($_POST['ship_date'])!='')? $_POST['ship_date']:date('m/d/Y',$this->lib->getTimeGMT()),
					'expected_delivery' => trim($_POST['expected_delivery']),
					'label' => $htmlImage
				);
				$this ->db->where("skey",$skey);
				$this ->db->update("shipments", $data_shipment);
				foreach($_POST['packages'] as $package){	//2
					$package_update = array(
						'shipment_ID' => $skey,
						'pkey' => $package[0],
						'package_type' => $package[1],
						'declared_value' => $package[2],
					//	'tracking_number' => $package[3],
						'weight' => $package[3],
						'weight_units' => $package[4],
						'length' => $package[5],
						'width' => $package[6],
						'height' => $package[7],
						'units_measurement' => $package[8]
					);
					$this ->db->where("pkey",$package[0]);
					$this ->db->update("packages", $package_update);
				}	//2	
			}	//1
		}	//0
		return array('error'=>$error);
	}//end editups_save_shipment function
	
	public function editusps_loadData()
	{
		$roles = $this ->author ->objlogin ->role;
		$okey = !empty($_GET['okey'])?$this ->lib ->escape($_GET['okey']):'';
		$skey = !empty($_GET['skey'])?$this ->lib ->escape($_GET['skey']):'';
		
		$destination_firstname = '';
		$destination_lastname = '';
		$destination_address = '';
		$destination_city = '';
		$destination_state = '';
		$destination_zipcode = '';
		$destination_phone = '';
		$destination_mail = '';
		$destination_country = '';
		
		$origin_firstname = $this ->author ->objlogin->firstname;
		$origin_lastname = $this ->author ->objlogin->lastname;
		$origin_address = $this ->author ->objlogin->address;
		$origin_city = $this ->author ->objlogin->city;
		$origin_state = $this ->author ->objlogin->state;
		$origin_zipcode = $this ->author ->objlogin->zipcode;
		$origin_phone = $this ->author ->objlogin->phone;
		$origin_mail = $this ->author ->objlogin->mail;
		$origin_country = $this ->author ->objlogin->country;
		
		$shipment_options = '';
		$transaction_ID = '';
		$tracking_number = '';
		$ship_date = '';
		$expected_delivery = '';
		
		$ship_label = '';
		$shipping_fee = 0;
		$base_price = 0;
		$oid = 0;
		$count = 0;
		$access_key = '';
		$UPS_Shipper = '';
		$UPS_userid = '';
		$UPS_Password = '';
		$ong_chu = $this ->lib ->__loadBoss__();
		
		$re = $this ->db->query("select * from shipments where skey = '$skey'");
		if($re-> num_rows() >0){
			$row = $re->row_array();
			$destination_firstname = $row['destination_firstname'];
			$destination_lastname = $row['destination_lastname'];
			$destination_address = $row['destination_address'];
			$destination_city = $row['destination_city'];
			$destination_state = $row['destination_state'];
			$destination_zipcode = $row['destination_zipcode'];
			$destination_phone = $row['destination_phone'];
			$destination_mail = $row['destination_mail'];
			$destination_country = $row['destination_country'];
			
			$origin_firstname = $row['origin_firstname'];
			$origin_lastname = $row['origin_lastname'];
			$origin_address = $row['origin_address'];
			$origin_city = $row['origin_city'];
			$origin_state = $row['origin_state'];
			$origin_zipcode = $row['origin_zipcode'];
			$origin_phone = $row['origin_phone'];
			$origin_mail = $row['origin_mail'];
			$origin_country = $row['origin_country'];
			
			$transaction_ID = $row['transaction_ID'];
			$tracking_number = $row['tracking_number'];	
			$ship_date = $row['ship_date'];
			$expected_delivery = $row['expected_delivery'];
			$access_key = $row['access_key'];
			
			if($destination_country == 'US'){
				foreach($this ->__USPS_domestic_services__ as $key => $label_weight){
					$select_ = '';
					if($key == $row['shipment_options']) $select_ = 'selected="selected"';
					$shipment_options .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
				}
			}else{
				$units_measurement_select = '';
				foreach($this ->__USPS_international_services__ as $key => $label_weight){
					$shipment_options .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
				}	
			}
		}
		
		$re = $this ->db->query("select orderid,shipping_name,shipping_address,shipping_city,shipping_state,shipping_zip,shipping_phone,billing_email,shipping_key,shipping_fee from orders where okey = '$okey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$oid = $row['orderid'];
			$base_price	= $row["shipping_fee"];
			$ship_label = $this ->database ->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");	
		}
		
		$sql_manufacturer = '';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
		}else{
			$shipping_fee += $base_price;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("select order_detais.last_shipping,order_detais.quality,items.itm_key from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
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
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$shipping_rate = $base_price;
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$shipping_rate += round($row['last_shipping']*$row['quality'], 2);
			}
			$shipping_fee += round($shipping_rate, 2);
		}//0
		
		$package_arr = array();
		$packages = array();
		$re = $this ->db->query("select * from packages where shipment_ID = '$skey' and okey = '$okey'");
		foreach($re ->result_array() as $row){
			
			$pkey = $row['pkey'];
			
			$id = $row['id'];
			$Products = '';
			$sql_manufacturer = '';
			if($roles['rid'] == MANUFACTURER){
				$sql_manufacturer = "and items.uid = ".$ong_chu;
			}
			$re_1 = $this ->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
			foreach($re_1 ->result_array() as $row_1){
				$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
			}
			if($Products != ''){
				$Products = substr($Products, 0, strlen($Products)-4);
			}elseif($Products == '') continue;
			
			$packages[] = $pkey;
			
			$weight_units_select = '';
			foreach($this ->__weight_units__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['weight_units']) $select_ = 'selected="selected"';
				$weight_units_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
			
			$_units_measurement_select = '';
			foreach($this ->__units_measurement__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['units_measurement']) $select_ = 'selected="selected"';
				$_units_measurement_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
			
			$_package_type_select = '';
			foreach($this ->__USPS_package_size__ as $key => $label_weight){
				$select_ = '';
				if($key == $row['package_type']) $select_ = 'selected="selected"';
				$_package_type_select .= '<option value="'.$key.'" '.$select_.'>'.$label_weight.'</option>';	
			}
			$package_arr[] = array(
				'height' =>$row['height'],
				'width' =>$row['width'],
				'length' =>$row['length'],
				'_units_measurement_' =>$_units_measurement_select,
				'_weight_units_' =>$weight_units_select,
				'weight' =>$row['weight'],
				'pack_Tracking_number' =>$row['tracking_number'],
				'declared_value' =>$row['declared_value'],
				'package_type' =>$_package_type_select,
				'products' =>$Products,
				'pkey' =>$pkey,
			);
		}		
		return array(	
			"package_arr" =>$package_arr,	
			"load_countries" => "dataCountries = ".json_encode($this ->lib ->__loadDataCountries__()).";",
			"loadOriginCountryValue" => "if(document.getElementById('origin_country')) document.getElementById('origin_country').value='".$origin_country."';",
			"loadOriginStateValue" => "if(document.getElementById('origin_state')) document.getElementById('origin_state').value='".$origin_state."';",
			"loadDestinationCountryValue" => "if(document.getElementById('destination_country')) document.getElementById('destination_country').value='".$destination_country."';",
			"loadDestinationStateValue" => "if(document.getElementById('destination_state')) document.getElementById('destination_state').value='".$destination_state."';",
			"ship_label" => $ship_label,
			"shipping_fee" => number_format($shipping_fee, 2),
			"shipment_options" => $shipment_options,
			"transaction_ID" => $transaction_ID,
			"tracking_number" => $tracking_number,
			"ship_date" => $ship_date,
			"expected_delivery" => $expected_delivery,
			"access_key" => $access_key,
			"destination_firstname" => $destination_firstname,
			"destination_lastname" => $destination_lastname,
			"destination_address" => $destination_address,
			"destination_city" => $destination_city,
			"destination_state" => $destination_state,
			"destination_zipcode" => $destination_zipcode,
			"destination_phone" => $destination_phone,
			"destination_mail" => $destination_mail,
			"origin_firstname" => $origin_firstname,
			"origin_lastname" => $origin_lastname,
			"origin_address" => $origin_address,
			"origin_city" => $origin_city,
			"origin_state" => $origin_state,
			"origin_zipcode" => $origin_zipcode,
			"origin_phone" => $origin_phone,
			"origin_mail" => $origin_mail,
			"skey" => $skey,
			"load_packages" => "var packages = ".json_encode($packages).";",
		);
	}//end editusps_loadData function
	
	public function editusps_save_shipment()
	{
		$error = '';
		if(isset($_POST['packages']) && is_array($_POST['packages']) && count($_POST['packages']) > 0){	//0
			$okey = (isset($_POST['okey']))?$this ->lib->escape($_POST['okey']):'';
			$skey = (isset($_POST['skey']))?$this ->lib->escape($_POST['skey']):'';
			$sid = 0;
			$weight = 0;
			$width_ = 0;
			$height_ = 0;
			$length_ = 0;
			$origin_phone = str_replace(" ", "", trim($_POST['origin_phone']));
			$origin_phone = str_replace("(", "", $origin_phone);
			$origin_phone = str_replace(")", "", $origin_phone);
			$origin_phone = str_replace("-", "", $origin_phone);
			if(strlen($origin_phone) > 10) $origin_phone = substr($origin_phone, 0, 10);
			$soluong_package = 1;
			foreach($_POST['packages'] as $package){	//2
				$donvi = $package[4];
				$weight += $package[3];
				$width_ += $package[6];
				$height_ += $package[7];
				$length_ += $package[5];
				$soluong_package ++;
			}
			if($okey != ''){	//1
				$img_data = '';
				$tracking_number = '';
				$transaction_ID = '';
				$check = false;
				$ship_date = (trim($_POST['ship_date'])!='')? trim($_POST['ship_date']):date('m/d/Y',$this->lib->getTimeGMT());
				$int_ship_date = strtotime($ship_date);
				$int_today = strtotime(date("m/d/Y"));
				if($int_ship_date > $int_today + 3*24*60*60 || $int_ship_date < $int_today){
					$ship_date = date("m/d/Y");	
				}
				$access_key = (isset($_POST['access_key'])&&$_POST['access_key']!='')?$_POST['access_key']:'';
				$service_type = '';
				if($_POST['destination_country'] == 'US'){
					$service_type = 'PriorityMail';	
				}else{
					$service_type = 'International';	
				}
				// Hiep work
				$re = $this ->db->query("select * from shipments where skey = '$skey'");
				if($re ->num_rows() >0){
					$row = $re->row_array();
					$sid = $row['id'];
					$CarrierPickupCancel = "https://secure.shippingapis.com/shippingapi.dll?API=CarrierPickupCancel&XML=
					<CarrierPickupCancelRequest USERID=\"".$row['access_key']."\">
					<FirmName>Bellavie Network.</FirmName>
					<SuiteOrApt></SuiteOrApt>
					<Address2>".$row['origin_address']."</Address2>
					<Urbanization></Urbanization>
					<City>>".$row['origin_city']."</City>
					<State>".$row['origin_state']."</State>
					<ZIP5>".$row['origin_zipcode']."</ZIP5>
					<ZIP4></ZIP4>
					<ConfirmationNumber>".$row['transaction_ID']."</ConfirmationNumber>
					</CarrierPickupCancelRequest>";
					$CarrierPickupCancel = $this ->lib ->__grabURL__($CarrierPickupCancel);
					$CarrierPickupCancel = file_get_contents($CarrierPickupCancel); 		
				}
				$data_pickup = "https://secure.shippingapis.com/shippingapi.dll?API=CarrierPickupSchedule&XML=
				<CarrierPickupScheduleRequest USERID=\"$access_key\">
				<FirstName>".$_POST['origin_firstname']."</FirstName>
				<LastName>".$_POST['origin_lastname']."</LastName>
				<FirmName>Bellavie Network.</FirmName>
				<SuiteOrApt></SuiteOrApt>
				<Address2>".trim($_POST['origin_address'])."</Address2>
				<Urbanization></Urbanization>
				<City>".trim($_POST['origin_city'])."</City>
				<State>".$_POST['origin_state']."</State>
				<ZIP5>".$_POST['origin_zipcode']."</ZIP5>
				<ZIP4></ZIP4>
				<Phone>".$origin_phone."</Phone>
				<Extension></Extension>
				<Package>
					 <ServiceType>$service_type</ServiceType>
					 <Count>$soluong_package</Count>
				</Package>
				<EstimatedWeight>$weight</EstimatedWeight>
				<PackageLocation>Front Door</PackageLocation>
				<SpecialInstructions></SpecialInstructions>
				</CarrierPickupScheduleRequest>";
					
				if($_POST['destination_country'] == 'US'){
					$data = "https://secure.shippingapis.com/shippingapi.dll?API=DeliveryConfirmationV3&XML=
					<DeliveryConfirmationV3.0Request USERID=\"$access_key\">
					<Option>1</Option>
					<ImageParameters/>
					<FromName>".$_POST['origin_firstname'].' '.$_POST['origin_lastname']."</FromName>
					<FromFirm>Bellavie Network</FromFirm>
					<FromAddress1/>
					<FromAddress2>".trim($_POST['origin_address'])."</FromAddress2>
					<FromCity>".trim($_POST['origin_city'])."</FromCity>
					<FromState>".$_POST['origin_state']."</FromState>
					<FromZip5>".$_POST['origin_zipcode']."</FromZip5>
					<FromZip4></FromZip4>
					
					<ToName>".$_POST['destination_firstname'].' '.$_POST['destination_lastname']."</ToName>
					<ToFirm>".$_POST['destination_firstname'].' '.$_POST['destination_lastname']."</ToFirm>
					<ToAddress1></ToAddress1>
					<ToAddress2>".trim($_POST['destination_address'])."</ToAddress2>
					<ToCity>".trim($_POST['destination_city'])."</ToCity>
					<ToState>".$_POST['destination_state']."</ToState>
					<ToZip5>".trim($_POST['destination_zipcode'])."</ToZip5>
					<ToZip4></ToZip4>
					
					<WeightInOunces>$weight</WeightInOunces>
					<ServiceType>Priority</ServiceType>
					
					<POZipCode>".$_POST['origin_zipcode']."</POZipCode>
					<ImageType>PDF</ImageType>
					<LabelDate>".$ship_date."</LabelDate>
					<CustomerRefNo>".$okey."</CustomerRefNo>
					<AddressServiceRequested>TRUE</AddressServiceRequested>
					</DeliveryConfirmationV3.0Request>";
					$data = $this ->lib ->__grabURL__($data);
					$content = file_get_contents($data);
					if($content != ''){
						$arr_data = $this ->lib ->partitionString("<DeliveryConfirmationLabel>", "</DeliveryConfirmationLabel>", $content);
						$img_data = isset($arr_data[1])?$arr_data[1]:'';
						if($img_data != ''){
							$arr_data = $this ->lib ->partitionString("<DeliveryConfirmationNumber>", "</DeliveryConfirmationNumber>", $content);
							$tracking_number = isset($arr_data[1])?$arr_data[1]:'';
							if($tracking_number != '') $tracking_number = substr($tracking_number, 8);	
							$check = true;
						}else{
							$arr_data = $this ->lib ->partitionString("<Description>", "</Description>", $content);
							$error = isset($arr_data[1])?$arr_data[1]:'';	
						}	
					}else{
						$error = 'Can not send request USPS.';	
					}
				}else{
					$tblcontries = array();
					$re = $this ->db->query("select * from tblcontries");
					foreach($re->result_array() as $row){
						$tblcontries[$row['code']] = $row['name'];	
					}
					$destination_phone = str_replace(" ", "", trim($_POST['destination_phone']));
					$destination_phone = str_replace("(", "", $destination_phone);
					$destination_phone = str_replace(")", "", $destination_phone);
					$destination_phone = str_replace("-", "", $destination_phone);
					if(strlen($destination_phone) > 10) $destination_phone = substr($destination_phone, 0, 10);
					$data = "https://secure.shippingapis.com/shippingapi.dll?API=FirstClassMailIntlCertify&XML=<FirstClassMailIntlCertifyRequest USERID=\"$access_key\">
						<Option/>
						<Revision>2</Revision>
						<ImageParameters/>
						<FromFirstName>".$_POST['origin_firstname']."</FromFirstName>
						<FromMiddleInitial></FromMiddleInitial>
						<FromLastName>".$_POST['origin_lastname']."</FromLastName>
						<FromFirm>Bellavie Network</FromFirm>
						<FromAddress1></FromAddress1>
						<FromAddress2>".trim($_POST['origin_address'])."</FromAddress2>
						<FromCity>".trim($_POST['origin_city'])."</FromCity>
						<FromState>".trim($_POST['origin_state'])."</FromState>
						<FromZip5>".trim($_POST['origin_zipcode'])."</FromZip5>
						<FromPhone>".$origin_phone."</FromPhone>
					
						<ToName>".trim($_POST['destination_firstname']).' '.trim($_POST['destination_lastname'])."</ToName>
						<ToFirm></ToFirm>
						<ToAddress1></ToAddress1>
						<ToAddress2>".trim($_POST['destination_address'])."</ToAddress2>
						<ToAddress3>".trim($_POST['destination_address'])."</ToAddress3>
						<ToCity>".trim($_POST['destination_city'])."</ToCity>
						<ToCountry>".(isset($tblcontries[$_POST['destination_country']])?$tblcontries[$_POST['destination_country']]:$_POST['destination_country'])."</ToCountry>
						<ToPostalCode>".trim($_POST['destination_zipcode'])."</ToPostalCode>
						<ToPOBoxFlag>N</ToPOBoxFlag>
						<ToPhone>".$destination_phone."</ToPhone>
						<ToFax></ToFax>
						<ToEmail>".trim($_POST['destination_mail'])."</ToEmail>
						<FirstClassMailType>PARCEL</FirstClassMailType>
						<ShippingContents>";
							foreach($_POST['packages'] as $package){	//2
								$re = $this ->db->query("select items.itm_name,items.itm_model,items.weight,items.current_cost,packages_items.qty from packages join packages_items join items on packages.id = packages_items.package_id and items.itm_id = packages_items.product_id where packages.pkey = '".$package[0]."'");
								foreach($re->result_array() as $row){
									$weight += $row['weight'];
									$data .= "<ItemDetail>
										<Description>".$row['itm_name']."</Description>
										<Quantity>".$row['qty']."</Quantity>
										<Value>".$row['current_cost']."</Value>
										<NetPounds>".($row['weight']>0?$row['weight']:1)."</NetPounds>
										<NetOunces>".($row['weight']>0?$row['weight']:1)."</NetOunces>
										<HSTariffNumber>".$row['itm_model']."</HSTariffNumber>
										<CountryOfOrigin>".$_POST['origin_country']."</CountryOfOrigin>
									</ItemDetail>";			
								}
							}
						$data .= "</ShippingContents>
						<GrossPounds>".($weight>0?$weight:1)."</GrossPounds>
						<GrossOunces>".($weight>0?$weight:1)."</GrossOunces>
						<Machinable>false</Machinable>
						<ContentType>GIFT</ContentType>
						<Agreement>Y</Agreement>
						<Comments>FirstClassMailIntl Comments</Comments>
						<ImageType>PDF</ImageType>
						<ImageLayout>ONEPERFILE</ImageLayout>
						<HoldForManifest>N</HoldForManifest>
						<EELPFC>30.37a</EELPFC>
						<Container>RECTANGULAR</Container>
						<Size>REGULAR</Size>
						<Length>".($length_>0?$length_:1)."</Length>
						<Width>".($width_>0?$width_:1)."</Width>
						<Height>".($height_>0?$height_:1)."</Height>
						<Girth>1</Girth>
					</FirstClassMailIntlCertifyRequest>";
					$data = $this ->lib ->__grabURL__($data);
					$content = file_get_contents($data);
					if($content != ''){
						$arr_data = $this ->lib ->partitionString("<LabelImage>", "</LabelImage>", $content);
						$img_data = isset($arr_data[1])?$arr_data[1]:'';
						if($img_data != ''){
							$arr_data = $this ->lib ->partitionString("<BarcodeNumber>", "</BarcodeNumber>", $content);
							$tracking_number = isset($arr_data[1])?$arr_data[1]:'';	
							$check = true;
						}else{
							$arr_data = $this ->lib ->partitionString("<Description>", "</Description>", $content);
							$error = isset($arr_data[1])?$arr_data[1]:'';	
						}
					}else{
						$error = 'Can not send request USPS.';		
					}
				}
				if($check == true){
					$data_pickup =$this ->lib -> __grabURL__($data_pickup);
					$content_pickup = file_get_contents($data_pickup);
					if($content_pickup != ''){
						$confirm_data =$this ->lib -> partitionString("<ConfirmationNumber>", "</ConfirmationNumber>", $content_pickup); 
						if($confirm_data != ''){
							$transaction_ID = isset($confirm_data[1])?$confirm_data[1]:'';	
						}else{
							$arr_data = $this ->lib ->partitionString("<Description>", "</Description>", $content_pickup);
							$error = isset($arr_data[1])?$arr_data[1]:'';	
						}
					}else{
						$error = 'Can not send pickup request USPS';	
					}
				}
				if($error == ''){
					$data_shipment = array(
						'origin_firstname' => $this ->lib ->escape($_POST['origin_firstname']),
						'origin_lastname' => $this ->lib ->escape($_POST['origin_lastname']),
						'origin_address' => $this ->lib ->escape($_POST['origin_address']),
						'origin_city' => $this ->lib ->escape($_POST['origin_city']),
						'origin_state' => $this ->lib ->escape($_POST['origin_state']),
						'origin_zipcode' => $this ->lib ->escape($_POST['origin_zipcode']),
						'origin_phone' => $this ->lib ->escape($_POST['origin_phone']),
						'origin_mail' => $this ->lib ->escape($_POST['origin_mail']),
						'origin_country' => $this ->lib ->escape($_POST['origin_country']),
						'destination_firstname' => $this ->lib ->escape($_POST['destination_firstname']),
						'destination_lastname' => $this ->lib ->escape($_POST['destination_lastname']),
						'destination_address' => $this ->lib ->escape($_POST['destination_address']),
						'destination_city' => $this ->lib ->escape($_POST['destination_city']),
						'destination_state' => $this ->lib ->escape($_POST['destination_state']),
						'destination_zipcode' => $this ->lib ->escape($_POST['destination_zipcode']),
						'destination_phone' => $this ->lib ->escape($_POST['destination_phone']),
						'destination_mail' => $this ->lib ->escape($_POST['destination_mail']),
						'destination_country' => $this ->lib ->escape($_POST['destination_country']),
						'shipment_options' => $this ->lib ->escape($_POST['shipment_options']),
						'access_key' => $access_key,
						'transaction_ID' => $transaction_ID,
						'tracking_number' => $tracking_number,
	
						'ship_date' => $ship_date,
						'expected_delivery' => trim($_POST['expected_delivery']),
						'label' => $img_data,
						'date_active' => time()
					);
					$this ->db->where("skey",$skey);
					$this ->db->update("shipments", $data_shipment);
					foreach($_POST['packages'] as $package){	//2
						$package_update = array(
							'shipment_ID' => $skey,
							'pkey' => $package[0],
							'package_type' => $package[1],
							'declared_value' => $package[2],
							'tracking_number' => $tracking_number,
							'weight' => $package[3],
							'weight_units' => $package[4],
							'length' => $package[5],
							'width' => $package[6],
							'height' => $package[7],
							'units_measurement' => $package[8]
						);
						$this ->db->where("pkey",$package[0]);
						$this ->db->update("packages", $package_update);
					}	//2	
					$this ->lib ->__sendMailShipment__($sid);	
				}
			}	//1
		}	//0
		return array('error'=>$error);
	}//end editusps_save_shipment function
	
	public function manually_loadData()
	{
		$roles = $this ->author ->objlogin ->role;
		$okey = !empty($_GET['okey'])?$this ->lib ->escape($_GET['okey']):'';
		$packages = !empty($_GET['packages'])?$_GET['packages']:array();
		
		if(!is_array($packages) || count($packages) <= 0){
			$this ->system ->URLgoto("store/orders/details/".$okey);
			exit;
		}
		
		$weight_units_select = '';
		foreach($this ->__weight_units__ as $key => $label_weight){
			$weight_units_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$units_measurement_select = '';
		foreach($this->__units_measurement__ as $key => $label_weight){
			$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$destination_firstname = '';
		$destination_lastname = '';
		$destination_address = '';
		$destination_city = '';
		$destination_state = '';
		$destination_zipcode = '';
		$destination_phone = '';
		$destination_mail = '';
		$destination_country = '';
		
		$origin_firstname = $this ->author ->objlogin->firstname;
		$origin_lastname = $this ->author ->objlogin->lastname;
		$origin_address = $this ->author ->objlogin->address;
		$origin_city = $this ->author ->objlogin->city;
		$origin_state = $this ->author ->objlogin->state;
		$origin_zipcode = $this ->author ->objlogin->zipcode;
		$origin_phone = $this ->author ->objlogin->phone;
		$origin_mail = $this ->author ->objlogin->mail;
		
		$ship_label = '';
		$shipping_fee = 0;
		$base_price = 0;
		$oid = 0;
		$count = 0;
		$count_shipping_free = 0;
		$ong_chu = $this ->lib ->__loadBoss__();
		
		$re = $this ->db->query("select orderid,shipping_name,shipping_address,shipping_country,shipping_city,shipping_state,shipping_zip,shipping_phone,billing_email,shipping_key,shipping_fee from orders where okey = '$okey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$oid = $row['orderid'];
			$shipping_name = $row['shipping_name'];
			$arr_shipping_name = explode(" ", trim($shipping_name));
			$firstname = '';
			$lastname = '';
			if(isset($arr_shipping_name[0])) $firstname = $arr_shipping_name[0];
			for($i = 1; $i < count($arr_shipping_name); $i++){
				$lastname .= $arr_shipping_name[$i].' ';	
			}
			$lastname = trim($lastname);
			$destination_firstname = $firstname;
			$destination_lastname = $lastname;
			$destination_address = $row['shipping_address'];
			$destination_city = $row['shipping_city'];
			$destination_state = $row['shipping_state'];
			$destination_zipcode = $row['shipping_zip'];
			$destination_phone = $row['shipping_phone'];
			$destination_mail = $row['billing_email'];
			$destination_country = $row['shipping_country'];
			
			$base_price			= $row["shipping_fee"];
			$ship_label = $this ->database ->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");	
		}
		
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == MANUFACTURER){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}else{
			$shipping_fee += $base_price;	
		}
		$arrPromotions = array();
		$re = $this ->db->query($sql_orders_promotions);
		foreach($re->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("select order_detais.last_shipping,order_detais.quality,items.itm_key,items.uid from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
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
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$shipping_rate = $base_price;
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$shipping_rate += round($row['last_shipping']*$row['quality'], 2);
			}
			$shipping_fee += round($shipping_rate, 2);
		}//0
		$package_arr = array();
		foreach($packages as $pkey){
			$re = $this ->db->query("select * from packages where pkey = '$pkey'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				
				
				$id = $row['id'];
				$Products = '';
				$sql_manufacturer = '';
				if($roles['rid'] == MANUFACTURER){
					$sql_manufacturer = "and items.uid = ".$ong_chu;
				}
				$re_1 = $this ->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
				foreach($re_1 ->result_array() as $row_1){
					$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
				}
				if($Products != ''){
					$Products = substr($Products, 0, strlen($Products)-4);
				}elseif($Products == '') continue;
				
				$package_arr[] = array(
					'products' =>$Products,
					'pkey' =>$pkey
				);
			}	
		}
		return array(
			'package_arr' =>$package_arr,
			'_weight_units_' => $weight_units_select,
			'_units_measurement_' => $units_measurement_select,
			'load_countries' => "dataCountries = ".json_encode($this ->lib ->__loadDataCountries__()).";",
			'loadOriginCountryValue' => "if(document.getElementById('origin_country')) document.getElementById('origin_country').value='".$this->author ->objlogin->country."';",
			'loadOriginStateValue' => "if(document.getElementById('origin_state')) document.getElementById('origin_state').value='".$this->author ->objlogin->state."';",
			'load_packages' =>"var packages = ".json_encode($packages).";",
			'loadDestinationCountryValue' =>"if(document.getElementById('destination_country')) document.getElementById('destination_country').value='".$destination_country."';",
			'loadDestinationStateValue' =>"if(document.getElementById('destination_state')) document.getElementById('destination_state').value='".$destination_state."';",
			'ship_label' =>$ship_label,
			'shipping_fee' =>number_format($shipping_fee, 2),
			'destination_firstname' =>$destination_firstname,
			'destination_lastname' =>$destination_lastname,
			'destination_address' =>$destination_address,
			'destination_city' =>$destination_city,
			'destination_state' =>$destination_state,
			'destination_zipcode' =>$destination_zipcode,
			'destination_phone' =>$destination_phone,
			'destination_mail' =>$destination_mail,
			'origin_firstname' =>$origin_firstname,
			'origin_lastname'=> $origin_lastname,
			'origin_address' => $origin_address,
			'origin_city' => $origin_city,
			'origin_state' => $origin_state,
			'origin_zipcode' => $origin_zipcode,
			'origin_phone' => $origin_phone,
			'origin_mail' => $origin_mail,
		);
	}//end manually_loadData function
	
	public function manually_save_shipment()
	{
		$obj_submit = $_POST['obj_submit'];
		$error = '';
		if(isset($obj_submit['packages_post']) && is_array($obj_submit['packages_post']) && count($obj_submit['packages_post']) > 0){	//0
			$okey = (isset($obj_submit['okey']))?$obj_submit['okey']:'';
			if($okey != ''){	//1
				$skey = $this ->lib ->GeneralRandomNumberKey(8);
				$re = $this ->db->query("select id from shipments where skey = '$skey'");
				while($re->num_rows() >0){
					$skey = $this ->lib ->GeneralRandomNumberKey(8);
					$re = $this ->db->query("select id from shipments where skey = '$skey'");
				}
				$data_shipment = array(
					'skey' => $skey,
					'okey' => $okey,
					'shipping_method' => 0,
					'origin_firstname' => $this ->lib ->escape($obj_submit['origin_firstname']),
					'origin_lastname' => $this ->lib ->escape($obj_submit['origin_lastname']),
					'origin_address' => $this ->lib ->escape($obj_submit['origin_address']),
					'origin_city' => $this ->lib ->escape($obj_submit['origin_city']),
					'origin_state' => $this ->lib ->escape($obj_submit['origin_state']),
					'origin_zipcode' => $this ->lib ->escape($obj_submit['origin_zipcode']),
					'origin_phone' => $this ->lib ->escape($obj_submit['origin_phone']),
					'origin_mail' => $this ->lib ->escape($obj_submit['origin_mail']),
					'origin_country' => $this ->lib ->escape($obj_submit['origin_country']),
					'destination_firstname' => $this ->lib ->escape($obj_submit['destination_firstname']),
					'destination_lastname' => $this ->lib ->escape($obj_submit['destination_lastname']),
					'destination_address' => $this ->lib ->escape($obj_submit['destination_address']),
					'destination_city' => $this ->lib ->escape($obj_submit['destination_city']),
					'destination_state' => $this ->lib ->escape($obj_submit['destination_state']),
					'destination_zipcode' => $this ->lib ->escape($obj_submit['destination_zipcode']),
					'destination_phone' => $this ->lib ->escape($obj_submit['destination_phone']),
					'destination_mail' => $this ->lib ->escape($obj_submit['destination_mail']),
					'destination_country' => $this ->lib ->escape($obj_submit['destination_country']),
					'shipment_options' => $this ->lib ->escape($obj_submit['shipment_options']),
					'transaction_ID' => $this ->lib ->escape($obj_submit['transaction_ID']),
					'tracking_number' => $this ->lib ->escape($obj_submit['tracking_number']),
					'ship_date' => (isset($obj_submit['ship_date'])&&trim($obj_submit['ship_date'])!='')?$obj_submit['ship_date']:date('m/d/Y',$this->lib->getTimeGMT()),
					'expected_delivery' => trim($obj_submit['expected_delivery']),
					'shipping_cost' => trim($obj_submit['shipping_cost'])
				);
				$this ->db->insert("shipments", $data_shipment);
				$id = $this ->db->insert_id();
				if(is_numeric($id) && $id > 0){
					foreach($obj_submit['packages_post'] as $package){	//2
						$package_update = array(
							'shipment_ID' => $skey,
							'pkey' => $package[0],
							'package_type' => $package[1],
							'declared_value' => $package[2],
							'weight' => $package[3],
							'tracking_number' => $this ->lib ->escape($obj_submit['tracking_number']),
							'weight_units' => $package[4],
							'length' => $package[5],
							'width' => $package[6],
							'height' => $package[7],
							'units_measurement' => $package[8]
						);
						$this ->db->where('pkey',$package[0]);
						$this ->db->update("packages", $package_update);
					}	//2	
					$this ->lib ->__sendMailShipment__($id);		
				}else{
					$error = 'Can not insert to database.';	
				}
			}	//1
		}	//0
		return array('error'=>$error);
	}//end manually_save_shipment function
	
	public function ups_loadData()
	{
		$roles = $this ->author->objlogin ->role;
		$okey = !empty($_GET['okey'])?$this ->lib->escape($_GET['okey']):'';
		$packages = isset($_GET['packages'])?$_GET['packages']:array();
		if(!is_array($packages) || count($packages) <= 0){
			$this ->system ->URLgoto("store/orders/details/".$okey);
			exit;
		}	
		$weight_units_select = '';
		foreach($this ->__weight_units__ as $key => $label_weight){
			$weight_units_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$units_measurement_select = '';
		foreach($this ->__units_measurement__ as $key => $label_weight){
			$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$units_measurement_select = '';
		foreach($this ->__UPS_Service__ as $key => $label_weight){
			$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$units_measurement_select = '';
		foreach($this ->__package_type__ as $key => $label_weight){
			$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$destination_firstname = '';
		$destination_lastname = '';
		$destination_country = '';
		$destination_address = '';
		$destination_city = '';
		$destination_state = '';
		$destination_zipcode = '';
		$destination_phone = '';
		$destination_mail = '';
		
		$origin_firstname = $this ->author ->objlogin->firstname;
		$origin_lastname = $this ->author ->objlogin->lastname;
		$origin_address = $this ->author ->objlogin->address;
		$origin_city = $this ->author ->objlogin->city;
		$origin_state = $this ->author ->objlogin->state;
		$origin_zipcode = $this ->author ->objlogin->zipcode;
		$origin_phone = $this ->author ->objlogin->phone;
		$origin_mail = $this ->author ->objlogin->mail;
		
		$ship_label = '';
		$shipping_fee = 0;
		$base_price = 0;
		$oid = 0;
		$count = 0;
		$count_shipping_free = 0;
		$ong_chu = $this ->lib -> __loadBoss__();
		
		$re = $this ->db->query("select orderid,shipping_name,shipping_address,shipping_country,shipping_city,shipping_state,shipping_zip,shipping_phone,billing_email,shipping_key,shipping_fee from orders where okey = '$okey'");
		if($re->num_rows() >0){
			$row = $re ->row_array();
			$oid = $row['orderid'];
			$shipping_name = $row['shipping_name'];
			$arr_shipping_name = explode(" ", trim($shipping_name));
			$firstname = '';
			$lastname = '';
			if(isset($arr_shipping_name[0])) $firstname = $arr_shipping_name[0];
			for($i = 1; $i < count($arr_shipping_name); $i++){
				$lastname .= $arr_shipping_name[$i].' ';	
			}
			$lastname = trim($lastname);
			$destination_firstname = $firstname;
			$destination_lastname = $lastname;
			$destination_address = $row['shipping_address'];
			$destination_city = $row['shipping_city'];
			$destination_state = $row['shipping_state'];
			$destination_zipcode = $row['shipping_zip'];
			$destination_phone = $row['shipping_phone'];
			$destination_mail = $row['billing_email'];
			$destination_country = $row['shipping_country'];
			
			$base_price			= $row["shipping_fee"];
			$ship_label = $this ->database ->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");	
		}
		
		$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
		$sql_manufacturer = '';
		if($roles['rid'] == 5){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
			$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
		}else{
			$shipping_fee += $base_price;	
		}
		$arrPromotions = array();
		$re = $this ->db->query($sql_orders_promotions);
		foreach($re ->result_array() as $row){
			$arrPromotions[] = $row;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("select order_detais.last_shipping,order_detais.quality,items.itm_key,items.uid from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
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
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$shipping_rate = $base_price;
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$shipping_rate += round($row['last_shipping']*$row['quality'], 2);
			}
			$shipping_fee += round($shipping_rate, 2);
		}//0
		$package_arr = array();
		foreach($packages as $pkey){
			$re = $this ->db->query("select * from packages where pkey = '$pkey'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				
				
				$id = $row['id'];
				$Products = '';
				$sql_manufacturer = '';
				if($roles['rid'] == 5){
					$sql_manufacturer = "and items.uid = ".$ong_chu;
				}
				$re_1 = $this ->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
				foreach($re_1 ->result_array() as $row_1){
					$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
				}
				if($Products != ''){
					$Products = substr($Products, 0, strlen($Products)-4);
				}elseif($Products == '') continue;
				
				$package_arr[] = array(
					'products' => $Products,
					'pkey' => $pkey,
				);
			}	
		}
		
		
		return array(
			'package_arr' =>$package_arr,
			'load_packages' =>"var packages = ".json_encode($packages).";", 
			'load_countries' => "dataCountries = ".json_encode($this ->lib ->__loadDataCountries__()).";",
			'loadOriginCountryValue' => "if(document.getElementById('origin_country')) document.getElementById('origin_country').value='".$this ->author ->objlogin->country."';",
			'loadOriginStateValue' => "if(document.getElementById('origin_state')) document.getElementById('origin_state').value='".$this ->author ->objlogin->state."';",
			'_weight_units_' =>$weight_units_select,
			'_units_measurement_' =>$units_measurement_select,
			'shipment_options' => $units_measurement_select,
			'_package_type_' => $units_measurement_select,
			'loadDestinationCountryValue' => "if(document.getElementById('destination_country')) document.getElementById('destination_country').value='".$destination_country."';",
			'loadDestinationStateValue' => "if(document.getElementById('destination_state')) document.getElementById('destination_state').value='".$destination_state."';",
			'ship_label' => $ship_label,
			'shipping_fee' => number_format($shipping_fee, 2),
			'destination_firstname' => $destination_firstname,
			'destination_lastname' => $destination_lastname,
			'destination_address' => $destination_address,
			'destination_city' => $destination_city,
			'destination_state' => $destination_state,
			'destination_zipcode' => $destination_zipcode,
			'destination_phone' => $destination_phone,
			'destination_mail' => $destination_mail,
			'origin_firstname' => $origin_firstname,
			'origin_lastname' => $origin_lastname,
			'origin_address' => $origin_address,
			'origin_city' => $origin_city,
			'origin_state' => $origin_state,
			'origin_zipcode' => $origin_zipcode,
			'origin_phone' => $origin_phone,
			'origin_mail' => $origin_mail,
		);
	}//end ups_loadData function
	
	public function ups_save_shipment()
	{
		$error = '';
		if(isset($_POST['packages']) && is_array($_POST['packages']) && count($_POST['packages']) > 0){	//0
			$okey = (!empty($_POST['okey']))?$this ->lib ->escape($_POST['okey']):'';
			if($okey != ''){	//1
				$this ->load->library("ups/ups");
				$this ->load->library("ups/upsShip");
				
				$this ->ups ->ups_start(trim($_POST['access_key']), trim($_POST['UPS_userid']), trim($_POST['UPS_Password']));
				$this ->ups->setTemplatePath('../xml/');
				$this ->ups->setTestingMode(0); // Change this to 0 for production
				$this ->upsShip ->upsShip_start($this ->ups);
				
				$this ->upsShip ->shipper(array('name' => trim($_POST['origin_firstname']).' '.trim($_POST['origin_lastname']),
							 'phone' => trim($_POST['origin_phone']), 
							 'shipperNumber' => trim($_POST['UPS_Shipper']), 
							 'address1' => trim($_POST['origin_address']), 
							 'address2' => '', 
							 'address3' => '', 
							 'city' => trim($_POST['origin_city']), 
							 'state' => trim($_POST['origin_state']), 
							 'postalCode' => trim($_POST['origin_zipcode']), 
							 'country' => $_POST['origin_country']));
				$this ->upsShip ->shipTo(array('companyName' => '', 
							'attentionName' => trim($_POST['destination_firstname']).' '.trim($_POST['destination_lastname']), 
							'phone' => trim($_POST['destination_phone']), 
							'address1' => trim($_POST['destination_address']), 
							'address2' => '', 
							'address3' => '', 
							'city' => trim($_POST['destination_city']), 
							'state' => trim($_POST['destination_state']), 
							'postalCode' => trim($_POST['destination_zipcode']), 
							'countryCode' => $_POST['destination_country']));
				$this ->upsShip ->shipFrom(array('companyName' => 'Bellavie Network', 
							'attentionName' => trim($_POST['origin_firstname']).' '.trim($_POST['origin_lastname']), 
							'phone' => trim($_POST['origin_phone']), 
							'address1' => trim($_POST['origin_address']), 
							'address2' => '', 
							'address3' => '', 
							'city' => trim($_POST['origin_city']), 
							'state' => trim($_POST['origin_state']), 
							'postalCode' => trim($_POST['origin_zipcode']), 
							'countryCode' => $_POST['origin_country']));
				foreach($_POST['packages'] as $package){	//2
					$this ->upsShip ->package(array('UnitOfDimensions' => $package[8],
						'UnitOfPackageWeight' => $package[4], 
						'weight' => $package[3],
						'code' => $package[1],
						'length' => $package[5],
						'width' => $package[6],
						'height' => $package[7]
					));
				}
				$this ->upsShip ->shipment(array('description' => '','serviceType' => $_POST['shipment_options']));
				$this ->upsShip ->buildRequestXML();
				$responseArray = $this ->upsShip ->responseArray();
				$ShipmentDigest = $responseArray['ShipmentConfirmResponse']['ShipmentDigest']['VALUE'];
				$this ->upsShip ->buildShipmentAcceptXML($ShipmentDigest);
				$responseArray = $this ->upsShip ->responseArray();
				$htmlImage = $responseArray['ShipmentAcceptResponse']['ShipmentResults']['PackageResults']['LabelImage']['GraphicImage']['VALUE'];
				
				$skey = $this ->lib ->GeneralRandomNumberKey(8);
				$re = $this ->db->query("select id from shipments where skey = '$skey'");
				while($re->num_rows() >0){
					$skey = $this ->lib ->GeneralRandomNumberKey(8);
					$re = $this ->db->query("select id from shipments where skey = '$skey'");
				}
				$data_shipment = array(
					'skey' => $skey,
					'okey' => $okey,
					'shipping_method' => 1,
					'origin_firstname' => $this ->lib->escape($_POST['origin_firstname']),
					'origin_lastname' => $this ->lib->escape($_POST['origin_lastname']),
					'origin_address' => $this ->lib->escape($_POST['origin_address']),
					'origin_city' => $this ->lib->escape($_POST['origin_city']),
					'origin_state' => $this ->lib->escape($_POST['origin_state']),
					'origin_zipcode' => $this ->lib->escape($_POST['origin_zipcode']),
					'origin_phone' => $this ->lib->escape($_POST['origin_phone']),
					'origin_mail' => $this ->lib->escape($_POST['origin_mail']),
					'origin_country' => $this ->lib->escape($_POST['origin_country']),
					'destination_firstname' => $this ->lib->escape($_POST['destination_firstname']),
					'destination_lastname' => $this ->lib->escape($_POST['destination_lastname']),
					'destination_address' => $this ->lib->escape($_POST['destination_address']),
					'destination_city' => $this ->lib->escape($_POST['destination_city']),
					'destination_state' => $this ->lib->escape($_POST['destination_state']),
					'destination_zipcode' => $this ->lib->escape($_POST['destination_zipcode']),
					'destination_phone' => $this ->lib->escape($_POST['destination_phone']),
					'destination_mail' => $this ->lib->escape($_POST['destination_mail']),
					'destination_country' => $this ->lib->escape($_POST['destination_country']),
					'shipment_options' => $this ->lib->escape($_POST['shipment_options']),
					'access_key' => $this ->lib->escape($_POST['access_key']),
					'UPS_Shipper' => $this ->lib->escape($_POST['UPS_Shipper']),
					'UPS_userid' => $this ->lib->escape($_POST['UPS_userid']),
					'UPS_Password' => $this ->lib->escape($_POST['UPS_Password']),
				//	'transaction_ID' => escape($_POST['transaction_ID']),
				//	'tracking_number' => escape($_POST['tracking_number']),
					'ship_date' => (trim($_POST['ship_date'])!='')?trim($_POST['ship_date']):date('m/d/Y',$this->lib->getTimeGMT()),
					'expected_delivery' => trim($_POST['expected_delivery']),
					'label' => $htmlImage
				);
				$this ->db->insert("shipments", $data_shipment);
				$id = $this ->db->insert_id();
				if(is_numeric($id) && $id > 0){
					foreach($_POST['packages'] as $package){	//2
						$package_update = array(
							'shipment_ID' => $skey,
							'pkey' => $package[0],
							'package_type' => $package[1],
							'declared_value' => $package[2],
						//	'tracking_number' => $package[3],
							'weight' => $package[3],
							'weight_units' => $package[4],
							'length' => $package[5],
							'width' => $package[6],
							'height' => $package[7],
							'units_measurement' => $package[8]
						);
						$this ->db->where('pkey',$package[0]);
						$this ->db->update("packages", $package_update);
					}	//2	
					$this ->lib ->__sendMailShipment__($id);	
				}else{
					$error = 'Can not insert to database.';	
				}
			}	//1
		}	//0
		return array('error'=>$error);
	}//end ups_save_shipment function
	
	public function usps_loadData()
	{
		$roles = $this->author ->objlogin ->role;
		$okey = !empty($_GET['okey'])? $this ->lib->escape($_GET['okey']):'';
		$packages = !empty($_GET['packages'])?$_GET['packages']:array();
		if(!is_array($packages) || count($packages) <= 0){
			$this ->system ->URLgoto("store/orders/details/".$okey);
			exit;
		}	
		
		$ong_chu = $this ->lib ->__loadBoss__();
		$weight_units_select = '';
		foreach($this ->__weight_units__ as $key => $label_weight){
			$weight_units_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$units_measurement_select = '';
		foreach($this ->__units_measurement__ as $key => $label_weight){
			$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		$units_measurement_select = '';
		foreach($this ->__USPS_package_size__ as $key => $label_weight){
			$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		
		$destination_firstname = '';
		$destination_lastname = '';
		$destination_country = '';
		$destination_address = '';
		$destination_city = '';
		$destination_state = '';
		$destination_zipcode = '';
		$destination_phone = '';
		$destination_mail = '';
		
		$origin_firstname = $this ->author ->objlogin->firstname;
		$origin_lastname = $this ->author ->objlogin->lastname;
		$origin_address = $this ->author ->objlogin->address;
		$origin_city = $this ->author ->objlogin->city;
		$origin_state = $this ->author ->objlogin->state;
		$origin_zipcode = $this ->author ->objlogin->zipcode;
		$origin_phone = $this ->author ->objlogin->phone;
		$origin_mail = $this ->author ->objlogin->mail;
		$access_key = '';
		
		$mid = $this ->database ->db_result("select items.uid from items join order_detais join orders on order_detais.itemid = items.itm_id and order_detais.orderid = orders.orderid where orders.okey = '$okey'");
		$re = $this ->db->query("select data_xml from manufacturers where uid = '".$mid."'");
		if($re->num_rows() >0){
			$row = $re ->row_array();
			if($row['data_xml'] != null && $row['data_xml'] != ''){
				$data_xml = unserialize($row['data_xml']);
				$origin_firstname = isset($data_xml['origin_firstname'])?$data_xml['origin_firstname']:$this ->author ->objlogin->firstname;
				$origin_lastname = isset($data_xml['origin_lastname'])?$data_xml['origin_lastname']:$this ->author ->objlogin->lastname;
				$origin_address = isset($data_xml['origin_address'])?$data_xml['origin_address']:$this ->author ->objlogin->address;
				$origin_city = isset($data_xml['origin_city'])?$data_xml['origin_city']:$this ->author ->objlogin->city;
				$origin_state = isset($data_xml['origin_state'])?$data_xml['origin_state']:$this ->author ->objlogin->state;
				$origin_zipcode = isset($data_xml['origin_zipcode'])?$data_xml['origin_zipcode']:$this ->author ->objlogin->zipcode;
				$origin_phone = isset($data_xml['origin_phone'])?$data_xml['origin_phone']:$this ->author ->objlogin->phone;
				$origin_mail = isset($data_xml['origin_mail'])?$data_xml['origin_mail']:$this ->author ->objlogin->mail;
				$access_key = (isset($data_xml['usps_id'])?$data_xml['usps_id']:'');		
			}	
		}
		
		$ship_label = '';
		$shipping_fee = 0;
		$base_price = 0;
		$oid = 0;
		$count = 0;
		$count_shipping_free = 0;
		
		$re = $this ->db->query("select orderid,shipping_name,shipping_address,shipping_country,shipping_city,shipping_state,shipping_zip,shipping_phone,billing_email,shipping_key,shipping_fee from orders where okey = '$okey'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$oid = $row['orderid'];
			$shipping_name = $row['shipping_name'];
			$arr_shipping_name = explode(" ", trim($shipping_name));
			$firstname = '';
			$lastname = '';
			if(isset($arr_shipping_name[0])) $firstname = $arr_shipping_name[0];
			for($i = 1; $i < count($arr_shipping_name); $i++){
				$lastname .= $arr_shipping_name[$i].' ';	
			}
			$lastname = trim($lastname);
			$destination_firstname = $firstname;
			$destination_lastname = $lastname;
			$destination_address = $row['shipping_address'];
			$destination_city = $row['shipping_city'];
			$destination_state = $row['shipping_state'];
			$destination_zipcode = $row['shipping_zip'];
			$destination_phone = $row['shipping_phone'];
			$destination_mail = $row['billing_email'];
			$destination_country = $row['shipping_country'];
			
			$base_price			= $row["shipping_fee"];
			$ship_label = $this ->database ->db_result("select label from shipping_rates where skey = '".$row['shipping_key']."'");	
		}
		
		
		if($destination_country == 'US'){
			$units_measurement_select = '';
			foreach($this ->__USPS_domestic_services__ as $key => $label_weight){
				$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
			}
		}else{
			$units_measurement_select = '';
			foreach($this ->__USPS_international_services__ as $key => $label_weight){
				$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
			}	
		}
		
		$sql_manufacturer = '';
		if($roles['rid'] == 5){
			$sql_manufacturer = "and items.uid = ".$ong_chu;
		}else{
			$shipping_fee += $base_price;	
		}
		$arr_manufacturers = array();
		$re_1 = $this ->db->query("select order_detais.last_shipping,order_detais.quality,items.itm_key,items.uid  from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
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
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			$shipping_rate = $base_price;
			foreach($arr_manufacturers[$m]['items'] as $row){//1
				$shipping_rate += round($row['last_shipping']*$row['quality'], 2);
			}
			$shipping_fee += round($shipping_rate, 2);
		}//0
		$package_arr = array();
		foreach($packages as $pkey){
			$re = $this ->db->query("select * from packages where pkey = '$pkey'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				
				
				$id = $row['id'];
				$Products = '';
				$sql_manufacturer = '';
				if($roles['rid'] == 5){
					$sql_manufacturer = "and items.uid = ".$ong_chu;
				}
				$re_1 = $this ->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id $sql_manufacturer");
				foreach($re_1 ->result_array() as $row_1){
					$Products .= $row_1['qty']." x ".$row_1['itm_model'].'<br>';
				}
				if($Products != ''){
					$Products = substr($Products, 0, strlen($Products)-4);
				}elseif($Products == '') continue;
				
				$package_arr[] = array(
					'products' => $Products,
					'pkey' => $pkey, 
				);
			}	
		}
		return array(
			'package_arr' =>$package_arr,
			'load_countries'=> "dataCountries = ".json_encode($this ->lib ->__loadDataCountries__()).";",
			'loadOriginCountryValue'=> "if(document.getElementById('origin_country')) document.getElementById('origin_country').value='".$this ->author ->objlogin->country."';",
			'loadOriginStateValue'=> "if(document.getElementById('origin_state')) document.getElementById('origin_state').value='".$this ->author ->objlogin->state."';",
			'_weight_units_' => $weight_units_select,
			'_units_measurement_' => $units_measurement_select,
			'_package_type_' => $units_measurement_select,
			'loadDestinationCountryValue' => "if(document.getElementById('destination_country')) document.getElementById('destination_country').value='".$destination_country."';",
			'loadDestinationStateValue' => "if(document.getElementById('destination_state')) document.getElementById('destination_state').value='".$destination_state."';",
			'ship_label' => $ship_label,
			'shipping_fee' => number_format($shipping_fee, 2),
			'destination_firstname' => $destination_firstname,
			'destination_lastname' => $destination_lastname,
			'destination_address' => $destination_address,
			'destination_city' => $destination_city,
			'destination_state' => $destination_state,
			'destination_zipcode' => $destination_zipcode,
			'destination_phone' => $destination_phone,
			'destination_mail' => $destination_mail,
			'origin_firstname' => $origin_firstname,
			'origin_lastname' => $origin_lastname,
			'origin_address' => $origin_address,
			'origin_city' => $origin_city,
			'origin_state' => $origin_state,
			'origin_zipcode' => $origin_zipcode,
			'origin_phone' => $origin_phone,
			'origin_mail' => $origin_mail,
			'access_key' => $access_key,
			'load_packages' => "var packages = ".json_encode($packages).";",
			'shipment_options' => $units_measurement_select,
		);
	}//end usps_loadData function
	
	public function usps_save_shipment()
	{
		$error = '';
		$skey='';
		if(isset($_POST['packages']) && is_array($_POST['packages']) && count($_POST['packages']) > 0){	//0
			$okey = (!empty($_POST['okey']))?$this ->lib->escape($_POST['okey']):'';
			$weight = 0;
			$width_ = 0;
			$height_ = 0;
			$length_ = 0;
			$origin_phone = str_replace(" ", "", trim($_POST['origin_phone']));
			$origin_phone = str_replace("(", "", $origin_phone);
			$origin_phone = str_replace(")", "", $origin_phone);
			$origin_phone = str_replace("-", "", $origin_phone);
			if(strlen($origin_phone) > 10) $origin_phone = substr($origin_phone, 0, 10);
			$soluong_package = 1;
			foreach($_POST['packages'] as $package){	//2
				$donvi = $package[4];
				$weight += $package[3];
				$width_ += $package[6];
				$height_ += $package[7];
				$length_ += $package[5];
				$soluong_package ++;
			}
			if($okey != ''){	//1
				$img_data = '';
				$tracking_number = '';
				$transaction_ID = '';
				$check = false;
				$ship_date = (isset($_POST['ship_date']) && $_POST['ship_date'] != '')?trim($_POST['ship_date']):date('m/d/Y',$this->lib->getTimeGMT());
				$int_ship_date = strtotime($ship_date);
				$int_today = strtotime(date("m/d/Y"));
				if($int_ship_date > $int_today + 3*24*60*60 || $int_ship_date < $int_today){
					$ship_date = date("m/d/Y");	
				}
				$access_key = (isset($_POST['access_key'])&&$_POST['access_key']!='')?$_POST['access_key']:'';
				$service_type = '';
				if($_POST['destination_country'] == 'US'){
					$service_type = $_POST['shipment_options'];	
				}else{
					$service_type = 'International';	
				}
				$data_pickup = "https://secure.shippingapis.com/shippingapi.dll?API=CarrierPickupSchedule&XML=
				<CarrierPickupScheduleRequest USERID=\"$access_key\">
				<FirstName>".$_POST['origin_firstname']."</FirstName>
				<LastName>".$_POST['origin_lastname']."</LastName>
				<FirmName>Bellavie Network.</FirmName>
				<SuiteOrApt></SuiteOrApt>
				<Address2>".trim($_POST['origin_address'])."</Address2>
				<Urbanization></Urbanization>
				<City>".trim($_POST['origin_city'])."</City>
				<State>".$_POST['origin_state']."</State>
				<ZIP5>".$_POST['origin_zipcode']."</ZIP5>
				<ZIP4></ZIP4>
				<Phone>".$origin_phone."</Phone>
				<Extension></Extension>
				<Package>
					 <ServiceType>$service_type</ServiceType>
					 <Count>$soluong_package</Count>
				</Package>
				<EstimatedWeight>$weight</EstimatedWeight>
				<PackageLocation>Front Door</PackageLocation>
				<SpecialInstructions></SpecialInstructions>
				</CarrierPickupScheduleRequest>";
				if($_POST['destination_country'] == 'US'){
					if($service_type == 'Express Mail'){
						$data = "https://secure.shippingapis.com/ShippingAPI.dll?API=ExpressMailLabelCertify&XML=<ExpressMailLabelCertifyRequest USERID=\"$access_key\">
						<Option /><Revision>1</Revision><EMCAAccount /><EMCAPassword /><ImageParameters />
						  <FromFirstName>".$_POST['origin_firstname']."</FromFirstName>
						  <FromLastName>".$_POST['origin_lastname']."</FromLastName>
						  <FromFirm>Bellavie Network</FromFirm>
						  <FromAddress1></FromAddress1>
						  <FromAddress2>".trim($_POST['origin_address'])."</FromAddress2>
						  <FromCity>".trim($_POST['origin_city'])."</FromCity>
						  <FromState>".$_POST['origin_state']."</FromState>
						  <FromZip5>".$_POST['origin_zipcode']."</FromZip5><FromZip4 />
						  <FromPhone>".$origin_phone."</FromPhone>
						  <ToFirstName>".$_POST['destination_firstname']."</ToFirstName>
						  <ToLastName>".$_POST['destination_lastname']."</ToLastName>
						  <ToFirm>".$_POST['destination_firstname'].' '.$_POST['destination_lastname']."</ToFirm>
						  <ToAddress1></ToAddress1>
						  <ToAddress2>".trim($_POST['destination_address'])."</ToAddress2>
						  <ToCity>".trim($_POST['destination_city'])."</ToCity><ToState>".$_POST['destination_state']."</ToState><ToZip5>".trim($_POST['destination_zipcode'])."</ToZip5><ToZip4 /><ToPhone /><WeightInOunces>$weight</WeightInOunces>
						  <ShipDate>".$ship_date."</ShipDate>
						  <FlatRate>false</FlatRate>
						  <SundayHolidayDelivery>false</SundayHolidayDelivery>
						  <StandardizeAddress>true</StandardizeAddress>
						  <WaiverOfSignature>true</WaiverOfSignature>
						  <NoHoliday>false</NoHoliday>
						  <NoWeekend>false</NoWeekend>
						  <SeparateReceiptPage>false</SeparateReceiptPage><POZipCode />
						  <FacilityType>DDU</FacilityType>
						  <ImageType>PDF</ImageType>
						  <LabelDate>".$ship_date."</LabelDate>
						  <CustomerRefNo>".$okey."</CustomerRefNo>
						  <SenderName>".$_POST['origin_firstname'].' '.$_POST['origin_lastname']."</SenderName><SenderEMail />
						  <RecipientName>".$_POST['destination_firstname'].' '.$_POST['destination_lastname']."</RecipientName><RecipientEMail />
						  <HoldForManifest>N</HoldForManifest>
						  <CommercialPrice>false</CommercialPrice>
						  <InsuredAmount>0.01</InsuredAmount>
						  </ExpressMailLabelCertifyRequest>";
						$data = $this ->lib ->__grabURL__($data);
						$content = file_get_contents($data);
						if($content != ''){
							$arr_data = $this ->lib ->partitionString("<EMLabel>", "</EMLabel>", $content);
							$img_data = isset($arr_data[1])?$arr_data[1]:'';
							if($img_data != ''){
								$arr_data = $this ->lib ->partitionString("<EMConfirmationNumber>", "</EMConfirmationNumber>", $content);
								$tracking_number = isset($arr_data[1])?$arr_data[1]:'';
								$check = true;
							}else{
								$arr_data = $this ->lib ->partitionString("<Description>", "</Description>", $content);
								$error = isset($arr_data[1])?$arr_data[1]:'';	
							}	
						}else{
							$error = 'Can not send request USPS.';	
						}
					}else{
						$data = "https://secure.shippingapis.com/shippingapi.dll?API=DeliveryConfirmationV3&XML=<DeliveryConfirmationV3.0Request USERID=\"$access_key\"><Option>1</Option><ImageParameters/><FromName>".$_POST['origin_firstname'].' '.$_POST['origin_lastname']."</FromName><FromFirm>Bellavie Network</FromFirm><FromAddress1/><FromAddress2>".trim($_POST['origin_address'])."</FromAddress2><FromCity>".trim($_POST['origin_city'])."</FromCity><FromState>".$_POST['origin_state']."</FromState><FromZip5>".$_POST['origin_zipcode']."</FromZip5><FromZip4></FromZip4><ToName>".$_POST['destination_firstname'].' '.$_POST['destination_lastname']."</ToName><ToFirm>".$_POST['destination_firstname'].' '.$_POST['destination_lastname']."</ToFirm><ToAddress1></ToAddress1><ToAddress2>".trim($_POST['destination_address'])."</ToAddress2><ToCity>".trim($_POST['destination_city'])."</ToCity><ToState>".$_POST['destination_state']."</ToState><ToZip5>".trim($_POST['destination_zipcode'])."</ToZip5><ToZip4></ToZip4><WeightInOunces>$weight</WeightInOunces><ServiceType>".$service_type."</ServiceType><POZipCode>".$_POST['origin_zipcode']."</POZipCode><ImageType>PDF</ImageType><LabelDate>".$ship_date."</LabelDate><CustomerRefNo>".$okey."</CustomerRefNo><AddressServiceRequested>TRUE</AddressServiceRequested></DeliveryConfirmationV3.0Request>";
						$data = $this ->lib -> __grabURL__($data);
						$content = file_get_contents($data);
						if($content != ''){
							$arr_data = $this ->lib ->partitionString("<DeliveryConfirmationLabel>", "</DeliveryConfirmationLabel>", $content);
							$img_data = isset($arr_data[1])?$arr_data[1]:'';

							if($img_data != ''){
								$arr_data = $this ->lib ->partitionString("<DeliveryConfirmationNumber>", "</DeliveryConfirmationNumber>", $content);
								$tracking_number = isset($arr_data[1])?$arr_data[1]:'';
								if($tracking_number != '') $tracking_number = substr($tracking_number, 8);	
								$check = true;
							}else{
								$arr_data = $this ->lib ->partitionString("<Description>", "</Description>", $content);
								$error = isset($arr_data[1])?$arr_data[1]:'';	
							}	
						}else{
							$error = 'Can not send request USPS.';	
						}
					}
				}else{
					$tblcontries = array();
					$re = $this ->db->query("select * from tblcontries");
					foreach($re ->result_array() as $row){
						$tblcontries[$row['code']] = $row['name'];	
					}
					$destination_phone = str_replace(" ", "", trim($_POST['destination_phone']));
					$destination_phone = str_replace("(", "", $destination_phone);
					$destination_phone = str_replace(")", "", $destination_phone);
					$destination_phone = str_replace("-", "", $destination_phone);
					if(strlen($destination_phone) > 10) $destination_phone = substr($destination_phone, 0, 10);
					$data = "https://secure.shippingapis.com/shippingapi.dll?API=FirstClassMailIntlCertify&XML=<FirstClassMailIntlCertifyRequest USERID=\"$access_key\">
						<Option/>
						<Revision>2</Revision>
						<ImageParameters/>
						<FromFirstName>".$_POST['origin_firstname']."</FromFirstName>
						<FromMiddleInitial></FromMiddleInitial>
						<FromLastName>".$_POST['origin_lastname']."</FromLastName>
						<FromFirm>Bellavie Network</FromFirm>
						<FromAddress1></FromAddress1>
						<FromAddress2>".trim($_POST['origin_address'])."</FromAddress2>
						<FromCity>".trim($_POST['origin_city'])."</FromCity>
						<FromState>".trim($_POST['origin_state'])."</FromState>
						<FromZip5>".trim($_POST['origin_zipcode'])."</FromZip5>
						<FromPhone>".$origin_phone."</FromPhone>
					
						<ToName>".trim($_POST['destination_firstname']).' '.trim($_POST['destination_lastname'])."</ToName>
						<ToFirm></ToFirm>
						<ToAddress1></ToAddress1>
						<ToAddress2>".trim($_POST['destination_address'])."</ToAddress2>
						<ToAddress3>".trim($_POST['destination_address'])."</ToAddress3>
						<ToCity>".trim($_POST['destination_city'])."</ToCity>
						<ToCountry>".(isset($tblcontries[$_POST['destination_country']])?$tblcontries[$_POST['destination_country']]:$_POST['destination_country'])."</ToCountry>
						<ToPostalCode>".trim($_POST['destination_zipcode'])."</ToPostalCode>
						<ToPOBoxFlag>N</ToPOBoxFlag>
						<ToPhone>".$destination_phone."</ToPhone>
						<ToFax></ToFax>
						<ToEmail>".trim($_POST['destination_mail'])."</ToEmail>
						<FirstClassMailType>PARCEL</FirstClassMailType>
						<ShippingContents>";
							foreach($_POST['packages'] as $package){	//2
								$re = $this ->db->query("select items.itm_name,items.itm_model,items.weight,items.current_cost,packages_items.qty from packages join packages_items join items on packages.id = packages_items.package_id and items.itm_id = packages_items.product_id where packages.pkey = '".$package[0]."'");
								foreach($re ->result_array() as $row){
									$weight += $row['weight'];
									$data .= "<ItemDetail>
										<Description>".$row['itm_name']."</Description>
										<Quantity>".$row['qty']."</Quantity>
										<Value>".$row['current_cost']."</Value>
										<NetPounds>".($row['weight']>0?$row['weight']:1)."</NetPounds>
										<NetOunces>".($row['weight']>0?$row['weight']:1)."</NetOunces>
										<HSTariffNumber>".$row['itm_model']."</HSTariffNumber>
										<CountryOfOrigin>".$_POST['origin_country']."</CountryOfOrigin>
									</ItemDetail>";			
								}
							}
						$data .= "</ShippingContents>
						<GrossPounds>".($weight>0?$weight:1)."</GrossPounds>
						<GrossOunces>".($weight>0?$weight:1)."</GrossOunces>
						<Machinable>false</Machinable>
						<ContentType>GIFT</ContentType>
						<Agreement>Y</Agreement>
						<Comments>FirstClassMailIntl Comments</Comments>
						<ImageType>PDF</ImageType>
						<ImageLayout>ONEPERFILE</ImageLayout>
						<HoldForManifest>N</HoldForManifest>
						<EELPFC>30.37a</EELPFC>
						<Container>RECTANGULAR</Container>
						<Size>REGULAR</Size>
						<Length>".($length_>0?$length_:1)."</Length>
						<Width>".($width_>0?$width_:1)."</Width>
						<Height>".($height_>0?$height_:1)."</Height>
						<Girth>1</Girth>
					</FirstClassMailIntlCertifyRequest>";
					$data = $this ->lib ->__grabURL__($data);
					$content = file_get_contents($data);
					if($content != ''){
						$arr_data = $this ->lib ->partitionString("<LabelImage>", "</LabelImage>", $content);
						$img_data = isset($arr_data[1])?$arr_data[1]:'';
						if($img_data != ''){
							$arr_data = $this ->lib ->partitionString("<BarcodeNumber>", "</BarcodeNumber>", $content);
							$tracking_number = isset($arr_data[1])?$arr_data[1]:'';	
							$check = true;
						}else{
							$arr_data = $this ->lib ->partitionString("<Description>", "</Description>", $content);
							$error = isset($arr_data[1])?$arr_data[1]:'';	
						}
					}else{
						$error = 'Can not send request USPS.';		
					}
				}
				if($check == true){
					$data_pickup = $this ->lib ->__grabURL__($data_pickup);
					$content_pickup = file_get_contents($data_pickup);
					if($content_pickup != ''){
						$confirm_data = $this ->lib ->partitionString("<ConfirmationNumber>", "</ConfirmationNumber>", $content_pickup); 
						if($confirm_data != ''){
							$transaction_ID = isset($confirm_data[1])?$confirm_data[1]:'';	
						}else{
							$arr_data = $this ->lib ->partitionString("<Description>", "</Description>", $content_pickup);
							$error = isset($arr_data[1])?$arr_data[1]:'';	
						}
					}else{
						$error = 'Can not send pickup request USPS';	
					}
				}
				if($error == ''){
					$skey = $this ->lib ->GeneralRandomNumberKey(8);
					$re = $this ->db->query("select id from shipments where skey = '$skey'");
					while($re->num_rows() >0){
						$skey = $this ->lib ->GeneralRandomNumberKey(8);
						$re = $this ->db->query("select id from shipments where skey = '$skey'");
					}
					$data_shipment = array(
						'skey' => $skey,
						'okey' => $okey,
						'shipping_method' => 2,
						'origin_firstname' => $this ->lib ->escape($_POST['origin_firstname']),
						'origin_lastname' => $this ->lib ->escape($_POST['origin_lastname']),
						'origin_address' => $this ->lib ->escape($_POST['origin_address']),
						'origin_city' => $this ->lib ->escape($_POST['origin_city']),
						'origin_state' => $this ->lib ->escape($_POST['origin_state']),
						'origin_zipcode' => $this ->lib ->escape($_POST['origin_zipcode']),
						'origin_phone' => $this ->lib ->escape($_POST['origin_phone']),
						'origin_mail' => $this ->lib ->escape($_POST['origin_mail']),
						'origin_country' => $this ->lib ->escape($_POST['origin_country']),
						'destination_firstname' => $this ->lib ->escape($_POST['destination_firstname']),
						'destination_lastname' => $this ->lib ->escape($_POST['destination_lastname']),
						'destination_address' => $this ->lib ->escape($_POST['destination_address']),
						'destination_city' => $this ->lib ->escape($_POST['destination_city']),
						'destination_state' => $this ->lib ->escape($_POST['destination_state']),
						'destination_zipcode' => $this ->lib ->escape($_POST['destination_zipcode']),
						'destination_phone' => $this ->lib ->escape($_POST['destination_phone']),
						'destination_mail' => $this ->lib ->escape($_POST['destination_mail']),
						'destination_country' => $this ->lib ->escape($_POST['destination_country']),
						'shipment_options' => $this ->lib ->escape($_POST['shipment_options']),
						'access_key' => $access_key,
						'transaction_ID' => $transaction_ID,
						'tracking_number' => $tracking_number,
						'ship_date' => $ship_date,
						'expected_delivery' => trim($_POST['expected_delivery']),
						'label' => $img_data,
						'date_active' => time()
					);
					$this ->db ->insert("shipments", $data_shipment);
					$id = $this ->db ->insert_id();
					if(is_numeric($id) && $id > 0){
						foreach($_POST['packages'] as $package){	//2
							$package_update = array(
								'shipment_ID' => $skey,
								'pkey' => $package[0],
								'package_type' => $package[1],
								'declared_value' => $package[2],
								'tracking_number' => $tracking_number,
								'weight' => $package[3],
								'weight_units' => $package[4],
								'length' => $package[5],
								'width' => $package[6],
								'height' => $package[7],
								'units_measurement' => $package[8]
							);
							$this ->db ->where('pkey',$package[0]);
							$this ->db ->update("packages", $package_update);
						}	//2	
						$this ->lib ->__sendMailShipment__($id);	
					}else{
						$error = 'Can not insert to database.';	
					}
				}
			}	//1
		}	//0
		return array('error'=>$error, 'skey'=>$skey);
	}//end usps_save_shipment function
}