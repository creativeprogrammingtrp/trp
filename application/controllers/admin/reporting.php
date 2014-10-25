<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Reporting extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/reporting_model", "m_reporting");
        $this->load->model("admin/mycompany_model", "m_com");
    }
    function perms(){
        $perms['Reporting'] = array('index','showBankProductFundedReport','showBankProductUnfundedReport','showBankProductUnfundedReportDatePicker','showBankProductFundedReportDatePicker','showBankProductByCustomrReport','showBankProductByCustomrReportDatePicker','showBankProductByEmployeeReport','showBankProductByEmployeeReportDatePicker','showBankProductByEmployeeCustomerReport','updateApplicationInfoFromReportPage','showDiscountBenefitsRevenueReport','showDiscountBenefitsRevenueReportDatePicker','showDiscountBenefitsCustomerReport','showDiscountBenefitsCustomerReportDatePicker','showDiscountBenefitsBenefitsSoldReport','showDiscountBenefitsBenefitsSoldReportDatePicker','showDiscountBenefitsEmployeeReport','showDiscountBenefitsEmployeeReportDatePicker','showDiscountBenefitsEmployeeCustomerReport','showInsurancesRevenueReport','showInsurancesRevenueReportDatePicker','showInsurancesInsuranceSoldReport','showInsurancesInsuranceSoldReportDatePicker','showInsurancesCustomerReport','showInsurancesCustomerReportDatePicker','showInsurancesEmployeeReport','showInsurancesEmployeeReportDatePicker','showInsurancesEmployeeCustomerReport','showTotalIncomeRevenueReport','showTotalIncomeRevenueReportDatePicker','showServiceBureauRevenueReport','showServiceBureauRevenueReportDatePicker','showDiscountBenefitsSoldCustomerReport','showInsurancesSoldCustomerReport');
        return $perms;			
    }

    public function index(){
        $data = array('title_page'=>"Reporting");
        $this->system->parse("reporting/reporting.htm",$data);
    }
    
    public function showBankProductFundedReport() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductFundedReport());
	   	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductReport.htm', $data);
    		exit();
    	}
    }
    
    public function showBankProductFundedReportDatePicker() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductFundedReportDatePicker());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductReport.htm', $data);
    		exit();
    	}
    }

    public function showBankProductUnfundedReport() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductUnfundedReport());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductUnfundexReport.htm', $data);
    		exit();
    	}
    }
        
    public function showBankProductUnfundedReportDatePicker() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductUnfundedReportDatePicker());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductUnfundexReport.htm', $data);
    		exit();
    	}
   }
    
   public function showBankProductByCustomrReport() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductByCustomrReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductByCustomerReport.htm', $data);
    		exit();
    	}
   }
    
    public function showBankProductByCustomrReportDatePicker() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductByCustomrReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductByCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    public function showBankProductByEmployeeReport() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductByEmployeeReport());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductByEmployeeReport.htm', $data);
    		exit();
    	}
    }
    
    
    public function showBankProductByEmployeeReportDatePicker() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductByEmployeeReportDatePicker());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductByEmployeeReport.htm', $data);
    		exit();
    	}
    }
    
    public function showBankProductByEmployeeCustomerReport() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_reporting->loadBankProductByEmployeeCustomerReport());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/bankProductByEmployeeCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    
    public function showDiscountBenefitsRevenueReport() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsRevenueReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    public function showDiscountBenefitsRevenueReportDatePicker() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsRevenueReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    public function showDiscountBenefitsBenefitsSoldReport() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsBenefitsSoldReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsBenefitsSoldReport.htm', $data);
    		exit();
    	}
    }
    
    public function showDiscountBenefitsBenefitsSoldReportDatePicker() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsBenefitsSoldReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsBenefitsSoldReport.htm', $data);
    		exit();
    	}
    }
    
    
    
    public function showDiscountBenefitsSoldCustomerReport() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsSoldCustomerReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsSoldCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    public function showDiscountBenefitsCustomerReport() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsCustomerReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    
    public function showDiscountBenefitsCustomerReportDatePicker() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsCustomerReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    
    public function showDiscountBenefitsEmployeeReport() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsEmployeeReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsEmployeeReport.htm', $data);
    		exit();
    	}
    }
    
    
    public function showDiscountBenefitsEmployeeReportDatePicker() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadDiscountBenefitsEmployeeReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsEmployeeReport.htm', $data);
    		exit();
    	}
    }
    
    public function showDiscountBenefitsEmployeeCustomerReport() {
    	$data = array();
    	$data['dataLoadDR'] = "dataClientDR = " . json_encode($this->m_reporting->loadwDiscountBenefitsEmployeeCustomerReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/discountBenefitsEmployeeCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    
    
    public function showInsurancesRevenueReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesRevenueReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    public function showInsurancesRevenueReportDatePicker() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesRevenueReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    public function showInsurancesInsuranceSoldReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesInsuranceSoldReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceInsuranceSoldReport.htm', $data);
    		exit();
    	}
    }
    
    public function showInsurancesInsuranceSoldReportDatePicker() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesInsuranceSoldReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceInsuranceSoldReport.htm', $data);
    		exit();
    	}
    }
    
    
    
    public function showInsurancesSoldCustomerReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesSoldCustomerReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceSoldCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    public function showInsurancesCustomerReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesCustomerReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    public function showInsurancesCustomerReportDatePicker() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesCustomerReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    
    public function showInsurancesEmployeeReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesEmployeeReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceEmployeeReport.htm', $data);
    		exit();
    	}
    }
    
    public function showInsurancesEmployeeReportDatePicker() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesEmployeeReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceEmployeeReport.htm', $data);
    		exit();
    	}
    }
    
    
    
    public function showInsurancesEmployeeCustomerReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadInsurancesEmployeeCustomerReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/insuranceEmployeeCustomerReport.htm', $data);
    		exit();
    	}
    }
    
    
    public function showTotalIncomeRevenueReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadTotalIncomeRevenueReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/totalIncomeRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    public function showTotalIncomeRevenueReportDatePicker() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadTotalIncomeRevenueReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/totalIncomeRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    public function showServiceBureauRevenueReport() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadServiceBureauRevenueReport());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/serviceBureauRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    public function showServiceBureauRevenueReportDatePicker() {
    	$data = array();
    	$data['dataLoadIR'] = "dataClientIR = " . json_encode($this->m_reporting->loadServiceBureauRevenueReportDatePicker());
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('reporting/serviceBureauRevenueReport.htm', $data);
    		exit();
    	}
    }
    
    
    
    public function updateApplicationInfoFromReportPage(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_reporting->updateApplicationInfoFromReportPage());
    		exit;
    	}
    }
    
    
}

    
?>