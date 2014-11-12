<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("admin/Customers_model", "m_customers");
        $this->load->model("admin/mycompany_model", "m_com");
    }

    public function perms() {
        $perms['Services'] = array('index','showCustomerList','updateCustomerInfo','showDetailsAboutSelectedCustomer','showCustomerReportByEro');

        return $perms;
    }

    
    public function index(){
    	$data = array();
    	$data['title_page'] = 'Customers';
    	$data['states'] = $this->m_com->loadStatesList();

        $officedata = $this->system->getOfficeInfo();
        $data['alloffice'] = $officedata['officeCombo'];
        $data['allofficewithAll'] = $officedata['officeComboWithAll'];
        $data['selectedCompanyName'] = $officedata['selectedOffice'];

        $data['hidefromEmployee'] = ($this->author->objlogin->isemployee == 1)?'style="display:none"':'';
    	$this->system->parse("customer/customers.htm", $data);
    }
   
    public function showCustomerList() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_customers->showCustomerList());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('customer/customer_list.htm', $data);
    		exit();
    	}
    }
    
    public function showCustomerReportByEro() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_customers->showCustomerListByEro());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('customer/customer_list.htm', $data);
    		exit();
    	}
    }
    
    public function showDetailsAboutSelectedCustomer() {
    	//$data = array();
    	//$dataLoadCustomer =  "dataClient = " . json_encode($this->m_customers->loadDetailsAboutSelectedCustomer());
    	//$data['states'] = $this->m_com->loadStatesList();
    	echo json_encode($this->m_customers->loadDetailsAboutSelectedCustomer());
    	 
    }
    
    public function updateCustomerInfo(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_customers->updateCustomerInfo());
    		exit;
    	}
    }
    
    
       
}

?>