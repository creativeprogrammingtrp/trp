<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trpadmin extends CI_Controller{
	var $name = '';
	var $pass = '';
    var $efin = '';
	function __construct(){
		parent::__construct();
	}
	function index(){
		$data = array(
			'title_page' => 'Admin Login'
		);
		$this->system->parse('trpadminLogin.htm', $data,false,'trpadminLogin.htm');
	}
	
	function checklogin(){
                $this->efin 	= $this->lib->escape($_POST["e"]);
		$this->name 	= $this->lib->escape($_POST["u"]);
		$this->pass 	= $this->lib->escape($_POST["p"]);
                
		$page = 'no';      
		if($this->author->checkLogin($this->efin,$this->name, $this->pass)){
			$this->session->set_userdata('sess_login', $this->author->objlogin);    
                $page = "OK";
		}
		echo $page;	
	}

	function perms(){
		$perms['TRP Admin Login Site'] = array('index', 'checklogin');
		return $perms;		
	}
}