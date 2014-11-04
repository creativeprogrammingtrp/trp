<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    

   
    function showCustomerList(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select c.*, m.company_name from new_applicent c, master_ero m  where c.uid = m.uid AND  c.uid = '" . $this->author->objlogin->uid . "' ORDER BY c.first_name ASC";
            }else{
                $sql = "select c.*, m.company_name from new_applicent c, master_ero m  where c.uid = m.uid AND  c.author_id = '" . $this->author->objlogin->uid . "' ORDER BY c.first_name ASC";
            }
    	}else{
    		$sql = "select c.*, m.company_name from new_applicent c, master_ero m  where c.uid = m.uid ORDER BY c.first_name ASC";
    	}
    	
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["create_date"]);
    		$data[] = $row;
    	}
    	return $data;
    }
    
    
    function showCustomerListByEro(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	/*if($this->author->objlogin->uid != '1'){
    		$sql = "select * from new_applicent ORDER BY first_name ASC";
    	}else{*/
    		$sql = "select c.*, m.company_name from new_applicent c, master_ero m  where c.uid = m.uid AND  c.uid = '" . $_GET['empid'] . "' ORDER BY c.first_name ASC";
    	//}
    	 
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["create_date"]);
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function loadDetailsAboutSelectedCustomer(){
    	$data = array();
    	$data_i = array();
    	$data_b = array();
    	$dataAll = array();
    	
    	$customerId = $this->lib->escape($_POST['cid']);
    	
    	//$sql = "select na.*, a.* from new_app na, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND a.applicent_id = '".$customerId."' AND app_from = 'newApplication' GROUP BY a.applicent_id ORDER BY app_id DESC";
    	if($this->author->objlogin->uid != '1'){
    		$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.applicent_id = '".$customerId."' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	}else{
    		$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.applicent_id = '".$customerId."' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	}
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND app_from = 'newApplication' AND u.uid = na.author_id  ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    	$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    
    		// get benefits info 
    		$sql_1 = "select * from  benefits  where app_id = '".$row["app_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		if(sizeof($res_1->result_array()) > 0){
	    		foreach ($res_1->result_array() as $row1) {
	    			$row['benefits'][] =  $row1;
	    		}
    		}else{
    			$row['benefits'][] =  array();
    		}
    		
    		
    		// get prodcuct for Insurance
    		$sql_4 = "select * from  insurance  where app_id = '".$row["app_id"]."'";
    		$res_4 = $this->db->query($sql_4);
    		
    		if(sizeof($res_4->result_array()) > 0){
    			foreach ($res_4->result_array() as $row4) {
    				//$row4["product_create_date"] = gmdate("F j, Y, g:i a", $row4["create_date"]);
    				
    				// get additional information for Insurance
    				$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where i.insurance_id = a.insurance_id AND a.aplicent_id = na.applicent_id AND a.insurance_id = '".$row4["insurance_id"]."'";
    				$res_3 = $this->db->query($sql_3);
    				
    				//$row['app'] = $res_2->result_array();
    				if(sizeof($res_3->result_array()) > 0){
    					foreach ($res_3->result_array() as $row3) {
    						$row4['i_additional'][] =  $row3;
    					}
    				}else{
    					$row4['i_additional'] =  array();
    				}
    				
    				$row['insurance'][] =  $row4;
    			}
    		}else{
    			$row['insurance'] =  array();
    		}
    		
    		$data[] = $row;
    	}
    	
    	
    	// for benefits
    	
    	//$sql2 = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND app_from = 'benefits'  AND u.uid = na.author_id  AND a.applicent_id = '".$customerId."' GROUP BY a.applicent_id ORDER BY na.app_id DESC";
    	$sql_b = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a where b.applicent_id = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.applicent_id = '".$customerId."' AND b.app_id IS NULL ORDER BY b.benefits_id DESC";
    	
    	$res_b = $this->db->query($sql_b);
    	foreach ($res_b->result_array() as $row_b) {
    		$row_b["format_date"] = gmdate("m/d/y", strtotime($row_b["create_date1"]));
    		
    		// Get Grouped Applicent Info if selected more then one
    		$sql_b_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row_b["benefits_id"]."'";
    		$res_b_1 = $this->db->query($sql_b_1);
    		
    		//$row['app'] = $res_1->result_array();
    		if(sizeof($res_b_1->result_array()) > 0){
    			foreach ($res_b_1->result_array() as $row_b1) {
    				$row_b['applicents'][] =  $row_b1;
    			}
    		}else{
    			$row_b['applicents'] =  array();
    		}
    		
    		
    	// get Notes for Benefits
    		$sql_b_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row_b["benefits_id"]."'  AND note_from = 'benefits'";
    			$res_b_2 = $this->db->query($sql_b_2);
    			
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_b_2->result_array()) > 0){
    				foreach ($res_b_2->result_array() as $row_b2) {
    					$row_b2["note_create_date"] = gmdate("F j, Y, g:i a", $row_b2["create_date"]);
    					$row_b['notes'][] =  $row_b2;
    				}
    			}else{
    				$row_b['notes'] =  array();
    			}
    		
    		$data_b[] = $row_b;
    	}
    	
    	
    	// for insurance
    	
    	 $sql_i = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a  where i.applicent_id = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.applicent_id = '".$customerId."'  AND i.app_id IS NULL ORDER BY i.insurance_id DESC";
    	$res_i = $this->db->query($sql_i);
    	foreach ($res_i->result_array() as $row_i) {
    		$row_i["format_date"] = gmdate("m/d/y", strtotime($row_i["create_date1"]));
    		
    		// Get Grouped Applicent Info if selected more then one
    		
    		$sql_i_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row_i["insurance_id"]."'";
    		$res_i_1 = $this->db->query($sql_i_1);
    		
    			if(sizeof($res_i_1->result_array()) > 0){
		    		foreach ($res_i_1->result_array() as $row_i1) {
		    			$row_i['applicents'][] =  $row_i1;
		    		}
    			}else{
    				$row_i['applicents'] =  array();
    			}
    			
    			// get Notes for Insurance
    			$sql_i_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, insurance i, users u  where u.uid = n.create_by AND i.insurance_id = n.new_app_id AND n.new_app_id = '".$row_i["insurance_id"]."'  AND note_from = 'insurance'";
    			$res_i_2 = $this->db->query($sql_i_2);
    			
    			if(sizeof($res_i_2->result_array()) > 0){
    				foreach ($res_i_2->result_array() as $row_i2) {
    					$row_i2["note_create_date"] = gmdate("F j, Y, g:i a", $row_i2["create_date"]);
    					$row_i['notes'][] =  $row_i2;
    				}
    			}else{
    				$row_i['notes'] =  array();
    			}	
    			
    			// get additional information for Insurance
    			$sql_i_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND a.insurance_id = '".$row_i["insurance_id"]."'";
    			$res_i_3 = $this->db->query($sql_i_3);
    			
    			if(sizeof($res_i_3->result_array()) > 0){
    				foreach ($res_i_3->result_array() as $row_i3) {
    					$row_i['i_additional'][] =  $row_i3;
    				}
    			}else{
    				$row_i['i_additional'] =  array();
    			}
    			
    		$data_i[] = $row_i;
    	}
    	
    	//return array_merge(array_merge($data3,$data1),$data2);
    	//return $data3;
    	$dataAll = array(
    			'allapplication' => $data,
    			'allbenefits' => $data_b,
    			'allinsurance' => $data_i
    	);
    	 
    	//print_r($data);
    	return $dataAll;
    }
    
    
    
    public function updateCustomerInfo() {
    
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
    	$this->db->where('applicent_id', $this->lib->escape($_POST['customer_id']));
    	$this->db->update('new_applicent', $data);
    
    	 
    	return $this->showCustomerList();
    }
    

}

?>
