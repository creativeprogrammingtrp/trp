<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Products_manage_model extends CI_Model
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
	private $__fileImages__ = array('jpg','jpeg','pjpeg','gif','bmp','png');
	
	public function load()
	{
		$manufacturer = '';
		if($this ->author -> objlogin ->role['rid'] != MANUFACTURER) $manufacturer = $this ->add_loadManufacturer();	
		$feature = '<input type="checkbox" class="input-checkbox" value="1" id="feature" />';
		$disabled = 'disabled="disabled"';
		$price_tab = '';
		if($this ->author ->isAccessPerm('products_manage','modify_cost')){
			$disabled = '';
			$price_tab = '<div style="float:left; margin-left:10px" class="tab" id="price_markup_tab" onclick="ChangeContent(5);">Price &amp; Markup</div>';	
		}
		$weight_units_select = '';
		foreach($this ->__weight_units__ as $key => $label_weight){
			$weight_units_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		$units_measurement_select = '';
		foreach($this ->__units_measurement__ as $key => $label_weight){
			$units_measurement_select .= '<option value="'.$key.'">'.$label_weight.'</option>';	
		}
		$categories_select = $this ->system ->get_sysvals('dressing_room', array());
		return array(
			'manufacturer' =>$manufacturer,
			'product_type' =>$this ->lib ->loadProductType(),
			'expiration_date_unit' =>$this ->lib->loadUnitDays(),
			'categories' =>$this ->lib->loadParentCategories(),
			'feature' =>$feature,
			'unit' =>$this ->loadUnit(),
			'disabled_cost' =>$disabled,
			'price_markup_tab' =>$price_tab,
			'_weight_units_' =>$weight_units_select,
			'_units_measurement_' =>$units_measurement_select,
			'load_dressing' =>"arr_dressing_room=".json_encode($categories_select).";"
		);
	}//end load function
	
	public function loadManufacturer($manufacturer){
		$str = '';
		if($this ->author ->objlogin ->role['rid']!= MANUFACTURER){
			$str .= '<span style="float:left; padding-left:5px">';
			$str .= '<select id="manufacturer" style="width:220px; color:#AEAEAE">';
			$str .= '<option value="" style="color:#AEAEAE">All Manufacturers</option>';
			$re2 = $this ->db ->query("select manufacturers.legal_business_name,manufacturers.uid from manufacturers join users on manufacturers.uid = users.uid where users.status = 1 order by manufacturers.legal_business_name ASC");
			foreach($re2->result_array() as $row2){
				$select = '';
				if($manufacturer != '' && $manufacturer == $row2['uid']) $select = 'selected="selected"';
				$str .= "<option value='".$row2['uid']."' $select>".$row2['legal_business_name']."</option>";	
			}
			$str .= '</select>';
			$str .= '</span>';
		}
		return $str;
	}//end loadManufacturer function
	
	public function loadingproducts()
	{
		$num_per_pager = 20;
		$page = (isset($_GET['page'])&&is_numeric($_GET['page'])&&$_GET['page']>0)?$_GET['page']:1;
		$limit = $num_per_pager*($page-1);
		
		$edit = 'no';
		$del = 'no';
		if($this ->author ->isAccessPerm('products_manage','edit')){
			$edit = 'yes';	
		}
		if($this ->author ->isAccessPerm('products_manage','delete')){
			$del = 'yes';	
		}
		
		$ong_chu = $this ->lib ->__loadBoss__();
		$catid = '';
		if(isset($_GET['catid']) && $_GET['catid'] != ''){
			$catid = $_GET['catid'];	
		}
		
		$sql_role = '';
		if($this ->author ->objlogin ->role['rid'] == MANUFACTURER){
			$sql_role = " and items.uid = ".$ong_chu;	
		}
		$key_word_sql = '';
		if(isset($_GET['key_word']) && trim($_GET['key_word']) != ''){
			$key_word = $this ->lib->escape($this ->lib ->replaceSpecChar($_GET['key_word']));
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_sql .= " and (";
						$key_word_sql .= " items.itm_key like '%$key%'";
						$key_word_sql .= " or items.itm_name like '%$key%'";
						$key_word_sql .= " or items.itm_model like '%$key%'";
						$key_word_sql .= " or items.itm_description like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		
		$sql_featured = '';
		if(isset($_GET['featured']) && $_GET['featured'] != ''){
			$sql_featured = " and items.itm_featured = '".$_GET['featured']."'";	
		}
		
		$sql_manufacturer = '';
		if(isset($_GET['manufacturer']) && $_GET['manufacturer'] != ''){
			$sql_manufacturer = " and items.uid = '".$_GET['manufacturer']."'";	
		}
		
		$where = "$sql_role $key_word_sql $sql_featured $sql_manufacturer";
		$re = $this ->db->query("select itm_key from items where itm_status = 1 $where order by itm_date DESC");
		$maxlength = $re->num_rows();
		$sql = "select items.itm_id,items.itm_key,items.itm_name,items.product_type,items.itm_model,items.inventories,items.current_cost,items.itm_featured,duration_refund,duration_type_refund from items where itm_status = 1 $where order by itm_date DESC limit $limit,".$num_per_pager;
		if($catid != ''){
			if($catid == '0'){
				$re = $this ->db->query("select itm_key from items where itm_status = 1 and cat_id = 0 $where order by itm_date DESC");
				$maxlength = $re->num_rows();	
				$sql = "select items.itm_id,items.itm_key,items.itm_name,items.product_type,items.itm_model,items.inventories,items.current_cost,items.itm_featured,duration_refund,duration_type_refund from items where itm_status = 1 and cat_id = 0 $where order by itm_date DESC limit $limit,".$num_per_pager;	
			}else{
				$re = $this ->db->query("select itm_key from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.cat_key = '$catid' $where order by items.itm_date DESC");
				$maxlength = $re->num_rows();	
				$sql = "select items.itm_id,items.itm_key,items.itm_name,items.product_type,items.itm_model,items.inventories,items.current_cost,items.itm_featured,duration_refund,duration_type_refund from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.cat_key = '$catid' $where order by items.itm_date DESC limit $limit,".$num_per_pager;		
			}
		}
		$arr_products = array();
		$re = $this ->db->query($sql);
		foreach($re->result_array() as $row){
			$itm_key = $row['itm_key'];
			
			$current_cost = $row['current_cost'];
			if(!is_numeric($current_cost)) $current_cost = 0;
			if($this ->author->objlogin ->role['rid'] != MANUFACTURER){
				$re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
				if($re_price ->num_rows()>0){
					$row_price = $re_price ->row_array();
					$markup_percentage = $row_price['markup_percentage'];
					if(!is_numeric($markup_percentage)) $markup_percentage = 0;
					$current_cost = $current_cost + $current_cost * $markup_percentage / 100;	
				}
			}
			$row['itm_price'] = $current_cost;
			
			$arr_file = $this ->lib ->__loadFileProduct__($row['itm_id'], 'thumb_slide');
			$row['file'] = $arr_file['file'];
			
			$row['refund'] = $this ->loadDuration($row['duration_refund']*$row['duration_type_refund']);
			$row['product_type'] = (int) $row['product_type'];
			
			$row['edit'] = $edit;
			$row['del'] = $del;
			$arr_products[] = $row;
		}
		return array('data'=>$arr_products, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page);
	}//end loadingproducts function
	
	private function loadDuration($duration)
	{
		$str = '';
		$years = 0;
		$months = 0;
		$days = 0;
		if($duration >= 365){
			$years = round($duration/365);
			$year_thua = $duration - $years * 365;
			if($year_thua >= 30){
				$months = round($year_thua / 30);
				$days = $year_thua - $months * 30;	
			}else{
				$days = $year_thua;	
			}	
		}elseif($duration >= 30){
			$months = round($duration / 30);
			$days = $duration - $months * 30;		
		}else{
			$days = $duration;		
		}
		if($years > 0){
			if($years > 1)
				$str .= "$years years &nbsp;";
			else $str .= "$years year &nbsp;";	
		}
		if($months > 0){
			if($months > 1) $str .= "$months months &nbsp;";
			else $str .= "$months month &nbsp;";	
		}
		if($days > 0){
			if($days > 1) $str .= "$days days ";
			else $str .= "$days day ";
		}
		if($str == '') $str = 'No refund';
		return $str;	
	}//end loadDuration function
	
	public function load_tax_list()
	{
		$str_content = '<div id="tax_setting" class="tax_tab" style=" display:none">';
		$str_content .= '	<div style="clear:both; margin-top:20px; float:left">';	
		
		$arrUsers = array();
		$arr_states = array_merge(array(""=>"All states"),$this ->lib ->GetSystemValues('States'));
	
		$re = $this ->db->query("select * from tax_rates WHERE status <> -1 order by weight DESC");
		foreach($re->result_array() as $row)
		{
			$state = ($row['state']==NULL)?'':$row['state'];
			if(isset($arr_states[$state]))
				$row['state'] = $arr_states[$state];
			else $row['state'] = 'None';	
			$arrUsers[] = $row;
		}
		$str_content .= '<table cellpadding="0" cellspacing="0" border="0" width="800px" class="table-per">';
		$str_content .= '	<thead>';
		$str_content .= '		<tr>';
		$str_content .= '			<th align="left" valign="middle" class="th-per">Name</th>';
		$str_content .= '			<th align="left" valign="middle" class="th-per">Rate</th>';
		$str_content .= '			<th align="right" valign="middle" class="th-per">State</th>';
		$str_content .= '		</tr>';
		$str_content .= '	</thead>';
		$str_content .= '	<tbody>';
		foreach($arrUsers as $tax){
			$tax_rate = $tax['rate'];
			$disable = 'disabled="disabled"';
			$checked = 'checked="checked"';
			$str_content .= '		<tr>';
			$str_content .= '			<td align="left" valign="top" class="td-row" style="max-width:600px !important; min-width:300px; word-wrap:break-word !important">'.$tax['name'].'</td>';
			$str_content .= '			<td align="left" valign="top" class="td-row" style="width:220px">
											&nbsp;&nbsp;&nbsp;%<input type="text"  class="input-text" id="'.$tax['id'].'" style="width:40px; text-align:right;" '.$disable.' onkeypress="return isNumberFloatKey(event)" name="tax_list[]" value="'.$tax_rate.'" />
											<label style="font-style:italic"><input id="chk_'.$tax['id'].'" type="checkbox" style="margin-left:10px" '.$checked.' onclick="return check_Box(\''.$tax['id'].'\')"/> Use rate default</label>
											<input type="hidden" id="hd_'.$tax['id'].'" value="'.$tax['rate'].'"/>
										</td>';
			$str_content .= '			<td align="right" valign="top" class="td-row" style="width:120px">'.$tax['state'].'</td>';
			$str_content .= '		</tr>';
		}
		$str_content .= '	</tbody>';
		$str_content .= '</table>';
		$str_content .= '</div>';
		$str_content .= '</div>';
		return $str_content;
	}//end load_tax_list function
	
	public function loadUnit($value = '')
	{
		$title = 'Product Unit';
		$str = '';
		$attach_pictures = $this ->lib ->GetSystemValues($title);
		if(!is_array($attach_pictures) || count($attach_pictures) == 0){
			$st_attach = "lb|Pounds\n";
			$st_attach .= "kg|Kilograms\n";
			$st_attach .= "oz|Ounces\n";
			$st_attach .= "g|Grams";
			$this ->db ->insert(
				'sysvals',
				array('sysval_title ' => $title, 'sysval_value' => $st_attach)
			);
			$attach_pictures = $this ->lib ->GetSystemValues($title);
		}
		foreach($attach_pictures as $key => $name){
			$select = '';
			if($key == $value) $select = 'selected="selected"';	
			$str .= '<option value="'.$key.'" '.$select.'>'.$this ->lib ->ConvertToTest($name).'</option>';	
		}
		return $str;
	}//end loadUnit function
	
	public function add_loadManufacturer()
	{
		$str = '<div style="font-weight:bold; clear:both; padding-bottom:3px">Vendors : ';
		$str .= '<select id="Ofmanufacturer" style="width:300px" >';
		$str .= '<optgroup label="Manufacturers" >';
		$re2 = $this ->db ->query("select manufacturers.legal_business_name,manufacturers.uid from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5) order by manufacturers.legal_business_name ASC");
		foreach($re2->result_array() as $row2){
			$str .= "<option value='".$row2['uid']."' id='Manufacturers'>".$row2['legal_business_name']."</option>";	
		}
		$str .= '</optgroup>';
		$str .= '<optgroup label="Charities">';
		$re2 = $this ->db ->query("select charities.legal_business_name,charities.uid from charities join users on charities.uid = users.uid and users.status <> -1 order by charities.legal_business_name ASC");
		foreach($re2->result_array() as $row2){
			$str .= "<option value='".$row2['uid']."' id='Charities'>".$row2['legal_business_name']."</option>";	
		}	
		$str .= '</optgroup>';
		$str .= '</select></div>';
		return $str;
	}//end loadManufacturer function
	
	public function __loadProductsPromotion__()
	{
		if(empty($_POST['ItemID'])) return array();
		$arr_Promotion = array();
		$itm_key = $_POST['ItemID'];
		$re = $this ->db ->query("select promo_key from items_promotion where item_key = '$itm_key'");
		foreach($re->result_array() as $row){
			$arr_Promotion[] = $row['promo_key'];
		}	
		return $arr_Promotion;
	}//end __loadProductsPromotion__ function
	
	public function __loadProductExists__()
	{
		$arr_products = array();
		$ItemID = (isset($_POST['ItemID']) && $_POST['ItemID'] != '')?$_POST['ItemID']:'';
		$re = $this ->db->query("select itm_id,itm_name,itm_model from items where itm_status = 1 and itm_key <> '$ItemID' order by itm_date DESC");
		foreach($re->result_array() as $row){
			$file_ = $this ->lib ->__loadFileProduct__($row['itm_id'], 'thumb');
			$row['file'] = $file_['file'];
			$arr_products[] = $row;
		}	
		return $arr_products;
	}//end __loadProductExists__ function
	
	public function loadAttributes()
	{
		$arr_ = array();
		$arr_items_attributes = array();
		$arr_items_options = array();
		
		$pkey = '';
		if(isset($_POST['ItemID']) && $_POST['ItemID'] != ''){
			$pkey = $_POST['ItemID'];
			$re = $this ->db ->query("select * from items_attributes where pkey = '$pkey'");
			foreach($re->result_array() as $row){
				$arr_items_attributes[] = $row;	
			}
			
			$re = $this ->db ->query("select okey,odefault,cost,price,weight from items_options where pkey = '$pkey'");
			foreach($re->result_array() as $row){
				$arr_items_options[] = $row;	
			}	
		}
		$re = $this ->db ->query("select * from attributes where status <> -1 and attri_type = 1 order by weight desc,name asc");
		foreach($re->result_array() as $row){
			
			$arr_options = array();
			$re_2 = $this ->db ->query("select * from attrioptions where status <> -1 and akey = '".$row['akey']."' order by weight desc,name asc");
			foreach($re_2->result_array() as $row_2){
				$my_option = 0;
				$my_objOption = array();
				if(count($arr_items_options) > 0){
					foreach($arr_items_options as $items_options){
						if($items_options['okey'] == $row_2['okey']){
							$my_option = 1;
							$my_objOption = $items_options;
							break;	
						}	
					}	
				}
				$row_2['my_objOption'] = $my_objOption;
				$row_2['my_option'] = $my_option;
				$arr_options[] = $row_2;
			}
			$row['options'] = $arr_options;
			
			$my_attribute = 0;
			$my_objAttribute = array();
			if(count($arr_items_attributes) > 0){
				foreach($arr_items_attributes as $items_attributes){
					if($items_attributes['akey'] == $row['akey']){
						$my_attribute = 1;
						$my_objAttribute = $items_attributes;
						break;	
					}	
				}	
			}
			$row['my_objAttribute'] = $my_objAttribute;
			$row['my_attribute'] = $my_attribute;
			
			$arr_[] = $row;	
		}
		return $arr_;
	}//end loadAttributes function
	
	public function add_item()
	{
		set_time_limit(3600000);
		ini_set("memory_limit", (2*1024)."M");
		
		$itm_saved = 0;
		if(isset($_POST['cost_product']) && is_numeric($_POST['cost_product']) && $_POST['cost_product'] > 0) $itm_saved = $_POST['cost_product'];
		$category = 0;
		if(isset($_POST['category']) && $_POST['category'] != '') $category = $_POST['category'];
		
		$ong_chu = $this ->lib ->__loadBoss__();
		if(isset($_POST['uid']) && $_POST['uid'] != '' && $_POST['uid'] != 0){
			$ong_chu = $_POST['uid'];	
		}
		$inventories = (isset($_POST['inventories']) && is_numeric(trim($_POST['inventories'])))?trim($_POST['inventories']):0;
		$minimum_in_stock = (isset($_POST['minimum_in_stock']) && is_numeric(trim($_POST['minimum_in_stock'])))?trim($_POST['minimum_in_stock']):0;
		$current_cost = (isset($_POST['current_cost']) && is_numeric(trim($_POST['current_cost'])))?trim($_POST['current_cost']):0;
		
		$duration_refund = (isset($_POST['items_duration']) && is_numeric(trim($_POST['items_duration'])))?trim($_POST['items_duration']):0;
		$duration_type_refund = (isset($_POST['items_duration_type']))?$_POST['items_duration_type']:1;
		$charge_refund = (isset($_POST['items_charge_refund']) && is_numeric(trim($_POST['items_charge_refund'])))?trim($_POST['items_charge_refund']):0;
		$charge_refund_type = (isset($_POST['items_charge_refund_type']))?$_POST['items_charge_refund_type']:0;		
		$data = array(
			'itm_name' 				=> $this ->lib ->escape($_POST['name_item']),
			'itm_model'				=> $this ->lib ->escape($_POST['model_item']),
			'origin'				=> $this ->lib ->escape($_POST['origin']),
			'itm_description'		=> $this ->lib ->FCKToSQL($_POST['description_item']),
			'uid'					=> $ong_chu,	// Ong chu manufacturer
			'cat_id'				=> $category,
			'itm_featured'			=> $_POST['itm_featured'],
			'special'				=> (isset($_POST['special']) && $_POST['special'] == 1) ? 1 : 0,
			'inventories'			=> $inventories,
			'minimum_in_stock'		=> $minimum_in_stock,
			'current_cost'			=> $current_cost,
			'voucher_value'			=> (isset($_POST['voucher_value']) && is_numeric($_POST['voucher_value']) && $_POST['voucher_value'] > 0)?$_POST['voucher_value']:0,
			'current_cost_date'		=> time(),
			'duration_refund'  		=> $duration_refund,
			'duration_type_refund' 	=> $duration_type_refund,
			'charge_refund'  		=> $charge_refund,
			'charge_refund_type' 	=> $charge_refund_type,
			'weight' 				=> (isset($_POST['weight']) && is_numeric($_POST['weight']) && $_POST['weight'] > 0)?$_POST['weight']:0,
			'UnitOfPackageWeight' 	=> isset($_POST['UnitOfPackageWeight'])?$_POST['UnitOfPackageWeight']:'lb',
			'length' 				=> (isset($_POST['length']) && is_numeric($_POST['length']) && $_POST['length'] > 0)?$_POST['length']:0,
			'width' 				=> (isset($_POST['width']) && is_numeric($_POST['width']) && $_POST['width'] > 0)?$_POST['width']:0,
			'height' 				=> (isset($_POST['height']) && is_numeric($_POST['height']) && $_POST['height'] > 0) ? $_POST['height'] : 0,
			'UnitOfDimensions' 		=> isset($_POST['UnitOfDimensions']) ? $_POST['UnitOfDimensions'] : 'in',
			'product_type' 			=> (isset($_POST['product_type']) && is_numeric($_POST['product_type'])) ? $_POST['product_type'] : 0,
			'expiration_date'		=> (isset($_POST['expiration_date']) && is_numeric($_POST['expiration_date'])) ? $_POST['expiration_date'] : 1,
			'expiration_date_unit'	=> (isset($_POST['expiration_date_unit']) && is_numeric($_POST['expiration_date_unit'])) ? $_POST['expiration_date_unit'] : 1
		);
		$itm_key = '';
		$item_id = 0;
		if(isset($_POST['item_id']) && $_POST['item_id'] != ''){
			$itm_key = $_POST['item_id'];	
		}
		if($itm_key == ''){
			$itm_key = $this ->lib ->GeneralRandomKey(20);
			$re = $this ->db ->query("select itm_id from items where itm_key = '$itm_key'");
			while($re->num_rows()>0){
				$itm_key = $this ->lib ->GeneralRandomKey(20);
				$re = $this ->db ->query("select itm_id from items where itm_key = '$itm_key'");
			}
			$data['last_cost'] = $current_cost;
			$data['last_cost_date'] = time();
			$data['itm_key'] = $itm_key;
			$data['itm_date'] = time();
			$this ->db ->insert('items', $data);
			$item_id = 	$this ->db ->insert_id();
		}elseif($itm_key != ''){
			$re = $this ->db ->query("select itm_id,current_cost,current_cost_date from items where itm_key = '$itm_key'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				if($row['current_cost'] != $current_cost){
					$data['last_cost'] = $row['current_cost'];
					$data['last_cost_date'] = $row['current_cost_date'];	
				}
				$this->db->update('items', $data, "itm_key = '$itm_key'");
				$item_id = $row['itm_id'];	
			}
		}
		
		$tax_lists = (isset($_POST['tax_lists'])&&is_array($_POST['tax_lists'])&&count($_POST['tax_lists'])>0)?$_POST['tax_lists']:array();
		$this ->db ->query("delete from items_tax where itm_key = '".$itm_key."'");
		foreach($tax_lists as $tax){
			$this ->db ->insert('items_tax', array('itm_key'=>$itm_key,'tax_id'=>(int)$tax['id'],'tax_rate'=>(float)$tax['rate']));
		}
		if($itm_key != ''){
			$dressing_room_path = 'shopping/data/img';
			$file_id = isset($_POST['img_dressing_room'])?$_POST['img_dressing_room']:'';
			if($file_id == ''){
				$dressing_room = '';
				$temp_query = $this ->db ->query("select dressing_room from items where itm_key = '$itm_key'");
				if($temp_query ->num_rows()>0){
					$temp_row  =$temp_query ->row_array();
					$dressing_room = $temp_row['dressing_room'];	
				}
				if(is_file($dressing_room_path.'/'.$dressing_room)){
					unlink($dressing_room_path.'/'.$dressing_room);
					$this ->db ->update('items', array('dressing_room'=>''), "itm_key = '$itm_key'");	
				}
			}else{
				if(!is_file($dressing_room_path.'/'.$file_id)){//0
					$img_dressing_room = '';
					if(isset($_SESSION["file_upload"][$file_id])){
						if(!is_dir($dressing_room_path)){		
							$oldumask = umask(0) ;
							mkdir( $dressing_room_path, 0777);
							umask( $oldumask ) ;
						}else{
							$oldumask = umask(0) ;
							chmod( $dressing_room_path, 0777);
							umask( $oldumask );
						}
						$img = imagecreatefromstring($_SESSION["file_upload"][$file_id]['content']);	
						
						imageSaveAlpha($img, true);
						$farbe_b = imagecolorallocatealpha($img,255,255,255,127);
						imagefill($img, 0, 0, $farbe_b); 	
						imagecolortransparent($img, $farbe_b); 
						ImageAlphaBlending($img, true);
						$ext = $_SESSION["file_upload"][$file_id]['ext'];	
						$img_dressing_room = $file_id.'.'.$ext;
						while(is_file($dressing_room_path.'/'.$img_dressing_room)){	
							$img_dressing_room = md5($img_dressing_room + rand()*100000).".$ext";	
						}
						
						if(strcasecmp($ext,"jpg") == 0){
							imagejpeg($img, $dressing_room_path.'/'.$img_dressing_room, 100);
						}elseif (strcasecmp($ext, "gif") == 0){
							imagegif($img, $dressing_room_path.'/'.$img_dressing_room);
						}elseif (strcasecmp($ext,"png") == 0){
							imagepng($img, $dressing_room_path.'/'.$img_dressing_room);	
						}
						imagedestroy($img);
						if(!is_file($dressing_room_path.'/'.$img_dressing_room)){
							$img_dressing_room = '';	
						}
					}
					$dressing_room = '';
					$temp_query = $this ->db ->query("select dressing_room from items where itm_key = '$itm_key'");
					if($temp_query ->num_rows()>0){
						$temp_row  =$temp_query ->row_array();
						$dressing_room = $temp_row['dressing_room'];	
					}
					if(is_file($dressing_room_path.'/'.$dressing_room)){
						unlink($dressing_room_path.'/'.$dressing_room);
					}
					$this ->db ->update('items', array('dressing_room'=>$img_dressing_room), "itm_key = '$itm_key'");	
				}//0	
			}
			// Locations
			$data_locations = array();
			$new_locations = (isset($_POST['locations']) && is_array($_POST['locations'])) ? $_POST['locations'] : array(); 
			$re = $this ->db ->query("select * from items_locations where ikey = '$itm_key' and status = 1");
			foreach($re->result_array() as $row){
				$data_locations[] = $row;	
			}
			for($i = 0; $i < count($data_locations); $i++){
				$check_exit = false;
				for($j = 0; $j < count($new_locations); $j++){
					if($new_locations[$j]['dbid'] == $data_locations[$i]['id']){
						  $this ->db->update('items_locations', array('location'=>$this ->lib ->escape($new_locations[$j]['value'])), "id = ".$data_locations[$i]['id']);	
						$check_exit = true;
						break;	
					}
				}
				if($check_exit == false){
					$data_locations[$i]['status'] = 0;
					$this ->db->update('items_locations', array('status'=>0), "id = ".$data_locations[$i]['id']);	
				}
			}
			for($j = 0; $j < count($new_locations); $j++){
				$check_exit = false;
				for($i = 0; $i < count($data_locations); $i++){
					if($new_locations[$j]['dbid'] == $data_locations[$i]['id']){
						$check_exit = true;
						break;	
					}
				}
				if($check_exit == false){
					$this ->db->insert('items_locations', array('ikey' => $itm_key, 'location' => $this ->lib ->escape($new_locations[$j]['value'])));	
				}
			}
			
			
			//data_reviews
			if(isset($_POST['data_reviews']) && is_array($_POST['data_reviews']) && count($_POST['data_reviews']) > 0){
				foreach($_POST['data_reviews'] as $review){
					$this ->db->update('reviews', array('status'=>$review['status']), "rid = ".$review['rid']);		
				}	
			}
			$this ->db ->query("delete from items_warranty where pkey = '$itm_key'");
			if(isset($_POST['item_warranty']) && $_POST['item_warranty'] != ''){
				$this ->db->insert('items_warranty', array('pkey' => $itm_key, 'wkey' => trim($_POST['item_warranty'])));		
			}
			if($this ->author ->isAccessPerm('markup','view')){		// Save markup
				$this ->db ->query("delete from commission_salerep_items where item_key = '$itm_key' and catid = 0");
				if(isset($_POST['MultiLevel']) && is_array($_POST['MultiLevel']) && count($_POST['MultiLevel']) > 0){
					$commission_salerep_items = array(
						'item_key' => $itm_key,
						'catid'		=> 0,
						'commission' => implode("|", $_POST['MultiLevel'])
					);
					$this ->db->insert('commission_salerep_items', $commission_salerep_items);		
				}
				$this ->db ->query("delete from items_markup where pkey = '$itm_key'");
				$item_markup = (isset($_POST['item_markup']))?$_POST['item_markup']:'';
				$markup_percentage = 0;
				if(isset($_POST['markup_percentage']) && is_numeric($_POST['markup_percentage'])){
					$markup_percentage = trim($_POST['markup_percentage']);	
				}else{
					$error = 'Markup percentage must be a number.';	
				}
				$commission_charities = 0;
				if(isset($_POST['commission_charities']) && is_numeric($_POST['commission_charities'])){
					$commission_charities = trim($_POST['commission_charities']);	
				}else{
					$error = 'Commission charities must be a number.';	
				}
				$commission_employees_bonus = 0;
				if(isset($_POST['commission_employees_bonus']) && is_numeric($_POST['commission_employees_bonus']) && $_POST['commission_employees_bonus'] >= 0){
					$commission_employees_bonus = trim($_POST['commission_employees_bonus']);	
				}else{
					$error = 'Commission employees bonus be a must number.';	
				}
				$credit_merchant = CREDIT_MERCHANT_DEF;
				if(isset($_POST['credit_merchant']) && is_numeric($_POST['credit_merchant']) && $_POST['credit_merchant'] >= 0){
					$credit_merchant = trim($_POST['credit_merchant']);	
				}else{
					$error = 'Credit Merchant cost be a must number.';	
				}
				$commission_trust_charity = 0;
				if(isset($_POST['commission_trust_charity']) && is_numeric($_POST['commission_trust_charity']) && $_POST['commission_trust_charity'] >= 0){
					$commission_trust_charity = trim($_POST['commission_trust_charity']);	
				}
				$commission_member = 0;
				if(isset($_POST['commission_member']) && is_numeric($_POST['commission_member']) && $_POST['commission_member'] >= 0){
					$commission_member = trim($_POST['commission_member']);	
				}
				$commission_affiliate = 0;
				if(isset($_POST['commission_affiliate']) && is_numeric($_POST['commission_affiliate']) && $_POST['commission_affiliate'] >= 0){
					$commission_affiliate = trim($_POST['commission_affiliate']);	
				}
				$re_markup = $this ->db ->query("select id from product_markup where mkey = '$item_markup'");
				if($re_markup ->num_rows() >0){
					$row_markup = $re_markup ->row_array();
					$markup_id = $row_markup['id'];
					$datas_markup = array(
						'caid' => $category,
						'mid' => $ong_chu,
						'markup_percentage' => $markup_percentage,
						'commission_member' => $commission_member,
						'commission_affiliate' => $commission_affiliate,
						'commission_charities' => $commission_charities,
						'commission_employees_bonus' => $commission_employees_bonus,
						'credit_merchant' => $credit_merchant,
						'commission_trust_charity' => $commission_trust_charity,
						'date_update' => time()
					);
					$this ->db ->update('product_markup', $datas_markup, "mkey = '$item_markup'");
				}else{
					$item_markup = $this ->lib ->GeneralRandomKey(20);
					$re = $this ->db ->query("select id from product_markup where mkey = '$item_markup'");
					
					while($re->num_rows() >0){
						$item_markup = $this ->lib ->GeneralRandomKey(20);
						$re = $this ->db ->query("select id from product_markup where mkey = '$item_markup'");
					}
					$datas_markup = array(
						'mkey' => $item_markup,
						'name' => $this ->lib ->escape($_POST['name_item']),
						'description' => $this ->lib ->escape($_POST['name_item']),
						'caid' => $category,
						'mid' => $ong_chu,
						'markup_percentage' => $markup_percentage,
						'commission_member' => $commission_member,
						'commission_affiliate' => $commission_affiliate,
						'commission_charities' => $commission_charities,
						'commission_employees_bonus' => $commission_employees_bonus,
						'credit_merchant' => $credit_merchant,
						'commission_trust_charity' => $commission_trust_charity,
						'date_create' => time(),
						'date_update' => time()
					);	
					$this ->db ->insert('product_markup', $datas_markup);
					$markup_id = $this ->db ->insert_id();
				}
				$this ->db ->insert('items_markup', array('pkey' => $itm_key,'mkey' => $item_markup));
			}
			if(isset($_POST['items_shippings']) && $_POST['items_shippings'] != 'NA'){
				$re_ship = $this ->db ->query("select id from items_shippings where pkey = '$itm_key'");
				foreach($re_ship ->result_array() as $row_ship){
					$re_country = $this ->db ->query("select id from items_shippings_countries where sid = ".$row_ship['id']);
					foreach($re_country ->result_array() as $row_country){
						$this ->db ->query("delete from items_shippings_states where country_id = ".$row_country['id']);		
					}
					$this ->db ->query("delete from items_shippings_countries where sid = ".$row_ship['id']);		
				}
				$this ->db ->query("delete from items_shippings where pkey = '$itm_key'");
				if(is_array($_POST['items_shippings']) && count($_POST['items_shippings']) > 0){
					for($m = 0; $m < count($_POST['items_shippings']); $m++){
						$obj_ship = $_POST['items_shippings'][$m];
						$data_items_shippings = array(
							'pkey' => $itm_key,
							'skey' => $obj_ship['skey'] 
						);
						if(isset($obj_ship['new_handling']) && is_numeric($obj_ship['new_handling']) && $obj_ship['new_handling'] >= 0){
							$data_items_shippings['handling'] = $obj_ship['new_handling'];	
						}
						$this ->db ->insert('items_shippings', $data_items_shippings);
						$sid = $this ->db ->insert_id();
						if(isset($obj_ship['countries']) && is_array($obj_ship['countries']) && count($obj_ship['countries']) >= 0){
							if(is_numeric($sid) && $sid > 0){//0
								for($i = 0; $i < count($obj_ship['countries']); $i++){
									$obj_country = $obj_ship['countries'][$i];
									if($obj_country['rate_type'] == 1){
										if(isset($obj_country['rate']) && is_numeric($obj_country['rate']) && $obj_country['rate'] >= 0){
											$this ->db ->insert('items_shippings_countries', array('sid'=>$sid, 'country_code'=>$obj_country['code'], 'country_rate'=>$obj_country['rate'], 'rate_type'=>1));		
										}
									}else{
										if(isset($obj_country['states']) && is_array($obj_country['states']) && count($obj_country['states']) > 0){
											$this ->db ->insert('items_shippings_countries', array('sid'=>$sid, 'country_code'=>$obj_country['code'], 'rate_type'=>0));
											$country_id = $this ->db ->insert_id();
											if(is_numeric($country_id) && $country_id > 0){
												for($j = 0; $j < count($obj_country['states']); $j++){
													$obj_states = $obj_country['states'][$j];
													if(isset($obj_states['rate']) && is_numeric($obj_states['rate']) && $obj_states['rate'] >= 0){
														$this ->db ->insert('items_shippings_states', array('country_id'=>$country_id, 'state_code'=>$obj_states['code'], 'state_rate'=>$obj_states['rate']));	
													}
												}//1		
											}		
										}	
									}	
								}	
							}//0		
						}	
					}	
				}	
			}
			if(isset($_POST['items_attributes']) && $_POST['items_attributes'] != 'NA'){
				$this ->db ->query("delete from items_attributes where pkey = '$itm_key'");
				$this ->db ->query("delete from items_options where pkey = '$itm_key'");	
				if(is_array($_POST['items_attributes']) && count($_POST['items_attributes']) > 0){
					foreach($_POST['items_attributes'] as $data_items_attributes){
						$data_items_attributes['pkey'] = $itm_key;
						$this ->db ->insert('items_attributes', $data_items_attributes);		
					}	
				}
				if(isset($_POST['items_options']) && is_array($_POST['items_options']) && count($_POST['items_options']) > 0){
					foreach($_POST['items_options'] as $data_items_options){
						$data_items_options['pkey'] = $itm_key;
						$this ->db ->insert('items_options', $data_items_options);		
					}	
				}
			}
			
			if(isset($_POST['items_promotion']) && $_POST['items_promotion'] != 'NA'){
				$this ->db ->query("delete from items_promotion where item_key = '$itm_key'");
				if(is_array($_POST['items_promotion']) && count($_POST['items_promotion']) > 0){
					foreach($_POST['items_promotion'] as $promo_key){
						$this ->db ->insert('items_promotion', array('item_key'=>$itm_key, 'promo_key'=>$promo_key));		
					}
				}
			}	
		}
		$this ->db ->query("delete from items_related where item1 = '$item_id' or item2 = '$item_id'");
		if(isset($_POST['ids_related']) && is_array($_POST['ids_related']) && count($_POST['ids_related'])>0){
			foreach($_POST['ids_related'] as $id_re){
				if(is_numeric($id_re) && $id_re > 0){
					$data = array(
						'item1'	=> $item_id,
						'item2'	=> $id_re
					);
					$this ->db ->insert('items_related', $data);	
				}	
			}
		}
		if(isset($_POST['image_id']) && is_array($_POST['image_id'])){
			$_files	= array();
			$re_itemfile = $this ->db ->query("select tid,filename,weight from items_files where tid = '$item_id'");
			foreach($re_itemfile ->result_array() as $row){
				$_files[] = $row;	
			}
			
			$image_id = $_POST['image_id'];
			$files_update = array();
			
			$save_path 				= "shopping/data/img";
			$save_path_thumb_slide	= "shopping/data/img/thumb_slide";
			$save_path_thumb 		= "shopping/data/img/thumb";
			$save_path_thumb_show 	= "shopping/data/img/thumb_show";
			$save_path_thumb_home 	= "shopping/data/img/thumb_home";
			$save_path_thumb_crop 	= "shopping/data/img/thumb_crop";
			
			
			for($i = 0; $i < count($image_id); $i++){
				$arr_fileID = explode("|", $image_id[$i]);
				$file_id = (isset($arr_fileID[0]) && $arr_fileID[0] != '')?$arr_fileID[0]:'';
				$extend_ = (isset($arr_fileID[1]) && $arr_fileID[1] != '')?$arr_fileID[1]:'';
				
				if($extend_ != '')
					$file_id = str_replace(".jpg", ".$extend_", $file_id);
				
				$check_exit_file = false;
				if(count($_files) > 0){
					foreach($_files as $arrFiles){
						if($arrFiles['filename'] == $file_id){
							$arrFiles['weight'] = (-1)*$i;
							$files_update[] = $arrFiles;
							$check_exit_file = true;
							break;	
						}	
					}	
				}
				if($check_exit_file == false){
					if(in_array(strtolower($extend_), $this ->__fileImages__) && isset($_SESSION["file_upload"][$file_id]) && $_SESSION["file_upload"][$file_id] != ''){	//1
						$img = imagecreatefromstring($_SESSION["file_upload"][$file_id]['content']);	
						$width = imageSX($img);
						$height = imageSY($img);
						$ext = $_SESSION["file_upload"][$file_id]['ext'];	
						
						$filename = $file_id.".$ext";
						while(is_file($save_path.'/'.$filename)){	
							$filename = md5($file_id + rand()*100000).".$ext";	
						}
						$files_update[] = array('tid'=>$item_id, 'filename'=>$filename, 'weight'=>(-1)*$i);
						
						if(strcasecmp($ext,"jpg") == 0){
							imagejpeg($img, $save_path.'/'.$filename, 100);
						}elseif (strcasecmp($ext, "gif") == 0){
							imagegif($img, $save_path.'/'.$filename);
						}elseif (strcasecmp($ext,"png") == 0){
							imagepng($img, $save_path.'/'.$filename);	
						}
						imagedestroy($img);
						
						if(isset($_SESSION["file_upload"][$file_id]['thumb_slide'])){
							$img_thumb_slide = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb_slide']);	
							if(strcasecmp($ext,"jpg") == 0){
								imagejpeg($img_thumb_slide, $save_path_thumb_slide.'/'.$filename, 100);
							}elseif (strcasecmp($ext, "gif") == 0){
								imagegif($img_thumb_slide, $save_path_thumb_slide.'/'.$filename);
							}elseif (strcasecmp($ext,"png") == 0){
								imagepng($img_thumb_slide, $save_path_thumb_slide.'/'.$filename);	
							}
							imagedestroy($img_thumb_slide);		
						}
						
						if(isset($_SESSION["file_upload"][$file_id]['thumb'])){
							$img_thumb = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb']);	
							if(strcasecmp($ext,"jpg") == 0){
								imagejpeg($img_thumb, $save_path_thumb.'/'.$filename, 100);
							}elseif (strcasecmp($ext, "gif") == 0){
								imagegif($img_thumb, $save_path_thumb.'/'.$filename);
							}elseif (strcasecmp($ext,"png") == 0){
								imagepng($img_thumb, $save_path_thumb.'/'.$filename);	
							}
							imagedestroy($img_thumb);		
						}
						
						if(isset($_SESSION["file_upload"][$file_id]['thumb_show'])){
							$img_thumb_show = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb_show']);	
							if(strcasecmp($ext,"jpg") == 0){
								imagejpeg($img_thumb_show, $save_path_thumb_show.'/'.$filename, 100);
							}elseif (strcasecmp($ext, "gif") == 0){
								imagegif($img_thumb_show, $save_path_thumb_show.'/'.$filename);
							}elseif (strcasecmp($ext,"png") == 0){
								imagepng($img_thumb_show, $save_path_thumb_show.'/'.$filename);	
							}
							imagedestroy($img_thumb_show);		
						}
						
						if(isset($_SESSION["file_upload"][$file_id]['thumb_home'])){
							$img_thumb_home = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb_home']);	
							if(strcasecmp($ext,"jpg") == 0){
								imagejpeg($img_thumb_home, $save_path_thumb_home.'/'.$filename, 100);
							}elseif (strcasecmp($ext, "gif") == 0){
								imagegif($img_thumb_home, $save_path_thumb_home.'/'.$filename);
							}elseif (strcasecmp($ext,"png") == 0){
								imagepng($img_thumb_home, $save_path_thumb_home.'/'.$filename);	
							}
							imagedestroy($img_thumb_home);		
						}
						
						if(isset($_SESSION["file_upload"][$file_id]['thumb_crop'])){
							$imgCrop = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb_crop']);	
							if(strcasecmp($ext,"jpg") == 0){
								imagejpeg($imgCrop, $save_path_thumb_crop.'/'.$filename, 100);
							}elseif (strcasecmp($ext, "gif") == 0){
								imagegif($imgCrop, $save_path_thumb_crop.'/'.$filename);
							}elseif (strcasecmp($ext,"png") == 0){
								imagepng($imgCrop, $save_path_thumb_crop.'/'.$filename);	
							}
							imagedestroy($imgCrop);	
						}
															
					}else{	//1
						
						$path_file = 'plupload/uploads/';
						$video_file = $file_id.'.'.$extend_;
						$filename = $file_id.'.jpg';
						if(is_file($path_file.$video_file)){
							if(copy($path_file.$video_file, $save_path_thumb_show.'/'.$video_file)){
								unlink($path_file.$video_file);
								$files_update[] = array('tid'=>$item_id, 'filename'=>$video_file, 'weight'=>(-1)*$i);
								if(isset($_SESSION["file_upload"][$file_id]['thumb_slide'])){
									$img_thumb_slide = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb_slide']);	
									imagejpeg($img_thumb_slide, $save_path_thumb_slide.'/'.$filename, 100);
									imagedestroy($img_thumb_slide);		
								}
								
								if(isset($_SESSION["file_upload"][$file_id]['thumb'])){
									$img_thumb = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb']);	
									imagejpeg($img_thumb, $save_path_thumb.'/'.$filename, 100);
									imagedestroy($img_thumb);		
								}
								
								if(isset($_SESSION["file_upload"][$file_id]['thumb_home'])){
									$img_thumb_home = imagecreatefromstring($_SESSION["file_upload"][$file_id]['thumb_home']);	
									imagejpeg($img_thumb_home, $save_path_thumb_home.'/'.$filename, 100);
									imagedestroy($img_thumb_home);		
								}	
							}	
						}	
					}		//1
				}	
			}
			
			//Update DataBase
			$this ->db ->query("delete from items_files where tid = '$item_id'");
			if(count($files_update) > 0){
				
				foreach($files_update as $arrFiles){
					$this ->db ->insert("items_files", $arrFiles);	
				}	
			}
			if(count($_files) > 0){
				foreach($_files as $arrFiles){
					$check_exit = false;
					for($i = 0; $i < count($files_update); $i++){
						if($files_update[$i]['filename'] == $arrFiles['filename']){
							$check_exit = true;
							break;	
						}	
					}
					if($check_exit == false){
						if(is_file($save_path.'/'.$arrFiles['filename'])) unlink($save_path.'/'.$arrFiles['filename']);	
						if(is_file($save_path_thumb.'/'.$arrFiles['filename'])) unlink($save_path_thumb.'/'.$arrFiles['filename']);	
						if(is_file($save_path_thumb_show.'/'.$arrFiles['filename'])) unlink($save_path_thumb_show.'/'.$arrFiles['filename']);	
						if(is_file($save_path_thumb_home.'/'.$arrFiles['filename'])) unlink($save_path_thumb_home.'/'.$arrFiles['filename']);	
						if(is_file($save_path_thumb_crop.'/'.$arrFiles['filename'])) unlink($save_path_thumb_crop.'/'.$arrFiles['filename']);
						if(is_file($save_path_thumb_slide.'/'.$arrFiles['filename'])) unlink($save_path_thumb_slide.'/'.$arrFiles['filename']);
						
						$arr_file_del = explode(".", $arrFiles['filename']);
						if(count($arr_file_del) > 0){
							$fileid_del = '';
							for($i = 0; $i < count($arr_file_del)-1; $i++){
								$fileid_del .= $arr_file_del[$i].'.';
							}
							if($fileid_del != ''){
								$fileid_del .= 'jpg';
								if(is_file($save_path_thumb.'/'.$fileid_del)) unlink($save_path_thumb.'/'.$fileid_del);		
								if(is_file($save_path_thumb_home.'/'.$fileid_del)) unlink($save_path_thumb_home.'/'.$fileid_del);	
								if(is_file($save_path_thumb_slide.'/'.$fileid_del)) unlink($save_path_thumb_slide.'/'.$fileid_del);
							}	
						}	
					}
						
				}	
			}
		}
		unset($_SESSION["file_upload"]);
		echo 'ok';
	}//end add_item function
	
	public function edit_loadValue($itm_key='')
	{
		$id = 0;
		$img_dressing_room = '';
		$dem = 0;
		
		$product_type = 0;
		$expiration_date = 1;
		$expiration_date_unit = 1;
		$special_product = '';
		
		$manufacturer = '';
		$current_cost = '';
		$voucher_value = '';
		$days = '';
		$months = '';
		$years = '';
		$percentage = '';
		$dollar = '';
		$item_warranty = '';
		$feature = '';
		$ids_related = '';
		$product_reviews = '';
		$units_measurement_select = '';
		$weight_units_select = '';		
		$name_item = '';
		$model_item = '';
		$origin = '';
		$description_item = '';
		$unit = '';
		$inventories = '';
		$minimum_in_stock = '';
		$current_cost_date = '';
		$last_cost = '';
		$last_cost_date = '';
		$duration = '';
		$charge_refund = '';
		$categories = '';
		$weight = '';
		$length = '';
		$width = '';
		$height = '';
		$re = $this ->db->query("select * from items where itm_key = '$itm_key' ");
		if($re->num_rows()>0){
			$row = $re->row_array();
			$id = $row['itm_id'];
			$uid_ = $row['uid'];
			if($this->author ->objlogin ->role['rid'] != MANUFACTURER){
				$manufacturer = $this ->edit_loadManufacturer($uid_);	
			}
			
			$current_cost = (is_numeric($row['current_cost']))?$row['current_cost']:0;
			$voucher_value = (is_numeric($row['voucher_value']))?$row['voucher_value']:0;
			
			$expiration_date = $row['expiration_date'];
			$expiration_date_unit = $row['expiration_date_unit'];
			
			if($row['special'] == 1) $special_product = 'checked="checked"';
			
			$_SESSION['item_id_edit'] = $id;
			
			$duration_type = $row['duration_type_refund'];
			if($duration_type == 1) $days = 'selected="selected"';
			elseif($duration_type == 30) $months = 'selected="selected"';
			elseif($duration_type == 365) $years = 'selected="selected"';
			
			$charge_refund_type = $row['charge_refund_type'];
			if($charge_refund_type == 0) $percentage = 'selected="selected"';
			elseif($charge_refund_type == 1) $dollar = 'selected="selected"';
			$temp_query = $this ->db ->query("select wkey from items_warranty where pkey = '$itm_key'");
			if($temp_query ->num_rows()>0){
				$temp_row = $temp_query ->row_array();
				$item_warranty = $temp_row['wkey'];
			}
			if($item_warranty == null) $item_warranty = '';
	
			$feature_check = '';
			if($row['itm_featured']==1){
				$feature_check = 'checked="checked"';
			}
			$feature = '<input type="checkbox" class="input-checkbox" value="1" '.$feature_check.' id="feature" />';
			
			$ids_related = '';
			$r = $this ->db->query("SELECT DISTINCT item1,item2 FROM items_related WHERE item1 = '$id' OR item2 = '$id'");
			foreach($r ->result_array() as $row2){
				$_id = ($row2['item2']==$id)?$row2['item1']:$row2['item2'];
				$ids_related .= "ids_related[ids_related.length] = $_id;\n";
			}
			
			foreach($this ->__weight_units__ as $key => $label_weight){
				$select_weight = '';
				if($key == $row['UnitOfPackageWeight']) $select_weight = 'selected="selected"';
				$weight_units_select .= '<option value="'.$key.'" '.$select_weight.'>'.$label_weight.'</option>';	
			}
			
			foreach($this ->__units_measurement__ as $key => $label_weight){
				$select_weight = '';
				if($key == $row['UnitOfDimensions']) $select_weight = 'selected="selected"';
				$units_measurement_select .= '<option value="'.$key.'" '.$select_weight.'>'.$label_weight.'</option>';	
			}
			
			//load product reviews
			$product_reviews = $this ->loadReviews($id);
			//end load product reviews
			
			if(is_file("shopping/data/img/".$row['dressing_room'])){
				$img_dressing_room = $row['dressing_room'];
			}
			
			$product_type = $row['product_type'];
			
			$name_item = $row['itm_name'];
			$model_item = $row['itm_model'];
			$origin = $row['origin'];
			$description_item = $this ->lib ->SQLToFCK($row['itm_description']);
			$inventories = $row['inventories'];
			$minimum_in_stock = $row['minimum_in_stock'];
			$current_cost_date = date("m/d/Y", $row['current_cost_date']);
			$last_cost = number_format($row['last_cost'],2);
			$last_cost_date = date("m/d/Y", $row['last_cost_date']);
			$duration = $row['duration_refund'];
			$charge_refund = $row['charge_refund'];
			$categories = $this ->lib ->loadParentCategories($row['cat_id']);
			$weight = $row['weight'];
			$length = $row['length'];
			$width = $row['width'];
			$height = $row['height'];							
		}
		$disabled = 'disabled="disabled"';
		$price_tab = '';
		if($this ->author ->isAccessPerm('products_manage','modify_cost')){
			$disabled = '';
			$price_tab = '<div style="float:left; margin-left:10px" class="tab" id="price_markup_tab" onclick="ChangeContent(5);">Price &amp; Markup</div>';	
		}
		
		$tax_tab = '';
		$tax_list = '';
		if($this ->author ->isAccessPerm('products_manage','modify_tax')){
			$tax_tab = '<div style="float:left; margin-left:10px" class="tab" id="tax_settings_tab" onclick="ChangeContent(7);">Tax</div>';
			$tax_list = $this ->edit_load_tax_list($itm_key);
		}
		$categories_select = $this ->system ->get_sysvals('dressing_room', array());				
		return array(
			'manufacturer' =>$manufacturer,
			'item_id' =>$itm_key,
			'current_cost' =>$current_cost,
			'voucher_value' =>$voucher_value,
			'days' =>$days,
			'months' =>$months,
			'years' =>$years,
			'percentage' =>$percentage,
			'dollar' =>$dollar,
			'item_warranty' =>$item_warranty,
			'feature' =>$feature,
			'load_ids_related' =>$ids_related,
			'product_reviews' =>$product_reviews,
			'_units_measurement_' =>$units_measurement_select,
			'_weight_units_' =>$weight_units_select,
			'name_item' =>$name_item,
			'model_item' =>$model_item,
			'origin' =>$origin,
			'description_item' =>$description_item,
			'inventories' =>$inventories,
			'minimum_in_stock' =>$minimum_in_stock,
			'current_cost_date' =>$current_cost_date,
			'last_cost' =>$last_cost,
			'last_cost_date' =>$last_cost_date,
			'duration' =>$duration,
			'charge_refund' =>$charge_refund,
			'categories' =>$categories,
			'weight' =>$weight,
			'length' =>$length,
			'width' =>$width,
			'height' =>$height,
			'special_product' =>$special_product,
			'expiration_date' =>$expiration_date,
			'expiration_date_unit' =>$this ->lib->loadUnitDays($expiration_date_unit),
			'product_type' =>$this ->lib->loadProductType($product_type),
			'load_locations' =>"var data_locations=".json_encode($this ->loadLocations($itm_key)).";",
			'disabled_cost' =>$disabled,
			'price_markup_tab' =>$price_tab,
			'tax_tab' =>$tax_tab,
			'tax_list' =>$tax_list,
			'img_dressing_room' =>$img_dressing_room,
			'load_dressing' =>"arr_dressing_room=".json_encode($categories_select).";"
		);
	}//end edit_loadValue function
	
	public function edit_loadManufacturer($uid)
	{
		$str = '<div style="font-weight:bold; clear:both; padding-bottom:3px">Vendors : ';
		$str .= '<select id="Ofmanufacturer" style="width:300px">';
		$str .= '<optgroup label="Manufacturers">';
		$re2 = $this ->db->query("select manufacturers.legal_business_name,manufacturers.uid from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5) order by manufacturers.legal_business_name ASC");
		foreach($re2 ->result_array() as $row2){
			$select_ = '';
			if($uid == $row2['uid']){
				$select_ = 'selected="selected"';	
			}
			$str .= "<option value='".$row2['uid']."' $select_>".$row2['legal_business_name']."</option>";	
		}
		$str .= '</optgroup>';
		$str .= '<optgroup label="Charities">';
		$re2 = $this ->db->query("select charities.legal_business_name,charities.uid from charities join users on charities.uid = users.uid and users.status <> -1 order by charities.legal_business_name ASC");
		foreach($re2 ->result_array() as $row2){
			$select_ = '';
			if($uid == $row2['uid']){
				$select_ = 'selected="selected"';	
			}
			$str .= "<option value='".$row2['uid']."' $select_>".$row2['legal_business_name']."</option>";	
		}	
		$str .= '</optgroup>';
		$str .= '</select></div>';
		return $str;
	}//end loadManufacturer function
	
	private function loadReviews($id){
		$product_reviews = '';
		$re = $this ->db ->query("select * from reviews where itm_id = $id order by rid desc");
		foreach($re->result_array() as $row){
			$r_name = $row['rname'];
			$r_content = $row['rcontent'];
			$r_status = $row['status'];
			$r_id = $row['rid'];
			$r_rating = $row['rating'];
			$checked = ($r_status==1)? 'checked = "checked"':'';
			$product_reviews.='<tr>';
			$product_reviews.='<td class="td_name">'.$r_name.'</td>';
			$product_reviews.='<td  class="td_review">'.$r_content.'</td>';
			$product_reviews.='<td  class="td_rating">'.$r_rating.'</td>';
			$product_reviews.='<td class="td_active" style="text-align:center;"><input type="checkbox" value="'.$r_id.'" class="review_check" name="cb_review[]" '.$checked.' /></td>';
			$product_reviews.='<td class="td_del"><a href="javascript:void(0)" onclick = "deleteReview('.$r_id.','.$id.')" class="del_review"></a></td>';
			$product_reviews.='</tr>';    				
		}
		return $product_reviews;	
	}//end loadReviews function
	
	private function loadLocations($ikey)
	{
		$arr_return = array();
		$re = $this ->db->query("select id,location from items_locations where ikey = '$ikey' and status = 1");
		foreach($re->result_array() as $row){
			$arr_return[] = $row;	
		}
		return $arr_return;	
	}//end loadLocations function
	
	public function loadImgProduct()
	{
		$arr = array();
		if(isset($_POST['itemID']) && $_POST['itemID'] != ''){
			$re = $this ->db->query("select items_files.filename from items_files join items on items_files.tid = items.itm_id where items.itm_key = '".$_POST['itemID']."' and items.itm_status <> -1 order by items_files.weight DESC ");
			foreach($re->result_array() as $row){
				if(is_file("shopping/data/img/thumb_show/".$row['filename'])){
					$arr_file_del = explode(".", $row['filename']);
					if(count($arr_file_del) > 0){
						$file_id = '';
						$ext = $arr_file_del[count($arr_file_del)-1];
						if(in_array(strtolower($ext), $this ->__fileImages__)){
							$file_id = $row['filename'];	
						}else{
							$fileid_del = '';
							for($i = 0; $i < count($arr_file_del)-1; $i++){
								$fileid_del .= $arr_file_del[$i].'.';
							}
							if($fileid_del != ''){
								$fileid_del .= 'jpg';
							}
							$file_id = $fileid_del.'|'.$ext;	
						}
						$arr[] = $file_id;	
					}		
				}
			}
		}

		return json_encode($arr);
	}	//end loadImgProduct function
	
	private function edit_load_tax_list($itm_key)
	{
		$tax_rate_items = array();
		$re = $this ->db ->query("select * from items_tax where itm_key = '".$itm_key."'");
		foreach($re->result_array() as $row){
			$tax_rate_items[] = $row;	
		}
		
		$str_content = '<div id="tax_setting" class="tax_tab" style=" display:none">';
		$str_content .= '	<div style="clear:both; margin-top:20px; float:left">';	
		$arrUsers = array();
		$arr_states = array_merge(array(""=>"All states"),$this ->lib ->GetSystemValues('States'));
	
		$re = $this ->db ->query("select * from tax_rates WHERE status <> -1 order by weight DESC");
		foreach($re->result_array() as $row)
		{
			$state = ($row['state']==NULL)?'':$row['state'];
			if(isset($arr_states[$state]))
				$row['state'] = $arr_states[$state];
			else $row['state'] = 'None';	
			$arrUsers[] = $row;
		}
		$str_content .= '<table cellpadding="0" cellspacing="0" border="0" width="800px" class="table-per" style="border:none !important">';
		$str_content .= '	<thead>';
		$str_content .= '		<tr>';
		$str_content .= '			<th align="left" valign="middle" class="th-per">Name</th>';
		$str_content .= '			<th align="left" valign="middle" class="th-per">Rate</th>';
		$str_content .= '			<th align="right" valign="middle" class="th-per">State</th>';
		$str_content .= '		</tr>';
		$str_content .= '	</thead>';
		$str_content .= '	<tbody>';
		
		foreach($arrUsers as $tax){
			$tax_rate = $tax['rate'];
			$disable = 'disabled="disabled"';
			$checked = 'checked="checked"';
			for($i = 0; $i < count($tax_rate_items); $i++){
				if($tax_rate_items[$i]['tax_id'] == $tax['id']){
					$tax_rate = $tax_rate_items[$i]['tax_rate'];
					$disable = '';
					$checked = '';
					break;
				}
			}
			$str_content .= '		<tr>';
			$str_content .= '			<td align="left" valign="top" class="td-row" style="max-width:600px !important; min-width:300px; word-wrap:break-word !important">'.$tax['name'].'</td>';
			$str_content .= '			<td align="left" valign="top" class="td-row" style="width:230px">
											&nbsp;&nbsp;&nbsp;%<input type="text"  class="input-text" id="'.$tax['id'].'" style="width:40px; text-align:right;" '.$disable.' onkeypress="return isNumberFloatKey(event)" name="tax_list[]" value="'.$tax_rate.'" />
											<label style="font-style:italic"><input id="chk_'.$tax['id'].'" type="checkbox" style="margin-left:10px" '.$checked.' onclick="return check_Box(\''.$tax['id'].'\')"/> Use rate default</label>
											<input type="hidden" id="hd_'.$tax['id'].'" value="'.$tax['rate'].'"/>
										</td>';
			$str_content .= '			<td align="right" valign="top" class="td-row" style="width:120px">'.$tax['state'].'</td>';
			$str_content .= '		</tr>';
		}
		
		$str_content .= '	</tbody>';
		$str_content .= '</table>';
		$str_content .= '</div>';
		$str_content .= '</div>';
		return $str_content;
	}//end edit_load_tax_list function
	
	public function edit_loadAttributes()
	{
		$arr_ = array();
		$arr_items_attributes = array();
		$arr_items_options = array();
		
		$pkey = '';
		if(isset($_POST['ItemID']) && $_POST['ItemID'] != ''){
			$pkey = $_POST['ItemID'];
			$re = $this ->db ->query("select * from items_attributes where pkey = '$pkey'");
			foreach($re->result_array() as $row){
				$arr_items_attributes[] = $row;	
			}
			
			$re = $this ->db ->query("select okey,odefault,cost,price,weight from items_options where pkey = '$pkey'");
			foreach($re->result_array() as $row){
				$arr_items_options[] = $row;	
			}	
		}
		$re = $this ->db ->query("select * from attributes where status <> -1 and attri_type = 1 order by weight desc,name asc");
		foreach($re->result_array() as $row){
			
			$arr_options = array();
			$re_2 = $this ->db ->query("select * from attrioptions where status <> -1 and akey = '".$row['akey']."' order by weight desc,name asc");
			foreach($re_2->result_array() as $row_2){
				$my_option = 0;
				$my_objOption = array();
				if(count($arr_items_options) > 0){
					foreach($arr_items_options as $items_options){
						if($items_options['okey'] == $row_2['okey']){
							$my_option = 1;
							$my_objOption = $items_options;
							break;	
						}	
					}	
				}
				$row_2['my_objOption'] = $my_objOption;
				$row_2['my_option'] = $my_option;
				$arr_options[] = $row_2;
			}
			$row['options'] = $arr_options;
			
			$my_attribute = 0;
			$my_objAttribute = array();
			if(count($arr_items_attributes) > 0){
				foreach($arr_items_attributes as $items_attributes){
					if($items_attributes['akey'] == $row['akey']){
						$my_attribute = 1;
						$my_objAttribute = $items_attributes;
						break;	
					}	
				}	
			}
			$row['my_objAttribute'] = $my_objAttribute;
			$row['my_attribute'] = $my_attribute;
			
			$arr_[] = $row;	
		}
		return $arr_;
	}//end edit_loadAttributes function
	
	
	public function delete_item()
	{
		if(!empty($_POST['itemid'])){   
                    $itemid = $_POST['itemid'];
                    
                    if(!empty($_POST['itm_id'])){
                        $itm_id = $_POST['itm_id'];
                         $this->db->delete('reviews', array('itm_id' => $itm_id)); 
                    }
			$this ->db ->update("items", array('itm_status' => -1), "itm_key = '$itemid'");	
		}
	}//end delete_item function
	
	public function delete_review()
	{
		if(empty($_POST['rid']) || empty($_POST['itm'])) return '';
		$rid = $_POST['rid'];
		settype($rid,'int');
		if($rid>0){
			if(empty($_POST['status'])){
				$this ->db->query("delete from reviews where rid = $rid");
			}
			else{
				$status = $_POST['status'];
				settype($status,'int');
				$this ->db->query("update reviews set status = $status where rid = $rid");
			}
		}
		return $this ->loadReviews($_POST['itm']);
	}//end delete_review function 
}//end Products_manage_model class