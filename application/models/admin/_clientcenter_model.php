<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clientcenter_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function loadBankingData() {
        $data = array();
        $query = $this->db->query("select * from master_ero  where uid = '" . $this->author->objlogin->uid . "'");
        
        if($query->num_rows() > 0){
        	$data_load = $query->row_array();
        }
        
        if(is_array($data_load) && count($data_load)){
        	$data['tax_preparation_fee'] = $data_load['tax_preparation_fee'];
        	$data['bank_transmission_fee'] = $data_load['bank_transmission_fee'];
        	$data['sb_fee'] = $data_load['sb_fee'];
        	//$data['e_file_fee'] = $data_load['e_file_fee'];
        	$data['add_on_fee'] = $data_load['add_on_fee'];
        }
        return $data;
    }

 function saveNewApplicationInfo() {
    	
    
    	$data = array(
    			'uid' => $this->author->objlogin->uid,
    			'first_name' => $this->lib->escape($_POST['first_name']),
    			'last_name' => $this->lib->escape($_POST['last_name']),
    			'ss_number' => $this->lib->escape($_POST['ss_number']),
    			'dob' => $this->lib->escape($_POST['dob']),
    			'add_spouse' => $this->lib->escape($_POST['add_spouse']),
    			'sp_first_name' => $this->lib->escape($_POST['sp_first_name']),
    			'sp_last_name' => $this->lib->escape($_POST['sp_last_name']),
    			'sp_ssn_no' => $this->lib->escape($_POST['sp_ss_number']),
    			'sp_dob' => $this->lib->escape($_POST['sp_dob']),
    			
    			'street_address_1' => $this->lib->escape($_POST['street_address']),
    			/*'street_address_2' => $this->lib->escape($_POST['street_address2']),*/
    			
    			'city' => $this->lib->escape($_POST['city']),
    			'state' => $this->lib->escape($_POST['state']),
    			'zip_code' => $this->lib->escape($_POST['zip_code']),
    			/*'country' => $this->lib->escape($_POST['country']),*/
    			'cell_phone' => $this->lib->escape($_POST['cell_phone']),
    			'email_add' => $this->lib->escape($_POST['email_add']),
    			
    			'app_total_refund_amt' => $this->lib->escape($_POST['total_refund_amt_3']),
    			'app_tax_preparation_fee' => $this->lib->escape($_POST['tax_preparation_fee_3']),
    			'app_bank_transmission_fee' => $this->lib->escape($_POST['bank_transmission_fee_3']),
    			'app_sb_fee' => $this->lib->escape($_POST['sb_fee_3']),
    			//'app_e_file_fee' => $this->lib->escape($_POST['e_file_fee_3']),
    			'app_add_on_fee' => $this->lib->escape($_POST['add_on_fee_3']),
    			'app_total_fees'  => $this->lib->escape($_POST['total_fees']),
    			'app_refund_amt' => $this->lib->escape($_POST['net_refund_amt_3']),
    			'app_benefit' => $this->lib->escape($_POST['total_benefit']),
    			'app_net_refund_amt' => $this->lib->escape($_POST['final_net_refund']),
    				
    			'payment_method' => $this->lib->escape($_POST['payment_method']),
    			'create_date' => $this->lib->getTimeGMT(),
    			
    			'card_number' => $this->lib->escape($_POST['card_number']),
    			'app_bank_name' => $this->lib->escape($_POST['bank_name']),
    			'app_account_no' => $this->lib->escape($_POST['acount_number']),
    			'app_routing_no' => $this->lib->escape($_POST['routing_number']),
    			
    			'assign_acc_no' => '4234432342334534',
    			'assign_routing_no' => '4232343433',
    			'status' => '0',
    			'payment_status' => '2'
    			
    	);
    	$this->db->insert("new_app", $data);
    	$lastAppId = $this->db->insert_id();
    	
    	$inputSeletecItems1 = $this->input->post('inputSeletecItems'); //$this->lib->escape($_POST['inputSeletecItems']);
    	//echo $inputSeletecItems;
    	$inputSeletecItems = substr($inputSeletecItems1,1);
    	//echo $inputSeletecItems;
    	//echo "<br>";
    	$items = explode(",",$inputSeletecItems);
    	//print_r($items);
    	//exit;
    	foreach ($items as $itm){
    		$indItm = explode("~",$itm);
    		
    		/*if($indItm[0] != 'Banking'){
    		$data1 = array(
	    			'app_id' => $lastAppId,
	    			'uid' => $this->author->objlogin->uid,
    				'prodcut_name' => $indItm[0],
    				'short_desc' => $indItm[1],
    				'img_source' => $indItm[2],
    				'price' => $indItm[3],
    		);
    		}else{*/
    			$data1 = array(
    					'app_id' => $lastAppId,
    					'uid' => $this->author->objlogin->uid,
    					'prodcut_name' => $indItm[0],
    					'short_desc' => $indItm[1],
    					'img_source' => $indItm[2],
    					'price' => $indItm[3],
    					'create_date' => $this->lib->getTimeGMT()
    			);
    //	}
    		
    		$this->db->insert("new_app_product", $data1);
    	}
    	
    	return true;
    }
    
    function loadRecentApplication(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	//AND create_date = '".$todate."'
    	$sql = "select * from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '0' ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/Y", $row["create_date"]);
            
            $sql_1 = "select * from new_app_product  where uid = '" . $this->author->objlogin->uid . "' AND app_id = '".$row["app_id"]."'";
            $res_1 = $this->db->query($sql_1);
            
            //$row['prodcuts'] = $res_1->result_array();
            foreach ($res_1->result_array() as $row1) { 
	            if ($row1['img_source'] !== '') {
	            	$row1['img_source'] = '<img  src="' . $this->system->URL_server__() . 'application/views/TRP/backend/img/' . $row1['img_source'] . '">';
	            } else {
	            	$row1['img_source'] = '';
	            }
	            $row['prodcuts'][] =  $row1;
            }
          //  http://localhost/trpplus/application/views/TRP/backend/img/Client_Center_chart.jpg
            $data[] = $row;
        }
        return $data;
    	
    	
    }
    
    function loadPendingFundsApplication(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select * from new_app  where uid = '" . $this->author->objlogin->uid . "' AND create_date < '".$todate."' AND status = '0' ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/Y", $row["create_date"]);
    
    		$sql_1 = "select * from new_app_product  where uid = '" . $this->author->objlogin->uid . "' AND app_id = '".$row["app_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		//$row['prodcuts'] = $res_1->result_array();
    		foreach ($res_1->result_array() as $row1) {
    			if ($row1['img_source'] !== '') {
    				$row1['img_source'] = '<img  src="' . $this->system->URL_server__() . 'application/views/TRP/backend/img/' . $row1['img_source'] . '">';
    			} else {
    				$row1['img_source'] = '';
    			}
    			$row['prodcuts'][] =  $row1;
    		}
    		//  http://localhost/trpplus/application/views/TRP/backend/img/Client_Center_chart.jpg
    		$data[] = $row;
    	}
    	return $data;
    	 
    	 
    }
    
    function loadReadyToPrintApplication(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select * from new_app  where uid = '" . $this->author->objlogin->uid . "' AND create_date < '".$todate."' AND status = '1' ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/Y", $row["create_date"]);
    
    		$sql_1 = "select * from new_app_product  where uid = '" . $this->author->objlogin->uid . "' AND app_id = '".$row["app_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		//$row['prodcuts'] = $res_1->result_array();
    		foreach ($res_1->result_array() as $row1) {
    			if ($row1['img_source'] !== '') {
    				$row1['img_source'] = '<img  src="' . $this->system->URL_server__() . 'application/views/TRP/backend/img/' . $row1['img_source'] . '">';
    			} else {
    				$row1['img_source'] = '';
    			}
    			$row['prodcuts'][] =  $row1;
    		}
    		//  http://localhost/trpplus/application/views/TRP/backend/img/Client_Center_chart.jpg
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function loadAllApplication(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select * from new_app  where uid = '" . $this->author->objlogin->uid . "' ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/Y", $row["create_date"]);
    
    		$sql_1 = "select * from new_app_product  where uid = '" . $this->author->objlogin->uid . "' AND app_id = '".$row["app_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		//$row['prodcuts'] = $res_1->result_array();
    		foreach ($res_1->result_array() as $row1) {
    			if ($row1['img_source'] !== '') {
    				$row1['img_source'] = '<img  src="' . $this->system->URL_server__() . 'application/views/TRP/backend/img/' . $row1['img_source'] . '">';
    			} else {
    				$row1['img_source'] = '';
    			}
    			$row['prodcuts'][] =  $row1;
    		}
    		//  http://localhost/trpplus/application/views/TRP/backend/img/Client_Center_chart.jpg
    		$data[] = $row;
    	}
    	return $data;
    }
    
    
    
    function loadSelectedReadyToPrintApplication($ids){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select * from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '1' AND app_id in ($ids) ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    
    	//$data[] = $res->result_array();
    	
    	foreach ($res->result_array() as $row) {
    		$data[] = $row;
    	}
   // print_r($data);
    
    	return $data;
    
    
    }
    
   
    public function updateApplicationInfo() {
    	
    	$data = array(
    			'first_name' => $this->lib->escape($_POST['first_name']),
    			'last_name' => $this->lib->escape($_POST['last_name']),
    			'ss_number' => $this->lib->escape($_POST['ss_number']),
    			'dob' => $this->lib->escape($_POST['dob']),
    			'sp_first_name' => $this->lib->escape($_POST['sp_first_name']),
    			'sp_last_name' => $this->lib->escape($_POST['sp_last_name']),
    			'sp_ssn_no' => $this->lib->escape($_POST['sp_ss_number']),
    			'sp_dob' => $this->lib->escape($_POST['sp_dob']),
    			
    			'street_address_1' => $this->lib->escape($_POST['street_address']),
    			'city' => $this->lib->escape($_POST['city']),
    			'state' => $this->lib->escape($_POST['state']),
    			'zip_code' => $this->lib->escape($_POST['zip_code']),
    			'cell_phone' => $this->lib->escape($_POST['cell_phone']),
    			'email_add' => $this->lib->escape($_POST['email_add']),
    	);
    	$this->db->where('app_id', $this->lib->escape($_POST['application_id']));
    	$this->db->update('new_app', $data);
    	
    	/*if ($_POST['option'] == 'allero') {
    		return $this->loadAllEros();
    	} else if ($_POST['option'] == 'pendingero') {
    		return $this->loadEro();
    	} else {
    		return $this->loadApprovedEro();
    	}*/
    	
    	return $this->loadRecentApplication();
    }

}

?>
