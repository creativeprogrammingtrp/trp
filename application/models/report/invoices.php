<?php
if( ! defined('BASEPATH')) exit ('No direct script access allowed');
class Invoices extends CI_Model{
    public function __construct() {
        parent::__construct();
    }

    public function loadObjChecks(){
	$arr = array();
	$roles = array();
	$re = $this->db->query("select * from roles");
	foreach($re->result_array() as $row){
		$roles[$row['rid']] = $row['name'];	
	}
	$sql_role = '';
	if(isset($_POST['role']) && $_POST['role'] != ''){
		$sql_role = 'and role = '.$_POST['role'];
	}
	$key_word_sql = '';
	if(isset($_POST['key_word']) && trim($_POST['key_word']) != ''){
		$key_word = $this->lib->escape($this->input->post('key_word'));
		$key_word = str_replace("  ", " ", $key_word);
		$arr_key = explode(" ", $key_word);
		foreach($arr_key as $key){
			if($key != ''){
				$key_word_sql .= " and (";
				$key_word_sql .= " id = '$key'";
				$key_word_sql .= " or pkey like '%$key%'";
				$key_word_sql .= " or check_number like '%$key%'";
				$key_word_sql .= " or legal_business_id like '%$key%'";
				$key_word_sql .= " or legal_business_name like '%$key%'";
				$key_word_sql .= " ) ";	
			}	
		}	
	}
	$re = $this->db->query("select * from payments where 1=1 $sql_role $key_word_sql order by date_pay ASC");
	foreach($re->result_array() as $row){
		$pay = $row['pay'];
		if($row['role'] == 5){
			$re2 = $this->db->query("select okey,pay from payments_orders where pkey = '".$row['pkey']."'");
			foreach($re2->result_array() as $row2){
				$pay += $row2['pay'];
			}	
		}
		$legal_business_name = $row['legal_business_name'];
		if($legal_business_name == null || $legal_business_name == ''){
			$table = '';
			switch((int)$row['role']){
				case 0:
					$table = 'acquisition_agent';
					break;
				case 12:
					$table = 'acquisition_agent';
					break;
				case 6:
					$table = 'tbaffiliates';
					break;	
				case 5:
					$table = 'manufacturers';
					break;
				case 8:
					$table = 'charities';
					break;
				case 9:
					$table = 'representatives';
					break;		
			}
			if($row['role'] == 0){
				$legal_business_name = "Employees";	
			}else{
				$re_2 = $this->db->query("select legal_business_name from $table where legal_business_id = '".$row['legal_business_id']."' ");
				if($re_2->num_rows() > 0){
                                    $row_2 = $re_2->num_array();
                                    $legal_business_name = $row_2['legal_business_name'];
				}
			}	
		}
		$arr[] = array(
			'pkey'      => $row['pkey'],
			'memo'      => sprintf('%06d', $row['id']),
			'check'     => $row['check_number'],
			'name'      => $legal_business_name,
			'type'      => isset($roles[$row['role']])?$roles[$row['role']]:'Employees',
			'date'      => date("M j, Y, g:i a", strtotime($row['date_pay'])),
			'pay'       => (float)$pay,
                        '@roles@'   => $this->loadRolesList()
		);
	}
	return $arr;
    }
    
    public function loadRolesList(){
	$st = '';
	$re = $this->db->query("select * from roles where rid > 4");
	foreach($re->result_array() as $row){
		$st .= '<option value="'.$row['rid'].'">'.$row['name'].'</option>';
	}
	return $st;
    }
    
    public function viewData($key){
	$created_str = '';
	$legal_business_name = '';
	$address = '';
	$invoi_number = 1;
	$pay = 0;
	$title = '';
	$orders_ID = '';
	$tblcontries = array();
	$re = $this->db->query("select * from tblcontries");
	foreach($re->result_array() as $row){
		$tblcontries[$row['code']] = $row['name'];	
	}
	$re = $this->db->query("select * from payments where pkey = '$key'");
	if($re->num_rows() > 0){
                $row = $re->row_array();
		$invoi_number = $row['id'];
		$created_str = date("m/d/Y", strtotime($row['date_pay']));
		$legal_business_name = $row['legal_business_name'];	
		$table = '';
		switch($row['role']){
			case 0:
				$table = 'acquisition_agent';
				$title = 'Commission Employees';
				break;
			case 12:
				$table = 'acquisition_agent';
				$title = 'Commission acquisition agent';
				break;
			case 6:
				$table = 'tbaffiliates';
				$title = 'Commission affiliates';
				break;	
			case 5:
				$table = 'manufacturers';
				$title = 'Manufacturers';
				break;
			case 8:
				$table = 'charities';
				$title = 'Commission charities';
				break;
			case 9:
				$table = 'representatives';
				$title = 'Commission sale representatives';
				break;		
		}
		if($row['role'] == 0){
			$legal_business_id = "None";
			$address = "None";	
		}else{
			$re_2 = $this->db->query("select legal_business_name,address,city,state,zipcode,country from $table where legal_business_id = '".$row['legal_business_id']."' ");
                        if($re_2->num_rows() > 0){
                                $row_2 = $re_2->row_array();
				$address = $row_2['address'].'<br>'.$row_2['city'].', '.$row_2['state'].' '.$row_2['zipcode'].'<br>'.(isset($tblcontries[$row_2['country']])?$tblcontries[$row_2['country']]:$row_2['country']);
			}
		}
		if($row['role'] == 5){
			$re_2 = $this->db->query("select okey,pay from payments_orders where pkey = '".$row['pkey']."' ");
			foreach($re_2->result_array() as $row_2){
				$pay += $row_2['pay'];
				$orders_ID .= $row_2['okey'].', ';
			}	
		}else{
			$pay = $row['pay'];		
		}
	}
	$this->load->library('num2text');
	$n2s = $this->num2text->convertDolla($pay);
	if($orders_ID != ''){
		$orders_ID = substr($orders_ID, 0, strlen($orders_ID) - 2);
                $view = array();
                $view['order_key'] = '<td align="left" valign="top"><b>Order # :</b></td><td align="left" valign="top">'.$orders_ID.'</td></tr>';
	}
        $view['title']           = $title;
        $view['date']            = $created_str;
        $view['pay_to']          = $legal_business_name;
        $view['address']         = $address;
        $view['invoice_number']  = sprintf('%06d', $invoi_number);
        $view['Total']           = number_format($pay, 2);
        $view['n2s']             = $n2s;
        
        return $view;
    }
}