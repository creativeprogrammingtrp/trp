<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Services_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
 function saveNewInsuranceInfo() {
 	
 	if($this->objlogin->parentUid > 0){
 		$parrentUid = $this->objlogin->parentUid;
 	}
 	else{
 		$parrentUid =$this->author->objlogin->uid;
 	}
 	
   	// get selected insurance item & insert into table
    $items = $this->input->post("selectedItem");
    $item = explode(',',substr($items,1));
    	
    for ($j=0; $j < count($item); $j++){
    		
    	$individualitems = explode('~',$item[$j]);
    		
    	// get slected applicent info
    	$selectedApplicent = $this->lib->escape($_POST['selectedApplicent']);
    	//substr('abcdef', 1);
    	$appex = explode(',',substr($selectedApplicent,1));
    		
    	$dataApp = array(
    		'applicent_id' => $appex[0], //$lastApplicentId,
    		'insurance_item' => $individualitems[0],
    		'insurance_img_source' => $individualitems[1],
    		'create_date' => $this->lib->getTimeGMT(),
    		'insurance_status' => '0',
    		'payment_status' => '0',
    		'author_id' => $this->author->objlogin->uid,
    		'uid' => $parrentUid
    	);
    		 
    	$this->db->insert("insurance", $dataApp);
    	$lastAppId = $this->db->insert_id();
    		
    	// insert other selected applicent  info
    	for($i = 1; $i < count($appex); $i++){
    		$data1 = array(
    			'applicent_id' => $appex[$i],
    			'insurance_id' => $lastAppId
    		);
    		$this->db->insert("insurance_applicent", $data1);
    	}
    		
    // Insert additional info for insurance product only
    if($appex[0] != ''){	
    	//echo $individualitems[0];
    	if($individualitems[0] == 'Family Individual'){
    		
    		$family_coverage_date = $this->input->post("family_coverage_date");//$this->lib->escape($_POST['family_coverage_date']);
    		$family_gender =  $this->input->post("family_gender"); //$this->lib->escape($_POST['family_gender']);
    		$family_tobacco_use =  $this->input->post("family_tobacco_use");// $this->lib->escape($_POST['family_tobacco_use']);
    		
    		//print_r($family_coverage_date);
    		
    		for($k=0; $k < count($family_coverage_date); $k++){
    			$data2 = array(
    					'insurance_id' => $lastAppId,
    					'aplicent_id' => $appex[0],
    					'insurance_title' => 'Family Individual',
    					'family_coverage_date' =>  $family_coverage_date[$k],
    					'family_gender' => $family_gender[$k],
    					'family_tobacco_use' => $family_tobacco_use[$k],
    					
    					
    			);
    			$this->db->insert("insurance_application_additional_info", $data2);
    		}
    		
    	}
    	if($individualitems[0] == 'Group Health'){
	    	$company_name_grouphealth = $this->lib->escape($_POST['company_name_grouphealth']);
	    	$industry_grouphealth = $this->lib->escape($_POST['industry_grouphealth']);
	    	$company_address_grouphealth = $this->lib->escape($_POST['company_address_grouphealth']);
	    	$state_grouphealth = $this->lib->escape($_POST['state_grouphealth']);
	    	$zip_grouphealth = $this->lib->escape($_POST['zip_grouphealth']);
	    	$requested_line_grouphealth = $this->lib->escape($_POST['requested_line_grouphealth']);
	    	$current_carrier_grouphealth = $this->lib->escape($_POST['current_carrier_grouphealth']);
	    	$renewal_date_grouphealth = $this->lib->escape($_POST['renewal_date_grouphealth']);
	    	$effective_date_grouphealth = $this->lib->escape($_POST['effective_date_grouphealth']);
	    	
	    	$data2 = array(
    			'insurance_id' => $lastAppId,
    			'aplicent_id' => $appex[0],
	    		'insurance_title' => 'Group Health',
    			'company_name_grouphealth' =>  $company_name_grouphealth,
    			'industry_grouphealth' => $industry_grouphealth,
    			'company_address_grouphealth' => $company_address_grouphealth,
    			'state_grouphealth' => $state_grouphealth,
    			'zip_grouphealth' => $zip_grouphealth,
    			'requested_line_grouphealth' => $requested_line_grouphealth,
    			'current_carrier_grouphealth' => $current_carrier_grouphealth,
    			'renewal_date_grouphealth' => $renewal_date_grouphealth,
    			'effective_date_grouphealth' => $effective_date_grouphealth,
	    			
	    	);
	    	$this->db->insert("insurance_application_additional_info", $data2);
	    	
    	}
    	if($individualitems[0] == 'Life Insurance & Annuities'){
    		
	    	$gender_life = $this->lib->escape($_POST['gender_life']);
	    	$height_life = $this->lib->escape($_POST['height_life']);
	    	$width_life = $this->lib->escape($_POST['width_life']);
	    	$tobacco_use_life = $this->lib->escape($_POST['tobacco_use_life']);
	    	
	    	$data2 = array(
    			'insurance_id' => $lastAppId,
    			'aplicent_id' => $appex[0],
	    		'insurance_title' => 'Life Insurance & Annuities',
    			'gender_life' =>  $gender_life,
    			'height_life' => $height_life,
    			'width_life' => $width_life,
    			'tobacco_use_life' => $tobacco_use_life,
	    			
    		);
	    	
	    	$this->db->insert("insurance_application_additional_info", $data2);
	    	
    	}
    	if($individualitems[0] == 'Auto Insurance'){
    	
    		$gender_auto = $this->input->post("gender_auto");
    		$marital_status_auto = $this->input->post("marital_status_auto");
    		$relation_auto = $this->input->post("relation_auto");
    		$year_auto = $this->lib->escape($_POST['year_auto']);
    		$make_auto = $this->lib->escape($_POST['make_auto']);
    		$model_auto = $this->lib->escape($_POST['model_auto']);
    		$coverage_auto = $this->lib->escape($_POST['coverage_auto']);
    		
    		//print_r($marital_status_auto);
    		for($k=0; $k < count($marital_status_auto); $k++){
    			$data2 = array(
    					'insurance_id' => $lastAppId,
    					'aplicent_id' => $appex[$k],
    					'insurance_title' => 'Auto Insurance',
    					'marital_status_auto' => $marital_status_auto[$k],
    					'gender_auto' => $gender_auto[$k],
    					'relation_auto' =>  $relation_auto[$k],
    					'year_auto' => $year_auto,
    					'make_auto' =>  $make_auto,
    					'model_auto' => $model_auto,
    					'coverage_auto' => $coverage_auto,
    					
    			);
    			
    			$this->db->insert("insurance_application_additional_info", $data2);
    			$year_auto = '';
    			$make_auto = '';
    			$model_auto = '';
    			$coverage_auto = '';
    		}
    	}
    	if($individualitems[0] == 'Home Insurance'){
    		$coverage_date_home = $this->lib->escape($_POST['coverage_date_home']);
    		$data2 = array(
    			'insurance_id' => $lastAppId,
    			'aplicent_id' => $appex[0],
    			'insurance_title' => 'Home Insurance',
    			'coverage_date_home' =>  $coverage_date_home,
    				
			);
    		$this->db->insert("insurance_application_additional_info", $data2);
    		
    	}if($individualitems[0] == 'Property & Casualty'){
	    	$revenue_property = $this->lib->escape($_POST['revenue_property']);
	    	$past_claims_property = $this->lib->escape($_POST['past_claims_property']);
	    	$insurance_type_property = $this->lib->escape($_POST['insurance_type_property']);
	    	
	    	$data2 = array(
    			'insurance_id' => $lastAppId,
    			'aplicent_id' => $appex[0],
	    		'insurance_title' => 'Property & Casualty',
    			'revenue_property' =>  $revenue_property,
    			'past_claims_property' => $past_claims_property,
    			'insurance_type_property' => $insurance_type_property,
	    			
    		);
	    	$this->db->insert("insurance_application_additional_info", $data2);
    	}
    	
    	//$this->db->insert("insurance_application_additional_info", $data2);
    	
    } // end if condition selected applicent not null 
    
 } // end of item for loop
    	return true;
}
    
    function saveNewInsuranceInfoNext(){
    	return true;
    }
    
    function saveNewBenefitsInfoNext(){
    	return true;
    }
    

    function saveNewBenefitsInfo() {
    	
    	if($this->objlogin->parentUid > 0){
    		$parrentUid = $this->objlogin->parentUid;
    	}
    	else{
    		$parrentUid =$this->author->objlogin->uid;
    	}
    	 
    	// get selected benefits info
    	$items = $this->lib->escape($_POST['selectedBenefitsItem']);
    	$indItm = explode('~',$items);
    	
    	// get selected applicent info
    	$selectedApplicent = $this->lib->escape($_POST['selectedApplicent']);
    	$appex = explode(',',substr($selectedApplicent,1));
    
    	$data = array(
    			
    			'applicent_id' => $appex[0], //$lastApplicentId,
    			'uid' => $parrentUid,
    			'benefits_item' => $indItm[0],
    			'benefits_price' => $indItm[3],
    			'benefits_item_desc' => $indItm[1],
    			'benefits_img_source' => $indItm[2],
    			'create_date' => $this->lib->getTimeGMT(),
    			'benefits_status' => '0',
    			'payment_status' => '0',
    			'author_id' => $this->author->objlogin->uid,
    			 
    	);
    	$this->db->insert("benefits", $data);
    	$lastAppId = $this->db->insert_id();
    	
    	//$selectedApplicent = $this->lib->escape($_POST['selectedApplicent']);
    	//$appex = explode(',',substr($selectedApplicent,1));
    	
    	for($i = 1; $i < count($appex); $i++){
	    	$data1 = array(
	    			 'benefits_id' => $lastAppId,
	    			 'applicent_id' => $appex[$i],
	    	 );
	    	$this->db->insert("benefits_applicent", $data1);
    	}
    	 
    	return true;
    }
    
    
    function showPendingInsurance(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();

    	$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a  where i.applicent_id = a.applicent_id AND i.uid = '" . $this->author->objlogin->uid . "' AND i.insurance_status = '0' ORDER BY i.insurance_id DESC";
    	
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		
    		// Get Grouped Applicent Info if selected more then one
    		
    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
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

    function showPendingInsuranceForEmployee(){
        $data = array();
        $todate = $this->lib->getTimeGMT();

        $sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a  where i.applicent_id = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.insurance_status = '0' ORDER BY i.insurance_id DESC";

        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            // Get Grouped Applicent Info if selected more then one

            $sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
            $res_1 = $this->db->query($sql_1);

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
    
    function showPendingInsuranceForAdmin(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    
    	$sql = "select i.*, a.*, e.company_name, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a, master_ero e  where i.applicent_id = a.applicent_id AND i.insurance_status = '0' AND e.uid = i.uid ORDER BY i.insurance_id DESC";
    	 
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    
    		// Get Grouped Applicent Info if selected more then one
    
    		$sql_1 = "select i.*, a.* from insurance_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.insurance_id = '".$row["insurance_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
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
    
    
    function showActiveInsurance(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'insurance' || insurance_item != '') AND na.create_date < '".$todate."' AND na.insurance_status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//$sql = "select i.*, ia.*, a.* from insurance i, insurance_applicent ia, new_applicent a  where i.insurance_id = ia.insurance_id AND ia.uid = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.create_date < '".$todate."' AND i.status = '1' ORDER BY i.insurance_id DESC";
    	$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a  where i.applicent_id = a.applicent_id AND i.uid = '" . $this->author->objlogin->uid . "' AND i.insurance_status = '1' ORDER BY i.insurance_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		
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
    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND i.insurance_id = '".$row["insurance_id"]."'";
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
    	
    	//print_r($data);
    	
    	return $data;
    }

    function showActiveInsuranceForEmployee(){
        $data = array();
        $todate = $this->lib->getTimeGMT();

        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'insurance' || insurance_item != '') AND na.create_date < '".$todate."' AND na.insurance_status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        //$sql = "select i.*, ia.*, a.* from insurance i, insurance_applicent ia, new_applicent a  where i.insurance_id = ia.insurance_id AND ia.uid = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.create_date < '".$todate."' AND i.status = '1' ORDER BY i.insurance_id DESC";
        $sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a  where i.applicent_id = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.insurance_status = '1' ORDER BY i.insurance_id DESC";
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

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
            $sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND i.insurance_id = '".$row["insurance_id"]."'";
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

        //print_r($data);

        return $data;
    }
    
    function showActiveInsuranceForAdmin(){
    	
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	 
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'insurance' || insurance_item != '') AND na.create_date < '".$todate."' AND na.insurance_status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//$sql = "select i.*, ia.*, a.* from insurance i, insurance_applicent ia, new_applicent a  where i.insurance_id = ia.insurance_id AND ia.uid = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.create_date < '".$todate."' AND i.status = '1' ORDER BY i.insurance_id DESC";
    	$sql = "select i.*, a.*, e.company_name, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a,  master_ero e  where i.applicent_id = a.applicent_id AND i.insurance_status = '1' AND e.uid = i.uid ORDER BY i.insurance_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    
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
    		$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND i.insurance_id = '".$row["insurance_id"]."'";
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
    	 
    	//print_r($data);
    	 
    	return $data;
    }
    
    
    function showCancelledInsurance(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'insurance' || insurance_item != '')	 AND na.create_date < '".$todate."' AND na.insurance_status = '2' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//$sql = "select i.*, ia.*, a.* from insurance i, insurance_applicent ia, new_applicent a  where i.insurance_id = ia.insurance_id AND ia.uid = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.create_date < '".$todate."' AND i.status = '2' ORDER BY i.insurance_id DESC";
    	$sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a  where i.applicent_id = a.applicent_id AND i.uid = '" . $this->author->objlogin->uid . "' AND i.insurance_status = '2' ORDER BY i.insurance_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		
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
    			$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND i.insurance_id = '".$row["insurance_id"]."'";
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


    function showCancelledInsuranceForEmployee(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'insurance' || insurance_item != '')	 AND na.create_date < '".$todate."' AND na.insurance_status = '2' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        //$sql = "select i.*, ia.*, a.* from insurance i, insurance_applicent ia, new_applicent a  where i.insurance_id = ia.insurance_id AND ia.uid = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.create_date < '".$todate."' AND i.status = '2' ORDER BY i.insurance_id DESC";
        $sql = "select i.*, a.*, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a  where i.applicent_id = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.insurance_status = '2' ORDER BY i.insurance_id DESC";
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

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
            $sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND i.insurance_id = '".$row["insurance_id"]."'";
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
    
    function showCancelledInsuranceForAdmin(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'insurance' || insurance_item != '')	 AND na.create_date < '".$todate."' AND na.insurance_status = '2' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//$sql = "select i.*, ia.*, a.* from insurance i, insurance_applicent ia, new_applicent a  where i.insurance_id = ia.insurance_id AND ia.uid = a.applicent_id AND i.author_id = '" . $this->author->objlogin->uid . "' AND i.create_date < '".$todate."' AND i.status = '2' ORDER BY i.insurance_id DESC";
    	$sql = "select i.*, a.*, e.company_name, FROM_UNIXTIME(i.create_date) as  create_date1  from insurance i, new_applicent a, master_ero e  where i.applicent_id = a.applicent_id  AND i.insurance_status = '2' AND e.uid = i.uid ORDER BY i.insurance_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    
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
    		$sql_3 = "select a.*, na.first_name, na.last_name from insurance_application_additional_info a, insurance i, new_applicent na  where a.insurance_id = i.insurance_id AND na.applicent_id = a.aplicent_id AND i.insurance_id = '".$row["insurance_id"]."'";
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
    
    
    function showPendingBenefits(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a where b.applicent_id = a.applicent_id AND b.uid = '" . $this->author->objlogin->uid . "' AND b.benefits_status = '0' ORDER BY b.benefits_id DESC";
    	
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		
    		// Get Grouped Applicent Info if selected more then one
    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    		if(sizeof($res_1->result_array()) > 0){
    			foreach ($res_1->result_array() as $row1) {
    				$row['applicents'][] =  $row1;
    			}
    		}else{
    			$row['applicents'] =  array();
    		}
    		
    		
    	// get Notes for Benefits
    			//$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."'  AND note_from = 'benefits'";
    		$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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
    		
    		$data[] = $row;
    	}
    	return $data;
    }

    function showPendingBenefitsForEmployee(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        $sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a where b.applicent_id = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.benefits_status = '0' ORDER BY b.benefits_id DESC";

        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            // Get Grouped Applicent Info if selected more then one
            $sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
            $res_1 = $this->db->query($sql_1);

            //$row['app'] = $res_1->result_array();
            if(sizeof($res_1->result_array()) > 0){
                foreach ($res_1->result_array() as $row1) {
                    $row['applicents'][] =  $row1;
                }
            }else{
                $row['applicents'] =  array();
            }


            // get Notes for Benefits
            //$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."'  AND note_from = 'benefits'";
            $sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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

            $data[] = $row;
        }
        return $data;
    }

    function showPendingBenefitsForAdmin(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select b.*, a.*, e.company_name, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a, master_ero e where b.applicent_id = a.applicent_id  AND b.benefits_status = '0'  AND e.uid = b.uid ORDER BY b.benefits_id DESC";
    	 
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    
    		// Get Grouped Applicent Info if selected more then one
    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		//$row['app'] = $res_1->result_array();
    		if(sizeof($res_1->result_array()) > 0){
    			foreach ($res_1->result_array() as $row1) {
    				$row['applicents'][] =  $row1;
    			}
    		}else{
    			$row['applicents'] =  array();
    		}
    
    
    		// get Notes for Benefits
    		//$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."'  AND note_from = 'benefits'";
    		$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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
    
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function showActiveBenefits(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	//$sql = "select b.*, ba.*, a.* from benefits b, benefits_applicent ba, new_applicent a  where b.benefits_id = ba.benefits_id AND ba.uid = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.create_date < '".$todate."' AND b.status = '1' ORDER BY b.benefits_id DESC";
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	
    	$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a where b.applicent_id = a.applicent_id AND b.uid = '" . $this->author->objlogin->uid . "' AND b.benefits_status = '1' ORDER BY b.benefits_id DESC";
    	
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		// Get Grouped Applicent Info if selected more then one
    		//$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.uid  AND i.app_id = '".$row["app_id"]."'";
    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    		if(sizeof($res_1->result_array()) > 0){
    			foreach ($res_1->result_array() as $row1) {
    				$row['applicents'][] =  $row1;
    			}
    		}else{
    			$row['applicents'] =  array();
    		}
    		
    		// get Notes for Benefits
    		//$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."' AND note_from = 'benefits'";
    		$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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
    		
    		
    		$data[] = $row;
    	}
    	return $data;
    }


    function showActiveBenefitsForEmployee(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        //$sql = "select b.*, ba.*, a.* from benefits b, benefits_applicent ba, new_applicent a  where b.benefits_id = ba.benefits_id AND ba.uid = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.create_date < '".$todate."' AND b.status = '1' ORDER BY b.benefits_id DESC";
        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";

        $sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a where b.applicent_id = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.benefits_status = '1' ORDER BY b.benefits_id DESC";

        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
            // Get Grouped Applicent Info if selected more then one
            //$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.uid  AND i.app_id = '".$row["app_id"]."'";
            $sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
            $res_1 = $this->db->query($sql_1);

            //$row['app'] = $res_1->result_array();
            if(sizeof($res_1->result_array()) > 0){
                foreach ($res_1->result_array() as $row1) {
                    $row['applicents'][] =  $row1;
                }
            }else{
                $row['applicents'] =  array();
            }

            // get Notes for Benefits
            //$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."' AND note_from = 'benefits'";
            $sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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


            $data[] = $row;
        }
        return $data;
    }
    
    function showActiveBenefitsForAdmin(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	//$sql = "select b.*, ba.*, a.* from benefits b, benefits_applicent ba, new_applicent a  where b.benefits_id = ba.benefits_id AND ba.uid = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.create_date < '".$todate."' AND b.status = '1' ORDER BY b.benefits_id DESC";
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	 
    	$sql = "select b.*, a.*, e.company_name, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a, master_ero e where b.applicent_id = a.applicent_id AND b.benefits_status = '1'  AND e.uid = b.uid  ORDER BY b.benefits_id DESC";
    	 
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		// Get Grouped Applicent Info if selected more then one
    		//$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.uid  AND i.app_id = '".$row["app_id"]."'";
    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		//$row['app'] = $res_1->result_array();
    		if(sizeof($res_1->result_array()) > 0){
    			foreach ($res_1->result_array() as $row1) {
    				$row['applicents'][] =  $row1;
    			}
    		}else{
    			$row['applicents'] =  array();
    		}
    
    		// get Notes for Benefits
    		//$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."' AND note_from = 'benefits'";
    		$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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
    
    
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function showCancelledBenefits(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	//$sql = "select b.*, ba.*, a.* from benefits b, benefits_applicent ba, new_applicent a  where b.benefits_id = ba.benefits_id AND ba.uid = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.create_date < '".$todate."' AND b.status = '2' ORDER BY b.benefits_id DESC";
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '2' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	$sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a where b.applicent_id = a.applicent_id AND b.uid = '" . $this->author->objlogin->uid . "' AND b.benefits_status = '2' ORDER BY b.benefits_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		// Get Grouped Applicent Info if selected more then one
    		//$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.uid  AND i.app_id = '".$row["app_id"]."'";
    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    		
    		//$row['app'] = $res_1->result_array();
    		if(sizeof($res_1->result_array()) > 0){
    			foreach ($res_1->result_array() as $row1) {
    				$row['applicents'][] =  $row1;
    			}
    		}else{
    			$row['applicents'] =  array();
    		}
    		
    		// get Notes for Benefits
    		//$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."'  AND note_from = 'benefits'";
    		$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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
    		
    		
    		$data[] = $row;
    	}
    	return $data;
    }


    function showCancelledBenefitsForEmployee(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        //$sql = "select b.*, ba.*, a.* from benefits b, benefits_applicent ba, new_applicent a  where b.benefits_id = ba.benefits_id AND ba.uid = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.create_date < '".$todate."' AND b.status = '2' ORDER BY b.benefits_id DESC";
        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND (app_from = 'benefits' || benefits_item != '') AND na.benefits_status = '2' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        $sql = "select b.*, a.*, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a where b.applicent_id = a.applicent_id AND b.author_id = '" . $this->author->objlogin->uid . "' AND b.benefits_status = '2' ORDER BY b.benefits_id DESC";
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
            // Get Grouped Applicent Info if selected more then one
            //$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.uid  AND i.app_id = '".$row["app_id"]."'";
            $sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
            $res_1 = $this->db->query($sql_1);

            //$row['app'] = $res_1->result_array();
            if(sizeof($res_1->result_array()) > 0){
                foreach ($res_1->result_array() as $row1) {
                    $row['applicents'][] =  $row1;
                }
            }else{
                $row['applicents'] =  array();
            }

            // get Notes for Benefits
            //$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."'  AND note_from = 'benefits'";
            $sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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


            $data[] = $row;
        }
        return $data;
    }
    
    function showCancelledBenefitsForAdmin(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select b.*, a.*, e.company_name, FROM_UNIXTIME(b.create_date) as  create_date1 from benefits b, new_applicent a, master_ero e where b.applicent_id = a.applicent_id AND b.benefits_status = '2'  AND e.uid = b.uid  ORDER BY b.benefits_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));
    		// Get Grouped Applicent Info if selected more then one
    		//$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.uid  AND i.app_id = '".$row["app_id"]."'";
    		$sql_1 = "select i.*, a.* from benefits_applicent i, new_applicent a  where a.applicent_id = i.applicent_id  AND i.benefits_id = '".$row["benefits_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		//$row['app'] = $res_1->result_array();
    		if(sizeof($res_1->result_array()) > 0){
    			foreach ($res_1->result_array() as $row1) {
    				$row['applicents'][] =  $row1;
    			}
    		}else{
    			$row['applicents'] =  array();
    		}
    
    		// get Notes for Benefits
    		//$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, new_app a, users u  where u.uid = n.create_by AND a.app_id = n.new_app_id AND n.new_app_id = '".$row["app_id"]."'  AND note_from = 'benefits'";
    		$sql_2 = "select n.*, u.firstname, u.lastname from newapp_benefits_insurance_note n, benefits b, users u  where u.uid = n.create_by AND b.benefits_id = n.new_app_id AND n.new_app_id = '".$row["benefits_id"]."'  AND note_from = 'benefits'";
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
    
    
    		$data[] = $row;
    	}
    	return $data;
    }
    
    function countInsurance(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "' AND create_date < '".$todate."' AND status = '1'";
    	$res = $this->db->query($sql);
    	
    	$result = $res->result_array() ;
    	return $result[0]['total'];
    	
    }
    
    
    
    function loadAllInsurance(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	$sql = "select * from new_app  where uid = '" . $this->author->objlogin->uid . "' ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["create_date"]);
    
    		$sql_1 = "select * from new_app_product  where uid = '" . $this->author->objlogin->uid . "' AND app_id = '".$row["app_id"]."'";
    		$res_1 = $this->db->query($sql_1);
    
    		//$row['prodcuts'] = $res_1->result_array();
    		foreach ($res_1->result_array() as $row1) {
    			/*if ($row1['img_source'] !== '') {
    				$row1['img_source'] = '<img  src="' . $this->system->URL_server__() . 'application/views/TRP/backend/img/' . $row1['img_source'] . '">';
    			} else {
    				$row1['img_source'] = '';
    			}*/
    			$row['prodcuts'][] =  $row1;
    		}
    		//  http://localhost/trpplus/application/views/TRP/backend/img/Client_Center_chart.jpg
    		$data[] = $row;
    	}
    	return $data;
    }
    
    
    public function updateInsuranceInfo() {
    	 
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
    	$this->db->where('applicent_id', $this->lib->escape($_POST['insurance_applicent_id']));
    	$this->db->update('new_applicent', $data);
    	 
    	
    	return $this->showPendingInsurance();
    }
    
    public function updateBenefitsInfo() {
    
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
    	$this->db->where('applicent_id', $this->lib->escape($_POST['benefits_applicent_id']));
    	$this->db->update('new_applicent', $data);
    
    	 
    	return $this->showPendingBenefits();
    }
    
    function addNewApplicent(){
    	if($this->objlogin->parentUid > 0){
    		$parrentUid = $this->objlogin->parentUid;
    	}
    	else{
    		$parrentUid =$this->author->objlogin->uid;
    	}
    	
    	$data = array(
    			'uid' => $parrentUid,
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
    			 
    			/*'app_total_refund_amt' => $this->lib->escape($_POST['total_refund_amt_3']),
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
    			    			 
    			'card_number' => $this->lib->escape($_POST['card_number']),
    			'app_bank_name' => $this->lib->escape($_POST['bank_name']),
    			'app_account_no' => $this->lib->escape($_POST['acount_number']),
    			'app_routing_no' => $this->lib->escape($_POST['routing_number']),
    			 
    			'assign_acc_no' => '4234432342334534',
    			'assign_routing_no' => '4232343433',*/
    			'create_date' => $this->lib->getTimeGMT(),
    			'author_id' => $this->author->objlogin->uid,			 
    	);
    	$this->db->insert("new_applicent", $data);
    	$lastAppId = $this->db->insert_id();
    	
    	return $lastAppId;
    }
    
    function loadRefindApplicentList() {
    	$data = array();
    	
    	$sql = "select app_id,uid,first_name,last_name,ss_number from new_app ORDER BY first_name ASC";
    	$res = $this->db->query($sql);
    	
    	foreach ($res->result_array() as $row) {
    		
    		$data[] = $row;
    	}
    	
    	return $data;
    }

    function changeStatusAndAddInsuranceNote(){
    	
    	// update insurace status
    	$data = array(
    			'insurance_status' => $this->lib->escape($_POST['istatus']),
    	);
    	//$this->db->where('app_id', $this->lib->escape($_POST['in_product_id']));
    	//$this->db->update('new_app', $data);
    	
    	$this->db->where('insurance_id', $this->lib->escape($_POST['appId']));
    	$this->db->update('insurance', $data);
    	
    	// new notes
    	$data = array(
    			
    			'note_text' => $this->lib->escape($_POST['insurance_note']),
    			'note_from' => 'insurance',
    			'new_app_id' => $this->lib->escape($_POST['appId']),
    			'create_date' => $this->lib->getTimeGMT(),
    			'create_by' => $this->author->objlogin->uid,
    	);
    	$this->db->insert("newapp_benefits_insurance_note", $data);
    	return true;
    }
    
    function changeStatusAndAddBenefitsNote(){
    	 
    	// update insurace status
    	$data = array(
    			'benefits_status' => $this->lib->escape($_POST['bstatus']),
    	);
    	$this->db->where('benefits_id', $this->lib->escape($_POST['appId']));
    	$this->db->update('benefits', $data);
    	 
    	// new notes
    	$data1 = array(
    			 
    			'note_text' => $this->lib->escape($_POST['benefits_note']),
    			'note_from' => 'benefits',
    			'new_app_id' => $this->lib->escape($_POST['appId']),
    			'create_date' => $this->lib->getTimeGMT(),
    			'create_by' => $this->author->objlogin->uid,
    	);
    	$this->db->insert("newapp_benefits_insurance_note", $data1);
    	
    	return true;
    	 
    }
    
    function loadSelectedApplicentInfo() {
    	$data = array();
    
    	$selectedApplicent = $this->lib->escape($_POST['selectedApplicent']);
    	//substr('abcdef', 1);
    	//$appex = explode(',',substr($selectedApplicent,1));
    	$ids = substr($selectedApplicent,1);
    
    	$sql = "select applicent_id,first_name,last_name,ss_number from new_applicent Where applicent_id in (".$ids.")";
    	$res = $this->db->query($sql);
    
    	foreach ($res->result_array() as $row) {
    
    		$data[] = $row;
    	}
    	//print_r($data);
    	return $data;
    }
}






?>
