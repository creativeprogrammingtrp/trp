<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Benefits extends CI_Controller{
    public  function __construct() {
        parent::__construct();
    }
    function perms(){
        $perms['Benefits'] = array('index');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"Benefits");
        $this->system->parse("benefits/benefits.htm",$data);
    }
}
    
?>
