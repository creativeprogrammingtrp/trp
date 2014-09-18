<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this ->load ->model("home_model");
	}
	
	function index()
	{
		$data = $this ->home_model ->load_content();
		$this ->system ->parse("home.htm",$data);
	}//end index function
	
	function perms()
	{
		$perms['Home'] = array('index');
		$perms['Home Settings'] = array('settings','update');
		return $perms;		
	}//end perms function
	
	function settings(){
		$data = $this ->home_model ->load_content();
		$data ['title_page'] = 'Home Settings';
		$this ->system ->parse("home_setting.htm",$data);
	}
	
	function update(){
		if($this->input->post("home_settings") && $this->input->post("home_settings") == 'yes'){
			echo $this->home_model->update_home();
			exit;
		}
	}
}//end Home class