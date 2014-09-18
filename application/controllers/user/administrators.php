<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrators extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this ->load ->model('administrators_model');
	}//end function __construct
	public function perms()
	{
		$perms['View Administrators Listing'] = array('index','loadDataAccounts','loadDataRoles');
		$perms['Add Account'] = array('add','saveAccount');
		$perms['Edit Account'] = array('edit','updateAccount');
		$perms['Delete Account'] = array('preDeleteAccount', 'deleteAccount');
		return $perms;	
	}//end function perms
	
	public function index()
	{
		$data = array
		(
			'keyword' =>(!empty($_GET['keyword']))?urldecode($_GET['keyword']):'',
			'status1' =>(isset($_GET['status']) && $_GET['status']==1)?'selected="selected"':'',
			'status0' =>(isset($_GET['status']) && $_GET['status']==2)?'selected="selected"':'',
			'title_page' =>'Administrator Accounts',
			'add_account' =>''
		);
		if($this->author->isAccessPerm('administrators', 'add'))
			$data['add_account'] = '<span class="btnFilter"><input type="button" class="btn btn-primary" value="Add Account" onclick="AddNewAccount()" /></span>';	
		$this ->system ->parse("admin_index.htm",$data);
	}//end function index
		
	public function loadDataRoles()
	{
		echo json_encode($this ->administrators_model ->loadRoles());
	}//end function loadDataRoles
	
	public function loadDataAccounts()
	{
		echo json_encode($this ->administrators_model ->getAdminAccounts());
	}//end function loadDataAccounts
	
	public function preDeleteAccount()
	{
		$this->system->parse_templace('delete_admin.htm', array('key' =>$this ->input ->post("accountKey")));
	}//end function preDeleteAccount
	
	public function deleteAccount()
	{
		if(!empty($_POST['accountKey']))
		{
			$this ->administrators_model ->deleteAccount();
		}
		$this ->loadDataAccounts();
	}//end deleteAccount function
	
	public function add()
	{
		if($this ->input ->post("saveUser"))
		{
			echo $this -> saveAccount($this ->input ->post("saveUser"));	
			exit;			
		}
		$this ->addAccountTemplate();	
	}//end addAccount function
	
	private function addAccountTemplate()
	{
		$data = array();
		$data['dataCountries'] = 'dataCountries = '.json_encode($this ->lib ->__loadDataCountries__());
		$data['title_page'] = 'Add Account';
		$this ->system ->parse("add_account_admin.htm",$data);
	}//end addAccountTemplate function
	
	private function saveAccount($data)
	{
		$error = '';
		if(isset($data) && is_array($data) && count($data) > 0)
		{
			$error = $this ->administrators_model ->saveAccount($data);
		}
		return json_encode(array('completed'=>"Saving completed.", 'error' => $error));
	}//end saveAccount function
	
	public function edit($key = '')
	{
		$key = $this ->lib ->escape(trim($key));
		if($this ->input ->post("saveUser"))
		{
			echo $this ->administrators_model-> updateAccount();	
			exit;
		}
		$this ->editAccountTemplate($key);
	}//end edit function
	
	private function editAccountTemplate($key)
	{
		$data = array();
		if($key!='')
		{
			$data = $this ->administrators_model ->loadAccountValue($key);	
		}
		$data['dataCountries'] = 'dataCountries = '.json_encode($this ->lib ->__loadDataCountries__());
		$data['title_page'] = 'Edit Account';
		$this ->system ->parse("edit_account_admin.htm",$data);
	}//end editAccountTemplate function
	
}//end class Administrators