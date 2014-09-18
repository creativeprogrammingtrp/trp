<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices_model extends CI_Model {
    
    var $string_oid = '';
    var $check_donate = false;

    public function loadAutoInvoicesData($okey, $ck_commission) {

        global $total, $__accessRight__, $__order_status__;
        $roles = $this->lib->loadRoles();
        $oid = 0;
      
     
        $total = 0;
        $tax = 0;
        $shipping_fee = 0;
        $subtotal = 0;
        $tax_total = 0;

        $tax_pecen = 0;
        $order_status = 1;
        $base_price = 0;
        $str = '';

        $tblcontries = array();
        $check_voucher = true;
        $data = array();

        $re = $this->db->query("select * from tblcontries");
        foreach ($re->result_array() as $row) {
            $tblcontries[$row['code']] = $row['name'];
        }
        $packages = array();
        $order_status_level = array();
        
      
       $okeys = $this->getOidBySkey($okey);
        
       $okeys = implode(',', $okeys);
      
     
       /* $r = '';
        if(isset($okeys) && is_array($okeys)){
             $okeys = implode(',', $okeys);
             $r = $this->db->query("SELECT * FROM orders WHERE okey in ($okeys)");
        }else{
             $r = $this->db->query("SELECT * FROM orders WHERE okey = '$okey'");
        }*/
     
       $r = $this->db->query("SELECT * FROM orders WHERE okey in ($okeys)");
        //$r = $this->db->query("SELECT oid FROM orders join orders_auto_delivery on orders.orderid= orders_auto_delivery.oid WHERE orders_auto_delivery.skey = '$okey'");
        //$r = $this->db->query("SELECT * FROM orders WHERE okey = '$okey'");
       $subtotal = 0;
        $tax = 0;
        $shipping_fee = 0;
        $total_itemTax = 0;
       
        foreach ($r->result_array() as $row) {
            $oid = $row['orderid'];
            //$okey = $row['okey'];//
            $billing_Name = $row["billing_name"];
            $billing_Address = $row["billing_address"];
            $billing_City = $row["billing_city"];
            $billing_State = $row["billing_state"];
            $billing_Zip = $row["billing_zip"];
            $billing_Phone = $row["billing_phone"];
            $billing_Email = $row["billing_email"];

            $shipping_Name = $row["shipping_name"];
            $shipping_Address = $row["shipping_address"];
            $shipping_City = $row["shipping_city"];
            $shipping_State = $row["shipping_state"];
            $shipping_Zip = $row["shipping_zip"];
            $shipping_Phone = $row["shipping_phone"];

            //$tax_pecen = $row["order_tax"];
            $date = $row["order_date"];
            $card_number = $row["card_number"];
            $order_status = $row['status'];
            $base_price = $row['shipping_fee'];
            $data['<!--order_number-->'] = $okey;
            $data ['<!--date-->'] = date("m/d/Y", strtotime($date));
            $data['<!--billingName-->'] = $billing_Name;
            $data['<!--billingAddress-->'] = $billing_Address;
            $data['<!--billingCity-->'] = $billing_City . ', ' . $billing_State . ' ' . $billing_Zip . ', ' . (isset($tblcontries[$row["billing_country"]]) ? $tblcontries[$row["billing_country"]] : '');
            $data['<!--billingPhone-->'] = $billing_Phone;
            $data['<!--billingEmail-->'] = $billing_Email;
            $data['<!--shippingName-->'] = $shipping_Name;
            $data['<!--shippingAddress-->'] = $shipping_Address;
            $data['<!--shippingCity-->'] = $shipping_City . ', ' . $shipping_State . ', ' . $shipping_Zip;
            $data['<!--shippingPhone-->'] = $shipping_Phone;
            //$data['<!--payment_method-->'] = $row['card_type'];
            $data['<!--card_number-->'] = 'xxxxxxxxxxxx' . $card_number;

            // modify tax
            $taxs = array();
            $re_tax = $this->db->query("select * from tax_rates where status <> -1 order by weight DESC,name ASC");
            foreach ($re_tax->result_array() as $row_tax) {
                if ($row_tax['state'] != '' && $row_tax['state'] != $shipping_State)
                    continue;
                $row_tax['tax_item_total'] = 0;
                $taxs[] = $row_tax;
            }
            $ship_label = $this->database->db_result("select label from shipping_rates where skey = '" . $row['shipping_key'] . "'");
            $data['<!--ship_label-->'] = $ship_label;
           // $re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
            $re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey in ('$okeys') ");
            foreach ($re_2->result_array() as $row_2) {
                $ship = 0;
                //$re_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey = '$okey'");
                $re_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey in ('$okeys') ");
                if ($re_3->row_array() > 0) {
                    $ship = 1;
                }
                $items = array();
                $re_3 = $this->db->query("select product_id,qty from packages_items where package_id = " . $row_2['id']);
                foreach ($re_3->result_array() as $row_3) {
                    $items[] = $row_3;
                }
                $packages[] = array(
                    'ship' => $ship,
                    'items' => $items
                );
            }
        //}

        $data['<!--order_status-->'] = (isset($oStatus[$order_status])) ? $oStatus[$order_status] : '';
        $ong_chu = $this->lib->__loadBoss__();
       // $sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
        $sql_orders_promotions = "select * from orders_promotions where order_key in ('$okeys') ";
        $sql_manufacturer = '';
        if ($roles['rid'] == 5) {
            $sql_manufacturer = "and items.uid = " . $ong_chu;
            $sql_orders_promotions .= " and manufacturer_id = " . $ong_chu;
        }
        $arrPromotions = array();
        $re = $this->db->query($sql_orders_promotions);
        foreach ($re->result_array() as $row) {
            $arrPromotions[] = $row;
        }
        $arr_manufacturers = array();
        $re_1 = $this->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.origin,items.itm_key,items.product_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
        foreach ($re_1->result_array() as $row_1) {
            $check_exist = false;
            for ($m = 0; $m < count($arr_manufacturers); $m++) {
                if ($arr_manufacturers[$m]['uid'] == $row_1['uid']) {
                    $arr_manufacturers[$m]['items'][] = $row_1;
                    $check_exist = true;
                    break;
                }
            }
            if ($check_exist == false) {
                $arr_manufacturers[] = array('uid' => $row_1['uid'], 'items' => array($row_1));
            }
        }
        $arr_orders_handling = array();
        $re_1 = $this->db->query("select * from orders_handling where oid = " . $oid);
        foreach ($re_1->result_array() as $row_1) {
            $arr_orders_handling[] = $row_1;
        }
//        $subtotal = 0;
//        $tax = 0;
//        $shipping_fee = 0;
        for ($m = 0; $m < count($arr_manufacturers); $m++) {
            $handling_fee_new = $base_price;
            foreach ($arr_orders_handling as $oh) {
                if ($oh['uid'] == $arr_manufacturers[$m]['uid']) {
                    $handling_fee_new = $oh['handling'];
                    break;
                }
            }
            $shipping_rate = $handling_fee_new;
            $count_ship_free = count($arr_manufacturers[$m]['items']);
            foreach ($arr_manufacturers[$m]['items'] as $row) {//1
                $itemid = $row["itemid"];
                $odetail = $row['id'];
                $itm_name = $row['itm_name'];
                $itm_model = $row['itm_model'];

                $qty_ship = 0;
                $qty_par = 0;
                if (count($packages) > 0) {
                    foreach ($packages as $package) {
                        $items = $package['items'];
                        for ($i = 0; $i < count($items); $i++) {
                            if ($items[$i]['product_id'] == $itemid) {
                                if ($package['ship'] == 0) {
                                    $qty_par += $items[$i]['qty'];
                                } elseif ($package['ship'] == 1) {
                                    $qty_ship += $items[$i]['qty'];
                                }
                                break;
                            }
                        }
                    }
                }
                $status_item = 1;
                if ($row['product_type'] == 0) {
                    if ($qty_ship == $row["quality"]) {
                        $status_item = 3;
                    } elseif ($qty_par > 0 || $qty_ship > 0) {
                        $status_item = 2;
                    }
                } else {
                    $status_item = 3;
                }
                $order_status_level[] = $status_item;

                $qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");

                $arr_file = $this->lib->__loadFileProduct__($itemid);
                $_filename = $arr_file['file'];


                $manufacturer = 'My Store';
                $re_manu = $this->db->query("select legal_business_name from manufacturers where uid = " . $row['uid']);
                foreach ($re_manu->result_array() as $row_manu) {
                    $manufacturer = $row_manu['legal_business_name'];
                }

                $attributes_str = '';
                $re_ = $this->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
                foreach ($re_->result_array() as $row_) {
                    $attributes_str .= '<br><b>' . $row_['label'] . ': </b>' . $row_['name'];
                    if (is_numeric($row_['price']) && $row_['price'] > 0) {
                        $attributes_str .= '&nbsp;&nbsp;(+$' . number_format($row_['price'], 2) . ')';
                    }
                }
                $check_shipping_free = false;
                $arr_show_promotions = array();
                $free_product_row = '';
                if (count($arrPromotions) > 0) {
                   
                    foreach ($arrPromotions as $promotions) {
                        if ($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']) {
                            $result_qty = $promotions['result_qty'];
                            $qty_buy = $row['quality'] - $qty_refund;
                            if ($qty_buy >= $promotions['minqty']) {
                                $bac_qty = 0;
                                if ($promotions['minqty'] > 0)
                                    $bac_qty = floor($qty_buy / $promotions['minqty']);
                                $qty_free = $bac_qty * $promotions['freeqty'];
                                $result_qty -= $qty_free;
                            }
                            if ($result_qty <= 0)
                                $result_qty = 0;

                            $manufacturer_free = 'My Store';
                            $re_manu = $this->db->query("select legal_business_name from manufacturers where uid = '" . $promotions['manufacturer_id'] . "'");
                            foreach ($re_manu->result_array() as $row_manu) {
                                $manufacturer_free = $row_manu['legal_business_name'];
                            }

                            $itm_model = '';
                            $itm_name = '';
                            $itemid = 0;
                            $re_ = $this->db->query("select itm_id,itm_name,itm_model from items where itm_key = '" . $promotions['itm_key'] . "'");
                            foreach ($re_->result_array() as $row_) {
                                $itm_name = $row_['itm_name'];
                                $itm_model = $row_['itm_model'];
                                $itemid = $row_['itm_id'];
                            }

                            $arr_file = $this->lib->__loadFileProduct__($itemid);
                            $_filename = $arr_file['file'];

                            $desc_free = '<div style="clear:both"><b>' . $itm_name . '</b><BR><br><b>Model: </b>' . $itm_model . '</div>';
                            $desc_free .= '<div style="clear:both; padding-top:10px">';
                            $desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $desc_free .= '	<tr>';
                            $desc_free .= '		<td align="left" valign="top"><img src="' .$this->system-> __path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
                            $desc_free .= '	</tr>';
                            $desc_free .= '</table>';
                            $desc_free .= '</div>';


                            $data['@img@'] = $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename;
                            $data['<!--desc-->'] = $desc_free;
                            $data['<!--total-->'] = '0.00';
                            $data['<!--price-->'] = '0.00';
                            $data['<!--qty_return-->'] = number_format($result_qty);
                            $data['<!--qty_buy-->'] = number_format($promotions['result_qty']);
                        }
                        if ($promotions['itm_key'] == $row['itm_key']) {//0
                            switch ($promotions['promo_type']) {
                                case 1:
                                    $arr_show_promotions_step = array('promo_type' => 1, 'discount_type' => $promotions['discount_type'], 'discount' => $promotions['discount']);
                                    $check_exist_pro = false;
                                    for ($p = 0; $p < count($arr_show_promotions); $p++) {
                                        if ($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']) {
                                            $arr_show_promotions[$p]['discount'] += $promotions['discount'];
                                            $check_exist_pro = true;
                                            break;
                                        }
                                    }
                                    if ($check_exist_pro == false)
                                        $arr_show_promotions[] = $arr_show_promotions_step;
                                    break;
                                case 3:
                                    $check_shipping_free = true;
                                    $arr_show_promotions_step = array('promo_type' => 3, 'discount_type' => $promotions['discount_type'], 'discount' => $promotions['discount']);
                                    $check_exist_pro = false;
                                    for ($p = 0; $p < count($arr_show_promotions); $p++) {
                                        if ($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']) {
                                            $arr_show_promotions[$p]['discount'] += $promotions['discount'];
                                            $check_exist_pro = true;
                                            break;
                                        }
                                    }
                                    if ($check_exist_pro == false)
                                        $arr_show_promotions[] = $arr_show_promotions_step;
                                    break;
                                case 4:
                                    $check_shipping_free = true;
                                    $arr_show_promotions_step = array('promo_type' => 4, 'discount_type' => 1, 'discount' => 1);
                                    $check_exist_pro = false;
                                    for ($p = 0; $p < count($arr_show_promotions); $p++) {
                                        if ($arr_show_promotions[$p]['promo_type'] == 4) {
                                            $check_exist_pro = true;
                                            break;
                                        }
                                    }
                                    if ($check_exist_pro == false)
                                        $arr_show_promotions[] = $arr_show_promotions_step;
                                    break;
                            }
                        }//0
                    }
                }
                $promotions_ = '';
                for ($p = 0; $p < count($arr_show_promotions); $p++) {
                    switch ($arr_show_promotions[$p]['promo_type']) {
                        case 1:
                            $discount_str = '';
                            if ($arr_show_promotions[$p]['discount_type'] == 0) {
                                $discount_str = number_format($arr_show_promotions[$p]['discount']) . '%';
                            } else {
                                $discount_str = '$' . number_format($arr_show_promotions[$p]['discount'], 2);
                            }
                            $promotions_ .= '<div style="clear:both; padding-top:10px">';
                            $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $promotions_ .= '	<tr>';
                            $promotions_ .= '		<td align="left" valign="top"><img src="' .$this->system-> __path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: ' . $discount_str . '</td>';
                            $promotions_ .= '	</tr>';
                            $promotions_ .= '</table>';
                            $promotions_ .= '</div>';
                            break;
                        case 3:
                            $discount_str = '';
                            if ($arr_show_promotions[$p]['discount_type'] == 0) {
                                $discount_str = number_format($arr_show_promotions[$p]['discount']) . '%';
                            } else {
                                $discount_str = '$' . number_format($arr_show_promotions[$p]['discount'], 2);
                            }
                            $promotions_ .= '<div style="clear:both; padding-top:10px">';
                            $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $promotions_ .= '	<tr>';
                            $promotions_ .= '		<td align="left" valign="top"><img src="' .$this->system-> __path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: ' . $discount_str . '</td>';
                            $promotions_ .= '	</tr>';
                            $promotions_ .= '</table>';
                            $promotions_ .= '</div>';
                            break;
                        case 4:
                            $promotions_ .= '<div style="clear:both; padding-top:10px">';
                            $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $promotions_ .= '	<tr>';
                            $promotions_ .= '		<td align="left" valign="top"><img src="' .$this->system-> __path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
                            $promotions_ .= '	</tr>';
                            $promotions_ .= '</table>';
                            $promotions_ .= '</div>';
                            break;
                    }
                }
                

                $itemprice = $row['itemprice'];
                $amount = round($itemprice * $row["quality"], 2);
                $subtotal += $amount;
              

                if ($row['product_type'] != 0) {
                    $row['shipping_fee'] = 0;
                    $shipping_rate = 0;
                    $count_ship_free--;
                } else {
                    $tax += $tax_pecen * $amount / 100;
                    if ($row['shipping_fee'] <= 0) {
                        $row['shipping_fee'] = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                }
                $shipping_rate += $row['shipping_fee'];

                $origin = '';
                if ($row['origin'] != '' && $row['origin'] != null) {
                    $origin = '<br>' . $this->lib->ConvertToHtml($row['origin']);
                }

                $tax_rate_items = array();
                $re_tax = $this->db->query("select * from items_tax where itm_key = '" . $row["itm_key"] . "'");
                foreach ($re_tax->result_array() as $row_tax) {
                    $tax_rate_items[] = $row_tax;
                }
  
                for ($i = 0; $i < count($taxs); $i++) {
                    $tax_rate = $taxs[$i]['rate'];
                    for ($j = 0; $j < count($tax_rate_items); $j++) {
                        if ($tax_rate_items[$j]['tax_id'] == $taxs[$i]['id']) {
                            $tax_rate = $tax_rate_items[$j]['tax_rate'];
                        }
                    }

                    $taxs[$i]['tax_item_total'] += round(((float) $tax_rate * (float) $itemprice * (float) $row["quality"]) / 100, 2);
                    $tax_total += $taxs[$i]['tax_item_total'];
                 
                }

                if ($qty_refund != null) {
                    $qty_refund;
                } else {
                    $qty_refund = '0';
                }
                
                $image = $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename;
                $str .= ' <tr class="tr_order_details">';
                $str .= ' <td> <table cellpadding="0" cellspacing="0" border="0"> <tr>';
                $str .= '<td align="left" valign="top" width="90px"><img border="0" src= "' . $image . '" /></td>';
                $str .= ' <td align="left" valign="top" style="padding-left:10px">' . '<div style="clear:both"><b>' . $itm_name . '</b><BR><b>Model: </b>' . $itm_model . $origin . $attributes_str . '</div>' . $promotions_ . '</td>';
                $str .= ' </tr> </table></td>';
                $str .= '<td align="right">$' . number_format($itemprice, 2) . '</td>';
                $str .= '<td align="center">' . number_format($row["quality"]) . '</td>';
                $str .= ' <td align="right">' . $qty_refund . '</td>';
                $str .= '<td align="right">$' . number_format($amount, 2) . '</td>';
                $str .= '</tr>';

                $data['tr_order_details'] = $str;
                $data['@id@'] = $itemid;
            }
            if ($shipping_rate == $handling_fee_new && $count_ship_free == 0)
                $shipping_rate = 0;
            $shipping_fee += round($shipping_rate, 2);
        }//0
          
            foreach ($taxs as $each_tax) {
                $total_itemTax += $each_tax['tax_item_total'];
                $data['<!--tax_name-->'] = $each_tax['name'];
                $data['<!--tax_item_total-->'] = '$' . number_format($total_itemTax, 2);
            }
        $shipping_fee = round($shipping_fee, 2);
        $tax = round($tax, 2);
       
        $total = $subtotal + $total_itemTax + $shipping_fee;//$tax_total
       
        /*$Wallet = $this->load_Auto_myWallet($total, $ck_commission);
        $mywallet = '';
        $totallast = '';
        if (isset($Wallet) && !empty($Wallet)) {

            $mywallet .= ' <tr><td style="text-align:right; padding-right:5px;">MyWallet Account:</td>';
            $mywallet .= '<td style="text-align:right">' . $Wallet[0] . '</td></tr>';

            $totallast .= '<tr><td style="text-align:right; padding-top:10px; padding-right:10px; border-top:#000 solid 1px">TOTAL:</td>';
            $totallast .= '<td style="text-align:right; padding-top:10px;border-top:#000 solid 1px">' . $Wallet[1] . '</td></tr>';
        }

        $data['mywallet'] = $mywallet;
        $data['total_last'] = $totallast;*/
        $data['<!--suptotal-->'] = '$' . number_format($subtotal, 2);
        $data['<!--Total-->'] = '$' . number_format($total, 2);
        if (count($taxs) <= 1)//0
            $data['<!--Tax-->'] = '$0.00';
        $data['<!--shipping_fee-->'] = '$' . number_format($shipping_fee, 2);

        $min_level = 3;
        $Canceled_status = 0;
        $Refunded_status = 0;
        if (count($order_status_level) > 0) {
            foreach ($order_status_level as $level) {
                if ($level == 4)
                    $Canceled_status = 1;
                elseif ($level == 5)
                    $Refunded_status = 1;
                elseif ($level < 4) {
                    if ($level < $min_level) {
                        $min_level = $level;
                    }
                }
            }
        }
        if ($Canceled_status == 1)
            $min_level = 4;
        elseif ($Refunded_status == 1)
            $min_level = 5;
        $data['<!--@order_status@-->'] = isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : null;
        // $strContent = str_replace('<!--@order_status@-->', isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : 'null', $strContent);
        $refund_amount = 0;
        $return_str = '';
        //$re_refund = $this->db->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
        $re_refund = $this->db->query("select id,refund_update,refund_type from orders_return where okey in ('$okeys') order by id DESC");
        foreach ($re_refund->result_array() as $row_return) {
            $reason = (isset($__refund_reason__[$row_return['refund_type']])) ? $__refund_reason__[$row_return['refund_type']] : 'None';
            $refund__ = $this->lib->loadAmountRefund($row_return['id'], $roles['rid'], $this->author->objlogin->uid, $okey, $oid, $tax_pecen, $base_price);
            if (is_numeric($refund__)) {
                $refund__ = $refund__ * (-1);
                $refund_amount += $refund__;
                $return_str .= '<tr>';
                $return_str .= '	<td align="right" valign="middle"><b>' . date("F j, Y g:i A", strtotime($row_return['refund_update'])) . ' refunded:</b></td>';
                $return_str .= '	<td align="right" valign="middle" style="padding-left:20px">' . $this->lib->showMoney($refund__) . '</td>';
                $return_str .= '</tr>';
            }
        }
        if ($refund_amount != 0) {
            $return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
            $return_str .= '<tr>';
            $return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
            $return_str .= '	<td align="right" valign="middle" style="padding-left:20px">' . $this->lib->showMoney($total + $refund_amount) . '</td>';
            $return_str .= '</tr>';
        }
        $data['<!--@refund_row@-->'] = $return_str;
        $data['@okey@'] = $okey;
        }//
        return $data;
   
    }

    function load_Auto_myWallet($total, $ck_commission) {
        $my_wallet = '';
        $total_last = '';
        $result = array();
        if (!empty($ck_commission) && $ck_commission > 0) {

            $total_last = $total;
            $my_wallet = number_format($ck_commission, 2);
            if ($my_wallet > $total)
                $my_wallet = $total;
            $total_last -= $my_wallet;
            $result[0] = '$' . number_format($my_wallet, 2);
            $result[1] = '$' . number_format($total_last, 2);
        }

        return $result;
    }

    function check_Auto_order_voucher($order_id) {
      
        $check = true;
        $oid = $this->database->db_result("SELECT orders.orderid FROM orders join orders_auto_delivery on orders.orderid= orders_auto_delivery.oid WHERE orders_auto_delivery.skey = '$order_id'");
        $re = $this->db->query("select order_detais.itemid from order_detais where order_detais.orderid = '" . $oid . "' ");
        foreach ($re->result_array() as $row) {
            $product_type = $this->database->db_result("select product_type from items where itm_id = '" . $row['itemid'] . "'");
            if ($product_type == 0) {
                $check = false;
                break;
            }
        }
        return $check;
    }
    
      function getOidBySkey($skey){
            $array = array();
            $sql = $this->db->query("select oid from orders_auto_delivery where skey = ".$skey);
            foreach($sql ->result_array() as $rows){
                  $sql = $this->db->query("select okey from orders where orderid = ".$rows['oid']);
                   foreach($sql ->result_array() as $row){
                       $array[]= implode(',',$row);
                   }
            }
            return $array;
        }
        
        
       public function loadInvoicesData($okey, $ck_commission) {

        global $total, $__accessRight__, $__order_status__;
        $roles = $this->lib->loadRoles();
        $oid = 0;
      
     
        $total = 0;
        $tax = 0;
        $shipping_fee = 0;
        $subtotal = 0;
        $tax_total = 0;

        $tax_pecen = 0;
        $order_status = 1;
        $base_price = 0;
        $str = '';

        $tblcontries = array();
        $check_voucher = true;
        $data = array();

        $re = $this->db->query("select * from tblcontries");
        foreach ($re->result_array() as $row) {
            $tblcontries[$row['code']] = $row['name'];
        }
        $packages = array();
        $order_status_level = array();
        

     
        $r = $this->db->query("SELECT * FROM orders WHERE okey = '$okey'");
       
        foreach ($r->result_array() as $row) {
            $oid = $row['orderid'];
            $billing_Name = $row["billing_name"];
            $billing_Address = $row["billing_address"];
            $billing_City = $row["billing_city"];
            $billing_State = $row["billing_state"];
            $billing_Zip = $row["billing_zip"];
            $billing_Phone = $row["billing_phone"];
            $billing_Email = $row["billing_email"];

            $shipping_Name = $row["shipping_name"];
            $shipping_Address = $row["shipping_address"];
            $shipping_City = $row["shipping_city"];
            $shipping_State = $row["shipping_state"];
            $shipping_Zip = $row["shipping_zip"];
            $shipping_Phone = $row["shipping_phone"];

            //$tax_pecen = $row["order_tax"];
            $date = $row["order_date"];
            $card_number = $row["card_number"];
            $order_status = $row['status'];
            $base_price = $row['shipping_fee'];

            $data['<!--order_number-->'] = $okey;
            $data ['<!--date-->'] = date("m/d/Y", strtotime($date));
            $data['<!--billingName-->'] = $billing_Name;
            $data['<!--billingAddress-->'] = $billing_Address;
            $data['<!--billingCity-->'] = $billing_City . ', ' . $billing_State . ' ' . $billing_Zip . ', ' . (isset($tblcontries[$row["billing_country"]]) ? $tblcontries[$row["billing_country"]] : '');
            $data['<!--billingPhone-->'] = $billing_Phone;
            $data['<!--billingEmail-->'] = $billing_Email;
            $data['<!--shippingName-->'] = $shipping_Name;
            $data['<!--shippingAddress-->'] = $shipping_Address;
            $data['<!--shippingCity-->'] = $shipping_City . ', ' . $shipping_State . ', ' . $shipping_Zip;
            $data['<!--shippingPhone-->'] = $shipping_Phone;
            //$data['<!--payment_method-->'] = $row['card_type'];
            $data['<!--card_number-->'] = 'xxxxxxxxxxxx' . $card_number;

            // modify tax
            $taxs = array();
            $re_tax = $this->db->query("select * from tax_rates where status <> -1 order by weight DESC,name ASC");
            foreach ($re_tax->result_array() as $row_tax) {
                if ($row_tax['state'] != '' && $row_tax['state'] != $shipping_State)
                    continue;
                $row_tax['tax_item_total'] = 0;
                $taxs[] = $row_tax;
            }

            $ship_label = $this->database->db_result("select label from shipping_rates where skey = '" . $row['shipping_key'] . "'");
            $data['<!--ship_label-->'] = $ship_label;
            $re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
            foreach ($re_2->result_array() as $row_2) {
                $ship = 0;
                $re_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey = '$okey'");
                if ($re_3->row_array() > 0) {
                    $ship = 1;
                }
                $items = array();
                $re_3 = $this->db->query("select product_id,qty from packages_items where package_id = " . $row_2['id']);
                foreach ($re_3->result_array() as $row_3) {
                    $items[] = $row_3;
                }
                $packages[] = array(
                    'ship' => $ship,
                    'items' => $items
                );
            }
        }

        $data['<!--order_status-->'] = (isset($oStatus[$order_status])) ? $oStatus[$order_status] : '';
        $ong_chu = $this->lib->__loadBoss__();
        $sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
        $sql_manufacturer = '';
        if ($roles['rid'] == 5) {
            $sql_manufacturer = "and items.uid = " . $ong_chu;
            $sql_orders_promotions .= " and manufacturer_id = " . $ong_chu;
        }
        $arrPromotions = array();
        $re = $this->db->query($sql_orders_promotions);
        foreach ($re->result_array() as $row) {
            $arrPromotions[] = $row;
        }
        $arr_manufacturers = array();
        $re_1 = $this->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.origin,items.itm_key,items.product_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
        foreach ($re_1->result_array() as $row_1) {
            $check_exist = false;
            for ($m = 0; $m < count($arr_manufacturers); $m++) {
                if ($arr_manufacturers[$m]['uid'] == $row_1['uid']) {
                    $arr_manufacturers[$m]['items'][] = $row_1;
                    $check_exist = true;
                    break;
                }
            }
            if ($check_exist == false) {
                $arr_manufacturers[] = array('uid' => $row_1['uid'], 'items' => array($row_1));
            }
        }
        $arr_orders_handling = array();
        $re_1 = $this->db->query("select * from orders_handling where oid = " . $oid);
        foreach ($re_1->result_array() as $row_1) {
            $arr_orders_handling[] = $row_1;
        }
        $subtotal = 0;
        $tax = 0;
        $shipping_fee = 0;
        for ($m = 0; $m < count($arr_manufacturers); $m++) {
            $handling_fee_new = $base_price;
            foreach ($arr_orders_handling as $oh) {
                if ($oh['uid'] == $arr_manufacturers[$m]['uid']) {
                    $handling_fee_new = $oh['handling'];
                    break;
                }
            }
            $shipping_rate = $handling_fee_new;
            $count_ship_free = count($arr_manufacturers[$m]['items']);
            foreach ($arr_manufacturers[$m]['items'] as $row) {//1
                $itemid = $row["itemid"];
                $odetail = $row['id'];
                $itm_name = $row['itm_name'];
                $itm_model = $row['itm_model'];

                $qty_ship = 0;
                $qty_par = 0;
                if (count($packages) > 0) {
                    foreach ($packages as $package) {
                        $items = $package['items'];
                        for ($i = 0; $i < count($items); $i++) {
                            if ($items[$i]['product_id'] == $itemid) {
                                if ($package['ship'] == 0) {
                                    $qty_par += $items[$i]['qty'];
                                } elseif ($package['ship'] == 1) {
                                    $qty_ship += $items[$i]['qty'];
                                }
                                break;
                            }
                        }
                    }
                }
                $status_item = 1;
                if ($row['product_type'] == 0) {
                    if ($qty_ship == $row["quality"]) {
                        $status_item = 3;
                    } elseif ($qty_par > 0 || $qty_ship > 0) {
                        $status_item = 2;
                    }
                } else {
                    $status_item = 3;
                }
                $order_status_level[] = $status_item;

                $qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");

                $arr_file = $this->lib->__loadFileProduct__($itemid);
                $_filename = $arr_file['file'];


                $manufacturer = 'My Store';
                $re_manu = $this->db->query("select legal_business_name from manufacturers where uid = " . $row['uid']);
                foreach ($re_manu->result_array() as $row_manu) {
                    $manufacturer = $row_manu['legal_business_name'];
                }

                $attributes_str = '';
                $re_ = $this->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
                foreach ($re_->result_array() as $row_) {
                    $attributes_str .= '<br><b>' . $row_['label'] . ': </b>' . $row_['name'];
                    if (is_numeric($row_['price']) && $row_['price'] > 0) {
                        $attributes_str .= '&nbsp;&nbsp;(+$' . number_format($row_['price'], 2) . ')';
                    }
                }
                $check_shipping_free = false;
                $arr_show_promotions = array();
                $free_product_row = '';
                if (count($arrPromotions) > 0) {
                   
                    foreach ($arrPromotions as $promotions) {
                        if ($promotions['promo_type'] == 2 && $promotions['product_key'] == $row['itm_key']) {
                            $result_qty = $promotions['result_qty'];
                            $qty_buy = $row['quality'] - $qty_refund;
                            if ($qty_buy >= $promotions['minqty']) {
                                $bac_qty = 0;
                                if ($promotions['minqty'] > 0)
                                    $bac_qty = floor($qty_buy / $promotions['minqty']);
                                $qty_free = $bac_qty * $promotions['freeqty'];
                                $result_qty -= $qty_free;
                            }
                            if ($result_qty <= 0)
                                $result_qty = 0;

                            $manufacturer_free = 'My Store';
                            $re_manu = $this->db->query("select legal_business_name from manufacturers where uid = '" . $promotions['manufacturer_id'] . "'");
                            foreach ($re_manu->result_array() as $row_manu) {
                                $manufacturer_free = $row_manu['legal_business_name'];
                            }

                            $itm_model = '';
                            $itm_name = '';
                            $itemid = 0;
                            $re_ = $this->db->query("select itm_id,itm_name,itm_model from items where itm_key = '" . $promotions['itm_key'] . "'");
                            foreach ($re_->result_array() as $row_) {
                                $itm_name = $row_['itm_name'];
                                $itm_model = $row_['itm_model'];
                                $itemid = $row_['itm_id'];
                            }

                            $arr_file = $this->lib->__loadFileProduct__($itemid);
                            $_filename = $arr_file['file'];

                            $desc_free = '<div style="clear:both"><b>' . $itm_name . '</b><BR><br><b>Model: </b>' . $itm_model . '</div>';
                            $desc_free .= '<div style="clear:both; padding-top:10px">';
                            $desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $desc_free .= '	<tr>';
                            $desc_free .= '		<td align="left" valign="top"><img src="' . __path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
                            $desc_free .= '	</tr>';
                            $desc_free .= '</table>';
                            $desc_free .= '</div>';


                            $data['@img@'] = $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename;
                            $data['<!--desc-->'] = $desc_free;
                            $data['<!--total-->'] = '0.00';
                            $data['<!--price-->'] = '0.00';
                            $data['<!--qty_return-->'] = number_format($result_qty);
                            $data['<!--qty_buy-->'] = number_format($promotions['result_qty']);
                        }
                        if ($promotions['itm_key'] == $row['itm_key']) {//0
                            switch ($promotions['promo_type']) {
                                case 1:
                                    $arr_show_promotions_step = array('promo_type' => 1, 'discount_type' => $promotions['discount_type'], 'discount' => $promotions['discount']);
                                    $check_exist_pro = false;
                                    for ($p = 0; $p < count($arr_show_promotions); $p++) {
                                        if ($arr_show_promotions[$p]['promo_type'] == 1 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']) {
                                            $arr_show_promotions[$p]['discount'] += $promotions['discount'];
                                            $check_exist_pro = true;
                                            break;
                                        }
                                    }
                                    if ($check_exist_pro == false)
                                        $arr_show_promotions[] = $arr_show_promotions_step;
                                    break;
                                case 3:
                                    $check_shipping_free = true;
                                    $arr_show_promotions_step = array('promo_type' => 3, 'discount_type' => $promotions['discount_type'], 'discount' => $promotions['discount']);
                                    $check_exist_pro = false;
                                    for ($p = 0; $p < count($arr_show_promotions); $p++) {
                                        if ($arr_show_promotions[$p]['promo_type'] == 3 && $arr_show_promotions[$p]['discount_type'] == $promotions['discount_type']) {
                                            $arr_show_promotions[$p]['discount'] += $promotions['discount'];
                                            $check_exist_pro = true;
                                            break;
                                        }
                                    }
                                    if ($check_exist_pro == false)
                                        $arr_show_promotions[] = $arr_show_promotions_step;
                                    break;
                                case 4:
                                    $check_shipping_free = true;
                                    $arr_show_promotions_step = array('promo_type' => 4, 'discount_type' => 1, 'discount' => 1);
                                    $check_exist_pro = false;
                                    for ($p = 0; $p < count($arr_show_promotions); $p++) {
                                        if ($arr_show_promotions[$p]['promo_type'] == 4) {
                                            $check_exist_pro = true;
                                            break;
                                        }
                                    }
                                    if ($check_exist_pro == false)
                                        $arr_show_promotions[] = $arr_show_promotions_step;
                                    break;
                            }
                        }//0
                    }
                }
                $promotions_ = '';
                for ($p = 0; $p < count($arr_show_promotions); $p++) {
                    switch ($arr_show_promotions[$p]['promo_type']) {
                        case 1:
                            $discount_str = '';
                            if ($arr_show_promotions[$p]['discount_type'] == 0) {
                                $discount_str = number_format($arr_show_promotions[$p]['discount']) . '%';
                            } else {
                                $discount_str = '$' . number_format($arr_show_promotions[$p]['discount'], 2);
                            }
                            $promotions_ .= '<div style="clear:both; padding-top:10px">';
                            $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $promotions_ .= '	<tr>';
                            $promotions_ .= '		<td align="left" valign="top"><img src="' .$this->lib->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: ' . $discount_str . '</td>';
                            $promotions_ .= '	</tr>';
                            $promotions_ .= '</table>';
                            $promotions_ .= '</div>';
                            break;
                        case 3:
                            $discount_str = '';
                            if ($arr_show_promotions[$p]['discount_type'] == 0) {
                                $discount_str = number_format($arr_show_promotions[$p]['discount']) . '%';
                            } else {
                                $discount_str = '$' . number_format($arr_show_promotions[$p]['discount'], 2);
                            }
                            $promotions_ .= '<div style="clear:both; padding-top:10px">';
                            $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $promotions_ .= '	<tr>';
                            $promotions_ .= '		<td align="left" valign="top"><img src="' .$this->lib->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: ' . $discount_str . '</td>';
                            $promotions_ .= '	</tr>';
                            $promotions_ .= '</table>';
                            $promotions_ .= '</div>';
                            break;
                        case 4:
                            $promotions_ .= '<div style="clear:both; padding-top:10px">';
                            $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $promotions_ .= '	<tr>';
                            $promotions_ .= '		<td align="left" valign="top"><img src="' .$this->lib->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
                            $promotions_ .= '	</tr>';
                            $promotions_ .= '</table>';
                            $promotions_ .= '</div>';
                            break;
                    }
                }

                $itemprice = $row['itemprice'];
                $amount = round($itemprice * $row["quality"], 2);
                $subtotal += $amount;
              

                if ($row['product_type'] != 0) {
                    $row['shipping_fee'] = 0;
                    $shipping_rate = 0;
                    $count_ship_free--;
                } else {
                    $tax += $tax_pecen * $amount / 100;
                    if ($row['shipping_fee'] <= 0) {
                        $row['shipping_fee'] = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                }
                $shipping_rate += $row['shipping_fee'];

                $origin = '';
                if ($row['origin'] != '' && $row['origin'] != null) {
                    $origin = '<br>' . $this->lib->ConvertToHtml($row['origin']);
                }

                $tax_rate_items = array();
                $re_tax = $this->db->query("select * from items_tax where itm_key = '" . $row["itm_key"] . "'");
                foreach ($re_tax->result_array() as $row_tax) {
                    $tax_rate_items[] = $row_tax;
                }
  
                for ($i = 0; $i < count($taxs); $i++) {
                    $tax_rate = $taxs[$i]['rate'];
                    for ($j = 0; $j < count($tax_rate_items); $j++) {
                        if ($tax_rate_items[$j]['tax_id'] == $taxs[$i]['id']) {
                            $tax_rate = $tax_rate_items[$j]['tax_rate'];
                        }
                    }

                    $taxs[$i]['tax_item_total'] += round(((float) $tax_rate * (float) $itemprice * (float) $row["quality"]) / 100, 2);
                    $tax_total = $taxs[$i]['tax_item_total'];
                }

                if ($qty_refund != null) {
                    $qty_refund;
                } else {
                    $qty_refund = '0';
                }
                
                $image = $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename;
                $str .= ' <tr class="tr_order_details">';
                $str .= ' <td> <table cellpadding="0" cellspacing="0" border="0"> <tr>';
                $str .= '<td align="left" valign="top" width="90px"><img border="0" src= "' . $image . '" /></td>';
                $str .= ' <td align="left" valign="top" style="padding-left:10px">' . '<div style="clear:both"><b>' . $itm_name . '</b><BR><b>Model: </b>' . $itm_model . $origin . $attributes_str . '</div>' . $promotions_ . '</td>';
                $str .= ' </tr> </table></td>';
                $str .= '<td align="right">$' . number_format($itemprice, 2) . '</td>';
                $str .= '<td align="center">' . number_format($row["quality"]) . '</td>';
                $str .= ' <td align="right">' . $qty_refund . '</td>';
                $str .= '<td align="right">$' . number_format($amount, 2) . '</td>';
                $str .= '</tr>';

                $data['tr_order_details'] = $str;
                $data['@id@'] = $itemid;
            }
            if ($shipping_rate == $handling_fee_new && $count_ship_free == 0)
                $shipping_rate = 0;
            $shipping_fee += round($shipping_rate, 2);
        }//0
            foreach ($taxs as $each_tax) {
                $data['<!--tax_name-->'] = $each_tax['name'];
                $data['<!--tax_item_total-->'] = '$' . number_format($each_tax['tax_item_total'], 2);
            }
        $shipping_fee = round($shipping_fee, 2);
        $tax = round($tax, 2);

        $total = $subtotal + $tax_total + $shipping_fee;
       
      /*  $Wallet = $this->load_myWallet($total, $ck_commission);
        $mywallet = '';
        $totallast = '';
        if (isset($Wallet) && !empty($Wallet)) {

            $mywallet .= ' <tr><td style="text-align:right; padding-right:5px;">MyWallet Account:</td>';
            $mywallet .= '<td style="text-align:right">' . $Wallet[0] . '</td></tr>';

            $totallast .= '<tr><td style="text-align:right; padding-top:10px; padding-right:10px; border-top:#000 solid 1px">TOTAL:</td>';
            $totallast .= '<td style="text-align:right; padding-top:10px;border-top:#000 solid 1px">' . $Wallet[1] . '</td></tr>';
        }

        $data['mywallet'] = $mywallet;
        $data['total_last'] = $totallast;*/
        $data['<!--suptotal-->'] = '$' . number_format($subtotal, 2);
        $data['<!--Total-->'] = '$' . number_format($total, 2);
        if (count($taxs) <= 1)//0
            $data['<!--Tax-->'] = '$0.00';
        $data['<!--shipping_fee-->'] = '$' . number_format($shipping_fee, 2);

        $min_level = 3;
        $Canceled_status = 0;
        $Refunded_status = 0;
        if (count($order_status_level) > 0) {
            foreach ($order_status_level as $level) {
                if ($level == 4)
                    $Canceled_status = 1;
                elseif ($level == 5)
                    $Refunded_status = 1;
                elseif ($level < 4) {
                    if ($level < $min_level) {
                        $min_level = $level;
                    }
                }
            }
        }
        if ($Canceled_status == 1)
            $min_level = 4;
        elseif ($Refunded_status == 1)
            $min_level = 5;
        $data['<!--@order_status@-->'] = isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : null;
        // $strContent = str_replace('<!--@order_status@-->', isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : 'null', $strContent);
        $refund_amount = 0;
        $return_str = '';
        $re_refund = $this->db->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
        foreach ($re_refund->result_array() as $row_return) {
            $reason = (isset($__refund_reason__[$row_return['refund_type']])) ? $__refund_reason__[$row_return['refund_type']] : 'None';
            $refund__ = $this->lib->loadAmountRefund($row_return['id'], $roles['rid'], $this->author->objlogin->uid, $okey, $oid, $tax_pecen, $base_price);
            if (is_numeric($refund__)) {
                $refund__ = $refund__ * (-1);
                $refund_amount += $refund__;
                $return_str .= '<tr>';
                $return_str .= '	<td align="right" valign="middle"><b>' . date("F j, Y g:i A", strtotime($row_return['refund_update'])) . ' refunded:</b></td>';
                $return_str .= '	<td align="right" valign="middle" style="padding-left:20px">' . $this->lib->showMoney($refund__) . '</td>';
                $return_str .= '</tr>';
            }
        }
        if ($refund_amount != 0) {
            $return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
            $return_str .= '<tr>';
            $return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
            $return_str .= '	<td align="right" valign="middle" style="padding-left:20px">' . $this->lib->showMoney($total + $refund_amount) . '</td>';
            $return_str .= '</tr>';
        }
        $data['<!--@refund_row@-->'] = $return_str;
        $data['@okey@'] = $okey;
        
        return $data;
   
    }

    function load_myWallet($total, $ck_commission) {
        $my_wallet = '';
        $total_last = '';
        $result = array();
        if (!empty($ck_commission) && $ck_commission > 0) {

            $total_last = $total;
            $my_wallet = number_format($ck_commission, 2);
            if ($my_wallet > $total)
                $my_wallet = $total;
            $total_last -= $my_wallet;
            $result[0] = '$' . number_format($my_wallet, 2);
            $result[1] = '$' . number_format($total_last, 2);
        }

        return $result;
    }

    
    function loadInvoiceDonate($okey, $ck_commission){
     
        global $total,$__accessRight__, $__order_status__;
	$roles =  $this->lib->loadRoles();
	$oid = 0;

	$total = 0;
	$tax = 0;
	$shipping_fee = 0;
	$subtotal = 0;
	
	$tax_pecen = 0;
	$order_status = 1;
	$base_price = 0;
         $data = array();
	
	$tblcontries = array();
	$strContent = $this->system->parse_templace("shop/print_donate_details.htm", $data = array(), true);
	$re = $this->db->query("select * from tblcontries");
	foreach($re->result_array() as $row ){
		$tblcontries[$row['code']] = $row['name'];	
	}
	$packages = array();
	$order_status_level = array();
	$r = $this->db->query("SELECT * FROM orders WHERE okey = '$okey'");
	foreach( $r->result_array() as $row){
		$oid = $row['orderid'];
		$billing_Name		= $row["billing_name"];
		$billing_Address	= $row["billing_address"];
		$billing_City		= $row["billing_city"];
		$billing_State		= $row["billing_state"];
		$billing_Zip		= $row["billing_zip"];
		$billing_Phone		= $row["billing_phone"];
		$billing_Email		= $row["billing_email"];

		$date				= $row["order_date"];
		$card_number		= $row["card_number"];
		
		/*$strContent	= str_replace('<!--order_number-->', $okey, $strContent);
		$strContent	= str_replace('<!--date-->', date("m/d/Y",strtotime($date)), $strContent);
		$strContent	= str_replace('<!--billingName-->', $billing_Name,$strContent);
		$strContent	= str_replace('<!--billingAddress-->', $billing_Address,$strContent);
		$strContent	= str_replace('<!--billingCity-->', $billing_City.', '.$billing_State.' '.$billing_Zip.', '.(isset($tblcontries[$row["billing_country"]])?$tblcontries[$row["billing_country"]]:''),$strContent);
		$strContent	= str_replace('<!--billingPhone-->', $billing_Phone,$strContent);
		$strContent	= str_replace('<!--billingEmail-->', $billing_Email,$strContent);
		//$strContent	= str_replace('<!--payment_method-->', $row['card_type'], $strContent);
		$strContent	= str_replace('<!--card_number-->', 'xxxxxxxxxxxx'.$card_number, $strContent);*/
                
                $data['<!--order_number-->'] = $okey;
                $data['<!--date-->'] =  date("m/d/Y",strtotime($date));
                  $data['<!--billingName-->'] = $billing_Name;
                   $data['<!--billingAddress-->'] = $billing_Address;
                    $data['<!--billingCity-->'] = $billing_City.', '.$billing_State.' '.$billing_Zip.', '.(isset($tblcontries[$row["billing_country"]])?$tblcontries[$row["billing_country"]]:''); 
                    $data['<!--billingPhone-->'] = $billing_Phone ;
                     $data['<!--billingEmail-->'] = $billing_Email;
                      $data['<!--card_number-->'] = 'xxxxxxxxxxxx'.$card_number;
                    
                    

		
	}
	$arr		= $this->lib->partitionString("<!--startRows-->","<!--endRows-->",$strContent);
	$strHeader	= $arr[0];
	$strRow		= $arr[1];
	$strFooter	= $arr[2];
	$strRows	= '';
	$ong_chu = $this->lib->__loadBoss__();
	$sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
	$sql_manufacturer = '';
	if($roles['rid'] == 5){
		$sql_manufacturer = "and items.uid = ".$ong_chu;
		$sql_orders_promotions .= " and manufacturer_id = ".$ong_chu;
	}
	$arrPromotions = array();
	$re = $this->db->query($sql_orders_promotions);
	foreach($re->result_array() as $row){
		$arrPromotions[] = $row;	
	}
	$arr_manufacturers = array();
	$re_1 = $this->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.origin,items.itm_key,items.product_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC");
	foreach($re_1->result_array() as $row_1){
		$check_exist = false;
		for($m = 0; $m < count($arr_manufacturers); $m++){
			if($arr_manufacturers[$m]['uid'] == $row_1['uid']){
				$arr_manufacturers[$m]['items'][] = $row_1;
				$check_exist = true;
				break;	
			}	
		}
		if($check_exist == false){
			$arr_manufacturers[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
		}	
	}
	
	$subtotal = 0;
	
	for($m = 0; $m < count($arr_manufacturers); $m++){//0
		
		foreach($arr_manufacturers[$m]['items'] as $row){//1
			$itemid = $row["itemid"];
			$odetail = $row['id'];
			$itm_name = $row['itm_name'];
			$itm_model = $row['itm_model'];

			$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");//insert code here 
		
			$arr_file =$this->lib->__loadFileProduct__($itemid);
			$_filename = $arr_file['file'];

			$itemprice = $row['itemprice'];
			$amount = round($itemprice * $row["quality"], 2);
			$subtotal += $amount;

			$origin = '';
			if($row['origin'] != '' && $row['origin'] != null){
				$origin = '<br>'.$this->lib->ConvertToHtml($row['origin']);	
			}
                        
                        
                          $attributes_str = '';
                $re_ = $this->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
                foreach ($re_->result_array() as $row_) {
                    $attributes_str .= '<br><b>' . $row_['label'] . ': </b>' . $row_['name'];
                    if (is_numeric($row_['price']) && $row_['price'] > 0) {
                        $attributes_str .= '&nbsp;&nbsp;(+$' . number_format($row_['price'], 2) . ')';
                    }
                }
                $str = '';       
                $image = $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename;
                $str .= ' <tr class="tr_order_details">';
                $str .= ' <td> <table cellpadding="0" cellspacing="0" border="0"> <tr>';
                $str .= '<td align="left" valign="top" width="90px"><img border="0" src= "' . $image . '" /></td>';
                $str .= ' <td align="left" valign="top" style="padding-left:10px">' . '<div style="clear:both"><b>' . $itm_name . '</b><BR><b>Model: </b>' . $itm_model . $origin . $attributes_str . '</div></td>';//' . $promotions_ . '
                $str .= ' </tr> </table></td>';
                $str .= '<td align="right">$' . number_format($itemprice, 2) . '</td>';
                $str .= '<td align="center">' . number_format($row["quality"]) . '</td>';
                $str .= ' <td align="right">' . $qty_refund . '</td>';
                $str .= '<td align="right">$' . number_format($amount, 2) . '</td>';
                $str .= '</tr>';
                $data['tr_order_details'] = $str;

			/*$t_strList = str_replace("@id@", $itemid, $strRow);
			$t_strList = str_replace("@img@", $this->system->URL_server__()."shopping/data/img/thumb/".$_filename, $t_strList);
			$t_strList = str_replace("<!--desc-->", '<div style="clear:both"><b>'.$itm_name.'</b><BR><b>Model: </b>'.$itm_model.$origin.'</div>', $t_strList);//$attributes_str   /$promotions_
			$t_strList = str_replace("<!--price-->", number_format($itemprice, 2), $t_strList);
			$t_strList = str_replace("<!--qty_return-->", number_format($qty_refund), $t_strList);
			$t_strList = str_replace("<!--qty-->", number_format($row["quality"]), $t_strList);
			$t_strList = str_replace("<!--total-->", number_format($amount, 2), $t_strList);
			$strRows .= $t_strList;//.$free_product_row;*/
		}//1

	}//0

	$total = $subtotal;
	//load_myWallet($strFooter,$total);
        $data['<!--suptotal-->'] = '$'.number_format($subtotal,2);
          $data['<!--Total-->'] = '$'.number_format($total,2);
            $data['@okey@'] = $okey;

	/*$strFooter = str_replace("<!--suptotal-->",'$'.number_format($subtotal,2),$strFooter);
	$strFooter = str_replace("<!--Total-->", '$'.number_format($total,2),$strFooter);
	$strContent = $strHeader . $strRows . $strFooter;
	$strContent = str_replace("@okey@", $okey, $strContent);*/
        return $data;
	//return $strContent;

    }
    
    
    
    function check_order_voucher($order_id) {
      
        $check = true;
        $oid = $this->database->db_result("select orderid from orders WHERE okey = '$order_id'");
        $re = $this->db->query("select order_detais.itemid from order_detais where order_detais.orderid = '" . $oid . "' ");
        foreach ($re->result_array() as $row) {
            $product_type = $this->database->db_result("select product_type from items where itm_id = '" . $row['itemid'] . "'");
            if ($product_type == 0) {
                $check = false;
                break;
            }
        }
        return $check;
    }

}









