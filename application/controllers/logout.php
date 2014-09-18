<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller{

	function __construct(){
		parent::__construct();
	}
	function index(){
		$this->session->sess_destroy();
		@session_start();
		if(isset($_SESSION['sess_login'])) unset($_SESSION['ses_login']);
		@session_unset();
		@session_destroy();		
		header('Location: '.$this->system->URL_server__());	
	}
	function perms(){
		$perms['Log Out Site'] = array('index');
		return $perms;		
	}
        
}