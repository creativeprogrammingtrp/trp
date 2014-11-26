<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privacypolicy extends CI_Controller{

	function __construct(){
		parent::__construct();
	}

    function index(){
		$data = array('title_page' => 'Privacy Policy');

		$data['url_base_path'] = $this->system->cleanUrl();
		$data['curPageURLServer'] = $this->system->URL_server__();

		$this->system->parse_templace('privacypolicy.htm', $data);
	}

	function perms(){
		$perms['Privacy Policy'] = array('index');
		return $perms;		
	}
}