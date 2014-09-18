<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manufacturer_model extends CI_Model 
{
	public function loadDataUsers()
	{
		$uid = $this ->author ->objlogin->uid;
		$modify = 'no';
		$del = 'no';
		$view = 'no';
		
		if($this ->author ->isAccessPerm('Manufacturers','view'))
		{
			$view = 'yes';
		}
		if($this ->author ->isAccessPerm('Manufacturers','edit'))
		{
			$modify = 'yes';
		}
		if($this ->author ->isAccessPerm('Manufacturers','delete'))
		{
			$del = 'yes';
		}
		$arrUsers = array();
		
		$key_word_sql = '';
		if(!empty($_POST['keyword'])){
			$key_word = urldecode($_POST['keyword']);
			$key_word = $this ->lib ->escape($key_word);
			$key_word = str_replace("  ", " ", $key_word);
			$key_word = str_replace("%", "\%", $key_word);
			$key_word = str_replace("_", "\_", $key_word);
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_sql .= " and (";
						$key_word_sql .= " users.name like '%$key%'";
						$key_word_sql .= " or users.mail like '%$key%'";
						$key_word_sql .= " or manufacturers.legal_business_id like '%$key%'";
						$key_word_sql .= " or manufacturers.legal_business_name like '%$key%'";
						$key_word_sql .= " or manufacturers.address like '%$key%'";
						$key_word_sql .= " or manufacturers.city like '%$key%'";
						$key_word_sql .= " or manufacturers.state like '%$key%'";
						$key_word_sql .= " or manufacturers.zipcode like '%$key%'";
						$key_word_sql .= " or manufacturers.phone like '%$key%'";
						$key_word_sql .= " or manufacturers.fax like '%$key%'";
						$key_word_sql .= " or manufacturers.website like '%$key%'";
						$key_word_sql .= " or manufacturers.tax like '%$key%'";
						$key_word_sql .= " or manufacturers.payment_type like '%$key%'";
						$key_word_sql .= " or manufacturers.beneficiary_bank like '%$key%'";
						$key_word_sql .= " or manufacturers.beneficiary_name like '%$key%'";
						$key_word_sql .= " or manufacturers.account_NO like '%$key%'";
						$key_word_sql .= " or manufacturers.SWIFT_CODE like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		$status_user_sql = '';
		$status =isset($_POST['status'])? urldecode($_POST['status']):'';
		if($status==2 || $status== 1){
			if($status==2) $status=0;
			$status_user_sql = " and users.status = '".$status."' ";		
		}else{
			$status_user_sql = " and users.status <> -1 ";	
		}
		
		if($this ->author ->objlogin ->role['rid'] == MANUFACTURER){
			$sql = "select manufacturers.legal_business_id,manufacturers.legal_business_name,users.mail,users.created,users.status,users.ukey,manufacturers.author ";
			$sql .= " from manufacturers join users on manufacturers.uid = users.uid WHERE 1=1 $status_user_sql $key_word_sql and manufacturers.author = $uid order by users.uid ASC";	
		}else{
			$sql = "select manufacturers.legal_business_id,manufacturers.legal_business_name,users.mail,users.created,users.status,users.ukey,manufacturers.author ";
			$sql .= " from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5) $status_user_sql $key_word_sql order by users.uid ASC";	
		}
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$row['created_str'] = date("m/d/Y", $row['created']);
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$row['view'] = $view;	
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end loadDataUsers function
	
	public function view_loadData()
	{
		$arrUsers = array();
		$dem_ = 0;
		if(isset($_POST['key']) && $_POST['key'] != ''){
			$key = trim($_POST['key']);
			$sql = "select users.ukey,users.name,users.pass,users.mail,users.status,users.created,manufacturers.* ";
			$sql .= " from manufacturers join users join users_roles on manufacturers.uid = users.uid and users.uid = users_roles.uid WHERE users_roles.rid = 5 and users.ukey = '$key' and users.status <> -1";
	
			$re = $this ->db ->query($sql);
			foreach($re ->result_array() as $row)
			{
				$id = $row['mid'];
				$row['created_str'] = date("m/d/Y", $row['created']);
				$row['current_password'] = $this ->author ->decode_password($row['pass']);
				$row['contract_start_date_str'] = date("m/d/Y", $row['contract_start_date']);
				$row['contract_end_date_str'] = date("m/d/Y", $row['contract_end_date']);
				$row['name_btype']=$this ->lib->GetNameBusinessTypeByID($row['business_type']);
				$row['name_status']=$this ->lib->GetStatusByID($row['status']);
				$row['state']= $this ->lib->GetNameStateByCode($row['state'],$row['country']);
				$row['country']= $this ->lib->GetNameCountryByCode($row['country']);
				
				$legalbusitact = array();
				$re_2 = $this ->db ->query("select * from legal_business_contact where linkID = '$id' and contactType = 2 and status <> -1");
				foreach($re_2 ->result_array() as $row_2)
				{
					$row_2['editID'] = $row_2['id'];
					$row_2['id'] = $dem_;
					$row_2['nameTittle']=$this ->lib->GetTittleNameByID($row_2['title']);
					$dem_ ++;	
					$row_2['last_update_date'] = date("m/d/Y", $row_2['last_update_date']); 
					$legalbusitact[] = $row_2;
				}
				
				$row['legalbusitact'] = $legalbusitact;
						
				$arrUsers[] = $row;
			}	
		}
		return $arrUsers;
	}//end view_loadData function
	function edit_saveUser()
	{
		$error = '';
		if(is_array($_POST['saveUser']) && count($_POST['saveUser']) > 0){
			$saveUser = $_POST['saveUser'];
                        var_dump($saveUser['key']);
			$key = (isset($saveUser['key']) && $saveUser['key'] != '')?$saveUser['key']:'';
			$uid = $this ->database ->db_result("select uid from users where ukey = '$key'");
			$legal_business_name = $this ->lib->escape($saveUser['legal_business_name']);
			if($this->checkTaxExist($this ->lib ->escape($saveUser['tax']),$uid)){
				return array('error' => 'This Tax ID is exist on system.');	
			}
			$firstname = '';
			$lastname = '';
			
			if($legal_business_name != ''){
				$arr_name = explode(" ", $legal_business_name);
				if(isset($arr_name[0]) && $arr_name[0] != '') $firstname = $arr_name[0];
				for($i = 1; $i < count($arr_name); $i++){
					if(isset($arr_name[$i]) && $arr_name[$i] != '') $lastname .= $arr_name[$i].' ';	
				}
				if($lastname != '') $lastname = substr($lastname, 0, strlen($lastname)-1);			
			}
			
			$data = array(
				'mail'				=>  $this ->lib->escape($saveUser["mail"]),
				'firstname'			=> $this ->lib->escape($saveUser["firstname"]),
				'lastname' 			=> $this ->lib->escape($saveUser["lastname"]),
                                'middlename' 			=> $this ->lib->escape($saveUser["middlename"]),
				'phone'				=> $this ->lib->escape($saveUser["phone"]),
				'address' 			=> $this ->lib->escape($saveUser['address']),
				'city'				=> $this ->lib->escape($saveUser['city']),
				'state'				=> $this ->lib->escape($saveUser['state']),
				'zipcode'			=> $this ->lib->escape($saveUser['zipcode']),
				'status'			=> $saveUser['status']
			);
			/*if(isset($saveUser["password"]) && $saveUser["password"] != ''){
				$data['pass'] = $this ->author->encode_password(trim($saveUser["password"]));
				$pass = $data['pass'];
			}*/
			if(isset($saveUser["name"]) && trim($saveUser["name"]) != ''){
				$name = $this ->lib->escape(trim($saveUser["name"]));
				if($this ->lib->check_name_exists_2($name, $key) > 0){
					$error = _error_name_exists_;	
					return array('error' => $error);	
				}else{
					$data['name'] = $name;	
				}
			}
			$this ->db ->where('ukey',$key);
			$this ->db ->update('users', $data);
			
			if(is_numeric($uid) && $uid > 0){
				
				$contract_start_date = 0;
				if(isset($saveUser['contract_start_date']) && trim($saveUser['contract_start_date']) != ''){
					$contract_start_date = 	strtotime($saveUser['contract_start_date']);
					if(!is_numeric($contract_start_date)) $contract_start_date = 0;
				}
				
				$contract_end_date = 0;
				if(isset($saveUser['contract_end_date']) && trim($saveUser['contract_end_date']) != ''){
					$contract_end_date = strtotime($saveUser['contract_end_date']);
					if(!is_numeric($contract_end_date)) $contract_end_date = 0;
				}
				
				$manufacturers = array(
                                    'legal_business_name' => $legal_business_name,
                                    'address' => $this ->lib ->escape($saveUser['address_business']),
                                    'city' => $this ->lib ->escape($saveUser['city_business']),
                                    'country' => '',
                                    'state' => $this ->lib ->escape($saveUser['state_business']),
                                    'zipcode' => $this ->lib ->escape($saveUser['zipcode_business']),   
                                    'phone' => '',
                                    'fax' => '',
                                    'website' => '',
                                    'business_type' => '',
                                    'tax' => $this ->lib ->escape($saveUser['tax']),
                                    'contract_file' => '',
                                    'payment_type' => '',
                                    'beneficiary_bank' => '',
                                    'beneficiary_name' => $this ->lib ->escape($saveUser['beneficiary_name']),
                                    'account_NO' => $this ->lib ->escape($saveUser['account_NO']),
                                    'SWIFT_CODE' => $this ->lib ->escape($saveUser['SWIFT_CODE'])
                    
				);
				$this ->db ->where('uid',$uid);
				$this ->db->update('manufacturers', $manufacturers);
				$mid = $this ->database->db_result("select mid from manufacturers where uid = '$uid'");
			
				if(is_numeric($mid) && $mid > 0){
					$mrid = $this ->database->db_result("select mrid from manufacturers_restocking_reminder where mid = $mid");
					if(is_numeric($mrid) && $mrid > 0){
						$this ->db ->where('mrid',$mrid);
						$this ->db->update('manufacturers_restocking_reminder', array("email_list" =>$this ->lib ->escape($saveUser['restocking_reminder_list']),'mid'=>$mid));
					}
					else{
						$this ->db->insert('manufacturers_restocking_reminder', array("email_list" =>$this ->lib ->escape($saveUser['restocking_reminder_list']),'mid'=>$mid));		
					}
					$arr_legalbusitact = array();
					$re_3 = $this ->db->query("select id from legal_business_contact where linkID = '$mid' and contactType = 2");
					foreach($re_3 ->result_array() as $row_3)
					{
						$arr_legalbusitact[] = $row_3['id'];	
					}
					$legal_business_contact = array(
                                            'linkID' => $mid,
                                            'contactType' => 2,
                                            'gender' => '',
                                            'title' => '',
                                            'first_name' => '',
                                            'last_name' => '',
                                            'middle_name' => '',
                                            'email' => '',
                                            'mobile' => '',
                                            'address_1' => $this ->lib ->escape($saveUser['address_1']),
                                            'address_2' => $this ->lib ->escape($saveUser['address_2']),
                                            'city' => $this ->lib ->escape($saveUser['city_mail']),
                                            'state' => $this ->lib ->escape($saveUser['state_mail']),
                                            'zipcode' => $this ->lib ->escape($saveUser['zipcode_mail']),
                                            'last_update_date' => strtotime(gmdate("Y-m-d H:i:s")),
                                            'note' => ''
                                        );
                                        $this ->db ->update('legal_business_contact', $legal_business_contact);
                                        
					if($error == ''){
						if(isset($saveUser['accessUser']) && is_array($saveUser['accessUser'])){
							$this ->db ->query("delete from access_users where uid = $uid");
							$data_access = array(
								'uid' => $uid,
								'perm' => serialize($saveUser['accessUser'])
							);
							$this ->db ->insert('access_users', $data_access);		
						}	
					}
				}else{
					$error = 'Can not insert manufacturers';	
				}
			}else{
				$error = 'Can not insert User';		
			}			
		}
		return array('error' => $error);
	}
	
	public function edit_loadData()
	{
		$arrUsers = array();
		$dem_ = 0;
		if(isset($_POST['key']) && $_POST['key'] != ''){
			$key = trim($_POST['key']);
			$sql = "select users.ukey,users.name,users.pass,users.mail,users.status,users.created,users.firstname,users.lastname,users.middlename,users.address as user_address,users.city as user_city,users.state as user_state,users.zipcode as user_zipcode,users.phone as home_phone,users.mobile as user_mobile,manufacturers.* ";
			$sql .= " from manufacturers join users join users_roles on manufacturers.uid = users.uid and users.uid = users_roles.uid WHERE users_roles.rid = 5 and users.ukey = '$key' and users.status <> -1";
	
			$re = $this ->db ->query($sql);  
			foreach($re ->result_array() as $row)
			{
				$id = $row['mid'];
				$row['created_str'] = date("m/d/Y", $row['created']);
				$row['current_password'] = $this ->author ->decode_password($row['pass']);
				$row['contract_start_date_str'] = ($row['contract_start_date'] != null && $row['contract_start_date'] > 0)?date("m/d/Y", $row['contract_start_date']):'';
				$row['contract_end_date_str'] = ($row['contract_end_date'] != null && $row['contract_end_date'] > 0)?date("m/d/Y", $row['contract_end_date']):'';
				
				$legalbusitact = array();
				$re_2 = $this ->db ->query("select * from legal_business_contact where linkID = '$id' and contactType = 2 and status <> -1");	
				foreach($re_2 ->result_array() as $row_2)
				{
					$row_2['editID'] = $row_2['id'];
					$row_2['id'] = $dem_;
					$dem_ ++;	
					$row_2['last_update_date'] = date("m/d/Y", $row_2['last_update_date']); 
					$legalbusitact[] = $row_2;
				}
				$re_3 = $this ->db ->query("select email_list from manufacturers_restocking_reminder where mid = $id");
				foreach($re_3 ->result_array() as $row_3)
				{
					$row['restocking_reminder_list'] = $row_3['email_list'];	
				}
				if(!isset($row['restocking_reminder_list'])) $row['restocking_reminder_list']='';
				$row['legalbusitact'] = $legalbusitact;
				$data_xml = array();
				if($row['data_xml'] != null && $row['data_xml'] != ''){
					$data_xml = unserialize($row['data_xml']);	
				}
				$row['data_xml'] = $data_xml;
				$arrUsers[] = $row;
			}
		}
		return $arrUsers;
	}
	
	public function em_loadDataUsers()
	{
		$rol = 0;
		$ukey = isset($_POST['ukey'])?$_POST['ukey']:'';
		if($ukey != ''){
			$uid = $this ->database->db_result("select uid from users where ukey = '$ukey'");	
			$rol = 5;
		}
		
		$modify = 'no';
		$del = 'no';
		$view = 'no';
		
		if($this ->author ->isAccessPerm('Manufacturers','view'))
		{
			$view = 'yes';	
		}
		if($this ->author ->isAccessPerm('Manufacturers','edit'))
		{
			$modify = 'yes';	
		}
		if($this ->author ->isAccessPerm('Manufacturers','delete'))
		{
			$del = 'yes';	
		}
		$arrUsers = array();
		
		$key_word_sql = '';
		if(isset($_POST['key_word']) && trim($_POST['key_word']) != ''){
			$key_word = $this ->lib ->escape($_POST['key_word']);
			$key_word = str_replace("  ", " ", $key_word);
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_sql .= " and (";
						$key_word_sql .= " users.name like '%$key%'";
						$key_word_sql .= " or users.mail like '%$key%'";
						$key_word_sql .= " or manufacturers.legal_business_id like '%$key%'";
						$key_word_sql .= " or manufacturers.legal_business_name like '%$key%'";
						$key_word_sql .= " or manufacturers.address like '%$key%'";
						$key_word_sql .= " or manufacturers.city like '%$key%'";
						$key_word_sql .= " or manufacturers.state like '%$key%'";
						$key_word_sql .= " or manufacturers.zipcode like '%$key%'";
						$key_word_sql .= " or manufacturers.phone like '%$key%'";
						$key_word_sql .= " or manufacturers.fax like '%$key%'";
						$key_word_sql .= " or manufacturers.website like '%$key%'";
						$key_word_sql .= " or manufacturers.tax like '%$key%'";
						$key_word_sql .= " or manufacturers.payment_type like '%$key%'";
						$key_word_sql .= " or manufacturers.beneficiary_bank like '%$key%'";
						$key_word_sql .= " or manufacturers.beneficiary_name like '%$key%'";
						$key_word_sql .= " or manufacturers.account_NO like '%$key%'";
						$key_word_sql .= " or manufacturers.SWIFT_CODE like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		$status_user_sql = '';
		if(isset($_POST['status_user']) && $_POST['status_user'] != ''){
			$status_user_sql = " and users.status = '".$_POST['status_user']."' ";		
		}else{
			$status_user_sql = " and users.status <> -1 ";	
		}
		if($this ->author ->objlogin ->role['rid'] == MANUFACTURER || $rol == 5){
			$sql = "select manufacturers.legal_business_id,manufacturers.legal_business_name,users.mail,users.created,users.status,users.ukey,manufacturers.author ";
			$sql .= " from manufacturers join users on manufacturers.uid = users.uid WHERE 1=1 $status_user_sql $key_word_sql and manufacturers.author = $uid order by users.uid ASC";	
		}else{
			$sql = "select manufacturers.legal_business_id,manufacturers.legal_business_name,users.mail,users.created,users.status,users.ukey,manufacturers.author ";
			$sql .= " from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5) $status_user_sql $key_word_sql order by users.uid ASC";	
		}
		$re = $this ->db ->query($sql);
		foreach($re->result_array() as $row)
		{
			$row['created_str'] = date("m/d/Y", $row['created']);
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$row['view'] = $view;	
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end em_loadDataUsers function
	
	function add_saveUser()
	{
		$error = '';
		if(is_array($_POST['saveUser']) && count($_POST['saveUser']) > 0){	//0
			$saveUser = $_POST['saveUser'];
			$user_randomkey = 'MA'.$this ->lib ->GeneralRandomNumberKey(12);
			$re = $this ->db ->query("select uid from users where ukey = '$user_randomkey'");
			foreach($re->result_array() as $row)
			{
				$user_randomkey = 'MA'.$this ->lib ->GeneralRandomNumberKey(12);
				$re = $this ->db ->query("select uid from users where ukey = '$user_randomkey'");	
			}
			
			$legal_business_id = '';
			$legal_business_name = $this ->lib ->escape($saveUser['legal_business_name']);
			/*if($legal_business_name != ''){
				$arr_name = explode(" ", $legal_business_name);
				if(isset($arr_name[0]) && $arr_name[0] != '') $firstname = $arr_name[0];
				for($i = 1; $i < count($arr_name); $i++){
					if(isset($arr_name[$i]) && $arr_name[$i] != '') $lastname .= $arr_name[$i].' ';	
				}
				if($lastname != '') $lastname = substr($lastname, 0, strlen($lastname)-1);
				if(count($arr_name) > 0){
					foreach($arr_name as $name_key){
						if($name_key != ''){
							$legal_business_id .= strtoupper(substr($name_key, 0, 1));	
						}	
					}	
				}			
			}*/
			
			if($this ->lib ->check_name_exists($this ->lib ->escape($saveUser["name"])) > 0){
				$error = _error_name_exists_;	
			}
			if($this->checkTaxExist($this ->lib ->escape($saveUser['tax']), 0)){
				$error = 'This Tax ID is exist on system.';	
			}
			if($error == ''){//1
				$data = array(
					'ukey'				=> $user_randomkey,
					'name' 				=> $this ->lib ->escape($saveUser["name"]),
					'pass' 				=> $this ->author ->encode_password(trim($saveUser["pass"])),
					'mail'				=> $this ->lib ->escape($saveUser["mail"]),
					'firstname'			=> $this ->lib ->escape($saveUser["firstname"]),
					'lastname' 			=> $this ->lib ->escape($saveUser["lastname"]),
					'phone'				=> $this ->lib ->escape($saveUser["phone"]),
					'address' 			=> $this ->lib ->escape($saveUser['address']),
					'city'				=> $this ->lib ->escape($saveUser['city']),
					'country' 			=> '',			
					'state'				=> $this ->lib ->escape($saveUser['state']),
					'zipcode'			=> $this ->lib ->escape($saveUser['zipcode']),
					'created'			=> strtotime(gmdate("Y-m-d H:i:s")),
					'access'			=> strtotime(gmdate("Y-m-d H:i:s")),
					'login'				=> strtotime(gmdate("Y-m-d H:i:s")),
					'status'			=> $saveUser['status']
				);
				$this ->db ->insert('users', $data);
				$uid = $this ->db ->insert_id();
				if(is_numeric($uid) && $uid > 0){//2
					/*$variables_ = array(
						'!username' => $this ->lib ->escape($saveUser['name']),
						'!password' => trim($saveUser['password'])
					);
					$this ->lib ->sendmailtype($this ->lib ->escape($saveUser["mail"]), __new_user_created_by_administrator__, $variables_);*/
					
					$users_roles = array(
						'uid' 	=> $uid,
						'rid'	=> 5
					);
					$this ->db ->insert('users_roles', $users_roles);
					
					$legal_business_id = $this ->lib ->GeneralRandomNumberKey(8);
					$re = $this ->db ->query("select uid from manufacturers where legal_business_id = '$legal_business_id'");
					foreach($re ->result_array() as $row)
					{
						$legal_business_id = $this ->lib ->GeneralRandomNumberKey(8);
						$re = $this ->db ->query("select uid from manufacturers where legal_business_id = '$legal_business_id'");
					}
					
					$manufacturers = array(
						'uid' => $uid,
						'author' => $this ->author ->objlogin->uid,
						'legal_business_name' => $legal_business_name,
						'legal_business_id' => $legal_business_id,
						'address' => $this ->lib ->escape($saveUser['address_business']),
						'city' => $this ->lib ->escape($saveUser['city_business']),
						'country' => '',
						'state' => $this ->lib ->escape($saveUser['state_business']),
						'zipcode' => $this ->lib ->escape($saveUser['zipcode_business']),	
						'phone' => '',
						'fax' => '',
						'website' => '',
						'business_type' => '',
						'tax' => $this ->lib ->escape($saveUser['tax']),
						'contract_file' => '',
						'payment_type' => '',
						'beneficiary_bank' => '',
						'beneficiary_name' => $this ->lib ->escape($saveUser['beneficiary_name']),
						'account_NO' => $this ->lib ->escape($saveUser['account_NO']),
						'SWIFT_CODE' => $this ->lib ->escape($saveUser['SWIFT_CODE'])
					);
					if(isset($saveUser['contract_start_date']) && $saveUser['contract_start_date'] != ''){
						$contract_start_date = 	strtotime($saveUser['contract_start_date']);
						if(is_numeric($contract_start_date)) $manufacturers['contract_start_date'] = $contract_start_date;
					}
					if( isset($saveUser['contract_end_date']) && $saveUser['contract_end_date'] != ''){
						$contract_end_date = 	strtotime($saveUser['contract_end_date']);
						if(is_numeric($contract_end_date)) $manufacturers['contract_end_date'] = $contract_end_date;
					}
					$this ->db->insert('manufacturers', $manufacturers);
					$mid = $this ->db ->insert_id();
					if(is_numeric($mid) && $mid > 0){
						//if(isset($saveUser['legalbusitact']) && is_array($saveUser['legalbusitact']) && count($saveUser['legalbusitact']) > 0){
							//foreach($saveUser['legalbusitact'] as $legal_contact){
								$legal_business_contact = array(
									'linkID' => $mid,
									'contactType' => 2,
									'gender' => '',
									'title' => '',
									'first_name' => '',
									'last_name' => '',
									'middle_name' => '',
									'email' => '',
									'mobile' => '',
                                                                        'address_1' => $this ->lib ->escape($saveUser['address_1']),
                                                                        'address_2' => $this ->lib ->escape($saveUser['address_2']),
                                                                        'city' => $this ->lib ->escape($saveUser['city_mail']),
                                                                        'state' => $this ->lib ->escape($saveUser['state_mail']),
                                                                        'zipcode' => $this ->lib ->escape($saveUser['zipcode_mail']),
									'last_update_date' => strtotime(gmdate("Y-m-d H:i:s")),
									'note' => ''
								);
                                         
								$this ->db ->insert('legal_business_contact', $legal_business_contact);
								$leg = $this ->db ->insert_id();
								if(!is_numeric($leg) || $leg < 0){
									$error = 'Can not insert to legal business contact';
									$this ->db ->query("delete from users where uid = '$uid'");
									$this ->db ->query("delete from users_roles where uid = '$uid' and rid = 5");
									$this ->db ->query("delete from manufacturers where mid = '$mid'");	
									break;	
								}	
							//}	
						//}
						if($error == ''){
							if(isset($saveUser['accessUser']) && is_array($saveUser['accessUser'])){
								$this ->db ->query("delete from access_users where uid = $uid");
								$data_access = array(
									'uid' => $uid,
									'perm' => serialize($saveUser['accessUser'])
								);
								$this ->db ->insert('access_users', $data_access);		
							}	
						}
					}else{
						$error = 'Can not insert manufacturers';
						$this ->db ->query("delete from users where uid = '$uid'");
						$this ->db ->query("delete from users_roles where uid = '$uid' and rid = 5");		
					}
				}else{
					$error = 'Can not insert User';		
				}
			}//1
			
		}//0
		return array('error' => $error);
	}//end add_saveUser function
	
	private function checkTaxExist($tax,$userID){
		if(trim($tax) != ''){
			$legalID = $this->database->db_result("select legal_business_id from manufacturers where uid = '".$userID."'");
			$re = $this->db->query("select manufacturers.tax from manufacturers join users on manufacturers.uid = users.uid where users.status = 1 and manufacturers.legal_business_id <> '".$legalID."' and manufacturers.tax = '".$tax."'");
			if($re->num_rows() > 0) return true;
			$re = $this->db->query("select charities.tax from charities join users on charities.uid = users.uid where users.status = 1 and charities.legal_business_id <> '".$legalID."' and charities.tax = '".$tax."'" );
			if($re->num_rows() > 0) return true;
		}
		return false;
	}
	
	public function delete_client()
	{
		$cid = $this ->lib ->escape($_POST['cid']);
		$this ->db ->where('ukey',$cid);
		$this ->db ->update("users", array("status"=>-1));
	
		$re = $this ->db ->query("select name,pass,mail from users where ukey = '$cid'");
		if($re->num_rows() >0)
		{
			$row = $re->row_array();
			$variables_ = array(
				'!username' => $row['name'],
				'!password' => $this ->author ->decode_password($row['pass'])
			);
			$this ->lib ->sendmailtype($row['mail'], __account_deleted__, $variables_);		
		}
		return json_encode($this ->loadDataUsers());
	}//end delete_client function
	
	public function getObjItem()
	{
		$objItem = array();
		if($this->author->objlogin ->role['rid'] == MANUFACTURER){
			$sql = "select manufacturers.legal_business_id,manufacturers.legal_business_name,users.mail,users.created,users.status,users.ukey,manufacturers.author ";
			$sql .= " from manufacturers join users on manufacturers.uid = users.uid WHERE manufacturers.author = $uid order by users.uid ASC";	
		}else{
			$sql = "select manufacturers.legal_business_id,manufacturers.legal_business_name,users.mail,users.created,users.status,users.ukey,manufacturers.author ";
			$sql .= " from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5) order by users.uid ASC";	
		}
		$re = $this ->db->query($sql);
		foreach($re->result_array() as $row)
		{
			$row['created_str'] = date("m/d/Y", $row['created']);
	
			if($row['status'] == 0) $row['status'] = 'Block';
			else $row['status'] = 'Active';
			$objItem[] = $row;
		}
		return $objItem;
	}//end getObjItem function 
	
}//end Manufacture_model class