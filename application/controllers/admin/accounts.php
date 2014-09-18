<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Accounts extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/ero_model","ero");
    }
    
    function perms(){
        $perms['Accounts'] = array('index','ShowPenddingApproveAccounts','approveEro','ShowApprovedAccounts','ShowPendingRegistration','ShowRejectedAccounts','ShowAllAccounts','rejectEro','approveFromReject','rejectApproved','deleteEro','ClickToEditEro','resetPassword','ShowUserLists','ShowServices','saveService','deleteService','addUser','deleteUser');
        return $perms;			
    }
    
    public function index(){
        $data = array('title_page'=>"Ero");
        $this->system->parse("accounts/accounts.htm",$data);
    }
    
     public function ShowAllAccounts(){
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->ero->loadAllAccounts());
        $data['style'] = ($this->author->objlogin->role['rid'] == 5)?'style="display:none"':'';
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('accounts/allaccounts.htm', $data);
            exit();
        }
    }
    
    public function ShowPenddingApproveAccounts(){
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->ero->loadAccounts());
        $data['style'] = ($this->author->objlogin->role['rid'] == 5)?'style="display:none"':'';
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('accounts/pendingaccounts.htm', $data);
            exit();
        }
    }
    
    public function ShowPendingRegistration(){
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->ero->loadPendingRegistrationEro());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('accounts/pendingregistrationero.htm', $data);
    		exit();
    	}
    }
    
    
    public function ShowApprovedAccounts(){
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->ero->loadApprovedAccounts());
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('accounts/approvedaccounts.htm', $data);
            exit();
        }
    }
    public function ShowRejectedAccounts(){
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->ero->loadRejectedAccounts());
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('accounts/rejectedaccounts.htm', $data);
            exit();
        }
    }
    
    public function approveEro(){
        if(!empty($_GET['id_efin'])){
            $efin = $_GET['id_efin'];
            header("Content-type: application/json");
            echo json_encode($this->ero->approveEro($efin));
            exit();
        } 
    }
   
     public function rejectEro(){
        if(!empty($_GET['id_efin'])){
            $efin = $_GET['id_efin'];
            header("Content-type: application/json");
            echo json_encode($this->ero->rejectEro($efin));
            exit();   
        }       
    }
    
    public function rejectApproved(){
        if(!empty($_GET['id_efin'])){
            $efin = $_GET['id_efin'];
            header("Content-type: application/json");
            echo json_encode($this->ero->rejectApproved($efin));
            exit();   
            
        }   
    }
    public function approveFromReject(){
        $data = array();
        if(!empty($_GET['id_efin'])){
            $efin = $_GET['id_efin'];
        }else{
            $efin = "";
        }
        $data['dataLoad'] = "dataClient = " . json_encode($this->ero->approveFromReject($efin));
        if(!empty($_GET['ajax']) && $_GET['ajax'] == 1 ){
            $this->system->parse_templace('accounts/rejectedero.htm', $data);
            exit();
        }        
    }
    
    public function deleteEro(){     
        $data = array();
        if(!empty($_GET['efin'])){
            $efin = $_GET['efin'];
        }else{
            $efin = "";
        }
        $data['dataLoad'] = "dataClient = " . json_encode($this->ero->deleteEro($efin));
        if(!empty($_GET['ajax']) && $_GET['ajax'] == 1 ){
            $this->system->parse_templace('accounts/alleros.htm', $data);
            exit();
        }        
    }
    
    public function ClickToEditEro(){
        if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
                echo json_encode($this->ero->saveCompanyInfo());
        	exit;
        }
        if( isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes' ){
        	echo json_encode($this->ero->saveProfileInfo());
        	exit;
        } 
    }
    
    
    public function resetPassword(){
         if (isset($_POST['reset_p']) && !empty($_POST['reset_p']) && $_POST['reset_p'] == 'yes') {
            echo json_encode($this->ero->resetPassword());
            exit();
        }
    }
    public function ShowUserLists(){
         if (isset($_POST['showlist']) && !empty($_POST['showlist']) && $_POST['showlist'] == 'yes') {
            echo json_encode($this->ero->ShowUserLists());
            exit();
        }
    }
    
      public function ShowServices(){
         if (isset($_POST['showservice']) && !empty($_POST['showservice']) && $_POST['showservice'] == 'yes') {
             echo json_encode($this->ero->loadServiceList());
            exit();
        }
    }
    
     public function saveService(){
         if (isset($_POST['save_service']) && !empty($_POST['save_service']) && $_POST['save_service'] == 'yes') {
            echo json_encode($this->ero->saveService());
            exit();
        }
    }
    
   public function deleteService(){
         if (isset($_POST['delete']) && !empty($_POST['delete']) && $_POST['delete'] == 'yes') {
            echo json_encode($this->ero->deleteService());
            exit();
        }
    }
    
     public function addUser(){
         if (isset($_POST['add']) && !empty($_POST['add']) && $_POST['add'] == 'yes') {
            echo json_encode($this->ero->addUser());
            exit();
        }
    }
    
     public function deleteUser() {
        if (isset($_POST['delete']) && !empty($_POST['delete']) && $_POST['delete'] == 'yes') {
            echo json_encode($this->ero->deleteUser());
            exit();
        }
    }
}
    
?>
