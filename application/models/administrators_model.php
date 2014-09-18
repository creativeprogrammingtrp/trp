<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrators_model extends CI_Model 
{
	public function loadRoles()
	{
		$query = $this ->db ->query("SELECT rid, name FROM roles WHERE rid IN (3, 4) ORDER BY rid");
		return $query ->result_array();
	}//end function loadRoles
	
	public function getAdminAccounts()
	{
		$arrUsers = array();
		$query = $this ->db ->query ("SELECT rid,uid FROM users_roles WHERE rid IN (3,4) ORDER BY uid");
		foreach($query ->result_array() as $userRole)
		{
			if(!$userRole['rid']) 
				continue;
			$key_word = (!empty($_POST['keyword']))?urldecode($_POST['keyword']):'';
			$key_word = $this ->lib ->escape($this->lib->replaceSpecChar($key_word));
			
			$status = (!empty($_POST['status']))?urlencode($_POST['status']):'';
			
			if(is_numeric($status)){
				if($status==2) $status=0;
				$status_sql = "and status = ".$status;
			}
			else{
				$status_sql = "and status <> -1 ";
			}
			$arr_key = explode(" ", $key_word);
			$key_word_sql = '';
			if(count($arr_key) > 0)
			{
				foreach($arr_key as $key)
				{
					if($key != '')
					{
						$key_word_sql .= " and (";
						$key_word_sql .= " name like '%$key%'";
						$key_word_sql .= " or mail like '%$key%'";
						$key_word_sql .= " or ukey like '%$key%'";
						$key_word_sql .= " or firstname like '%$key%'";
						$key_word_sql .= " or lastname like '%$key%'";
						$key_word_sql .= " or phone like '%$key%'";
						$key_word_sql .= " or mobile like '%$key%'";
						$key_word_sql .= " or address like '%$key%'";
						$key_word_sql .= " or city like '%$key%'";
						$key_word_sql .= " or state like '%$key%'";
						$key_word_sql .= " or zipcode like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}//foreach	
			}
			
			$where = 'uid = '.$userRole['uid'].' '.$status_sql.' '.$key_word_sql;
			$subQuery = $this ->db ->query('SELECT uid, ukey, name, mail,status FROM users WHERE '.$where.' ORDER BY uid ASC');
			foreach($subQuery ->result_array() as $user)
			{
				$user['rid'] = $userRole['rid'];
				$user['type'] = ($user['uid'] == $this ->author ->objlogin->uid)? 1:0;
				$arrUsers[] = $user;
			}//foreach
		}
		unset($userRole,$user,$query,$subQuery);
		return $arrUsers;		
	}//end function getAdminAccounts
	
	public function deleteAccount()
	{
		if(empty($_POST['accountKey'])) return false;
		$key = $this->lib->escape($_POST['accountKey']);
		$this ->db ->query ("UPDATE users SET status = -1 WHERE ukey = '".$key."'");
		
		$query = $this ->db ->query("SELECT name, pass, mail FROM users WHERE ukey ='".$key."'");
		if(count($row = $query ->row_array())>0)
		{
			$variables = array(
				'!username' => $row['name'],
				'!password' => $this ->author ->decode_password($row['pass'])
			);
			$mailtype = __account_deleted__;
			//$this ->lib ->sendmailtype($row['mail'], $mailtype, $variables);
		}
	}//end function deleteAccount
	
	public function saveAccount($data)
	{		
		$error = '';
		if (($this ->author ->check_name_exists($data["name"])) > 0) $error = _error_name_exists_;
		
		if($error == '')
		{
			if(isset($data['rid']) && is_numeric($data['rid']) && $data['rid'] > 0)
			{
				$user_randomkey = $this ->lib ->GeneralRandomKey(20);
				$this -> db ->select("uid");
				$query = $this ->db ->get_where("users",array("ukey" =>$user_randomkey));
				while($query->num_rows() > 0)
				{
					$user_randomkey = $this ->lib ->GeneralRandomKey(20);
					$query = $this ->db ->get_where("users",array("ukey" =>$user_randomkey));
				}
				$rid = $data['rid'];
				unset($data['rid']);
				$data['ukey'] = $user_randomkey;
				if(isset($data['pass']) && $data['pass'] != '')
				{
					$data['pass']	= $this ->author ->encode_password($data['pass']);	
				}
				$data['created'] = $this->lib->getTimeGMT();
				$data['access'] = $this->lib->getTimeGMT();
				$this ->db ->insert("users", $data);
				$uid = $this->db->insert_id();		
				$this ->db ->delete("users_roles",array("uid" =>$uid));
				$this ->db ->insert("users_roles", array('uid'=>$uid, 'rid'=>$rid));					
				if(is_numeric($uid) && $uid > 0)
				{
					$variables_ = array(
						'!username' => $data['name'],
						'!password' => $this ->author ->decode_password($data['pass'])
					);
					//sendmailtype($data['mail'], __new_user_created_by_administrator__, $variables_);
				}else
				{
					$error = _error_cannot_insert_db_;	
				}			
			}
		}
		return $error;	
	}//end saveAccount function
	
	public function loadAccountValue($key)
	{
		$data = array();
		$query = $this ->db ->get_where("users",array("ukey"=>$key,"status !=" =>-1));
		if($query ->num_rows() >0)
		{
			$rid = 0;
			$row = $query ->row_array();
			$data['key'] = $key;
			$data['firstname'] = $row['firstname'];
			$data['lastname'] = $row['lastname'];
			$data['phone'] = $row['phone'];
			$data['address'] = $row['address'];	
			$data['varcity']="var city='".$row['city']."';";
			$data['varCountry']="var country='".$row['country']."';";
			$data['varState'] ="var state='".$row['state']."';";
			$data['zipcode'] = $row['zipcode'];
			if($row['status'] == 1) 
			{
				$data['check_1'] = 'checked="checked"';
			}
			else 
			{
				$data['check_0'] = 'checked="checked"';
			}
			$data['user_name'] = $row['name'];
			$data['mail'] = $row['mail'];
			$this ->db ->select("rid");
			$subQuery = $this ->db ->get_where("users_roles",array("uid"=>$row['uid']));
			if($subQuery ->num_rows()>0) 
			{
				$ridRow = $subQuery ->row_array();
				$rid = $ridRow['rid'];	
			}
			$data['varRid'] = "rid = $rid;";
		}
		return $data;
	}//end loadAccountValue function
	
	public function updateAccount()
	{
		$data = $this ->input ->post("saveUser");
		$ukey = (isset($data['ukey']))?$this ->lib ->escape($data['ukey']):'';
		$query = $this ->db ->get_where("users",array("ukey" =>$ukey));
		if($query ->num_rows() <=0)
		{
			return json_encode(array("error" =>'unspecified user'));
		}
		$row = $query ->row_array();
		if(isset($data['name']) && $data['name'] !=$row['name'])
		{
			$exist = $this ->author ->check_name_exists($this ->lib->escape($data['name']));	
			if($exist>0)
			{
				return json_encode(array("error" =>'username exist'));
			} 
		}
		$this ->db ->where("uid",$row['uid']);
		$this ->db ->update("users_roles", array('rid'=>$data['rid']));
		unset($data['rid']);
		if(trim($data['pass']) == '') unset($data['pass']);
		if(isset($data['pass']))
		{
			$data['pass']	= $this ->author ->encode_password($data['pass']);
		}
		$this ->db ->where("ukey",$ukey);
		$this->db->update('users', $data); 
		return json_encode(array("error" =>''));
	}//end updateAccount
	
}//end class administrators_model