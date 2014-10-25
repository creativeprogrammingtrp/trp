<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Bankproducts extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/clientcenter_model", "m_clientcenter");
        $this->load->model("admin/mycompany_model", "m_com");
    }
    function perms(){
        $perms['Bank Products'] = array('index');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"BankProducts");
        $data['readytoprintCoutBadgeValue'] = $this->m_clientcenter->countReadyToPrintApplication();
        $data['countPendingFundsApplication'] = $this->m_clientcenter->countPendingFundsApplication();
        $data['countPrintedCheckApplication'] = $this->m_clientcenter->countPrintedCheckApplication();
        $data['countVoidCheckApplication'] = $this->m_clientcenter->countVoidCheckApplication();
        $data['countAllApplication'] = $this->m_clientcenter->countAllApplication();
        $data['countAllPaidApplication'] = $this->m_clientcenter->countAllPaidApplication();
        $data['countAllVoidedPaymentApplication'] = $this->m_clientcenter->countAllVoidedPaymentApplication();

        
        
        $this->system->parse("clientCenter/clientcenter.htm",$data);
    }
    
    
    
}
    
?>
