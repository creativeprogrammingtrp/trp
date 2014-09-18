<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shome_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('lib');
    }

    function get_special_products() {
        $arr_products = array();
        $re = '';
        //isset($_SESSION['_CART']) and is_array($_SESSION['_CART'])
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and product_type = 0 and items.special = 1 order by items.itm_date DESC");
        } else {
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and items.special = 1 order by items.itm_date DESC");
        }
        foreach ($re->result_array() as $row) {
            $itm_key = $row['itm_key'];
            $row['promotions'] = $this->lib->load_items_promotion($itm_key);
            $row['outOfStock'] = (is_numeric($row['inventories']) && $row['inventories'] > 0) ? '' : "(Out Of Stock)";
            $row['alias_name'] = $row['itm_name'];
            if (strlen($row['itm_name']) > 40) {
                $temp = substr($row['itm_name'], 0, 20);
                $row['alias_name'] = (strripos($temp, ' ') > 0) ? substr($temp, 0, strripos($temp, ' ')) . '...' : substr($temp, 0, 20) . '...';
            }
            if (count($row['promotions']) > 0 || $row['itm_featured'] == 1) {
                $arr_file = $this->lib->__loadFileProduct__($row['itm_id']);
                //$row['video'] = $arr_file['video'];
                $row['file'] = $arr_file['file']!=""?$this->lib->shop_data_url() . 'thumb_home/' . $arr_file['file']:$this->system->URL_server__()."application/views/".$this->system->theme."/shop/images/212x192.jpg";
                $current_cost = (is_numeric($row['current_cost']) && $row['current_cost'] > 0) ? $row['current_cost'] : 0;
                $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                if ($row_price = $re_price->row_array()) {
                    $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? $row_price['markup_percentage'] : 0;
                    $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
                }
                $row['itm_price'] = number_format(round($current_cost, 2, PHP_ROUND_HALF_UP), 2);
                $row['stars'] = $this->lib->load_items_stars($row['itm_id']);
                $arr_products[] = $row;
            }
        }
        return $arr_products;
    }

    function get_featured_products() {
        $arr_products = array();
        $re = '';
        //isset($_SESSION['_CART']) and is_array($_SESSION['_CART'])
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured,items.product_type,items.minimum_in_stock from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and product_type = 0 and items.special = 0 and items.itm_featured = 1 order by items.itm_date DESC  LIMIT 0, 12");
        } else {
            $re = $this->db->query("select items.itm_id,items.itm_key,items.itm_name,items.origin,items.inventories,items.current_cost,items.itm_featured,items.product_type,items.minimum_in_stock from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 and items.special = 0 and items.itm_featured = 1 order by items.itm_date DESC  LIMIT 0, 12 ");
        }
        foreach ($re->result_array() as $row) {
            $itm_key = $row['itm_key'];
            $row['promotions'] = $this->lib->load_items_promotion($itm_key);
            $row['outOfStock'] = (is_numeric($row['inventories']) && $row['inventories'] > 0) ? '' : '<p style="color:#FF0000;">(Out Of Stock)</p>';
            $row['alias_name'] = $row['itm_name'];
            if (strlen($row['itm_name']) > 40) {
                $temp = substr($row['itm_name'], 0, 40);
                $row['alias_name'] = (strripos($temp, ' ') > 0) ? substr($temp, 0, strripos($temp, ' ')) . '...' : substr($temp, 0, 40) . '...';
            }
            if (count($row['promotions']) > 0 || $row['itm_featured'] == 1) {
                $arr_file = $this->lib->__loadFileProduct__($row['itm_id']);
                //$row['video'] = $arr_file['video'];
                $row['file'] = $arr_file['file']!=""?$this->lib->shop_data_url() . 'thumb_home/' . $arr_file['file']:$this->system->URL_server__()."application/views/".$this->system->theme."/shop/images/212x192.jpg";
                $current_cost = (is_numeric($row['current_cost']) && $row['current_cost'] > 0) ? $row['current_cost'] : 0;
                $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                if ($row_price = $re_price->row_array()) {
                    $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? $row_price['markup_percentage'] : 0;
                    $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
                }
                $row['itm_price'] = number_format(round($current_cost, 2, PHP_ROUND_HALF_UP), 2);
                $row['stars'] = $this->lib->load_items_stars($row['itm_id']);
                //$arr_products[] = $row;
            }
            $cartButton = $this->lib->addToCartButton($itm_key,$row['product_type'],$row['inventories']);
            $stringButton = explode('|', $cartButton);
            $row['addCart'] = $stringButton[0];
            $row['Wishlist'] = $stringButton[1];
            $arr_products[] = $row;
            
        }
        return $arr_products;
    }
	public function loadBanners()
	{
		$query = $this->db->query("SELECT CONCAT('".$this->system->URL_server__()."resource/banner/shome/', link) AS link, IF(url='','javascript:void(0);',url) AS url FROM banners_home WHERE status = 1 ORDER BY weight DESC");	
		return $query->result_array();
	}//function loadBanners

}