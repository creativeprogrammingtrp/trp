<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reporting_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    function loadBankProductFundedReport() {
    	$data = array();
    	//$todate = date('Y-m-d');
    	//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);
    	
    	$before30 = date('Y-m-d', strtotime('-29 days'));
    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    	 
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	if($this->author->objlogin->uid != '1'){
    	 $sql = "SELECT 
    			a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
    			
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id    			
    			AND a.uid = '" . $this->author->objlogin->uid . "'
    			AND a.posted_date != ''
    			AND DATE(FROM_UNIXTIME(a.posted_date)) between '$before30_date' AND '$todate_date'
    			ORDER BY a.posted_date DESC
    	"; 
    	}else{
    		$sql = "SELECT
    			a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
    
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			
    			AND a.posted_date != ''
    AND DATE(FROM_UNIXTIME(a.posted_date)) between '$before30_date' AND '$todate_date'
    			ORDER BY a.posted_date DESC
    	";
    	}
    	//AND DATE(FROM_UNIXTIME(a.posted_date)) between '$before30_date' AND '$todate_date'
    	//AND a.posted_date != ''
    			//AND DATE(FROM_UNIXTIME(a.posted_date)) between '$before30_date' AND '$todate_date'
    	$res = $this->db->query($sql);
    	
    	foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function loadBankProductFundedReportDatePicker() {
    	$data = array();
    	//$todate = date('Y-m-d');
    	//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);
    	
    	$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    	 
    	$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	if($this->author->objlogin->uid != '1'){
    	$sql = "SELECT
    		a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
    
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			AND a.uid = '" . $this->author->objlogin->uid . "'
    			AND a.posted_date != ''
    			AND DATE(FROM_UNIXTIME(a.posted_date)) between '$before30_date' AND '$todate_date'
    			ORDER BY a.posted_date DESC
    	";
    	}
    	else{
    		$sql = "SELECT
    		a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
    		
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    		    			AND a.posted_date != ''
    		    			AND DATE(FROM_UNIXTIME(a.posted_date)) between '$before30_date' AND '$todate_date'
    		    			ORDER BY a.posted_date DESC
    		    			";
    	}
    	$res = $this->db->query($sql);
    	 
    	foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function loadBankProductUnfundedReport() {
    	$data = array();
    	
    	//$todate = date('Y-m-d');
    	//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);

    	
    	$before30 = date('Y-m-d', strtotime('-29 days'));
    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    	
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	if($this->author->objlogin->uid != '1'){
    	
     			$sql = "SELECT
    			a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
    
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			AND a.uid = '" . $this->author->objlogin->uid."'
    			AND a.create_date != ''
    			AND a.posted_date IS NULL
    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    			ORDER BY a.create_date DESC";
    	}else{
    			$sql = "SELECT
    			a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
    		
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			AND a.create_date != ''
    			AND a.posted_date IS NULL
    		    AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    		    ORDER BY a.create_date DESC";
    	}
    	//
    	$res = $this->db->query($sql);
    	 
    	foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    		
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function loadBankProductUnfundedReportDatePicker() {
    	$data = array();
    	 
    	//$todate = date('Y-m-d');
    	//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);
    
    	
    	$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    	
    	$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	 
    	 
    	if($this->author->objlogin->uid != '1'){
	    	 $sql = "SELECT
	    	a.app_id,
	    	FROM_UNIXTIME(a.create_date) AS create_date1,
	    	a.*,
	    	FROM_UNIXTIME(a.posted_date) AS posted_date1,
	    	c.*
	    
	    	FROM new_app a, new_applicent c
	    	where c.applicent_id = a.applicent_id
	    	AND a.uid = '" . $this->author->objlogin->uid . "'
	    	AND a.create_date != ''
	    	AND a.posted_date IS NULL
	    	AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
	    	ORDER BY a.create_date DESC
	    	";
    	}else{
    		$sql = "SELECT
	    	a.app_id,
	    	FROM_UNIXTIME(a.create_date) AS create_date1,
	    	a.*,
	    	FROM_UNIXTIME(a.posted_date) AS posted_date1,
	    	c.*
	    	FROM new_app a, new_applicent c
	    	where c.applicent_id = a.applicent_id
    		AND a.create_date != ''
    		AND a.posted_date IS NULL
    		AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    		ORDER BY a.create_date DESC";
    	}
	    	 
    	$res = $this->db->query($sql);
    
    	foreach ($res->result_array() as $row) {
	    	$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
	    	$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
	    			$data[] = $row;
    	}
    					return $data;
    	}
    
    	function loadBankProductByCustomrReport() {
    		$data = array();
    		 
    		//$todate = date('Y-m-d');
    		//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);
    	
    		 
    		$before30 = date('Y-m-d', strtotime('-29 days'));
    		$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    		 
    		$todate = date('Y-m-d');
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    		 
    		if($this->author->objlogin->uid != '1'){
    		 
    		$sql = "SELECT
    		a.*,
    		c.*,
    		FROM_UNIXTIME(a.create_date) AS create_date1,
    		FROM_UNIXTIME(a.posted_date) AS posted_date1,
    		u.name, u.firstname, u.lastname
    		FROM new_app a, new_applicent c, users u
    		where c.applicent_id = a.applicent_id
    		AND a.uid = '" . $this->author->objlogin->uid . "'
    		AND u.uid = a.author_id
    		AND a.create_date != ''
    		AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    		ORDER BY c.first_name ASC";
    		}else{
    		$sql = "SELECT
    		a.*,
    		c.*,
    		FROM_UNIXTIME(a.create_date) AS create_date1,
    		FROM_UNIXTIME(a.posted_date) AS posted_date1,
    		u.name, u.firstname, u.lastname
    		FROM new_app a, new_applicent c, users u
    		where c.applicent_id = a.applicent_id
    		AND u.uid = a.author_id
    		AND a.create_date != ''
    		AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    		ORDER BY c.first_name ASC";
    		}
    	//AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '2014-08-17'
    		$res = $this->db->query($sql);
    	
    		foreach ($res->result_array() as $row) {
	    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
	    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
	    		
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
    		return $data;
    	}
    	
    	
    	
    	function loadBankProductByCustomrReportDatePicker() {
    		$data = array();
    	
    		//$todate = date('Y-m-d');
    		//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);
    	
    		 
    		$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
    		$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    		 
    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	
    		if($this->author->objlogin->uid != '1'){
    		
    			$sql = "SELECT
	    		a.*,
	    		c.*,
	    		FROM_UNIXTIME(a.create_date) AS create_date1,
	    		FROM_UNIXTIME(a.posted_date) AS posted_date1,
	    		u.name, u.firstname, u.lastname
	    	
	    		FROM new_app a, new_applicent c, users u
	    		where c.applicent_id = a.applicent_id
	    		AND a.uid = '" . $this->author->objlogin->uid . "'
	    		AND u.uid = a.author_id
	    		AND a.create_date != ''
	    		AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
	    		ORDER BY c.first_name ASC
	    		";
    		}else{
    			$sql = "SELECT
	    		a.*,
	    		c.*,
	    		FROM_UNIXTIME(a.create_date) AS create_date1,
	    		FROM_UNIXTIME(a.posted_date) AS posted_date1,
	    		u.name, u.firstname, u.lastname
	    		FROM new_app a, new_applicent c, users u
	    		where c.applicent_id = a.applicent_id
				AND u.uid = a.author_id
    			AND a.create_date != ''
    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    			ORDER BY c.first_name ASC";
    		}
    	
    		$res = $this->db->query($sql);
    	
    		foreach ($res->result_array() as $row) {
	    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
	    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
	    		
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
    		return $data;
    	}
    	
    	
    	
    	
    	function loadBankProductByEmployeeReport() {
    		$data = array();
    		 
    		//$todate = date('Y-m-d');
    		//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);
    		 
    		 
    		$before30 = date('Y-m-d', strtotime('-29 days'));
    		$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    		 
    		$todate = date('Y-m-d');
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    		 
    		 
    		if($this->author->objlogin->uid != '1'){
    			$sql = "SELECT
    			a.app_id,
    			a.author_id as employee_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.create_date,
    			sum(a.app_tax_preparation_fee) as app_tax_preparation_fee,
    			sum(a.app_sb_fee) as app_sb_fee,
    			sum(a.app_bank_transmission_fee) as app_bank_transmission_fee,
    			sum(a.app_add_on_fee) as app_add_on_fee,
    			sum(a.audit_guard_fee) as audit_guard_fee,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			a.posted_date,
    			sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee,
    			sum(a.app_actual_bank_transmission_fee) as app_actual_bank_transmission_fee,
    			sum(a.app_actual_sb_fee) as app_actual_sb_fee,
    			sum(a.app_actual_add_on_fee) as app_actual_add_on_fee,
    			sum(a.actual_audit_guard_fee) as actual_audit_guard_fee,
    			sum(a.app_refund_amt) as app_refund_amt,
    			a.payment_method,
    			a.payment_status,
    			u.uid,
    			u.firstname,
    			u.lastname
    			 
    			FROM new_app a, users u, ero e
    			where a.uid = e.uid
    			AND   e.author = a.author_id
    			AND  u.uid = e.author
    			
    			AND a.create_date != ''
    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    			GROUP BY a.author_id
    			ORDER BY u.firstname ASC
    			";
    		}else{
    			
	    	    $sql = "SELECT
	    		a.app_id,
	    		a.author_id as employee_id, 
	    		FROM_UNIXTIME(a.create_date) AS create_date1,
	    		a.create_date,
	    		sum(a.app_tax_preparation_fee) as app_tax_preparation_fee,
	    		sum(a.app_sb_fee) as app_sb_fee,
	    		sum(a.app_bank_transmission_fee) as app_bank_transmission_fee,
	    		sum(a.app_add_on_fee) as app_add_on_fee,
	    		sum(a.audit_guard_fee) as audit_guard_fee,
	    		FROM_UNIXTIME(a.posted_date) AS posted_date1,
	    		a.posted_date,
	    		sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee,
	    		sum(a.app_actual_bank_transmission_fee) as app_actual_bank_transmission_fee,
	    		sum(a.app_actual_sb_fee) as app_actual_sb_fee,
	    		sum(a.app_actual_add_on_fee) as app_actual_add_on_fee,
	    		sum(a.actual_audit_guard_fee) as actual_audit_guard_fee,
	    		sum(a.app_refund_amt) as app_refund_amt,
	    		a.payment_method,
	    		a.payment_status,
	    		u.uid,
	    		u.firstname,
	    		u.lastname
	    		 
	    		FROM new_app a, users u
	    		where u.uid = a.author_id
	    		AND a.create_date != ''
	    		AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
	    		GROUP BY a.author_id
	    		ORDER BY u.firstname ASC
	    		";
    		}
    		$res = $this->db->query($sql);
    		 
	    		foreach ($res->result_array() as $row) {
		    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
		    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
	    			$data[] = $row;
	    		}
    		return $data;
    		}
    		
    		function loadBankProductByEmployeeReportDatePicker() {
    			$data = array();
    			 
    			$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
	    		$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
	    		 
	    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
	    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate))); 
    			 
	    		if($this->author->objlogin->uid != '1'){
    			$sql = "SELECT
    			a.app_id,
    			a.author_id as employee_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.create_date,
    			sum(a.app_tax_preparation_fee) as app_tax_preparation_fee,
    			sum(a.app_sb_fee) as app_sb_fee,
    			sum(a.app_bank_transmission_fee) as app_bank_transmission_fee,
    			sum(a.app_add_on_fee) as app_add_on_fee,
    			sum(a.audit_guard_fee) as audit_guard_fee,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			a.posted_date,
    			sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee,
    			sum(a.app_actual_bank_transmission_fee) as app_actual_bank_transmission_fee,
    			sum(a.app_actual_sb_fee) as app_actual_sb_fee,
    			sum(a.app_actual_add_on_fee) as app_actual_add_on_fee,
    			sum(a.actual_audit_guard_fee) as actual_audit_guard_fee,
    			sum(a.app_refund_amt) as app_refund_amt,
    			a.payment_method,
    			a.payment_status,
    			u.uid,
    			u.firstname,
    			u.lastname
    		
    			 
    			FROM new_app a, users u, ero e
    			where a.uid = e.uid
    			AND   e.author = a.author_id
    			AND  u.uid = e.author
    			
    			AND a.create_date != ''
    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    			GROUP BY a.author_id
    			ORDER BY u.firstname ASC
    			";
	    		}else{
	    			$sql = "SELECT
	    			a.app_id,
	    			a.author_id as employee_id,
	    			FROM_UNIXTIME(a.create_date) AS create_date1,
	    			a.create_date,
	    			sum(a.app_tax_preparation_fee) as app_tax_preparation_fee,
	    			sum(a.app_sb_fee) as app_sb_fee,
	    			sum(a.app_bank_transmission_fee) as app_bank_transmission_fee,
	    			sum(a.app_add_on_fee) as app_add_on_fee,
	    			sum(a.audit_guard_fee) as audit_guard_fee,
	    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
	    			a.posted_date,
	    			sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee,
	    			sum(a.app_actual_bank_transmission_fee) as app_actual_bank_transmission_fee,
	    			sum(a.app_actual_sb_fee) as app_actual_sb_fee,
	    			sum(a.app_actual_add_on_fee) as app_actual_add_on_fee,
	    			sum(a.actual_audit_guard_fee) as actual_audit_guard_fee,
	    			sum(a.app_refund_amt) as app_refund_amt,
	    			a.payment_method,
	    			a.payment_status,
	    			u.uid,
	    			u.firstname,
	    			u.lastname
	    			
	    			
	    			FROM new_app a, users u
	    			where u.uid = a.author_id
	    			AND a.create_date != ''
	    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
	    			GROUP BY a.author_id
	    			ORDER BY u.firstname ASC
	    			";
	    		}
    			 
    			$res = $this->db->query($sql);
    			 
    			foreach ($res->result_array() as $row) {
    				$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    				$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    				$data[] = $row;
    			}
    			return $data;
    	}		
    	
    	
    	
    	function loadBankProductByEmployeeCustomerReport() {
    		$data = array();
    		 
    		$employeeId = $_GET['empid'];
    		 
    		if($this->author->objlogin->uid != '1'){
    		$sql = "SELECT
    		a.*,
    		c.*,
    		FROM_UNIXTIME(a.create_date) AS create_date1,
    		FROM_UNIXTIME(a.posted_date) AS posted_date1,
    		
    		u.uid, u.firstname, u.lastname
    	
    		FROM new_app a, new_applicent c, users u
    		where c.applicent_id = a.applicent_id
    		AND a.uid = '" . $this->author->objlogin->uid . "'
    		AND u.uid = a.author_id
    		AND a.create_date != ''
    		AND a.uid = '$employeeId'
    		ORDER BY c.first_name ASC    		
    		";
    		}
    		else{
    			$sql = "SELECT
    		a.*,
    		c.*,
    		FROM_UNIXTIME(a.create_date) AS create_date1,
    		FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			
    		u.uid, u.firstname, u.lastname
   
    		FROM new_app a, new_applicent c, users u
    		where c.applicent_id = a.applicent_id
    		AND u.uid = a.author_id
    		AND a.create_date != ''
    		AND a.uid = '$employeeId'
    		ORDER BY c.first_name ASC
    		";
    		}
    		$res = $this->db->query($sql);
    	
    		foreach ($res->result_array() as $row) {
	    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
	    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
	    		
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
    		return $data;
    	}
    		
    		
    
    function loadBankProductRevenueReport31231() {
    	$data = array();
    	 
    	if($this->author->objlogin->uid != '1'){
    	$sql = "SELECT
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.create_date,
    			sum(a.app_tax_preparation_fee) as app_tax_preparation_fee,
    			sum(a.app_sb_fee) as app_sb_fee,
    			sum(a.app_bank_transmission_fee) as app_bank_transmission_fee,
    			sum(a.app_add_on_fee) as app_add_on_fee,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			a.posted_date,
    			sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee,
    			sum(a.app_actual_bank_transmission_fee) as app_actual_bank_transmission_fee,
    			sum(a.app_actual_sb_fee) as app_actual_sb_fee,
    			sum(a.app_actual_add_on_fee) as app_actual_add_on_fee,
    
    			c.first_name,
    			c.last_name,
    			c.ss_number,
    			FROM new_app where c.applicent_id = a.applicent_id
    			group by DATE(create_date1), DATE(posted_date1)";
    	}else{
    		$sql = "SELECT
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.create_date,
    			sum(a.app_tax_preparation_fee) as app_tax_preparation_fee,
    			sum(a.app_sb_fee) as app_sb_fee,
    			sum(a.app_bank_transmission_fee) as app_bank_transmission_fee,
    			sum(a.app_add_on_fee) as app_add_on_fee,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			a.posted_date,
    			sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee,
    			sum(a.app_actual_bank_transmission_fee) as app_actual_bank_transmission_fee,
    			sum(a.app_actual_sb_fee) as app_actual_sb_fee,
    			sum(a.app_actual_add_on_fee) as app_actual_add_on_fee,
    		
    			c.first_name,
    			c.last_name,
    			c.ss_number,
    			FROM new_app where c.applicent_id = a.applicent_id
    			group by DATE(create_date1), DATE(posted_date1)";
    	}
    	//group by app_id";
    	//group by DATE(create_date1), DATE(posted_date1)";
    	$res = $this->db->query($sql);
    	 
    	foreach ($res->result_array() as $row) {
    	$row["create_date"] = gmdate("m/d/y", $row["create_date"]);
    		$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    		$data[] = $row;
    	}
    	return $data;
    }
    	
    	public function updateApplicationInfoFromReportPage() {
    	
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
    		$this->db->where('applicent_id', $this->lib->escape($_POST['application_id']));
    		$this->db->update('new_applicent', $data);
    	
    		/*if ($_POST['option'] == 'allero') {
    		 return $this->loadAllEros();
    		} else if ($_POST['option'] == 'pendingero') {
    		return $this->loadEro();
    		} else {
    		return $this->loadApprovedEro();
    		}*/
    		$app_type = $this->lib->escape($_POST['app_type']);
    		$app_type_d= $this->lib->escape($_POST['app_type_d']);
    		
    		/*if ($app_type_d == 'dashboard'){return $this->loadLastFiveRecentApplication();}
    		elseif($app_type == 0){
    			return $this->loadPendingFundsApplication();
    		}
    		elseif ($app_type == 1){return $this->loadReadyToPrintApplication();}
    		elseif ($app_type == 2){return $this->loadPrintedApplication();}
    		elseif ($app_type == 3){return $this->loadVoidedApplication();}
    		//elseif ($app_type == 4){return $this->loadAllApplication();}
    		else{
    			return $this->loadAllApplication();
    		}
    	*/
    	
    		//return $this->loadRecentApplication();
    	}
    	
    	
    	function loadDiscountBenefitsRevenueReport() {
    		$data = array();
    		 
    		//$todate = date('Y-m-d');
    		//$before30 = date('Y-m-d', strtotime('-29 days')); //date('Y-m-d',strtotime($todate)+30);
    	
    		 
    		$before30 = date('Y-m-d', strtotime('-29 days'));
    		$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    		 
    		$todate = date('Y-m-d');
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    		 
    		 
    		 
    		/*$sql = "SELECT
    		a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
    	
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			AND a.uid = '" . $this->author->objlogin->uid."'
    	    			AND (app_from = 'benefits' || benefits_item != '') 
    	    			AND a.create_date != ''
    	    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    	    			ORDER BY a.create_date DESC
    	    			";
    		*/
    		if($this->author->objlogin->uid != '1'){
    			$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    				AND b.author_id = '" . $this->author->objlogin->uid . "'
    			    				AND b.create_date != ''
    			    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    			    				ORDER BY b.create_date DESC";
    			
    		}else{
    		$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from 
    				benefits b, new_applicent a 
    				where b.applicent_id = a.applicent_id 
    				AND b.create_date != ''
    	    		AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    	    		ORDER BY b.create_date DESC";
    		}
    		//
    		$res = $this->db->query($sql);
    	
    		foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    		
	    		// Get Grouped Applicent Info if selected more then one
	    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
	    		$res_1 = $this->db->query($sql_1);
	    		
	    		if(sizeof($res_1->result_array()) > 0){
	    			foreach ($res_1->result_array() as $row1) {
	    				$row['applicents'][] =  $row1;
	    			}
	    		}else{
	    			$row['applicents'] =  array();
	    		}
    		
    		
    			// get Notes for Benefits
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
    			$res_2 = $this->db->query($sql_2);
    			
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}
    		
    		$data[] = $row;
    		}
    		return $data;
    	}
    	
    	
    	
    	function loadDiscountBenefitsRevenueReportDatePicker() {
    		$data = array();
    		 
    		$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
	    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
	    		 
    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    		 
    		/*$sql = "SELECT
    		a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
   
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			AND a.uid = '" . $this->author->objlogin->uid."'
    	    			AND (app_from = 'benefits' || benefits_item != '')
    	    			AND a.create_date != ''
    	    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    	    			ORDER BY a.create_date DESC
    	    			";
    		*/
    		if($this->author->objlogin->uid != '1'){
    			$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    				AND b.uid = '" . $this->author->objlogin->uid . "'
    		    				AND b.create_date != ''
    		    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    		    				ORDER BY b.create_date DESC";
    		}else{
    			$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    			    				AND b.create_date != ''
    			    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    			    				ORDER BY b.create_date DESC";
    		}
    		//
    		$res = $this->db->query($sql);
    		 
    		foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    	
    			// Get Grouped Applicent Info if selected more then one
	    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
	    		$res_1 = $this->db->query($sql_1);
	    		
	    		if(sizeof($res_1->result_array()) > 0){
	    			foreach ($res_1->result_array() as $row1) {
	    				$row['applicents'][] =  $row1;
	    			}
	    		}else{
	    			$row['applicents'] =  array();
	    		}
    		
    		
    			// get Notes for Benefits
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
    			$res_2 = $this->db->query($sql_2);
    			
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}
    	
    		$data[] = $row;
    		}
    				return $data;
    }
    
    
    
    function loadDiscountBenefitsBenefitsSoldReport() {
    	$data = array();
    
    	$before30 = date('Y-m-d', strtotime('-29 days'));
    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    	 
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	if($this->author->objlogin->uid != '1'){
    		$sql = "SELECT DATE(FROM_UNIXTIME(create_date)) as create_date1, create_date, 
    			FROM_UNIXTIME(create_date) as create_date2,
				SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN 1 ELSE 0 END) AS medical,
				SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN 1 ELSE 0 END) AS lifestyle,
				SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN 1 ELSE 0 END) AS combination
				from benefits
        			WHERE create_date != ''
        			AND b.uid = '" . $this->author->objlogin->uid . "'
        			AND DATE(FROM_UNIXTIME(create_date)) between '$before30_date' AND '$todate_date'
        			GROUP BY DATE(FROM_UNIXTIME(create_date))
        			ORDER BY DATE(FROM_UNIXTIME(create_date)) DESC
        			";
    	}else{
    		$sql = "SELECT DATE(FROM_UNIXTIME(create_date)) as create_date1, create_date,
    		FROM_UNIXTIME(create_date) as create_date2,
    		SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN 1 ELSE 0 END) AS medical,
    		SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN 1 ELSE 0 END) AS lifestyle,
    		SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN 1 ELSE 0 END) AS combination
    		from benefits
    		WHERE create_date != ''
    		AND DATE(FROM_UNIXTIME(create_date)) between '$before30_date' AND '$todate_date'
    		GROUP BY DATE(FROM_UNIXTIME(create_date))
    		ORDER BY DATE(FROM_UNIXTIME(create_date)) DESC
    		";
    	}
        			//
    	$res = $this->db->query($sql);
    	 
	    	foreach ($res->result_array() as $row) {
		    	//$row["create_date"] = gmdate("m/d/y", $row["create_date"]);
		    	$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date2"]));
		    	 $data[] = $row;
	    	}
    				return $data;
    	}
    	
    	function loadDiscountBenefitsBenefitsSoldReportDatePicker() {
    		$data = array();
    	
    		$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
	    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
	    		 
    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    		if($this->author->objlogin->uid != '1'){
    		$sql = "SELECT DATE(FROM_UNIXTIME(create_date)) as create_date1, create_date,
    		FROM_UNIXTIME(create_date) as create_date2,
    		SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN 1 ELSE 0 END) AS medical,
    		SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN 1 ELSE 0 END) AS lifestyle,
    		SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN 1 ELSE 0 END) AS combination
    		from benefits
    		WHERE create_date != ''
    		AND b.uid = '" . $this->author->objlogin->uid . "'
    		AND DATE(FROM_UNIXTIME(create_date)) between '$before30_date' AND '$todate_date'
    		GROUP BY DATE(FROM_UNIXTIME(create_date))
    		ORDER BY DATE(FROM_UNIXTIME(create_date)) DESC
    		";
    		}else{
    			$sql = "SELECT DATE(FROM_UNIXTIME(create_date)) as create_date1, create_date,
    			FROM_UNIXTIME(create_date) as create_date2,
    			SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN 1 ELSE 0 END) AS medical,
    			SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN 1 ELSE 0 END) AS lifestyle,
    			SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN 1 ELSE 0 END) AS combination
    			from benefits
    			WHERE create_date != ''
    			AND DATE(FROM_UNIXTIME(create_date)) between '$before30_date' AND '$todate_date'
    			GROUP BY DATE(FROM_UNIXTIME(create_date))
    			ORDER BY DATE(FROM_UNIXTIME(create_date)) DESC
    			";
    		}
    		//
    		$res = $this->db->query($sql);
    	
    		foreach ($res->result_array() as $row) {
    		//$row["app_create_date"] = gmdate("m/d/y", $row["create_date"]);
    			$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date2"]));
    			$data[] = $row;
    		}
    		return $data;
    	}
    	
    	
    	
    	
    	function loadDiscountBenefitsCustomerReport() {
    		$data = array();
    		
    		$before30 = date('Y-m-d', strtotime('-29 days'));
    		$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    		 
    		$todate = date('Y-m-d');
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    		 /*
    		$sql = "SELECT
    		a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
   
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			AND a.uid = '" . $this->author->objlogin->uid."'
    	    			AND (app_from = 'benefits' || benefits_item != '')
    	    			AND a.create_date != ''
    	    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    	    			ORDER BY c.first_name ASC
    	    			";
    		*/
    		if($this->author->objlogin->uid != '1'){
    			$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    				AND b.author_id = '" . $this->author->objlogin->uid . "'
    		    				AND b.create_date != ''
    		    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    		    				ORDER BY b.create_date DESC";
    		}else{
    			$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    			    				AND b.create_date != ''
    			    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    			    				ORDER BY b.create_date DESC";
    			
    		}
    		//
    		$res = $this->db->query($sql);
    		 
    		foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    	
    		// Get Grouped Applicent Info if selected more then one
	    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
	    		$res_1 = $this->db->query($sql_1);
	    		
	    		if(sizeof($res_1->result_array()) > 0){
	    			foreach ($res_1->result_array() as $row1) {
	    				$row['applicents'][] =  $row1;
	    			}
	    		}else{
	    			$row['applicents'] =  array();
	    		}
    		
    		
    			// get Notes for Benefits
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
    			$res_2 = $this->db->query($sql_2);
    			
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}
    	
    		$data[] = $row;
    		}
    				return $data;
    	}
    	
    	
    	
    	function loadDiscountBenefitsCustomerReportDatePicker() {
    		$data = array();
    	
    		$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
	    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
	    		 
    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    		if($this->author->objlogin->uid != '1'){
    		$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    				AND b.author_id = '" . $this->author->objlogin->uid . "'
    		    				AND b.create_date != ''
    		    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    		    				ORDER BY b.create_date DESC";
    		}else{
    			$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    				
    			    				AND b.create_date != ''
    			    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    			    				ORDER BY b.create_date DESC";
    			
    		}
    		//
    		$res = $this->db->query($sql);
    		 
    		foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
   
    			// Get Grouped Applicent Info if selected more then one
	    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
	    		$res_1 = $this->db->query($sql_1);
	    		
	    		if(sizeof($res_1->result_array()) > 0){
	    			foreach ($res_1->result_array() as $row1) {
	    				$row['applicents'][] =  $row1;
	    			}
	    		}else{
	    			$row['applicents'] =  array();
	    		}
    		
    		
    			// get Notes for Benefits
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
    			$res_2 = $this->db->query($sql_2);
    			
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}
    		
    			$data[] = $row;
    		}
    		return $data;
    		}
    		
    		
    		function loadDiscountBenefitsEmployeeReport() {
    			$data = array();
    		
    			$before30 = date('Y-m-d', strtotime('-29 days'));
    			$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    		
    			$todate = date('Y-m-d');
    			$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    			if($this->author->objlogin->uid != '1'){
    			$sql = "SELECT 
    			FROM_UNIXTIME(b.create_date) as create_date1, b.create_date,
    			SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN benefits_price ELSE 0 END) AS medical,
    			SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN benefits_price ELSE 0 END) AS lifestyle,
    			SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN benefits_price ELSE 0 END) AS combination,
    			u.uid,
    			u.firstname,
    			u.lastname
    			FROM benefits b, users u, ero e
    			where a.uid = e.uid
    			AND   e.author = a.author_id
    			AND  u.uid = e.author
    			
    			AND b.create_date != ''
    			AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    			GROUP BY b.author_id
    			ORDER BY u.firstname ASC
    			";
    			}else {
    				$sql = "SELECT
    				FROM_UNIXTIME(b.create_date) as create_date1, b.create_date,
    				SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN benefits_price ELSE 0 END) AS medical,
    				SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN benefits_price ELSE 0 END) AS lifestyle,
    				SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN benefits_price ELSE 0 END) AS combination,
    				u.uid,
    				u.firstname,
    				u.lastname
    				FROM benefits b, users u
    				where u.uid = b.author_id
    				AND b.create_date != ''
    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    				GROUP BY b.author_id
    				ORDER BY u.firstname ASC
    				";
    			}
    			//
    				$res = $this->db->query($sql);
    		
	    			foreach ($res->result_array() as $row) {
	    				$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
	    				$data[] = $row;
	    			}
    				return $data;
    		}
    			
    			
    			function loadDiscountBenefitsEmployeeReportDatePicker() {
    				$data = array();
    			
    				$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
			    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
			    		 
		    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
		    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
		    		if($this->author->objlogin->uid != '1'){
    				$sql = "SELECT
    				FROM_UNIXTIME(b.create_date) as create_date1, b.create_date,
    				SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN benefits_price ELSE 0 END) AS medical,
    				SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN benefits_price ELSE 0 END) AS lifestyle,
    				SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN benefits_price ELSE 0 END) AS combination,
    				u.uid,
    				u.firstname,
    				u.lastname
    				FROM benefits b, users u, ero e
	    			where a.uid = e.uid
	    			AND   e.author = a.author_id
	    			AND  u.uid = e.author
	    			
    				AND b.create_date != ''
    				AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
    				GROUP BY b.author_id
    				ORDER BY u.firstname ASC
    				";
		    		}else{
		    			$sql = "SELECT
		    			FROM_UNIXTIME(b.create_date) as create_date1, b.create_date,
		    			SUM(CASE WHEN `benefits_item` = 'Medical Package' THEN benefits_price ELSE 0 END) AS medical,
		    			SUM(CASE WHEN `benefits_item` = 'Lifestyle Package' THEN benefits_price ELSE 0 END) AS lifestyle,
		    			SUM(CASE WHEN `benefits_item` = 'Combination Package' THEN benefits_price ELSE 0 END) AS combination,
		    			u.uid,
		    			u.firstname,
		    			u.lastname
		    			FROM benefits b, users u
		    			where u.uid = b.author_id
		    			AND b.create_date != ''
		    			AND DATE(FROM_UNIXTIME(b.create_date)) between '$before30_date' AND '$todate_date'
		    			GROUP BY b.author_id
		    			ORDER BY u.firstname ASC
		    			";
		    		}
    				//
    				$res = $this->db->query($sql);
    			
    				foreach ($res->result_array() as $row) {
    					$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    					$data[] = $row;
    				}
    				return $data;
    			}
    			 
    			
    			
    			
    			function loadwDiscountBenefitsEmployeeCustomerReport() {
    				$data = array();
    		
    				$employeeId = $_GET['empid'];
    				
    				/*$sql = "SELECT
    		a.app_id,
    			FROM_UNIXTIME(a.create_date) AS create_date1,
    			a.*,
    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    			c.*
  
    			FROM new_app a, new_applicent c
    			where c.applicent_id = a.applicent_id
    			AND a.author_id = '$employeeId'
    			    			AND (app_from = 'benefits' || benefits_item != '')
    			    			ORDER BY c.first_name ASC
    			    			";*/
    				if($this->author->objlogin->uid != '1'){
    				$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    				AND b.author_id = '".$employeeId."'
    				    				AND b.create_date != ''
    				    				ORDER BY a.first_name ASC";
    				}else {
    					$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from
    				benefits b, new_applicent a
    				where b.applicent_id = a.applicent_id
    				AND b.author_id = '".$employeeId."'
    				    				AND b.create_date != ''
    				    				ORDER BY a.first_name ASC";
    					
    				}
    			    			////AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    				$res = $this->db->query($sql);
    				 
    				foreach ($res->result_array() as $row) {
    					$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    					//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
   
    					// Get Grouped Applicent Info if selected more then one
			    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
			    		$res_1 = $this->db->query($sql_1);
			    		
			    		if(sizeof($res_1->result_array()) > 0){
			    			foreach ($res_1->result_array() as $row1) {
			    				$row['applicents'][] =  $row1;
			    			}
			    		}else{
			    			$row['applicents'] =  array();
			    		}
		    		
		    		
		    			// get Notes for Benefits
		    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
		    			$res_2 = $this->db->query($sql_2);
		    			
		    			if(sizeof($res_2->result_array()) > 0){
		    				foreach ($res_2->result_array() as $row2) {
		    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
		    					$row['notes'][] =  $row2;
		    				}
		    			}else{
		    				$row['notes'] =  array();
		    			}
    				
    					$data[] = $row;
    				}
    				return $data;
    			}
    				
    				
    			
    				function loadDiscountBenefitsSoldCustomerReport() {
    					$data = array();
    				
    					$activedate = $_GET['activedate'];
    					
    				/*
    					$sql = "SELECT
    					a.app_id,
    					DATE(FROM_UNIXTIME(a.create_date)) AS create_date1,
    					FROM_UNIXTIME(a.create_date) AS create_date2,
    					a.*,
    					FROM_UNIXTIME(a.posted_date) AS posted_date1,
    					c.*
    				
    					FROM new_app a, new_applicent c
    					where c.applicent_id = a.applicent_id
    					AND DATE(FROM_UNIXTIME(a.create_date)) = '$activedate'
    					AND (app_from = 'benefits' || benefits_item != '')
    					ORDER BY c.first_name ASC
    					";
    					*/
    					if($this->author->objlogin->uid != '1'){
	    					$sql = "select b.*, a.*, DATE(FROM_UNIXTIME(b.create_date)) as  create_date1, FROM_UNIXTIME(b.create_date) as  create_date2 from
		    				benefits b, new_applicent a
		    				where b.applicent_id = a.applicent_id
		    				AND DATE(FROM_UNIXTIME(b.create_date)) = '$activedate'
		    				ORDER BY a.first_name ASC";
    					}else{
    						$sql = "select b.*, a.*, DATE(FROM_UNIXTIME(b.create_date)) as  create_date1, FROM_UNIXTIME(b.create_date) as  create_date2 from
    						benefits b, new_applicent a
    						where b.applicent_id = a.applicent_id
    						AND DATE(FROM_UNIXTIME(b.create_date)) = '$activedate'
    						ORDER BY a.first_name ASC";
    					}
    					////AND a.author_id = '$employeeId'
    					////AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    					$res = $this->db->query($sql);
    						
    					foreach ($res->result_array() as $row) {
    					$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date2"]));
    					//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    					 
    					// Get Grouped Applicent Info if selected more then one
    					$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
    					$res_1 = $this->db->query($sql_1);
    					 
    					if(sizeof($res_1->result_array()) > 0){
    						foreach ($res_1->result_array() as $row1) {
    							$row['applicents'][] =  $row1;
    						}
    					}else{
    						$row['applicents'] =  array();
    					}
    					
    					
    					// get Notes for Benefits
    					$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
    					$res_2 = $this->db->query($sql_2);
    					 
    					if(sizeof($res_2->result_array()) > 0){
    						foreach ($res_2->result_array() as $row2) {
    							$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    							$row['notes'][] =  $row2;
    						}
    					}else{
    						$row['notes'] =  array();
    					}
    				
    				   $data[] = $row;
    				  }
    				 return $data;
    			}
    				
    				    				
    			function loadInsurancesRevenueReport() {
    				$data = array();
    				 
    				$before30 = date('Y-m-d', strtotime('-29 days'));
    				$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    				 
    				$todate = date('Y-m-d');
    				$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    				 
    				 
    				 /*
    				 $sql = "SELECT
			    			a.app_id,
			    			FROM_UNIXTIME(a.create_date) AS create_date1,
			    			a.*,
			    			FROM_UNIXTIME(a.posted_date) AS posted_date1,
    						c.*
			    			FROM new_app a
    						INNER JOIN  new_applicent c ON c.applicent_id = a.applicent_id
    						where 
			    			a.uid = '" . $this->author->objlogin->uid."'
			    			    			AND a.create_date != ''
			    			    			AND (app_from = 'insurance' || insurance_item != '')
			    			    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
			    			    			ORDER BY a.create_date DESC
			    			    			";*/
			    			    			//
    				if($this->author->objlogin->uid != '1'){
    				 		$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  
    				 				from insurance i, new_applicent a  
    				 				where i.applicent_id = a.applicent_id 
    				 				AND i.author_id = '" . $this->author->objlogin->uid . "'
    				 				AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    				 				ORDER BY i.create_date DESC";
    				}else{
    					$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
    				 				from insurance i, new_applicent a
    				 				where i.applicent_id = a.applicent_id
    					    		AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    					    		ORDER BY i.create_date DESC";
    				}
    				 		$res = $this->db->query($sql);
			    				 
			    			foreach ($res->result_array() as $row) {
			    				$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
			    				//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
			    			
			    			// Get Grouped Applicent Info if selected more then one
    		
				    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
				    		$res_1 = $this->db->query($sql_1);
				    		
				    		//$row['app'] = $res_1->result_array();
				    			if(sizeof($res_1->result_array()) > 0){
						    		foreach ($res_1->result_array() as $row1) {
						    			$row['applicents'][] =  $row1;
						    		}
				    			}else{
				    				$row['applicents'] =  array();
				    			}
				    			
				    			// get Notes for Insurance
				    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, insurance i, users u  where u.uid = n.create_by AND i.insurance_id = n.new_app_id AND n.new_app_id = '".$row["insurance_id"]."'  AND note_from = 'insurance'";
				    			$res_2 = $this->db->query($sql_2);
				    			
				    			//$row['app'] = $res_2->result_array();
				    			if(sizeof($res_2->result_array()) > 0){
				    				foreach ($res_2->result_array() as $row2) {
				    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
				    					$row['notes'][] =  $row2;
				    				}
				    			}else{
				    				$row['notes'] =  array();
				    			}	
				    			
				    			// get additional information for Insurance
				    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND a.insurance_id = '".$row["insurance_id"]."'";
				    			$res_3 = $this->db->query($sql_3);
				    			 
				    			//$row['app'] = $res_2->result_array();
				    			if(sizeof($res_3->result_array()) > 0){
				    				foreach ($res_3->result_array() as $row3) {
				    					$row['i_additional'][] =  $row3;
				    				}
				    			}else{
				    				$row['i_additional'] =  array();
				    			}
			    				
			    				
			    				$data[] = $row;
			    		}
    						return $data;
    				}
    				
    				
    				function loadInsurancesRevenueReportDatePicker() {
    					$data = array();
    						
    					$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
				    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
				    		 
			    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
			    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));	
    						
			    		if($this->author->objlogin->uid != '1'){	
    					$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  
    				 				from insurance i, new_applicent a  
    				 				where i.applicent_id = a.applicent_id 
    				 				AND i.author_id = '" . $this->author->objlogin->uid . "'
    				 				AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    				 				ORDER BY i.create_date DESC";
			    		}else{
			    			$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
    				 				from insurance i, new_applicent a
    				 				where i.applicent_id = a.applicent_id
			    			    	AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
			    			    	ORDER BY i.create_date DESC";
			    		}	
    							    			//
    						$res = $this->db->query($sql);
    				
    						foreach ($res->result_array() as $row) {
    						$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    						//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    				
    						// Get Grouped Applicent Info if selected more then one
    		
    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    			if(sizeof($res_1->result_array()) > 0){
		    		foreach ($res_1->result_array() as $row1) {
		    			$row['applicents'][] =  $row1;
		    		}
    			}else{
    				$row['applicents'] =  array();
    			}
    			
    			// get Notes for Insurance
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, insurance i, users u  where u.uid = n.create_by AND i.insurance_id = n.new_app_id AND n.new_app_id = '".$row["insurance_id"]."'  AND note_from = 'insurance'";
    			$res_2 = $this->db->query($sql_2);
    			
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}	
    			
    			// get additional information for Insurance
    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND a.insurance_id = '".$row["insurance_id"]."'";
    			$res_3 = $this->db->query($sql_3);
    			 
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_3->result_array()) > 0){
    				foreach ($res_3->result_array() as $row3) {
    					$row['i_additional'][] =  $row3;
    				}
    			}else{
    				$row['i_additional'] =  array();
    			}
    						 
    						 
    				
    						$data[] = $row;
    					}
    				return $data;
    			}
    			
    			
    			function loadInsurancesInsuranceSoldReport() {
    				$data = array();
    			
    				$before30 = date('Y-m-d', strtotime('-29 days'));
    				$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    			
    				$todate = date('Y-m-d');
    				$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    			
    				if($this->author->objlogin->uid != '1'){
	    				$sql = "SELECT
	    				DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
	    				FROM_UNIXTIME(i.create_date) as create_date2,
	    				SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
	    				SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
	    				SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
	    				SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
	    				SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
	    				SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
	    				u.uid,
	    				u.firstname,
	    				u.lastname
	    				FROM  insurance i, users u
	    				where u.uid = i.author_id
	    				AND i.author_id = '" . $this->author->objlogin->uid . "'
	    				AND i.create_date != ''
	    				AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
	    				GROUP BY DATE(FROM_UNIXTIME(i.create_date))
	    				ORDER BY DATE(FROM_UNIXTIME(i.create_date)) DESC
	    				";
    				}else{
    					$sql = "SELECT
    					DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
    					FROM_UNIXTIME(i.create_date) as create_date2,
    					SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
    					SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
    					SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
    					SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
    					SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
    					SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM  insurance i, users u
    					where u.uid = i.author_id
    					AND i.create_date != ''
    					AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    					GROUP BY DATE(FROM_UNIXTIME(i.create_date))
    					ORDER BY DATE(FROM_UNIXTIME(i.create_date)) DESC
    					";
    				}
    				//
    				$res = $this->db->query($sql);
    			
    				foreach ($res->result_array() as $row) {
    				$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date2"]));
    				$data[] = $row;
    			}
    			return $data;
    			}
    			
    			
    			function loadInsurancesInsuranceSoldReportDatePicker() {
    				$data = array();
    				 
    				$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
			    	$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
			    		 
		    		$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
		    		$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    				 
		    		if($this->author->objlogin->uid != '1'){
    				$sql = "SELECT
    				DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
    				FROM_UNIXTIME(i.create_date) as create_date2,
    				SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
    				SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
    				SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
    				SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
    				SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
    				SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
    				u.uid,
    				u.firstname,
    				u.lastname
    				FROM  insurance i, users u
    				where u.uid = i.author_id
    				AND i.author_id = '" . $this->author->objlogin->uid . "'
    				AND i.create_date != ''
    				AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    				GROUP BY DATE(FROM_UNIXTIME(i.create_date))
    				ORDER BY DATE(FROM_UNIXTIME(i.create_date)) DESC
    				";
		    		}else{
		    			$sql = "SELECT
		    			DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
		    			FROM_UNIXTIME(i.create_date) as create_date2,
		    			SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
		    			SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
		    			SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
		    			SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
		    			SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
		    			SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
		    			u.uid,
		    			u.firstname,
		    			u.lastname
		    			FROM  insurance i, users u
		    			where u.uid = i.author_id
		    			AND i.create_date != ''
		    			AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
		    			GROUP BY DATE(FROM_UNIXTIME(i.create_date))
		    			ORDER BY DATE(FROM_UNIXTIME(i.create_date)) DESC
		    			";
		    		}
    				//
    				$res = $this->db->query($sql);
    				 
    				foreach ($res->result_array() as $row) {
    				$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date2"]));
    				$data[] = $row;
    				}
    			    	return $data;
    				}
    				
    				
    				function loadInsurancesCustomerReport() {
    					$data = array();
    						
    					$before30 = date('Y-m-d', strtotime('-29 days'));
    					$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    						
    					$todate = date('Y-m-d');
    					$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    					
    					if($this->author->objlogin->uid != '1'){
    						$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  
    				 				from insurance i, new_applicent a  
    				 				where i.applicent_id = a.applicent_id 
    				 				AND i.author_id = '" . $this->author->objlogin->uid . "'
    				 				AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    				 				ORDER BY i.create_date DESC";
    					}
    					else{
    						$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
    				 				from insurance i, new_applicent a
    				 				where i.applicent_id = a.applicent_id
    						    	AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    						    	ORDER BY i.create_date DESC";
    					}    			//
    						$res = $this->db->query($sql);
    				
    						foreach ($res->result_array() as $row) {
    						$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    							    			//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    				
    						// Get Grouped Applicent Info if selected more then one
    		
    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    			if(sizeof($res_1->result_array()) > 0){
		    		foreach ($res_1->result_array() as $row1) {
		    			$row['applicents'][] =  $row1;
		    		}
    			}else{
    				$row['applicents'] =  array();
    			}
    			
    			// get Notes for Insurance
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, insurance i, users u  where u.uid = n.create_by AND i.insurance_id = n.new_app_id AND n.new_app_id = '".$row["insurance_id"]."'  AND note_from = 'insurance'";
    			$res_2 = $this->db->query($sql_2);
    			
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}	
    			
    			// get additional information for Insurance
    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND a.insurance_id = '".$row["insurance_id"]."'";
    			$res_3 = $this->db->query($sql_3);
    			 
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_3->result_array()) > 0){
    				foreach ($res_3->result_array() as $row3) {
    					$row['i_additional'][] =  $row3;
    				}
    			}else{
    				$row['i_additional'] =  array();
    			}		    				
    		$data[] = $row;
    	}
	return $data;
}
    					
    					
    				function loadInsurancesCustomerReportDatePicker() {
    						$data = array();
    								    				
    						$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
			    			$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
			    		 
		    				$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
		    				$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));

		    				if($this->author->objlogin->uid != '1'){
								$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  
    				 				from insurance i, new_applicent a  
    				 				where i.applicent_id = a.applicent_id 
    				 				AND i.author_id = '" . $this->author->objlogin->uid . "'
    				 				AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    				 				ORDER BY i.create_date DESC";
		    				}else{
		    					$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
    				 				from insurance i, new_applicent a
    				 				where i.applicent_id = a.applicent_id
		    					    AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
		    					    ORDER BY i.create_date DESC";
		    				}
    							    			//
    						$res = $this->db->query($sql);
    				
    						foreach ($res->result_array() as $row) {
    						$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    							    			//$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    				
    						// Get Grouped Applicent Info if selected more then one
    		
    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    			if(sizeof($res_1->result_array()) > 0){
		    		foreach ($res_1->result_array() as $row1) {
		    			$row['applicents'][] =  $row1;
		    		}
    			}else{
    				$row['applicents'] =  array();
    			}
    			
    			// get Notes for Insurance
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, insurance i, users u  where u.uid = n.create_by AND i.insurance_id = n.new_app_id AND n.new_app_id = '".$row["insurance_id"]."'  AND note_from = 'insurance'";
    			$res_2 = $this->db->query($sql_2);
    			
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}	
    			
    			// get additional information for Insurance
    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND a.insurance_id = '".$row["insurance_id"]."'";
    			$res_3 = $this->db->query($sql_3);
    			 
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_3->result_array()) > 0){
    				foreach ($res_3->result_array() as $row3) {
    					$row['i_additional'][] =  $row3;
    				}
    			}else{
    				$row['i_additional'] =  array();
    			}		    				
    		$data[] = $row;
    	}
	return $data;
 }
    								    				
    				
    				function loadInsurancesEmployeeReport() {
    					$data = array();
    					 
    					$before30 = date('Y-m-d', strtotime('-29 days'));
    					$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    					 
    					$todate = date('Y-m-d');
    					$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    					 
    					if($this->author->objlogin->uid != '1'){
    					$sql = "SELECT
    					DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
    					FROM_UNIXTIME(i.create_date) as create_date2,
    					SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
    					SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
    					SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
    					SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
    					SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
    					SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM insurance i, users u, ero e
		    			where i.uid = e.uid
		    			AND   e.author = i.author_id
		    			AND  u.uid = e.author
    					AND i.create_date != ''
    					AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    					GROUP BY u.firstname
    					ORDER BY u.firstname ASC
    					";
    					}else{
    						$sql = "SELECT
    						DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
    						FROM_UNIXTIME(i.create_date) as create_date2,
    						SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
    						SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
    						SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
    						SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
    						SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
    						SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
    						u.uid,
    						u.firstname,
    						u.lastname
    						FROM insurance i, users u
    						where u.uid = i.author_id
    						AND i.create_date != ''
    						AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    						GROUP BY u.firstname
    						ORDER BY u.firstname ASC
    						";
    					}
    					//
    					$res = $this->db->query($sql);
    					 
    					foreach ($res->result_array() as $row) {
    					$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date2"]));
    				$data[] = $row;
    					}
    				    						return $data;
    					}
    					
    					function loadInsurancesEmployeeReportDatePicker() {
    						$data = array();
    					
    						$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
			    			$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
			    		 
		    				$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
		    				$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
		    				if($this->author->objlogin->uid != '1'){
    						$sql = "SELECT
    					DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
    					FROM_UNIXTIME(i.create_date) as create_date2,
    					SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
    					SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
    					SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
    					SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
    					SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
    					SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM insurance i, users u, ero e
		    			where i.uid = e.uid
		    			AND   e.author = i.author_id
		    			AND  u.uid = e.author
    					AND i.create_date != ''
    					AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
    					GROUP BY u.firstname
    					ORDER BY u.firstname ASC
    					";
		    				}else{
		    					$sql = "SELECT
		    					DATE(FROM_UNIXTIME(i.create_date)) as create_date1, i.create_date,
		    					FROM_UNIXTIME(i.create_date) as create_date2,
		    					SUM(CASE WHEN insurance_item = 'Family Individual' THEN 1 ELSE 0 END) AS family,
		    					SUM(CASE WHEN insurance_item = 'Group Health' THEN 1 ELSE 0 END) AS groupHealth,
		    					SUM(CASE WHEN insurance_item = 'Life Insurance & Annuities' THEN 1 ELSE 0 END) AS lifeInsurance,
		    					SUM(CASE WHEN insurance_item = 'Auto Insurance' THEN 1 ELSE 0 END) AS autoInsurance,
		    					SUM(CASE WHEN insurance_item = 'Home Insurance' THEN 1 ELSE 0 END) AS homeInsurance,
		    					SUM(CASE WHEN insurance_item = 'Property & Casualty' THEN 1 ELSE 0 END) AS propertyCasualty,
		    					u.uid,
		    					u.firstname,
		    					u.lastname
		    					FROM insurance i, users u
		    					where u.uid = i.author_id
		    					AND i.create_date != ''
		    					AND DATE(FROM_UNIXTIME(i.create_date)) between '$before30_date' AND '$todate_date'
		    					GROUP BY u.firstname
		    					ORDER BY u.firstname ASC
		    					";
		    				}
    						//
    						$res = $this->db->query($sql);
    					
    						foreach ($res->result_array() as $row) {
    						$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date2"]));
    							$data[] = $row;
    						}
    									return $data;
    						}
    					
    					

    					function loadInsurancesEmployeeCustomerReport() {
    						$data = array();
    					
    						$employeeId = $_GET['empid'];
    						
    						if($this->author->objlogin->uid != '1'){
    							$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
    				 				from insurance i, new_applicent a
    				 				where i.applicent_id = a.applicent_id
    								AND i.uid = '" . $this->author->objlogin->uid."'
    				 				AND i.author_id = '".$employeeId."'
    						    	ORDER BY i.create_date DESC";
    						}else{
    							$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
    				 				from insurance i, new_applicent a
    				 				where i.applicent_id = a.applicent_id
    				 				AND i.author_id = '".$employeeId."'
    						    	ORDER BY i.create_date DESC";
    						}
    						$res = $this->db->query($sql);
    					
    						foreach ($res->result_array() as $row) {
    							$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    							//	    			$row["posted_date"] = gmdate("m/d/y", $row["posted_date"]);
    					
    								    			// Get Grouped Applicent Info if selected more then one
    		
    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    			if(sizeof($res_1->result_array()) > 0){
		    		foreach ($res_1->result_array() as $row1) {
		    			$row['applicents'][] =  $row1;
		    		}
    			}else{
    				$row['applicents'] =  array();
    			}
    			
    			// get Notes for Insurance
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, insurance i, users u  where u.uid = n.create_by AND i.insurance_id = n.new_app_id AND n.new_app_id = '".$row["insurance_id"]."'  AND note_from = 'insurance'";
    			$res_2 = $this->db->query($sql_2);
    			
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}	
    			
    			// get additional information for Insurance
    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND a.insurance_id = '".$row["insurance_id"]."'";
    			$res_3 = $this->db->query($sql_3);
    			 
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_3->result_array()) > 0){
    				foreach ($res_3->result_array() as $row3) {
    					$row['i_additional'][] =  $row3;
    				}
    			}else{
    				$row['i_additional'] =  array();
    			}
    								    			
    					
    								    				$data[] = $row;
    								    			}
    								    			return $data;
    								    			}	

    								    			
    	function loadInsurancesSoldCustomerReport() {
    		$data = array();
    		$activedate = $_GET['activedate'];
    		if($this->author->objlogin->uid != '1'){
    		$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
			from insurance i, new_applicent a
    		where i.applicent_id = a.applicent_id
    		AND i.uid = '" . $this->author->objlogin->uid."'
    		AND DATE(FROM_UNIXTIME(i.create_date)) = '$activedate'
    		ORDER BY i.create_date DESC";
    		}else{
    			$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1
			from insurance i, new_applicent a
    		where i.applicent_id = a.applicent_id
    			AND DATE(FROM_UNIXTIME(i.create_date)) = '$activedate'
    			ORDER BY i.create_date DESC";
    		}
    		$res = $this->db->query($sql);
    								    						    				
			foreach ($res->result_array() as $row) {
				$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

				//Get Grouped Applicent Info if selected more then one
    		
    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    			if(sizeof($res_1->result_array()) > 0){
		    		foreach ($res_1->result_array() as $row1) {
		    			$row['applicents'][] =  $row1;
		    		}
    			}else{
    				$row['applicents'] =  array();
    			}
    			
    			// get Notes for Insurance
    			$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, insurance i, users u  where u.uid = n.create_by AND i.insurance_id = n.new_app_id AND n.new_app_id = '".$row["insurance_id"]."'  AND note_from = 'insurance'";
    			$res_2 = $this->db->query($sql_2);
    			
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_2->result_array()) > 0){
    				foreach ($res_2->result_array() as $row2) {
    					$row2["note_create_date"] = gmdate("F j, Y, g:i a", $row2["create_date"]);
    					$row['notes'][] =  $row2;
    				}
    			}else{
    				$row['notes'] =  array();
    			}	
    			
    			// get additional information for Insurance
    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND a.insurance_id = '".$row["insurance_id"]."'";
    			$res_3 = $this->db->query($sql_3);
    			 
    			//$row['app'] = $res_2->result_array();
    			if(sizeof($res_3->result_array()) > 0){
    				foreach ($res_3->result_array() as $row3) {
    					$row['i_additional'][] =  $row3;
    				}
    			}else{
    				$row['i_additional'] =  array();
    			}
			$data[] = $row;
    	}
	return $data;
 }
    	
    	function loadTotalIncomeRevenueReport() {
    		$data = array();
    			
    		$before30 = date('Y-m-d', strtotime('-29 days'));
    					$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    					 
    					$todate = date('Y-m-d');
    					$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    			
    					if($this->author->objlogin->uid != '1'){
    		$sql = "SELECT
    		a.create_date,
    		FROM_UNIXTIME(b.create_date) as  create_date1,
    		SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    		SUM(app_actual_add_on_fee) AS actAddonFee,
    		SUM(actual_audit_guard_fee) AS actAuditGuardFee,
    		SUM(b.benefits_price) AS totalBenefits
    		FROM new_app a,  benefits b
    		where 
    		a.uid = b.uid
    		AND a.create_date != ''
    		AND b.uid = '" . $this->author->objlogin->uid."'
    		AND a.uid = '" . $this->author->objlogin->uid."'
    		AND b.benefits_status != '2'
    		AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    		GROUP BY DATE(FROM_UNIXTIME(a.create_date)) , DATE(FROM_UNIXTIME(b.create_date))
    		ORDER BY DATE(FROM_UNIXTIME(a.create_date)) DESC
    		";
    					}else{
    						$sql = "SELECT
    						a.create_date,
    						FROM_UNIXTIME(a.create_date) as  create_date1,
    						SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    						SUM(app_actual_add_on_fee) AS actAddonFee,
    						SUM(actual_audit_guard_fee) AS actAuditGuardFee,
    						SUM(b.benefits_price) AS totalBenefits
    						FROM new_app a,  benefits b
    						where
    						a.author_id = b.author_id
    						AND b.benefits_status != '2'
    						AND a.create_date != ''
    						AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    						GROUP BY DATE(FROM_UNIXTIME(a.create_date))
    						ORDER BY DATE(FROM_UNIXTIME(a.create_date)) DESC
    						";
    					}
    		//
    		$res = $this->db->query($sql);
    			
    		foreach ($res->result_array() as $row) {
    		$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		$data[] = $row;
    		}
    		return $data;
    		}
    		
    		function loadTotalIncomeRevenueReportDatePicker() {
    			$data = array();
    			 
    			$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
			    $before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
			    		 
		    	$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
		    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    			 
		    	if($this->author->objlogin->uid != '1'){
    			$sql = "SELECT
    			a.create_date,
    			FROM_UNIXTIME(a.create_date) as  create_date1,
    			SUM(b.benefits_price) AS totalBenefits,
    			SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    			SUM(app_actual_add_on_fee) AS actAddonFee,
    			SUM(actual_audit_guard_fee) AS actAuditGuardFee
    			FROM new_app a, benefits b
    			where
    			a.author_id = b.author_id
    			AND b.uid = '" . $this->author->objlogin->uid."'
    			AND a.uid = '" . $this->author->objlogin->uid."'
    			AND b.benefits_status != '2'
    			AND a.create_date != ''
    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    			GROUP BY DATE(FROM_UNIXTIME(a.create_date))
    			ORDER BY DATE(FROM_UNIXTIME(a.create_date)) DESC
    			";
		    	}else{
		    		$sql = "SELECT
		    		a.create_date,
		    		FROM_UNIXTIME(a.create_date) as  create_date1,
		    		SUM(b.benefits_price) AS totalBenefits,
		    		SUM(app_actual_tax_preparation_fee) AS actTaxFee,
		    		SUM(app_actual_add_on_fee) AS actAddonFee,
		    		SUM(actual_audit_guard_fee) AS actAuditGuardFee
		    		FROM new_app a, benefits b
		    		where
		    		a.author_id = b.author_id
		    		AND a.create_date != ''
		    		AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
		    		GROUP BY DATE(FROM_UNIXTIME(a.create_date))
		    		ORDER BY DATE(FROM_UNIXTIME(a.create_date)) DESC
		    		";
		    	}
    			//SUM(CASE WHEN benefits_item != '' THEN benefits_price ELSE 0 END) AS totalBenefits,
    			$res = $this->db->query($sql);
    			 
    			foreach ($res->result_array() as $row) {
    			$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    			$data[] = $row;
    			}
    			return $data;
    			}
    		
    		function loadServiceBureauRevenueReport() {
    				$data = array();
    				 
    				$before30 = date('Y-m-d', strtotime('-29 days'));
    				$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
    			
    				$todate = date('Y-m-d');
    				$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    				 
    				if($this->author->objlogin->uid != '1'){
    				 $sql = "SELECT m.uid, m.p_efin, m.service_bureau_num ,
    					a.create_date,
    				 	FROM_UNIXTIME(a.create_date) as  create_date1,
    					
    					SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    					SUM(app_actual_add_on_fee) AS actAddonFee,
    					SUM(actual_audit_guard_fee) AS actAuditGuardFee,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM new_app a, users u, master_ero m 
    				 	WHERE m.is_service_bureau = '1' AND u.uid = m.uid AND service_bureau_num = '".$this->author->objlogin->efin."'
    					AND u.uid = a.author_id
    					AND a.create_date != ''
    					AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    					GROUP BY u.uid
    					ORDER BY u.firstname ASC";
    				}else{
    					$sql = "SELECT m.uid, m.p_efin, m.service_bureau_num ,
    					a.create_date,
    				 	FROM_UNIXTIME(a.create_date) as  create_date1,
    	
    					SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    					SUM(app_actual_add_on_fee) AS actAddonFee,
    					SUM(actual_audit_guard_fee) AS actAuditGuardFee,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM new_app a, users u, master_ero m
    				 	WHERE m.is_service_bureau = '1' AND u.uid = m.uid 
						AND u.uid = a.author_id
    					AND a.create_date != ''
    					AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    					GROUP BY u.uid
    					ORDER BY u.firstname ASC";
    				}
    				//
    				$res = $this->db->query($sql);
    				 
    				foreach ($res->result_array() as $row) {
    				$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    				$data[] = $row;
    				}
    				return $data;
    				}	
    		
    		function loadServiceBureauRevenueReportDatePicker() {
    					$data = array();
    						
    					$before30 = $_GET['startdate'];  //$this->lib->escape($_POST['startdate']);
			    			$before30_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$before30)));
			    		 
		    				$todate = $_GET['enddate']; //$this->lib->escape($_POST['enddate']);
		    				$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    						
    					/*$sql = "SELECT
    					a.create_date,
    					SUM(CASE WHEN benefits_item != '' THEN benefits_price ELSE 0 END) AS totalBenefits,
    					SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    					SUM(app_actual_add_on_fee) AS actAddonFee,
    					SUM(actual_audit_guard_fee) AS actAuditGuardFee,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM new_app a, users u
    					where u.uid = a.author_id
    					AND a.create_date != ''
    					AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    					GROUP BY u.uid
    					ORDER BY u.firstname ASC
    					";*/
    					//
		    			if($this->author->objlogin->uid != '1'){
    					$sql = "SELECT m.uid, m.p_efin, m.service_bureau_num ,
    					a.create_date,
    					FROM_UNIXTIME(a.create_date) as  create_date1,
    					SUM(CASE WHEN benefits_item != '' THEN benefits_price ELSE 0 END) AS totalBenefits,
    					SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    					SUM(app_actual_add_on_fee) AS actAddonFee,
    					SUM(actual_audit_guard_fee) AS actAuditGuardFee,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM new_app a, users u,
						master_ero m WHERE m.is_service_bureau = '1' AND u.uid = m.uid AND service_bureau_num = '".$this->author->objlogin->efin."'
    					AND u.uid = a.author_id
    					AND a.create_date != ''
    					AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
    					GROUP BY u.uid
    					ORDER BY u.firstname ASC";
		    			}else{
		    					$sql = "SELECT m.uid, m.p_efin, m.service_bureau_num ,
    					a.create_date,
    					FROM_UNIXTIME(a.create_date) as  create_date1,
    					SUM(CASE WHEN benefits_item != '' THEN benefits_price ELSE 0 END) AS totalBenefits,
    					SUM(app_actual_tax_preparation_fee) AS actTaxFee,
    					SUM(app_actual_add_on_fee) AS actAddonFee,
    					SUM(actual_audit_guard_fee) AS actAuditGuardFee,
    					u.uid,
    					u.firstname,
    					u.lastname
    					FROM new_app a, users u,
						master_ero m WHERE m.is_service_bureau = '1' AND u.uid = m.uid AND service_bureau_num = '".$this->author->objlogin->efin."'
		    			AND u.uid = a.author_id
		    			AND a.create_date != ''
		    			AND DATE(FROM_UNIXTIME(a.create_date)) between '$before30_date' AND '$todate_date'
		    			GROUP BY u.uid
		    			ORDER BY u.firstname ASC";
		    				}
    							
    					$res = $this->db->query($sql);
    						
    					foreach ($res->result_array() as $row) {
    					$row["app_create_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    					$data[] = $row;
    					}
    					return $data;
    					}
    				
    

}

?>
