<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productsearch_model extends CI_Model 
{
	public static $table_name = "items";
	
	//For Display
	var $page = 1;
	var $pager_data = array();
	var $all_data = array();
	var $direction = "desc";
	var $field = "itm_name";
	var $current_id = NULL;
	var $items_per_page = 20;
	var $total_items = 0;
	var $number_link_display = 6;
	var $total_all_items = 0;
	var $where_filter = "";
	var $arr_filter_monum = array(array(array("itm_name"),"like","filter_name"),array(array("itm_description"),"like","filter_description"));
	var $entity = array("itm_id","itm_name","itm_price");
	
	var $key_field = "id";
	var $field_delete = "itm_status";
	var $value_delete = -1;
	var $type_delete = FALSE;
	
	function __construct()
    {
        parent::__construct();
		$this->load->library("lib");
    }
	
	public function get_total_entries()
	{
		if ($this->type_delete === FALSE)
		{
			$sql = "select count(*) as total from ".self::$table_name." where $this->field_delete<>$this->value_delete";
		}
		else
		{
			$sql = "select count(*) as total from ".self::$table_name;
		}
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$this->total_all_items = $row['total'];
		return $this;
	}
	
	public function get_all_records()
	{	
		$this->get_total_entries();
		$this->_generate_filter_where();
		$this->_generate_filter_cat();
		$sql = "";
		if(isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on'){
           $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured,items.itm_date from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and product_type = 0 ".$this->where_filter." order by items.itm_date DESC");
        }else{
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured,items.itm_date from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 ".$this->where_filter." order by items.itm_date DESC");
        }
		foreach ($re->result_array() as $row){
		$itm_key = $row['itm_key'];
		$row['promotions'] = $this->lib->load_items_promotion($itm_key);
		$row['outOfStock'] = (is_numeric($row['inventories'])&&$row['inventories']>0)?'':'<p style="color:#FF0000;">(Out Of Stock)</p>';
		$row['alias_name'] = $row['itm_name'];
		if(strlen($row['itm_name']) >40){
			$temp = substr($row['itm_name'],0,40);
			$row['alias_name'] = (strripos($temp,' ')>0)? substr($temp,0,strripos($temp,' ')).'...' : substr($temp,0,40).'...';	
		}
		$arr_file = $this->lib->__loadFileProduct__($row['itm_id']);
		$row['file'] = $this->lib->shop_data_url().'thumb_home/'.$arr_file['file'];
		$current_cost = (is_numeric($row['current_cost']) && $row['current_cost'] > 0)?$row['current_cost']:0;
		if(count($row['promotions']) > 0 || $row['itm_featured'] == 1){
			$re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
			if($row_price = $re_price->row_array()){
				$markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0)?$row_price['markup_percentage']:0;
				$current_cost = $current_cost + $current_cost * $markup_percentage / 100;	
			}	
		}
		$row['itm_price'] = round( $current_cost, 2, PHP_ROUND_HALF_UP);
		$row['stars'] = $this->lib->load_items_stars($row['itm_id']);
		$row['link'] = $this->system->URL_server__()."shop/item_details?itemid=".$row['itm_key'];
		$this->all_data[] = $row;
		}

		$this->total_items = count($this->all_data);
		
		return array("total_items" => $this->total_items,
					"data"=>$this->all_data,
					"direction"=>$this->direction,
					"field"=>$this->field,
					"page"=>$this->page,
					"items_per_page"=>$this->items_per_page,
					"number_link_display"=>$this->number_link_display,
					"total_all_items"=>$this->total_all_items);
	}
	
	private function _escape_input($value)
	{
		return $this->lib->escape($value);
	}
	
	private function _escape_output($value)
	{
		return $this->lib->escape($value);
	}
	
	private function _generate_filter_cat()
	{
		$str = "";
		if ($this->input->post('cat_sub'))
		{
			$id = $this->input->post('cat_sub');
			if ($id != 0) $str .= " '$id' ";
			if ($this->input->post('type') == 0)
			{ 
				$str2 = $this->_get_cat_id($id);
				if ($str2 !== "") $str .= ", ".$str2;
			}
		}
		if ($str != "") $this->where_filter .= " AND categories.cat_id in ( $str )";
	}
	
	private function _get_cat_id($id)
	{
		$str = "";
		$sql = " SELECT `cat_id` FROM `categories` WHERE `fid` = $id AND `status` = 1 ";
		$query = $this->db->query($sql);
		foreach ($query->result_array() as $row)
		{
			$str .= ($str=="")?" '".$row['cat_id']." ' ":", '".$row['cat_id']."' ";
			$str2 = $this->_get_cat_id($row['cat_id']);
			if ($str2 !== "") $str .= ", ".$str2;
		}
		return $str;
	}
	
	public function _generate_filter_where()
	{
		$this->where_filter = "";
		if (count($this->arr_filter_monum) > 0)
		{
			for ($l = 0; $l < count($this->arr_filter_monum); $l++)
			{
				$str = "";
				$key = $this->arr_filter_monum[$l]['0'];
				$operator = $this->arr_filter_monum[$l]['1'];
				$critical = $this->input->post($this->arr_filter_monum[$l]['2']);
				
				if ($critical != "")
				for ($i = 0;$i<count($key);$i++)
				{
					switch ($operator)
					{
						case "like":
							if ($str == "") $str .= " ".$key[$i]." like '%".$this->_escape_output($critical)."%' ";	
							else $str .= " or $key like '%".$this->_escape_output($critical)."%' ";
						break;
						case "equal":
							if ($str == "") $str .= " ".$key[$i]." = '".$this->_escape_output($critical)."' ";
							else $str .= " or $key = '".$this->_escape_output($critical)."' ";
						break;
					}
				}

				if (trim($this->where_filter) == "")
				{
					 if ($str !== "") $this->where_filter = " ( ".$str." ) ";
				}
				else
				{
					if ($str !== "") $this->where_filter .= " OR ( ".$str." ) ";
				}
			}
		}
		if ($this->where_filter !== "") $this->where_filter = " AND ( ".$this->where_filter." ) ";
	}
	
	private function _filter_search_keys()
	{
		if (!is_numeric($this->page) || $this->page <= 0) $this->page = 1;
		if ($this->field == NULL || $this->field == "" || !in_array($this->field,$this->entity))
		{
			$this->field = "itm_name";
		}
		if (!in_array($this->direction,array("asc","desc")))
		{
			$this->direction = "desc";
		} 
	}
	
	private function _filter_data()
	{
		return $this;
	}
}