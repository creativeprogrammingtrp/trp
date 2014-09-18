<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Attributes_model extends CI_Model{
    public function __construct() {
        parent::__construct();
		$this->load->library("lib");
    }
    
    public function loadData(){
        $arrUsers = array();
		$modify = 'no';
		$options = 'no';
		$del = 'no';
		if($this->author->isAccessPerm('attributes','edit')) $modify = 'yes';
		if($this->author->isAccessPerm('attributes','del')) $del = 'yes';
		
		$display_type = $this->lib->GetSystemValues('Display type');

		$re = $this->db->query("select * from attributes WHERE status <> -1 and attri_type = 1 order by weight DESC, name ASC");
		foreach($re->result_array() as $row){
			
			$row['display_type_str'] = $display_type[$row['display_type']];
			$re_1 = $this->db->query("select count(*) as count_op from attrioptions where akey = '".$row['akey']."' and status <> -1");
			foreach($re_1->result_array() as $result) {
				$row['numOfoptions'] = $result['count_op'];
			}
			if(count($re_1->result_array())) {
				$options = 'yes';
			}
			$row['del'] = $del;
			$row['options'] = $options;
			$row['modify'] = $modify;	
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}
	public function loadValue($str){
		$name = '';
		$label = '';
		$required = '';
		$display_type_value = 0;
		$weight = 0;
		$akey = $str;
		$re = $this->db->query("select * from attributes where akey = '$akey'");
		foreach($re->result_array() as $row){
			$name = $row['name'];
			$label = $row['label'];
			if($row['required'] == 1)
				$required = 'checked="checked"';
			$weight = $row['weight'];
			$display_type_value = $row['display_type'];	
		}
		
		$str = array();
		$str['@required@'] = $required;
        $str['@akey@'] = $akey;
		$str['@name@'] = $name;
        $str['@label@'] = $label;
		$str['@display_type@'] = $display_type_value;
		$str['@weight@']	= $this->loadWeight($weight);
		$display_type = $this->lib->OutputSelectBox($this->lib->GetSystemValues('Display type'), 'display_type', 'display_type', 'style="WIDTH: 150px"', $display_type_value);
		$str['@display_type@']	= $display_type;
		
		return $str;
    }
	
	public function loadWeight($weight){
		$str = '<select id="weight" style="width:80px">';
		for($i = 50; $i > -51; $i--){
			$select = '';
			if($i == $weight) $select = 'selected="selected"';
			$str .= '<option value="'.$i.'" '.$select.'>'.$i.'</option>';	
		}
		$str .= '</select>';
		return $str;				
	}
	
	public function saveEdit() {
		$error = '';
		$akey = (isset($_POST['akey']))?$_POST['akey']:'';
		$display_type = 1;
		if(isset($_POST['display_type']) && is_numeric(trim($_POST['display_type']))){
			$display_type = trim($_POST['display_type']);	
		}
		
		$weight = 0;
		if(isset($_POST['weight']) && is_numeric(trim($_POST['weight']))){
			$cost = trim($_POST['weight']);	
		}
		if(isset($_POST['required'])){
			$required = $_POST['required'];	
		}
			
		$datas = array(
			'name' => $this->lib->escape($this->input->post('name')),
			'label' => $this->lib->escape($this->input->post('label')),
			'display_type' => $display_type,
			'weight' => $weight,
			'required' => $required
			
		);
		if($error == ''){
			$this->db->update('attributes', $datas, "akey = '$akey'");
			
		}
		return array('error' => $error);
	}
	
	public function add_loadWeight($weight=0) {
		$str = '<select id="weight" style="width:80px">';
		for($i = 50; $i > -51; $i--){
			$select = '';
			if($i == $weight) $select = 'selected="selected"';
			$str .= '<option value="'.$i.'" '.$select.'>'.$i.'</option>';	
		}
		$str .= '</select>';
		return $str;	
	}
	public function add_loadValue() {
		$str['@weight@']	= $this->add_loadWeight();
		$display_type = $this->lib->OutputSelectBox($this->lib->GetSystemValues('Display type'), 'display_type', 'display_type', 'style="WIDTH: 150px"', 1);
		$str['@display_type@']	= $display_type;
		
		return $str;
	}
	
	public function saveObj(){
        $error = '';
	
		$key = $this->lib->GeneralRandomKey(20);
		$re = $this->db->query("select name from attributes where akey = '$key'");
		foreach($re->result_array() as $row){
			$key = $this->lib->GeneralRandomKey(20);
			$re = $this->db->query("select name from attributes where akey = '$key'");
		}
		$data['akey'] = $key;
		if(isset($_POST['name']) && trim($_POST['name']) != ''){
			$data['name'] = $this->lib->escape($_POST['name']);	
		}
		
		if(isset($_POST['label']) && trim($_POST['label']) != ''){
			$data['label'] = $this->lib->escape($_POST['label']);	
		}
		
		if(isset($_POST['required'])){
			$data['required'] = $_POST['required'];	
		}
		
		if(isset($_POST['display_type'])){
			$data['display_type'] = $_POST['display_type'];	
		}
		
		if(isset($_POST['weight'])){
			$data['weight'] = $_POST['weight'];	
		}
		
		if($error == ''){
			$id = $this->db->insert('attributes', $data);
			if(is_numeric($id) || $id <= 0){
				$error = _error_cannot_insert_db_;	
			}	
		}
		return array('error' => $error);
    }
	
	public function delete_obj($key){
        if(isset($key)){
			$this->db->update("attributes", array('status'=> -1), "akey = '".$key."'");	
		}
		return $this->loadData();
    }
	
	public function saveDatas(){
		if(isset($_POST['datas']) && is_array($_POST['datas']) && count($_POST['datas']) > 0){
			$datas = $_POST['datas'];
			$re = $this->db->query("select * from attributes WHERE status <> -1");
			foreach($re->result_array() as $row){
				for($i = 0; $i < count($datas); $i++){
					if($datas[$i]['akey'] == $row['akey']){
						$akey = $row['akey'];
						$this->db->update("attributes", array('weight' => -$i), "akey = '$akey'");
						break;	
					}	
				}
			}	
		}
		return $this->loadData();
	}
	
	public function loadOptions($key) {
		$str = array();
		$modify = 'no';
		$del = 'no';
		if($this->author->isAccessPerm('attributes','editOption')) $modify = 'yes';
		if($this->author->isAccessPerm('attributes','delOption')) $del = 'yes';
		$re = $this->db->query("select * from attrioptions WHERE status <> -1 and akey = '$key' order by weight DESC, name ASC");
		foreach($re->result_array() as $row) {	
			$row['modify'] = $modify;
			$row['del'] = $del;
			$str[count($str)] = $row; 
		}
		return $str;
	}
	public function loadValueOption($str){
		$name = '';
		$cost = '0.00';
		$price = '0.00';
		$weight = 0;
		$okey = '';
		$okey = $str;
		$re = $this->db->query("select * from attrioptions where okey = '$okey'");
		foreach($re->result_array() as $row){
			$name = $row['name'];
			$cost = $row['cost'];
			$price = $row['price'];
			$weight = $row['weight'];
		}	
		$str = array();
		$str['@okey@'] = $okey;
		$str['@name@'] = $name;
		$str['@cost@'] = $cost;
		$str['@price@']	= $price;
		$str['@weight@'] = $this->loadWeight($weight);
		return $str;	
	}
	public function delete_obj_option($key,$akey){
        if(isset($key)){
			$this->db->update("attrioptions", array('status'=> -1), "okey = '".$key."'");	
		}
		return $this->loadOptions($akey);
    }
	public function saveOption() {
		$error = '';
		$data = array();
		
		$key = $this->lib->GeneralRandomKey(20);
		$re = $this->db->query("select name from attrioptions where okey = '$key'");
		foreach($re->result_array() as $row){
			$key = $this->lib->GeneralRandomKey(20);
			$re = $this->db->query("select name from attrioptions where okey = '$key'");
		}
		
		$data['okey'] = $key;
		
		if(isset($_POST['akey']) && trim($_POST['akey']) != ''){
			$data['akey'] = $this->lib->escape($_POST['akey']);	
		}
		
		if(isset($_POST['name']) && trim($_POST['name']) != ''){
			$data['name'] = $this->lib->escape($_POST['name']);	
		}
		
		$cost = 0;
		if(isset($_POST['cost']) && is_numeric($_POST['cost']) && $_POST['cost'] > 0) $cost = $_POST['cost'];
		$data['cost'] = $cost;
		
		$price = 0;
		if(isset($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) $price = $_POST['price'];
		$data['price'] = $price;
		
		if(isset($_POST['weight'])){
			$data['weight'] = $_POST['weight'];	
		}
		if($error == ''){
			$id = $this->db->insert('attrioptions', $data);
			if(is_numeric($id) || $id <= 0){
				$error = _error_cannot_insert_db_;	
			}	
		}
		return array('error' => $error);
	}
	public function saveEditOption() {
		$error = '';
		if(isset($_POST['okey']) && $_POST['okey'] != ''){
			$data = array();
			$okey =  $_POST['okey'];
			if(isset($_POST['name']) && trim($_POST['name']) != ''){
				$data['name'] = $this->lib->escape($_POST['name']);	
			}
			
			$cost = 0;
			if(isset($_POST['cost']) && is_numeric($_POST['cost']) && $_POST['cost'] > 0) $cost = $_POST['cost'];
			$data['cost'] = $cost;
			
			$price = 0;
			if(isset($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) $price = $_POST['price'];
			$data['price'] = $price;
			
			if(isset($_POST['weight'])){
				$data['weight'] = $_POST['weight'];	
			}
			if($error == ''){
				$this->db->update('attrioptions', $data, "okey = '$okey'");
			}
		}
		return array('error' => $error);
	}
	public function saveformOption() {
		$akey = $_POST['akey'];
		if(isset($_POST['datas']) && is_array($_POST['datas']) && count($_POST['datas']) > 0){
			$datas = $_POST['datas'];
			$re = $this->db->query("select * from attrioptions WHERE status <> -1");
			foreach($re->result_array() as $row){
				for($i = 0; $i < count($datas); $i++){
					if($datas[$i]['okey'] == $row['okey']){
						$okey = $row['okey'];
						$this->db->update('attrioptions', array('weight' => -$i), "okey = '$okey'");
						break;	
					}	
				}
			}	
		}
		return $this->loadOptions($akey);
	}
}
