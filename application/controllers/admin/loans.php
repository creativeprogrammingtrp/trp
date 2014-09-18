<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Loans extends CI_Controller{
    public  function __construct() {
        parent::__construct();
    }
    function perms(){
        $perms['Loans'] = array('index');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"Loans");
        $this->system->parse("banking/loans.htm",$data);
    }
}
    
?>
