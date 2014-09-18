<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myaccount extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this ->load ->model("myaccount_model");
	}//end __construct function
	
	public function perms()
	{
		$perms['Edit My Account'] = array("index","takephoto");
		return $perms;
	}//end perms function
	
	public function index()
	{
		$role = $this ->author ->objlogin ->role['rid'];
		switch($role)
		{
			case Sale_Representatives:
				$this ->myaccount_rep();
				break;
			default:
				$this ->myaccount_user();
				break;
		}//switch
	}//end index function
	
	private function myaccount_rep()
	{
		
		if($this ->input ->post("check_pass"))
		{
			echo json_encode($this->myaccount_model->checkPassword());
			exit;	
		}
		if($this ->input ->post("saveUser"))
		{
			echo json_encode($this->myaccount_model->updateAccountRep());
			exit;	
		}
		
		$data = $this->myaccount_model->loadUser();
		$rep = $this ->myaccount_model ->loadRep();
		$same_checked = isset($rep['same_checked']) && $rep['same_checked'] ==1 ? $same_checked = 'checked=\"checked\"':'';
		$data['same_checked'] = $same_checked;
		foreach($rep as $key =>$val)
		{
			$key = 'rep_'.$key;
			$data[$key] = $val;
		}//foreach
		if(isset($data['rep_date_of_birth']))
		{			
			$data['rep_date_of_birth'] = date("m/d/Y",strtotime($data['rep_date_of_birth']));	
		}
		$data ['dataCountries'] = 'dataCountries='.json_encode($this ->lib ->__loadDataCountries2__()).';';
		$data['currentYear'] = gmdate("Y");
		$data['title_page'] = "My Profile";
		$this ->system ->parse("myaccount_rep.htm",$data);
		
	}//end myaccount_rep function
	
	private function myaccount_user()
	{
		if($this ->input ->post("saveUser"))
		{
			echo json_encode($this ->myaccount_model->updateAccountUser());
			exit;
		}
		$data = $this ->myaccount_model ->loadAccountValue();
		$data['title_page'] = 'User Account';
		$data['dataCountries'] = 'dataCountries = '.json_encode($this ->lib ->__loadDataCountries2__());
		$this ->system->parse("myaccount_user.htm",$data);
	}//end myaccount_user function
	
	public function takephoto()
	{
		$file_path = 'temp/users/avatars/';
		$filename = date('YmdHis') . '.jpg';
		
		if(!is_dir($file_path)){
			 $oldumask = umask(0);
			 @mkdir($file_path, 0777);
			 @umask($oldumask );
		}else{
			 $oldumask = umask(0);
			 @chmod($file_path, 0777);
			 @umask($oldumask );
		}	
		$result = file_put_contents($file_path.$filename, file_get_contents('php://input') );
		if (!$result) {
			print "ERROR: Failed to write data to $filename, check permissions\n";
			exit();
		}
		$url = $this->system->URL_server__(). $file_path.$filename;
		echo "$url\n";

	}//function takephoto
	
}//end myaccount class