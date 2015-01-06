<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Sb extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/ero_model","ero");
        $this->load->model("admin/mycompany_model", "m_com");
    }
    
    function perms(){
        $perms['Sb'] = array('index','ShowAllSb');
        return $perms;			
    }
    
    public function index(){
    	$data = array();
        $data['title_page'] = 'Sb';
        $officedata = $this->system->getSBOfficeInfo();

        $data['alloffice'] = $officedata['officeCombo'];
        $data['selectedCompanyName'] = $officedata['selectedOffice'];

        if($this->author->objlogin->uid != '1') {
            if ($this->author->objlogin->isemployee != 1) { // if not employee
                $this->system->parse("accounts/sb.htm", $data);
            }
        }
        /*else {
            $this->system->parse("accounts/ero_admin.htm", $data);
        }*/
    }
    
    
     public function ShowAllSb(){
        $data = array();
        $data['dataLoad'] = "dataClient = " . json_encode($this->ero->loadAllSb());
        $data['style'] = ($this->author->objlogin->role['rid'] == 5)?'style="display:none"':'';
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('accounts/allsb_for_parent.htm', $data);
            exit();
        }
    }

}
    
?>
