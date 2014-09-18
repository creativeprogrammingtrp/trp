<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Item_details_model extends CI_Model {

    var $wishlist_btn = '';
    var $addtocart_btn = '';
    var $itm_key = '';
    var $itm_id = '';
    var $product_type = 0;
    
    private $__fileImages__ = array('jpg','jpeg','pjpeg','gif','bmp','png');

    function __construct() {
        parent::__construct();
    }

    function loadData() {

        $key = $this->lib->cleanSpecialCharsFromUrl($_GET['itemid']);
        $this->itm_key = $key;
        $description = '';
        $minimum_in_stock = '';
        $item_name = '';
        $item_model = '';
        $img_fb = '';
        $origin = '';
        $review = array();
        $relatedProduct = array();
        $this->wishlist_btn = '';
        $this->addtocart_btn = '';
        $current_cost = 0;
        $total_review = 0;
        $rating = '';
        $totalPoints = 0;
        $instock = 0;
        $inventories = 0;

        $sql = $this->db->query("select items.*,categories.* FROM items join categories on items.cat_id=categories.cat_id  where items.itm_key = '" . $this->itm_key . "' AND items.itm_status > 0");
        if ($sql->num_rows() < 1) {
            header('Location:' . $this->system->URL_server__() . 'shop/shome');
        }
        foreach ($sql->result_array() as $row) {

            $this->itm_id = $row['itm_id'];
            $this->product_type = $row['product_type'];
            $this->addToCart_btn($row['inventories']);
            $current_cost_sub = (is_numeric($row['current_cost']) && $row['current_cost'] > 0) ? (float) $row['current_cost'] : 0;
            $current_cost = $this->itemPrice($this->itm_key, $current_cost_sub);
            $arr_file = $this->lib->__loadFileProduct__($this->itm_id);
            $img_fb = ($arr_file['file']) ? $arr_file['file'] : '';
            $total_review = $this->countReview($this->itm_id);
            $review = $this->loadReviews($this->itm_id);
            $relatedProduct = $this->loadRelatedProducts($this->itm_id);

            $rating = $this->lib->load_items_stars($this->itm_id);

            $totalPoints += $this->totalPoints($this->itm_id);

            if ($row['inventories'] == null || $row['inventories'] <= 0) {
                $instock = '(Out Of Stock)';
            } else {
                $instock = $row['inventories'];
            }

            $description = $row['itm_description'];
            $minimum_in_stock = $row['minimum_in_stock'];
            $item_name = $row['itm_name'];
            $item_model = $row['itm_model'];
            $origin = $row['origin'];
			if (trim($origin) != "") $origin = '<dt>Origin:</dt><dd>'.$origin.'</dd>';
            $inventories = $row['inventories'];
        }
        return $data = array(
            'description' => $this->lib->SQLToFCK($description),
            'minimum_in_stock' => $minimum_in_stock,
            'inventories' => $inventories,
            'in_stock' => ($instock == null || $instock <= 0) ? $instock : '',
            'if("load_instock" == "yes");' => "var instock = parseInt(" . $minimum_in_stock . ");",
            'item_name' => $item_name,
            'item_price' => number_format($current_cost, 2),
            'item_model' => $item_model,
            'itemId' => $this->itm_key,
            'origin' => $origin,
            'item_id' => $this->itm_key,
            'total_Review' => $total_review,
            "if('dataReview'=='ok');" => " dataReview = " . json_encode($review) . ";",
            "if('dataRelated'=='ok');" => " dataRelated = " . json_encode($relatedProduct) . ";",
            'image_big' => $this->lib->shop_data_url() . 'thumb_crop/' . $img_fb,
            'image_small' => $this->lib->shop_data_url() . 'thumb_slide/' . $img_fb,
            'addwishlist' => $this->wishlist_btn ,
            'addToCart' => (isset($instock) && $instock > 0) ? $this->addtocart_btn : '',
            'rating' => $rating,
            'total_Points' => $totalPoints,
            "if('product_image'=='ok');" => " dataImgProduct = " . json_encode($this->loadImgProduct($this->itm_key)) . ";"
           
        );

    }

    function countReview($item_id) {
        $total = 0;
        $sql = "select count(*) as total_review from reviews where itm_id = '$item_id' and status = 1 order by rid desc";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $total += $query->row()->total_review;
        }
        return $total;
    }

    function totalPoints($item_id) {
        $sql = "select sum(rating) as totalPoints from reviews where itm_id = '$item_id' and status = 1 order by rid desc";
        $query = $this->database->db_result($sql);
        return $query;
    }

    function loadSpecialProduct() {
        $data = array();
        $sql = $this->db->query("select items.* FROM items join categories on items.cat_id=categories.cat_id  where items.special = 1 and items.itm_status > 0");
        foreach ($sql->result_array() as $result) {
            $data[] = $result;
        }
        return array('data' => $data);
    }

    function loadReviews($itmId) {
        $loadReview = array();
        $sql = "select * from reviews where itm_id = $itmId and status = 1 order by rdate desc";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row) {
            $row['date_comment'] = gmdate('m/d/Y', strtotime($row['rdate']));
            $row['rcontent'] = $this->lib->SQLToFCK($row['rcontent']);
            $row['rname'] = $this->lib->SQLToFCK($row['rname']);
            $row['rid'] = $row['rid'];
            $loadReview[] = $row;
        }

        return $loadReview;
    }

    function loadRelatedProducts($itm_id) {

        $array_related = array();
        $sql = $this->db->query("SELECT DISTINCT item1,item2 FROM items_related WHERE item1 = '$itm_id' or item2 = '$itm_id'");
        foreach ($sql->result_array() as $row) {
            $_id = ($row['item2'] == $itm_id) ? $row['item1'] : $row['item2'];
            $sql_sub = $this->db->query("SELECT  categories.cat_key, items.itm_id, items.inventories, items.itm_name,items.itm_key,items.itm_name,items.current_cost,items.product_type FROM items join categories on items.cat_id = categories.cat_id WHERE items.itm_id = '{$_id}'  "); //
            foreach ($sql_sub->result_array() as $result) {
				$result['outOfStock'] = (is_numeric($result['inventories']) && $result['inventories'] > 0) ? '' : "<p style='color:#FF0000;'>(Out Of Stock)</p>";
                $current_cost = $this->itemPrice($result['itm_key'], $result['current_cost']);
                $file_ = $this->lib->__loadFileProduct__($_id);
                $result['filename'] = $file_;
                $result['rating'] = $this->lib->load_items_stars($result['itm_id']);
                $result['rating'] = explode('>', $result['rating']);
                unset($result['rating'][0]);
                $result['rating'] = implode('>', $result['rating']);
                $result['current_cost'] = $current_cost;
                $result['stringButton'] = explode('|', $this->addToRelatedButton($result['itm_key'], $result['product_type']));
                $result['addCart'] = $result['stringButton'][0];
                $result['Wishlist'] = $result['stringButton'][1];

                $array_related[] = $result;
            }
        }
        return $array_related;
    }

    function addToRelatedButton($key, $product_type) {

        $wishlist_button = $this->author->objlogin->uid > 0 ? '<button class="btn Wishlist btn-small" data-toggle="tooltip" data-title="+To Wishlist" onClick="addToWishlist(\'' . $key . '\')" ><i class="icon-heart"></i></button>' : '';
        $cart_button = '';
        $arr_location = array();
        //auto delivery
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            if (isset($_SESSION['_CART']) && is_array($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                $cart_button .= '<button class="btn btn-small btn-primary" style="margin-right:3px" onclick=" schedule(\'' . $key . '\',1)" data-title="+To Cart" data-toggle="tooltip" ><i class="icon-eye-open"></i></button>';
            }
        } else {
            if ($product_type == 1) {
                $re_location = $this->db->query("select * from items_locations where ikey = '" . $key . "' and status = 1");
                foreach ($re_location->result_array() as $row_location) {
                    if (trim($row_location['location']) != '')
                        $arr_location[] = $row_location;
                }
                if (count($arr_location) < 1) {


                    $cart_button .= '<span id="add2cart" style="padding-right:3px">';

                    $item_rid = $this->database->db_result("select rid from users_roles join items on users_roles.uid = items.uid where items.itm_key = '" . $key . "'");
                    if ($item_rid != 8)
                        $cart_button .= '<button class="btn btn-primary btn-small" onclick=" return a2c(\'a\', \'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>';
                    else
                    if (isset($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                        $cart_button .= '<button class="btn btn-primary btn-small" onclick="return a2c(\'a\', \'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip" ><i class="icon-shopping-cart"></i></button>';
                    } else {
                        $cart_button .= '<button class="btn btn-primary btn-small" onclick="return donate(\'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>';
                    }
                    $cart_button .= '</span>';
                } else {
                    $cart_button .= '<button class="btn btn-primary findlocation btn-small" style="margin-right:3px" onclick="return find_location(\'' . $key . '\',1)" data-title="Find location" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>';
                }
            } else {
                $cart_button .= '<input type="hidden" value ="1" id="p_qlty" >';
                $cart_button .= '<span id="add2cart" style="padding-right:3px">';
                $cart_button .= '	<button id= "related" class="btn btn-primary btn-small" onclick="return a2c(\'a\', \'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>';
                $cart_button .= '</span>';
            }
        }

        return $cart_button . '|' . $wishlist_button;
    }

    function getItmIdByKey($key) {
        $key = trim(strip_tags($key));
        if ($key == "")
            return -1;
        $sql = "select itm_id from items where itm_key = '{$key}'";
        $query_row = $this->db->query($sql);
        foreach ($query_row->result_array() as $row) {
            $itmId = $row['itm_id'];
        }
        return $itmId;
    }

    function saveReview() {

        $itmkey = $_POST['itm_id'];
        $itmid = $this->getItmIdByKey($itmkey);
        $rname = $_POST['name'];
        $review = $_POST['review'];
        $rating = $_POST['rating'];

        $data = array(
            'itm_id' => $itmid,
            'rname' => $this->lib->FCKToSQL($rname),
            'rcontent' => $this->lib->FCKToSQL($review),
            'rating' => $rating
        );
        $this->db->insert('reviews', $data);
    }

    function addWishlist() {
        if (!isset($_POST['itemID']) || trim($_POST['itemID']) == '')
            return 'invalid product';
        $itemID = $this->lib->escape($_POST['itemID']);
        $sql = $this->db->query("select itm_id from items where itm_key='{$itemID}'");
        $uid = $this->session->userdata('ses_login')->uid;
        if ($sql->num_rows() <= 0)
            return 'invalid product';
        if ($uid <= 0)
            return 'please login!';
        $sql_sub = $this->db->query("select wid from items_wishlist where itm_key='{$itemID}' and uid = $uid");
        if ($sql_sub->num_rows() > 0) {
            return 'This product has been in your wishlist!';
        } else {
            $this->db->query("insert into items_wishlist(itm_key,uid) values('{$itemID}',$uid)");
            return 'Add product to your wishlist successfully!';
        }
    }

    public function loadImgProduct($key)
	{
		$arr = array();
		if(isset($key) && $key!= ''){
			$re = $this ->db->query("select items_files.filename from items_files join items on items_files.tid = items.itm_id where items.itm_key = '".$key."' and items.itm_status <> -1 order by items_files.weight DESC ");
			foreach($re->result_array() as $row){
				if(is_file("shopping/data/img/thumb_show/".$row['filename'])){
					$arr_file_del = explode(".", $row['filename']);
					if(count($arr_file_del) > 0){
						$file_id = '';
						$ext = $arr_file_del[count($arr_file_del)-1];
						if(in_array(strtolower($ext), $this ->__fileImages__)){
							$file_id = $row['filename'];	
						}else{
							$fileid_del = '';
							for($i = 0; $i < count($arr_file_del)-1; $i++){
								$fileid_del .= $arr_file_del[$i].'.';
							}
							if($fileid_del != ''){
								$fileid_del .= 'jpg';
							}
							$file_id = $fileid_del.'|'.$ext;	
						}
						$arr[] = $file_id;	
					}		
				}
			}
		}
		return json_encode($arr);
	}	//end loadImgProduct function

    private function itemPrice($itm_key, $current_cost_sub) {
        $current_cost = $current_cost_sub;
        $last_cost = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey =  '{$itm_key}'");
        foreach ($last_cost->result_array() as $row_price) {
            $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? (float) $row_price['markup_percentage'] : 0;
            $current_cost += $current_cost * $markup_percentage / 100;
        }
        return $current_cost;
    }

    function addToCart_btn($inventories) {
        $wishlist_button = '';
        //$wishlist_button = $this->author->objlogin->uid > 0 ? '<button class="btn Wishlist"  data-toggle="tooltip" data-title="+To Wishlist" onClick="addToWishlist(\'' . $this->itm_key . '\')"  ><i class="icon-heart"></i></button>' : '';
        if($this->author->objlogin->uid > 0){
            if($inventories == null || $inventories <=0){
                 $wishlist_button .= '<button class="btn Wishlist"  data-toggle="tooltip" data-title="+To Wishlist" onClick="addToWishlist(\'' . $this->itm_key . '\')" style="border-radius:4px 4px 4px 4px"  ><i class="icon-heart"></i></button>';
            }else{
                 $wishlist_button .= '<button class="btn Wishlist"  data-toggle="tooltip" data-title="+To Wishlist" onClick="addToWishlist(\'' . $this->itm_key . '\')"  ><i class="icon-heart"></i></button>';
            }
        }
        
        $cart_button = '';
        $arr_location = array();
        //auto delivery
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            if (isset($_SESSION['_CART']) && is_array($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                $cart_button .= '<table cellpadding="0" cellspacing="0" border="0">';
                $cart_button .= '<tr><td><table>';
                $cart_button .= '<tr><td style="font-size:12px"><b>Select quantity for:</b></td></tr>';
                $i = 0;
                foreach ($_SESSION['_CART'] as $ckey => $value) {
                    $cart_button .= '<tr>';
                    $cart_button .= '<td align="left" valign="middle" style="padding:10px 5px 5px 0px; font-size:12px;">';
                    $cart_button .= '	<b>Schedule ' . ($i + 1) . ' :</b>';
                    $cart_button .= '</td>';
                    $cart_button .= '<td align="left" valign="middle" style="padding:0 5px;">';
                    $cart_button .= '	<input type="text" class="input-text" id="p_qlty_' . $i . '" name="' . $ckey . '" style="width:40px; margin-top:10px; border-radius:4px" onkeypress="return isNumberIntKey(event)"  onkeyup="valid(this)" />';
                    $cart_button .= '</td>';
                    $cart_button .= '</tr>';
                    $i++;
                }
                $cart_button .= '</table></td>';
                $cart_button .= '</tr>';
                $cart_button .= '</table><div style="margin-bottom:20px"></div>';

              
                $cart_button .= '<span id="add2cart" ><button class="btn btn-primary" style="border-radius :5px 0px 0px 5px"   onclick="return submit_order(\'a\', \'' . $this->itm_key . '\',' . count($_SESSION['_CART']) . ')"  ><i class="icon-shopping-cart"></i> Add To Cart</button>';
                $cart_button .= '</span>';
            }
        } else {
            if ($this->product_type == 1) {
                $re_location = $this->db->query("select * from items_locations where ikey = '" . $this->itm_key . "' and status = 1");
                foreach ($re_location->result_array() as $row_location) {
                    if (trim($row_location['location']) != '')
                        $arr_location[] = $row_location;
                }
            
                if (count($arr_location) < 1) {

                    $cart_button .= '<input class="span1" type="text" id="p_qlty" name="p_qlty" value="" placeholder="QTY" onkeypress="return isNumberIntKey(event)"  onkeyup="valid(this)"  >';
                    $cart_button .= '<span id="add2cart" >';

                    $item_rid = $this->database->db_result("select rid from users_roles join items on users_roles.uid = items.uid where items.itm_key = '" . $this->itm_key . "'");
               
                    if ($item_rid != 8)
                        $cart_button .= '<button class="btn btn-primary" onclick=" return a2c(\'a\', \'' . $this->itm_key . '\');"><i class="icon-shopping-cart"></i> Add To Cart</button>';
                    else{
   
                    if (isset($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                        
                        $array = get_object_vars($_SESSION['_CART']);
                        if(is_array($array['items']) && !empty($array['items'])){
                             $cart_button .= '<button class="btn btn-primary" onclick="return a2c(\'a\', \'' . $this->itm_key . '\');"><i class="icon-shopping-cart"></i> Donate</button>';
                        }else{
                             $cart_button .= '<button class="btn btn-primary" onclick="return donate(\'' . $this->itm_key . '\');"><i class="icon-shopping-cart"></i> Donate</button>';
                        }
                        
                        
                      // $cart_button .= '<button class="btn btn-primary" onclick="return a2c(\'a\', \'' . $this->itm_key . '\');"><i class="icon-shopping-cart"></i> Donate</button>';
                    } //else {
                        //$cart_button .= '<button class="btn btn-primary" onclick="return donate(\'' . $this->itm_key . '\');"><i class="icon-shopping-cart"></i> Donate</button>';
                    //}
                    }
                    
                    $cart_button .= '</span>';
                } else {
       
                    $cart_button .= '<button class="btn btn-primary findlocation" style= "border-radius: 5px 0 0 5px !important ;" onclick="return find_location(\'' . $this->itm_key . '\')"><i class="icon-shopping-cart"></i> Find Location</button>';
                }
            } else {
                $cart_button .= '<input class="span1" type="text" id="p_qlty" name="p_qlty" value="" placeholder="QTY" onkeypress="return isNumberIntKey(event)" onkeyup="valid(this)"  >';
                $cart_button .= '<span id="add2cart">';
                $cart_button .= '	<button class="btn btn-primary" onclick="return a2c(\'a\', \'' . $this->itm_key . '\');"><i class="icon-shopping-cart"></i> Add To Cart</button>';
                $cart_button .= '</span>';
            }
        }
        $this->wishlist_btn = $wishlist_button;
        $this->addtocart_btn = $cart_button;
    }
 
    function DistanceCalc($lat1, $lon1, $lat2, $lon2) {
        //if (($lat1==0) || ($lat2==0) || ($lon1==0) || ($lon2==0)){return -1;}
        $dLat = ($lat2 - $lat1) * M_PI / 180;
        $dLon = ($lon2 - $lon1) * M_PI / 180;
        $lat1 = $lat1 * M_PI / 180;
        $lat2 = $lat2 * M_PI / 180;
        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return ($c * 3958.754);
    }

    function updateViewProduct($itm_key) {
        $session_id = session_id();
        $ip = $_SERVER['REMOTE_ADDR'];
        $check = false;

        $re = $this->db->query("select * from items_view where ip = '$ip' and session_id = '$session_id' and itm_key = '$itm_key'");
        if ($re->row_array() > 0)
            $check = true;

        if ($check == false) {
            $data = array(
                'itm_key' => $itm_key,
                'ip' => $ip,
                'session_id' => $session_id
            );
            $this->db->insert("items_view", $data);
        }
    }

    function findLocation() {

        $itmid = $this->lib->cleanSpecialCharsFromUrl($_GET['itmid']);
        $catid = "";
        $itm_id = 0;
        $dressing_room = '';
        $img_fb = '';
        $img_source = '';
        $itm_name = '';
        $itm_model = '';
        $price_str = '';
        $origin = '';
        $instock = '';
        $radius = '';
        $zip = '';
        $arr_location = array();
        $promotion = '';

        $re = $this->db->query("select items.*,categories.* FROM items join categories on items.cat_id=categories.cat_id where items.itm_key = '$itmid' AND items.itm_status > 0");
        if ($re->num_rows() < 1) {
            header('Location:' . $this->system->URL_server__() . 'shop/shome');
        }
        foreach ($re->result_array() as $row) {
            $this->updateViewProduct($itmid);
            $catid = $row["cat_id"];
            $itm_id = $row['itm_id'];
            $itm_name = $row['itm_name'];
            $cat_key = $row['cat_key'];
            $category_name = $row['cat_name'];

            $arr_file = $this->lib->__loadFileProduct__($itm_id);
            $img_fb = $arr_file['file'];
            $img_source = $this->system->URL_server__() . 'shopping/data/img/thumb_home/' . $img_fb;
            $itm_model = $row['itm_model'];

            $price = (is_numeric($row['current_cost']) && $row['current_cost'] > 0) ? (float) $row['current_cost'] : 0;
            $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itmid'");
            foreach ($re_price->result_array() as $row_price) {
                $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? (float) $row_price['markup_percentage'] : 0;
                $price = $price + $price * $markup_percentage / 100;
            }
            $row['itm_price'] = $price;
            $price_str = "$" . number_format($price, 2);

            //$this->ShowPromotions($itmid);

            $arr_promotions = $this->lib->load_items_promotion($itmid);
            if (count($arr_promotions) > 0) {
                foreach ($arr_promotions as $promo) {

                    $promotion = $promo['promo_name'];
                }
            }
            if (!is_null($row['origin']) && trim($row['origin']) != '') {
                $origin = $row['origin']; //$this->lib->ConvertToHtml($row['origin']);
            }
            // $strContent = str_replace("<!--@cat_links@-->", '&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href="' . cleanUrl() . 'products&catid=' . $cat_key . '">' . $row["cat_name"] . '</a>', $strContent);
            //$uid = $this->author->objlogin->uid;
            if ($row['inventories'] == null || $row['inventories'] <= 0) {
                $instock = '(Out Of Stock)';
            } else {
                $instock = $row['inventories'];
            }


            $radius = (isset($_GET['radius']) && is_numeric($_GET['radius']) && $_GET['radius'] > 0) ? (float) $_GET['radius'] : ''; //0
            $zip = isset($_GET['address']) ? $_GET['address'] : '';

            $center_log = 0;
            $center_lat = 0;
            $longlat = $this->lib->GetLatLongFromAddress($zip);

            $arr_longlat = explode(';', $longlat);
            if (isset($arr_longlat[0]) && is_numeric($arr_longlat[0]))
                $center_log = $arr_longlat[0];
            if (isset($arr_longlat[1]) && is_numeric($arr_longlat[1]))
                $center_lat = $arr_longlat[1];

            if ($row["product_type"] == 1) {
                $re_location = $this->db->query("select * from items_locations where ikey = '" . $row['itm_key'] . "' and status = 1");
                foreach ($re_location->result_array() as $row_location) {
                    if ($row_location['location'] == '')
                        continue;
                    $dis = 0;
                    if ($center_lat !== 0 && $center_log !== 0) {
                        $longlat = $this->lib->GetLatLongFromAddress($row_location['location']);

                        $arr_lnglt = explode(';', $longlat);
                        $lng = (isset($arr_lnglt[0]) && is_numeric($arr_lnglt[0])) ? $arr_lnglt[0] : 0;
                        $lat = (isset($arr_lnglt[1]) && is_numeric($arr_lnglt[1])) ? $arr_lnglt[1] : 0;
                        if ($lng === 0 && $lat === 0)
                            continue;
                        $dis = $this->DistanceCalc($center_lat, $center_log, $lat, $lng);
                        if ($dis > $radius) {
                            continue;
                        }
                        $row_location['mile'] = $dis;
                        $arr_location[] = $row_location;
                    }
                }
            }
        }
        return $data = array(
            'img_source' => $img_source,
            'item_name' => $itm_name,
            'item_model' => $itm_model,
            'price_string' => $price_str,
            'origin' => $origin,
            'in_stock' => $instock,
            'radius' => $radius,
            'zip' => $zip,
            "if('loadInStock'=='yes');" => "instock = " . $instock . ";",
            "if('loadItemPrice'=='yes');" => "item_price = " . $price_str . ";",
            "if('loadItemID'=='yes');" => "ItemID = '$itmid';\n",
            "if('load_list_available' == 'yes');" => "Locations = " . json_encode($arr_location) . ";",
            'promotion' => $promotion
        );
    }

    function loadAttributes() {
        $arr_ = array();
        $arr_items_attributes = array();
        $arr_items_options = array();

        $pkey = '';
        if (isset($_POST['ItemID']) && $_POST['ItemID'] != '') {
            $pkey = $_POST['ItemID'];
            $re = $this->db->query("select * from items_attributes where pkey = '$pkey'");
            foreach ($re->result_array() as $row) {
                $arr_items_attributes[] = $row;
            }

            $re = $this->db->query("select okey,odefault,cost,price,weight from items_options where pkey = '$pkey'");
            foreach ($re->result_array() as $row) {
                $arr_items_options[] = $row;
            }
        }

        $re = $this->db->query("select * from attributes where status <> -1 order by weight desc,name asc");
        foreach ($re->result_array() as $row) {

            $arr_options = array();
            $re_2 = $this->db->query("select * from attrioptions where status <> -1 and akey = '" . $row['akey'] . "' order by weight desc,name asc");
            foreach ($re_2->result_array() as $row_2) {
                if (count($arr_items_options) > 0) {
                    foreach ($arr_items_options as $items_options) {
                        if ($items_options['okey'] == $row_2['okey']) {
                            $row_2['odefault'] = $items_options['odefault'];
                            if ($items_options['cost'] != null && is_numeric($items_options['cost'])) {
                                $row_2['cost'] = $items_options['cost'];
                            }
                            if ($items_options['price'] != null && is_numeric($items_options['price'])) {
                                $row_2['price'] = $items_options['price'];
                            }
                            if ($items_options['weight'] != null && is_numeric($items_options['weight'])) {
                                $row_2['weight'] = $items_options['weight'];
                            }
                            $arr_options[] = $row_2;
                            break;
                        }
                    }
                }
            }
            $row['options'] = $arr_options;

            if (count($arr_items_attributes) > 0) {
                foreach ($arr_items_attributes as $items_attributes) {
                    if ($items_attributes['akey'] == $row['akey']) {
                        if ($items_attributes['label'] != null && $items_attributes['label'] != '') {
                            $row['label'] = $items_attributes['label'];
                        }
                        if ($items_attributes['required'] != null && is_numeric($items_attributes['required'])) {
                            $row['required'] = $items_attributes['required'];
                        }
                        if ($items_attributes['display'] != null && is_numeric($items_attributes['display'])) {
                            $row['display_type'] = $items_attributes['display'];
                        }
                        if ($items_attributes['weight'] != null && is_numeric($items_attributes['weight'])) {
                            $row['weight'] = $items_attributes['weight'];
                        }
                        $arr_[] = $row;
                        break;
                    }
                }
            }
        }
        return $arr_;
    }

}

