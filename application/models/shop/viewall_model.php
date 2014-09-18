<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viewall_model extends CI_Model 
{
	var $all_data = array();
	var $number_link_display = 6;
	var $total_item = 0;
	function __construct()
    {
        parent::__construct();
		$this->load->library('lib');
	}
	
	function get_special_products()
	{
        $re = '';
        if(isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on'){
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured, items.itm_date from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and product_type = 0 and items.special = 1 order by items.itm_date DESC");
        }else{
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured, items.itm_date from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and items.special = 1 order by items.itm_date DESC");
        }
		foreach ($re->result_array() as $row){
		$itm_key = $row['itm_key'];
		$row['promotions'] = $this->lib->load_items_promotion($itm_key);
		$row['outOfStock'] = (is_numeric($row['inventories'])&&$row['inventories']>0)?'':"(Out Of Stock)";
		$row['alias_name'] = $row['itm_name'];
		if(strlen($row['itm_name']) >40){
			$temp = substr($row['itm_name'],0,20);
			$row['alias_name'] = (strripos($temp,' ')>0)? substr($temp,0,strripos($temp,' ')).'...' : substr($temp,0,20).'...';	
		}
		if(count($row['promotions']) > 0 || $row['itm_featured'] == 1){
			$arr_file = $this->lib->__loadFileProduct__($row['itm_id']);
			$row['file'] = $this->lib->shop_data_url().'thumb_home/'.$arr_file['file'];
			$current_cost = (is_numeric($row['current_cost']) && $row['current_cost'] > 0)?$row['current_cost']:0;
			$re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
			if($row_price = $re_price->row_array()){
				$markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0)?$row_price['markup_percentage']:0;
				$current_cost = $current_cost + $current_cost * $markup_percentage / 100;	
			}
			$row['itm_price'] = round( $current_cost, 2, PHP_ROUND_HALF_UP);
			$row['stars'] = $this->lib->load_items_stars($row['itm_id']);
			$row['link'] = $this->system->URL_server__()."shop/item_details?itemid=".$row['itm_key'];
			$this->all_data[] = $row;	
		}
		}
		$this->total_items = count($this->all_data);
		
		return array("total_items" => $this->total_items,
					"data"=>$this->all_data,
					"number_link_display"=>$this->number_link_display);
	}
	
	function get_featured_products()
	{
        $re = '';
        if(isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on'){
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured, items.itm_date from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and product_type = 0 and items.special = 0 and items.itm_featured = 1 order by items.itm_date DESC");
        }else{
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured, items.itm_date from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and items.special = 0 and items.itm_featured = 1 order by items.itm_date DESC");
        }
		foreach ($re->result_array() as $row){
		$itm_key = $row['itm_key'];
		$row['promotions'] = $this->lib->load_items_promotion($itm_key);
		$row['outOfStock'] = (is_numeric($row['inventories'])&&$row['inventories']>0)?'':"(Out Of Stock)";
		$row['alias_name'] = $row['itm_name'];
		if(strlen($row['itm_name']) >40){
			$temp = substr($row['itm_name'],0,40);
			$row['alias_name'] = (strripos($temp,' ')>0)? substr($temp,0,strripos($temp,' ')).'...' : substr($temp,0,40).'...';	
		}
		if(count($row['promotions']) > 0 || $row['itm_featured'] == 1){
			$arr_file = $this->lib->__loadFileProduct__($row['itm_id']);
			$row['file'] = $this->lib->shop_data_url().'thumb_home/'.$arr_file['file'];
			$current_cost = (is_numeric($row['current_cost']) && $row['current_cost'] > 0)?$row['current_cost']:0;
			$re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
			if($row_price = $re_price->row_array()){
				$markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0)?$row_price['markup_percentage']:0;
				$current_cost = $current_cost + $current_cost * $markup_percentage / 100;	
			}
			$row['itm_price'] = round( $current_cost, 2, PHP_ROUND_HALF_UP);
			$row['stars'] = $this->lib->load_items_stars($row['itm_id']);
			$row['link'] = $this->system->URL_server__()."shop/item_details?itemid=".$row['itm_key'];
			$this->all_data[] = $row;	
		}
		}
		$this->total_items = count($this->all_data);
		
		return array("total_items" => $this->total_items,
					"data"=>$this->all_data,
					"number_link_display"=>$this->number_link_display);
	}
}