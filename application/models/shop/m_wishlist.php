<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_wishlist extends CI_Model 
{
	public function getWishlist()
	{
		$arr_products = array();
		$re = $this->db->query("SELECT (items.current_cost + items.current_cost*IFNULL(product_markup.markup_percentage,0)/100) AS itm_price, items.itm_id,items.itm_key,items.itm_name,items.itm_model,items.origin,items.inventories,items.itm_featured,items.product_type FROM (product_markup INNER JOIN items_markup ON items_markup.mkey = product_markup.mkey), (items INNER JOIN items_wishlist ON items.itm_key = items_wishlist.itm_key) WHERE product_markup.status <> -1 AND items_markup.pkey = items.itm_key AND items_wishlist.uid = ".$this->author->objlogin->uid." ORDER BY items_wishlist.wid DESC");
		foreach($re->result_array() as $row)
		{
			$arr_file = $this->lib->__loadFileProduct__($row['itm_id'], 'thumb_home');
			$row['video'] = $arr_file['video'];
			$row['file'] = $this->lib->shop_data_url().'thumb/'.$arr_file['file'];
			
			$row['outOfStock'] = '<i class="icon-remove-sign"></i>';
			if(is_numeric($row['inventories'])&&$row['inventories']>0)
				$row['outOfStock'] = '<i class="icon-ok"></i>';
				
			$row['promotions'] = $this->lib->load_items_promotion($row['itm_key']);
			$row['itm_price'] = number_format($row['itm_price'] ,2);
                        $stringButton = $this->lib->addToCartButton($row['itm_key'],$row['product_type'],$row['inventories']);
                        $cutButton = explode('|', $stringButton);
                        unset($cutButton[1]);
                        $row['addCartButton'] = $cutButton[0];
                        $stars = $this->lib->load_items_stars($row['itm_id']);
                        $row['rating'] = $stars;
                     
			$arr_products[] = $row;
		}
		return array("data"=>$arr_products);
	}//function getWishlist
	
	public function deleteItem($key='')
	{
		if(!is_numeric($this->author->objlogin->uid) || $this->author->objlogin->uid<=0)
			return 0;//Anonymous user
		if(!is_string($key) || trim($key)=='')
			return -1;//product key is invalid
		$key = $this->lib->escape($key);	
		$this->db->where(array("itm_key"=>$key, "uid"=>$this->author->objlogin->uid));
		$this->db->delete("items_wishlist");
		return 1;
	}//function deleteItem
	
	public function addItem($key='')
	{
		if(!is_numeric($this->author->objlogin->uid) || $this->author->objlogin->uid<=0)
			return 0;//Anonymous user
		if(!is_string($key) || trim($key)=='')
			return -1;//product key is invalid
		$key = $this->lib->escape($key);	
		$itm_id = $this->database->db_result("SELECT itm_id FROM items WHERE itm_key='$key'");
		if(!is_numeric($itm_id) || $itm_id<=0) 
			return -2;//product is invalid
		$wid = $this->database->db_result("SELECT wid FROM items_wishlist WHERE itm_key='$key' AND uid = ".$this->author->objlogin->uid);
		if(is_numeric($wid) && $wid>0) 
			return -3;//This product has been in your wishlist
		$this->db->query("INSERT INTO items_wishlist(itm_key,uid) VALUES('$key',".$this->author->objlogin->uid.")");
		return 1;
	}//function addItem
}//class M_wishlist