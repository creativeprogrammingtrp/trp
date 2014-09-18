<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myaccount_model extends CI_Model 
{
	var $objUser = array();
	var $error_saveUser = '';
	var $uid = 0;
	var $tempAvatarPath = "temp/users/avatars/";
	public function __construct()
	{
		parent::__construct();
		$this ->uid = $uid = $this ->author ->objlogin ->uid;
	}//end __construct function
	public function loadUser()
	{
		$sql = " SELECT * FROM (SELECT * FROM `users` WHERE `status` = 1 and uid = '".$this ->uid."') T1 LEFT JOIN (SELECT uid as lid, legal_business_id as repid FROM representatives) T2 on T1.uid = T2.lid ";
		$query = $this ->db -> query($sql);
		return $query->row_array();
	}//end loadUser function
	
	public function loadRep()
	{
		$query = $this ->db ->get_where("representatives",array("uid" =>$this ->uid));
		return $query->row_array();
	}//end loadRep function
	
	public function checkPassword()
	{
		$password = $this ->author ->encode_password(trim($this->objUser["oldPassword"]));
		$query = $this ->db ->get_where("users",array("uid" => $this ->uid,"pass" =>$password));
		if($query ->num_rows() <=0)
		{
			return 'Old password incorrect!';	
		}
		return '';
	}//end checkPassword function
	function updatetbUser(){
		if(isset($this->objUser["oldPassword"]) && trim($this->objUser["oldPassword"])!= '')
		{
			$this ->error_saveUser = $this ->checkPassword();	
		}
		$firstname = (isset($this->objUser['first_name']) && $this->objUser['first_name'] != '')?$this ->lib ->escape($this->objUser['first_name']):'';
		$middlename = (isset($this->objUser['mi']) && $this->objUser['mi'] != '')?$this ->lib ->escape($this->objUser['mi']):'';
		$lastname = (isset($this->objUser['last_name']) && $this->objUser['last_name'] != '')?$this ->lib ->escape($this->objUser['last_name']):'';
		if($this->error_saveUser != '') return false;	
	
		$data = array(
			'mail'				=> $this ->lib ->escape($this->objUser["primary_email"]),
			'firstname'			=> $firstname,
			'lastname' 			=> $lastname,
			'middlename' 		=> $middlename,
			'phone'				=> $this ->lib ->escape($this->objUser["home_phone"]),
			'mobile'			=> $this ->lib ->escape($this->objUser['mobile']),
			'address' 			=> $this ->lib ->escape($this->objUser['street_address']),
			'city'				=> $this ->lib ->escape($this->objUser['city']),
			'country'			=> $this ->lib ->escape($this->objUser['country']),
			'state'				=> $this ->lib ->escape($this->objUser['state']),
			'zipcode'			=> $this ->lib ->escape($this->objUser['zipcode']),
		);
		if(isset($this->objUser["oldPassword"]) && trim($this->objUser["oldPassword"])!= '')
		{
			$data['pass'] = $this ->author ->encode_password(trim($this->objUser["newPassword"]));
		}
		$this ->db ->where("uid",$this ->uid);
		$this ->db ->update('users', $data);
		
	}
	
	function sendUserMail(){
		if($this->error_saveUser != '') return false;
		$variables_ = array(
			'!username' => $this ->lib ->escape($this->objUser['user_name']),
			'!password' => trim($this->objUser['newPassword'])
		);
		//sendmailtype(escape($this->objUser["primary_email"]), __new_user_created_by_administrator__, $variables_);	
	}
	
	function updatetbRepresentatives(){
		if($this->error_saveUser != '') return false;		
		$data = array(
			'legal_business_name' => ($this->objUser['legal_business_name'] && trim($this->objUser['legal_business_name']) !='')? $this ->lib ->escape($this->objUser['legal_business_name']) :'',
			'legal_business_fname' => $this ->lib ->escape($this->objUser['first_name_2']),
			'legal_business_lname' =>  $this ->lib ->escape($this->objUser['last_name_2']),
			'address' => $this ->lib ->escape($this->objUser['street_address_2']),
			'apartment_2' => $this ->lib ->escape($this->objUser['apartment_suite_floor_2']),
			'city' => $this ->lib ->escape($this->objUser['city_2']),
			'country' => $this ->lib ->escape($this->objUser['country_2']),
			'state' => $this ->lib ->escape($this->objUser['state_2']),
			'zipcode' => $this ->lib ->escape($this->objUser['zipcode_2']),	
			'phone' => $this ->lib ->escape($this->objUser['home_phone_2']),
			//'payment_preference' => $this ->lib ->escape($this->objUser['payment_preference']),
			'ssn_itin' => $this ->lib ->escape($this->objUser['ssn_itin']),
			'secondary_mail' => $this ->lib ->escape($this->objUser['secondary_email']),
			'date_of_birth' => $this->objUser['date_birth']!=""?date("Y-m-d", strtotime($this->objUser['date_birth'])):NULL,
			'apartment' => $this ->lib ->escape($this->objUser['apartment_suite_floor']),
			'same_checked'	=> $this ->lib ->escape($this->objUser['same_checked']),
			'association'	=> $this ->lib ->escape($this->objUser['association']),
		);
		
		$this ->db ->where("uid",$this ->uid);
		$this ->db ->update('representatives', $data);
	}	
	public function updateAccountRep(){
		$this->error_saveUser = '';
		if($this ->input ->post("saveUser")){
			$this->objUser = $this ->input ->post("saveUser");
			if (trim($this->lib->escape($this->objUser['ssn_itin']))!="")
			{
				$query = $this->db->query("select * from representatives where uid <> '".$this->uid."' and  ssn_itin = '".trim($this->lib->escape($this->objUser['ssn_itin']))."'");
				if ($query->num_rows()>0)
				{
					$this->error_saveUser = "SSN / Tax ID already exists.";
					return array('error' => $this->error_saveUser);
				}
			}	
			$this->updatetbUser();
			//$this->sendUserMail();
			$this->updatetbRepresentatives();
		}//0
		$this->author ->updateSes();
		return array('error' => $this->error_saveUser);
	}//end saveUser function
	
	public function loadAccountValue()
	{
		$query = $this ->db ->get_where("users",array("uid" =>$this ->author ->objlogin ->uid));
		$data = $query ->row_array();
		if(count($data) >0)
		{
			$data['varCountry']="var country='".$data['country']."';";
			$data['varState'] ="var state='".$data['state']."';";
                        $data['varCity'] = "var city ='".$data['city']."';";
			$rid = $this ->author ->objlogin ->role['rid'];
			$data['varRid'] = "rid = $rid;";	
			if($data['picture'] ==NULL || $data['picture']=='')
				$data['picture'] = "default-avatar.png";
				$data['picture'] = $this->system->URL_server__().$this->system->avatarPath.$data['picture'];	
		}
		return $data;
	}//end loadAccountValue function
	
	public function updateAccountUser()
	{
		$error = '';
		$this->objUser = $this ->input ->post("saveUser");
		if(isset($this->objUser['oldPassword']) && $this->objUser['oldPassword'] !='')
		{
			$error = $this ->checkPassword();
		}
		if($error=='')
		{
			$data = array();
			foreach($this->objUser as $key =>$val)
			{
				if($key == 'oldPassword') continue;
				elseif($key=="avatar")
					$data[$key] = $val;
				elseif($key == 'pass')
				{
					$data[$key]	 = $this ->author ->encode_password($val);
					continue;
				}
				$data[$key] = $this ->lib->escape($val)	;
			}
			if(!empty($data['avatar']) && trim($data['avatar'])!="")
			{
				$data['picture'] = '';
				if($this->deleteAvatar($this->uid))
				{
					$tempFileName = basename($data['avatar']);
					$fileExt = $this->getFileExt($tempFileName);
					$avatarName = $this->getUkeyByID($this->uid);	
						
					if($avatarName && $fileExt!= '')
					{
						$avatarName .= "_".time().$fileExt;
						if(!is_dir($this->system->avatarPath)){
							 $oldumask = umask(0);
							 @mkdir($this->system->avatarPath, 0777);
							 @umask($oldumask );
						}else{
							 $oldumask = umask(0);
							 @chmod($this->system->avatarPath, 0777);
							 @umask($oldumask );
						}
						if (file_exists($this->tempAvatarPath.$tempFileName) && copy($data['avatar'],$this->system->avatarPath . $avatarName)) {
						  unlink($this->tempAvatarPath.$tempFileName);
						}
						$data['picture'] = $avatarName;
					}
				}
			}
			unset($data['avatar']);
			$this ->db ->where("uid",$this ->uid);
			$this ->db ->update("users",$data);
		}
		return array("error" =>$error);
	}//end updateAccountUser function
	
	private function deleteAvatar($uid)
	{
		if(!is_numeric($uid) || $uid<=0)
			return false;
		$picture = $this->database->db_result("SELECT picture FROM users WHERE uid = $uid LIMIT 1");
		if(empty($picture) || !file_exists($this->system->avatarPath.$picture))
			return true;
		return unlink($this->system->avatarPath.$picture);
	}//function deleteAvatar
	
	private function getFileExt($strFileName)
	{
		return substr($strFileName,strrpos($strFileName,'.'));
	}//function getFileExt
	private function getUkeyByID($uid)
	{
		if(!is_numeric($uid) || $uid<=0)
			return false;
		return $this->database->db_result("SELECT ukey FROM users WHERE uid = $uid LIMIT 1");
	}//function updateAvatar
}//end Myaccount_model class