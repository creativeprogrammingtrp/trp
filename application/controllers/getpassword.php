<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getpassword extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('getpassword_model','getpass');
	}
	function perms(){
		$perms['Forgot Passwords'] = array('index','getpass');
		return $perms;		
	}
	function index(){
		if($this->author->objlogin->uid == 0){
			$this->system->parse("getpassword.htm", array("title_page" => "Forgot Password"));
			return;
		}
		$this->system->URLgoto('user/myaccount');
	}
	function getpass(){
		if(trim($this->input->post('username')) != '' && trim($this->input->post('email')) != ''){
			$this->getpass->GetPass();
		}
		return;
	}
}