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
        }else{
        	$data_load = array();
        }
        
       
        return $data_load;
    }

 function saveNewApplicationInfo() {

     $data = array();

 	if($this->author->objlogin->parentUid > 0){
 		$parrentUid = $this->author->objlogin->parentUid;
 	}
 	else{
 		$parrentUid =$this->author->objlogin->uid;
 	}
 	
 	$html = "";
 	$auditItm = '';
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
 			'create_date' => $this->lib->getTimeGMT(),
 			'author_id' => $this->author->objlogin->uid,
 			);
    
	 	$this->db->insert("new_applicent", $data);
	 	$lastApplicentId = $this->db->insert_id();


        // Email EROs Part
         $query = $this->db->query("select m.*, u.firstname, u.lastname, u.phone, u.mail from master_ero m, users u  where m.uid = u.uid AND m.uid = '" . $parrentUid . "'");


         if($query->num_rows() > 0){
             $data_load = $query->result_array();
         }else{
             $data_load = array();
         }



	 	// for email customer info part
	 	
	 	$html .= "Hello,<br>";  // {$this->lib->escape($_POST['first_name'])} {$this->lib->escape($_POST['last_name'])},<br><br>";

     $html .= "<table width='100%'>";
     $html .= "<tr><td colspan='2'><h3>ERO Info:</h3></td></tr>";
     $html .= "<tr><td  width='25%'>Contact Person:</td><td  width='75%'>".$data_load[0]['firstname']." &nbsp; ".$data_load[0]['lastname']."</td></tr>";
     $html .= "<tr><td>Company Name:</td><td>".$data_load[0]['company_name']."</td></tr>";
     $html .= "<tr><td>Email:</td><td>".$data_load[0]['mail']."</td></tr>";
     $html .= "<tr><td>Contact No:</td><td>".$data_load[0]['phone']."</td></tr>";
     $html .= "<tr><td colspan='2'><h3>ERO Business Address:</h3></td></tr>";
     $html .= "<tr><td>Address Line 1:</td><td>".$data_load[0]['business_addr_1']."</td></tr>";
     $html .= "<tr><td>Address Line 2:</td><td>".$data_load[0]['business_addr_2']."</td></tr>";
     $html .= "<tr><td>Phone #:</td><td>".$data_load[0]['business_phone']."</td></tr>";
     $html .= "<tr><td>City:</td><td>".$data_load[0]['business_city']."</td></tr>";
     $html .= "<tr><td>State:</td><td>".$data_load[0]['business_state']."</td></tr>";
     $html .= "<tr><td>Zip:</td><td>".$data_load[0]['business_zip']."</td></tr>";
     $html .= "</table>";

	 	$html .= "<table width='100%'>";
        $html .= "<tr><td colspan='2'>&nbsp;</td></tr>";
	 	$html .= "<tr><td colspan='2'><h3>Applicent Info:</h3></td></tr>";
	 	$html .= "<tr><td  width='25%'>First Name:</td><td  width='75%'>".$this->lib->escape($_POST['first_name'])."</td></tr>";
	 	$html .= "<tr><td>Last Name:</td><td>".$this->lib->escape($_POST['last_name'])."</td></tr>";
	 	//$html .= "<tr><td>SSN #:</td><td>".$this->lib->escape($_POST['ss_number'])."</td></tr>";
	 	//$html .= "<tr><td>Birth Date:</td><td>".$this->lib->escape($_POST['dob'])."</td></tr>";
	 	//if($this->lib->escape($_POST['sp_first_name']) != ''){
	 	//$html .= "<tr><td>Spouse's First Name:</td><td>".$this->lib->escape($_POST['sp_first_name'])."</td></tr>";
	 	//$html .= "<tr><td>Spouse's Last Name:</td><td>".$this->lib->escape($_POST['sp_last_name'])."</td></tr>";
	 	//$html .= "<tr><td>SSN #:</td><td>".$this->lib->escape($_POST['sp_ss_number'])."</td></tr>";
	 	//$html .= "<tr><td>Birth Date:</td><td>".$this->lib->escape($_POST['sp_dob'])."</td></tr>";
	 	//}
	 	$html .= "<tr><td>Address: </td><td>".$this->lib->escape($_POST['street_address'])."</td></tr>";
	 	$html .= "<tr><td>City:</td><td>".$this->lib->escape($_POST['city'])."</td></tr>";
	 	$html .= "<tr><td>State:</td><td>".$this->lib->escape($_POST['state'])."</td></tr>";
	 	$html .= "<tr><td>Zip Code:</td><td>".$this->lib->escape($_POST['zip_code'])."</td></tr>";
	 	$html .= "<tr><td>Contact Phone #:</td><td>".$this->lib->escape($_POST['cell_phone'])."</td></tr>";
	 	$html .= "<tr><td>Email Address:</td><td>".$this->lib->escape($_POST['email_add'])."</td></tr>";
	 	$html .= "</table>";
	 	
	 	// end for email customer info part
 	 
	 	// get audit guard item if selected
	 	$inputAuditgurdSeletecItems = $this->input->post('inputAuditgurdSeletecItems'); 
	 	
	 	if($inputAuditgurdSeletecItems != ''){
	 		$auditItm = explode("~",$inputAuditgurdSeletecItems);
	 		
	 		$auditItemName = $auditItm[0];
	 		$auditItemDesc = $auditItm[1];
	 		$auditItemImg = $auditItm[2];
	 		$auditprice = $auditItm[3];
	 		
	 	}else{
	 		$auditItemName = '';
	 		$auditItemDesc = '';
	 		$auditItemImg = '';
	 		$auditprice = 0;
	 	}
	 	
	 // Group No. This number is fixed for TRP Solution.
     $groupNo = '1933';

     // get last assign account no form db
     $lastAssignAccNoFull = $this->getLastAssignAccountNo();

     if($lastAssignAccNoFull != ''){
         $lastAssignAccNo1 = intval(substr($lastAssignAccNoFull, 4))+1;
         $lastAssignAccNo = $groupNo.str_pad($lastAssignAccNo1, 13, "0", STR_PAD_LEFT);

     }else{
         $lastAssignAccNo = $groupNo.'0000000000001';
     }

     $data['AssignAccNo'] = $lastAssignAccNo;

    	$dataApp = array(
    			'uid' => $parrentUid,
    			'applicent_id' => $lastApplicentId,
    			
    			'app_total_refund_amt' => $this->lib->escape($_POST['total_refund_amt_3']),
    			'app_tax_preparation_fee' => $this->lib->escape($_POST['tax_preparation_fee_3']),
    			'app_bank_transmission_fee' => $this->lib->escape($_POST['bank_transmission_fee_3']),
    			'app_sb_fee' => $this->lib->escape($_POST['sb_fee_3']),
    			//'app_e_file_fee' => $this->lib->escape($_POST['e_file_fee_3']),
    			'app_add_on_fee' => $this->lib->escape($_POST['add_on_fee_3']),
    			
    			'audit_guard_item' => $auditItemName,
    			'audit_guard_item_desc' => $auditItemDesc,
    			'audit_guard_img_source' => $auditItemImg,
    			'audit_guard_fee' => $auditprice,
    			
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


    			'assign_acc_no' => $lastAssignAccNo,
    			'assign_routing_no' => '111900659',
    			'status' => '0',
    			'payment_status' => '0',
    			'author_id' => $this->author->objlogin->uid,
    			
    	);
    	$this->db->insert("new_app", $dataApp);
    	$lastAppId = $this->db->insert_id();
    	
    	
    	
    	// Get Benefits item if selected & save
    	$inputSeletecItems1 = $this->input->post('inputSeletecItems'); //$this->lib->escape($_POST['inputSeletecItems']);
    	if($inputSeletecItems1 != ''){
    		$indItm = explode("~",$inputSeletecItems1);
    		
    		$dataBenefits = array(
    				 
    				'app_id' => $lastAppId,
    				'applicent_id' => $lastApplicentId, //$lastApplicentId,
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
    		$this->db->insert("benefits", $dataBenefits);
    		//$lastAppId = $this->db->insert_id();
    	}
    	
    	
    	
    	// Get Insurance Item if selected
    	
    	$insuranceProductHtml = '';
    	
    	$Insitems = $this->input->post('inputInsurenceSeletecItems');
    	$item = explode(',',substr($Insitems,1));
    	
    	//$individualitems = explode('~',$Insitems);
     $individualitems = array();
    	
    	$insuranceAdditionalInfoHtml = '';
    	// echo count($item);
    	if($Insitems != ''){ 
    	for ($j=0; $j < count($item); $j++){
    		
    		
    		$individualitems = explode('~',$item[$j]);
    		
    		$data1 = array(
    				
    			'app_id' => $lastAppId,
	    		'applicent_id' => $lastApplicentId, //$lastApplicentId,
	    		'insurance_item' => $individualitems[0],
	    		'insurance_img_source' => $individualitems[1],
	    		'create_date' => $this->lib->getTimeGMT(),
	    		'insurance_status' => '0',
	    		'payment_status' => '0',
	    		'author_id' => $this->author->objlogin->uid,
	    		'uid' => $parrentUid
    		);
    		//	}
    		
    		$this->db->insert("insurance", $data1);
    		$lastInsuranceAppId = $this->db->insert_id();
    		
    	//	$this->db->insert("new_app_product", $data1);
    		//$lastAppProductId = $this->db->insert_id();
    		
    		//$insuranceProductHtml .= "<tr><td>".$individualitems[0]."</td><td></td><td></td></tr>";

    
    	
    		
    	// insurance additional info
    	if($individualitems[0] == 'Family Individual'){
    	
    		$family_coverage_date = $this->input->post("family_coverage_date");//$this->lib->escape($_POST['family_coverage_date']);
    		$family_gender =  $this->input->post("family_gender"); //$this->lib->escape($_POST['family_gender']);
    		$family_tobacco_use =  $this->input->post("family_tobacco_use");// $this->lib->escape($_POST['family_tobacco_use']);
    	
    		//print_r($family_coverage_date);
    	
    		
    			$data2 = array(
    					'insurance_id' => $lastInsuranceAppId,
    					'aplicent_id' => $lastApplicentId,
    					'insurance_title' => 'Family Individual',
    					'family_coverage_date' =>  $family_coverage_date,
    					'family_gender' => $family_gender,
    					'family_tobacco_use' => $family_tobacco_use,
    			);
    			$this->db->insert("insurance_application_additional_info", $data2);
    		
    			$insuranceAdditionalInfoHtml .= "<tr><td colspan='2'><h3>Family / Individual:</h3></td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td width='25%'>Coverage Date:</td><td width='75%'>".$family_coverage_date."</td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td>Gender:</td><td>".$family_gender."</td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td>Tobacco Use:</td><td>".$family_tobacco_use."</td></tr>";
    			
    	
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
    				'insurance_id' => $lastInsuranceAppId,
    					'aplicent_id' => $lastApplicentId,
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
    		
    		$insuranceAdditionalInfoHtml .= "<tr><td colspan='2'><h3>Group Health:</h3></td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td width='25%'>Company Name:</td><td width='75%'>".$company_name_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Industry:</td><td>".$industry_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Address:</td><td>".$company_address_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>State:</td><td>".$state_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Zip Code:</td><td>".$zip_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Requested Lines of Coverage:</td><td>".$requested_line_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Current Carrier:</td><td>".$current_carrier_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Current Renewal Date:</td><td>".$renewal_date_grouphealth."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Requested Effective Date:</td><td>".$effective_date_grouphealth."</td></tr>";
    		
    		
    	
    	}
    	if($individualitems[0] == 'Life Insurance & Annuities'){
    	
    		$gender_life = $this->lib->escape($_POST['gender_life']);
    		$height_life = $this->lib->escape($_POST['height_life']);
    		$width_life = $this->lib->escape($_POST['width_life']);
    		$tobacco_use_life = $this->lib->escape($_POST['tobacco_use_life']);
    	
    		$data2 = array(
    				'insurance_id' => $lastInsuranceAppId,
    					'aplicent_id' => $lastApplicentId,
    					'insurance_title' => 'Life Insurance & Annuities',
    				'gender_life' =>  $gender_life,
    				'height_life' => $height_life,
    				'width_life' => $width_life,
    				'tobacco_use_life' => $tobacco_use_life,
    		);
    	
    		$this->db->insert("insurance_application_additional_info", $data2);
    		
    		$insuranceAdditionalInfoHtml .= "<tr><td colspan='2'><h3>Life Insurance &amp; Annuities:</h3></td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td width='25%'>Gender:</td><td width='75%'>".$gender_life."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Height:</td><td>".$height_life."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Width:</td><td>".$width_life."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Tobacco Use:</td><td>".$tobacco_use_life."</td></tr>";
    		
    	
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
    		
    			$data2 = array(
    					'insurance_id' => $lastInsuranceAppId,
    					'aplicent_id' => $lastApplicentId,
    					'insurance_title' => 'Auto Insurance',
    					'marital_status_auto' => $marital_status_auto,
    					'gender_auto' => $gender_auto,
    					'relation_auto' =>  $relation_auto,
    					'year_auto' => $year_auto,
    					'make_auto' =>  $make_auto,
    					'model_auto' => $model_auto,
    					'coverage_auto' => $coverage_auto,
    			);
    			
    			$this->db->insert("insurance_application_additional_info", $data2);
    			
    			$insuranceAdditionalInfoHtml .= "<tr><td colspan='2'><h3>Auto Insurance:</h3></td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td width='25%'>Marital Status :</td><td width='75%'>".$marital_status_auto."</td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td>Gender:</td><td>".$gender_auto."</td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td>Year:</td><td>".$year_auto."</td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td>Marke:</td><td>".$make_auto."</td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td>Model:</td><td>".$model_auto."</td></tr>";
    			$insuranceAdditionalInfoHtml .= "<tr><td>Coverage:</td><td>".$coverage_auto."</td></tr>";
    			
    		
    	}
    	if($individualitems[0] == 'Home Insurance'){
    		$coverage_date_home = $this->lib->escape($_POST['coverage_date_home']);
    		$data2 = array(
    				'insurance_id' => $lastInsuranceAppId,
    					'aplicent_id' => $lastApplicentId,
    					'insurance_title' => 'Home Insurance',
    				'coverage_date_home' =>  $coverage_date_home,
    		);
    		$this->db->insert("insurance_application_additional_info", $data2);
    		
    		$insuranceAdditionalInfoHtml .= "<tr><td colspan='2'><h3>Home Insurance:</h3></td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td width='25%'>Coverage Date:</td><td width='75%'>".$coverage_date_home."</td></tr>";
    		
    		 
    	
    	}
    	if($individualitems[0] == 'Property & Casualty'){
    		$revenue_property = $this->lib->escape($_POST['revenue_property']);
    		$past_claims_property = $this->lib->escape($_POST['past_claims_property']);
    		$insurance_type_property = $this->lib->escape($_POST['insurance_type_property']);
    	
    		$data2 = array(
    				'insurance_id' => $lastInsuranceAppId,
    					'aplicent_id' => $lastApplicentId,
    					'insurance_title' => 'Property & Casualty',
    				'revenue_property' =>  $revenue_property,
    				'past_claims_property' => $past_claims_property,
    				'insurance_type_property' => $insurance_type_property,
    		);
    		$this->db->insert("insurance_application_additional_info", $data2);
    		
    		$insuranceAdditionalInfoHtml .= "<tr><td colspan='2'><h3>Property &amp; Casualty:</h3></td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td width='25%'>Provide a realistic estimate of your gross revenue for the current financial year:</td><td width='75%'>".$revenue_property."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Have any claims been filed against your business during the past 3 years:</td><td>".$past_claims_property."</td></tr>";
    		$insuranceAdditionalInfoHtml .= "<tr><td>Please indicate which types of insurance you would like to receive a quote for:</td><td>".$insurance_type_property."</td></tr>";
    	}
    	
 } // END OF INSURACNE PRODUCT ADD
 }
    	// End insurance additional info
    	
 $bankingFee = floatval($this->lib->escape($_POST['tax_preparation_fee_3']) + $this->lib->escape($_POST['bank_transmission_fee_3']) + $this->lib->escape($_POST['sb_fee_3']) + $this->lib->escape($_POST['add_on_fee_3']));
 /*
 $html .= "<table width='100%'>";
 $html .= "<tr><td colspan='3'>Refund Amount</td></tr>";
 $html .= "<tr><td>".floatval($this->lib->escape($_POST['net_refund_amt_3']))."</td><td>&nbsp;</td></tr>";
 $html .= "</table>";
 
 $html .= "<table width='100%'>";
 $html .= "<tr><td colspan='3'>Bank Fee</td></tr>";
 $html .= "<tr><td>Banking</td><td></td><td>".$bankingFee."</td></tr>";
 $html .= "</table>";
 */
 $html .= "<table width='100%'>";
 //$html .= "<tr><td colspan='3'>Product Fee</td></tr>";
 if($inputAuditgurdSeletecItems != ''){ // for bank
 	//$html .= "<tr><td>".$auditItm[0]."</td><td></td><td>".$auditprice."</td></tr>";
 }
 if($inputSeletecItems1 != ''){ // for benefits
 	//$html .= "<tr><td>".$indItm[0]."</td><td></td><td>".$indItm[3]."</td></tr>";
     $total = floatval($bankingFee + $auditprice + $indItm[3]);
 }else{
     $total = floatval($bankingFee + $auditprice);
 }
 
 //$html .= $insuranceProductHtml;
 
 //$total = floatval($bankingFee + $auditprice + $indItm[3]);
/* $html .= "<tr><td><strong>Total</strong></td><td>&nbsp;</td><td>".$total."</td></tr>";
 $html .= "<tr><td></td><td>&nbsp;</td></tr>";
 $html .= "</table>";
 
 $html .= "<table width='100%'>";
 $html .= "<tr><td colspan='3'>Net Refund Amount</td></tr>";
 $html .= "<tr><td></td><td></td><td>".floatval($this->lib->escape($_POST['final_net_refund']))."</td></tr>";
 $html .= "</table>";*/
 
 
 $html .= "<table width='100%'>";
 $html .= "<tr><td colspan='2'><h2>Additional Information:</h2></td></tr>";
 $html .= $insuranceAdditionalInfoHtml;
 $html .= "</table>";
 
    	
    	// Send email to the Ishan if insurance item is seleted by applicent
    	if(sizeof($individualitems) > 0) {
            //$to = 'zishanmomin@gmail.com';
            //  $to = 'shuvro@osourcebd.com';
            $to = 'ishanpittalia@gmail.com';
            //$to = $this->lib->escape($_POST['email_add']);
            $fromName = "ScaleFinancial.com";
            $fromEmail = "info@scalefinancial.com";
            $subject = 'Order Receipt form scalefinancial.com';

            $headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . $fromName . '<' . $fromEmail . '>' . "\r\n";

            /*$first_name = $this->lib->escape($_POST['first_name']);
            $last_name = $this->lib->escape($_POST['last_name']);
            $app_total_refund_amt = $this->lib->escape($_POST['total_refund_amt_3']);
            $app_total_fees = $this->lib->escape($_POST['total_fees']);
            $app_refund_amt = $this->lib->escape($_POST['net_refund_amt_3']);
            $app_benefit = $this->lib->escape($_POST['total_benefit']);
            $app_net_refund_amt = $this->lib->escape($_POST['final_net_refund']);

            //$html = "Dear {$first_name} {$last_name},<br><br>";
            $html .= "New application info submitted at scalefinancial.com.<br><br>";
            $html .= "<strong>Refund amount:</strong> ".$app_total_refund_amt. "<br>";
            $html .= "<strong>Total fees:</strong> ".$app_total_fees. "<br>";
            $html .= "<strong>Total Benefits:</strong> ".$app_benefit. "<br>";
            $html .= "<strong>Net Refund:</strong> ".$app_net_refund_amt. "<br>";*/

            $body = "<html><head><title>$subject</title></head><body> {$html} <br> <br> <br>Regards,<br><b>scalefinancial.com</b></body></html>";
            if (mail($to, $subject, $body, $headers)) {
                //echo "ok";
                return $data;
            } else {
                //echo "error";
                return false;
            }

        }else{
            return $data;
        }
    	
    	// End send email to the customer
    	
    }


    function getLastAssignAccountNo(){
        $sql = "select assign_acc_no from new_app ORDER BY app_id DESC Limit 1";
        $res = $this->db->query($sql);
        $result = $res->result_array();
        if(sizeof($result > 0)){
            return $result[0]['assign_acc_no'];
        }else{
            return '';
        }

    }

    function loadRecentApplication(){
    	$data = array();
    	//$todate = $this->lib->getTimeGMT();
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	//AND create_date = '".$todate."'
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND  na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date = '".$todate_date."' AND na.status = '0' ORDER BY na.app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", $row["create_date"]);
            
           // $sql_1 = "select * from new_app_product  where uid = '" . $this->author->objlogin->uid . "' AND app_id = '".$row["app_id"]."'";
            //$res_1 = $this->db->query($sql_1);
            
            //$row['prodcuts'] = $res_1->result_array();
           // foreach ($res_1->result_array() as $row1) { 
	            /*if ($row1['img_source'] !== '') {
	            	$row1['img_source'] = '<img  src="' . $this->system->URL_server__() . 'application/views/TRP/backend/img/' . $row1['img_source'] . '">';
	            } else {
	            	$row1['img_source'] = '';
	            }*/
	         //   $row['prodcuts'][] =  $row1;
           // }
          //  http://localhost/trpplus/application/views/TRP/backend/img/Client_Center_chart.jpg
            $data[] = $row;
        }
        return $data;
    	
    	
    }
    
    function loadLastFiveRecentApplication(){
    	$data = array();
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date > '".$todate_date."'  AND na.status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC LIMIT 5";
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
    	return $data;
    	 
    	 
    }
    
    function loadPendingFundsApplication(){
    	$data = array();
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	// $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND DATE(FROM_UNIXTIME(na.create_date)) <= '".$todate_date."'  AND na.status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC";
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
    	return $data;
    	 
    	 
    }


    function loadPendingFundsApplicationForEmployee(){
        $data = array();
        $todate = date('Y-m-d');
        $todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));

        // $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND DATE(FROM_UNIXTIME(na.create_date)) <= '".$todate_date."'  AND na.status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.author_id = '" . $this->author->objlogin->uid . "' AND na.status = '0' AND u.uid = na.author_id ORDER BY na.app_id DESC";
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
        return $data;


    }
    
    function loadPendingFundsApplicationForAdmin(){
    	$data = array();
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	 
      //$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, master_ero e, new_applicent a  where a.applicent_id = na.applicent_id AND DATE(FROM_UNIXTIME(na.create_date)) <= '".$todate_date."'  AND na.status = '0' AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, master_ero e, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '0' AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";
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
    	return $data;
    
    
    }
	
	function loadVoidCheckApplication(){
    	$data = array();
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	$sql = "select * from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '3' ORDER BY app_id DESC";
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
    
    function loadReadyToPrintApplication(){
    	$data = array();
    	
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."'  AND app_from = 'newApplication'  AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//if($this->author->objlogin->uid != '1'){
    		$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//}else{
    	//	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//}
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
    	return $data;
    }


    function loadReadyToPrintApplicationForEmployee(){
        $data = array();

        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."'  AND app_from = 'newApplication'  AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        //if($this->author->objlogin->uid != '1'){
            $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.author_id = '" . $this->author->objlogin->uid . "' AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
       // }else{
          //  $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
       // }
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
        return $data;
    }

    
    function loadReadyToPrintApplicationForAdmin(){
    	$data = array();
    	 
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."'  AND app_from = 'newApplication'  AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, master_ero e, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '1' AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";
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
    	return $data;
    }
    
    
    function loadPrintedApplication(){
    	$data = array();
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.status = '2' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."'  AND app_from = 'newApplication' AND na.status = '2' AND u.uid = na.author_id ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    	$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            $currenttime = $this->lib->getTimeGMT();

            $getcheckDetails = $this->loadCheckDetailsByAppId($row["app_id"]);

            $issuetime = $getcheckDetails[0]["issue_date"];  // integer

            $timeAfterOneHour = $issuetime+60*60; // next one hours integer

            if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour) {
                $row["void_status"] = "true";

            }else{
                $row["void_status"] = "false";
            }
    
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
    	return $data;
    }

    function loadPrintedApplicationForEmployee(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.author_id = '" . $this->author->objlogin->uid . "' AND na.status = '2' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."'  AND app_from = 'newApplication' AND na.status = '2' AND u.uid = na.author_id ORDER BY app_id DESC";
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            $currenttime = $this->lib->getTimeGMT();

            $getcheckDetails = $this->loadCheckDetailsByAppId($row["app_id"]);

            $issuetime = $getcheckDetails[0]["issue_date"];  // integer

            $timeAfterOneHour = $issuetime+60*60; // next one hours integer

            if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour) {
                $row["void_status"] = "true";

            }else{
                $row["void_status"] = "false";
            }

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
        return $data;
    }


    function loadPrintedApplicationForAdmin(){
    	$data = array();
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, master_ero e, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '2' AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."'  AND app_from = 'newApplication' AND na.status = '2' AND u.uid = na.author_id ORDER BY app_id DESC";
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            $currenttime = $this->lib->getTimeGMT();

            $getcheckDetails = $this->loadCheckDetailsByAppId($row["app_id"]);

            $issuetime = $getcheckDetails[0]["issue_date"];  // integer

            $timeAfterOneHour = $issuetime+60*60; // next one hours integer

            if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour) {
                $row["void_status"] = "true";

            }else{
                $row["void_status"] = "false";
            }

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
    	return $data;
    }
    
    
    function loadVoidedApplication(){
    	$data = array();
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.status = '3' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."' AND app_from = 'newApplication'  AND na.status = '3' AND u.uid = na.author_id ORDER BY app_id DESC";
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
    	return $data;
    }


    function loadVoidedApplicationForEmployee(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.author_id = '" . $this->author->objlogin->uid . "' AND na.status = '3' AND u.uid = na.author_id ORDER BY na.app_id DESC";
        //$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."' AND app_from = 'newApplication'  AND na.status = '3' AND u.uid = na.author_id ORDER BY app_id DESC";
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
        return $data;
    }


    function loadVoidedApplicationForAdmin(){
    	$data = array();
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, master_ero e, new_applicent a  where a.applicent_id = na.applicent_id  AND na.status = '3' AND u.uid = na.author_id  AND e.uid = na.uid  ORDER BY na.app_id DESC";
    	//$sql = "select na.*, u.name, u.firstname, u.lastname, a.* from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.create_date < '".$todate."' AND app_from = 'newApplication'  AND na.status = '3' AND u.uid = na.author_id ORDER BY app_id DESC";
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
    	return $data;
    }


    function loadPaidApplication(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.status = '4' AND u.uid = na.author_id ORDER BY na.app_id DESC";

        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            $currenttime = $this->lib->getTimeGMT();

            $issuetime = $row["direct_deposit_time"];  // integer

            $timeAfterOneHour = $issuetime+60*60; // next one hours integer

            if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour) {
                $row["void_status"] = "true";

            }else{
                $row["void_status"] = "false";
            }

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
        return $data;
    }

    function loadPaidApplicationForEmployee(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.author_id = '" . $this->author->objlogin->uid . "' AND na.status = '4' AND u.uid = na.author_id ORDER BY na.app_id DESC";

        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            $currenttime = $this->lib->getTimeGMT();

            $issuetime = $row["direct_deposit_time"];  // integer

            $timeAfterOneHour = $issuetime+60*60; // next one hours integer

            if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour) {
                $row["void_status"] = "true";

            }else{
                $row["void_status"] = "false";
            }

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
        return $data;
    }

    function loadPaidApplicationForAdmin(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, master_ero e, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '4' AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";

        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", strtotime($row["create_date1"]));

            $currenttime = $this->lib->getTimeGMT();

            $issuetime = $row["direct_deposit_time"];  // integer

            $timeAfterOneHour = $issuetime+60*60; // next one hours integer

            if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour) {
                $row["void_status"] = "true";

            }else{
                $row["void_status"] = "false";
            }

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
        return $data;
    }


    function loadVoidedPaymentApplication(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.status = '5' AND u.uid = na.author_id ORDER BY na.app_id DESC";

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
        return $data;
    }

    function loadVoidedPaymentApplicationForEmployee(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.author_id = '" . $this->author->objlogin->uid . "' AND na.status = '5' AND u.uid = na.author_id ORDER BY na.app_id DESC";

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
        return $data;
    }

    function loadVoidedPaymentApplicationForAdmin(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, master_ero e, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '5' AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";

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
        return $data;
    }


    function countReadyToPrintApplication(){
    	$data = array();
    	
    	if($this->author->objlogin->uid  != '1'){
    		$sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "'  AND status = '1'";
    	}
    	elseif($this->author->objlogin->uid  == '1'){
    		$sql = "select count(app_id ) as total from new_app  where status = '1'";
    	}
    	$res = $this->db->query($sql);
    	
    	$result = $res->result_array() ;
    	return $result[0]['total'];
    	
    }
    
    function countRecentApplication(){
    	$data = array();
    	//$todate = $this->lib->getTimeGMT();
    	//$todate = date('Y-m-d');
    	//AND create_date = '".$todate."'
    	if($this->author->objlogin->uid  != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "'  AND status = '0'";
            }else{
                $sql = "select count(app_id ) as total from new_app  where author_id = '" . $this->author->objlogin->uid . "'  AND status = '0'";
            }
    	}
    	elseif($this->author->objlogin->uid  == '1'){
    		$sql = "select count(app_id ) as total from new_app  where status = '0'";
    	}
    	$res = $this->db->query($sql);
    	
    	$result = $res->result_array() ;
    	return $result[0]['total'];
    }
    
    function countPendingFundsApplication(){
    	$data = array();
    	
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime($todate));
    	
    	if($this->author->objlogin->uid  != '1'){ //
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select count(app_id ) as total, DATE(FROM_UNIXTIME(create_date)) from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '0'";
            }else{
                $sql = "select count(app_id ) as total, DATE(FROM_UNIXTIME(create_date)) from new_app  where author_id = '" . $this->author->objlogin->uid . "' AND status = '0'";
            }
    		//$sql = "select count(app_id ) as total, DATE(FROM_UNIXTIME(create_date)) from new_app  where uid = '" . $this->author->objlogin->uid . "' AND DATE(FROM_UNIXTIME(create_date)) < '".$todate_date."' AND status = '0'";
    	}elseif($this->author->objlogin->uid  == '1'){
    		//$sql = "select count(app_id ) as total from new_app  where DATE(FROM_UNIXTIME(create_date)) < '".$todate_date."' AND status = '0'";
    		$sql = "select count(app_id ) as total from new_app  where status = '0'";
    	}
    	$res = $this->db->query($sql);
    	
    	$result = $res->result_array() ;
    	return $result[0]['total'];
    }
    
    function countPrintedCheckApplication(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	if($this->author->objlogin->uid  != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '2'";
            }else{
                $sql = "select count(app_id ) as total from new_app  where author_id = '" . $this->author->objlogin->uid . "' AND status = '2'";
            }
    	}
    	elseif($this->author->objlogin->uid  == '1'){
    		$sql = "select count(app_id ) as total from new_app  where  status = '2'";
    	}
    	$res = $this->db->query($sql);
    	 
    	$result = $res->result_array() ;
    	return $result[0]['total'];
    }
    
    function countVoidCheckApplication(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	if($this->author->objlogin->uid  != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '3'";
            }else{
                $sql = "select count(app_id ) as total from new_app  where author_id = '" . $this->author->objlogin->uid . "' AND status = '3'";
            }
    	}elseif($this->author->objlogin->uid  == '1'){
    		$sql = "select count(app_id ) as total from new_app  where status = '3'";
    	} 
    	$res = $this->db->query($sql);
    
    	$result = $res->result_array() ;
    	return $result[0]['total'];
    }
    
    function countAllApplication(){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	if($this->author->objlogin->uid  != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "'";
            }else{
                $sql = "select count(app_id ) as total from new_app  where author_id = '" . $this->author->objlogin->uid . "'";
            }
    	}elseif($this->author->objlogin->uid  == '1'){
    		$sql = "select count(app_id ) as total from new_app";
    	}
    	$res = $this->db->query($sql);
    
    	$result = $res->result_array() ;
    	return $result[0]['total'];
    }


    function countAllPaidApplication(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        if($this->author->objlogin->uid  != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '4'";
            }else{
                $sql = "select count(app_id ) as total from new_app  where author_id = '" . $this->author->objlogin->uid . "' AND status = '4'";
            }
        }elseif($this->author->objlogin->uid  == '1'){
            $sql = "select count(app_id ) as total from new_app WHERE  status = '4'";
        }
        $res = $this->db->query($sql);

        $result = $res->result_array() ;
        return $result[0]['total'];
    }


    function countAllVoidedPaymentApplication(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        if($this->author->objlogin->uid  != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $sql = "select count(app_id ) as total from new_app  where uid = '" . $this->author->objlogin->uid . "' AND status = '5'";
            }else{
                $sql = "select count(app_id ) as total from new_app  where author_id = '" . $this->author->objlogin->uid . "' AND status = '5'";
            }
        }elseif($this->author->objlogin->uid  == '1'){
            $sql = "select count(app_id ) as total from new_app  WHERE status = '5'";
        }
        $res = $this->db->query($sql);

        $result = $res->result_array() ;
        return $result[0]['total'];
    }
    
    
    
    function loadLastFiveReadyToPrintApplication(){
    	$data = array();
    	$todate = date('Y-m-d');
    	$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));
    	
    	if($this->author->objlogin->uid != '1'){
    		$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	}else{
    		$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '1' AND u.uid = na.author_id ORDER BY na.app_id DESC";
    	}
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
    
    function loadAllApplication(){
    	$data = array();
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.uid = '" . $this->author->objlogin->uid . "' AND u.uid = na.author_id ORDER BY na.app_id DESC";
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
    	return $data;
    }

    function loadAllApplicationForEmployee(){
        $data = array();
        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.author_id = '" . $this->author->objlogin->uid . "' AND u.uid = na.author_id ORDER BY na.app_id DESC";
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
        return $data;
    }
    
    function loadAllApplicationForAdmin(){
    	$data = array();
    	$sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, users u, master_ero e, new_applicent a  where a.applicent_id = na.applicent_id AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";
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
    	return $data;
    }

    
    function loadSelectedReadyToPrintApplication($ids){
    	$data = array();
    	$todate = $this->lib->getTimeGMT();
    	if($this->author->objlogin->uid != '1'){
    		$sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.uid = '" . $this->author->objlogin->uid . "' AND n.status = '1' AND payment_method = 'Check' AND n.app_id in ($ids) ORDER BY n.app_id DESC";
    	}else{
    		$sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.status = '1' AND payment_method = 'Check' AND n.app_id in ($ids) ORDER BY n.app_id DESC";
    	}
    	$res = $this->db->query($sql);
    
    	//$data[] = $res->result_array();
    	
    	foreach ($res->result_array() as $row) {
    		$data[] = $row;
    	}
   // print_r($data);
    
    	return $data;
     }

    function loadAllReadyToPrintApplication(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        if($this->author->objlogin->uid != '1'){
            $sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.uid = '" . $this->author->objlogin->uid . "' AND n.status = '1' AND payment_method = 'Check' ORDER BY n.app_id DESC";
        }else{
            $sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.status = '1' AND payment_method = 'Check'  ORDER BY n.app_id DESC";
        }
        $res = $this->db->query($sql);

        //$data[] = $res->result_array();

        foreach ($res->result_array() as $row) {
            $data[] = $row;
        }
        // print_r($data);

        return $data;


    }

    function loadSelectedDirectDepositApplication($ids){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        if($this->author->objlogin->uid != '1'){
            $sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.uid = '" . $this->author->objlogin->uid . "' AND n.status = '1' AND payment_method = 'Direct Deposit' AND n.app_id in ($ids) ORDER BY n.app_id DESC";
        }else{
            $sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.status = '1' AND payment_method = 'Direct Deposit' AND n.app_id in ($ids) ORDER BY n.app_id DESC";
        }
        $res = $this->db->query($sql);

        //$data[] = $res->result_array();

        foreach ($res->result_array() as $row) {
            $data[] = $row;
        }
        // print_r($data);

        return $data;
    }

    function loadAllDirectDepositApplication(){
        $data = array();
        $todate = $this->lib->getTimeGMT();
        if($this->author->objlogin->uid != '1'){
            $sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.uid = '" . $this->author->objlogin->uid . "' AND n.status = '1' AND payment_method = 'Direct Deposit' ORDER BY n.app_id DESC";
        }else{
            $sql = "select n.*, a.* from new_app n, new_applicent a  where a.applicent_id = n.applicent_id AND n.status = '1' AND payment_method = 'Direct Deposit'  ORDER BY n.app_id DESC";
        }
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
    	if ($app_type_d == 'dashboard'){return $this->loadLastFiveRecentApplication();}
    	elseif($app_type == 0){
    		return $this->loadPendingFundsApplication();
    	}
    	elseif ($app_type == 1){return $this->loadReadyToPrintApplication();}
    	elseif ($app_type == 2){return $this->loadPrintedApplication();}
    	elseif ($app_type == 3){return $this->loadVoidedApplication();}
    	elseif ($app_type == 4){return $this->loadPaidApplication();}
        elseif ($app_type == 5){return $this->loadVoidedPaymentApplication();}
    	else{
    		return $this->loadAllApplication();
    	}

    	
    	//return $this->loadRecentApplication();
    }
    
   
    function checkAccountNo($accountno){
    	//$data = array();
    	$sql = "select * from new_app where assign_acc_no = '$accountno' AND w_account_no IS NULL";
    	$res = $this->db->query($sql);
    	
    	if(sizeof($res->result_array()) > 0){
    		$resul = $res->result_array();
    		return $resul[0];
    	}else{
    		return array();
    	}
    }

    function checkImportedFile($filename){
        //$data = array();
        $sql = "select * from new_app where imp_file_name = '$filename'";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
           // $resul = $res->result_array();
            return true;
        }else{
            return false;
        }
    }


    //

    function loadUncompletedAssignCheckRange(){
        $sql = "select * from assign_check where complete_date IS NULL AND uid='".$this->author->objlogin->uid."'";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
            $resul = $res->result_array();
            return $resul[0];
        }else{
            return array();
        }
    }


    function loadUncompletedAssignCheckRangeById($id){
        $sql = "select * from assign_check where complete_date IS NULL AND uid='".$id."'";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
            $resul = $res->result_array();
            return $resul[0];
        }else{
            return array();
        }
    }


    function loadOldTransJournalAccontInfo(){
        $sql = "select * from  transjournal_queu_account ORDER BY transjournal_queu_id ASC";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
           // $resul = $res->result_array();
            return $res->result_array();
        }else{
            return array();
        }
    }

    function loadSelectedQueTransJournalAccountInfo($appid){
        $sql = "select * from  transjournal_queu_account where transjournal_queu_id='$appid'";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
            // $resul = $res->result_array();
            return $res->result_array();
        }else{
            return array();
        }
    }


    function loadLastPrintedCheckNo(){
        $sql = "select * from app_check WHERE uid = '".$this->author->objlogin->uid."' ORDER BY check_id DESC limit 1";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
            $resul = $res->result_array();
            return $resul[0]['check_no'];
        }else{
            return '';
        }
    }

    function loadLastPrintedCheckNoById($id){
        $sql = "select * from app_check WHERE uid = '".$id."' ORDER BY check_id DESC limit 1";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
            $resul = $res->result_array();
            return $resul[0]['check_no'];
        }else{
            return '';
        }
    }


    function loadCheckDetailsByAppId($id){
        $sql = "select *, FROM_UNIXTIME(issue_date) as  issue_date1 from app_check WHERE app_id = '$id' AND transaction_code = '320' AND re_print IS NULL ORDER BY check_id DESC limit 1";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){
          //  $resul = $res->result_array();
           return $res->result_array();
        }else{
            return '';
        }
    }


    // thids function only will used for export file
    function loadAllPrintedCheckForExport(){

        if($this->author->objlogin->parentUid > 0){
            $parrentUid = $this->author->objlogin->parentUid;
        }
        else{
            $parrentUid = $this->author->objlogin->uid;
        }

        $sql = "select pc.*,a.*, FROM_UNIXTIME(pc.issue_date) as  check_issue_date  from app_check pc,  new_app a WHERE pc.app_id=a.app_id AND pc.exported_date IS NULL  ORDER BY pc.check_id DESC";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){

            foreach ($res->result_array() as $row) {
                $row["formated_check_issue_date"] = gmdate("mdy", strtotime($row["check_issue_date"]));
                $row["formated_check_issue_date_full"] = gmdate("m-d-Y", strtotime($row["check_issue_date"]));
                $data[] = $row;
            }
                return $data;
        }else{
            return array();
        }
    }

    // this function only will used for export file ACH
    function loadAllPrintedCheckForExportIntoACH(){

        if($this->author->objlogin->parentUid > 0){
            $parrentUid = $this->author->objlogin->parentUid;
        }
        else{
            $parrentUid = $this->author->objlogin->uid;
        }

        $sql = "select pc.*,a.*, e.*, sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee_sum, SUM(a.app_actual_add_on_fee) as app_actual_add_on_fee_sum, FROM_UNIXTIME(pc.issue_date) as  check_issue_date  from app_check pc,  new_app a,  master_ero e WHERE pc.app_id=a.app_id AND a.uid = e.uid  AND pc.re_print IS NULL AND pc.ach_export_date IS NULL  Group by pc.uid ORDER BY pc.check_id DESC ";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){

            foreach ($res->result_array() as $row) {
                $row["formated_check_issue_date"] = gmdate("mdy", strtotime($row["check_issue_date"]));
                $row["formated_check_issue_date_full"] = gmdate("m-d-Y", strtotime($row["check_issue_date"]));
                $data[] = $row;
            }
            return $data;
        }else{
            return array();
        }
    }

    function updateExportTImeForAllPrintedCheckForExport($checkid)
    {
        $todate = $this->lib->getTimeGMT();
        $sql = "UPDATE app_check SET exported_date = '$todate' WHERE check_id = '$checkid'";
        $this->db->query($sql);

    }

    function updateACHExportTImeForAllPrintedCheckForExport($appid)
    {
        $todate = $this->lib->getTimeGMT();
        $sql = "UPDATE  new_app SET ach_export_date = '$todate' WHERE app_id = '$appid'";
        $this->db->query($sql);

    }

    function addNewFileInfoWithAllPrintedCheckForExport($filename)
    {

        $data = array(
            'file_name' => $filename,
            'file_create_date' => $this->lib->getTimeGMT(),
            'created_by' => $this->author->objlogin->uid
        );

        $this->db->insert("generated_file_info", $data);
    }


    function addNewFileInfoWithAllEROPaymentInfoForExport($filename)
    {

        $data = array(
            'file_name' => $filename,
            'file_create_date' => $this->lib->getTimeGMT(),
            'created_by' => $this->author->objlogin->uid
        );

        $this->db->insert("generated_ach_file_info", $data);
    }


    function loadWxportedFileList(){
        $sql = "select *, FROM_UNIXTIME(file_create_date) as  file_create_date  from generated_file_info ORDER BY file_id DESC";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){

            foreach ($res->result_array() as $row) {
                $row["formated_file_create_date"] = gmdate("m/d/y H:i:s", strtotime($row["file_create_date"]));
                $data[] = $row;
            }
            return $data;

        }else{
            return array();
        }
    }


    function loadACHWxportedFileList(){
        $sql = "select *, FROM_UNIXTIME(file_create_date) as  file_create_date  from generated_ach_file_info ORDER BY file_id DESC";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){

            foreach ($res->result_array() as $row) {
                $row["formated_file_create_date"] = gmdate("m/d/y H:i:s", strtotime($row["file_create_date"]));
                $data[] = $row;
            }
            return $data;

        }else{
            return array();
        }
    }

    function loadLastFileIDModifier(){

        $todate = date('Y-m-d');
        $todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));

        $sql = "select *, FROM_UNIXTIME(file_create_date) as  file_create_date  from generated_ach_file_info  where DATE(FROM_UNIXTIME(file_create_date)) = '$todate_date' ORDER BY file_id DESC limit 1";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){

            foreach ($res->result_array() as $row) {
                $row["formated_file_create_date"] = gmdate("m/d/y H:i:s", strtotime($row["file_create_date"]));
                $data[] = $row;
            }
            return $data;

        }else{
            return array();
        }
    }


    function loadListOfEROForACHExport(){

        //$todate = date('Y-m-d');
        //$todate_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$todate)));

        $sql = "select * from  new_app  where payment_method = 'Direct Deposit' AND direct_deposit_time IS NOT NULL AND ach_export_date IS NULL  GROUP BY uid ORDER BY uid DESC";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){

            foreach ($res->result_array() as $row) {
              //  $row["formated_file_create_date"] = gmdate("m/d/y H:i:s", strtotime($row["file_create_date"]));
                $data[] = $row;
            }
            return $data;

        }else{
            return array();
        }
    }

    function loadAllPaidACHApplicationForExportIntoACHByERO($eroid){

        $sql = "select u.firstname,u.lastname, a.*, e.*, sum(a.app_actual_tax_preparation_fee) as app_actual_tax_preparation_fee_sum, SUM(a.app_actual_add_on_fee) as app_actual_add_on_fee_sum  from users u, new_app a,  master_ero e WHERE  a.uid = e.uid AND u.uid = e.uid AND a.uid= '$eroid' Group by a.uid";
        $res = $this->db->query($sql);

        if(sizeof($res->result_array()) > 0){

            foreach ($res->result_array() as $row) {
              //  $row["formated_check_issue_date"] = gmdate("mdy", strtotime($row["check_issue_date"]));
               // $row["formated_check_issue_date_full"] = gmdate("m-d-Y", strtotime($row["check_issue_date"]));
                $data[] = $row;
            }
            return $data;
        }else{
            return array();
        }
    }


}

?>
