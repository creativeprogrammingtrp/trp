<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_featured extends CI_Model 
{
	public function getFeaturedProducts()
	{
		$arr_products = array();
		$sql = "SELECT (items.current_cost + items.current_cost*IFNULL(product_markup.markup_percentage,0)/100) AS itm_price, items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.itm_featured FROM (product_markup INNER JOIN items_markup ON items_markup.mkey = product_markup.mkey AND product_markup.status <> -1) RIGHT JOIN items ON items_markup.pkey = items.itm_key,categories WHERE items.cat_id = categories.cat_id AND items.itm_status = 1 AND categories.status = 1 AND items.special = 0 AND items.itm_featured = 1";
        if(isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on')
        	$sql .= " AND items.product_type = 0 ";
		$sql .= " ORDER BY items.itm_date DESC";
		
        $re = $this->db->query($sql);
		$count = 0;
		foreach ($re->result_array() as $row)
		{
			$row['promotions'] = $this->lib->load_items_promotion($row['itm_key']);
			if(count($row['promotions']) <= 0 && $row['itm_featured'] != 1)
				continue;
			if($count>=5)
				break;	
			$row['outOfStock'] = (is_numeric($row['inventories'])&&$row['inventories']>0)?'':"(Out Of Stock)";
			$arr_file = $this->lib->__loadFileProduct__($row['itm_id']);
			$row['file'] = $arr_file['file']!=""?$this->lib->shop_data_url() . 'thumb_home/' . $arr_file['file']:$this->system->URL_server__()."application/views/".$this->system->theme."/shop/images/212x192.jpg";
			$row['itm_price'] = number_format($row['itm_price'] ,2);
			$row['stars'] = $this->lib->load_items_stars($row['itm_id']);
			$arr_products[] = $row;		
			$count++;
		}//foreach
		return $arr_products;
	}//function getFeaturedProducts
}//class M_featured