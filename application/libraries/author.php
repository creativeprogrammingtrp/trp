<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Author{
	var $CI;
	var $objlogin;
	
	function __construct(){
		$this->CI =& get_instance();
		$this->objlogin = $this->CI->objlogin;
		if(! isset($this->CI->system)){
			$this->CI->system = new System(1);	
		}	
	}
	
	function isAccessSystem(){
		$class = ucfirst($this->CI->router->fetch_class());
		$method = $this->CI->router->fetch_method();
		$checkIsAccess = $this->isAccessPerm($class, $method);
		if($checkIsAccess == false){
			 redirect($this ->CI ->system->URL_server__(), 'refresh');
		}
	}
	
	function isAccessPerm($class, $method){
		$checkIsAccess = false;
		if(count($this->objlogin->permission) > 0){
			foreach($this->objlogin->permission as $rolePerms){
				if(strcasecmp($rolePerms['controller'], $class) == 0){
					if(is_array($rolePerms['functions']) && count($rolePerms['functions']) > 0){
						foreach($rolePerms['functions'] as $func){
							if(strcasecmp($func, $method) == 0){
								$checkIsAccess = true;
								break;	
							}	
						}
					}	
				}
				if($checkIsAccess == true) break;	
			}	
		}
		return $checkIsAccess;	
	}
	
	function isAccess($class, $perm){
		$checkIsAccess = false;
		if(count($this->objlogin->permission) > 0){
			foreach($this->objlogin->permission as $rolePerms){
				if(strcasecmp($rolePerms['controller'], $class) == 0 && $rolePerms['permission'] == $perm){
					$checkIsAccess = true;
					break;	
				}
				if($checkIsAccess == true) break;	
			}	
		}
		return $checkIsAccess;	
	}
	
	function loadRole($uid=0){
		$roles = array();
		if(is_numeric($uid) && $uid > 0){
			if($this->CI->session->userdata('ses_login') && $this->CI->session->userdata('ses_login')->uid == $uid){
				$roles = $this->CI->session->userdata('ses_login')->role;		
			}else{
				$query = $this->CI->db->query("select roles.rid as rid,roles.name,roles.rlink from roles join users_roles on roles.rid = users_roles.rid where users_roles.uid = $uid");
				if ($query->num_rows() > 0){
					$roles = $query->row_array();
				}		
			}
		}else{
			if($this->CI->session->userdata('ses_login')){
				if($this->CI->session->userdata('ses_login')->uid > 0){
					$roles = $this->CI->session->userdata('ses_login')->role;			
				}else{
					$roles = $this->loadAnonymousRole();
				}
			}else{
				$roles = $this->loadAnonymousRole();	
			}	
		}
		return $roles;	
	}
	
	function loadAnonymousRole(){
		$roles = array();
		$query = $this->CI->db->query("select * from roles where rid = 1");
		if ($query->num_rows() > 0){
			$roles = $query->row_array();
		}
		return $roles;	
	}
	
	function getDatas(){
		if($this->CI->session->userdata('ses_login')){
			$this->objlogin = $this->CI->session->userdata('ses_login');
		}else{
			$this->CI->session->set_userdata('ses_login', $this->objlogin);
		}
		$this->objlogin->role = $this->loadRole();	
		$this->objlogin->permission = $this->getPerms($this->objlogin->role['rid']);	
	}
	
	function getThisRole(){
		if(isset($this->objlogin->uid) && $this->objlogin->uid > 0){
			$query = $this->CI->db->query("select roles.* from roles join users_roles on roles.rid = users_roles.rid where users_roles.uid = ".$this->objlogin->uid);
			if ($query->num_rows() > 0){
				$this->objlogin->role = $query->row_array();	
			}
		}
	}
	
	function getPerms($rid){
		$permission = array();
		$query = $this->CI->db->query("select permission,controller,functions from roles_permission where rid = ".$rid);
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$row['functions'] = unserialize($row['functions']);
				$permission[] = $row;	
			}	
		}
		return $permission;	
	}
        function checkSignUp($efin){
            if(trim($efin) == '') return false;
            $query = $this ->CI ->db ->query("SELECT * FROM efins WHERE efin= '".$efin."'");
            if($query ->num_rows()> 0){
               $sql = "SELECT * from users where efin = ".$efin;
               $query1 = $this->CI->db->query($sql);
               if ($query1->num_rows() > 0){
					$row = $query1->row_array();
					$this->objlogin->uid 			= $row["uid"];
					$this->objlogin->ukey			= $row['ukey'];
					$this->objlogin->name			= $row["name"];
					$this->objlogin->pass			= $row["pass"];
					$this->objlogin->mail			= $row["mail"];
					$this->objlogin->firstname		= $row["firstname"];
					$this->objlogin->lastname		= $row["lastname"];
					$this->objlogin->phone			= $row["phone"];
					$this->objlogin->mobile			= $row["mobile"];
					$this->objlogin->address		= $row["address"];
					$this->objlogin->city			= $row['city'];
					$this->objlogin->state			= $row['state'];
					$this->objlogin->zipcode		= $row['zipcode'];
					$this->objlogin->country		= $row['country'];
					$this->objlogin->created		= $row['created'];
					$this->objlogin->access			= $row['access'];
					$this->objlogin->login			= $row['login'];
					$this->objlogin->status			= $row["status"];
					$this->objlogin->picture		= $row["picture"];
					$this->objlogin->data 			= $row['data'];
					$this->objlogin->permission 	= array();
					$this->getThisRole();
					$this->UpdateLogin();
					return true;
				}
				return false;
            }
            
        }
	
    function checkLogin($t_efin,$t_user, $t_password){
	//function checkLogin($t_user, $t_password){
		if(trim($t_user) == '' || trim($t_password) == '' ) return false;
		$t_password = $this->encode_password($t_password);
		$re = $this->CI->db->query("SELECT users.uid FROM users join users_roles on users.uid = users_roles.uid WHERE status <> -1 and rid = 5 and name ='$t_user' ");
		
		$uid = 0;
                $sql = '';
        if ($re->num_rows() > 0)
		{
			$row = $re->row_array();
			$uid =  $row['uid']; 
		}
		/*else{
			$re1 = $this->CI->db->query("SELECT users.uid FROM users join users_roles on users.uid = users_roles.uid WHERE status <> -1 and rid = 6 and name ='$t_user' ");
			
			$row = $re1->row_array();
			$uid =  $row['uid'];
		}*/
		//if (trim($t_efin) == '') $t_efin = 0;
//		echo $uid;
		//echo "SELECT * from users where (efin = '".$t_efin."')  AND  name = '$t_user' AND pass = '$t_password' ";
		if ($uid != 0) // comments added by Azfar : if not admin
		{
            $sql = "SELECT * from users where (efin = ".$t_efin.")  AND  name = '$t_user' AND pass = '$t_password' AND (status = '1' OR status = '2' OR status = '4')"; // user checking wih efin
			//$sql = "SELECT * from users where  name = '$t_user' AND pass = '$t_password' "; // user checking wihout efin
		}
		else{
            $sql = "SELECT * from users where name = '$t_user' AND pass = '$t_password' AND (status = '1' OR status = '2' OR status = '4')";
        }

		$query = $this->CI->db->query($sql);
		
		if ($query->num_rows() > 0){
			$row = $query->row_array();
			$this->objlogin->uid 			= $row["uid"];
			$this->objlogin->ukey			= $row['ukey'];
			$this->objlogin->name			= $row["name"];
			$this->objlogin->pass			= $row["pass"];
			$this->objlogin->mail			= $row["mail"];
			$this->objlogin->firstname		= $row["firstname"];
			$this->objlogin->lastname		= $row["lastname"];
			$this->objlogin->phone			= $row["phone"];
			$this->objlogin->mobile			= $row["mobile"];
			$this->objlogin->address		= $row["address"];
			$this->objlogin->city			= $row['city'];
			$this->objlogin->state			= $row['state'];
			$this->objlogin->zipcode		= $row['zipcode'];
			$this->objlogin->country		= $row['country'];
			$this->objlogin->efin			= $row['efin'];
			$this->objlogin->created		= $row['created'];
			$this->objlogin->access			= $row['access'];
			$this->objlogin->login			= $row['login'];
			$this->objlogin->status			= $row["status"];
			$this->objlogin->picture		= $row["picture"];
			$this->objlogin->data 			= $row['data'];
			$this->objlogin->permission 	= array();
			$this->getThisRole();
			$this->UpdateLogin();
			
			$uid= $row["uid"];
			// for get parent id for sigin in user.
			$sql1 = "SELECT * from ero where author = '$uid'";
			$query1 = $this->CI->db->query($sql1);
			if ($query1->num_rows() > 0){
				$row1 = $query1->row_array();
				$this->objlogin->parentUid 	= $row1['uid'];
			}else{
				$this->objlogin->parentUid 	= 0;
			}
			return true;
		}
		
		
		
		return false;
	}
	
	function UpdateLogin(){   	    
		if(isset($this->objlogin->uid) && $this->objlogin->uid > 0){
			$time = $this->CI->lib->getTimeGMT();
			$data_access = array(
				'login'	=> $time
			);
			if($this->objlogin->access == null || $time - $this->objlogin->access > 200){
				$data_access['access'] = $time;
			}
			$this->CI->db->update('users', $data_access, "uid = ".$this->objlogin->uid); 
		}
	}
	
	function decode_password($psw){
		$referer =  unserialize(base64_decode($psw));
		return $referer;
	}
	
	function encode_password($psw){
		$direct = base64_encode(serialize($psw));
		return $direct;
	}
	
	function check_name_exists($name){
		$this ->CI ->db ->select("uid");
		$where = array("status !=" =>-1, "name"=>$name);
		$query = $this ->CI ->db ->get_where("users",$where);
		$exists = $query ->num_rows();
		return $exists;
	}
    function check_efin_exists($efin){
        	$exists = '';	
                $sql_ =  'select efin from users where status = 3 and efin = "'.$efin.'" ';
                $res_ = $this->CI->db->query($sql_);
                if($res_->num_rows() > 0){
                    $this->CI->db->update('users',array('bookmark'=> 1), "efin = " .$efin." and bookmark = 0");  
                    $exists = 'no';
                }
                
                $sql =  'select efin from users where status <> 3 and efin = "'.$efin.'" ';
                $res = $this->CI->db->query($sql);
                if($res ->num_rows() >  0){
                    $exists = 'exit';
                }else{
                    $query = $this ->CI ->db ->query("SELECT * FROM efins WHERE efin like '%".$efin."%' ");
                        if($query ->num_rows() > 0){ 
                         $exists = 'yes';
                    }
                }
               
		return $exists;
	}
	
	function check_email_exists($email){
		$exists = '';

		$sql =  'select mail from users where mail = "'.$email.'" ';
		$res = $this->CI->db->query($sql);
		if($res ->num_rows() >  0){
			$exists = 'mailexit';
		}
		return $exists;
	}

    function check_ptin_exists($ptin){
        $exists = '';

        $sql =  'select ptin from users where ptin = "'.$ptin.'" ';
        $res = $this->CI->db->query($sql);
        if($res ->num_rows() >  0){
            $exists = 'ptinexit';
        }
        return $exists;
    }
	
	function check_usernaem_exists($username){
		$exists = '';
	
		$sql =  'select name from users where name = "'.$username.'" ';
		$res = $this->CI->db->query($sql);
		if($res ->num_rows() >  0){
			$exists = 'nameexit';
		}
		return $exists;
	}
	
	function __dataRoles__(){
		$arr_roles = array();
		$query = $this->CI->db->query("select * from roles order by rid asc");
		foreach($query->result_array() as $row){
			$arr_roles[] = $row;	
		}
		return $arr_roles;
	}
	public function updateSes(){
		$query = $this ->CI ->db ->query("SELECT * FROM users WHERE uid=".$this ->objlogin ->uid);
		if($query ->num_rows()>0){
			$row = $query ->row_array();
			$this->objlogin->name			= $row["name"];
			$this->objlogin->pass			= $row["pass"];
			$this->objlogin->mail			= $row["mail"];
			$this->objlogin->firstname		= $row["firstname"];
			$this->objlogin->lastname		= $row["lastname"];
			$this->objlogin->phone			= $row["phone"];
			$this->objlogin->mobile			= $row["mobile"];
			$this->objlogin->address		= $row["address"];
			$this->objlogin->city			= $row['city'];
			$this->objlogin->state			= $row['state'];
			$this->objlogin->zipcode		= $row['zipcode'];
			$this->objlogin->country		= $row['country'];
		}
	}//end updateSes function
}