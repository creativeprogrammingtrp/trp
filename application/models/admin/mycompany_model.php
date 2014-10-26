<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mycompany_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function loadList() {
        $data = array();
        //$sql = "select ero.*,rid,email,pass,users.uid as user_id,users.status from users join ero join users_roles on users.uid = ero.uid and users_roles.uid = users.uid where users.status <> -1 and users.uid =" . $this->author->objlogin->uid;
       $sql = "select ero.*,rid,email,pass,users.uid as user_id,users.status from users
				INNER JOIN ero on ero.uid = users.uid
				INNER JOIN users_roles ur on ur.uid = ero.author
 				where users.status <> -1 and ero.status_ero <> -1 and users.uid =". $this->author->objlogin->uid;
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    function loadListForAdmin($eroid) {
        $data = array();
        //$sql = "select ero.*,rid,email,pass,users.uid as user_id,users.status from users join ero join users_roles on users.uid = ero.uid and users_roles.uid = users.uid where users.status <> -1 and users.uid =" . $this->author->objlogin->uid;
        $sql = "select ero.*,rid,email,pass,users.uid as user_id,users.status from users
				INNER JOIN ero on ero.uid = users.uid
				INNER JOIN users_roles ur on ur.uid = ero.author
 				where users.status <> -1 and ero.status_ero <> -1 and users.uid =". $eroid;
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    function loadEmployeeList() {
        $data = array();
       // echo $sql = "select ero.*,rid,email,pass,users.uid as user_id,users.status from users join ero join users_roles on users.uid = ero.author and users_roles.uid = users.uid where users.status <> -1 and ero.status_ero <> -1 and users.uid =" . $this->author->objlogin->uid;
       $sql = "select ero.*,rid,email,pass,users.uid as user_id,users.status from users 
				INNER JOIN ero on ero.uid = users.uid
				INNER JOIN users_roles ur on ur.uid = ero.author
 				where users.status <> -1 and ero.status_ero <> -1 and users.uid =". $this->author->objlogin->uid;
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
    function loadInfor() {
        $check = false;
        $sql_1 = "select * from master_ero  where uid = '" . $this->author->objlogin->uid . "' ";
        $res_1 = $this->db->query($sql_1);
        if ($res_1->num_rows() > 0) {
            $row_1 = $res_1->row_array();
            if ($row_1['image'] !== '') {
                $row_1['image'] = '<img  src="' . $this->system->URL_server__() . 'data/logo/' . $row_1['image'] . '">';
            } else {
                $row_1['image'] = '';
            }
            $check = true;
        }
        $sql_2 = "select users.*,roles.name as role from users join users_roles join roles on users.uid = users_roles.uid and users_roles.rid = roles.rid where users.uid = '" . $this->author->objlogin->uid . "' ";
        $res_2 = $this->db->query($sql_2);
        if ($res_2->num_rows() > 0) {
            $row_2 = $res_2->row_array();
            $row_2['pass'] = $this->author->decode_password($row_2['pass']);
            $row_2['disabled'] = ($this->author->objlogin->role['rid'] == 5) ? 'yes' : 'no';
        }

        if ($check == true) {
            $result = array_merge($row_1, $row_2);
        } else {
            $result = $row_2;
        }
        return $result;
    }
    
    

    function saveCompany() {
        $file_id = (isset($_POST['file_id']) && $_POST['file_id'] != '') ? $_POST['file_id'] : '';
        $ext = $_POST['ext'];
        $filename = $file_id . '.' . $ext;

        $query1 = $this->db->get_where('master_ero', array('p_efin' => $this->lib->escape($_POST['p_efin']), 'uid' => $this->author->objlogin->uid));
        
        $data = array(
            'p_efin' => $this->lib->escape($_POST['p_efin']),
        		'service_bureau_num' => $this->lib->escape($_POST['service_bureau_num']),
        		'is_parent_efin' => $this->lib->escape($_POST['is_parent_efin']),
        		'is_service_bureau' => $this->lib->escape($_POST['is_service_bureau']),
        		
        		'is_view' => $this->lib->escape($_POST['is_view']),
        		'pefin_status' => $this->lib->escape($_POST['pefin_status']),
        		
            'image' => $this->lib->escape($filename),
            'company_name' => $this->lib->escape($_POST['company_name']),
        	'business_phone' => $this->lib->escape($_POST['com_phoneno']),
            'business_addr_1' => $this->lib->escape($_POST['address_1']),
            'business_addr_2' => $this->lib->escape($_POST['address_2']),
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
            'date_created' => $this->lib->getTimeGMT()
        );
        $query = $this->db->get_where('master_ero', array('uid' => $this->author->objlogin->uid));
        if ($query->num_rows() > 0) {
            $this->db->where('uid', $this->author->objlogin->uid);
            $this->db->update('master_ero', $data);
        } else {
            $data['uid'] = $this->author->objlogin->uid;
            $data['efin'] = $this->lib->escape($_POST['efin']);
            $this->db->insert("master_ero", $data);
        }
        
       
        if ($query1->num_rows() > 0) {
        	
        }else{
        	$data1 = array(
        			'uid' => $this->author->objlogin->uid,
        			'efin' => $this->lib->escape($_POST['efin']),
        			'pefin' => $this->lib->escape($_POST['p_efin']),
        			'service_buraue' => $this->lib->escape($_POST['service_bureau_num']),
        			'add_date' => $this->lib->getTimeGMT()
        			);
        	$this->db->insert("efin_pefin", $data1);
        }
       
        
        
        return $this->loadInfor();
    }

    function saveCompanyFromAdmin() {
        $file_id = (isset($_POST['file_id']) && $_POST['file_id'] != '') ? $_POST['file_id'] : '';
        $ext = $_POST['ext'];
        $filename = $file_id . '.' . $ext;

        $ero_id =  $_POST['ero_id'];

        $query1 = $this->db->get_where('master_ero', array('p_efin' => $this->lib->escape($_POST['p_efin']), 'uid' => $ero_id));

        $data = array(
            'p_efin' => $this->lib->escape($_POST['p_efin']),
            'service_bureau_num' => $this->lib->escape($_POST['service_bureau_num']),
            'is_parent_efin' => $this->lib->escape($_POST['is_parent_efin']),
            'is_service_bureau' => $this->lib->escape($_POST['is_service_bureau']),

            'is_view' => $this->lib->escape($_POST['is_view']),
            'pefin_status' => $this->lib->escape($_POST['pefin_status']),

            'image' => $this->lib->escape($filename),
            'company_name' => $this->lib->escape($_POST['company_name']),
            'business_phone' => $this->lib->escape($_POST['com_phoneno']),
            'business_addr_1' => $this->lib->escape($_POST['address_1']),
            'business_addr_2' => $this->lib->escape($_POST['address_2']),
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
            'date_created' => $this->lib->getTimeGMT()
        );
        $query = $this->db->get_where('master_ero', array('uid' => $ero_id));
        if ($query->num_rows() > 0) {
            $this->db->where('uid', $ero_id);
            $this->db->update('master_ero', $data);
        } else {
            $data['uid'] = $ero_id;
            $data['efin'] = $this->lib->escape($_POST['efin']);
            $this->db->insert("master_ero", $data);
        }


        if ($query1->num_rows() > 0) {

        }else{
            $data1 = array(
                'uid' => $ero_id,
                'efin' => $this->lib->escape($_POST['efin']),
                'pefin' => $this->lib->escape($_POST['p_efin']),
                'service_buraue' => $this->lib->escape($_POST['service_bureau_num']),
                'add_date' => $this->lib->getTimeGMT()
            );
            $this->db->insert("efin_pefin", $data1);
        }



        return $this->ero->loadAllErosForAdmin();
    }

    function saveSetupCompany() {
    	$file_id = (isset($_POST['file_id']) && $_POST['file_id'] != '') ? $_POST['file_id'] : '';
    	$ext = $_POST['ext'];
    	$filename = $file_id . '.' . $ext;
    
    	$data = array(
    			'p_efin' => $this->lib->escape($_POST['p_efin']),
    			'image' => $this->lib->escape($filename),
    			'company_name' => $this->lib->escape($_POST['company_name']),
    			'business_addr_1' => $this->lib->escape($_POST['address_1']),
    			'business_addr_2' => $this->lib->escape($_POST['address_2']),
                'business_phone' => $this->lib->escape($_POST['phoneno']),
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
    			'date_created' => $this->lib->getTimeGMT(),
    			'complete_status' => $this->lib->escape($_POST['complete_status']),
    	);
    	$query = $this->db->get_where('master_ero', array('uid' => $this->author->objlogin->uid));
    	if ($query->num_rows() > 0) {
    		$this->db->where('uid', $this->author->objlogin->uid);
    		$this->db->update('master_ero', $data);
    	} else {
    		$data['uid'] = $this->author->objlogin->uid;
    		$data['efin'] = $this->lib->escape($_POST['efin']);
    		$this->db->insert("master_ero", $data);
    	}
    
    	// if(isset($_POST['bank_name'])){
    	$this->saveBankAccount();
    	//}
    
    	// if(isset($_POST['tax_preparation_fee'])){
    	$this->saveBankFees();
    	// }
    
    	$this->saveSetupProfile();
    
    
    	return $this->loadInfor();
    }
    
    function checkParentEFINStatus(){
    	$query = $this->db->get_where('users', array('efin' => $this->lib->escape($_POST['p_efin'])));
    	 if($query->num_rows() > 0) { return 1;}else{  return 0;}	
    }
    
    function checkSBEFINStatus(){
    	$query = $this->db->get_where('users', array('efin' => $this->lib->escape($_POST['service_bureau_num'])));
    	if($query->num_rows() > 0) { return 1;}else{  return 0;}
    }
    
    
    function saveSetupProfile() {
    	 
    	if($_POST['complete_status'] == '1'){
    		$status = '1';
    	}else{
    		$status = '4';
    	}
    	 
    	$data = array(
    			'name' => $this->lib->escape($_POST['username']),
    			'ptin' => $this->lib->escape($_POST['ptin']),
    			//'pass' 				=> $this ->author ->encode_password(trim($_POST['pass'])),
    			'mail' => $this->lib->escape($_POST['email']),
    			'firstname' => $this->lib->escape($_POST['first_name']),
    			'lastname' => $this->lib->escape($_POST['last_name']),
    			'phone' => $this->lib->escape($_POST['phone']),
    			// 'mobile' => $this->lib->escape($_POST['cell_phone']),
    			'created' => strtotime(gmdate("Y-m-d H:i:s")),
    			'access' => strtotime(gmdate("Y-m-d H:i:s")),
    			'login' => strtotime(gmdate("Y-m-d H:i:s")),
    			'data' => 1,
    			'status' => $status
    	);
    
    	$this->db->where("uid", $this->author->objlogin->uid);
    	$this->db->update('users', $data);
    	return $this->loadInfor();
    }
    
    function saveProfile() {
    	
        $data = array(
            'name' => $this->lib->escape($_POST['username']),
            'ptin' => $this->lib->escape($_POST['ptin']),
            //'pass' 				=> $this ->author ->encode_password(trim($_POST['pass'])),
            'mail' => $this->lib->escape($_POST['email']),
            'firstname' => $this->lib->escape($_POST['first_name']),
            'lastname' => $this->lib->escape($_POST['last_name']),
            'phone' => $this->lib->escape($_POST['phone']),
           // 'mobile' => $this->lib->escape($_POST['cell_phone']),
            'created' => strtotime(gmdate("Y-m-d H:i:s")),
            'access' => strtotime(gmdate("Y-m-d H:i:s")),
            'login' => strtotime(gmdate("Y-m-d H:i:s")),
            'data' => 1,
  
        );

        $this->db->where("uid", $this->author->objlogin->uid);
        $this->db->update('users', $data);
        return $this->loadInfor();
    }

    function saveProfileFromAdmin() {

        $ero_id = $this->lib->escape($_POST['ero_id']);
        $data = array(
            'name' => $this->lib->escape($_POST['username']),
            'ptin' => $this->lib->escape($_POST['ptin']),
            //'pass' 				=> $this ->author ->encode_password(trim($_POST['pass'])),
            'mail' => $this->lib->escape($_POST['email']),
            'firstname' => $this->lib->escape($_POST['first_name']),
            'lastname' => $this->lib->escape($_POST['last_name']),
            'phone' => $this->lib->escape($_POST['phone']),
            // 'mobile' => $this->lib->escape($_POST['cell_phone']),
            'created' => strtotime(gmdate("Y-m-d H:i:s")),
            'access' => strtotime(gmdate("Y-m-d H:i:s")),
            'login' => strtotime(gmdate("Y-m-d H:i:s")),
            'data' => 1,

        );

        $this->db->where("uid", $ero_id);
        $this->db->update('users', $data);
        return  $this->ero->loadAllErosForAdmin();
    }


    
    function saveBankAccount(){
    	$data = array(
    			'bank_name' => 	$this->lib->escape($_POST['bank_name']),
    			'bank_routing' => $this->lib->escape($_POST['bank_routing']),
    			'bank_account' => $this->lib->escape($_POST['b_account_name'])
    	);
    	
    	$this->db->where('uid', $this->author->objlogin->uid);
        $this->db->update('master_ero', $data);
    	return $this->loadInfor();
    }

    function saveBankAccountFromAdmin(){
        $ero_id = $this->lib->escape($_POST['ero_id']);
        $data = array(
            'bank_name' => 	$this->lib->escape($_POST['bank_name']),
            'bank_routing' => $this->lib->escape($_POST['bank_routing']),
            'bank_account' => $this->lib->escape($_POST['b_account_name'])
        );

        $this->db->where('uid', $ero_id);
        $this->db->update('master_ero', $data);
        return  $this->ero->loadAllErosForAdmin();
    }




    function saveBankFees(){

        if($this->lib->escape($_POST['add_on_fee']) != 0){
            $addonfeeComm = 10.00;
        }else{
            $addonfeeComm = 0.00;
        }


    	$data = array(
    			'tax_preparation_fee' => 	$this->lib->escape($_POST['tax_preparation_fee']),
        		'bank_transmission_fee' => $this->lib->escape($_POST['bank_transmission_fee']),
        		'sb_fee' => $this->lib->escape($_POST['sb_fee']),
        		//'e_file_fee' => $this->lib->escape($_POST['e_file_fee']),
        		'add_on_fee' => $this->lib->escape($_POST['add_on_fee']),
            'add_on_commission_type' => '1',
            'add_on_commission' => $addonfeeComm
    	);
    	 
    	$this->db->where('uid', $this->author->objlogin->uid);
    	$this->db->update('master_ero', $data);
    	return $this->loadInfor();
    }

    function saveBankFeesFromAdmin(){
        $ero_id = $this->lib->escape($_POST['ero_id']);

        if($this->lib->escape($_POST['add_on_fee']) != 0){
            $addonfeeComm = 10.00;
        }else{
            $addonfeeComm = 0.00;
        }

        $data = array(
            'tax_preparation_fee' => 	$this->lib->escape($_POST['tax_preparation_fee']),
            'bank_transmission_fee' => $this->lib->escape($_POST['bank_transmission_fee']),
            'sb_fee' => $this->lib->escape($_POST['sb_fee']),
            //'e_file_fee' => $this->lib->escape($_POST['e_file_fee']),
            'add_on_fee' => $this->lib->escape($_POST['add_on_fee']),
            'tax_pre_commission_type' => $this->lib->escape($_POST['tax_preparation_commission1_type']),
            'tax_pre_commission' => $this->lib->escape($_POST['tax_preparation_commission1']),
            'add_on_commission_type' => 1,
            'add_on_commission' => $addonfeeComm
        );

        $this->db->where('uid', $ero_id);
        $this->db->update('master_ero', $data);
        return  $this->ero->loadAllErosForAdmin();
    }
    
    
    function resetPass() {
        $current_pass = $this->author->encode_password($this->lib->escape($_POST['current_p']));
        $query = $this->db->get_where('users', array('uid' => $this->author->objlogin->uid, 'pass' => $current_pass));
        if ($query->num_rows() > 0) {
            $data = array(
                'pass' => $this->author->encode_password(trim($_POST['new_p']))
            );
            $this->db->where("uid", $this->author->objlogin->uid);
            $this->db->update('users', $data);
            return 'ok';
        } else {
            return 'Wrong current password';
        }
    }

    function addUser() {
        $cid = $this->lib->escape($_POST['cid']);
        $author = $this->lib->escape($_POST['author']);

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
            'efin' => $this->lib->escape($_POST['efin']),
            'ptin' => $this->lib->escape($_POST['ptin']),
            'is_employee' => '1'
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
                $data_ero['uid'] = $this->author->objlogin->uid;
                $data_ero['author'] = $last_id;
                $this->db->insert('ero', $data_ero);
                $users_roles = array(
                    'uid' => $last_id,
                    'rid' => $this->lib->escape($_POST['role'])
                );
                $this->db->insert('users_roles', $users_roles);


                $data_masterero = array(
                    'uid' => $last_id
                );

                $this->db->insert(' master_ero', $data_masterero);
            } 
        }
        return $this->loadList();
    }

    function addUserFromAdmin() {
        $cid = $this->lib->escape($_POST['cid']);
        $author = $this->lib->escape($_POST['author']);
        $ero_id = $this->lib->escape($_POST['ero_id']);

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
            'is_employee' => '1'
        );
        $sql = "select uid  from users where uid = '" . $ero_id . "' ";
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
                $data_ero['uid'] = $ero_id;
                $data_ero['author'] = $last_id;
                $this->db->insert('ero', $data_ero);
                $users_roles = array(
                    'uid' => $last_id,
                    'rid' => $this->lib->escape($_POST['role'])
                );
                $this->db->insert('users_roles', $users_roles);

                $data_masterero = array(
                    'uid' => $last_id
                );

                $this->db->insert(' master_ero', $data_masterero);
            }
        }
        return $this->loadListForAdmin($ero_id);
    }

    function deleteUser() {
        $author = $_POST['author'];
        $cid = $_POST['cid'];
        $this->db->where("uid", $author);
        $this->db->update('users', array('status' => -1));
        $this->db->where("cid", $cid);
        $this->db->update('ero', array('status_ero' => 2));
        return $this->loadList();
    }

	function deleteService() {
        $s_id = $_POST['s_id'];
        $this->db->where("id", $s_id);
        $this->db->delete('service');
        return $this->loadServiceList();
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
			'uid' => $this->author->objlogin->uid
        );
		
		$sql = "select uid  from service where id = '" . $s_id . "' ";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0){
            $this->db->where('id', $s_id);
            $this->db->update('service', $data);
        } else {
        	$this->db->insert('service', $data);
		}
		return $this->loadServiceList();
    }
	
	function loadServiceList(){
		$data = array();
        $sql = "select * from service where uid =" . $this->author->objlogin->uid;
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
			if($row['logo_service'] != '' && $row['logo_service'] != '.')
				$row['logo'] = '<img  style="width:80px;" src="' . $this->system->URL_server__() . 'data/logo/' . $row['logo_service'] . '">';
			else
				$row['logo'] = '';
            $row['cost'] = number_format($row['cost'],2);
            $row['sb_saleprice'] = number_format($row['sb_saleprice'],2);
            $row['ero_saleprice'] = number_format($row['ero_saleprice'],2);
            $data[] = $row;
        }
        return $data;	
	}
	
	
	function loadStatesList() {
		//$data = array();
		$html = '';
		$sql = "select * from tblsates where idcountry = 1";
		$res = $this->db->query($sql);
		//print_r ($res->result_array());
		//exit;
		//$html .= '';
		foreach ($res->result_array() as $row) {
			 // $data = $res->row_array();
			 $name = $row['name'];
			 
			 $html .= '<option value="'.$name.'">'.$name.'</option>';
			 
		}
		//$html .= '</select>';
		//print_r($html);
		//exit;
		return $html;
	}
	
	function loadApplicentList() {
		//$data = array();
		$html = '';
		/*$sql = "select app_id,uid,first_name,last_name,ss_number from new_app ORDER BY first_name ASC";
		$res = $this->db->query($sql);
		//print_r ($res->result_array());
		//exit;
		//$html .= '';
		foreach ($res->result_array() as $row) {
			// $data = $res->row_array();
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$ss_number = $row['ss_number'];
			$app_id = $row['app_id'];
			$last4SsNo = explode('-',$ss_number);
			
			$html .= '<tr><td><input type="checkbox" name="selecctall" id="selecctall" value=""></td><td id="'.$app_id.'">'.$first_name.' '.$last_name.' (XXX-XX-'.$last4SsNo[2].')</td></tr>';
	
			//$html .= '<option value="'.$app_id.'">'.$first_name.' '.$last_name.' (XXX-XX-'.$last4SsNo[2].')</option>';
	
		}
		
		*/
		
		$sql1 = "select applicent_id as app_id,uid,first_name,last_name,ss_number from new_applicent where uid = '".$this->author->objlogin->uid."' ORDER BY first_name ASC";
		$res1 = $this->db->query($sql1);
		
		foreach ($res1->result_array() as $row1) {
			// $data = $res->row_array();
			$first_name1 = $row1['first_name'];
			$last_name1 = $row1['last_name'];
			$ss_number1 = $row1['ss_number'];
			$app_id1 = $row1['app_id'];
			$last4SsNo1 = explode('-',$ss_number1);
			
			$html .= '<tr class="tr'.$app_id1.'"><td><button onclick="javascript:gettablevalue2(this);" id="'.$app_id1.'" value="'.$app_id1.'" type="button" class="btn btn-success btn-sm '.$app_id1.'"><i class="icon-plus"></i></button></td><td onclick="javascript:gettablevalue(this);" id="'.$app_id1.'">'.$first_name1.' '.$last_name1.' (XXX-XX-'.$last4SsNo1[2].')</td></tr>';
		
			//$html .= '<option value="'.$app_id1.'">'.$first_name1.' '.$last_name1.' (XXX-XX-'.$last4SsNo1[2].')</option>';
		
		}
		
		//$html .= '</select>';
		//print_r($html);
		//exit;
		return $html;
	}
	
	function loadApplicentListBySearchText($text) {
		//$data = array();
		$html = '';
		
		$selectedcus = explode(',',$this->lib->escape($_POST['selectedCust']));
		
		
		$sql1 = "select applicent_id as app_id,uid,first_name,last_name,ss_number from new_applicent where uid = '".$this->author->objlogin->uid."' AND first_name like '{$text}%' ORDER BY first_name ASC";
		$res1 = $this->db->query($sql1);
	
		foreach ($res1->result_array() as $row1) {
			// $data = $res->row_array();
			$first_name1 = $row1['first_name'];
			$last_name1 = $row1['last_name'];
			$ss_number1 = $row1['ss_number'];
			$app_id1 = $row1['app_id'];
			$last4SsNo1 = explode('-',$ss_number1);
				
			if (in_array($app_id1, $selectedcus)) {
				$html .= '<tr class="tr'.$app_id1.'"><td><button onclick="removeSelectedApplicentInfo2(this);" id="'.$app_id1.'" value="'.$app_id1.'" type="button" class="btn btn-danger btn-sm '.$app_id1.'"><i class="icon-plus"></i></button></td><td onclick="javascript:gettablevalue(this);" id="'.$app_id1.'">'.$first_name1.' '.$last_name1.' (XXX-XX-'.$last4SsNo1[2].')</td></tr>';
			}else{
			//$html .= '<tr><td><input type="checkbox" name="selecctall" id="selecctall" value=""></td><td onclick="javascript:gettablevalue(this);" id="'.$app_id1.'">'.$first_name1.' '.$last_name1.' (XXX-XX-'.$last4SsNo1[2].')</td></tr>';
				$html .= '<tr class="tr'.$app_id1.'"><td><button onclick="javascript:gettablevalue2(this);" id="'.$app_id1.'" value="'.$app_id1.'" type="button" class="btn btn-success btn-sm '.$app_id1.'"><i class="icon-plus"></i></button></td><td onclick="javascript:gettablevalue(this);" id="'.$app_id1.'">'.$first_name1.' '.$last_name1.' (XXX-XX-'.$last4SsNo1[2].')</td></tr>';
			}
	
		}
	
		
		return $html;
	}
	
	function loadCountryList() {
		//$data = array();
		$html = '';
		$sql = "select * from tblcontries ORDER BY name ASC";
		$res = $this->db->query($sql);
		//print_r ($res->result_array());
		//exit;
		//$html .= '';
		foreach ($res->result_array() as $row) {
			// $data = $res->row_array();
			$name = $row['name'];
	
			$html .= '<option value="'.$name.'">'.$name.'</option>';
	
		}
		//$html .= '</select>';
		//print_r($html);
		//exit;
		return $html;
	}
	

}

?>
