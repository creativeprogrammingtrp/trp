<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ero_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }
    
    public function loadAllEros() {
    	$data = array();
    	$sql = "";
    	//print_r($this->author->objlogin);
    	//exit;
    	if ($this->author->objlogin->role['rid'] == 5) {
    		 $sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
    
AND users_roles.rid = 5
AND efin_pefin.pefin =".$this->author->objlogin->efin." 
        ORDER BY users.uid DESC";
    	} else {
    		 $sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where status <> 3 and  users_roles.rid = 5 ORDER BY users.uid DESC";
    	}
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["created"]);
    		if ($row['image'] !== '') {
    			$row['image'] = '<img  src="' . $this->system->URL_server__() . 'data/logo/' . $row['image'] . '">';
    		} else {
    			$row['image'] = '';
    		}
    		$data[] = $row;
    	}
    	return $data;
    }

    
    public function loadAllErosForAdmin() {
    	$data = array();
    	$sql = "";
    	//print_r($this->author->objlogin);
    	//exit;
    	if ($this->author->objlogin->role['rid'] == 5) {
    		$sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
    
AND users_roles.rid = 5
AND efin_pefin.pefin =".$this->author->objlogin->efin."
        ORDER BY users.uid DESC";
    	} else {
    		$sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where  users_roles.rid = 5 ORDER BY users.uid DESC";
    	}
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["created"]);
    		if ($row['image'] !== '') {
    			$row['image'] = '<img  src="' . $this->system->URL_server__() . 'data/logo/' . $row['image'] . '">';
    		} else {
    			$row['image'] = '';
    		}
    		$data[] = $row;
    	}
    	return $data;
    }
    
    public function loadAllAccounts() {
        $data = array();
        $sql = "";
        //print_r($this->author->objlogin);
        //exit;
        if ($this->author->objlogin->role['rid'] == 5) {
            $sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid

AND users_roles.rid = 5
AND efin_pefin.pefin =".$this->author->objlogin->efin."
      ORDER BY users.uid DESC  ";
        } else {
            $sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where status <> 3 and  users_roles.rid = 5 ORDER BY users.uid DESC";
        }
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", $row["created"]);
            if ($row['image'] !== '') {
                $row['image'] = '<img  src="' . $this->system->URL_server__() . 'data/logo/' . $row['image'] . '">';
            } else {
                $row['image'] = '';
            }
            $data[] = $row;
        }
        return $data;
    }

    public function loadEro() {
        $data = array();
        $sql = "";
        if ($this->author->objlogin->role['rid'] == 5) {
            $sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
AND users.status =1 
AND users_roles.rid = 5
AND master_ero.p_efin =".$this->author->objlogin->efin."
		ORDER BY users.uid DESC";
        } else {
            $sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role,users.uid as user_uid  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where status = 1 and  users_roles.rid = 5 ORDER BY users.uid DESC";
        }
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", $row["created"]);
            $data[] = $row;
        }
        return $data;
    }

    public function loadAccounts() {
    	$data = array();
    	$sql = "";
    	if ($this->author->objlogin->role['rid'] == 5) {
    		$sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
AND efin_pefin.status = 0
AND users_roles.rid = 5
AND efin_pefin.pefin =".$this->author->objlogin->efin." ORDER BY users.uid DESC";
    	} else {
    		$sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role,users.uid as user_uid  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where status = 1 and  users_roles.rid = 5 ORDER BY users.uid DESC";
    	}
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["created"]);
    		$data[] = $row;
    	}
    	return $data;
    }
    
    public function loadApprovedEro() {
        $data = array();
        $sql = "";
        if ($this->author->objlogin->role['rid'] == 5) {
            $sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
AND efin_pefin.status = 1
AND users_roles.rid = 5
AND master_ero.p_efin =".$this->author->objlogin->efin." ORDER BY users.uid DESC";
        } else {
            $sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where status = 2 and  users_roles.rid = 5 ORDER BY users.uid DESC";
        }
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $row["format_date"] = gmdate("m/d/y", $row["created"]);
            $data[] = $row;
        }
        return $data;
    }
    
    public function loadApprovedAccounts() {
    	$data = array();
    	$sql = "";
    	if ($this->author->objlogin->role['rid'] == 5) {
    		$sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
AND efin_pefin.status = 1
AND users_roles.rid = 5
AND master_ero.p_efin =".$this->author->objlogin->efin." ORDER BY users.uid DESC";
    	} else {
    		$sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where status = 2 and  users_roles.rid = 5 ORDER BY users.uid DESC";
    	}
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["created"]);
    		$data[] = $row;
    	}
    	return $data;
    }
    
    
    public function loadPendingRegistrationEro() {
    	$data = array();
    	$sql = "";
    	if ($this->author->objlogin->role['rid'] == 5) {
    	 	$sql = "select * from users join users_roles  on users.uid = users_roles.uid where users_roles.rid = 9 and  status = 4 ORDER BY users.uid DESC ";
    	} else {
    		$sql = "select *,users.name as username ,users.efin as user_efin,roles.name as role  from users join roles  join users_roles join master_ero  on users_roles.rid = roles.rid and  users.uid = users_roles.uid and users.uid = master_ero.uid where status = 4 and  users_roles.rid = 5 ORDER BY users.uid DESC";
    	}
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$row["format_date"] = gmdate("m/d/y", $row["created"]);
    		$data[] = $row;
    	}
    	return $data;
    }

    public function loadRejectedEro() {
        $data = array();
        $sql = "";
        if ($this->author->objlogin->role['rid'] == 5) {
            $sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
AND efin_pefin.status = 2
AND users_roles.rid = 5
AND efin_pefin.pefin =".$this->author->objlogin->efin." ORDER BY users.uid DESC";
        } else {
            $sql = "select * from users join users_roles  on users.uid = users_roles.uid  where  users_roles.rid = 5 and status = 3 ORDER BY users.uid DESC";
        }

        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $efin = $row['efin'];
            $sql = $this->db->query("select efin from users where status <> 3 and  efin='" . $efin . "' ");
            if ($sql->num_rows() > 0) {
                $res = $sql->row_array();
                $row['new_efin'] = $res['efin'];
            }
            $row["format_date"] = gmdate("m/d/y", $row["created"]);
            $data[] = $row;
        }
        return $data;
    }
    
    public function loadRejectedAccounts() {
    	$data = array();
    	$sql = "";
    	if ($this->author->objlogin->role['rid'] == 5) {
    		$sql = "SELECT users.* , roles.*, users_roles.*, master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
AND efin_pefin.status = 2
AND users_roles.rid = 5
AND efin_pefin.pefin =".$this->author->objlogin->efin." ORDER BY users.uid DESC";
    	} else {
    		$sql = "select * from users join users_roles  on users.uid = users_roles.uid  where  users_roles.rid = 5 and status = 3 ORDER BY users.uid DESC";
    	}
    
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$efin = $row['efin'];
    		$sql = $this->db->query("select efin from users where status <> 3 and  efin='" . $efin . "' ");
    		if ($sql->num_rows() > 0) {
    			$res = $sql->row_array();
    			$row['new_efin'] = $res['efin'];
    		}
    		$row["format_date"] = gmdate("m/d/y", $row["created"]);
    		$data[] = $row;
    	}
    	return $data;
    }

    public function approveEro($efin) {
    	if($this->author->objlogin->role['rid'] == 5){
    	$sql = "update users set status = 2 where status = 1 and efin = '" . $efin . "' ";
    	
    	// update master ero with parent efin status
    	$sql2 = "update  master_ero set is_view = 1, pefin_status = 1 where pefin_status = 0 and efin = '" . $efin . "' ";
    	$this->db->query($sql2);
    	
    	// update efin_pefin ero with parent efin status
    	$sql3 = "update efin_pefin set status = 1, is_view = 1, approved_date = '".$this->lib->getTimeGMT()."' where status = 0 and efin = '" . $efin . "' ";
    	$this->db->query($sql3);
    	
    	}else{
        $sql = "update users set status = 4 where status = 1 and efin = '" . $efin . "' ";
    	}
        $this->db->query($sql);
        return $this->loadEro();
    }

    public function rejectEro($efin) {
    	if($this->author->objlogin->role['rid'] == 5){
	    	// update master ero with parent efin status
	    	$sql2 = "update  master_ero set is_view = 1, pefin_status = 2 where pefin_status = 0 and efin = '" . $efin . "' ";
	    	$this->db->query($sql2);
	    	 
	    	// update efin_pefin ero with parent efin status
	    	$sql3 = "update efin_pefin set status = 2, is_view = 1, reject_date = '".$this->lib->getTimeGMT()."' where status = 0 and efin = '" . $efin . "' ";
	    	$this->db->query($sql3);
    	}
    	
        $sql = "update users set status = 3 where status = 1 and efin = '" . $efin . "' ";
        $this->db->query($sql);
        return $this->loadEro();
    }

    public function rejectApproved($efin) {
    	
    	if($this->author->objlogin->role['rid'] == 5){
    		// update master ero with parent efin status
    		$sql2 = "update  master_ero set is_view = 1, pefin_status = 2 where pefin_status = 1 and efin = '" . $efin . "' ";
    		$this->db->query($sql2);
    		 
    		// update efin_pefin ero with parent efin status
    		$sql3 = "update efin_pefin set status = 2, is_view = 1, reject_date = '".$this->lib->getTimeGMT()."' where status = 1 and efin = '" . $efin . "' ";
    		$this->db->query($sql3);
    	}
    	
    	
        $sql = "update users set status = 3 where status = 2 and efin = '" . $efin . "' ";
        $this->db->query($sql);
        return $this->loadApprovedEro();
    }

    public function approveFromReject($efin) {
    	
    	if($this->author->objlogin->role['rid'] == 5){
    		// update master ero with parent efin status
    		$sql2 = "update  master_ero set is_view = 1, pefin_status = 1 where pefin_status = 2 and efin = '" . $efin . "' ";
    		$this->db->query($sql2);
    		 
    		// update efin_pefin ero with parent efin status
    		$sql3 = "update efin_pefin set status = 1, is_view = 1, approved_date = '".$this->lib->getTimeGMT()."' where status = 2 and efin = '" . $efin . "' ";
    		$this->db->query($sql3);
    	}
    	
    	
        $sql = "update users set status = 1 where status = 3 and bookmark = 0 and efin = '" . $efin . "' ";
        $this->db->query($sql);
        return $this->loadRejectedEro();
    }

    public function deleteEro($efin) {
        $sql = "delete from users where efin = '" . $efin . "' ";
        $this->db->query($sql);
        return $this->loadAllEros();
    }

    public function saveCompanyInfo() {
        $image = '';
        if (isset($_POST['obj_image']) && is_array($_POST['obj_image'])) {
            $arr_img = $_POST['obj_image'];
            $image = $arr_img['file_id'] . '.' . $arr_img['ext'];
        }

        $data = array(
            'p_efin' => $this->lib->escape($_POST['p_efin']),
            'image' => $image,
            'company_name' => $this->lib->escape($_POST['company_name']),
            'business_addr_1' => $this->lib->escape($_POST['address_1']),
            'business_addr_2' => $this->lib->escape($_POST['address_2']),
        	'business_phone' => $this->lib->escape($_POST['com_phoneno']),
            'business_zip' => $this->lib->escape($_POST['zipcode']),
            'business_city' => $this->lib->escape($_POST['city']),
            'business_state' => $this->lib->escape($_POST['state']),
            'same_as' => $this->lib->escape($_POST['same_as']),
            'mail_addr_1' => $this->lib->escape($_POST['address_1_m']),
            'mail_addr_2' => $this->lib->escape($_POST['address_2_m']),
            'mail_zip' => $this->lib->escape($_POST['zipcode_m']),
            'mail_city' => $this->lib->escape($_POST['city_m']),
            'mail_state' => $this->lib->escape($_POST['state_m']),
            'tax_software' => $this->lib->escape($_POST['tax']),
            //'bank_name' => $this->lib->escape($_POST['bank_name']),
            //'bank_routing' => $this->lib->escape($_POST['bank_routing']),
            // 'bank_account' => $this->lib->escape($_POST['bank_account']),
            // 'addon' => $this->lib->escape($_POST['addon']),
            //'file' => $this->lib->escape($_POST['file']),
            // 'agprep' => $this->lib->escape($_POST['agprep']),
            // 'ag' => $this->lib->escape($_POST['ag']),
            'date_created' => $this->lib->getTimeGMT()
        );
        $this->db->where('uid', $this->lib->escape($_POST['uid']));
        $this->db->update('master_ero', $data);
        if ($_POST['option'] == 'allero') {
            return $this->loadAllEros();
        } else if ($_POST['option'] == 'pendingero') {
            return $this->loadEro();
        } else {
            return $this->loadApprovedEro();
        }
    }

    public function saveProfileInfo() {
        $data = array(
            'name' => $this->lib->escape($_POST['username']),
        	'ptin' => $this->lib->escape($_POST['ptin']),
            'mail' => $this->lib->escape($_POST['email']),
            'firstname' => $this->lib->escape($_POST['first_name']),
            //'middlename' => $this->lib->escape($_POST['middle_name']),
            'lastname' => $this->lib->escape($_POST['last_name']),
            'phone' => $this->lib->escape($_POST['phone']),
            //'mobile' => $this->lib->escape($_POST['cell_phone']),
            // 'address' => $this->lib->escape($_POST['address']),
            // 'city' => $this->lib->escape($_POST['city']),
            // 'state' => $this->lib->escape($_POST['state']),
            //'zipcode' => $this->lib->escape($_POST['zipcode']),
            'created' => strtotime(gmdate("Y-m-d H:i:s")),
            'access' => strtotime(gmdate("Y-m-d H:i:s")),
            'login' => strtotime(gmdate("Y-m-d H:i:s"))
        );

        $this->db->where("uid", $this->lib->escape($_POST['uid']));
        $this->db->update('users', $data);
        if ($_POST['option'] == 'allero') {
            return $this->loadAllEros();
        } else if ($_POST['option'] == 'pendingero') {
            return $this->loadEro();
        } else {
            return $this->loadApprovedEro();
        }
    }
    
    public function savePaymentInfo() {
    	$data = array(
    			'bank_name' => $this->lib->escape($_POST['bank_name']),
    			'bank_routing' => $this->lib->escape($_POST['bank_routing']),
    			'bank_account' => $this->lib->escape($_POST['b_account_name']),
    			
    	);
    
     	$this->db->where('uid', $this->lib->escape($_POST['uid']));
        $this->db->update('master_ero', $data);
        if ($_POST['option'] == 'allero') {
            return $this->loadAllEros();
        } else if ($_POST['option'] == 'pendingero') {
            return $this->loadEro();
        } else {
            return $this->loadApprovedEro();
        }
    }
    
    public function saveBankInfo() {
    	$data = array(
    			'tax_preparation_fee' => 	$this->lib->escape($_POST['tax_preparation_fee']),
        		'bank_transmission_fee' => $this->lib->escape($_POST['bank_transmission_fee']),
        		'sb_fee' => $this->lib->escape($_POST['sb_fee']),
        		//'e_file_fee' => $this->lib->escape($_POST['e_file_fee']),
        		'add_on_fee' => $this->lib->escape($_POST['add_on_fee'])
    			 
    	);
    
    	$this->db->where('uid', $this->lib->escape($_POST['uid']));
    	$this->db->update('master_ero', $data);
    	if ($_POST['option'] == 'allero') {
    		return $this->loadAllEros();
    	} else if ($_POST['option'] == 'pendingero') {
    		return $this->loadEro();
    	} else {
    		return $this->loadApprovedEro();
    	}
    }
    
    public function saveEroStatusChangeInfo() {
    	$data = array(
    			'status' => $this->lib->escape($_POST['ero_status'])
    
    	);
    
    	$this->db->where('uid', $this->lib->escape($_POST['uid']));
    	$this->db->update('users', $data);

        if($this->author->objlogin->uid != '1'){
            if ($_POST['option'] == 'allero') {

                return $this->loadAllEros();
            } else if ($_POST['option'] == 'pendingero') {
                return $this->loadEro();
            }elseif ($_POST['option'] == 'pendingregistrationero'){
                return $this->loadPendingRegistrationEro();
            }
            else {
                return $this->loadApprovedEro();
            }
        }else{
            if ($_POST['option'] == 'allero') {

                return $this->loadAllErosForAdmin();
            }
        }
    }

    public function resetPassword() {
        $current_pass = $this->author->encode_password($this->lib->escape($_POST['current_p']));
        $uid = (isset($_POST['uid']) && !empty($_POST['uid'])) ? $this->lib->escape($_POST['uid']) : '';
        $query = $this->db->get_where('users', array('uid' => $uid, 'pass' => $current_pass));
        if ($query->num_rows() > 0) {
            $data = array(
                'pass' => $this->author->encode_password(trim($_POST['new_p']))
            );
            $this->db->where("uid", $uid);
            $this->db->update('users', $data);
            return 'ok';
        } else {
            return 'Wrong current password';
        }
    }

    public function ShowUserLists() {
        $data = array();
        $uid = (isset($_POST['uid']) && !empty($_POST['uid'])) ? $this->lib->escape($_POST['uid']) : '';
        $sql = "select ero.*,rid,email,pass,users.uid as user_id,users.status from users join ero join users_roles on users.uid = ero.uid and users_roles.uid = users.uid where users.status <> -1 and ero.status_ero <> -1 and users.uid =" . $uid; 
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    public function loadServiceList() {
        $data = array();
        $uid = (isset($_POST['uid']) && !empty($_POST['uid'])) ? $this->lib->escape($_POST['uid']) : '';
        $sql = "select * from service where uid =" . $uid;
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            if ($row['logo_service'] != '' && $row['logo_service'] != '.')
                $row['logo'] = '<img  style="width:80px;" src="' . $this->system->URL_server__() . 'data/logo/' . $row['logo_service'] . '">';
            else
                $row['logo'] = '';
            $row['cost'] = number_format($row['cost'], 2);
            $row['sb_saleprice'] = number_format($row['sb_saleprice'], 2);
            $row['ero_saleprice'] = number_format($row['ero_saleprice'], 2);
            $data[] = $row;
        }
        return $data;
    }

    function saveService() {
        $file_id = (isset($_POST['file_id']) && $_POST['file_id'] != '') ? $_POST['file_id'] : '';
        $ext = $_POST['ext'];
        $filename = $file_id . '.' . $ext;

        $s_id = $this->lib->escape($_POST['s_id']);

        $data = array(
            'name' => $this->lib->escape($_POST['name']),
            'cost' => $this->lib->escape($_POST['cost']),
            'sb_saleprice' => $this->lib->escape($_POST['sb_saleprice']),
            'ero_saleprice' => $this->lib->escape($_POST['ero_saleprice']),
            'logo_service' => $this->lib->escape($filename),
            'uid' => $this->lib->escape($_POST['uid'])
        );

        $sql = "select uid  from service where id = '" . $s_id . "' ";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            $this->db->where('id', $s_id);
            $this->db->update('service', $data);
        } else {
            $this->db->insert('service', $data);
        }
        return $this->loadServiceList();
    }
    
    public function deleteService() {
        $s_id = $_POST['s_id'];
        $this->db->where("id", $s_id);
        $this->db->delete('service');
        return $this->loadServiceList();
    }
    
    function addUser() {
        $cid = $this->lib->escape($_POST['cid']);
        $author = $this->lib->escape($_POST['author']);
        $uid = $this->lib->escape($_POST['uid']);

        $data_ero = array(
            'ptin' => $this->lib->escape($_POST['ptin']),
            'legal_business_name' => $this->lib->escape($_POST['username']),
            'legal_business_fname' => $this->lib->escape($_POST['first_name']),
            'legal_business_lname' => $this->lib->escape($_POST['last_name']),
            'email' => $this->lib->escape($_POST['add_email']),
        );
        $data = array(
            'name' => $this->lib->escape($_POST['username']),
            'pass' => $this->author->encode_password(trim($this->input->post("pass"))),
            'mail' => $this->lib->escape($_POST['add_email']),
            'firstname' => $this->lib->escape($_POST['first_name']),
            'lastname' => $this->lib->escape($_POST['last_name']),
            'created' => strtotime(gmdate("Y-m-d H:i:s")),
            'access' => strtotime(gmdate("Y-m-d H:i:s")),
            'login' => strtotime(gmdate("Y-m-d H:i:s")),
            'ptin' => $this->lib->escape($_POST['ptin']),
        );
        $sql = "select uid  from users where uid = '" . $author . "' ";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            $this->db->where('uid', $author);
            $this->db->update('users', $data);
            $this->db->where('cid', $cid);
            $this->db->update('ero', $data_ero);
        } else {
            $this->db->insert('users', $data);
            $last_id = $this->db->insert_id();

            if ($last_id > 0) {
                $data_ero['uid'] = $uid;
                $data_ero['author'] = $last_id;
                $this->db->insert('ero', $data_ero);
                $users_roles = array(
                    'uid' => $last_id,
                    'rid' => $this->lib->escape($_POST['role'])
                );
                $this->db->insert('users_roles', $users_roles);
            } 
        }
        return $this->ShowUserLists();
    }
    
    public function deleteUser() {
        $author = $_POST['author'];
        $this->db->where("author", $author);
        $this->db->update('ero', array('status_ero' => -1));
        return $this->ShowUserLists();
    }

}

?>
