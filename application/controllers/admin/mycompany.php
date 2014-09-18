<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Mycompany extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("admin/mycompany_model","m_com");
    }
    function perms(){
            $perms['My Company'] = array('index','createUser','edit'); 
            return $perms;			
    }
    public function index(){
       $data['title_page'] = 'ERO';
       if( isset($_POST['load']) && !empty($_POST['load']) && $_POST['load'] == 'yes' ){
        	echo json_encode($this->m_com->loadList());
        	exit;
        }
        $this->system->parse("mycompany.htm",$data);
       
    }
    public function edit($uid){
        $data['title_page'] = 'ERO';
        if( isset($uid) && !empty($uid)){
        	 $data['dataLoad'] = "dataClient = ". json_encode($this->m_com->loadInfor($uid)); 	
        }
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
                echo json_encode($this->m_com->saveCompany($uid));
        	exit;
        } 
        if( isset($_POST['load_p']) && !empty($_POST['load_p']) && $_POST['load_p'] == 'yes' ){
        	echo json_encode($this->m_com->saveProfile($uid));
        	exit;
        } 
        $this->system->parse("editEro.htm",$data);
    }
    public function createUser(){
        $data['title_page'] = 'Create User';
        $this->system->parse("createuser.htm",$data);
    }
}
    
?>
