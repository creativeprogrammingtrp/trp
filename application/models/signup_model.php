<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup_model extends CI_Model 
{
	public function saveUserDatas()
	{
		$user_randomkey = $this ->lib ->GeneralRandomKey(20);
		$this ->db ->select("uid");
		$query = $this ->db ->get_where("users",array('ukey' => $user_randomkey));
		while( $query ->num_rows() >0)
		{
			$user_randomkey = $this ->lib ->GeneralRandomKey(20);
			$query = $this ->db ->get_where("users",array('ukey' => $user_randomkey));
		}
		$data = array
		(
			'ukey'				=> $user_randomkey,
			'name' 				=> $this ->lib ->escape($this ->input ->post("username")),
			'pass' 				=> $this ->author ->encode_password(trim($this ->input ->post("password"))),
			'mail'				=> trim($this ->input ->post("email")),
			'firstname'			=> $this ->lib ->escape($this ->input ->post("primary_first_name")),
			'lastname' 			=> $this ->lib ->escape($this ->input ->post("primary_last_name")),
			'phone'				=> $this ->lib ->escape($this ->input ->post("primary_phone")),
			'address' 			=> $this ->lib ->escape($this ->input ->post('address')),
			'city'				=> $this ->lib ->escape($this ->input ->post('city')),
			'state'				=> $this ->lib ->escape($this ->input ->post('state')),
			'zipcode'			=> $this ->lib ->escape($this ->input ->post('zipcode')),
			'country'			=> 'US',
			'created'			=> $this->lib->getTimeGMT(),
			'access'			=> $this->lib->getTimeGMT(),
			'login'				=> $this->lib->getTimeGMT(),
			'status'			=> 4,
                        'efin'              =>  $this ->lib ->escape($this ->input ->post("efin"))  
		);
		$this->session->set_userdata("customer_name",$data['firstname']." ".$data['lastname']);
		$this ->db ->insert('users', $data);
		$user_id =  $this->db->insert_id();		
		if(is_numeric($user_id) && $user_id > 0)
		{
			/*$variables_ = array(
				'!username' => $this ->lib ->escape($this ->input ->post("user_name")),
				'!password' => trim($this ->input ->post("password"))
			);
			sendmailtype(trim($this ->input ->post("email")), __no_approval_required__, $variables_);*/
			$users_roles = array(
				'uid' 	=> $user_id,
				'rid'	=> 5
			);
			$this ->db ->insert('users_roles', $users_roles);
                        $data_master_ero = array(
                            'uid' => $user_id
                         );
                        $this->db->insert('master_ero',$data_master_ero);
			return true;
		}
		return false;
	}
	
	public function getPassword(){
		$name = $this ->lib ->escape($this ->input ->post('f_username_signin'));
		$efin = $this ->lib ->escape($this ->input ->post('f_efin_signin'));
		$sql = "Select * from users where name='$name' AND efin='$efin'";
		
		$res = $this->db->query($sql);
		foreach ($res->result_array() as $row) {
			//$row["format_date"] = gmdate("m-d-Y", $row["created"]);
			$row["pass"] = $this ->author ->decode_password($row["pass"]);
			$data[] = $row;
		}
		return $data;
	}
}