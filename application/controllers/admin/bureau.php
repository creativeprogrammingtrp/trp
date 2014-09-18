<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Bureau extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/bureau_model","bureau");
    }
    function perms(){
        $perms['Bureau'] = array('index','showAllService');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"Service Bureau");
        $this->system->parse("accounts/bureau.htm",$data);
    }
    
     public function showAllService() {
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->bureau->showAllService());
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('accounts/showallservice.htm', $data);
            exit();
        }
    }
    
    
}
    
?>
