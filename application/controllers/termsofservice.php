<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Termsofservice extends CI_Controller{

	function __construct(){
		parent::__construct();
	}

    function index(){
		$data = array('title_page' => 'Terms Of Service');

		$data['url_base_path'] = $this->system->cleanUrl();
		$data['curPageURLServer'] = $this->system->URL_server__();

		$this->system->parse_templace('termsofservice.htm', $data);
	}

	function perms(){
		$perms['Terms Of Service'] = array('index');
		return $perms;		
	}
}


