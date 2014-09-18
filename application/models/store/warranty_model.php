<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Warranty_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function loadData(){
        $arrUsers = array();
		$modify = 'no';
		$del = 'no';
        
        if($this->author->isAccessPerm('warranty','edit')) $modify = 'yes';
        if($this->author->isAccessPerm('warranty','del')) $del = 'yes';
        
        $re = $this->db->query("select * from warranty WHERE status <> -1 order by date_update DESC");
		foreach($re->result_array() as $row){
			$row['date_update_str'] = date("m/d/Y", $row['date_update']);
			$row['date_create_str'] = date("m/d/Y", $row['date_create']);
			
			$row['duration_str'] = $this->loadDuration($row['duration']*$row['duration_type']);
			
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$arrUsers[] = $row;
		}
		return $arrUsers;
    }
    
    public function loadDuration($duration){
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
		if($str == '') $str = 'No warranty';
		return $str;	
    }
    
    public function saveObj(){
        $error = '';
	
	$key = $this->lib->GeneralRandomKey(20);
	$re = $this->db->query("select id from warranty where wkey = '$key'");
	foreach($re->result_array() as $row){
		$key = $this->lib->GeneralRandomKey(20);
		$re = $this->db->query("select id from warranty where wkey = '$key'");
	}
	$duration = 0;
	if(isset($_POST['duration']) && is_numeric(trim($_POST['duration']))){
		$duration = trim($_POST['duration']);	
	}
	$duration_type = 1;
	if(isset($_POST['duration_type']) && is_numeric(trim($_POST['duration_type']))){
		$duration_type = trim($_POST['duration_type']);	
	}
	$cost = 0;
	if(isset($_POST['cost']) && is_numeric(trim($_POST['cost']))){
		$cost = trim($_POST['cost']);	
	}	
	$datas = array(
		'wkey' => $key,
		'label' => $this->lib->escape($this->input->post('label')),
		'duration' => $duration,
		'duration_type' => $duration_type,
		'cost' => $cost,
		'date_create' => time(),
		'date_update' => time()
		
	);
	if($error == ''){
		$id = $this->db->insert('warranty', $datas);
		if(is_numeric($id) || $id <= 0){
			$error = _error_cannot_insert_db_;	
		}	
	}
	return array('error' => $error);
    }
    
    public function delete_obj($key){
        if(isset($key)){
		$this->db->update("warranty", array('status'=> -1), "wkey = '".$key."'");	
	}
	return $this->loadData();
    }
    
    public function loadValue($str){
	$key = $str;
	$label = '';
	$duration = 0;
	$duration_type = 1;
	$cost = 0;
        
		$re = $this->db->query("select * from warranty where wkey = '$key'");
		foreach($re->result_array() as $row){
			$label = $row['label'];
			$duration = $row['duration'];
			$duration_type = $row['duration_type'];
			$cost = $row['cost'];	
		}
	$str = array();
        $str['@key@'] = $key;
        $str['@label@'] = $label;
        $str['@duration@'] = $duration;
	
	$days = '';
	$months = '';
	$years = '';
	if($duration_type == 1) $days = 'selected="selected"';
	elseif($duration_type == 30) $months = 'selected="selected"';
	elseif($duration_type == 365) $years = 'selected="selected"';
	
        $str['@days@'] = $days;
        $str['@months@'] = $months;
        $str['@years@'] = $years;
        $str['@cost@'] = $cost;
        
        return $str;
	
    }
    
    public function saveEdit(){
        $error = '';
		$key = (isset($_POST['key']))?$_POST['key']:'';
		$duration = 0;
		if(isset($_POST['duration']) && is_numeric(trim($_POST['duration']))){
			$duration = trim($_POST['duration']);	
		}
		$duration_type = 1;
		if(isset($_POST['duration_type']) && is_numeric(trim($_POST['duration_type']))){
			$duration_type = trim($_POST['duration_type']);	
		}
		
		$cost = 0;
		if(isset($_POST['cost']) && is_numeric(trim($_POST['cost']))){
			$cost = trim($_POST['cost']);	
		}
			
		$datas = array(
			'label' => $this->lib->escape($this->input->post('label')),
			'duration' => $duration,
			'duration_type' => $duration_type,
			'cost' => $cost,
			'date_update' => time()
			
		);
		if($error == ''){
			$this->db->update('warranty', $datas, "wkey = '$key'");
		}
		return array('error' => $error);
    }
	
	public function view_loadValue()
	{
		$warranties = array();
		$caid = (isset($_GET['caid'])&&is_numeric($_GET['caid']))?$_GET['caid']:0;	
		$re = $this ->db->query("select * from warranty WHERE status <> -1 order by date_update DESC");
		foreach($re-> result_array() as $row)
		{
			$id = $row['id'];
			$warranties[]= array(
				'warranty_name' =>$row['label'],
				'key' =>$row['wkey'],
				'duration' =>$this ->loadDuration($row['duration']*$row['duration_type']),
				'cost' =>number_format($row['cost'], 2)
			);
		}
		return $warranties;
	}//end view_loadValue function
}
