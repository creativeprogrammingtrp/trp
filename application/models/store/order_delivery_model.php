<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order_delivery_model extends CI_Model {

    var $okey = '';
    var $oid = 0;
    var $tblcontries = array();
    var $shipping_fee = 0;
    var $tax = 0;
    var $subtotal = 0;
    var $tax_pecen = 0;
    var $base_price = 0;
    var $order_status = 1;
    var $order_status_str = '';
    var $packages = array();
    var $order_date = '';
    var $totalPrice = 0;
    var $shippingName = '';
    var $shippingAddress = '';
    var $shippingCity = '';
    var $billingName = '';
    var $billingAddress = '';
    var $billingCity = '';
    var $billingEmail = '';
    var $card_number = '';
    var $ship_label = '';
    var $arr_manufacturers = array();
    var $arrPromotions = array();
    var $arr_orders_handling = array();
    var $ItemDetails = array();
    var $order_status_level = array();
    var $refund_row = '';
    var $sql_customer = '';
    var $key_word_sql = '';
    var $status_sql = '';
    var $month_sql = '';
    var $year_sql = '';
    var $sql_uid_clients = '';
    var $sql_manufacturer = '';
    var $sql_promotion = '';
    var $order_data = array();
    var $maxlength = 0;
    var $page = 1;
    var $rid = '';
    var $order_schedule_details = array();
    var $check_commission = '';
    var $mywallet = '';

    //View List
    public function ViewList($page, $colum, $sortby) {
        return json_encode($this->load_order($page, $colum, $sortby));
    }

    function check_valid(&$varible) {
        if (isset($varible) && trim($varible) != '') {
            return $this->lib->escape($varible);
        }
        return '';
    }

    function set_sql_customer($ukey) {
        if ($this->check_valid($ukey) != '') {
            $uid = $this->database->db_result("select uid from users where ukey = '$ukey'");
            if (is_numeric($uid) && $uid > 0) {
                $this->sql_customer = ' and orders.user_id = ' . $uid;
            }
        }
    }

    function set_key_word_sql($key_word) {
        if ($this->check_valid($key_word) != '') {
            $key_word_ = '';
            $arr_key = explode(" ", $key_word);
            if (count($arr_key) > 0) {
                foreach ($arr_key as $key) {
                    if ($key != '') {
                        $key_word_ .= " and (";
                        $key_word_ .= " orders.okey like '%$key%'";
                        $key_word_ .= " or orders.shipping_name like '%$key%'";
                        $key_word_ .= " or orders.shipping_address like '%$key%'";
                        $key_word_ .= " or orders.shipping_city like '%$key%'";
                        $key_word_ .= " or orders.shipping_state like '%$key%'";
                        $key_word_ .= " or orders.shipping_zip like '%$key%'";
                        $key_word_ .= " or orders.shipping_phone like '%$key%'";
                        $key_word_ .= " or orders.billing_name like '%$key%'";
                        $key_word_ .= " or orders.billing_address like '%$key%'";
                        $key_word_ .= " or orders.billing_city like '%$key%'";
                        $key_word_ .= " or orders.billing_state like '%$key%'";
                        $key_word_ .= " or orders.billing_zip like '%$key%'";
                        $key_word_ .= " or orders.billing_phone like '%$key%'";
                        $key_word_ .= " or orders.billing_email like '%$key%'";
                        $key_word_ .= " ) ";
                    }
                }
                $this->key_word_sql = $key_word_;
            }
        }
    }

    function set_status_sql($status) {
        if ($this->check_valid($status) != '' && is_numeric($this->check_valid($status))) {
            $this->status_sql = " and orders.status = '" . $status . "'";
        }
    }

    function set_month_sql($month) {
        if ($this->check_valid($month) != '' && is_numeric($this->check_valid($month))) {
            $this->month_sql = " and MONTH(orders.order_date) = '" . $month . "'";
        }
    }

    function set_year_sql($year) {
        if ($this->check_valid($year) != '' && is_numeric($this->check_valid($year))) {
            $this->year_sql = " and YEAR(orders.order_date) = '" . $year . "'";
        }
    }

    function set_sql_uid_clients() {
        $this->sql_uid_clients = " AND orders.user_id = " . $this->author->objlogin->uid;
    }

    function set_sql_manufacturer() {
        $ong_chu = $this->lib->__loadBoss__();
        $this->sql_manufacturer = " and items.uid = " . $ong_chu;
        $this->sql_promotion = " and manufacturer_id = " . $ong_chu;
    }

    function load_order($page, $col, $sortby_) {

        $roles = $this->author->objlogin->role;

        $num_per_pager = NUMROWPERPAGE;
        $limit = $num_per_pager * ($page - 1);

        $__order_status__ = $this->config->item('__order_status__');
        $__refund_reason__ = $this->config->item('__refund_reason__');

        if ($roles['rid'] == MANUFACTURER) {
            $this->set_sql_manufacturer();
        } elseif ($roles['rid'] != ADMINISTRATOR && $roles['rid'] != NWMANAGEMENT) {
            $this->set_sql_uid_clients();
        }

        $sort_type = '';
        $sort_by = 'desc';

        if ($sortby_ == 0)
            $sort_by = 'asc';

        switch ($col) {
            case 0:
                $sort_type = 'A.okey';
                break;
            case 1:
                $sort_type = 'A.status';
                break;
            case 2:
                $sort_type = 'A.order_date';
                break;
        }
     
        $sql_1 = " (select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name,orders.r_ordernum from orders where orders.status <> -1  " . $this->sql_uid_clients . $this->sql_customer . " ) as A";
        $sql_2 = " (select distinct order_detais.orderid from order_detais join items on order_detais.itemid = items.itm_id where 1=1 " . $this->sql_manufacturer . ") as B";
        $sql_3 = " (select * from orders_auto_delivery) as C";


        $max_sql = "select count(A.orderid) ";
        $max_sql .= " from " . $sql_1 . " join " . $sql_2 . " on A.orderid = B.orderid join " . $sql_3 . " on A.orderid = C.oid GROUP BY A.orderid";

        $maxlength = $this->database->db_result($max_sql);

        $max_sql = "select C.id,C.oid,C.schedule_date,C.status AS status_delivery, A.orderid,A.okey,A.order_date,A.order_tax,A.shipping_fee,A.status,A.billing_name,A.r_ordernum";
        $max_sql .= " from " . $sql_1 . " join " . $sql_2 . " on A.orderid = B.orderid join " . $sql_3 . " on A.orderid = C.oid";
        $max_sql .= " ORDER BY A.orderid DESC, C.schedule_date ASC limit $limit," . $num_per_pager;

        $data = array();
        $query = $this->db->query($max_sql);
        foreach ($query->result_array() as $row) {
            $order_status = $row['status'];
            $okey = $row['okey'];
            $packages = array();
            $order_status_level = array();
            $tracking_number = $this->database->db_result("select tracking_number from shipments where okey = '$okey'");
            if ($tracking_number == false)
                $tracking_number = '';
            $re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
            foreach ($re_2->result_array() as $row_2) {
                $ship = 0;
                $re_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey = '$okey'");

                if ($re_3->num_rows() > 0) {
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
            $tax_pecen = $row["order_tax"];
            $handling_fee = $row['shipping_fee'];
            $order_refund = array();

            $re_refund = $this->db->query("select refund_key,id,refund_date,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
            foreach ($re_refund->result_array() as $row_return) {
                $check_ok_refund = false;
                $status_refund = 1;
                $del_refund = 1;
                $re_1 = $this->db->query("select odetail_return.odetail,odetail_return.qty,odetail_return.status,odetail_return.max_qty,order_detais.quality from odetail_return join order_detais join items on odetail_return.odetail = order_detais.id and order_detais.itemid = items.itm_id where odetail_return.rid = " . $row_return['id'] . " $sql_manufacturer");
                foreach ($re_1->result_array() as $row_1) {
                    if ($row_1['qty'] > 0) {
                        $check_ok_refund = true;
                        if ($row_1['status'] == 1) {
                            $del_refund = 0;
                        }
                        if ($row_1['status'] == 0) {
                            $status_refund = 0;
                        }
                    }
                }
                if ($check_ok_refund == false)
                    continue;
                $reason = (isset($__refund_reason__[$row_return['refund_type']])) ? $__refund_reason__[$row_return['refund_type']] : 'None';
                $refund_date = '';
                if ($row_return['refund_update'] != NULL)
                    $refund_date = gmdate("m/d/Y", strtotime($row_return['refund_update']));
                elseif ($row_return['refund_date'] != NULL)
                    $refund_date = gmdate("m/d/Y", strtotime($row_return['refund_date']));
                $refund_status = 'Refund Completed';
                if ($status_refund == 0)
                    $refund_status = 'Refund Pending';
                $order_refund[] = array('refund_key' => $row_return['refund_key'], 'reason' => $reason, 'refund_date' => $refund_date, 'status' => $refund_status, 'total' => $this->lib->showMoney(-($this->loadAmountRefund2($row_return['id'], $roles['rid'], $this->author->objlogin->uid))), 'del' => $del_refund);
            }

            $arrPromotions = array();
            $sql_orders_promotions = "select * from orders_promotions where order_key = '$okey' " . $this->sql_promotion;
            $re_pro = $this->db->query($sql_orders_promotions);
            foreach ($re_pro->result_array() as $row_pro) {
                $arrPromotions[] = $row_pro;
            }
            $arr_manufacturers = array();
            $re_1 = $this->db->query("select order_detais.*,items.itm_key,items.uid from order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = " . $row['orderid'] . $this->sql_manufacturer . " order by order_detais.id ASC");
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
            $re_1 = $this->db->query("select * from orders_handling where oid = " . $row['orderid']);
            foreach ($re_1->result_array() as $row_1) {
                $arr_orders_handling[] = $row_1;
            }
            $subtotal = 0;
            $shipping_fee = 0;
            for ($m = 0; $m < count($arr_manufacturers); $m++) {//0
                $handling_fee_new = $handling_fee;
                foreach ($arr_orders_handling as $oh) {
                    if ($oh['uid'] == $arr_manufacturers[$m]['uid']) {
                        $handling_fee_new = $oh['handling'];
                        break;
                    }
                }
                $shipping_rate = $handling_fee_new;
                $count_ship_free = count($arr_manufacturers[$m]['items']);
                foreach ($arr_manufacturers[$m]['items'] as $row_1) {//1
                    $itemid = $row_1['itemid'];
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
                    if ($qty_ship == $row_1["quality"]) {
                        $status_item = 3;
                    } elseif ($qty_par > 0 || $qty_ship > 0) {
                        $status_item = 2;
                    }
                    $order_status_level[] = $status_item;
                
                    $itm_price = ($roles['rid'] == MANUFACTURER) ? $row_1["current_cost"] : $row_1["itemprice"];
                    $check_shipping_free = false;
                    if (count($arrPromotions) > 0) {//2
                        foreach ($arrPromotions as $promotions) {//3
                            if ($promotions['itm_key'] == $row_1['itm_key']) {//4
                                switch ($promotions['promo_type']) {
                                    case 3:
                                        $check_shipping_free = true;
                                        break;
                                    case 4:
                                        $check_shipping_free = true;
                                        break;
                                }
                            }//4
                        }//3
                    }//2
                    if ($row_1['shipping_fee'] <= 0) {
                        $row_1['shipping_fee'] = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                    if ($roles['rid'] == MANUFACTURER) {
                        $shipping_rate += round($row_1['last_shipping'] * $row_1["quality"], 2);
                    } else {
                        $shipping_rate += $row_1['shipping_fee'];
                    }
                    $subtotal += round($itm_price * $row_1["quality"], 2);
                }//1
                if ($roles['rid'] != MANUFACTURER) {
                    if ($shipping_rate == $handling_fee_new && $count_ship_free == 0)
                        $shipping_rate = 0;
                }
                $shipping_fee += round($shipping_rate, 2);
            }//0
            $shipping_fee = round($shipping_fee, 2);
            $tax = round($tax_pecen * $subtotal / 100, 2);
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
            if ($order_status == 4)
                $Canceled_status = 1;
            if ($Canceled_status == 1)
                $min_level = 4;
            elseif ($Refunded_status == 1)
                $min_level = 5;

            $order_date_int = strtotime($row['order_date']);
            $refund_action = 'no';
            $cancel_action = 'no';
            if ($row['r_ordernum'] != NULL && $row['r_ordernum'] != '') {
                if ($roles['rid'] == ADMINISTRATOR || $roles['rid'] == NWMANAGEMENT || $roles['rid'] == MANUFACTURER) {
                    if ($this->lib->getTimeGMT() - $order_date_int <= 30 * 24 * 60 * 60) {
                        $refund_action = 'yes';
                    }
                    if ($this->lib->getTimeGMT() - $order_date_int <= 3 * 24 * 60 * 60 && $min_level == 1) {
                        $cancel_action = 'yes';
                    }
                }
            }

            $order_total = $this->load_Auto_myWallet($okey, $subtotal + $tax + $shipping_fee);
            $data[] = array(
                'okey' => $okey,
                'billing_name' => $row['billing_name'],
                'order_date' => $row['order_date'],
                'order_date_format' => gmdate("l, F j, Y", $order_date_int),
                'tracking' => $tracking_number,
                'status' => (int) $min_level,
                'status_format' => isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : 'null',
                'order_total' => (float) ($subtotal + $tax + $shipping_fee),
                'refund' => $order_refund,
                'refund_action' => $refund_action,
                'cancel' => $cancel_action,
                'schedule_date' => gmdate("l, F j, Y", strtotime($row['schedule_date'])),
                'status_delivery' => $row['status_delivery']
            );
        }
  
        $this->order_data = $data;
        $this->maxlength = (int) $maxlength;
        $this->page = (int) $page;
        $this->rid = (int) $roles['rid'];
        $this->getScheduleDate();

        return array('data' => $this->order_data, 'maxlength' => $this->maxlength, 'page' => $this->page, 'rid' => $this->rid);
    }

    private function getScheduleDate() {
        $arr_data = $this->order_data;
        $order_schedule = array();
        $mem = '';
        for ($i = 0; $i < count($arr_data); $i++) {
            $okey = $arr_data[$i]['okey'];
            if ($i > 0)
                if ($okey == $arr_data[$i - 1]['okey'])
                    continue;

            if ($arr_data[$i]['status_delivery'] == PENDING) {
                $arr_data[$i]['last_date'] = $arr_data[$i]['schedule_date'];
                if (isset($arr_data[$i + 1]['okey']) && $arr_data[$i+1]['okey'] == $okey) {
                    $arr_data[$i]['next_date'] = $arr_data[$i + 1]['schedule_date'];
                } else {
                    $arr_data[$i]['next_date'] = 'None';
                }
                $order_schedule[] = $arr_data[$i];
                continue;
            } elseif ($arr_data[$i]['status_delivery'] == PACKERSHIPPING) {
                $arr_data[$i]['last_date'] = $arr_data[$i]['schedule_date'];
                if ($arr_data[$i + 1]['okey'] == $okey) {
                    $arr_data[$i]['next_date'] = $arr_data[$i + 1]['schedule_date'];
                } else {
                    $arr_data[$i]['next_date'] = 'None';
                }
                $order_schedule[] = $arr_data[$i];
                continue;
            } elseif ($arr_data[$i]['status_delivery'] == COMPLETED) {
               
                while ($okey == $arr_data[$i + 1]['okey'] && ($arr_data[$i + 1]['status_delivery'] == COMPLETED || $arr_data[$i + 1]['status_delivery'] == CANCELED || $arr_data[$i + 1]['status_delivery'] == REFUNDED)) {
                    $arr_data[$i] = $arr_data[$i + 1];
                    $i++;
                }

                $arr_data[$i]['last_date'] = $arr_data[$i]['schedule_date'];
                if ($arr_data[$i + 1]['okey'] == $okey) {
                    $arr_data[$i]['next_date'] = $arr_data[$i + 1]['schedule_date'];
                } else {
                    $arr_data[$i]['next_date'] = 'None';
                }
                $order_schedule[] = $arr_data[$i];
                continue;
            } elseif ($arr_data[$i]['status_delivery'] == CANCELED || $arr_data[$i]['status_delivery'] == REFUNDED) {

                while ($okey == $arr_data[$i + 1]['okey'] && ($arr_data[$i + 1]['status_delivery'] == COMPLETED || $arr_data[$i + 1]['status_delivery'] == CANCELED || $arr_data[$i + 1]['status_delivery'] == REFUNDED)) {
                    $arr_data[$i] = $arr_data[$i + 1];
                    $i++;
                }

                $arr_data[$i]['last_date'] = $arr_data[$i]['schedule_date'];
                if ($arr_data[$i + 1]['okey'] == $okey) {
                    $arr_data[$i]['next_date'] = $arr_data[$i + 1]['schedule_date'];
                } else {
                    $arr_data[$i]['next_date'] = 'None';
                }
                $order_schedule[] = $arr_data[$i];
                continue;
            }
        }
        $this->order_data = $order_schedule;
    }

    private function loadAmountRefund2($refundID, $rid = ADMINISTRATOR, $uid) {
        $okey = '';
        $re = $this->db->query("select * from orders_return where id = $refundID");
        if ($row = $re->row_array()) {
            $okey = $row['okey'];
            $re_1 = $this->db->query("select * from odetail_return where rid = $refundID order by id ASC");
            foreach ($re_1->result_array() as $row_1) {
                $dataRefund[] = $row_1;
            }
        }
        $subtotal = 0;
        $total = 0;
        $tax = 0;
        $shipping_fee = 0;
        $Shipping_Discounts = 0;
        $oid = 0;
        $tax_pecen = 0;
        $base_price = 0;
        $order_date = 0;
        $total_price_buy = 0;
        $r = $this->db->query("SELECT orderid,shipping_fee,order_tax FROM orders WHERE okey in ($okey)");
        if ($row = $r->row_array()) {
            $oid = $row['orderid'];
            $tax_pecen = $row["order_tax"];
        }
        $total_ship_buy = 0;
        $total_ship_refund = 0;

        $sql_manufacturer = '';
        $sql_orders_promotions = "select * from orders_promotions where order_key in ($okey)";
        if ($rid == MANUFACTURER) {
            $sql_manufacturer = "and items.uid = " . $uid;
            $sql_orders_promotions .= " and manufacturer_id = " . $uid;
        }
        $arrPromotions = array();
        $re = $this->db->query($sql_orders_promotions);
        foreach ($re->result_array() as $row) {
            $arrPromotions[] = $row;
        }

        $order_detais = array();
        $strSql = "SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.duration_refund,items.duration_type_refund,items.charge_refund,items.charge_refund_type FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '$oid' $sql_manufacturer order by order_detais.id ASC";
        $r = $this->db->query($strSql);
        foreach ($r->result_array() as $row) {
            $max_qty = $row['quality'];
            $re_2 = $this->db->query("select qty from odetail_return where odetail = '" . $row['id'] . "' and status = 1");
            foreach ($re_2->result_array() as $row_2) {
                $max_qty -= $row_2['qty'];
            }
            if ($max_qty < 0)
                $max_qty = 0;
            $qty_buy = $row['quality'] = $max_qty;
            $qty_refund = 0;
            for ($r_ = 0; $r_ < count($dataRefund); $r_++) {
                if ($dataRefund[$r_]['odetail'] == $row['id']) {
                    if ($dataRefund[$r_]['status'] == 1) {
                        $qty_buy = $row['quality'] = $dataRefund[$r_]['max_qty'];
                    }
                    $qty_refund = ($dataRefund[$r_]['qty'] > $qty_buy) ? $qty_buy : $dataRefund[$r_]['qty'];
                    $qty_buy -= $qty_refund;
                    break;
                }
            }
            $row['qty_buy'] = $qty_buy;
            $row['qty_refund'] = $qty_refund;
            $order_detais[] = $row;
        }
        $count = 0;
        $count_shipping_free = 0;
        $count_shipping_free_2 = 0;
        foreach ($order_detais as $row) {
            $count++;
            $itemid = $row["itemid"];
            $odetail = $row['id'];

            $itemprice_buy = $itemprice_old = $last_itemprice = $row['last_itemprice'];
            if ($rid == MANUFACTURER) {
                $itemprice_buy = $itemprice_old = $last_itemprice = $row['last_cost'];
            }
            $ship_per_item_old = $last_shipping = $row['last_shipping'] * $row['quality'];
            $ship_buy = $ship_buy_last = $row["qty_buy"] * $row['last_shipping'];
            $ship_refund = $row["qty_refund"] * $row['last_shipping'];

            $check_shipping_free = false;
            $check_shipping_free_2 = false;
            $arr_show_promotions = array();
            $free_product_row = '';
            if (count($arrPromotions) > 0) {
                foreach ($arrPromotions as $promotions) {
                    if ($promotions['itm_key'] == $row['itm_key']) {//0
                        switch ($promotions['promo_type']) {
                            case 1:
                                for ($r_ = 0; $r_ < count($order_detais); $r_++) {
                                    if ($order_detais[$r_]['itm_key'] == $promotions['product_key']) {
                                        if ($order_detais[$r_]['quality'] >= $promotions['minqty']) {
                                            if ($promotions['discount_type'] == 0) {
                                                $itemprice_old -= $last_itemprice * $promotions['discount'] / 100;
                                            } else {
                                                $itemprice_old -= $promotions['discount'];
                                            }
                                            if ($order_detais[$r_]['qty_buy'] >= $promotions['minqty']) {
                                                if ($promotions['discount_type'] == 0) {
                                                    $itemprice_buy -= $last_itemprice * $promotions['discount'] / 100;
                                                } else {
                                                    $itemprice_buy -= $promotions['discount'];
                                                }
                                            }
                                        }
                                        break;
                                    }
                                }
                                break;
                            case 3:
                                for ($r_ = 0; $r_ < count($order_detais); $r_++) {
                                    if ($order_detais[$r_]['itm_key'] == $promotions['product_key']) {
                                        if ($order_detais[$r_]['quality'] >= $promotions['minqty']) {
                                            $check_shipping_free = true;
                                            if ($promotions['discount_type'] == 0) {
                                                $ship_per_item_old -= $last_shipping * $promotions['discount'] / 100;
                                            } else {
                                                $ship_per_item_old -= $promotions['discount'];
                                            }
                                            if ($order_detais[$r_]['qty_buy'] >= $promotions['minqty']) {
                                                if ($promotions['discount_type'] == 0) {
                                                    $ship_buy -= $ship_buy_last * $promotions['discount'] / 100;
                                                } else {
                                                    $ship_buy -= $promotions['discount'];
                                                }
                                                $check_shipping_free_2 = true;
                                            }
                                        }
                                        break;
                                    }
                                }
                                break;
                            case 4:
                                for ($r_ = 0; $r_ < count($order_detais); $r_++) {
                                    if ($order_detais[$r_]['itm_key'] == $promotions['product_key']) {
                                        if ($order_detais[$r_]['quality'] >= $promotions['minqty']) {
                                            $check_shipping_free = true;
                                            $ship_per_item_old = 0;
                                            if ($order_detais[$r_]['qty_buy'] >= $promotions['minqty']) {
                                                $ship_buy = 0;
                                                $check_shipping_free_2 = true;
                                            }
                                        }
                                        break;
                                    }
                                }
                                break;
                        }
                    }//0
                }
            }
            if ($ship_per_item_old <= 0) {
                $ship_per_item_old = 0;
                if ($check_shipping_free == true)
                    $count_shipping_free++;
            }
            if ($ship_buy <= 0) {
                $ship_buy = 0;
                if ($check_shipping_free_2 == true)
                    $count_shipping_free_2++;
            }
            $shipping_fee += $ship_per_item_old;
            $total_ship_buy += $ship_buy;
            $total_ship_refund += $ship_refund;

            if ($itemprice_old < 0)
                $itemprice_old = 0;
            $itemprice_old = round($itemprice_old, 2);
            $price_old = round($itemprice_old * $row['quality'], 2);
            $total += $price_old;

            if ($itemprice_buy < 0)
                $itemprice_buy = 0;
            $itemprice_buy = round($itemprice_buy, 2);
            $price_buy = round($row["qty_buy"] * $itemprice_buy, 2);
            $total_price_buy += $price_buy;
            $total_price = $price_old - $price_buy;
            $subtotal += $total_price;
        }
        $tax_old = round($tax_pecen * $total / 100, 2);
        $tax_buy = round($tax_pecen * $total_price_buy / 100, 2);
        $tax_return = $tax_old - $tax_buy;

        $Shipping_Discounts = round($total_ship_buy + $total_ship_refund, 2) - round($shipping_fee, 2);
        if ($Shipping_Discounts != 0) {
            $subtotal -= $Shipping_Discounts;
        }
        $subtotal += $tax_return;
        if ($subtotal < 0)
            $subtotal = 0;
        return $subtotal;
    }

    function setOkey($okey) {
        $this->okey = $okey;
    }

    //inert code here


    function getOidBySkey($skey) {
        $array = array();
        $sql = $this->db->query("select oid from orders_auto_delivery where skey = " . $skey);
        foreach ($sql->result_array() as $rows) {
            $sql = $this->db->query("select okey from orders where orderid = " . $rows['oid']);
            foreach ($sql->result_array() as $row) {
                $array[] = implode(',', $row);
            }
        }
        return $array;
    }

    public function loadAutoInvoicesData($okey) {

        global $total, $__accessRight__, $__order_status__;
        $__order_status__ = $this->config->item('__order_status__');
        $__refund_reason__ = $this->config->item('__refund_reason__');
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

        $r = $this->db->query("SELECT * FROM orders WHERE okey in ($okeys)");

        $subtotal = 0;
        $tax = 0;
        $shipping_fee = 0;
        $total_itemTax = 0;

        foreach ($r->result_array() as $row) {
            $oid = $row['orderid'];
            $okeye = $row['okey'];
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
            $data['order_number'] = $okey;
            $data ['order_date'] = date("m/d/Y", strtotime($date));
            $data['billingName'] = $billing_Name;
            $data['billingAddress'] = $billing_Address;
            $data['billingCity'] = $billing_City . ', ' . $billing_State . ' ' . $billing_Zip . ', ' . (isset($tblcontries[$row["billing_country"]]) ? $tblcontries[$row["billing_country"]] : '');
            $data['billingPhone'] = $billing_Phone;
            $data['billingEmail'] = $billing_Email;
            $data['shippingName'] = $shipping_Name;
            $data['shippingAddress'] = $shipping_Address;
            $data['shippingCity'] = $shipping_City . ', ' . $shipping_State . ', ' . $shipping_Zip;
            $data['shippingPhone'] = $shipping_Phone;
            //$data['<!--payment_method-->'] = $row['card_type'];
            $data['card_number'] = 'xxxxxxxxxxxx' . $card_number;

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
            $data['ship_label'] = $ship_label;
            // $re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '$okey'");
            $re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey  = '{$okeye}' ");
            //$re_2 = $this->db->query("select id,pkey,shipment_ID from packages where okey in ('$okeys') ");
            foreach ($re_2->result_array() as $row_2) {
                $ship = 0;
                //$re_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey = '$okey'");
                ///$re_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey in ('$okeys') ");
                $re_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey = '{$okeye}' ");
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

            $data['order_status'] = (isset($oStatus[$order_status])) ? $oStatus[$order_status] : '';
            $ong_chu = $this->lib->__loadBoss__();
            // $sql_orders_promotions = "select * from orders_promotions where order_key = '$okey'";
            // $sql_orders_promotions = "select * from orders_promotions where order_key in ('$okeys') ";
            $sql_orders_promotions = "select * from orders_promotions where order_key  = '{$okeye}' ";
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
                                $desc_free .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
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
                                $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
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
                                $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                                $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: ' . $discount_str . '</td>';
                                $promotions_ .= '	</tr>';
                                $promotions_ .= '</table>';
                                $promotions_ .= '</div>';
                                break;
                            case 4:
                                $promotions_ .= '<div style="clear:both; padding-top:10px">';
                                $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                                $promotions_ .= '	<tr>';
                                $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
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


                    /* $image = $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename;
                      $str .= '<tr class="tr-row">';
                      $str .=  '<td align="left" valign="top" class="td-row">';
                      $str .=   '<table cellpadding="0" cellspacing="0" border="0">';
                      $str .=   '<tr>';
                      $str .=    '<td align="left" valign="top" width="100px"><img src= "' . $image . '" border="0" /></td>';
                      $str .=    '<td align="left" valign="top" style="padding: 10px;">'. '<div style="clear:both"><b>' . $itm_name . '</b><BR><b>Model: </b>' . $itm_model . $origin . $attributes_str .'</td>';
                      $str .=   '</tr>';
                      $str .=  '</table>';
                      $str .= '</td>';
                      $str .= '<td align="left" valign="middle" class="td-row">$' . number_format($itemprice, 2) . '</td>';
                      $str .= '<td align="center" valign="middle" class="td-row">' . number_format($row["quality"]) . '</td>';
                      $str .= '<td align="center" valign="middle" class="td-row">' . $qty_refund . '</td>';
                      $str .= '<td align="right" valign="middle" class="td-row">$' . number_format($amount, 2) . '</td>';
                      $str .= '</tr>'; */



                    $this->ItemDetails[] = array(
                        'img' => $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename,
                        'desc' => '<div><ul><li>' . $itm_name . '</li><li style="padding-top: 10px">Model:' . $itm_model . '</li><li style="padding-top: 10px">' . $origin . $attributes_str . '</li></ul></div>' . $promotions_,
                        'price' => number_format($itemprice, 2),
                        'qty_buy' => number_format($row["quality"]),
                        'qty_return' => $qty_refund,
                        'total' => number_format($amount, 2)
                    );




                    //$data['tr_order_details'] = $str;ItemDetails
                    $data['ItemDetails'] = $this->ItemDetails;
                    $data['@id@'] = $itemid;
                }
                if ($shipping_rate == $handling_fee_new && $count_ship_free == 0)
                    $shipping_rate = 0;
                $shipping_fee += round($shipping_rate, 2);
            }//0

            foreach ($taxs as $each_tax) {
                $total_itemTax += $each_tax['tax_item_total'];
                $data['tax_name'] = $each_tax['name'];
                $data['tax_item_total'] = '$' . number_format($total_itemTax, 2);
            }
            $shipping_fee = round($shipping_fee, 2);
            $tax = round($tax, 2);

            $total = $subtotal + $total_itemTax + $shipping_fee; //$tax_total

            $Wallet = $this->load_myWallet($okeys, $total);

            $mywallet = '';
            $totallast = '';


            if (isset($Wallet) && !empty($Wallet)) {

                $mywallet .= ' <tr>';
                $mywallet .= '<td style="text-align:right;"><h3>MyWallet Account:</h3></td>';
                $mywallet .= ' <td style="text-align:right"><h5>' . $Wallet[0] . ' </h5></td>';
                $mywallet .= ' </tr>';
                $mywallet .= '<tr>';
                $mywallet .= '<td style="text-align:right; padding-top:10px; border-top:#000 solid 1px"><h3>TOTAL:</h3></td>';
                $mywallet .= ' <td style="text-align:right; padding-top:10px;border-top:#000 solid 1px"><h5>' . $Wallet[1] . '</h5>';
                $mywallet .= ' </td>';
                $mywallet .= '</tr> ';
            }
            $data['mywallet'] = $mywallet;
            $data['total_last'] = $totallast;
            $data['suptotal'] = '$' . number_format($subtotal, 2);
            $data['totalPrice'] = '$' . number_format($total, 2);
            if (count($taxs) <= 1)//0
                $data['Tax'] = '$0.00';
            $data['shipping_fee'] = '$' . number_format($shipping_fee, 2);

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
            $data['order_status'] = isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : null;
            // $strContent = str_replace('<!--@order_status@-->', isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : 'null', $strContent);
            $refund_amount = 0;
            $return_str = '';
            $re_refund = $this->db->query("select id,refund_update,refund_type from orders_return where okey = '$okey' order by id DESC");
            //$re_refund = $this->db->query("select id,refund_update,refund_type from orders_return where okey = '{$okeye}'  order by id DESC");
            foreach ($re_refund->result_array() as $row_return) {
                $reason = (isset($__refund_reason__[$row_return['refund_type']])) ? $__refund_reason__[$row_return['refund_type']] : 'None';
                $refund__ = $this->lib->loadAmountRefund($row_return['id'], $roles['rid'], $this->author->objlogin->uid, $okeye, $oid, $tax_pecen, $base_price);
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
            $data['refund_row'] = $return_str;
            $data['@okey@'] = $okey;
        }//
        return $data;
    }

    function load_Auto_myWallet($okey = '', $total = 0) {

        $re = $this->db->query("select * from payments_orders join payments on payments_orders.pkey = payments.pkey where payments.role = " . Sale_Representatives . " and payments_orders.okey = $okey ");
        if ($re->num_rows() <= 0)
            return array();
        $row = $re->row_array();
        $roles = $this->author->objlogin->role;
        $total_last = $total;
        $my_wallet = number_format($row['pay'], 2);
        if ($my_wallet > $total)
            $my_wallet = $total;
        $total_last += $this->shipping_fee + $this->tax - $my_wallet;

        return array(
            'myVallet' => '$' . number_format($my_wallet, 2),
            'total_last' => '$' . number_format($total_last, 2)
        );
    }
    //end code more 

    function loadOrderInfo() {

        if ($this->okey == '')
            return;
        $this->subtotal = 0;
        $this->__order_status__ = $this->config->item('__order_status__');
        $this->loadCountriesList();

        $r = $this->db->query("SELECT * FROM orders WHERE okey  = '" . $this->okey . "' ");
        foreach ($r->result_array() as $row) {
            $this->oid = $row['orderid'];
            $this->tax_pecen = $row["order_tax"];
            $this->order_date = gmdate("F j, Y", strtotime($row["order_date"]));
            $this->card_number = $row["card_number"];
            $this->base_price = $row['shipping_fee'];
            $this->order_status = $row['status'];
            $this->shippingName = $row["shipping_name"];
            $this->shippingAddress = $row["shipping_address"];
            $this->shippingCity = $row["shipping_city"] . ', ' . $row["shipping_state"] . ' ' . $row["shipping_zip"] . ', ' . (isset($this->tblcontries[$row["shipping_country"]]) ? $this->tblcontries[$row["shipping_country"]] : '');
            $this->billingName = $row["billing_name"];
            $this->billingAddress = $row["billing_address"];
            $this->billingCity = $row["billing_city"] . ', ' . $row["billing_state"] . ' ' . $row["billing_zip"] . ', ' . (isset($this->tblcontries[$row["billing_country"]]) ? $this->tblcontries[$row["billing_country"]] : '');
            $this->billingEmail = $row["billing_email"];
            $this->card_number = "XXXXX" . $row['card_number'];
            $this->ship_label = $this->database->db_result("select label from shipping_rates where skey = '" . $row['shipping_key'] . "'");
        }
        $this->loadpackages();
        $this->loadPromotions();
        $this->loadManufacturers();
        $this->loadOrderHandling();
       // $this->loadOrderStatus();
        $this->loadItemDetails();
        $load_myWallet = $this->load_Auto_myWallet($this->okey, $this->subtotal);

        if (isset($load_myWallet) && !empty($load_myWallet)) {

            $mywallet = '';

            $mywallet .= ' <tr>';
            $mywallet .= '<td style="text-align:right;"><h3>MyWallet Account:</h3></td>';
            $mywallet .= ' <td style="text-align:right"><h5>' . $load_myWallet['myVallet'] . ' </h5></td>';
            $mywallet .= ' </tr>';
            $mywallet .= '<tr>';
            $mywallet .= '<td style="text-align:right; padding-top:10px; border-top:#000 solid 1px"><h3>TOTAL:</h3></td>';
            $mywallet .= ' <td style="text-align:right; padding-top:10px;border-top:#000 solid 1px"><h5>' . $load_myWallet['total_last'] . '</h5>';
            $mywallet .= ' </td>';
            $mywallet .= '</tr> ';


            $this->mywallet = $mywallet;
        }
    }

    function loadOrderSchedule() {
        $oid = $this->database->db_result("select orderid from orders where okey = '" . $this->okey . "' ");
        $re = $this->db->query("select schedule_date,status from orders_auto_delivery where oid = '$oid' Order by schedule_date ASC");
        foreach ($re->result_array() as $row) {
            $row['schedule_date'] = gmdate("l, F j, Y", strtotime($row['schedule_date']));
            $this->order_schedule_details[] = $row;
        }
    }

    function loadOrderHandling() {
        $re_1 = $this->db->query("select * from orders_handling where oid = " . $this->oid);
        foreach ($re_1->result_array() as $row_1) {
            $this->arr_orders_handling[] = $row_1;
        }
    }

    function loadpackages() {
        $query = $this->db->query("select id,pkey,shipment_ID from packages where okey = '" . $this->okey . "' ");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row_2) {
                $ship = 0;
                $query_3 = $this->db->query("select id from shipments where skey = '" . $row_2['shipment_ID'] . "' and okey = '" . $this->okey . "' ");
                if ($query_3->num_rows() > 0) {
                    $ship = 1;
                }
                $items = array();
                $query_3 = $this->db->query("select product_id,qty from packages_items where package_id = " . $row_2['id']);
                if ($query_3->num_rows() > 0) {
                    foreach ($query_3->result_array() as $row_3) {
                        $items[] = $row_3;
                    }
                }
                $this->packages[] = array(
                    'ship' => $ship,
                    'items' => $items
                );
            }
        }
    }

    function loadManufacturers() {
        $re_1 = $this->db->query("SELECT order_detais.*,items.uid,items.itm_name,items.itm_model,items.itm_key,items.origin FROM order_detais join items on order_detais.itemid = items.itm_id where order_detais.orderid = '" . $this->oid . "' order by order_detais.id ASC");
        foreach ($re_1->result_array() as $row_1) {
            $check_exist = false;
            for ($m = 0; $m < count($this->arr_manufacturers); $m++) {
                if ($this->arr_manufacturers[$m]['uid'] == $row_1['uid']) {
                    $this->arr_manufacturers[$m]['items'][] = $row_1;
                    $check_exist = true;
                    break;
                }
            }
            if ($check_exist == false) {
                $this->arr_manufacturers[] = array('uid' => $row_1['uid'], 'items' => array($row_1));
            }
        }
    }

    function loadPromotions() {
        $sql_orders_promotions = "select * from orders_promotions where order_key = '" . $this->okey . "' ";
        $re = $this->db->query($sql_orders_promotions);
        foreach ($re->result_array() as $row) {
            $this->arrPromotions[] = $row;
        }
    }

    function loadOrderStatus() {
        $__order_status__ = $this->config->item('__order_status__');
        $min_level = 3;
        $Canceled_status = 0;
        $Refunded_status = 0;
        if (count($this->order_status_level) > 0) {
            foreach ($this->order_status_level as $level) {
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
        if ($this->order_status == 4)
            $Canceled_status = 1;
        if ($Canceled_status == 1)
            $min_level = 4;
        elseif ($Refunded_status == 1)
            $min_level = 5;
        $this->order_status_str = isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : 'null';
    }

    function loadCountriesList() {
        $re = $this->db->query("select * from tblcontries");
        foreach ($re->result_array() as $row) {
            $this->tblcontries[$row['code']] = $row['name'];
        }
    }

    function loadItemDetails() {

        $r = $this->db->query("SELECT * FROM orders WHERE okey = '" . $this->okey . "' ");
        foreach ($r->result_array() as $row) {
            $this->base_price = $row['shipping_fee'];
        }


        for ($m = 0; $m < count($this->arr_manufacturers); $m++) {//0
            $handling_fee_new = $this->base_price;
            //var_dump($handling_fee_new);
            foreach ($this->arr_orders_handling as $oh) {
                if ($oh['uid'] == $this->arr_manufacturers[$m]['uid']) {
                    $handling_fee_new = $oh['handling'];
                    break;
                }
            }
            $shipping_rate = $handling_fee_new;
            $count_ship_free = count($this->arr_manufacturers[$m]['items']);
            foreach ($this->arr_manufacturers[$m]['items'] as $row) {//1
                $this->getItems($row, $count_ship_free, $shipping_rate);
            }
            if ($shipping_rate == $handling_fee_new && $count_ship_free == 0)
                $shipping_rate = 0;

            $this->shipping_fee += $shipping_rate;
        }
        $this->shipping_fee = round($this->shipping_fee, 2);
        $this->tax = round($this->tax_pecen * $this->subtotal / 100, 2);
        $this->totalPrice = $this->subtotal + $this->tax + $this->shipping_fee;

        $__refund_reason__ = $this->config->item('__refund_reason__');
        $refund_amount = 0;
        $return_str = '';
        $re_refund = $this->db->query("select id,refund_update,refund_type from orders_return where okey = '" . $this->okey . "' order by id DESC");
        foreach ($re_refund->result_array() as $row_return) {
            $reason = (isset($__refund_reason__[$row_return['refund_type']])) ? $__refund_reason__[$row_return['refund_type']] : 'None';
            $refund__ = $this->lib->loadAmountRefund($row_return['id'], 2, $this->author->objlogin->uid, $this->okey, $this->oid, $this->tax_pecen, $this->base_price);
            if (is_numeric($refund__)) {
                $refund__ = $refund__ * (-1);
                $refund_amount += $refund__;
                $return_str .= '<tr>';
                $return_str .= '	<td align="right" valign="middle"><b>' . gmdate("F j, Y g:i A", strtotime($row_return['refund_update'])) . ' refunded:</b></td>';
                $return_str .= '	<td align="right" valign="middle" style="padding-left:20px">' . $this->lib->showMoney($refund__) . '</td>';
                $return_str .= '</tr>';
            }
        }
        if ($refund_amount != 0) {
            $return_str .= '<tr><td colspan="2" height="10px"></td></tr>';
            $return_str .= '<tr>';
            $return_str .= '	<td align="right" valign="middle"><b>Balance:</b></td>';
            $return_str .= '	<td align="right" valign="middle" style="padding-left:20px">' . $this->lib->showMoney($this->totalPrice + $refund_amount) . '</td>';
            $return_str .= '</tr>';
        }
        $this->refund_row = $return_str;
    }

    function getItems($row, &$count_ship_free, &$shipping_rate) {
        $itemid = $row["itemid"];
        $odetail = $row['id'];
        $itm_name = $row['itm_name'];
        $itm_model = $row['itm_model'];

        $origin = '';
        if ($row['origin'] != '' && $row['origin'] != null) {
            $origin = $this->lib->ConvertToHtml($row['origin']);
        }

        $arr_file = $this->lib->__loadFileProduct__($itemid);
        $_filename = $arr_file['file'];
        $order_status_level[] = $row['Status'];
        $qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = $odetail and status = 1");

        $this->loadOrderStatusLevel($itemid, $row["quality"]);
        $manufacturer = $this->loadManufacturerName($row['uid']);
        $attributes_str = $this->loadAttributes_str($odetail);

        $check_shipping_free = false;
        $arr_show_promotions = array();
        $free_product_row = array();
        if (count($this->arrPromotions) > 0) {
            foreach ($this->arrPromotions as $promotions) {
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

                    $manufacturer_free = $this->loadManufacturerName($promotions['manufacturer_id']);

                    $itm_model_pro = '';
                    $itm_name_pro = '';
                    $itemid_pro = 0;
                    $re_ = $this->db->query("select itm_id,itm_name,itm_model from items where itm_key = '" . $promotions['itm_key'] . "'");
                    foreach ($re_->result_array() as $row_) {
                        $itm_name_pro = $row_['itm_name'];
                        $itm_model = $row_['itm_model'];
                        $itemid_pro = $row_['itm_id'];
                    }
                    $arr_file = $this->lib->__loadFileProduct__($itemid_pro);

                    $desc_free = '<div style="clear:both"><b>' . $itm_name_pro . '</b><BR><b>Model: </b>' . $itm_model_pro . '</div>';
                    $desc_free .= '<div style="clear:both; padding-top:10px">';
                    $desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
                    $desc_free .= '	<tr>';
                    $desc_free .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                    $desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
                    $desc_free .= '	</tr>';
                    $desc_free .= '</table>';
                    $desc_free .= '</div>';
                    $free_product_row[] = array(
                        'img' => $this->system->URL_server__() . "shopping/data/img/thumb/" . $arr_file['file'],
                        'desc' => $desc_free,
                        'price' => '0.00',
                        'qty_buy' => number_format($promotions['result_qty']),
                        'qty_return' => number_format($result_qty),
                        'total' => '0.00'
                    );
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
                    $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
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
                    $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                    $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: ' . $discount_str . '</td>';
                    $promotions_ .= '	</tr>';
                    $promotions_ .= '</table>';
                    $promotions_ .= '</div>';
                    break;
                case 4:
                    $promotions_ .= '<div style="clear:both; padding-top:10px">';
                    $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                    $promotions_ .= '	<tr>';
                    $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                    $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
                    $promotions_ .= '	</tr>';
                    $promotions_ .= '</table>';
                    $promotions_ .= '</div>';
                    break;
            }
        }
        if ($row['shipping_fee'] <= 0) {
            $row['shipping_fee'] = 0;
            if ($check_shipping_free == true)
                $count_ship_free--;
        }
        $shipping_rate += $row['shipping_fee'];
        $amount = round($row["itemprice"] * $row["quality"], 2);
        $this->subtotal += $amount;

        $this->ItemDetails[] = array(
            'img' => $this->system->URL_server__() . "shopping/data/img/thumb/" . $_filename,
            'desc' => '<div><ul><li>' . $itm_name . '</li><li style="padding-top: 10px">Model:' . $itm_model . '</li><li style="padding-top: 10px">' . $origin . $attributes_str . '</li></ul></div>' . $promotions_,
            'price' => number_format($row["itemprice"], 2),
            'qty_buy' => number_format($row["quality"]),
            'qty_return' => number_format($qty_refund),
            'total' => number_format($amount, 2)
        );
        $this->ItemDetails = array_merge($this->ItemDetails, $free_product_row);
    }

    function loadOrderStatusLevel($itemid, $quality) {
        $__order_status__ = $this->config->item('__order_status__');
        $qty_ship = 0;
        $qty_par = 0;
        if (count($this->packages) > 0) {
            foreach ($this->packages as $package) {
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
        if ($qty_ship == $quality) {
            $status_item = 3;
        } elseif ($qty_par > 0 || $qty_ship > 0) {
            $status_item = 2;
        }
        $this->order_status_level[] = $status_item;
        
        //insert code here 
        $min_level = 3;
        $Canceled_status = 0;
        $Refunded_status = 0;
        if (count($this->order_status_level) > 0) {
            foreach ($this->order_status_level as $level) {
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
        if ($this->order_status == 4)
            $Canceled_status = 1;
        if ($Canceled_status == 1)
            $min_level = 4;
        elseif ($Refunded_status == 1)
            $min_level = 5;
        $this->order_status_str = isset($__order_status__[$min_level]) ? $__order_status__[$min_level] : 'null';
        //end code here 

    }

    function loadManufacturerName($uid) {
        $manufacturer = 'My Store';
        $re_manu = $this->db->query("select legal_business_name from manufacturers where uid = " . $uid);
        if ($row_manu = $re_manu->row_array()) {
            $manufacturer = $row_manu['legal_business_name'];
        }
        return $manufacturer;
    }

    function loadAttributes_str($odetail) {
        $attributes_str = '';
        $re_ = $this->db->query("SELECT * FROM orders_attributes WHERE odetail = '$odetail' order by weight DESC");
        foreach ($re_->result_array() as $row_) {
            $attributes_str .= '<br><b>' . $row_['label'] . ': </b>' . $row_['name'];
            if (is_numeric($row_['price']) && $row_['price'] > 0) {
                $attributes_str .= '&nbsp;&nbsp;(+$' . number_format($row_['price'], 2) . ')';
            }
        }
        return $attributes_str;
    }

    private function load_myWallet($okey = '', $total = 0) {
        $result = array();
        $re = $this->db->query("select * from payments_orders join payments on payments_orders.pkey = payments.pkey where payments.role = " . Sale_Representatives . " and payments_orders.okey in ($okey) ");
        if ($re->num_rows() <= 0)
            return array();
        $row = $re->row_array();
        $roles = $this->author->objlogin->role;
        $total_last = $total;
        $my_wallet = number_format($row['pay'], 2);
        if ($my_wallet > $total)
            $my_wallet = $total;
        $total_last += $this->shipping_fee + $this->tax - $my_wallet;

        /* return array(
          'myVallet' => '$' . number_format($my_wallet, 2),
          'total_last' => '$' . number_format($total_last, 2)
          ); */
        $result[0] = '$' . number_format($my_wallet, 2);
        $result[1] = '$' . number_format($total_last, 2);
        return $result;
    }

}
