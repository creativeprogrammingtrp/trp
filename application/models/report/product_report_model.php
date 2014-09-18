<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_report_model extends CI_Model 
{
	public function loadingproducts($key_word='',$catid='',$featured='',$manufacturer='',$page=1)
	{
		$num_per_pager = 20;
		$sql_limit = '';
		if ($page !== -1)
		{
			$page = (is_numeric($page)&&$page>0)? $page:1;
			$limit = $num_per_pager*($page-1);
			$sql_limit = " limit $limit,".$num_per_pager;
		}
		else
		{
			$sql_limit = "";
		}
		
		
		$ong_chu = $this ->lib -> __loadBoss__();	
		$sql_role = '';
		if($this ->author ->objlogin ->role['rid'] == MANUFACTURER){
			$sql_role = " and items.uid = ".$ong_chu;	
		}
		
		$key_word_sql = '';
		if(trim($key_word) != ''){
			$key_word = $this ->lib ->escape($key_word);
			$key_word = str_replace("  ", " ", $key_word);
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_sql .= " and (";
						$key_word_sql .= " items.itm_key like '%$key%'";
						$key_word_sql .= " or items.itm_name like '%$key%'";
						$key_word_sql .= " or items.itm_model like '%$key%'";
						$key_word_sql .= " or items.itm_description like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		
		$sql_featured = '';
		if(trim($featured)!= ''){
			$sql_featured = " and items.itm_featured = '".trim($featured)."'";	
		}
		
		$sql_manufacturer = '';
		if(trim($manufacturer) != ''){
			$sql_manufacturer = " and items.uid = '".trim($manufacturer)."'";	
		}
		
		$where = "$sql_role $key_word_sql $sql_featured $sql_manufacturer";
		$sql = "select count(itm_key) as count_key from items where itm_status = 1 $where order by itm_date DESC";
		$temp_query = $this->db ->query($sql);
		$temp_row = $temp_query ->row_array();
		$maxlength = count($temp_row) >0 ? $temp_row['count_key']:0;
		$sql = "select items.itm_id,items.itm_key,items.itm_name,items.itm_model,items.inventories from items where itm_status = 1 $where order by itm_date DESC ".$sql_limit;
		if($catid != ''){
			if($catid == '0'){
				$sql = "select count(itm_key) as count_key from items where itm_status = 1 and cat_id = 0 $where order by itm_date DESC";
				$temp_query = $this->db ->query($sql);
				$temp_row = $temp_query ->row_array();
				$maxlength = count($temp_row) >0 ? $temp_row['count_key']:0;
				$sql = "select items.itm_id,items.itm_key,items.itm_name,items.itm_model,items.inventories from items where itm_status = 1 and cat_id = 0 $where order by itm_date DESC  ".$sql_limit;	
			}else{
				$sql = "select count(items.itm_key) as count_key from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.cat_key = '$catid' $where order by items.itm_date DESC";
				$temp_query = $this->db ->query($sql);
				$temp_row = $temp_query ->row_array();
				$maxlength = count($temp_row) >0 ? $temp_row['count_key']:0;
				$sql = "select items.itm_id,items.itm_key,items.itm_name,items.itm_model,items.inventories from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.cat_key = '$catid' $where order by items.itm_date DESC ".$sql_limit;		
			}
		}
		$arr_products = array();
		$re = $this ->db ->query($sql);
		foreach($re ->result_array() as $row)
		{
			$arr_file = $this ->lib -> __loadFileProduct__($row['itm_id'], 'thumb_slide');
			$row['file'] = $arr_file['file'];
			$temp_query = $this->db ->query("select count(id) as count_id from items_view where itm_key = '".$row['itm_key']."'");
			$temp_row = $temp_query ->row_array();
			$view = count($temp_row) >0 ? $temp_row['count_id']:0;
			$row['view'] = $view;
			
			$sold = 0;
			$re_1 = $this ->db ->query("select quality from order_detais where itemid = ".$row['itm_id']);
			foreach($re_1 ->result_array() as $row_1)
			{
				$sold += $row_1['quality'];	
			} 
			$row['sold'] = $sold;
			$arr_products[] = $row;
		}
		return array('data'=>$arr_products, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page, 'rid'=>(int)$this ->author->objlogin ->role['rid']);
	}//end loadingproducts function
	
	function loadManufacturer($manufacturer)
	{
		$str = '';
		if($this ->author ->objlogin ->role['rid'] != MANUFACTURER || $this ->author ->objlogin ->role['rid'] != CHARITY){
			$str .= '<span style="float:left; padding-left:5px">';
			$str .= '<select id="manufacturer" style="width:220px; color:#AEAEAE">';
			$str .= '<option value="" style="color:#AEAEAE">All Vendors</option>';
			$re2 = $this ->db ->query("select manufacturers.legal_business_name,manufacturers.uid from manufacturers join users on manufacturers.uid = users.uid  where users.status = 1 order by manufacturers.legal_business_name ASC");
			foreach($re2 ->result_array() as $row2)
			{
				$select = '';
				if($manufacturer != '' && $manufacturer == $row2['uid']) $select = 'selected="selected"';
				$str .= "<option value='".$row2['uid']."' $select>".$row2['legal_business_name']."</option>";	
			}
                        
                        $sql_manu = $this->db->query("select charities.legal_business_name,charities.legal_business_id,charities.uid from users join charities on charities.uid = users.uid where charities.trust = 1 and users.status <> -1");
                        foreach($sql_manu ->result_array() as $row_manu)
			{
				$select = '';
				if($manufacturer != '' && $manufacturer == $row_manu['uid']) $select = 'selected="selected"';
				$str .= "<option value='".$row_manu['uid']."' $select>".$row_manu['legal_business_name']."</option>";	
			}
			$str .= '</select>';
			$str .= '</span>';
		}
		return $str;
	}//end loadManufacturer function

}//end Product_report_model class

