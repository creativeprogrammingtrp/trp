<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myaccount_links extends CI_Controller {
	public function index()
	{
		$this ->system ->parse("myaccount_links.htm",array("title_page" => 'My Account'));	
	}	
	public function perms()
	{
		$perms["View my account"] = array("index");
		return $perms;
	}
}