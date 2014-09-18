<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shipping_model extends CI_Model 
{	
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
	private $__fedex_packaging__ = array(
		'01'		=> 'Your packaging',
		'06' 		=> 'FedEx envelope',
		'02'		=> 'FedEx pak',
		'03'		=> 'FedEx box',
		'04'		=> 'FedEx tube',
		'15'		=> 'FedEx 10kg box', 
		'25'		=> 'FedEx 25kg box'  
	);
	private $__fedex_services__ = array(
		'ALL' 		=> 'All',
		'FDXE'		=> 'FedEx Express',
		'FDXG'		=> 'FedEx Ground' 
	);
	public function loadShipping()
	{
		$arrUsers = array();
		$modify = 'no';
		$del = 'no';
		if($this ->author ->isAccessPerm('Shipping','edit'))
		{
			$modify = 'yes';	
		}
		if($this ->author ->isAccessPerm('Shipping','delete'))
		{
			$del = 'yes';	
		}
		$re = $this ->db ->query("select * from shipping_rates WHERE status <> -1 order by weight DESC");
		foreach($re->result_array() as $row)
		{
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end loadShipping function
	
	public function saveDatas()
	{
		if(isset($_POST['datas']) && is_array($_POST['datas']) && count($_POST['datas']) > 0)
		{
			$datas = $_POST['datas'];
			$Enabled = (isset($_POST['Enabled'])&&is_array($_POST['Enabled']))?$_POST['Enabled']:array();
			$re = $this ->db ->query("select * from shipping_rates WHERE status <> -1");
			foreach($re->result_array() as $row)
			{
				for($i = 0; $i < count($datas); $i++)
				{
					if($datas[$i]['skey'] == $row['skey']){
						$data_update = array('weight' => -$i);
						if(in_array($row['skey'], $Enabled)) $data_update['status'] = 1;
						else $data_update['status'] = 0;
						$this->db ->where('skey',$row['skey']);
						$this->db ->update("shipping_rates", $data_update);
						break;	
					}	
				}
			}	
		}
		return $this ->loadShipping();
	}//end saveDatas function
	
	public function mups_loadData($ukey='')
	{
		$key = $this->lib->escape($ukey);
		$checked = '';
		$label = '';
		$description = '';
		$shipping_info = '';
		$access_key = '';
		$UPS_Shipper = '';
		$UPS_userid = '';
		$UPS_Password = '';
		$handling_fee = '';
		$package_options = '';
		$shipment_options = '';
		$re = $this->db->query("select * from shipping_rates where skey = '$key'");
		if($re->num_rows()>0)
		{
			$row = $re ->row_array();
			$checked = ($row['status']==1)?'checked="checked"':'';
			$label = $row['label'];
			$description = $row['description'];
			$shipping_info = $row['shipping_info'];
			$handling_fee = $row['handling_fee'];
			
			$re2 = $this->db->query("select * from shipping_ups where skey = '$key'");
			if($re2->num_rows()>0)
			{
				$row2 = $re2 ->row_array();
				$access_key = $row2['access_key'];
				$UPS_Shipper = $row2['UPS_Shipper'];
				$UPS_userid = $row2['UPS_userid'];	
				$UPS_Password = $row2['UPS_Password'];
				
				foreach($this ->__package_type__ as $key_ => $label_weight){
					$select_ = '';
					if($row2['Package_type'] == $key_) $select_ = 'selected="selected"';
					$package_options .= '<option value="'.$key_.'" '.$select_.'>'.$label_weight.'</option>';	
				}
								
				foreach($this -> __UPS_Service__ as $key_ => $label_weight){
					$select_ = '';
					if($row2['UPS_service'] == $key_) $select_ = 'selected="selected"';
					$shipment_options .= '<option value="'.$key_.'" '.$select_.'>'.$label_weight.'</option>';	
				}							
			}
		}
		$data = array(
			'key' =>$ukey,
			'checked' =>$checked,
			'label' =>$label,
			'description' =>$description,
			'shipping_info' =>$shipping_info,
			'access_key' =>$access_key,
			'UPS_Shipper' =>$UPS_Shipper,
			'UPS_userid' =>$UPS_userid,
			'UPS_Password' =>$UPS_Password,
			'handling_fee' =>$handling_fee,
			'_package_type_' =>$package_options,
			'shipment_options' =>$shipment_options,
		);
		return $data;	
	}//end mups_loadData function
	
	public function mups_saveShippingmethod()
	{
		$key = isset($_POST['key'])?$this ->lib ->escape($_POST['key']):'';
		$shipping_rates = array(
			'label' =>$this ->lib -> escape($_POST['label']),
			'description' => $this ->lib ->escape($_POST['description']),
			'shipping_info' => $this ->lib ->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 1,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db ->where('skey',$key);
		$this ->db ->update('shipping_rates', $shipping_rates);
		$shipping_ups = array(
			'access_key' => $this ->lib ->escape($_POST['access_key']),
			'UPS_Shipper' => $this ->lib ->escape($_POST['UPS_Shipper']),
			'UPS_userid' => $this ->lib ->escape($_POST['UPS_userid']),
			'UPS_Password' => $this ->lib ->escape($_POST['UPS_Password']),
			'Package_type' => $_POST['Package_type'],
			'UPS_service' => $_POST['UPS_service']			
		);
		$this ->db ->where('skey',$key);
		$this ->db ->update('shipping_ups', $shipping_ups);	
		return 'ok';
	}//end mups_saveShippingmethod function
	
	public function mmanually_loadData($ukey='')
	{
		$key = $this->lib->escape($ukey);
		$dataCountries = $this ->lib->__loadDataCountries__();
		
		$checked = '';
		$label = '';
		$description = '';
		$shipping_info = '';
		$handling_fee = '';
		
		$re = $this ->db ->query("select * from shipping_rates where skey = '$key'");
		if($re->num_rows()>0)
		{
			$row = $re ->row_array();
			$checked = ($row['status']==1)?'checked="checked"':'';
			$label = $row['label'];
			$description = $row['description'];
			$shipping_info = $row['shipping_info'];
			$handling_fee = $row['handling_fee'];
			
			$re2 = $this ->db ->query("select * from shipping_manually where skey = '$key'");
			foreach($re2->result_array() as $row2)
			{
				for($i = 0; $i < count($dataCountries); $i++)
				{
					if($dataCountries[$i]['code'] == $row2['country'])
					{
						$dataCountries[$i]['rate'] = $row2['country_rate'];
						$dataCountries[$i]['rate_type'] = $row2['rate_type'];
						if(isset($dataCountries[$i]['states']) && is_array($dataCountries[$i]['states']) && count($dataCountries[$i]['states']) > 0)
						{
							$re3 = $this ->db ->query("select * from shipping_manually_states where ship_country_id = ".$row2['id']);
							foreach($re3->result_array() as $row3)
							{
								for($j = 0; $j < count($dataCountries[$i]['states']); $j++)
								{
									if($dataCountries[$i]['states'][$j]['code'] == $row3['state'])
									{
										$dataCountries[$i]['states'][$j]['rate'] = $row3['state_rate'];
										break;	
									}	
								}	
							}
						}
						break;	
					}	
				}							
			}
		}
		$data = array(
			'key' =>$ukey,
			'checked' =>$checked,
			'label' =>$label,
			'description' =>$description,
			'shipping_info' =>$shipping_info,
			'handling_fee' =>$handling_fee,
			'load_countries' =>"dataCountries = ".json_encode($dataCountries).";"
		);
		return $data;								
	}//end mmanually_loadData function
	
	function manually_saveShippingmethod()
	{
		$key = $this ->lib ->GeneralRandomKey(20);
		$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		foreach($re->result_array() as $row)
		{
			$key = $this ->lib ->GeneralRandomKey(20);
			$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		}
		$shipping_rates = array(
			'skey' => $key,
			'ukey' => $this->author ->objlogin->uid,
			'label' => $this ->lib ->escape($_POST['label']),
			'description' => $this ->lib ->escape($_POST['description']),
			'shipping_info' => $this ->lib ->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 0,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db->insert('shipping_rates', $shipping_rates);
		$id = $this ->db->insert_id();
		if(is_numeric($id) && $id > 0){
			if(isset($_POST['dataCountries']) && is_array($_POST['dataCountries']) && count($_POST['dataCountries']) > 0){
				foreach($_POST['dataCountries'] as $country_ship){
					$rate_type = (isset($country_ship['rate_type']) && is_numeric($country_ship['rate_type']))?$country_ship['rate_type']:1;
					if($rate_type == 1){
						$country_rate = (isset($country_ship['rate']) && is_numeric($country_ship['rate']) && $country_ship['rate'] > 0)?$country_ship['rate']:0;
						if($country_rate > 0){
							$shipping_manually = array(
								'skey' => $key,
								'country' => $country_ship['code'],
								'country_rate' => $country_rate,
								'rate_type' => $rate_type
							);
							$this ->db->insert('shipping_manually', $shipping_manually);
							$ship_country_id = $this ->db->insert_id();	
						}	
					}elseif($rate_type == 0){
						if(isset($country_ship['states']) && is_array($country_ship['states']) && count($country_ship['states']) > 0){
							$shipping_manually = array(
								'skey' => $key,
								'country' => $country_ship['code'],
								'country_rate' => $country_rate,
								'rate_type' => $rate_type
							);
							$this ->db->insert('shipping_manually', $shipping_manually);
							$ship_country_id =$this ->db->insert_id();	
							if(is_numeric($ship_country_id) && $ship_country_id > 0){	//0
								foreach($country_ship['states'] as $states){
									$shipping_manually_states = array(
										'ship_country_id' => $ship_country_id,
										'state' => $states['code'],
										'state_rate' => (isset($states['rate']) && is_numeric($states['rate']) && $states['rate'] > 0)?$states['rate']:0,
									);
									$this ->db->insert('shipping_manually_states', $shipping_manually_states);		
								}	
							}	//0
						}
					}
				}	
			}
		}else{
			return '';	
		}
		return 'ok';
	}//end manually_saveShippingmethod function
	
	public function mmanually_saveShippingmethod()
	{
		$key = isset($_POST['key'])?$_POST['key']:'';
		$shipping_rates = array(
			'label' => $this ->lib->escape($_POST['label']),
			'description' => $this ->lib->escape($_POST['description']),
			'shipping_info' => $this ->lib->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db ->where('skey',$key);
		$this ->db ->update('shipping_rates', $shipping_rates);
		$re = $this ->db ->query("select id from shipping_manually where skey = '$key'");
		foreach($re->result_array() as $row)
		{
			$this ->db ->query("delete from shipping_manually where skey = '$key'");
			$this ->db ->query("delete from shipping_manually_states where ship_country_id = ".$row['id']);		
		}
		if(isset($_POST['dataCountries']) && is_array($_POST['dataCountries']) && count($_POST['dataCountries']) > 0){
			foreach($_POST['dataCountries'] as $country_ship){
				$rate_type = (isset($country_ship['rate_type']) && is_numeric($country_ship['rate_type'])) ? (int)$country_ship['rate_type'] : 1;
				$country_rate = (isset($country_ship['rate']) && is_numeric($country_ship['rate']) && $country_ship['rate'] >= 0) ? (float)$country_ship['rate'] : -1;
				if($rate_type == 1){
					if($country_rate >= 0){
						$shipping_manually = array(
							'skey' => $key,
							'country' => $country_ship['code'],
							'country_rate' => $country_rate,
							'rate_type' => $rate_type
						);
						$this ->db ->insert('shipping_manually', $shipping_manually);
						$ship_country_id = 	$this->db ->insert_id();
					}	
				}elseif($rate_type == 0){
					if(isset($country_ship['states']) && is_array($country_ship['states']) && count($country_ship['states']) > 0){
						$shipping_manually = array(
							'skey' => $key,
							'country' => $country_ship['code'],
							'country_rate' => $country_rate,
							'rate_type' => $rate_type
						);
						$this ->db ->insert('shipping_manually', $shipping_manually);
						$ship_country_id = $this->db ->insert_id();
						if(is_numeric($ship_country_id) && $ship_country_id > 0){	//0
							foreach($country_ship['states'] as $states){
								$shipping_manually_states = array(
									'ship_country_id' => $ship_country_id,
									'state' => $states['code'],
									'state_rate' => (isset($states['rate']) && is_numeric($states['rate']) && $states['rate'] >= 0)?$states['rate']:-1,
								);
								$this ->db ->insert('shipping_manually_states', $shipping_manually_states);		
							}	
						}	//0
					}
				}
			}	
		}
		return 'ok';
	}//end mmanually_saveShippingmethod function
	
	public function musps_loadData($ukey='')
	{
		$key = $this->lib->escape($ukey);
		$checked = '';
		$label = '';
		$description = '';
		$shipping_info = '';
		$USPS_userid = '';
		$handling_fee = '';
		$machinable = '';
		$package_size = '';
		$USPS_domestic_services = '';
		$USPS_international_services = '';
		
		$re = $this ->db ->query("select * from shipping_rates where skey = '$key'");
		if($re->num_rows()>0)
		{
			$row = $re->row_array();
			$checked = ($row['status']==1)?'checked="checked"':'';
			$label = $row['label'];
			$description = $row['description'];
			$shipping_info = $row['shipping_info'];
			$handling_fee = $row['handling_fee'];
			$re2 = $this ->db ->query("select * from shipping_usps where skey = '$key'");
			if($re2->num_rows()>0)
			{
				$row2 = $re2->row_array();
				$USPS_userid = $row2['USPS_userid'];	
				$machinable = $row2['Machinable'];
				foreach($this ->__USPS_package_size__ as $key_ => $label_size)
				{
					$select_ = '';
					if($row2['Package_type'] == $key_) $select_ = 'selected="selected"';
					$package_size .= '<option value="'.$key_.'" '.$select_.'>'.$label_size.'</option>';	
				}
				foreach($this ->__USPS_domestic_services__ as $key_ => $label_doser)
				{
					$select_ = '';
					if($row2['USPS_service'] == $key_) $select_ = 'selected="selected"';
					$USPS_domestic_services .= '<option value="'.$key_.'" '.$select_.'>'.$label_doser.'</option>';	
				}
				foreach($this ->__USPS_international_services__ as $key_ => $label_inser)
				{
					$select_ = '';
					if($row2['USPS_inter_service'] == $key_) $select_ = 'selected="selected"';
					$USPS_international_services .= '<option value="'.$key_.'" '.$select_.'>'.$label_inser.'</option>';	
				}						
			}
		}
		$str_machinable = '';
		if($machinable == 'TRUE')
		{
			$str_machinable .= '<option value="FALSE">No</option>';
			$str_machinable .= '<option value="TRUE" selected="selected">Yes</option>';	
		}
		else
		{
			$str_machinable .= '<option value="FALSE" selected="selected">No</option>';	
			$str_machinable .= '<option value="TRUE" >Yes</option>';	
		}
		$data=array(
			'_package_size_' =>$package_size,
			'_domestic_services_' =>$USPS_domestic_services,
			'__USPS_international_services__' =>$USPS_international_services,
			'key' =>$ukey,
			'checked' =>$checked,
			'label' =>$label,
			'description' =>$description,
			'shipping_info' =>$shipping_info,
			'USPS_userid' =>$USPS_userid,
			'handling_fee' =>$handling_fee,
			'machinable' =>$str_machinable,
		);
		return $data;								
	}//end musps_loadData function
	
	public function musps_saveShippingmethod()
	{
		$key = isset($_POST['key'])?$_POST['key']:'';
		$shipping_rates = array(
			'label' =>  $this->lib->escape($_POST['label']),
			'description' =>  $this->lib->escape($_POST['description']),
			'shipping_info' =>  $this->lib->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 2,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db ->where('skey',$key);
		$this ->db ->update('shipping_rates', $shipping_rates);
		$shipping_usps = array(
			'skey' => $key,
			'USPS_userid' => $this->lib->escape($_POST['USPS_userid']),
			'Package_type' => $_POST['Package_type'],
			'Machinable'	=> $_POST['machinable'],
			'USPS_service' => $_POST['USPS_service'],
			'USPS_inter_service' => $_POST['USPS_inter_service']	
		);
		$this ->db ->where('skey',$key);
		$this ->db ->update('shipping_usps', $shipping_usps);	
		return 'ok';
	}//end musps_saveShippingmethod function
	
	public function mfedex_loadData($ukey='')
	{
		$key = $this->lib->escape($ukey);
		$checked = '';
		$label = '';
		$handling_fee = '';
		$description = '';
		$shipping_info = '';
		$account = '';
		$meter = '';
		$fedex_key = '';
		$fedex_password = '';
		$packaging = '';
		$services = '';
		
		$re = $this ->db->query("select * from shipping_rates where skey = '$key'");
		if($re->num_rows()>0)
		{
			$row=  $re->row_array();
			$checked = ($row['status']==1)?'checked="checked"':'';
			$label = $row['label'];
			$description = $row['description'];
			$shipping_info = $row['shipping_info'];
			$handling_fee = $row['handling_fee'];	
			$re_2 = $this ->db->query("select * from shipping_fedex where skey = '$key'");
			if($re_2->num_rows()>0)
			{
				$row_2=  $re_2->row_array();
				$account = $row_2['account'];
				$meter = $row_2['meter'];
				$fedex_key = $row_2['fedex_key'];
				$fedex_password = $row_2['password'];
				foreach($this->__fedex_packaging__ as $key_ => $label_pac){
					$select = '';
					if($key_ == $row_2['packaging']) $select = 'selected="selected"';	
					$packaging .= '<option '.$select.'value="'.$key_.'">'.$label_pac.'</option>';
				}
				foreach($this ->__fedex_services__ as $key_ => $label_ser){
					$select = '';
					if($key_ == $row_2['services']) $select = 'selected="selected"';
					$services .= '<option value="'.$key_.'" '.$select.'>'.$label_ser.'</option>';	
				}
			}
		}
		$data = array(
			'key' =>$ukey,
			'check' =>$checked,
			'label' =>$label,
			'description' =>$description,
			'handling_fee' =>$handling_fee,
			'shipping_info' =>$shipping_info,
			'account' =>$account,
			'meter' =>$meter,
			'fedex_key' =>$fedex_key,
			'fedex_password' =>$fedex_password,
			'packaging' =>$packaging,
			'fedex_service' =>$services
		);
		return $data;
	}//end mfedex_loadData function
	
	public function mfedex_saveShippingmethod()
	{
		$key = isset($_POST['key'])?$_POST['key']:'';
		$shipping_rates = array(
			'label' => $this->lib->escape($_POST['label']),
			'description' => $this->lib->escape($_POST['description']),
			'shipping_info' => $this->lib->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 3,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db ->where('skey',$key);
		$this ->db ->update('shipping_rates', $shipping_rates);
		$shipping_fedex = array(
			'skey' => $key,
			'account' => $this->lib->escape($_POST['fedex_acc']),
			'meter' => $this->lib->escape($_POST['meter_num']),
			'fedex_key'	=> $this->lib->escape($_POST['fedex_key']),
			'password'	=> $this->lib->escape($_POST['fedex_password']),
			'packaging' => $_POST['packaging'],
			'services' => $_POST['fedex_service']			
		);
		$this ->db ->where('skey',$key);
		$this ->db ->update('shipping_fedex', $shipping_fedex);	
		return 'ok';
	}//end mfedex_saveShippingmethod function
	
	public function add_saveShippingmethod()
	{
		$key = $this ->lib ->GeneralRandomKey(20);
		$re = $this ->db ->query("select label from shipping_rates where skey = '$key'");
		foreach($re->result_array() as $row)
		{
			$key = $this ->lib ->GeneralRandomKey(20);
			$re = $this ->db ->query("select label from shipping_rates where skey = '$key'");
		}
		$shipping_rates = array(
			'skey' => $key,
			'ukey' => $this ->author ->objlogin->uid,
			'label' => $this ->lib ->escape($_POST['label']),
			'description' => $this ->lib ->escape($_POST['description']),
			'shipping_info' => $this ->lib ->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 0,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db->insert('shipping_rates', $shipping_rates);
		$id = $this ->db->insert_id();
		if(is_numeric($id) && $id > 0){
			if(isset($_POST['dataCountries']) && is_array($_POST['dataCountries']) && count($_POST['dataCountries']) > 0){
				foreach($_POST['dataCountries'] as $country_ship){
					$rate_type = (isset($country_ship['rate_type']) && is_numeric($country_ship['rate_type']))?$country_ship['rate_type']:1;
					if($rate_type == 1){
						$country_rate = (isset($country_ship['rate']) && is_numeric($country_ship['rate']) && $country_ship['rate'] > 0)?$country_ship['rate']:0;
						if($country_rate > 0){
							$shipping_manually = array(
								'skey' => $key,
								'country' => $country_ship['code'],
								'country_rate' => $country_rate,
								'rate_type' => $rate_type
							);
							$this ->db->insert('shipping_manually', $shipping_manually);	
							$ship_country_id = $this ->db->insert_id();
						}	
					}elseif($rate_type == 0){
						if(isset($country_ship['states']) && is_array($country_ship['states']) && count($country_ship['states']) > 0){
							$shipping_manually = array(
								'skey' => $key,
								'country' => $country_ship['code'],
								'country_rate' => $country_rate,
								'rate_type' => $rate_type
							);
							$this ->db->insert('shipping_manually', $shipping_manually);
							$ship_country_id =$this ->db->insert_id();
							if(is_numeric($ship_country_id) && $ship_country_id > 0){	//0
								foreach($country_ship['states'] as $states){
									$shipping_manually_states = array(
										'ship_country_id' => $ship_country_id,
										'state' => $states['code'],
										'state_rate' => (isset($states['rate']) && is_numeric($states['rate']) && $states['rate'] > 0)?$states['rate']:0,
									);
									$this ->db->insert('shipping_manually_states', $shipping_manually_states);		
								}	
							}	//0
						}
					}
				}	
			}
		}else{
			return '';	
		}
		return 'ok';
	}//end add_saveShippingmethod function
	
	public function add_ups_loadData()
	{
		$_package_type_ = '';
		$shipment_options = '';
		foreach($this ->__package_type__ as $key => $label_weight)
		{
			$_package_type_ .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		foreach($this ->__UPS_Service__ as $key => $label_weight)
		{
			$shipment_options .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		return array(
			'_package_type_' => $_package_type_,
			'shipment_options' => $shipment_options,
		);
	}//end add_ups_loadData function
	
	public function ups_saveShippingmethod()
	{
		$key = $this ->lib ->GeneralRandomKey(20);
		$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		foreach($re->result_array() as $row)
		{
			$key = $this ->lib ->GeneralRandomKey(20);
			$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		}
		$shipping_rates = array(
			'skey' => $key,
			'ukey' => $this->author->objlogin ->uid,
			'label' => $this ->lib ->escape($_POST['label']),
			'description' => $this ->lib ->escape($_POST['description']),
			'shipping_info' => $this ->lib ->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 1,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db->insert('shipping_rates', $shipping_rates);
		$id = $this ->db ->insert_id();
		if(is_numeric($id) && $id > 0){
			$shipping_ups = array(
				'skey' => $key,
				'access_key' => $this ->lib ->escape($_POST['access_key']),
				'UPS_Shipper' => $this ->lib ->escape($_POST['UPS_Shipper']),
				'UPS_userid' => $this ->lib ->escape($_POST['UPS_userid']),
				'UPS_Password' => $this ->lib ->escape($_POST['UPS_Password']),
				'Package_type' => $_POST['Package_type'],
				'UPS_service' => $_POST['UPS_service']			
			);
			$this ->db->insert('shipping_ups', $shipping_ups);	
		}else{
			return '';	
		}
		return 'ok';
	}//end ups_saveShippingmethod function
	
	public function add_usps_loadData()
	{
		$package_size = '';
		$domestic_services = '';
		$international_services = '';
		foreach($this ->__USPS_package_size__ as $key => $label_size)
		{
			$package_size .= '<option value="'.$key.'">'.$label_size.'</option>';	
		}
		foreach($this ->__USPS_domestic_services__ as $key => $label_services)
		{
			$domestic_services .= '<option value="'.$key.'">'.$label_services.'</option>';	
		}
		foreach($this ->__USPS_international_services__ as $key => $label_services)
		{
			$international_services .= '<option value="'.$key.'">'.$label_services.'</option>';	
		}
		return array(
			'_package_size_' => $package_size,
			'_domestic_services_' => $domestic_services,
			'_international_services_' => $international_services,
		);
	}//end add_usps_loadData function
	
	public function usps_saveShippingmethod()
	{
		$key =$this ->lib-> GeneralRandomKey(20);
		$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		foreach($re->result_array() as $row)
		{
			$key = $this ->lib->GeneralRandomKey(20);
			$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		}
		$shipping_rates = array(
			'skey' => $key,
			'ukey' => $this ->author ->objlogin->uid,
			'label' => $this ->lib->escape($_POST['label']),
			'description' => $this ->lib->escape($_POST['description']),
			'shipping_info' => $this ->lib->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 2,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db->insert('shipping_rates', $shipping_rates);
		$id = $this ->db->insert_id();
		
		if(is_numeric($id) && $id > 0){
			$shipping_usps = array(
				'skey' => $key,
				'USPS_userid' => $this ->lib->escape($_POST['USPS_userid']),
				'Package_type' => $_POST['Package_type'],
				'USPS_service' => $_POST['USPS_service'],
				'USPS_inter_service' => $_POST['USPS_inter_service'],
				'Machinable'	=> $_POST['machinable']			
			);
			$this ->db->insert('shipping_usps', $shipping_usps);	
		}else{
			return '';	
		}
		return 'ok';
	}//end usps_saveShippingmethod function
	
	public function add_fedex_loadData()
	{
		$fedex_packaging = '';
		$fedex_services = '';
		foreach($this->__fedex_packaging__ as $key => $label_pac)
		{
			$fedex_packaging .= '<option value="'.$key.'">'.$label_pac.'</option>';	
		}
		foreach($this->__fedex_services__ as $key => $label_ser)
		{
			$fedex_services .= '<option value="'.$key.'">'.$label_ser.'</option>';	
		}
		return array(
			'packaging' => $fedex_packaging,
			'fedex_service' => $fedex_services
		);
	}//end add_fedex_loadData function
	
	public function fedex_saveShippingmethod()
	{
		$key = $this ->lib->GeneralRandomKey(20);
		$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		foreach($re->result_array() as $row)
		{
			$key =$this ->lib->GeneralRandomKey(20);
			$re = $this ->db->query("select label from shipping_rates where skey = '$key'");
		}
		$shipping_rates = array(
			'skey' => $key,
			'ukey' => $this->author ->objlogin->uid,
			'label' => $this ->lib->escape($_POST['label']),
			'description' => $this ->lib->escape($_POST['description']),
			'shipping_info' => $this ->lib->escape($_POST['shipping_info']),
			'status' => $_POST['status'],
			'shipping_method' => 3,
			'handling_fee' => (isset($_POST['handling_fee']) && $_POST['handling_fee'] > 0)?$_POST['handling_fee']:0
		);
		$this ->db->insert('shipping_rates', $shipping_rates);
		$id = $this ->db->insert_id();
		if(is_numeric($id) && $id > 0){
			$shipping_fedex = array(
				'skey' => $key,
				'account' => $this ->lib->escape($_POST['fedex_acc']),
				'meter' => $this ->lib->escape($_POST['meter_num']),
				'fedex_key'	=> $this ->lib->escape($_POST['fedex_key']),
				'password'	=> $this ->lib->escape($_POST['fedex_password']),
				'packaging' => $_POST['packaging'],
				'services' => $_POST['fedex_service']			
			);
			$this ->db->insert('shipping_fedex', $shipping_fedex);	
		}else{
			return '';	
		}
		return 'ok';
	}//end fedex_saveShippingmethod function
	
	public function delete_ship()
	{
		if(isset($_POST['skey']) && $_POST['skey'] != '')
		{
			$this ->db ->where('skey',$this ->lib ->escape($_POST['skey']));
			$this ->db ->update("shipping_rates", array('status'=> -1));	
		}
		return $this ->loadShipping();
	}//end delete_ship function
	
	public function view_shipping()
	{
		$strContent = array();
		$tblcontries = array();
		$re = $this ->db->query("select * from tblcontries");
		foreach($re->result_array() as $row){
			$states = array();
			$re2 = $this ->db->query("select * from tblsates");
			foreach($re2->result_array() as $row2){
				$states[$row2['code']] = $row2['name'];
			}
			$row['states'] = $states;
			$tblcontries[] = $row;	
		}
		
		$arr_items_shippings = array();
		$pkey = isset($_GET['ItemID'])?$_GET['ItemID']:'';
		$re = $this ->db->query("select * from items_shippings where pkey = '$pkey'");
		foreach($re->result_array() as $row){
			$countries = array();
			$re_2 = $this ->db->query("select * from items_shippings_countries where sid = ".$row['id']);
			foreach($re_2->result_array() as $row_2){
				$states = array();
				$re_3 = $this ->db->query("select * from items_shippings_states where country_id = ".$row_2['id']);
				foreach($re_3->result_array() as $row_3){
					$states[] = $row_3;	
				}
				$row_2['states'] = $states;
				$countries[] = $row_2;	
			}
			$row['countries'] = $countries;
			$arr_items_shippings[] = $row;	
		}
		$re = $this ->db->query("select * from shipping_rates where status <> -1 order by weight desc,label asc");
		foreach($re->result_array() as $row){
			$default_country = array();
			$new_handling = '';
			for($i = 0; $i < count($arr_items_shippings); $i++){
				if($arr_items_shippings[$i]['skey'] == $row['skey']){
					$default_country = $arr_items_shippings[$i]['countries'];
					$new_handling = $arr_items_shippings[$i]['handling'];
					if($new_handling < 0) $new_handling = '';
					break;	
				}	
			}
			$obj_ship = array('skey'=>$row['skey'], 'label'=>$row['label'], 'handling_fee'=>(float)$row['handling_fee'], 'countries'=>array(), 'new_handling'=>$new_handling);
			$re2 = $this ->db->query("select * from shipping_manually where skey = '".$row['skey']."'");
			foreach($re2->result_array() as $row2){
				$country_name = '';
				$country_value = '';
				$default_states = array();
				for($i = 0; $i < count($default_country); $i++){
					if($default_country[$i]['country_code'] == $row2['country'] && $default_country[$i]['rate_type'] == $row2['rate_type']){
						$country_value = $default_country[$i]['country_rate'];
						$default_states = $default_country[$i]['states'];
						break;	
					}	
				}
				$states__ = array();
				if(count($tblcontries) > 0){
					foreach($tblcontries as $obj_country){
						if($obj_country['code'] == $row2['country']){
							$states__ = $obj_country['states'];
							$country_name = $obj_country['name'];
							break;	
						}	
					}	
				}
				if($row2['rate_type'] == 0){
					$states = array();
					$re3 = $this ->db->query("select * from shipping_manually_states where ship_country_id = ".$row2['id']);
					foreach($re3->result_array() as $row3){
						if($row3['state_rate'] >= 0){
							$state_value = '';
							for($i = 0; $i < count($default_states); $i++){
								if($default_states[$i]['state_code'] == $row3['state']){
									$state_value = $default_states[$i]['state_rate'];
									break;	
								}	
							}
							$states[] = array(
								'name' => isset($states__[$row3['state']])?$states__[$row3['state']]:'',
								'code' => $row3['state'],
								'rate' => $row3['state_rate'],
								'value' => $state_value
							);		
						}	
					}
					if(count($states) > 0){
						$obj_ship['countries'][] = array(
							'name' => $country_name,
							'code' => $row2['country'],
							'rate' => (float)$row2['country_rate'],
							'rate_type' => (int)$row2['rate_type'],
							'value' => $country_value,
							'states' => $states
						);	
					}	
				}elseif($row2['rate_type'] == 1){
					if($row2['country_rate'] >= 0){
						$obj_ship['countries'][] = array(
							'name' => $country_name,
							'code' => $row2['country'],
							'rate' => (float)$row2['country_rate'],
							'rate_type' => (int)$row2['rate_type'],
							'value' => $country_value,
							'states' => array()
						);	
					}	
				}	
			}
			$strContent[] = $obj_ship;	
		}
		return $strContent;
	}//end view_shipping function
}//end Shipping_model class
