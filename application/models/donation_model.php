<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donation_model extends CI_Model 
{
	
	public function loadDataUsers()
	{
		$arrUsers = array();
	
		$arr_key = array();
		if(isset($_POST['key_word']) && trim($_POST['key_word']) != ''){
			$key_word = $this ->lib ->escape($_POST['key_word']);
			$key_word = str_replace("  ", " ", $key_word);
			$arr_key = explode(" ", $key_word);	
		}
		
		$sql_raises_type = '';
		if(isset($_POST['raises_type']) && $_POST['raises_type'] != ''){
			$sql_raises_type = " and role = ".$_POST['raises_type'];	
		}
		$sql = "select * from raises where 1=1 $sql_raises_type order by date_raise DESC";
		$re = $this->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$row['created_str'] = date("m/d/Y H:m:s", strtotime($row['date_raise']));
			$legal_business_id = '';
			$legal_business_name = '';
			$table = '';
			switch($row['role']){
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
				case 0:
					$table = 'raises';
					break;
			}
			
			if($table == '') continue;
			if($table == 'raises'){
				$row['legal_business_id'] = '';
				$row['legal_business_name'] = 'Employee';
				$arrUsers[] = $row; 
				continue;
			}
			
			$sql_key = '';
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$sql_key .= " and (";
						$sql_key .= " legal_business_id like '%$key%'";
						$sql_key .= " or legal_business_name like '%$key%'";
						$sql_key .= " or address like '%$key%'";
						$sql_key .= " or city like '%$key%'";
						$sql_key .= " or state like '%$key%'";
						$sql_key .= " or zipcode like '%$key%'";
						$sql_key .= " or country like '%$key%'";
						$sql_key .= " or phone like '%$key%'";
						$sql_key .= " or fax like '%$key%'";
						$sql_key .= " or website like '%$key%'";
						$sql_key .= " ) ";	
					}
				}	
			}
			
			$re_2 = $this ->db ->query("select legal_business_id,legal_business_name,address,city,state,zipcode from $table where legal_business_id = '".$row['legal_business_id']."' $sql_key ");
			$row_2 = $re_2 ->row_array();
			if(count($row_2)>0){
				$legal_business_id = $row_2['legal_business_id'];
				$legal_business_name = $row_2['legal_business_name'];	
			}
			
			if($legal_business_id == '' || $legal_business_name == '') continue;
			
			$row['legal_business_id'] = $legal_business_id;
			$row['legal_business_name'] = $legal_business_name;
			
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end loadDataUsers function
}//end donation_model class