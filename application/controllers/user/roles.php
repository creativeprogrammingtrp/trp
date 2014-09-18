<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Roles extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this ->load ->model("roles_model");
	}//end __construct function
	public function perms()
	{
		$perms['Manage Roles'] = array("index");
		return $perms;
	}//end perms function
	
	public function index()
	{
		if($this ->input ->post("loadDataRoles") == 'yes')
		{
			echo json_encode($this ->roles_model ->loadDataRoles());
			exit;	
		}
		if($this ->input ->post("saveRole") == "yes")
		{
			echo json_encode($this ->roles_model ->saveRole());
			exit;
		}
		if($this ->input ->post("upRole") == "yes")
		{
			echo json_encode($this ->roles_model ->updateRole());
			exit;	
		}
		if($this ->input ->post("delRole") == 'yes')
		{
			echo json_encode($this ->roles_model ->deleteRole());	
			exit;
		}
		$this ->system ->parse("roles.htm",array("title_page" => "Roles"));
	}//end index function
	
}//end Roles class