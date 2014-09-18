<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Bankaccount extends CI_Controller{
    public  function __construct() {
        parent::__construct();
    }
    function perms(){
        $perms['Bankaccount'] = array('index');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"Bank Account");
        $this->system->parse("banking/bankaccount.htm",$data);
    }
}
    
?>
