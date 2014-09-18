<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signin extends CI_Controller{
	var $name = '';
	var $pass = '';
    var $efin = '';
	function __construct(){
		parent::__construct();
	}
	function index(){
		$data = array(
			'title_page' => 'Sign In'
		);
		$data['url_base_path'] = $this->system->cleanUrl();
		$data['curPageURLServer'] = $this->system->URL_server__();
		$this->system->parse_templace('login.htm', $data);
		//$this->system->parse('login.htm', $data);
	}
	
	function perms(){
		$perms['Sign In'] = array('index');
		return $perms;		
	}
}