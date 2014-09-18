<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Submenu extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('submenu_model','submenu');
	}
	
	function perms(){
		$perms['View Sub Menu'] = array('index','mkey');
		return $perms;		
	}
	
	function index(){
		
	}
	
	function mkey($mkey=NULL){
		$this->submenu->setkey($mkey);
		$this->system->parse('SubMenus.htm', $this->submenu->parse());	
	}
}
?>