<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("admin/mycompany_model", "m_com");
        $this->load->model("admin/ero_model","ero");
    }

    function perms() {
        $perms['Settings'] = array('index', 'profileSetting', 'showCompanyInformation', 'showSetupCompanyInformation', 'saveCompanyInfo','saveSetupCompanyInfo', 'saveProfileInfo', 'resetPassword','showUserLists','showUserForm','addUser','deleteUser','ShowService','saveService','deleteService','savePaymentInfo','showPaymentInfo','showBankFeesInfo','saveBankingFeesInfo','checkParentEFINInfo','checkSBEFINInfo','saveCompanyInfoFromAdmin','saveProfileInfoFromAdmin','saveBankingFeesInfoFromAdmin','savePaymentInfoFromAdmin');
        return $perms;
    }

    public function index() {
        $data = array('title_page' => "Settings");
        $this->system->parse("settings/settings.htm", $data);
    }

    public function saveCompanyInfo() {
        if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
        	if (isset($_POST['sendfrom']) && !empty($_POST['sendfrom']) && $_POST['sendfrom'] == 'allEroPage') {
        		$this->m_com->saveCompany();
        		echo json_encode($this->ero->loadAllErosForAdmin());
        	}else{
            	echo json_encode($this->m_com->saveCompany());
        	}
            exit();
        }
    }

    public function saveCompanyInfoFromAdmin() {
        if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {

            echo json_encode($this->m_com->saveCompanyFromAdmin());
            exit();
        }
    }
    
    public function checkParentEFINInfo() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_com->checkParentEFINStatus());
    		exit();
    	}
    }
    
    public function checkSBEFINInfo() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_com->checkSBEFINStatus());
    		exit();
    	}
    }
    
    
    public function saveSetupCompanyInfo() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_com->saveSetupCompany());
    		exit();
    	}
    }
    
    public function saveProfileInfo() {
        if (isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes') {
            echo json_encode($this->m_com->saveProfile());
            exit();
        }
    }

    public function saveProfileInfoFromAdmin() {
        if (isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes') {
            echo json_encode($this->m_com->saveProfileFromAdmin());
            exit();
        }
    }

    public function profileSetting() {
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->m_com->loadInfor());
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('settings/profilesettings.htm', $data);
            exit();
        }
    }
    
    public function savePaymentInfo() {
    	if (isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes') {
    		echo json_encode($this->m_com->saveBankAccount());
    		exit();
    	}
    }

    public function savePaymentInfoFromAdmin() {
        if (isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes') {
            echo json_encode($this->m_com->saveBankAccountFromAdmin());
            exit();
        }
    }


    
    public function showPaymentInfo() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_com->loadInfor());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('settings/paymetnInfosettings.htm', $data);
    		exit();
    	}
    }
    
    public function saveBankingFeesInfo() {
    	if (isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes') {
    		echo json_encode($this->m_com->saveBankFees());
    		exit();
    	}
    }

    public function saveBankingFeesInfoFromAdmin() {
        if (isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes') {
            echo json_encode($this->m_com->saveBankFeesFromAdmin());
            exit();
        }
    }



    public function showBankFeesInfo() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_com->loadInfor());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('settings/bankfeesInfosettings.htm', $data);
    		exit();
    	}
    }
    

    public function showCompanyInformation() {
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->m_com->loadInfor());
        $data['states'] = $this->m_com->loadStatesList();
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('settings/companyinformation.htm', $data);
            exit();
        }
    }
    
    public function showSetupCompanyInformation() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_com->loadInfor());
        $data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('setuperoinfo/setupcompanyinformation.htm', $data);
    		exit();
    	}
    }
    
    public function showUserLists(){
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->m_com->loadEmployeeList());
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('settings/userlists.htm', $data);
            exit();
        }
    }
    
     public function showUserForm(){
        $data = array();
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('settings/userForm.htm', $data);
            exit();
        }
    }
     public function ShowService(){
        $data = array();
		$data['dataLoad'] = "dataService = " . json_encode($this->m_com->loadServiceList());
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('settings/servicelist.htm', $data);
            exit();
        }
    }
    
    public function saveService(){
         if (isset($_POST['save_service']) && !empty($_POST['save_service']) && $_POST['save_service'] == 'yes') {
            echo json_encode($this->m_com->saveService());
            exit();
        }
    }
    
    public function resetPassword() {
        if (isset($_POST['reset_p']) && !empty($_POST['reset_p']) && $_POST['reset_p'] == 'yes') {
            echo json_encode($this->m_com->resetPass());
            exit();
        }
    }
    
    public function addUser(){
    	
         if (isset($_POST['add']) && !empty($_POST['add']) && $_POST['add'] == 'yes') {
         	if ($_POST['author'] == ""){
                $check_ptin_exists = $this ->author ->check_ptin_exists($this ->input ->post('ptin'));
                if($check_ptin_exists != 'ptinexit') {
                    $checkUsername = $this->author->check_usernaem_exists($this->input->post('username'));
                    if ($checkUsername == 'nameexit') {
                        $data = "Username is already in use.";
                        echo json_encode($data);
                        exit();
                    } else {
                        echo json_encode($this->m_com->addUser());
                        exit();
                    }
                }else{
                    $data = "PTIN is already in use.";
                    echo json_encode($data);
                    exit();
                }
         	}
         	else{
            	echo json_encode($this->m_com->addUser());
            	exit();
         	}
        }
    }

    public function addUserFromAdmin(){

        if (isset($_POST['add']) && !empty($_POST['add']) && $_POST['add'] == 'yes') {
            if ($_POST['author'] == ""){
                $checkUsername =  $this ->author ->check_usernaem_exists($this ->input ->post('username'));
                if($checkUsername == 'nameexit'){
                    $data = "Username is already in use.";
                    echo json_encode($data);
                    exit();
                }else{
                    echo json_encode($this->m_com->addUserFromAdmin());
                    exit();
                }
            }
            else{
                echo json_encode($this->m_com->addUserFromAdmin());
                exit();
            }
        }
    }
    
    public function deleteUser(){
         if (isset($_POST['delete']) && !empty($_POST['delete']) && $_POST['delete'] == 'yes') {
            echo json_encode($this->m_com->deleteUser());
            exit();
        }
    }
	
	public function deleteService(){
         if (isset($_POST['delete']) && !empty($_POST['delete']) && $_POST['delete'] == 'yes') {
            echo json_encode($this->m_com->deleteService());
            exit();
        }
    }
}
