<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charity_manage_model extends CI_Model 
{
	public function loadDataUsers()
	{
		$modify = 'no';
		$del = 'no';
		$view = 'no';
		if($this ->author ->isAccessPerm('CharityManage','view'))
		{
			$view = 'yes';	
		}
		if($this ->author ->isAccessPerm('CharityManage','edit'))
		{
			$modify = 'yes';	
		}
		if($this ->author ->isAccessPerm('CharityManage','delete'))
		{
			$del = 'yes';	
		}
		$arrUsers = array();
		
		$key_word_sql = '';
		if(!empty($_POST['key_word']))
		{
			$key_word = $this ->lib ->escape(urldecode($_POST['key_word']));
			$key_word = str_replace("%", "\%", $key_word);
			$key_word = str_replace("_", "\_", $key_word);
			$key_word = str_replace("  ", " ", $key_word);
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_sql .= " and (";
						$key_word_sql .= " users.name like '%$key%'";
						$key_word_sql .= " or users.mail like '%$key%'";
						$key_word_sql .= " or charities.legal_business_id like '%$key%'";
						$key_word_sql .= " or charities.legal_business_name like '%$key%'";
						$key_word_sql .= " or charities.address like '%$key%'";
						$key_word_sql .= " or charities.city like '%$key%'";
						$key_word_sql .= " or charities.state like '%$key%'";
						$key_word_sql .= " or charities.zipcode like '%$key%'";
						$key_word_sql .= " or charities.phone like '%$key%'";
						$key_word_sql .= " or charities.fax like '%$key%'";
						$key_word_sql .= " or charities.website like '%$key%'";
						$key_word_sql .= " or charities.tax like '%$key%'";
						$key_word_sql .= " or charities.payment_type like '%$key%'";
						$key_word_sql .= " or charities.beneficiary_bank like '%$key%'";
						$key_word_sql .= " or charities.beneficiary_name like '%$key%'";
						$key_word_sql .= " or charities.account_NO like '%$key%'";
						$key_word_sql .= " or charities.SWIFT_CODE like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		$status_user_sql = '';
		$status = (!empty($_POST['status_user']))? urldecode($_POST['status_user']):'';
		if($status==2 || $status==1){
			if($status==2) $status=0;
			$status_user_sql = " and users.status = '".$status."' ";		
		}else{
			$status_user_sql = " and users.status <> -1 ";	
		}
		
		$sql = "select users.ukey,users.name,users.pass,users.mail,users.status,users.created,charities.* ";
		$sql .= " from charities join users join users_roles on charities.uid = users.uid and users.uid = users_roles.uid WHERE users_roles.rid = 8 $status_user_sql $key_word_sql order by users.uid ASC";
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$row['created_str'] = date("m/d/Y", $row['created']);
			$row['pass'] = $this ->author ->decode_password($row['pass']);	
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$row['view'] = $view;	
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end loadDataUsers function
	
	public function loadData()
	{
		$arrUsers = array();
		$dem_ = 0;
		if(isset($_POST['key']) && $_POST['key'] != ''){
			$key = trim($_POST['key']);
			$sql = "select users.ukey,users.name,users.pass,users.mail,users.status,users.created, users.country,charities.* ";
			$sql .= " from charities join users join users_roles on charities.uid = users.uid and users.uid = users_roles.uid WHERE users_roles.rid = 8 and users.ukey = '$key' and users.status <> -1";
	
			$re = $this ->db ->query($sql);
			foreach($re->result_array() as $row)
			{
				$id = $row['cid'];
				$countryid=$row['country'];
				$row['created_str'] = date("m/d/Y", $row['created']);
				$row['current_password'] = $this ->author ->decode_password($row['pass']);
				$row['status'] = $this ->lib ->GetStatusByID($row['status']);
				$row['contract_start_date_str'] = ($row['contract_start_date'] != null && $row['contract_start_date'] != '')?date("m/d/Y", $row['contract_start_date']):'';
				$row['contract_end_date_str'] = ($row['contract_end_date'] != null && $row['contract_end_date'] != '')?date("m/d/Y", $row['contract_end_date']):'';
				$row['state']= $this ->lib ->GetNameStateByCode($row['state'],$row['country']);
				
				$legalbusitact = array();
				$re_2 = $this ->db ->query("select * from legal_business_contact where linkID = '$id' and contactType = 4 and status <> -1");
				$row_countryname = $this ->db ->query("select name from tblcontries where code = '$countryid'");
				foreach($re_2->result_array() as $row_2)
				{
					$row_2['editID'] = $row_2['id'];
					$row_2['id'] = $dem_;
					$row_2['nameTittle']=$this ->lib ->GetTittleNameByID($row_2['title']);
					$dem_ ++;	
					$row_2['last_update_date'] = date("m/d/Y", $row_2['last_update_date']); 
					$legalbusitact[] = $row_2;
				}
				
				$row['legalbusitact'] = $legalbusitact;
				$row['countryname']= $row_countryname ->result_array();	
				$arrUsers[] = $row;
			}	
		}
		return $arrUsers;
	}//end loadData function
	function edit_loadData()
	{
		$arrUsers = array();
		$dem_ = 0;
		if(isset($_POST['key']) && $_POST['key'] != ''){
			$key = trim($_POST['key']);
			$sql = "select users.ukey,users.name,users.pass,users.mail,users.status,users.created,charities.* ";
			$sql .= " from charities join users join users_roles on charities.uid = users.uid and users.uid = users_roles.uid WHERE users_roles.rid = 8 and users.ukey = '$key' and users.status <> -1";
	
			$re = $this ->db ->query($sql);
			foreach($re ->result_array() as $row)
			{
				$id = $row['cid'];
				$row['created_str'] = date("m/d/Y", $row['created']);
				$row['current_password'] = $this ->author ->decode_password($row['pass']);
				$row['contract_start_date_str'] = ($row['contract_start_date'] != null && $row['contract_start_date'] != '')?date("m/d/Y", $row['contract_start_date']):'';
				$row['contract_end_date_str'] = ($row['contract_end_date'] != null && $row['contract_end_date'] != '')?date("m/d/Y", $row['contract_end_date']):'';
				
				$legalbusitact = array();
				$re_2 = $this ->db ->query("select * from legal_business_contact where linkID = '$id' and contactType = 4 and status <> -1");
				foreach($re_2 ->result_array() as $row_2)
				{
					$row_2['editID'] = $row_2['id'];
					$row_2['id'] = $dem_;
					$dem_ ++;	
					$row_2['last_update_date'] = date("m/d/Y", $row_2['last_update_date']); 
					$legalbusitact[] = $row_2;
				}
				
				$row['legalbusitact'] = $legalbusitact;
						
				$arrUsers[] = $row;
			}	
		}
		return $arrUsers;
	}//end edit_loadData function
	
	public function edit_saveUser()
	{
		$error = '';
		if(is_array($_POST['saveUser']) && count($_POST['saveUser']) > 0){	//0
			$saveUser = $_POST['saveUser'];
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
			$pass = '';
			$data = array(
				'mail'				=> $this ->lib->escape($saveUser["mail"]),
				'firstname'			=> $firstname,
				'lastname' 			=> $lastname,
				'phone'				=> $this ->lib->escape($saveUser["phone"]),
				'address' 			=> $this ->lib->escape($saveUser['address']),
				'city'				=> $this ->lib->escape($saveUser['city']),			
				'state'				=> $this ->lib->escape($saveUser['state']),
				'zipcode'			=> $this ->lib->escape($saveUser['zipcode']),
				'status'			=> $saveUser['status']
			);
			
			if(isset($saveUser["password"]) && $saveUser["password"] != ''){
				$data['pass'] =$this ->author-> encode_password(trim($saveUser["password"]));
				$pass = $data['pass'];
			}
			if(isset($saveUser["name"]) && trim($saveUser["name"]) != ''){
				$name = $this ->lib->escape($saveUser["name"]);
				if($this ->lib->check_name_exists_2($name, $key) > 0){
					$error = _error_name_exists_;	
					return array('error' => $error);	
				}else{
					$data['name'] = $name;	
				}
			}
			$re = $this ->db ->query("select name,pass,mail,status from users where ukey = '$key'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				if($pass == '') $pass = $row['pass'];
				if($saveUser['status'] == 0 && $row['status'] == 1){
					$variables_ = array(
						'!username' => $data['name'],
						'!password' => $this ->author ->decode_password($pass)
					);
					$this ->lib->sendmailtype($this ->lib->escape($saveUser["mail"]), __account_blocked__, $variables_);	
				}elseif($saveUser['status'] == 1 && $row['status'] == 0){
					$variables_ = array(
						'!username' => $data['name'],
						'!password' => $this ->author ->decode_password($pass)
					);
					$this ->lib->sendmailtype($this ->lib->escape($saveUser["mail"]), __account_activation__, $variables_);		
				}
			}
			$this ->db ->where('ukey',$key);
			$this ->db ->update('users', $data);
			$uid = $this ->database->db_result("select uid from users where ukey = '$key'");
			if(is_numeric($uid) && $uid > 0){//2
				$contract_start_date = 0;
				if(trim($saveUser['contract_start_date']) != ''){
					$contract_start_date = 	strtotime($saveUser['contract_start_date']);
					if(!is_numeric($contract_start_date)) $contract_start_date = 0;
				}
				
				$contract_end_date = 0;
				if(trim($saveUser['contract_end_date']) != ''){
					$contract_end_date = strtotime($saveUser['contract_end_date']);
					if(!is_numeric($contract_end_date)) $contract_end_date = 0;
				}
				$charities = array(
					'legal_business_name' => $legal_business_name,
					'address' => $this ->lib->escape($saveUser['address']),
					'city' => $this ->lib->escape($saveUser['city']),
					'state' => $this ->lib->escape($saveUser['state']),
					'zipcode' => $this ->lib->escape($saveUser['zipcode']),
					'country' => $saveUser['country'],
					'phone' => $this ->lib->escape($saveUser['phone']),
					'fax' => $this ->lib->escape($saveUser['fax']),
					'website' => $this ->lib->escape($saveUser['website']),
					'tax' => $this ->lib->escape($saveUser['tax']),
					'contract_start_date' => $contract_start_date,
					'contract_end_date' => $contract_end_date,
					'contract_file' => '',
					'payment_type' => $this ->lib->escape($saveUser['payment_type']),
					'beneficiary_bank' => $this ->lib->escape($saveUser['beneficiary_bank']),
					'beneficiary_name' => $this ->lib->escape($saveUser['beneficiary_name']),
					'account_NO' => $this ->lib->escape($saveUser['account_NO']),
					'SWIFT_CODE' => $this ->lib->escape($saveUser['SWIFT_CODE']),
					'trust' => (isset($saveUser['trust']) && is_numeric($saveUser['trust']))?$saveUser['trust']:0,
					'featured' => (isset($saveUser['featured']) && is_numeric($saveUser['featured']))?$saveUser['featured']:0,
					'description' => $this ->lib->escape($saveUser['description'])
				);
				$this ->db->where('uid',$uid);
				$this ->db->update('charities', $charities);
				$mid = $this ->database->db_result("select cid from charities where uid = '$uid'");
			
				if(is_numeric($mid) && $mid > 0){
					$arr_legalbusitact = array();
					$re_3 = $this ->db->query("select id from legal_business_contact where linkID = '$mid' and contactType = 4");
					foreach($re_3 ->result_array() as $row_3)
					{
						$arr_legalbusitact[] = $row_3['id'];	
					}
					$arr_legalbusitact_update = array();
					if(isset($saveUser['legalbusitact']) && is_array($saveUser['legalbusitact']) && count($saveUser['legalbusitact']) > 0){
						foreach($saveUser['legalbusitact'] as $legal_contact){
							$legal_business_contact = array(
								'linkID' => $mid,
								'contactType' => 4,
								'gender' => $this ->lib->escape($legal_contact['gender']),
								'title' => $this ->lib->escape($legal_contact['title']),
								'first_name' => $this ->lib->escape($legal_contact['first_name']),
								'last_name' => $this ->lib->escape($legal_contact['last_name']),
								'middle_name' => $this ->lib->escape($legal_contact['middle_name']),
								'email' => $this ->lib->escape($legal_contact['email']),
								'mobile' => $this ->lib->escape($legal_contact['mobile']),
								'last_update_date' => $this->lib->getTimeGMT(),
								'note' =>$this ->lib-> escape($legal_contact['note'])
							);
							if(isset($legal_contact['editID']) && is_numeric($legal_contact['editID']) && $legal_contact['editID'] > 0){
								$this ->db->where('id',$legal_contact['editID']);
								$this ->db->update('legal_business_contact', $legal_business_contact);
								$arr_legalbusitact_update[] = $legal_contact['editID'];
							}else{
								$this ->db->insert('legal_business_contact', $legal_business_contact);	
								$leg = $this ->db ->insert_id();
								if(!is_numeric($leg) || $leg < 0){
									$error = 'Can not insert to legal business contact';
								}else{
									$arr_legalbusitact_update[] = $leg;
								}
							}	
						}	
					}
					if(count($arr_legalbusitact) > 0){
						foreach($arr_legalbusitact as $lega){
							if(!in_array($lega, $arr_legalbusitact_update)){
								$this ->db->query("delete from legal_business_contact where id = '$lega'");	
							}	
						}	
					}
				}else{
					$error = 'Can not insert charities';	
				}
			}else{
				$error = 'Can not insert User';		
			}
			
		}//0
		return array('error' => $error);
	}//end edit_saveUser function
	
	function delete_client()
	{
		if(isset($_POST['cid']) && $_POST['cid'] != '')
		{
			$cid = $this ->lib ->escape($_POST['cid']);
			$this ->db ->update("users", array("status"=>-1),array('ukey' =>$cid));
			$re = $this ->db ->query("select name,pass,mail from users where ukey = '$cid'");
			if($re ->num_rows() >0)
			{
				$row = $re->row_array();
				$variables_ = array(
					'!username' => $row['name'],
					'!password' => $this ->author ->decode_password($row['pass'])
				);
				$this ->lib->sendmailtype($row['mail'], __account_deleted__, $variables_);		
			}
		}
		return json_encode($this ->loadDataUsers());
	}//end delete_client function
	public function add_saveUser()
	{
		$error = '';
		if(is_array($_POST['saveUser']) && count($_POST['saveUser']) > 0){	//0
			$saveUser = $_POST['saveUser'];
				
			$user_randomkey = 'CH'.$this ->lib->GeneralRandomNumberKey(12);
			$re = $this ->db->query("select uid from users where ukey = '$user_randomkey'");
			foreach($re->result_array() as $row)
			{
				$user_randomkey = 'CH'.$this ->lib->GeneralRandomNumberKey(12);
				$re =  $this ->db->query("select uid from users where ukey = '$user_randomkey'");	
			}
			
			$legal_business_id = $this ->lib->GeneralRandomReferralCode(12);
			$re =  $this ->db->query("select uid from charities where legal_business_id = '$legal_business_id'");
			foreach($re->result_array() as $row)
			{
				$legal_business_id = $this ->lib->GeneralRandomReferralCode(12);
				$re =  $this ->db->query("select uid from charities where legal_business_id = '$legal_business_id'");
			}
			
			$legal_business_name = $this ->lib->escape($saveUser['legal_business_name']);
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
			
			if($this ->lib->check_name_exists($this ->lib->escape($saveUser["name"])) > 0){
				$error = _error_name_exists_;	
			}
			if($this->checkTaxExist($this ->lib ->escape($saveUser['tax']),0)){
				return array('error' => 'This Tax ID is exist on system.');	
			}
			if($error == ''){//1
				$data = array(
					'ukey'				=> $user_randomkey,
					'name' 				=> $this ->lib->escape($saveUser["name"]),
					'pass' 				=> $this ->author-> encode_password(trim($saveUser["password"])),
					'mail'				=> $this ->lib->escape($saveUser["mail"]),
					'firstname'			=> $firstname,
					'lastname' 			=> $lastname,
					'phone'				=> $this ->lib->escape($saveUser["phone"]),
					'address' 			=> $this ->lib->escape($saveUser['address']),
					'city'				=> $this ->lib->escape($saveUser['city']),
					'state'				=> $this ->lib->escape($saveUser['state']),
					'zipcode'			=> $this ->lib->escape($saveUser['zipcode']),
					'country'			=> "US",
					'created'			=> $this->lib->getTimeGMT(),
					'access'			=> $this->lib->getTimeGMT(),
					'login'				=> $this->lib->getTimeGMT(),
					'status'			=> $saveUser['status']
				);
			
				$this ->db->insert('users', $data);
				$uid = $this ->db ->insert_id();
				if(is_numeric($uid) && $uid > 0){//2
					$variables_ = array(
						'!username' => $this ->lib->escape($saveUser['name']),
						'!password' => trim($saveUser['password'])
					);
					$this ->lib->sendmailtype($this ->lib->escape($saveUser["mail"]), __new_user_created_by_administrator__, $variables_);
					
					$users_roles = array(
						'uid' 	=> $uid,
						'rid'	=> 8
					);
					$this ->db->insert('users_roles', $users_roles);
					
					$manufacturers = array(
						'uid' => $uid,
						'author' => $this ->author ->objlogin->uid,
						'legal_business_name' => $legal_business_name,
						'legal_business_id' => $legal_business_id,
						'address' => $this ->lib->escape($saveUser['address']),
						'city' => $this ->lib->escape($saveUser['city']),
						'country' => $saveUser['country'],
						'state' => $saveUser['state'],
						'zipcode' => $this ->lib->escape($saveUser['zipcode']),					
						'phone' => $this ->lib->escape($saveUser['phone']),
						'fax' => $this ->lib->escape($saveUser['fax']),
						'website' => $this ->lib->escape($saveUser['website']),
						'tax' => $this ->lib->escape($saveUser['tax']),
						'contract_file' => '',
						'payment_type' => $this ->lib->escape($saveUser['payment_type']),
						'beneficiary_bank' =>$this ->lib-> escape($saveUser['beneficiary_bank']),
						'beneficiary_name' => $this ->lib->escape($saveUser['beneficiary_name']),
						'account_NO' => $this ->lib->escape($saveUser['account_NO']),
						'SWIFT_CODE' => $this ->lib->escape($saveUser['SWIFT_CODE']),
						'trust' => (isset($saveUser['trust']) && is_numeric($saveUser['trust']))?$saveUser['trust']:0,
						'featured' => (isset($saveUser['featured']) && is_numeric($saveUser['featured']))?$saveUser['featured']:0,
						'description' => $this ->lib->escape($saveUser['description'])
					);
					
					if($saveUser['contract_start_date'] != ''){
						$contract_start_date = 	strtotime($saveUser['contract_start_date']);
						if(is_numeric($contract_start_date)) $manufacturers['contract_start_date'] = $contract_start_date;
					}
					
					if($saveUser['contract_end_date'] != ''){
						$contract_end_date = 	strtotime($saveUser['contract_end_date']);
						if(is_numeric($contract_end_date)) $manufacturers['contract_end_date'] = $contract_end_date;
					}
					 
					$this ->db->insert('charities', $manufacturers);
					$mid =$this ->db ->insert_id();
					if(is_numeric($mid) && $mid > 0){
						if(isset($saveUser['legalbusitact']) && is_array($saveUser['legalbusitact']) && count($saveUser['legalbusitact']) > 0){
							foreach($saveUser['legalbusitact'] as $legal_contact){
								$legal_business_contact = array(
									'linkID' => $mid,
									'contactType' => 4,
									'gender' => $this ->lib->escape($legal_contact['gender']),
									'title' => $this ->lib->escape($legal_contact['title']),
									'first_name' => $this ->lib->escape($legal_contact['first_name']),
									'last_name' => $this ->lib->escape($legal_contact['last_name']),
									'middle_name' => $this ->lib->escape($legal_contact['middle_name']),
									'email' => $this ->lib->escape($legal_contact['email']),
									'mobile' => $this ->lib->escape($legal_contact['mobile']),
									'last_update_date' => $this->lib->getTimeGMT(),
									'note' => $this ->lib->escape($legal_contact['note'])
								);
								$this ->db->insert('legal_business_contact', $legal_business_contact);
								$leg = $this ->db ->insert_id();
								if(!is_numeric($leg) || $leg < 0){
									$error = 'Can not insert to legal business contact';
									$this ->db->query("delete from users where uid = '$uid'");
									$this ->db->query("delete from users_roles where uid = '$uid' and rid = 8");
									$this ->db->query("delete from charities where id = '$mid'");	
									break;	
								}	
							}	
						}
					}else{
						$error = 'Can not insert Charities';
					//	$error = _error_cannot_insert_db_;
						$this ->db->query("delete from users where uid = '$uid'");
						$this ->db->query("delete from users_roles where uid = '$uid' and rid = 8");		
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
			
			$legalID = $this->database->db_result("select legal_business_id from charities where uid = '".$userID."'");
			$re = $this->db->query("select charities.tax from charities join users on charities.uid = users.uid where users.status = 1 and charities.legal_business_id <> '".$legalID."' and charities.tax = '".$tax."'" );
			if($re->num_rows() > 0) return true;
		}
		return false;
	}
}//end charity_manage_model class
