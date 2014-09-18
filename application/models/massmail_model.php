<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Massmail_model extends CI_Model {
	private $error = '';
	private $table_name = '';
	
	public function display()
	{
		$arrUsers = array();
		$key_word_sql = '';
		$key_word = $this->input->get('key')!==FALSE?urldecode($this->input->get('key')):'';
		if ($key_word !=='')
		{
			$key_word = str_replace("  ", " ", $key_word);
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_sql .= " and (";
						$key_word_sql .= " name like '%$key%'";
						$key_word_sql .= " or mail_from like '%$key%'";
						$key_word_sql .= " or mail_subject like '%$key%'";
						$key_word_sql .= " or mail_body like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		$sql = "select * from mass_mail WHERE status <> -1 $key_word_sql order by id ASC";
		$query = $this->db->query($sql);
		foreach ($query->result_array() as $row){
			$row['create_str'] = date("m/d/Y", $row['created']);
			$mail_roles = array();
			$re_2 = $this->db->query("select roles.name,role_type from mail_roles join roles on mail_roles.rid = roles.rid where mail_roles.mkey = '".$row['mkey']."'");
			foreach($re_2->result_array() as $row_2){
				$mail_roles[] = $row_2;	
			}
			$row['mail_roles'] = $mail_roles;
			$arrUsers[] = $row;
		}
		return json_encode($arrUsers);
	}
	public function add()
	{
		$error = '';
		if(is_array($_POST['obj_submit']) && count($_POST['obj_submit']) > 0){	//0
			$obj_submit = $_POST['obj_submit'];
			$sendmail = 0;
			if(isset($obj_submit['sendmail']) && $obj_submit['sendmail'] == 1){
				$sendmail = 1;	
			}
			$key = $this->lib->GeneralRandomKey(20);
			$re = $this->db->query("select id from mass_mail where mkey = '$key'");
			while($re->num_rows()>0){
				$key = $this->lib->GeneralRandomKey(20);
				$re = $this->db->query("select id from mass_mail where mkey = '$key'");
			}
			$mail_subject = $this->lib->escape($obj_submit["mail_subject"]);
			$mail_from = $this->lib->escape($obj_submit["mail_from"]);
			$mail_body = $this->lib->FCKToSQL($obj_submit["mail_body"]);
			$data = array(
				'mkey' => $key,
				'name' => $this->lib->escape($obj_submit["name"]),
				'mail_from' => $mail_from,
				'mail_subject' => $mail_subject,
				'mail_body' => $mail_body,
				'created' => time()
			);
			$mail_body = $this->lib->SQLToFCK($obj_submit["mail_body"]);
			$this->db->insert('mass_mail', $data);
			$id = $this->db->insert_id();
			if(is_numeric($id) && $id > 0){//2
				if(isset($obj_submit["mail_roles"]) && is_array($obj_submit["mail_roles"]) && count($obj_submit["mail_roles"]) > 0){
					foreach($obj_submit["mail_roles"] as $mail_roles){
						$data_mail_roles = array(
							'mkey' => $key,
							'rid' => $mail_roles['rid'],
							'role_type' => $mail_roles['role_type']
						);
						$this->db->insert('mail_roles', $data_mail_roles);
						$mid = $this->db->insert_id();
						if(is_numeric($mid) && $mid > 0){//2
							if($mail_roles['role_type'] == 'all'){
								if($sendmail == 1){
									$re_2 = $this->db->query("select mail from users join users_roles on users_roles.uid = users.uid where users.status <> -1 and users_roles.rid = ".$mail_roles['rid']);
									foreach($re_2->result_array() as $row_2){
										//sendmail($row_2['mail'],$mail_subject,SENDER_NAME,$mail_from,$mail_body);
									}
								}
							}elseif($mail_roles['role_type'] == 'select'){
								if(isset($mail_roles['mail_users']) && is_array($mail_roles['mail_users']) && count($mail_roles['mail_users']) > 0){
									foreach($mail_roles['mail_users'] as $objuser){
										$mail_users = array(
											'rid' => $mid,
											'ukey' => $objuser['ukey']
										);
										$ui = $this->db->insert('mail_users', $mail_users);
										if($sendmail == 1){
											//sendmail($objuser['mail'],$mail_subject,SENDER_NAME,$mail_from,$mail_body);	
										}	
									}	
								}
							}elseif($mail_roles['role_type'] == 'except'){
								$arr_users = array();
								$arr_user_choice = array();
								$re_2 = $this->db->query("select ukey,mail from users join users_roles on users_roles.uid = users.uid where users.status <> -1 and users_roles.rid = ".$mail_roles['rid']);
								foreach($re_2->result_array() as $row_2){
									$arr_users[] = $row_2;
								}
								if(isset($mail_roles['mail_users']) && is_array($mail_roles['mail_users']) && count($mail_roles['mail_users']) > 0){
									foreach($mail_roles['mail_users'] as $objuser){
										$mail_users = array(
											'rid' => $mid,
											'ukey' => $objuser['ukey']
										);
										$ui = $this->db->insert('mail_users', $mail_users);
									}
									$arr_user_choice[] = $objuser['ukey'];
								}
								if($sendmail == 1){
									if(count($arr_users) > 0){
										foreach($arr_users as $user){
											$check_exist = false;
											for($i = 0; $i < count($arr_user_choice); $i++){
												if($arr_user_choice[$i] == $user['ukey']){
													$check_exist = true;
													break;	
												}	
											}
											if($check_exist == false){
												//sendmail($user['mail'],$mail_subject,SENDER_NAME,$mail_from,$mail_body);		
											}	
										}	
									}
								}
							}
						}else{
							$error = 'Can not insert mail_roles';	
						}
					}	
				}
			}else{
				$error = 'Can not insert mass_mail';		
			}
		}
		return array('error' => $error);
	}
	function displayRoles(){
		$arr_roles = array();
		$re = $this->db->query("select * from roles where rid > 1 order by rid asc");
		foreach($re->result_array() as $row){
			$arr_users = array();
			$re_2 = $this->db->query("select ukey,name,mail from users join users_roles on users_roles.uid = users.uid where users.status <> -1 and users_roles.rid = ".$row['rid']);
			foreach($re_2->result_array() as $row_2){
				$arr_users[] = $row_2;	
			}
			$row['users'] = $arr_users;
			$row['users_select'] = array();
			$arr_roles[] = $row;	
		}
		return json_encode($arr_roles);
	}
	
	public function loadvalue(&$data,$key)
	{
		$name = '';
		$mail_from = '';
		$mail_subject = '';
		$mail_body = '';
		$re = $this->db->query("select * from mass_mail where mkey = '$key'");
		if($re->num_rows()>0){
			$row = $re->row_array();
			$name = $row['name'];
			$mail_from = $row['mail_from'];
			$mail_subject = $row['mail_subject'];
			$mail_body = $row['mail_body'];	
		}
		$data['key'] =  $key;
		$data['name'] =  $name;
		//$str	= str_replace("if('load_data_roles'=='yes');", 'data_roles='.json_encode(displayRoles($key)).';', $str);
		$data['roles'] = json_encode($this->edisplayRoles($key));
		$data['from_mail'] = $mail_from;
		$data['mail_subject'] = $mail_subject;
		$data['mail_body'] = $this->lib->SQLToFCK($mail_body);
	}
	
	public function edisplayRoles($key){
		$mail_roles = array();
		$re_2 = $this->db->query("select mail_roles.id,mail_roles.rid,mail_roles.role_type from mail_roles join roles on mail_roles.rid = roles.rid where mail_roles.mkey = '$key'");
		$mail_roles = $re_2->result_array();
		
		$arr_roles = array();
		$re = $this->db->query("select * from roles where rid > 1 order by rid asc");
		foreach($re->result_array() as $row){
			$mail_users = array();
			$role_type = 'all';
			$checked = 0;
			for($i = 0; $i < count($mail_roles); $i++){
				if($mail_roles[$i]['rid'] == $row['rid']){
					$checked = 1;
					$role_type = $mail_roles[$i]['role_type'];
					$re_2 = $this->db->query("select ukey from mail_users where rid = ".$mail_roles[$i]['id']);
					foreach ($re_2->result_array() as $row_2){
						$mail_users[] = $row_2['ukey'];	
					}
					break;
				}	
			}
			
			$arr_users_select = array();
			$arr_users = array();
			$re_2 = $this->db->query("select ukey,name,mail from users join users_roles on users_roles.uid = users.uid where users.status <> -1 and users_roles.rid = ".$row['rid']);
			foreach($re_2->result_array() as $row_2){
				if(in_array($row_2['ukey'], $mail_users)) $arr_users_select[] = $row_2;
				else $arr_users[] = $row_2;	
			}
			$row['users'] = $arr_users;
			$row['users_select'] = $arr_users_select;
			$row['checked'] = $checked;
			$row['role_type'] = $role_type;
			$arr_roles[] = $row;	
		}
		return $arr_roles;
	}
	
	public function edit()
	{
			$error = '';
			if(is_array($_POST['obj_submit']) && count($_POST['obj_submit']) > 0){	//0
				$obj_submit = $_POST['obj_submit'];
				$key = isset($obj_submit['key'])?$obj_submit['key']:'';
				if($key == '') return array('error'=>'No data');
				$sendmail = 0;
				if(isset($obj_submit['sendmail']) && $obj_submit['sendmail'] == 1){
					$sendmail = 1;	
				}
				$mail_subject = $this->lib->escape($obj_submit["mail_subject"]);
				$mail_from = $this->lib->escape($obj_submit["mail_from"]);
				$mail_body = $this->lib->FCKToSQL($obj_submit["mail_body"]);
				$data = array(
					'name' => $this->lib->escape($obj_submit["name"]),
					'mail_from' => $mail_from,
					'mail_subject' => $mail_subject,
					'mail_body' => $mail_body
				);
				$mail_body = $this->lib->SQLToFCK($obj_submit["mail_body"]);
				$this->db->update('mass_mail', $data, "mkey = '$key'");
				$re = $this->db->query("select id from mail_roles where mkey = '$key'");
				foreach ($re->result_array() as $row){
					$this->db->query("delete from mail_users where rid = ".$row['id']);	
				}
				$this->db->query("delete from mail_roles where mkey = '$key'");
				if(isset($obj_submit["mail_roles"]) && is_array($obj_submit["mail_roles"]) && count($obj_submit["mail_roles"]) > 0){
					foreach($obj_submit["mail_roles"] as $mail_roles){
						$data_mail_roles = array(
							'mkey' => $key,
							'rid' => $mail_roles['rid'],
							'role_type' => $mail_roles['role_type']
						);
						$this->db->insert('mail_roles', $data_mail_roles);
						$mid = $this->db->insert_id();
						if(is_numeric($mid) && $mid > 0){//2
							if($mail_roles['role_type'] == 'all'){
								if($sendmail == 1){
									$re_2 = $this->db->query("select mail from users join users_roles on users_roles.uid = users.uid where users.status <> -1 and users_roles.rid = ".$mail_roles['rid']);
									foreach ($re_2->result_array() as $row_2){
										//sendmail($row_2['mail'],$mail_subject,$SENDER_NAME,$mail_from,$mail_body);
									}
								}
							}elseif($mail_roles['role_type'] == 'select'){
								if(isset($mail_roles['mail_users']) && is_array($mail_roles['mail_users']) && count($mail_roles['mail_users']) > 0){
									foreach($mail_roles['mail_users'] as $objuser){
										$mail_users = array(
											'rid' => $mail_roles['rid'],
											'ukey' => $objuser['ukey']
										);
										$ui = $this->db->insert('mail_users', $mail_users);
										if($sendmail == 1){
											//sendmail($objuser['mail'],$mail_subject,$SENDER_NAME,$mail_from,$mail_body);	
										}	
									}	
								}
							}elseif($mail_roles['role_type'] == 'except'){
								$arr_users = array();
								$arr_user_choice = array();
								$re_2 = $this->db->query("select ukey,mail from users join users_roles on users_roles.uid = users.uid where users.status <> -1 and users_roles.rid = ".$mail_roles['rid']);
								foreach ($re_2->result_array() as $row_2){
									$arr_users[] = $row_2;
								}
								if(isset($mail_roles['mail_users']) && is_array($mail_roles['mail_users']) && count($mail_roles['mail_users']) > 0){
									foreach($mail_roles['mail_users'] as $objuser){
										$mail_users = array(
											'rid' => $mail_roles['rid'],
											'ukey' => $objuser['ukey']
										);
										$ui = $this->db->insert('mail_users', $mail_users);
									}
									$arr_user_choice[] = $objuser['ukey'];
								}
								if($sendmail == 1){
									if(count($arr_users) > 0){
										foreach($arr_users as $user){
											$check_exist = false;
											for($i = 0; $i < count($arr_user_choice); $i++){
												if($arr_user_choice[$i] == $user['ukey']){
													$check_exist = true;
													break;	
												}	
											}
											if($check_exist == false){
												//sendmail($user['mail'],$mail_subject,$SENDER_NAME,$mail_from,$mail_body);		
											}	
										}	
									}
								}
							}
						}else{
							$error = 'Can not insert mail_roles';	
						}
					}	
				}
			}
			return array('error' => $error);
	}
	
	public function delete()
	{
		if ($this->input->post('cid') !== FALSE)
		{
			$this->db->update("mass_mail", array("status"=>-1), "mkey = '".$this->input->post('cid')."'");
			return $this->display();	
		}
	}
}