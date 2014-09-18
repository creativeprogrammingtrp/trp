<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_manufacturer extends CI_Model {

    var $arr_order_status = array();
    var $uid_manufacturers = '';
    var $total_products = 0;
    var $arr_years = array();
    var $arr_dataCharts = array();
    var $min_Year = 0;
    var $arr_manufacturers__ = array();
    var $packages = array();
    var $arr_orders_handling = array();
    var $shipping_fee = 0;
    var $tax = 0;
    var $subtotal = 0;
    var $order_status_level = array();
    var $okey = '';
    var $order_status = '';
    var $count_item_voucher_pending = 0;
    var $count_item_voucher_redeem = 0;
    var $ship_date = 0;
    var $arr_pending_date = array();
    var $total_pending = 0;
    var $total_setlement = 0;
    var $total_refund = 0;
    var $refund_amount = 0;
    var $pending_amount = 0;
    var $setlement_amount = 0;
    var $shipping_amount = 0;
    var $subtotal_amount = 0;
    var $tax_amount = 0;
	var $check_order_complete = false;
    function __construct() {
        parent::__construct();
    }

    public function loadSalesChart() {
        $arr_years = array();
        $arr_dataCharts = array();
        $min_Year = $year = (int) date('Y');
        $re_3 = $this->db->query("select payments_orders.pay,payments.pay_month,payments.pay_year from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5");
        foreach ($re_3->result_array() as $row_3) {
            $this->total_pending = 0;
            $this->total_setlement = 0;
            $paid = 0;
            $this->total_refund = 0;
            $month_chart = (int) $row_3['pay_month'];
            $year_chart = (int) $row_3['pay_year'];
            if ($min_Year > $year_chart)
                $min_Year = $year_chart;
            $check_ = false;
            for ($i = 0; $i < count($arr_dataCharts); $i++) {
                if ($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart) {
                    $arr_dataCharts[$i]['paid'] += (float) $row_3['pay'];
                    $check_ = true;
                    break;
                }
            }
            if ($check_ == false) {
                $arr_dataCharts[] = array(
                    'year' => $year_chart,
                    'month' => $month_chart,
                    'paid' => (float) $row_3['pay'],
                    'YTD_earnings' => 0,
                    'refund' => 0,
                    'pending' => 0
                );
            }
        }
        $sql = "select users.created,manufacturers.legal_business_id,manufacturers.legal_business_name,manufacturers.uid from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1";
        $re = $this->db->query($sql);
        foreach ($re->result_array() as $row) {
            if ($row['created'] != null && is_numeric($row['created'])) {
                if ($min_Year > (int) date("Y", $row['created']))
                    $min_Year = (int) date("Y", $row['created']);
            }
            $re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,order_date,status from orders ORDER BY orderid DESC");
            foreach ($re_2->result_array() as $row_2) {
                $status_order = $row_2['status'];
                $this->okey = $row_2['okey'];
                $base_price = $row_2["shipping_fee"];
                $this->shipping_fee = 0;
                $this->subtotal = 0;
                $this->tax = 0;
                $this->packages = array();
                $this->order_status_level = array();
                $this->arr_manufacturers__ = array();
                $this->arr_orders_handling = array();
                $this->getPackages();
                $this->getItems($row['uid'], $row_2['orderid']);

                if (count($this->arr_manufacturers__) == 0)
                    continue;
                $this->calculateOrder($row_2['orderid'], $base_price, $status_order);
//                $this->getOrderStatus($status_order);
//                if ($this->order_status != 3)
//                    continue;

                $order_date = strtotime($row_2['order_date']);
                $month_chart = (int) date("m", $order_date);
                $year_chart = (int) date("Y", $order_date);
                if ($min_Year > $year_chart)
                    $min_Year = $year_chart;
                $check_ = false;
                for ($i = 0; $i < count($arr_dataCharts); $i++) {
                    if ($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart) {
                        $arr_dataCharts[$i]['YTD_earnings'] += (float) $this->setlement_amount;
                        $arr_dataCharts[$i]['refund'] += (float) $this->refund_amount;
                        $arr_dataCharts[$i]['pending'] += (float) $this->pending_amount;
                        $check_ = true;
                        break;
                    }
                }
                if ($check_ == false) {
                    $arr_dataCharts[] = array(
                        'year' => $year_chart,
                        'month' => $month_chart,
                        'paid' => 0,
                        'YTD_earnings' => (float) $this->setlement_amount,
                        'refund' => (float) $this->refund_amount,
                        'pending' => (float) $this->pending_amount
                    );
                }
            }
        }
        for ($i = $min_Year; $i < $year + 1; $i++) {
            $arr_years[] = (int) $i;
        }

        return array(
            'objYear' => $arr_years,
            'chart' => $arr_dataCharts
        );
    }

    public function loadAffiliate() {
        $num_per_pager = 20;
        $page = (isset($_POST['page']) && is_numeric($_POST['page']) && $_POST['page'] > 0) ? $_POST['page'] : 1;
        $limit = $num_per_pager * ($page - 1);
        $arr_Affiliate = array();
//      $sql = "select count(manufacturers.mid) from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 and manufacturers.author IN (select uid from users_roles where rid IN (3,4) )";
        $sql = "select count(manufacturers.mid) from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5)";
        $maxlength = $this->database->db_result($sql);
        $sql = "select manufacturers.legal_business_id,manufacturers.legal_business_name,manufacturers.uid from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5) order by manufacturers.uid asc limit $limit," . $num_per_pager;
        $re = $this->db->query($sql);
        foreach ($re->result_array() as $row) {
            $total_price = 0;
            $total_pay = 0;
            $total_refund = 0;
            $total_pending = 0;
            $re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,order_date,status from orders ORDER BY orderid DESC");
            foreach ($re_2->result_array() as $row_2) {
                $status_order = $row_2['status'];
                $this->okey = $row_2['okey'];
                $base_price = $row_2["shipping_fee"];
                $this->shipping_fee = 0;
                //$check_manufacturer = false;
                //$count_shipping_free = 0;
                $this->subtotal = 0;
                $this->tax = 0;

                $this->packages = array();
                $this->order_status_level = array();
                $this->arr_orders_handling = array();
                $this->arr_manufacturers__ = array();

                $this->getPackages();
                $this->getItems($row['uid'], $row_2['orderid']);

                if (count($this->arr_manufacturers__) == 0)
                    continue;

                $this->calculateOrder($row_2['orderid'], $base_price, $status_order, $row['uid']);
//                $this->getOrderStatus($status_order);
//                if ($this->order_status != 3)
//                   continue;


                $total_pending += $this->pending_amount;
                $total_price += $this->setlement_amount;
                $total_refund += $this->refund_amount;
            }
            $row['pending'] = (float) $total_pending;
            $row['earnings'] = (float) $total_price;
            $row['returned'] = (float) $total_refund;
            $row['paid'] = $this->database->db_result("select sum(payments_orders.pay) from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5 and payments.legal_business_id = '" . $row['legal_business_id'] . "'");

            $balance = 0;
            if ($row['earnings'] < $row['paid']) {
                $row['earnings'] = $row['paid'];
            } else {
                $balance += $row['earnings'] - $row['paid'];
            }
            $row['balance'] = $balance;
            $arr_Affiliate[] = $row;
        }
        return array('data' => $arr_Affiliate, 'maxlength' => (int) $maxlength);
    }

    public function loaddata() {

        $total_manufacturers = 0;
        $total_products = 0;
        $this->total_pending = 0;
        $this->total_setlement = 0;
        $paid = 0;
        $this->total_refund = 0;
        $balance = 0;
        $__order_status__ = $this->config->item('__order_status__');

        $export_perm = '';
        if ($this->author->isAccessPerm('manufacturer', 'exportManufacturers')) {
            $export_perm = '<div style="float:right"><input type="submit" class="btn btn-primary" value="Export to Excel" onclick="return Export_to_Excel()"/></div>';
        }
        $this->arr_order_status = array();
        foreach ($__order_status__ as $key => $label) {
            $this->arr_order_status[] = array(
                'total' => 0,
                'id' => $key,
                'label' => $label
            );
        }
        $arr_uid = array();
        //$re = $this->db->query("select items.itm_id,items.uid from items join users join users_roles on items.uid = users.uid and users.uid = users_roles.uid where items.itm_status <> -1 and users.status <> -1 and users_roles.rid = 5");
        $re = $this->db->query("select items.itm_id,items.uid,manufacturers.balance_pending_day from items join users join users_roles join manufacturers on items.uid = users.uid and users.uid = users_roles.uid and manufacturers.uid = users.uid where items.itm_status <> -1 and users.status <> -1 and users_roles.rid = 5");
        foreach ($re->result_array() as $row) {
            $total_products++;
            if (!in_array($row['uid'], $arr_uid)) {
                $arr_uid[] = $row['uid'];
                $arr_temp = array(
                    'uid' => $row['uid'],
                    'pending_date' => $row['balance_pending_day']
                );
                $this->arr_pending_date[] = $arr_temp;
            }
        }

        $uid_manufacturers = implode(",", $arr_uid);
        $total_manufacturers = $this->database->db_result("select count(manufacturers.mid) from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1");
        $re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,order_date,status from orders ORDER BY orderid DESC");
        foreach ($re_2->result_array() as $row_2) {
            $status_order = $row_2['status'];
            $this->okey = $row_2['okey'];
            $this->shipping_fee = 0;
            $this->subtotal = 0;
            $base_price = $row_2["shipping_fee"];
            $this->packages = array();
            $this->order_status_level = array();
            $this->tax = 0;
            $this->arr_manufacturers__ = array();
            $this->arr_orders_handling = array();
            $this->getPackages();

            $this->getItems($uid_manufacturers, $row_2['orderid'], true);

            if (count($this->arr_manufacturers__) == 0)
                continue;

            $this->calculateOrder($row_2['orderid'], $base_price, $status_order);
            $this->getOrderStatus($status_order);

            for ($i = 0; $i < count($this->arr_order_status); $i++) {
                if ($this->arr_order_status[$i]['id'] == $this->order_status) {
                    $this->arr_order_status[$i]['total']++;
                    break;
                }
            }
//            if ($this->order_status != 3)
//                continue;

            $this->total_pending += $this->pending_amount;
            $this->total_setlement += $this->setlement_amount;
            $this->total_refund += $this->refund_amount;
        }

        $paid = $this->database->db_result("select sum(payments_orders.pay) as total from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5");
        $this->total_setlement -= $this->total_refund;

        if ($this->total_setlement < $paid) {
            $this->total_setlement = $paid;
        } else {
            $balance = $this->total_setlement - $paid;
        }
        $balance = round($balance, 2);
        $str_order_statitic = '';
        if (count($this->arr_order_status) > 0) {
            $str_order_header = '<thead><tr>';
            $str_order_content = '<tr>';
            for ($i = 0; $i < count($this->arr_order_status); $i++) {
                $str_order_header .= '<td align="center" class="td-row" valign="middle" style="border-right:1px solid #DEDEDE; font-weight:bold">' . $this->arr_order_status[$i]['label'] . '</td>';
                $str_order_content .= '<td align="center" class="td-row" valign="middle" style="border-right:1px solid #DEDEDE; font-weight:bold; border-bottom:none">' . number_format($this->arr_order_status[$i]['total']) . '</td>';
            }
            $str_order_header .= '</tr></thead>';
            $str_order_content .= '</tr>';
            $str_order_statitic .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #D3D3D3; border-right:none" >';
            $str_order_statitic .= $str_order_header . $str_order_content;
            $str_order_statitic .= '</table>';
        }

        $data = array(
            'show_export_button' => $export_perm,
            'total_manufacturers' => number_format($total_manufacturers),
            'total_products' => number_format($total_products),
            'total_pending' => $this->lib->showMoney($this->total_pending),
            'total_price' => $this->lib->showMoney($this->total_setlement),
            'total_paid' => $this->lib->showMoney($paid),
            'balance' => $this->lib->showMoney($balance),
            'refunded' => $this->lib->showMoney($this->total_refund),
            'orders_statitic' => $str_order_statitic,
            'load_month_current' => "var month_current = parseInt(" . (date('m') * 1) . ", 10);",
            'load_year_current' => "var year_current = parseInt(" . date('Y') . ", 10);"
        );
        return $data;
    }

    public function loadExcelData() {

        $total_manufacturers = 0;
        $total_products = 0;
        $this->total_pending = 0;
        $this->total_setlement = 0;
        $paid = 0;
        $this->total_refund = 0;
        $balance = 0;
        $__order_status__ = $this->config->item('__order_status__');
        $this->arr_order_status = array();
        foreach ($__order_status__ as $key => $label) {
            $this->arr_order_status[] = array(
                'total' => 0,
                'id' => $key,
                'label' => $label
            );
        }
        $arr_uid = array();
        $re = $this->db->query("select items.itm_id,items.uid,manufacturers.balance_pending_day from items join users join users_roles join manufacturers on items.uid = users.uid and users.uid = users_roles.uid and manufacturers.uid = users.uid where items.itm_status <> -1 and users.status <> -1 and users_roles.rid = 5");
        foreach ($re->result_array() as $row) {
            $total_products++;
            if (!in_array($row['uid'], $arr_uid)) {
                $arr_uid[] = $row['uid'];
                $arr_temp = array(
                    'uid' => $row['uid'],
                    'pending_date' => $row['balance_pending_day']
                );
                $this->arr_pending_date[] = $arr_temp;
            }
        }
        $uid_manufacturers = implode(",", $arr_uid);
        $total_manufacturers = $this->database->db_result("select count(manufacturers.mid) from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1");
        $re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,order_date,status from orders ORDER BY orderid DESC");
        foreach ($re_2->result_array() as $row_2) {
            $status_order = $row_2['status'];
            $this->okey = $row_2['okey'];
            $this->shipping_fee = 0;
            $this->subtotal = 0;
            $base_price = $row_2["shipping_fee"];
            $this->packages = array();
            $this->order_status_level = array();
            $this->tax = 0;
            $this->arr_manufacturers__ = array();
            $this->arr_orders_handling = array();
            $this->getPackages();

            $this->getItems($uid_manufacturers, $row_2['orderid'], true);

            if (count($this->arr_manufacturers__) == 0)
                continue;

            $this->calculateOrder($row_2['orderid'], $base_price, $status_order);
            $this->getOrderStatus($status_order);

            for ($i = 0; $i < count($this->arr_order_status); $i++) {
                if ($this->arr_order_status[$i]['id'] == $this->order_status) {
                    $this->arr_order_status[$i]['total']++;
                    break;
                }
            }

//            if ($this->order_status != 3)
//                continue;

            $this->total_pending += $this->pending_amount;
            $this->total_setlement += $this->setlement_amount;
            $this->total_refund += $this->refund_amount;
        }

        $paid = $this->database->db_result("select sum(payments_orders.pay) as total from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5");
        $this->total_setlement -= $this->total_refund;

        if ($this->total_setlement < $paid) {
            $this->total_setlement = $paid;
        } else {
            $balance = $this->total_setlement - $paid;
        }
        $balance = round($balance, 2);
        $data = array(
            'total_manufacturers' => number_format($total_manufacturers),
            'total_products' => number_format($total_products),
            'total_pending' => $this->lib->showMoney($this->total_pending),
            'total_price' => $this->lib->showMoney($this->total_setlement),
            'total_paid' => $this->lib->showMoney($paid),
            'balance' => $this->lib->showMoney($balance),
            'refunded' => $this->lib->showMoney($this->total_refund),
            'orders_statitic' => $this->arr_order_status,
            'load_month_current' => "var month_current = parseInt(" . (date('m') * 1) . ", 10);",
            'load_year_current' => "var year_current = parseInt(" . date('Y') . ", 10);"
        );
        return $data;
    }

    public function loadExcelDetail($akey = '') {

        $__order_status__ = $this->config->item('__order_status__');
        $Member_since = '';
        $last_login = '';
        $legal_business_name = '';
        $legal_business_id = '';
        $Address = '';
        $total_products = 0;
        $total_price = 0;
        $funds_pending = 0;
        $paid = 0;
        $pending_date = 0;
        $settlement = 0;
        $pending = 0;
        $total_refund = 0;
        $balance = '';
        $export_perm = '';
        $payment_perm = '';

        $key = $this->lib->escape($akey);
        if ($this->author->objlogin->role['rid'] == 5) {
            $query = $this->db->query("select manufacturers.legal_business_id from manufacturers join users on manufacturers.uid = users.uid where users.uid = " . $this->author->objlogin->uid);
            $row = $query->row_array();
            if (count($row) > 0) {
                $key = $row['legal_business_id'];
            }
            unset($query);
            unset($row);
        }
        $this->arr_order_status = array();
        foreach ($__order_status__ as $id => $label) {
            $this->arr_order_status[] = array(
                'total' => 0,
                'id' => $id,
                'label' => $label
            );
        }

        $re_1 = $this->db->query("select manufacturers.uid,manufacturers.balance_pending_day,manufacturers.legal_business_name,manufacturers.legal_business_id,manufacturers.address,manufacturers.city,manufacturers.state,manufacturers.zipcode,users.created,users.login from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 and manufacturers.legal_business_id = '$key'");
        $row = $re_1->row_array();
        if (count($row) > 0) {
            $uid = $row['uid'];
            $Member_since = date("F j, Y", $row['created']);
            $last_login = date("F j, Y, g:i a", $row['login']);
            $legal_business_name = $row['legal_business_name'];
            $legal_business_id = $row['legal_business_id'];
            $Address = $row['address'] . ' ' . $row['city'] . ', ' . $row['state'] . ' ' . $row['zipcode'];
            $total_products = $this->database->db_result("select count(itm_id) from items where uid = $uid and itm_status <> -1");
            $pending_date = $row['balance_pending_day'];
            $re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,status from orders ORDER BY orderid DESC");
            foreach ($re_2->result_array() as $row_2) {
                $status_order = $row_2['status'];
                $this->okey = $row_2['okey'];
                $base_price = $row_2["shipping_fee"];
                $this->shipping_fee = 0;
                $this->subtotal = 0;
                $this->tax = 0;
                $this->packages = array();
                $this->order_status_level = array();
                $this->arr_manufacturers__ = array();
                $this->arr_orders_handling = array();

                $this->getPackages();
                $this->getItems($uid, $row_2['orderid']);

                if (count($this->arr_manufacturers__) == 0)
                    continue;

                $this->calculateOrder($row_2['orderid'], $base_price);
                $this->getOrderStatus($status_order);

                for ($i = 0; $i < count($this->arr_order_status); $i++) {
                    if ($this->arr_order_status[$i]['id'] == $this->order_status) {
                        $this->arr_order_status[$i]['total']++;
                        break;
                    }
                }

//                if ($this->order_status != 3)
//                    continue;

                $total_refund += $this->refund_amount;
                $pending += $this->pending_amount;
                $settlement += $this->setlement_amount;
            }
        }
        $paid = $this->database->db_result("select sum(payments_orders.pay) from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5 and payments.legal_business_id = '$key'");
        if ($settlement < $paid) {
            $settlement = $paid;
        } else {
            $balance = $settlement - $paid;
        }
        $settlement = round($settlement, 2);
        $pending = round($pending, 2);
        $balance = round($balance, 2);
        $paid = round($paid, 2);
        $total_refund = round($total_refund, 2);

        $pending_order = '';
        if (count($this->arr_order_status) > 0) {
            for ($i = 0; $i < count($this->arr_order_status); $i++) {
                $pending_order = number_format($this->arr_order_status[0]['total']);
            }
        }

        $data = array(
            'member_since' => $Member_since,
            'total_products' => number_format($total_products),
            'total_paid' => $this->lib->showMoney($paid),
            'total_refund' => $this->lib->showMoney($total_refund),
            'balance' => $this->lib->showMoney($balance),
            'legal_business_name' => $legal_business_name,
            'legal_business_id' => $legal_business_id,
            'Address' => $Address,
            'last_login' => $last_login,
            'key' => $key,
            'pending' => $this->lib->showMoney($pending),
            'settlement' => $this->lib->showMoney($settlement),
            'orders_statitic' => $this->arr_order_status,
            'years' => $this->generateYears(),
            'months' => $this->generateMonths(),
            'export_perm' => $export_perm,
            'order_status' => $this->generateStatus(),
            'pending_order' => $pending_order,
            'redeen_voucher' => ($this->author->objlogin->role['rid'] != MANUFACTURER) ? $this->count_item_voucher_redeem : '',
            'pending_voucher' => ($this->author->objlogin->role['rid'] != MANUFACTURER) ? $this->count_item_voucher_pending : '',
            "if('permiss' == 'ok');" => $payment_perm,
            'load_month_current' => "var month_current = parseInt(" . (date('m') * 1) . ", 10);",
            'load_year_current' => "var year_current = parseInt(" . date('Y') . ", 10);"
        );
        return $data;
    }

    function loadDetailData($akey = '') {
        $__order_status__ = $this->config->item('__order_status__');
        $Member_since = '';
        $last_login = '';
        $legal_business_name = '';
        $legal_business_id = '';
        $Address = '';
        $total_products = 0;
        $total_price = 0;
        $funds_pending = 0;
        $paid = 0;
        $pending_date = 0;
        $settlement = 0;
        $pending = 0;
        $total_refund = 0;
        $balance = '';
        $export_perm = '';
        $payment_perm = '';

        $key = $this->lib->escape($akey);
        if ($this->author->objlogin->role['rid'] == 5) {
            $query = $this->db->query("select manufacturers.legal_business_id from manufacturers join users on manufacturers.uid = users.uid where users.uid = " . $this->author->objlogin->uid);
            $row = $query->row_array();
            if (count($row) > 0) {
                $key = $row['legal_business_id'];
            }
            unset($query);
            unset($row);
        }

        if ($this->author->isAccessPerm('Manufacturer', 'payment')) {
            $payment_perm = "if(arr_data_chart[i].balance > 0){
								pay_btn = '<a id=\"pay_'+i+'\" href=\"'+url_server__+'report/manufacturer/payment/'+manu_key+'/'+arr_data_chart[i].year+'/'+arr_data_chart[i].month+'\" class=\"button cboxElement\" style=\"font-size:10px; padding:3px 6px\">Pay</a>';	
							}";
        }

        if ($this->author->isAccessPerm('Manufacturer', 'exportDetail')) {
            $export_perm = '<div style="float:right"><input type="submit" class="btn btn-primary" value="Export to Excel" onclick="return Export_to_Excel()"/></div>';
        }

        $this->arr_order_status = array();
        foreach ($__order_status__ as $id => $label) {
            $this->arr_order_status[] = array(
                'total' => 0,
                'id' => $id,
                'label' => $label
            );
        }

        $re_1 = $this->db->query("select manufacturers.uid,manufacturers.balance_pending_day,manufacturers.legal_business_name,manufacturers.legal_business_id,manufacturers.address,manufacturers.city,manufacturers.state,manufacturers.zipcode,users.created,users.login from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 and manufacturers.legal_business_id = '$key'");
        $row = $re_1->row_array();
        if (count($row) > 0) {
            $uid = $row['uid'];
            $Member_since = date("F j, Y", $row['created']);
            $last_login = date("F j, Y, g:i a", $row['login']);
            $legal_business_name = $row['legal_business_name'];
            $legal_business_id = $row['legal_business_id'];
            $Address = $row['address'] . ' ' . $row['city'] . ', ' . $row['state'] . ' ' . $row['zipcode'];
            $total_products = $this->database->db_result("select count(itm_id) from items where uid = $uid and itm_status <> -1");
            $pending_date = $row['balance_pending_day'];
            $re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,status from orders ORDER BY orderid DESC");
            foreach ($re_2->result_array() as $row_2) {
                $status_order = $row_2['status'];
                $this->okey = $row_2['okey'];
                $base_price = $row_2["shipping_fee"];
                $this->shipping_fee = 0;
                $this->subtotal = 0;
                $this->tax = 0;
                $this->packages = array();
                $this->order_status_level = array();
                $this->arr_manufacturers__ = array();
                $this->arr_orders_handling = array();

                $this->getPackages();
                $this->getItems($uid, $row_2['orderid']);

                if (count($this->arr_manufacturers__) == 0)
                    continue;

                $this->calculateOrder($row_2['orderid'], $base_price);
                $this->getOrderStatus($status_order);

                for ($i = 0; $i < count($this->arr_order_status); $i++) {
                    if ($this->arr_order_status[$i]['id'] == $this->order_status) {
                        $this->arr_order_status[$i]['total']++;
                        break;
                    }
                }

                if ($this->order_status != 3) continue;
                    
                $total_refund += $this->refund_amount;
                $pending += $this->pending_amount;
                $settlement += $this->setlement_amount;
            }
        }
        $paid = $this->database->db_result("select sum(payments_orders.pay) from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5 and payments.legal_business_id = '$key'");
        if ($settlement < $paid) {
            $settlement = $paid;
        } else {
            $balance = $settlement - $paid;
        }
        $settlement = round($settlement, 2);
        $pending = round($pending, 2);
        $balance = round($balance, 2);
        $paid = round($paid, 2);
        $total_refund = round($total_refund, 2);

        $str_order_statitic = '';
        $pending_order = '';
        if (count($this->arr_order_status) > 0) {
            $str_order_header = '<thead><tr>';
            $str_order_content = '<tr>';
            for ($i = 0; $i < count($this->arr_order_status); $i++) {
                $str_order_header .= '<td align="center" class="td-row" valign="middle" style="border-right:1px solid #DEDEDE; font-weight:bold">' . $this->arr_order_status[$i]['label'] . '</td>';
                $str_order_content .= '<td align="center" class="td-row" valign="middle" style="border-right:1px solid #DEDEDE; font-weight:bold; border-bottom:none">' . number_format($this->arr_order_status[$i]['total']) . '</td>';
                $pending_order = number_format($this->arr_order_status[0]['total']);
            }
            $str_order_header .= '</tr></thead>';
            $str_order_content .= '</tr>';
            $str_order_statitic .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #D3D3D3; border-right:none" >';
            $str_order_statitic .= $str_order_header . $str_order_content;
            $str_order_statitic .= '</table>';
        }

        $data = array(
            'member_since' => $Member_since,
            'total_products' => number_format($total_products),
            'total_paid' => $this->lib->showMoney($paid),
            'total_refund' => $this->lib->showMoney($total_refund),
            'balance' => $this->lib->showMoney($balance),
            'legal_business_name' => $legal_business_name,
            'legal_business_id' => $legal_business_id,
            'Address' => $Address,
            'key' => $key,
            'total_refund' => $this->lib->showMoney($total_refund) ,
            'pending' => $this->lib->showMoney($pending),
            'settlement' => $this->lib->showMoney($settlement),
            'orders_statitic' => $str_order_statitic,
            'years' => $this->generateYears(),
            'months' => $this->generateMonths(),
            'export_perm' => $export_perm,
            'order_status' => $this->generateStatus(),
            'pending_order' => $pending_order,
            'redeen_voucher' => ($this->author->objlogin->role['rid'] != MANUFACTURER) ? '<div class="contain"><div class="title">Redeemed Vouchers: </div><div class="result">' . $this->count_item_voucher_redeem . '</div></div>' : '',
            'pending_voucher' => ($this->author->objlogin->role['rid'] != MANUFACTURER) ? '<div class="contain"><div class="title">Pending Vouchers: </div><div class="result">' . $this->count_item_voucher_pending . '</div></div>' : '',
            "if('permiss' == 'ok');" => $payment_perm,
            'load_month_current' => "var month_current = parseInt(" . (date('m') * 1) . ", 10);",
            'load_year_current' => "var year_current = parseInt(" . date('Y') . ", 10);"
        );
        return $data;
    }

    private function check_pending_amount($pending_date) {
        $today = date('Y-m-d', $this->lib->getTimeGMT());
        $ship_date = date('Y-m-d', $this->ship_date);
        if ($this->ship_date > 0) {
            $interval = floor(($this->lib->getTimeGMT() - $this->ship_date) / 86400);
            if ($interval >= $pending_date)
                return false;
            else
                return true;
        }else
            return true;
    }

    function loadDetailSalesChart() {
        $pending_date = 0;
        $settlement = 0;
        $pending = 0;

        $key = (isset($_POST['akey']) && $_POST['akey'] != '') ? $_POST['akey'] : '';
        $arr_years = array();
        $arr_dataCharts = array();
        $min_Year = $year = (int) date('Y');
        $re_3 = $this->db->query("select payments_orders.pay as pay, payments.pay_month as month_pay, payments.pay_year as year_pay from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5 and payments.legal_business_id = '$key'");
        foreach ($re_3->result_array() as $row_3) {
            $month_chart = (int) $row_3['month_pay'];
            $year_chart = (int) $row_3['year_pay'];
            $check_ = false;
            for ($i = 0; $i < count($arr_dataCharts); $i++) {
                if ($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart) {
                    $arr_dataCharts[$i]['paid'] += (float) $row_3['pay'];
                    $check_ = true;
                    break;
                }
            }
            if ($check_ == false) {
                $arr_dataCharts[] = array(
                    'year' => $year_chart,
                    'month' => $month_chart,
                    'paid' => (float) $row_3['pay'],
                    'YTD_earnings' => 0,
                    'refund' => 0,
                    'pending_amount' => 0
                );
            }
        }
        $sql = "select users.created,manufacturers.uid,manufacturers.legal_business_id,manufacturers.legal_business_name,manufacturers.balance_pending_day from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 and manufacturers.legal_business_id = '$key'";
        $re = $this->db->query($sql);
        $row = $re->row_array();
        if (count($row) > 0) {
            $uid = $row['uid'];
            if ($row['created'] != null && is_numeric($row['created'])) {
                if ($min_Year > (int) date("Y", $row['created']))
                    $min_Year = (int) date("Y", $row['created']);
            }
            $pending_date = $row['balance_pending_day'];
            $re_2 = $this->db->query("select orderid,okey,order_tax,shipping_fee,order_date,status from orders ORDER BY orderid DESC");
            foreach ($re_2->result_array() as $row_2) {
                $status_order = $row_2['status'];
                $this->okey = $row_2['okey'];
                $base_price = $row_2["shipping_fee"];

                $this->shipping_fee = 0;
                $this->subtotal = 0;
                $this->packages = array();
                $this->order_status_level = array();
                $this->arr_manufacturers__ = array();
                $this->tax = 0;

                $this->getPackages();
                $this->getItems($uid, $row_2['orderid']);

                if (count($this->arr_manufacturers__) == 0)
                    continue;

                $this->arr_orders_handling = array();
                $this->calculateOrder($row_2['orderid'], $base_price);
                $this->getOrderStatus($status_order);
                if ($this->order_status != 3)  continue;
                   
                $total_refund = 0;
                $order_date = strtotime($row_2['order_date']);
                $month_chart = (int) date("m", $order_date);
                $year_chart = (int) date("Y", $order_date);
                $check_ = false;
                for ($i = 0; $i < count($arr_dataCharts); $i++) {
                    if ($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart) {
                        $arr_dataCharts[$i]['YTD_earnings'] += (float) $this->setlement_amount;
                        $arr_dataCharts[$i]['refund'] += (float) $this->refund_amount;
                        $arr_dataCharts[$i]['pending_amount'] += (float) $this->pending_amount;
                        $check_ = true;
                        break;
                    }
                }
                if ($check_ == false) {
                    $arr_dataCharts[] = array(
                        'year' => $year_chart,
                        'month' => $month_chart,
                        'paid' => 0,
                        'YTD_earnings' => (float) $this->setlement_amount,
                        'refund' => (float) $this->refund_amount,
                        'pending_amount' => (float) $this->pending_amount
                    );
                }
            }
        }
        for ($i = $min_Year; $i < $year + 1; $i++) {
            $arr_years[] = (int) $i;
        }
        return array(
            'objYear' => $arr_years,
            'chart' => $arr_dataCharts
        );
    }

    function loadDetailOrders() {
        $__order_status__ = $this->config->item('__order_status__');
        $arr_orders = array();
        $maxlength = 0;
        $page = (isset($_POST['page']) && is_numeric($_POST['page']) && $_POST['page'] > 0) ? $_POST['page'] : 1;
        $key = (isset($_POST['akey']) && $_POST['akey'] != '') ? $_POST['akey'] : '';

        $pay = 'no';
        if ($this->author->isAccessPerm('Manufacturer', 'payment'))
            $pay = 'yes';

        $status_sql = '';
        if (isset($_POST['status_order']) && $_POST['status_order'] != '') {
            $status_sql = $_POST['status_order'];
        }
        $month_sql = '';
        if (isset($_POST['month']) && is_numeric($_POST['month'])) {
            $month_sql = " and MONTH(orders.order_date) = '" . $_POST['month'] . "'";
        }
        $year_sql = '';
        if (isset($_POST['year']) && is_numeric($_POST['year'])) {
            $year_sql = " and YEAR(orders.order_date) = '" . $_POST['year'] . "'";
        }
        $status_paid_sql = '';
        if (isset($_POST['status_paid']) && $_POST['status_paid'] != '') {
            $status_paid_sql = $_POST['status_paid'];
        }

        $sql = "select manufacturers.uid,manufacturers.legal_business_id from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 and manufacturers.legal_business_id = '$key'";
        $re = $this->db->query($sql);
        $row = $re->row_array();
        if (count($row) > 0) {
            $max_sql = "select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name ";
            $max_sql .= "from orders join order_detais join items on orders.orderid = order_detais.orderid and order_detais.itemid = items.itm_id  ";
            $max_sql .="where items.uid = " . $row['uid'] . " $month_sql $year_sql ORDER BY orders.orderid DESC ";

            $re_2 = $this->db->query($max_sql);
            foreach ($re_2->result_array() as $row_2) {
                $status_order = $row_2['status'];
                $base_price = $row_2["shipping_fee"];

                $this->okey = $row_2['okey'];
                $this->shipping_fee = 0;
                $this->subtotal = 0;
                $this->tax = 0;

                $this->order_status_level = array();
                $this->packages = array();
                $this->arr_manufacturers__ = array();

                $this->getPackages();
                $this->getItems($row['uid'], $row_2['orderid']);
                if (count($this->arr_manufacturers__) == 0)
                    continue;

                $this->arr_orders_handling = array();
                $this->calculateOrder($row_2['orderid'], $base_price);
				
				if($this->check_order_complete == false) continue;
				
                $this->getOrderStatus($status_order);
//               if ($this->order_status != 3)
//                    continue;
				

                $row_2['paid'] = (float) $this->database->db_result("select sum(payments_orders.pay) from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5 and payments.legal_business_id = '" . $row['legal_business_id'] . "' and payments_orders.okey = '" . $this->okey . "'");

//                $amount_ = round($this->subtotal + $this->tax + $this->shipping_fee, 2);
//                $total_refund = 0;
//                if ($status_order == 4) {//Cancel
//                    $total_refund = $amount_;
//                    $amount_ = 0;
//                }

                if ($status_paid_sql != '') {
                    $balance = $this->setlement_amount - $row_2['paid'];
                    if ($status_paid_sql == 0 && $balance <= 0)
                        continue;
                    elseif ($status_paid_sql == 1 && $row_2['paid'] <= 0)
                        continue;
                }
                $row_2['pending'] = (float) $this->pending_amount;
                $row_2['order_total'] = (float) $this->setlement_amount;
                $row_2['subtotal'] = (float) $this->subtotal_amount;
                $row_2['tax'] = (float) $this->tax_amount;
                $row_2['shipping_fee'] = (float) $this->shipping_amount;
                $row_2['refund'] = (float) $this->refund_amount;
                $row_2['status_format'] = isset($__order_status__[3]) ? $__order_status__[3] : 'null';
                $row_2['status'] = 3;
                $row_2['order_date_format'] = date("m/d/Y", strtotime($row_2['order_date']));
                $row_2['pay'] = $pay;
                $row_2['month'] = (int) date("m", strtotime($row_2['order_date']));
                $row_2['year'] = (int) date("Y", strtotime($row_2['order_date']));
                $arr_orders[] = $row_2;
            }
        }
        return array('maxlength' => (int) $maxlength, 'data' => $arr_orders, 'page' => (int) $page);
    }

    function getOrderStatus($status_order) {
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
        if ($status_order == 4) {
            $Canceled_status = 1;
            $min_level = 4;
        }
        $this->order_status = $min_level;
    }

    function calculateOrder($oid, $base_price, $status_order = -1) {
        $re_1 = $this->db->query("select * from orders_handling where oid = " . $oid);
        foreach ($re_1->result_array() as $row_1) {
            $this->arr_orders_handling[] = $row_1;
        }
        $this->shipping_fee = 0;
        $this->pending_amount = 0;
        $this->setlement_amount = 0;
        $this->refund_amount = 0;
        $this->subtotal_amount = 0;
        $this->tax_amount = 0;
        $this->shipping_amount = 0;
		$this->check_order_complete = false;
		
		$arr_manu_min_status_level = array();

        for ($m = 0; $m < count($this->arr_manufacturers__); $m++) {//0
            $tax = 0;
            $subtotal = 0;
            $shipping_fee = 0;
            $pending_amount = 0;
            $setlement_amount = 0;
            $refund_amount = 0;
            $manu_status_level = array();
			$check_product_item = false;

            $handling_fee_new = $base_price;
            foreach ($this->arr_orders_handling as $oh) {
                if ($oh['uid'] == $this->arr_manufacturers__[$m]['uid']) {
                    $handling_fee_new = $oh['handling'];
                    break;
                }
            }
            $shipping_rate = $handling_fee_new;
            foreach ($this->arr_manufacturers__[$m]['items'] as $row_1) {//1
                $itemid = $row_1['itemid'];
                $product_type = $row_1['product_type'];
                $qty_ship = 0;
                $qty_par = 0;
				$shiprate = 0;
				
                if ($product_type == 0) {
					if($check_product_item == false) $check_product_item = true;
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
                    if ($qty_ship == $row_1["quality"]) {
                        $status_item = 3;
                    } elseif ($qty_par > 0 || $qty_ship > 0) {
                        $status_item = 2;
                    }
                    $manu_status_level[] = $status_item;
                    $this->order_status_level[] = $status_item;
					$shiprate = $row_1['last_shipping'] * $row_1["quality"];
                    $shipping_rate += round($shiprate, 2);
                } else {
                    $manu_status_level[] = 3;
                    $this->order_status_level[] = 3;
                    $shipping_rate = 0;
					$shiprate = 0;
                }
				$sub_total = round($row_1["current_cost"] * $row_1["quality"], 2);
                $subtotal += $sub_total;
				
				$tax_temp = $sub_total * $row_1["tax_persend"] / 100;
                $tax += round($tax_temp, 2);
				
				$amount = round($sub_total + $tax_temp + $shiprate, 2);
				
                if ($product_type == 2 || $product_type == 1) {
                    if ($status_order == 4) {
                        $refund_amount += $amount;
                    } else {
                        $setlement_amount += $amount;
                    }
                } else {
                    if (count($this->arr_pending_date) > 0) {
                        $pending_date = 15;
                        for ($j = 0; $j < count($this->arr_pending_date); $j++) {
                            if ($this->arr_pending_date[$j]['uid'] == $row_1['uid']) {
                                $pending_date = $this->arr_pending_date[$j]['pending_date'];
                                break;
                            }
                        }
                        if ($this->check_pending_amount($pending_date)) {
                           
                            if ($status_order == 4) {
                                $refund_amount += $amount;
                            } else {
                                $pending_amount += $amount;
                            }
                        } else {
                            if ($status_order == 4) {
                                $refund_amount += $amount;
                            } else {
                                $setlement_amount += $amount;
                            }
                        }
                    } else {
                        $pending_date = 15;
                        $pending_date = $this->database->db_result("select manufacturers.balance_pending_day from manufacturers where uid = " . $row_1['uid']);

                        if ($this->check_pending_amount($pending_date)) {
                            if ($status_order == 4) {
                                $refund_amount += $amount;
                            } else {
                                $pending_amount += $amount;
                            }
                        } else {
                            if ($status_order == 4) {
                                $refund_amount += $amount;
                            } else {
                                $setlement_amount += $amount;
                            }
                        }
                    }
                }
            }//1
			$this->tax += $tax;
			$this->subtotal += $subtotal;
			
            if (count($manu_status_level) > 0) {
                $manu_min_status_level = 3;
                for ($h = 0; $h < count($manu_status_level); $h++) {
                    if ($manu_status_level[$h] < $manu_min_status_level) {
                        $manu_min_status_level = $manu_status_level[$h];
                    }
                }
				$arr_manu_min_status_level[] = $manu_min_status_level;
                if ($manu_min_status_level != 3)  continue;
                
				if($check_product_item){
					if($pending_amount > 0) $pending_amount += $handling_fee_new;
					elseif($setlement_amount > 0) $setlement_amount += $handling_fee_new;
				}
                $this->refund_amount += $refund_amount;
                $this->pending_amount += $pending_amount;
                $this->setlement_amount += $setlement_amount;
                $this->shipping_amount += $shipping_rate;
                $this->subtotal_amount += $subtotal;
                $this->tax_amount += $tax;
            }
            $this->shipping_fee += round($shipping_rate, 2);
        }//0
		$check_manu_pass = false;
		if(count($arr_manu_min_status_level) > 0){
			for($l = 0; $l < count($arr_manu_min_status_level); $l++){
				if($arr_manu_min_status_level[$l] == 3){
					$check_manu_pass = true;
					break;	
				}
			}
		}
		if($check_manu_pass) $this->check_order_complete = true ;
		
        $this->shipping_fee = round($this->shipping_fee, 2);
    }

    function getPackages() {
        $arr_shipdate = array();
        $re_3 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '" . $this->okey . "'");
        foreach ($re_3->result_array() as $row_3) {
            $ship = 0;
            $re_4 = $this->db->query("select id, ship_date from shipments where skey = '" . $row_3['shipment_ID'] . "' and okey = '" . $this->okey . "'");
            foreach ($re_4->result_array() as $row_4) {
                $arr_shipdate[count($arr_shipdate)] = strtotime($row_4['ship_date']);
                if (count($row_4) > 0) {
                    $ship = 1;
                }
            }
            $items = array();
            $re_4 = $this->db->query("select product_id,qty from packages_items where package_id = " . $row_3['id']);
            foreach ($re_4->result_array() as $row_4) {
                $items[] = $row_4;
            }

            $this->packages[] = array(
                'ship' => $ship,
                'items' => $items
            );
        }
        $ship_date = 0;
        for ($i = 0; $i < count($arr_shipdate); $i++) {
            $date_ship = $arr_shipdate[$i];
            if ($ship_date < $date_ship) {
                $ship_date = $date_ship;
            }
        }
        $this->ship_date = $ship_date;
    }

    function getItems($muid, $oid, $str_uid = false) {
        if ($str_uid) {
            $sql_arr_manu = " items.uid in (" . $muid . ") ";
        } else {
            $sql_arr_manu = ' items.uid = ' . $muid;
        }
        $re_1 = $this->db->query("select order_detais.orderid, order_detais.itemid,order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,order_detais.last_shipping,order_detais.tax_persend,items.itm_id, items.itm_key,items.uid,items.product_type from order_detais join items  on order_detais.itemid = items.itm_id where $sql_arr_manu and order_detais.orderid = " . $oid);
        foreach ($re_1->result_array() as $row_1) {
            if ($row_1['product_type'] == 2) {
                $status_voucher = $this->db->query("select voucher.status from  voucher  where  voucher.order_id ='$oid' and item_id = '" . $row_1['itm_id'] . "'");
                $vouche_redeem_quality = 0;
                foreach ($status_voucher->result_array() as $status) {
                    if ($status['status'] == 1) {
                        $vouche_redeem_quality++;
                        $this->count_item_voucher_redeem++;
                    } else {
                        $this->count_item_voucher_pending++;
                        continue;
                    }
                }
                $row_1['quality'] = $vouche_redeem_quality;
            }
            if ($row_1['quality'] == 0)
                continue;
            $check_exist = false;
            for ($m = 0; $m < count($this->arr_manufacturers__); $m++) {
                if ($this->arr_manufacturers__[$m]['uid'] == $row_1['uid']) {
                    $this->arr_manufacturers__[$m]['items'][] = $row_1;
                    $check_exist = true;
                    break;
                }
            }
            if ($check_exist == false) {
                $this->arr_manufacturers__[] = array('uid' => $row_1['uid'], 'items' => array($row_1));
            }
        }
    }

    function payment_loadData($key = '', $year = '', $month = '', $okey = '') {

        $arr_orders = array();
        $key = $this->lib->escape($key);
        $pay_month = $this->lib->escape($month);
        $pay_year = $this->lib->escape($year);
        $okey = $this->lib->escape($okey);
        $sql_order_key = '';
        if (isset($okey) && !empty($okey)) {
            $sql_order_key = ' and orders.okey = ' . $okey;
        }

        $sql_paymonth = ' and MONTH(orders.order_date) = ' . $pay_month;
        $sql_payyear = ' and YEAR(orders.order_date) = ' . $pay_year;
        $sql_pay_month = ' and payments.pay_month = ' . $pay_month;
        $sql_pay_year = ' and payments.pay_year = ' . $pay_year;

        $legal_business_name = '';
        $sql = "select manufacturers.uid,manufacturers.legal_business_id,manufacturers.legal_business_name from manufacturers join users on manufacturers.uid = users.uid where users.status <> -1 and manufacturers.legal_business_id = '$key'";
        $re = $this->db->query($sql);
        $row = $re->row_array();
        if (count($row) > 0) {
            $legal_business_name = $row['legal_business_name'];
            $max_sql = "select distinct orders.orderid,orders.okey,orders.order_date,orders.order_tax,orders.shipping_fee,orders.status,orders.billing_name ";
            $max_sql .= " from orders join order_detais join items on orders.orderid = order_detais.orderid and order_detais.itemid = items.itm_id ";
            $max_sql .= " where items.uid = " . $row['uid'] . " $sql_order_key $sql_paymonth $sql_payyear ORDER BY orders.orderid DESC"; //$sql_order_key
            $re_2 = $this->db->query($max_sql);

            foreach ($re_2->result_array() as $row_2) {
                $status_order = $row_2['status'];

                $this->okey = $row_2['okey'];
                $base_price = $row_2["shipping_fee"];

                $this->shipping_fee = 0;
                $this->subtotal = 0;
                $this->packages = array();
                $this->order_status_level = array();
                $this->tax = 0;
                $this->arr_manufacturers__ = array();

                $this->getPackages();

                $this->getItems($row['uid'], $row_2['orderid']);
                $this->calculateOrder($row_2['orderid'], $base_price);
                
                
//                $this->getOrderStatus($status_order);
//                if ($this->order_status != 3)
//                    continue;

                $paid = $this->database->db_result("select sum(payments_orders.pay) from payments join payments_orders on payments_orders.pkey = payments.pkey where payments.role = 5 and payments.legal_business_id = '" . $row['legal_business_id'] . "' and payments_orders.okey = '" . $this->okey . "' $sql_pay_month $sql_pay_year");
                $total_refund = 0;
//                $amount_ = round($this->subtotal + $this->tax + $this->shipping_fee, 2);
//                if ($status_order == 4) {
//                    $total_refund = $amount_;
//                }

                if ($this->setlement_amount <= $paid) {
                    continue;
                }

                $arr_orders[] = array(
                    'okey' => $this->okey,
                    'subtotal' => (float)$this->subtotal_amount,
                    'tax' => (float) $this->tax_amount,
                    'shipping' => (float) $this->shipping_amount,
                    'refund' => (float) $this->refund_amount,
                    'paid' => (float) $paid
                    
                );
            }
        }
        $data = array(
            'date' => date("F j, Y, g:i a"),
            'legal_business_id' => $key,
            'legal_business_name' => $legal_business_name,
            'key' => $key,
            'okey' => $this->okey,
            'pay_month' => $pay_month,
            'pay_year' => $pay_year,
            'arr_orders' => 'orders=' . json_encode($arr_orders) . ';'
        );
        return $data;
    }

    function payment_saveObj() {

        $error = '';
        $pay_key = '';
        if (isset($_POST['key'])) {
            $key = $_POST['key'];
            $okey = isset($_POST['okey']) ? $_POST['okey'] : '';
            $pay_month = (isset($_POST['pay_month']) && is_numeric($_POST['pay_month'])) ? $_POST['pay_month'] : 0;
            $pay_year = (isset($_POST['pay_year']) && is_numeric($_POST['pay_year'])) ? $_POST['pay_year'] : 0;
            if (isset($_POST['orders_payment']) && is_array($_POST['orders_payment']) && count($_POST['orders_payment']) > 0) {
                $pay_key = $this->lib->GeneralRandomKey(20);
                $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
                foreach ($re_key->result_array() as $row_key) {
                    $pay_key = $this->lib->GeneralRandomKey(20);
                    $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
                }
                $datas = array(
                    'pkey' => $pay_key,
                    'check_number' => $this->lib->escape($_POST['check_number']),
                    'role' => 5,
                    'legal_business_id' => $key,
                    'legal_business_name' => $this->lib->escape($_POST['legal_business_name']),
                    'date_pay' => date('Y-m-d H:i:s', $this->lib->getTimeGMT()), //time(),
                    'pay_month' => $pay_month,
                    'pay_year' => $pay_year
                );
                $this->db->insert("payments", $datas);
                $id = $this->db->insert_id();
                if (!is_numeric($id))
                    $error = 'Can not save to database.';
                else {
                    foreach ($_POST['orders_payment'] as $order) {
                        $arr_ = explode("|", $order);
                        $payments_orders = array(
                            'pkey' => $pay_key,
                            'okey' => $arr_[0],
                            'pay' => $arr_[1]
                        );
                        $this->db->insert("payments_orders", $payments_orders);
                    }
                }
            }
        }
        return array('error' => $error, /* 'paid'=>$pay, */ 'pay_key' => $pay_key);
    }

    function generateYears() {

        $current_year = date('Y');
        $years = '';
        for ($i = 2011; $i <= date('Y'); $i++) {
            $select = '';
            if ($current_year == $i)
                $select = 'selected="selected"';
            $years .= '<option value="' . $i . '" ' . $select . '>' . $i . '</option>';
        }
        if ($years != '')
            $years = '<select id="years" style="color:#AEAEAE"><option value="" style="color:#AEAEAE">Year</option>' . $years . '</select>';
        return $years;
    }

    function generateMonths() {

        $__month__ = $this->config->item('__month__');
        $current_month = date('m');
        $months = '';
        foreach ($__month__ as $num => $label) {
            $select = '';
            if ($current_month == $num)
                $select = 'selected="selected"';
            $months .= '<option value="' . $num . '" ' . $select . '>' . $label . '</option>';
        }
        if ($months != '')
            $months = '<select id="month" style="color:#AEAEAE"><option value="" style="color:#AEAEAE">Month</option>' . $months . '</select>';

        return $months;
    }

    function generateStatus() {

        $__order_status__ = $this->config->item('__order_status__');
        $months = '';
        foreach ($__order_status__ as $num => $label) {
            $months .= '<option value="' . $num . '">' . $label . '</option>';
        }
        return $months;
    }

}