<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Bankapp extends CI_Controller{
    public  function __construct() {
        parent::__construct();
    }
    function perms(){
        $perms['Bank App'] = array('index');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"Bank Apps");
        $this->system->parse("banking/bankapp.htm",$data);
    }
}
    
?>
