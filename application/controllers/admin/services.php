<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("admin/Services_model", "m_services");
        $this->load->model("admin/mycompany_model", "m_com");
    }

    public function perms() {
        $perms['Services'] = array('index','benefits','insurance','addinsurance','showPendingInsurance','addbenefits','showPendingBenefits','updateInsuranceInfo','updateBenefitsInfo','insurancePolicies','benefitsOrders','addinsuranceNext','addbenefitsNext','addNewApplicent','showActiveInsurance','showCancelledInsurance','showActiveBenefits','showCancelledBenefits','searchApplicent','changeStatusAndAddInsuranceNote','changeStatusAndAddBenefitsNote','getSelectedApplicentInfo');
        return $perms;
    }

    
    public function index(){
    	$data = array();
    	$data['title_page'] = 'Services';
    	$this->system->parse("services/services.htm", $data);
    }
   

    public function benefits(){
    	$data = array();
    	$data['title_page'] = 'Benifits';
    	$data['url_base_path'] = $this->system->cleanUrl();
    	$data['curPageURLServer'] = $this->system->URL_server__();
    	$data['states'] = $this->m_com->loadStatesList();
    	$data['applicent'] = $this->m_com->loadApplicentList();
    	$this->system->parse("services/benefits.htm", $data);
    }
    
    public function benefitsOrders(){
    	$data = array();
    	$data['title_page'] = 'Benifits';
    	$data['url_base_path'] = $this->system->cleanUrl();
    	$data['curPageURLServer'] = $this->system->URL_server__();
    	$data['states'] = $this->m_com->loadStatesList();
    	$this->system->parse("services/benefits_orders.htm", $data);
    }
    
    public function insurance(){
    	$data = array();
    	$data['title_page'] = 'Insurance';
    	$data['url_base_path'] = $this->system->cleanUrl();
    	$data['curPageURLServer'] = $this->system->URL_server__();
    	$data['states'] = $this->m_com->loadStatesList();
    	$data['applicent'] = $this->m_com->loadApplicentList();
    	
    	$this->system->parse("services/insurance.htm", $data);
    }
    
    public function insurancePolicies(){
    	$data = array();
    	$data['title_page'] = 'Insurance';
    	$data['url_base_path'] = $this->system->cleanUrl();
    	$data['curPageURLServer'] = $this->system->URL_server__();
    	$data['states'] = $this->m_com->loadStatesList();
    	$this->system->parse("services/insurance_policies.htm", $data);
    }
    
    public function addinsurance() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_services->saveNewInsuranceInfo());
    		exit();
    	}
    }
    
    public function searchApplicent() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_com->loadApplicentListBySearchText($_POST['searchit']));
    		exit();
    	}
    }
    

public function addNewApplicent() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		//echo json_encode($this->m_services->addNewApplicent());
    		echo json_encode($this->m_services->addNewApplicent());
    		//$data['applicent'] = $this->m_com->loadApplicentList();
    		//echo json_encode($this->m_com->loadApplicentList());
    		exit();
    	}
    }
    
    
    public function addinsuranceNext() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		 echo json_encode($this->m_services->saveNewInsuranceInfoNext());
    		exit();
    	}
    }
    
    
    public function addbenefitsNext() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_services->saveNewBenefitsInfoNext());
    		exit();
    	}
    }
    
    
    public function addbenefits() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_services->saveNewBenefitsInfo());
    		exit();
    	}
    }
    
    
    public function showPendingInsurance() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showPendingInsurance());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/pending_insurance.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showPendingInsuranceForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/pending_insurance_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showPendingInsuranceForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('services/pending_insurance_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showActiveInsurance() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showActiveInsurance());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/active_insurance.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showActiveInsuranceForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/active_insurance_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showActiveInsuranceForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('services/active_insurance_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showCancelledInsurance() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showCancelledInsurance());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/cancelled_insurance.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showCancelledInsuranceForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/cancelled_insurance_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showCancelledInsuranceForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('services/cancelled_insurance_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showPendingBenefits() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showPendingBenefits());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/pending_benefits.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showPendingBenefitsForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/pending_benefits_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showPendingBenefitsForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('services/pending_benefits_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showActiveBenefits() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showActiveBenefits());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/active_benefits.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showActiveBenefitsForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/active_benefits_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showActiveBenefitsForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('services/active_benefits_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showCancelledBenefits() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showCancelledBenefits());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/cancelled_benefits.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showCancelledBenefitsForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('services/cancelled_benefits_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_services->showCancelledBenefitsForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('services/cancelled_benefits_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    
    public function updateInsuranceInfo(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_services->updateInsuranceInfo());
    		exit;
    	}
    }
    
    
    public function updateBenefitsInfo(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_services->updateBenefitsInfo());
    		exit;
    	}
    }
    
    public function changeStatusAndAddInsuranceNote(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_services->changeStatusAndAddInsuranceNote());
    		exit;
    	}
    }
    
    public function changeStatusAndAddBenefitsNote(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_services->changeStatusAndAddBenefitsNote());
    		exit;
    	}
    }
    
    public function getSelectedApplicentInfo() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_services->loadSelectedApplicentInfo());
    		//exit();
    	}
    }
   
    
}

?>