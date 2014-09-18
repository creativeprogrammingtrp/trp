<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Clientcenter extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/clientcenter_model", "m_clientcenter");
        $this->load->model("admin/mycompany_model", "m_com");
    }
    function perms(){
        $perms['Client Center'] = array('index','newapp','saveNewApplicationInfo','showRecentApplication','nextstep','updateApplicationInfo','showPendingFundsApplication','showReadyToPrintApplication','showSelectedReadyToPrintApplication','showAllApplication', 'showPrintedApplication','showVoidedApplication');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"clientCenter");
        $this->system->parse("clientCenter/clientcenter.htm",$data);
    }
    
    public function newapp(){
    	$data = array();
    	$data['title_page'] = "BankProducts";
    	//$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadBankingData());
    	$resultdata = $this->m_clientcenter->loadBankingData();
    	
    	if(is_array($resultdata) && count($resultdata)){
    		$data['tax_preparation_fee'] = $resultdata['tax_preparation_fee'];
    		$data['bank_transmission_fee'] = $resultdata['bank_transmission_fee'];
    		$data['sb_fee'] = $resultdata['sb_fee'];
    		//$data['e_file_fee'] = $data_load['e_file_fee'];
    		$data['add_on_fee'] = $resultdata['add_on_fee'];
    	}
    	
    /*	$data['message'] = '<div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <strong>Success!</strong> Your Application is Submitted!
                    </div>';
    	
    	*/
    	$data['statusmessage'] = '';
    	$action = $this->uri->segment (4);
    	if($action == 'save'){
    		$result = $this->m_clientcenter->saveNewApplicationInfo();
    		if($result){
    			$data['statusmessage'] = '<div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <strong>Success!</strong> Your Application is Submitted!
                    </div>';
    		}else{
    			$data['statusmessage'] = '<div class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <strong>Error!</strong> Sorry! Your Application is not successfully submited. Please Try Again.
                    </div>';
    		}
    	}
    	// print_r($data);
    	// exit;
    	$data['states'] = $this->m_com->loadStatesList();
    	$data['countries'] = $this->m_com->loadCountryList();
    	
    	$this->system->parse("clientCenter/new_application.htm",$data);
    }
    
    public function saveNewApplicationInfo() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_clientcenter->saveNewApplicationInfo());
    		exit();
    	}
    }
    
    public function nextstep() {
    	if (isset($_POST['next_step']) && !empty($_POST['next_step']) && $_POST['next_step'] == 'yes') {
    		//echo json_encode($this->m_clientcenter->saveNewApplicationInfo());
    		echo json_encode("data:1");
    		exit();
    	}
    }
    
    
    
    public function showRecentApplication() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadRecentApplication());
		//print_r($this->m_clientcenter->loadRecentApplication());exit;
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('clientCenter/recent_applications.htm', $data);
    		exit();
    	}
    }
    
    public function showPendingFundsApplication() {
    	$data = array();
    	
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
	    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPendingFundsApplication());    	
	    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
	    		$this->system->parse_templace('clientCenter/pending_funds_applications.htm', $data);
	    		exit();
	    	}
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPendingFundsApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/pending_funds_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
	
    
    public function showReadyToPrintApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
	    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadReadyToPrintApplication());
	    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
	    		$this->system->parse_templace('clientCenter/ready_to_print_applications.htm', $data);
	    		exit();
	    	}
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadReadyToPrintApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/ready_to_print_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    
    public function showPrintedApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
	    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPrintedApplication());
	    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
	    		$this->system->parse_templace('clientCenter/printed_applications.htm', $data);
	    		exit();
	    	}
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPrintedApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/printed_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showVoidedApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	
    	if($this->author->objlogin->uid != '1'){
	    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedApplication());
	    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
	    		$this->system->parse_templace('clientCenter/voided_applications.htm', $data);
	    		exit();
	    	}
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/voided_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    
    
    public function showAllApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
	    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadAllApplication());
	    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
	    		$this->system->parse_templace('clientCenter/all_applications.htm', $data);
	    		exit();
	    	}
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadAllApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/all_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showSelectedReadyToPrintApplication() {
    	$data = array();
    	if($_GET['ids'] != ''){
    		$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']));
    	}else{
    		$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadReadyToPrintApplication());
    	}
    	//$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('clientCenter/modal_ready_to_print_applications.htm', $data);
    		exit();
    	}
    }
    
    public function updateApplicationInfo(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_clientcenter->updateApplicationInfo());
    		exit;
    	}
    }
    
   
    
}
    
?>
