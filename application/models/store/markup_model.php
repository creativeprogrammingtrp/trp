<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Markup_model extends CI_Model
{
	public function loadObj()
	{
		$arrUsers = array();
		$modify = 'no';
		$del = 'no';
		if($this->author->isAccessPerm("markup","edit")){
			$modify = 'yes';	
		}
		if($this->author->isAccessPerm("markup","delete")){
			$del = 'yes';	
		}
		$re = $this ->db->query("select * from product_markup WHERE status <> -1 order by date_update DESC");
		foreach($re->result_array() as $row){
			$row['date_update_str'] = date("m/d/Y", $row['date_update']);
			$row['date_create_str'] = date("m/d/Y", $row['date_create']);
			$category = '';
			if($row['caid'] == 0){
				$category = 'All Categories';	
			}else{
				$re1 = $this ->db->query("select cat_name from categories where cat_id = ".$row['caid']." and status <> -1");
				if($re1 ->num_rows()>0){
					$row1 = $re1->row_array();
					$category = $row1['cat_name'];	
				}else{
					continue;	
				}	
			}
			$row['category'] = $category;
			
			$manufacturer = '';
			if($row['mid'] == 0){
				$manufacturer = 'All Manufacturers';	
			}else{
				$re1 = $this ->db->query("select manufacturers.legal_business_name from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 and manufacturers.uid = ".$row['mid']);
				if($re1 ->num_rows()>0){
					$row1 = $re1->row_array();
					$manufacturer = $row1['legal_business_name'];	
				}else{
					continue;	
				}	
			}
			$row['manufacturer'] = $manufacturer;
				
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end loadObj function
	
	public function loadManufacturers($value='')
	{
		$str = '';
		$re = $this ->db->query("select manufacturers.legal_business_name,manufacturers.uid from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 order by manufacturers.legal_business_name ASC");
		foreach($re ->result_array() as $row){
			$select = '';
			if($row['uid'] == $value) $select = 'selected="selected"';
			$str .= '<option value="'.$row['uid'].'" '.$select.'>'.$row['legal_business_name'].'</option>';		
		}
		return $str;				
	}//end loadManufacturers function
	
	public function add_saveObj()
	{
		$error = '';
		$category = isset($_POST['category'])?$_POST['category']:0;
		$manufacturer = isset($_POST['manufacturer'])?$_POST['manufacturer']:0;
		
		$key = $this ->lib ->GeneralRandomKey(20);
		$re = $this ->db ->query("select id from product_markup where mkey = '$key'");
		while($row->num_rows() >0){
			$key = $this ->lib ->GeneralRandomKey(20);
			$re = $this ->db ->query("select id from product_markup where mkey = '$key'");
		}
		
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
		if(isset($_POST['commission_employees_bonus']) && is_numeric($_POST['commission_employees_bonus'])){
			$commission_employees_bonus = trim($_POST['commission_employees_bonus']);	
		}else{
			$error = 'Commission employees bonus must be a number.';	
		}
		
		$credit_merchant = CREDIT_MERCHANT_DEF;
		if(isset($_POST['credit_merchant']) && is_numeric($_POST['credit_merchant'])){
			$credit_merchant = trim($_POST['credit_merchant']);	
		}else{
			$error = 'Credit merchant cost must be a number.';	
		}
		
		$commission_trust_charity = 0;
		if(isset($_POST['commission_trust_charity']) && is_numeric($_POST['commission_trust_charity']) && $_POST['commission_trust_charity'] > 0){
			$commission_trust_charity = trim($_POST['commission_trust_charity']);	
		}
			
		$datas = array(
			'mkey' => $key,
			'name' => $this ->lib ->escape($_POST['name']),
			'description' => $this ->lib ->escape($_POST['description']),
			'caid' => $category,
			'mid' => $manufacturer,
			'markup_percentage' => $markup_percentage,
			'commission_charities' => $commission_charities,
			'commission_employees_bonus' => $commission_employees_bonus,
			'credit_merchant' => $credit_merchant,
			'commission_trust_charity' => $commission_trust_charity,
			'date_create' => time(),
			'date_update' => time()
		);
		if($error == ''){
			$this ->db ->insert('product_markup', $datas);
			$id = $this ->db ->insert_id();
			if(is_numeric($id) && $id > 0){
				if(isset($_POST['salerepPost']) && is_array($_POST['salerepPost'])){
					foreach($_POST['salerepPost'] as $salerep){
						if(is_array($salerep) && count($salerep) > 0){
							$this ->db ->insert('product_salerep', array('markup_id'=>$id));
							$salerep_id = $this ->db ->insert_id();
							foreach($salerep as $commission){
								if(!is_numeric($commission)) $commission = 0;
								$this ->db ->insert('product_case', array('salerep_id'=>$salerep_id, 'commission'=>$commission));	
							}
						}	
					}	
				}	
			}else{
				$error = _error_cannot_insert_db_;		
			}	
		}
		return array('error' => $error);
	}//end add_saveObj function
	
	public function loadValue()
	{
		$key = '';
		$category = 0;
		$manufacturers = 0;
		$markup_percentage = '';
		$commission_charities = '';
		$name = '';
		$commission_employees_bonus = 0;
		$credit_merchant = CREDIT_MERCHANT_DEF;
		$commission_trust_charity = 0;
		$description = '';
		$id = 0;
		
		if(isset($_GET['key'])){
			$key = $_GET['key'];
			$re = $this ->db->query("select * from product_markup where mkey = '$key'");
			if($re->num_rows()>0){
				$row = $re->row_array();
				$id = $row['id'];
				$category = $row['caid'];
				$manufacturers = $row['mid'];
				$name = $row['name'];
				$description = $row['description']==null?'':$row['description'];
				
				$markup_percentage = $row['markup_percentage'];
				if(!is_numeric($markup_percentage)) $markup_percentage = 0;	
				
				$commission_charities = $row['commission_charities'];
				if(!is_numeric($commission_charities)) $commission_charities = 0;	
				
				$commission_employees_bonus = $row['commission_employees_bonus'];
				if(!is_numeric($commission_employees_bonus)) $commission_employees_bonus = 0;
				
				$credit_merchant = $row['credit_merchant'];
				if(!is_numeric($credit_merchant)) $credit_merchant = CREDIT_MERCHANT_DEF;
				
				$commission_trust_charity = $row['commission_trust_charity'];		
			}	
		}
		return array(
			'category' =>$this ->lib ->loadParentCategories($category),
			'manufacturers' =>$this ->edit_loadManufacturers($manufacturers),
			'key' =>$key,
			'description' =>$description,
			'name' =>$name,
			'markup_percentage' =>$markup_percentage,
			'commission_charities' =>$commission_charities,
			'commission_employees_bonus' =>$commission_employees_bonus,
			'credit_merchant' => $credit_merchant,
			'commission_trust_charity' =>$commission_trust_charity,
			'loadObjSaleRep' =>"dataSaleRep=".json_encode($this ->loadObjSaleRep($id)).";",
		);
	}//end loadValue function
	
	private function edit_loadManufacturers($value='')
	{
		$str = '';
		$re = $this->db->query("select manufacturers.legal_business_name,manufacturers.uid from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 order by manufacturers.legal_business_name ASC");
		foreach($re->result_array() as $row){
			$select = '';
			if($row['uid'] == $value) $select = 'selected="selected"';
			$str .= '<option value="'.$row['uid'].'" '.$select.'>'.$row['legal_business_name'].'</option>';		
		}
		return $str;				
	}//end edit_loadManufacturers function
	
	private function loadObjSaleRep($id)
	{
		$arr = array();
		$dem = 0;
		$re = $this ->db->query("select id from product_salerep where markup_id = $id order by id ASC");
		foreach($re->result_array() as $row){
			$arr_sale = array('id'=>$dem);
			$arr_com = array();
			$re_1 = $this ->db->query("select commission from product_case where salerep_id = ".$row['id']." order by id ASC");
			foreach($re1->result_array() as $row1){
				$arr_com[] = (float)$row_1['commission'];	
			}
			$arr_sale['value'] = $arr_com;
			$arr[] = $arr_sale;
			$dem ++;	
		}
		return $arr;
	}//end loadObjSaleRep function
	
	public function edit_saveObj()
	{
		$error = '';
		$category = isset($_POST['category'])?$_POST['category']:0;
		$manufacturer = isset($_POST['manufacturer'])?$_POST['manufacturer']:0;
		
		$key = (isset($_POST['key']) && $_POST['key'] != '')?$_POST['key']:'';
		
		$markup_percentage = 0;
		if(isset($_POST['markup_percentage']) && is_numeric($_POST['markup_percentage'])){
			$markup_percentage = trim($_POST['markup_percentage']);	
		}else{
			$error = 'Markup percentage must be number.';	
		}
		
		$commission_charities = 0;
		if(isset($_POST['commission_charities']) && is_numeric($_POST['commission_charities'])){
			$commission_charities = trim($_POST['commission_charities']);	
		}else{
			$error = 'Commission charities must be number.';	
		}
		
		$commission_employees_bonus = 0;
		if(isset($_POST['commission_employees_bonus']) && is_numeric($_POST['commission_employees_bonus'])){
			$commission_employees_bonus = trim($_POST['commission_employees_bonus']);	
		}else{
			$error = 'Commission employees bonus must be number.';	
		}
		
		$credit_merchant = CREDIT_MERCHANT_DEF;
		if(isset($_POST['credit_merchant']) && is_numeric($_POST['credit_merchant'])){
			$credit_merchant = trim($_POST['credit_merchant']);	
		}else{
			$error = 'Credit Merchant cost must be number.';	
		}
		
		$commission_trust_charity = 0;
		if(isset($_POST['commission_trust_charity']) && is_numeric($_POST['commission_trust_charity']) && $_POST['commission_trust_charity'] > 0){
			$commission_trust_charity = trim($_POST['commission_trust_charity']);	
		}
		
		$datas = array(
			'name' => $this ->lib ->escape($_POST['name']),
			'description' => $this ->lib ->escape($_POST['description']),
			'caid' => $category,
			'mid' => $manufacturer,
			'markup_percentage' => $markup_percentage,
			'commission_charities' => $commission_charities,
			'commission_employees_bonus' => $commission_employees_bonus,
			'credit_merchant' => $credit_merchant,
			'commission_trust_charity' => $commission_trust_charity,
			'date_update' => time()
		);
		if($error == ''){
			$this ->db ->update('product_markup', $datas, "mkey = '$key'");
			$id =0;
			$re = $this ->db ->query("select id from product_markup where mkey = '$key'");
			if($re->num_rows() >0) {
				$row = $re ->row_array();
				$id = $row['id'];	
			}
			if(is_numeric($id) && $id > 0){
				$re = $this ->db ->query("select id from product_salerep where markup_id = $id order by id ASC");
				foreach($re-> result_array() as $row){
					$this ->db ->query("delete from product_case where salerep_id = ".$row['id']);
				}
				$this ->db ->query("delete from product_salerep where markup_id = $id");
				if(isset($_POST['salerepPost']) && is_array($_POST['salerepPost'])){
					foreach($_POST['salerepPost'] as $salerep){
						if(is_array($salerep) && count($salerep) > 0){
							$this ->db ->insert('product_salerep', array('markup_id'=>$id));
							$salerep_id = $this ->db ->insert_id();
							foreach($salerep as $commission){
								if(!is_numeric($commission)) $commission = 0;
								$this ->db ->insert('product_case', array('salerep_id'=>$salerep_id, 'commission'=>$commission));	
							}
						}	
					}	
				}	
			}
		}
		return array('error' => $error);
	}//end edit_saveObj function
	
	public function delete_obj()
	{
		if(isset($_POST['key'])){
			$this ->db->update("product_markup", array('status'=> -1), "mkey = '".$_POST['key']."'");	
		}
		return $this ->loadObj();
	}//end delete_obj function
	
	public function view_loadValue()
	{
		$caid = (isset($_GET['caid'])&&is_numeric($_GET['caid']))?$_GET['caid']:0;
		$mid = (isset($_GET['mid'])&&is_numeric($_GET['mid']))?$_GET['mid']:0;
		$pkey = (isset($_GET['ItemID']))?$_GET['ItemID']:'';
		$ptype = (isset($_GET['ptype']))?$_GET['ptype']:'';
		
		$key = '';
		$category = 0;
		$manufacturers = 0;
		$markup_percentage = 0;
		$commission_charities = 0;
		$commission_employees_bonus = 0;
		$commission_trust_charity = 0;
		$commission_member = 0;
		$credit_merchant = CREDIT_MERCHANT_DEF;
		$commission_affiliate = 0;
		$description = '';
		$id = 0;
		
		$table = 'items_markup';
		$catid = 0;
		if($ptype == 'ads'){
			$table = 'ad_items_markup';
			$catid = 1;
		}
		
		$re = $this ->db->query("select product_markup.* from ".$table." join product_markup on product_markup.mkey = ".$table.".mkey where ".$table.".pkey = '$pkey' and product_markup.status <> -1");
		if($re-> num_rows()>0){
			$row = $re ->row_array();
			$key = $row['mkey'];
			$id = $row['id'];
			$markup_percentage = $row['markup_percentage'];
			$commission_charities = $row['commission_charities'];
			$commission_employees_bonus = $row['commission_employees_bonus'];
			$commission_trust_charity = $row['commission_trust_charity'];
			$credit_merchant = $row['credit_merchant'];
			$commission_member = $row['commission_member'];	
			$commission_affiliate = $row['commission_affiliate'];			
		}
		
		$sale_rep_setting = $this ->system ->get_general_setting();
		$Number_of_level = (isset($sale_rep_setting['Number_of_level']) && is_numeric($sale_rep_setting['Number_of_level']))?$sale_rep_setting['Number_of_level']:0;
		
		$commission = array();
		$datalevel = array();
		$re_2 = $this ->db ->query("select commission from commission_salerep_items where item_key = '$pkey' and catid = $catid");
		if($re_2 ->num_rows() >0){
			$row_2 = $re_2 ->row_array();
			if($row_2['commission'] != '' && $row_2['commission'] != null) $commission = explode("|", $row_2['commission']);	
		}
		for($i = 0; $i < $Number_of_level; $i++){
			$level_value = (isset($commission[$i]) && is_numeric($commission[$i]) && $commission[$i] >= 0)?$commission[$i]:0;
			$datalevel[] = array(
				'level' =>"Level ".($i+1),
				'level_value' =>$level_value
			);
		}
		return array(
			'key' => $key,
			'markup_percentage' => $markup_percentage,
			'commission_member' => $commission_member,
			'commission_affiliate' => $commission_affiliate,
			'commission_charities' => $commission_charities,
			'commission_employees_bonus' => $commission_employees_bonus,
			'credit_merchant' => $credit_merchant,
			'commission_trust_charity' => $commission_trust_charity,
			'datalevel' => $datalevel,
		);
	}//end view_loadValue function

}//end markup_model class