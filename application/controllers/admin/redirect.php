<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redirect extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('lib');
		
	}	
	
	function perms(){
		$perms['Redirect Account'] = array('Index');
		return $perms;			
	}
	
	function Index(){
		if(isset($_POST['redirect']) && $_POST['redirect'] == 'yes'){
			$this->session->sess_destroy();
		
			@session_start();
	
			if(isset($_SESSION['ses_login'])) unset($_SESSION['ses_login']);
			@session_unset();
			@session_destroy();
			echo 'Ok';
			exit;
		}
	}
}