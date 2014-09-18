<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpermission_model extends CI_Model {
	var $arrRoles = array();
	var $roles_permission = array();
	var $controller_access = array();
	var $dataPost = array();
	var $rid = 0;
	function __construct(){
        parent::__construct();
    }	

	function getRole($id){
		$query = $this->db->query("select * from roles where rid=$id");
		if ($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$per = array();
				$query2 = $this->db->query("select permission,controller,functions from roles_permission where rid = ".$row['rid']." order by permission asc, controller asc");
				if ($query2->num_rows() > 0){
					foreach ($query2->result_array() as $row2){
						$per[] = $row2;	
					}
				}
				$row['per'] = $per;
				$this->arrRoles[] = $row;
			}
		}			
	}
	
	function saveRolesAccess($controller_access=array()){
		if ($this->input->get('key')!==FALSE && is_numeric($this->input->get('key')) && $this->input->get('key')>0)
		{
			$this->rid = $this->input->get('key');
		}
		if($controller_access !== NULL && is_array($controller_access) && count($controller_access) > 0){
			$this->controller_access = $controller_access;
		}
		$this->truncateRolesAccess();
		$this->dataPost = (isset($_POST['dataPost']) && is_array($_POST['dataPost']))?$_POST['dataPost']:array();
		$roles_permission = array();
		if(count($this->dataPost) > 0){//0
			foreach($this->dataPost as $data){//1
				if(is_array($data['perm']) && count($data['perm']) > 0){
					$perm = $data['perm'];
					foreach($perm as $pers){
						if($pers != ''){//1
							$arr_pers = explode("|", $pers);
							$permission = isset($arr_pers[0])?$arr_pers[0]:'';
							$controller = isset($arr_pers[1])?$arr_pers[1]:'';
							
							$functions = array();
							if(count($this->controller_access) > 0){
								foreach($this->controller_access as $controller_ => $perms){//0
									if($controller == $controller_){
										if(is_array($perms) && count($perms) > 0){
											foreach($perms as $access_name => $functions_){
												if($access_name == $permission){
													$functions = $functions_;
													break;	
												}
											}
										}
										break;	
									}	
								}//0
							}	
							$functions = serialize($functions);
							
							$this->roles_permission[] = array(
								'rid' => $data['rid'],
								'permission' => $permission,
								'controller' => $controller,
								'functions' => $functions
							);
							
						}//1	
					}	
				}
			}//1	
		}//0
		$this->insertRolesAccess();
	//	$this->updateUsersAccess();
	}
	
	function truncateRolesAccess(){
		$this->db->query('DELETE FROM `roles_permission` WHERE  rid = '.$this->rid);
	}
	
	function nameRoles($rid)
	{
		$query = $this->db->query('select * from roles where rid='.$rid);
		if ($query->num_rows() > 0) 
		{
			$row = $query->row_array();
			return $row['name'];
		}
		return '';
	}
	
	function insertRolesAccess($roles_permission = NULL){
		if($roles_permission !== NULL && is_array($roles_permission) && count($roles_permission) > 0){
			$this->roles_permission = $roles_permission;
		}
		$this->db->insert_batch('roles_permission', $this->roles_permission);
	}
	
	function updateUsersAccess(){
		$query = $this->db->query("select users_permission.*,users_roles.rid from users_permission join users_roles on users_roles.uid =  users_permission.uid where users_roles.rid = ".$this->rid);
		if ($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				if(! $this->in_roleAccess($row)){
					$this->db->delete('users_permission', array('uid' => $row['uid'])); 	
				}		
			}
		}
	
	}
	
	function in_roleAccess($row){
		$return = false;
		if(count($this->roles_permission) > 0){
			foreach($this->roles_permission as $roleAcc){
				if($roleAcc['rid'] == $row['rid'] && $roleAcc['permission'] == $row['permission'] && $roleAcc['controller'] == $row['controller']){
					$return = true;
					break;	
				}	
			}	
		}
		return $return;	
	}
	
}