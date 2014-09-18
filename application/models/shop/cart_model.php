<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cart_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('general');
    }

    function loadData() {
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            return $this->lib->loadshoppingcartAuto();
        }
        return $this->lib->loadshoppingcart();
    }

    function check_items_voucher($itm_key) {
        $product_type = $this->db->query("select product_type from items where itm_key = '" . $itm_key . "'");
        foreach ($product_type->result_array() as $row) {

            if ($row['product_type'] == 0) {
                return false;
                break;
            } else {
                return true;
            }
        }
    }

    function loadObjectOrders() {
        $schedule = new schedule();
        if (isset($_SESSION['__manufacturers__']))
            unset($_SESSION['__manufacturers__']);


        $_SESSION['__manufacturers__'] = $schedule->arr_schedule;

        $arr_merge = array_merge(array('shippings' => $schedule->items__auto, 'taxs' => $schedule->schedule_taxs, 'commissions' => $this->loadEarningsRep($this->author->objlogin->uid)), $schedule->shopping_carts);

        return json_encode($arr_merge);
    }

    function loadCart($itm_key, $arrKeys, $Qty) {
        $re = $this->db->query("SELECT itm_id,itm_key,itm_name,itm_model,current_cost FROM items WHERE itm_key = '$itm_key' and itm_status = 1 ");
        if ($re->num_rows() > 0) {//1
            $row = $re->row_array();
            $itm_id = $row['itm_id'];
            $_filename = $this->database->db_result("select filename from items_files where tid = " . $itm_id . " order by weight DESC ");
            $current_cost = $row['current_cost'];
            if (!is_numeric($current_cost))
                $current_cost = 0;
            $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
            if ($re_price->num_rows() > 0) {
                $row_price = $re_price->row_array();
                $markup_percentage = $row_price['markup_percentage'];
                if (!is_numeric($markup_percentage))
                    $markup_percentage = 0;
                $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
            }
            $current_cost = round($current_cost, 2);

            $attributes = array();
            for ($i = 1; $i < count($arrKeys); $i++) {//3
                $arr_attribute = array(
                    'label' => '',
                    'name' => '',
                    'price' => 0
                );
                $arr_textfi = explode(__keyat__, $arrKeys[$i]);
                if (count($arr_textfi) == 2) {
                    if ($arr_textfi[1] == '')
                        continue;
                    $re_label = $this->db->query("select label from items_attributes where akey = '" . $arr_textfi[0] . "' and pkey = '$itm_key'");
                    if ($re_label->num_rows() > 0) {
                        $row_label = $re_label->row_array();
                        $arr_attribute['label'] = $row_label['label'];
                    } else {
                        $arr_attribute['label'] = $this->database->db_result("select label from attributes where status <> -1 and akey = '" . $arr_textfi[0] . "'");
                    }
                    $arr_attribute['name'] = $arr_textfi[1];
                } elseif (count($arr_textfi) == 1) {
                    $arr_attribute['label'] = $this->database->db_result("select items_attributes.label from items_attributes join attrioptions on items_attributes.akey = attrioptions.akey where items_attributes.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                    $re_pri = $this->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                    if ($re_pri->num_rows() > 0) {
                        $row_pri = $re_pri->row_array();
                        $arr_attribute['name'] = $row_pri['name'];
                        $arr_attribute['price'] = $row_pri['price'];
                    }
                }
                $attributes[] = $arr_attribute;
            }//3
            if (count($attributes) > 0) {
                foreach ($attributes as $attri) {
                    $at_price = round($attri['price'], 2);
                    $current_cost += $at_price;
                }
            }
            $total_current_cost = round($current_cost * $Qty, 2);

            return $arr_data = array(
                'filename' => $_filename,
                'qty' => $Qty,
                'itm_model' => $row['itm_model'],
                'itm_name' => $row['itm_name'],
                'current_cost' => $current_cost,
                'total_current_cost' => $total_current_cost
            );
        }
    }

    function removeOrder() {
        $ckey = isset($_POST['ckey']) ? $_POST['ckey'] : '';
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            if (isset($_SESSION['_CART']) && is_array($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                $carts = $_SESSION['_CART'];
                foreach ($carts as $key => $cart) {
                    if ($key == $ckey) {
                        if (isset($_SESSION['_CART'][$key]))
                            unset($_SESSION['_CART'][$key]);
                    }
                }
            }
        }
        return $this->lib->loadshoppingcartAuto();
    }

    function loadAutoCart($itm_key, $arrKeys, $Qty) {
        $arr_data = array();
        $total_price = 0;
        $itm_key = $this->lib->escape($itm_key);
        $re = $this->db->query("SELECT itm_id,itm_key,itm_name,itm_model,current_cost FROM items WHERE itm_key = '$itm_key' and itm_status = 1 ");
        if ($re->num_rows() > 0) {//1
            $row = $re->row_array();
            $itm_id = $row['itm_id'];
            $_filename = $this->database->db_result("select filename from items_files where tid = " . $itm_id . " order by weight DESC ");
            $current_cost = $row['current_cost'];
            if (!is_numeric($current_cost))
                $current_cost = 0;
            $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
            if ($re_price->num_rows() > 0) {
                $row_price = $re_price->row_array();
                $markup_percentage = $row_price['markup_percentage'];
                if (!is_numeric($markup_percentage))
                    $markup_percentage = 0;
                $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
            }
            $current_cost = round($current_cost, 2);

            $attributes = array();
            for ($i = 1; $i < count($arrKeys); $i++) {//3
                $arr_attribute = array(
                    'label' => '',
                    'name' => '',
                    'price' => 0
                );
                $arr_textfi = explode(__keyat__, $arrKeys[$i]);
                if (count($arr_textfi) == 2) {
                    if ($arr_textfi[1] == '')
                        continue;
                    $re_label = $this->db->query("select label from items_attributes where akey = '" . $arr_textfi[0] . "' and pkey = '$itm_key'");
                    if ($re_label->num_rows() > 0) {
                        $row_label = $re_label->row_array();
                        $arr_attribute['label'] = $row_label['label'];
                    } else {
                        $arr_attribute['label'] = $this->database->db_result("select label from attributes where status <> -1 and akey = '" . $arr_textfi[0] . "'");
                    }
                    $arr_attribute['name'] = $arr_textfi[1];
                } elseif (count($arr_textfi) == 1) {
                    $arr_attribute['label'] = $this->database->db_result("select items_attributes.label from items_attributes join attrioptions on items_attributes.akey = attrioptions.akey where items_attributes.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                    $re_pri = $this->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                    if ($re_pri->num_rows() > 0) {
                        $row_pri = $re_pri->row_array();
                        $arr_attribute['name'] = $row_pri['name'];
                        $arr_attribute['price'] = $row_pri['price'];
                    }
                }
                $attributes[] = $arr_attribute;
            }//3
            if (count($attributes) > 0) {
                foreach ($attributes as $attri) {
                    $at_price = round($attri['price'], 2);
                    $current_cost += $at_price;
                }
            }
            $itm_cost = $current_cost;
            $current_cost = round($current_cost * $Qty, 2);
            $total_price += $current_cost;
            $arr_data = array(
                '_filename' => $_filename,
                'itm_name' => $row['itm_name'],
                'itm_model' => $row['itm_model'],
                'current_cost' => $current_cost,
                'itm_cost' => $itm_cost,
                'total_current_cost' => $total_price
            );
        }
        return $arr_data;
    }

    function gotoCheckout() {
        $err = '';
        $arr_schedule = (isset($_POST['schedule']) && is_array($_POST['schedule'])) ? $_POST['schedule'] : array();
        if (count($arr_schedule) > 0) {
            for ($i = 0; $i < count($arr_schedule); $i++) {
                if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
                    if (isset($_SESSION['_CART']) && is_array($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                        $carts = $_SESSION['_CART'];
                        foreach ($carts as $ckey => $cart) {
                            if ($ckey == $arr_schedule[$i]['id']) {
                                $cart->cdate = $arr_schedule[$i]['start'];
                                $_SESSION['_CART'][$ckey] = $cart;
                            }
                        }
                    }
                }
            }
        }
        return array('err' => $err);
    }

    function checkout() {

        $billing_Name = '';
        $billing_LastName = '';
        $billing_Address = '';
        $billing_City = '';
        $billing_Zip = '';
        $billing_Phone = '';
        $billing_Email = '';
        $billing_country = '';
        $billing_state = '';

        $shipping_Name = '';
        $shipping_Address = '';
        $shipping_City = '';
        $shipping_Zip = '';
        $shipping_Phone = '';
        $shipping_country = '';
        $shipping_state = '';

        $shipping_check = '';

        if (isset($REQUEST_METHOD) && $REQUEST_METHOD == 'POST') {
            $shipping_Name = isset($_REQUEST['shipping_Name']) ? $_REQUEST['shipping_Name'] : '';
            $shipping_Address = isset($_REQUEST['shipping_Address']) ? $_REQUEST['shipping_Address'] : '';
            $shipping_City = isset($_REQUEST['shipping_City']) ? $_REQUEST['shipping_City'] : '';
            $shipping_Zip = isset($_REQUEST['shipping_Zip']) ? $_REQUEST['shipping_Zip'] : '';
            $shipping_Phone = isset($_REQUEST['shipping_Phone']) ? $_REQUEST['shipping_Phone'] : '';
            $shipping_country = isset($_REQUEST['shipping_Country']) ? $_REQUEST['shipping_Country'] : '';
            $shipping_state = isset($_REQUEST['shipping_State']) ? $_REQUEST['shipping_State'] : '';
            $billing_Name = isset($_REQUEST['billing_Name']) ? $_REQUEST['billing_Name'] : '';
            $billing_LastName = isset($_REQUEST['billing_LastName']) ? $_REQUEST['billing_LastName'] : '';
            $billing_Address = isset($_REQUEST['billing_Address']) ? $_REQUEST['billing_Address'] : '';
            $billing_City = isset($_REQUEST['billing_City']) ? $_REQUEST['billing_City'] : '';
            $billing_Zip = isset($_REQUEST['billing_Zip']) ? $_REQUEST['billing_Zip'] : '';
            $billing_Phone = isset($_REQUEST['billing_Phone']) ? $_REQUEST['billing_Phone'] : '';
            $billing_Email = isset($_REQUEST['billing_Email']) ? $_REQUEST['billing_Email'] : '';
            $billing_country = isset($_REQUEST['billing_Country']) ? $_REQUEST['billing_Country'] : '';
            $billing_state = isset($_REQUEST['billing_State']) ? $_REQUEST['billing_State'] : '';

            if (isset($_REQUEST['shipping_Check']) && $_REQUEST['shipping_Check'] == 1)
                $shipping_check = '';
            else
                $shipping_check = 'checked';

            $strContent = str_replace('@cc_Card_Type_' . $_REQUEST['cc_Card_Type'] . '@', "selected", $strContent);
            if (isset($_REQUEST['cc_Card_Month']))
                $month = $_REQUEST['cc_Card_Month'];
            if (isset($_REQUEST['cc_Card_Year']))
                $year = $_REQUEST['cc_Card_Year'];
        }else {
            if ($this->author->objlogin->login) {
                $r = $this->db->query("SELECT * FROM users WHERE uid=" . $this->author->objlogin->uid);
                if ($r->num_rows() > 0) {
                    $row = $r->row_array();
                    $billing_state = $row["state"];
                    $billing_country = $row["country"];
                    $billing_Name = $row["firstname"];
                    $billing_LastName = $row["lastname"];
                    $billing_Address = $row["address"];
                    $billing_City = $row["city"];
                    $billing_Zip = $row["zipcode"];
                    $billing_Phone = $row["phone"];
                    $billing_Email = $row["mail"];
                }
            } elseif (isset($_SESSION['__new_account__'])) {
                $billing_Name = isset($_SESSION['__new_account__']['firstname']) ? $_SESSION['__new_account__']['firstname'] : '';
                $billing_LastName = isset($_SESSION['__new_account__']['lastname']) ? $_SESSION['__new_account__']['lastname'] : '';
                $billing_Email = isset($_SESSION['__new_account__']['mail']) ? $_SESSION['__new_account__']['mail'] : '';
                $billing_Phone = isset($_SESSION['__new_account__']['phone']) ? $_SESSION['__new_account__']['phone'] : '';
            }
        }

        $data = array(
            'shipping_Name' => $shipping_Name,
            'shipping_Address' => $shipping_Address,
            'shipping_city' => $shipping_City,
            'shipping_Zip' => $shipping_Zip,
            'shipping_Phone' => $shipping_Phone,
            'shipping_country' => $shipping_country,
            'shipping_state' => $shipping_state,
            'billing_Name' => $billing_Name,
            'billing_LastName' => $billing_LastName,
            'billing_Address' => $billing_Address,
            'billing_City' => $billing_City,
            'billing_Zip' => $billing_Zip,
            'billing_Phone' => $billing_Phone,
            'billing_Email' => $billing_Email,
            'billing_country' => $billing_country,
            'billing_state' => $billing_state,
            'billing_city' => $billing_City,
            "if('load_countries'=='yes');" => "dataCountries = " . json_encode($this->__loadDataCountries2__()) . ";",
            'shipping_Check' => $shipping_check
        );
        return $data;
    }

    function loadObjectOrder() {
        $tblcontries = array();
        $re = $this->db->query("select * from tblcontries");
        foreach ($re->result_array() as $row) {
            $tblcontries[$row['code']] = $row['name'];
        }
        include("application/libraries/xmlparser.php");
        $xmlParser = new xmlparser();

        $shopping_carts = $this->lib->loadshoppingcart();
        $shipping_Country = (isset($_POST['shipping_Country']) && $_POST['shipping_Country'] != '') ? $_POST['shipping_Country'] : '';
        $shipping_State = (isset($_POST['shipping_State']) && $_POST['shipping_State'] != '') ? $_POST['shipping_State'] : '';
        $shipping_Zip = (isset($_POST['shipping_Zip']) && $_POST['shipping_Zip'] != '') ? $_POST['shipping_Zip'] : '';
        $shipping_City = (isset($_POST['shipping_City']) && $_POST['shipping_City'] != '') ? $_POST['shipping_City'] : '';
        $shipping_Address = (isset($_POST['shipping_Address']) && $_POST['shipping_Address'] != '') ? $_POST['shipping_Address'] : '';
        $shipping_Name = (isset($_POST['shipping_Name']) && $_POST['shipping_Name'] != '') ? $_POST['shipping_Name'] : '';
        $shipping_Phone = (isset($_POST['shipping_Phone']) && $_POST['shipping_Phone'] != '') ? $_POST['shipping_Phone'] : '';
        $arr_items_shippings = array();
        $manufacturers = array();


        $taxs = array();
        $re = $this->db->query("select * from tax_rates where status <> -1 order by weight DESC,name ASC");
        foreach ($re->result_array() as $row) {
            if ($row['state'] != '' && $row['state'] != $shipping_State)
                continue;
            $taxs[] = $row;
        }
        foreach ($_SESSION['_CART']->items as $k => $vs) {//0
            $arrKeys = explode(__keycode__, $k);
            if (count($arrKeys) > 0) {//1
                $itm_key = $arrKeys[0];
                $weight = 0;
                $UnitOfPackageWeight = 'lb';
                $Width = 1;
                $Length = 1;
                $Height = 1;
                $uid = 1;
                $product_type = 0;
                $item_price = 0;

                $re_2 = $this->db->query("select uid,weight,width,height,length,UnitOfPackageWeight,product_type,current_cost from items where itm_key = '$itm_key'");
                if ($re_2->num_rows() > 0) {
                    $row_2 = $re_2->row_array();
                    $uid = $row_2['uid'];
                    $weight = $row_2['weight'];
                    $Width = $row_2['width'];
                    if ($Width <= 0)
                        $Width = 1;
                    $Length = $row_2['length'];
                    if ($Length <= 0)
                        $Length = 1;
                    $Height = $row_2['height'];
                    if ($Height <= 0)
                        $Height = 1;
                    $UnitOfPackageWeight = $row_2['UnitOfPackageWeight'];
                    $product_type = $row_2['product_type'];
                    $item_price = $row_2['current_cost'];

                    $item_price = $row_2['current_cost'];
                    if (!is_numeric($item_price))
                        $item_price = 0;
                    $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                    if ($re_price->num_rows() > 0) {
                        $row_price = $re_price->row_array();
                        $markup_percentage = $row_price['markup_percentage'];
                        if (!is_numeric($markup_percentage))
                            $markup_percentage = 0;
                        $item_price += $item_price * $markup_percentage / 100;
                    }
                }

                $tax_rate_items = array();
                $item_rid = $this->database->db_result("select rid from users_roles join items on users_roles.uid = items.uid where items.itm_key = '" . $itm_key . "'");
                if ($product_type == 1 && $item_rid == 8) {
                    for ($i = 0; $i < count($taxs); $i++) {
                        if (!isset($taxs[$i]['tax_item_total']))
                            $taxs[$i]['tax_item_total'] = 0;
                        $taxs[$i]['tax_item_total'] += 0;
                    }
                }else {
                    $re = $this->db->query("select * from items_tax where itm_key = '" . $itm_key . "'");
                    foreach ($re->result_array() as $row) {
                        $tax_rate_items[] = $row;
                    }
                    for ($i = 0; $i < count($taxs); $i++) {
                        $tax_rate = $taxs[$i]['rate'];
                        for ($j = 0; $j < count($tax_rate_items); $j++) {
                            if ($tax_rate_items[$j]['tax_id'] == $taxs[$i]['id']) {
                                $tax_rate = $tax_rate_items[$j]['tax_rate'];
                            }
                        }
                        if (!isset($taxs[$i]['tax_item_total']))
                            $taxs[$i]['tax_item_total'] = 0;
                        $taxs[$i]['tax_item_total'] += round(((float) $tax_rate * (float) $item_price * (float) $vs) / 100, 2);
                    }
                }

                $check_exist = false;
                for ($i = 0; $i < count($manufacturers); $i++) {
                    if ($manufacturers[$i]['uid'] == $uid) {
                        $check_exist = true;
                        $manufacturers[$i]['items'][] = array('key' => $itm_key, 'sum' => $vs, 'k' => $k, 'weight' => $weight, 'uw' => $UnitOfPackageWeight, 'Width' => $Width, 'Length' => $Length, 'Height' => $Height, 'product_type' => $product_type);
                        break;
                    }
                }
                if ($check_exist == false) {
                    $manufacturers[] = array('uid' => $uid, 'items' => array(array('key' => $itm_key, 'sum' => $vs, 'k' => $k, 'weight' => $weight, 'uw' => $UnitOfPackageWeight, 'Width' => $Width, 'Length' => $Length, 'Height' => $Height, 'product_type' => $product_type)));
                }
                if ($product_type == 0) {
                    $re = $this->db->query("select * from items_shippings where pkey = '$itm_key'");
                    foreach ($re->result_array() as $row) {
                        $countries = array();
                        $re_2 = $this->db->query("select * from items_shippings_countries where sid = " . $row['id']);
                        foreach ($re_2->result_array() as $row_2) {
                            $states = array();
                            $re_3 = $this->db->query("select * from items_shippings_states where country_id = " . $row_2['id']);
                            foreach ($re_3->result_array() as $row_3) {
                                $states[] = $row_3;
                            }
                            $row_2['states'] = $states;
                            $countries[] = $row_2;
                        }
                        $row['countries'] = $countries;
                        $arr_items_shippings[] = $row;
                    }
                }
            }//1
        }//0
        $items__ = array();
        $re = $this->db->query("select * from shipping_rates where status = 1 order by weight DESC ");
        foreach ($re->result_array() as $row) {//00
            $is_error = false;
            $skey = $row['skey'];
            $price = -1;
            if ($row['shipping_method'] == 0) {
                $re2 = $this->db->query("select * from shipping_manually where skey = '" . $row['skey'] . "'");
                foreach ($re2->result_array() as $row2) {
                    if ($row2['country'] == $shipping_Country) {
                        if ($row2['rate_type'] == 1) {//Select Country
                            if ($row2['country_rate'] >= 0) {// Thoa man
                                $price = $row2['country_rate'];
                                break;
                            }
                        } else {//
                            $re3 = $this->db->query("select * from shipping_manually_states where ship_country_id = " . $row2['id']);
                            foreach ($re3->result_array() as $row3) {
                                if ($row3['state'] == $shipping_State) {
                                    if ($row3['state_rate'] >= 0) {// Thoa man
                                        $price = $row3['state_rate'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                for ($m = 0; $m < count($manufacturers); $m++) {//2
                    foreach ($manufacturers[$m]['items'] as $kindex => $item) {//3
                        $item['ship_rate'][$row['skey']] = $price;
                        $manufacturers[$m]['items'][$kindex] = $item;
                    }
                }
            } elseif ($row['shipping_method'] == 1) {//uPS
                //	$price = 0;	
            } elseif ($row['shipping_method'] == 2) {//USPS
                $re_2 = $this->db->query("select * from shipping_usps where skey = '$skey'");
                if ($re_2->num_rows() > 0) {//0
                    $row_2 = $re_2->row_array();
                    $access_key = ($row_2['USPS_userid'] != null && $row_2['USPS_userid'] != '') ? $row_2['USPS_userid'] : '052BELLA6359';
                    if ($shipping_Country == 'US') {//1
                        for ($m = 0; $m < count($manufacturers); $m++) {//2
                            $origin_zipcode = '';
                            $re_m = $this->db->query("select data_xml,zipcode from manufacturers where uid = '" . $manufacturers[$m]['uid'] . "'");
                            if ($re_m->num_rows() > 0) {
                                $row_m = $re_m->row_array();
                                if ($row_m['data_xml'] != null && $row_m['data_xml'] != '') {
                                    $data_xml = unserialize($row_m['data_xml']);
                                    $origin_zipcode = (isset($data_xml['origin_zipcode']) && $data_xml['origin_zipcode'] != '' && $data_xml['origin_zipcode'] != null) ? $data_xml['origin_zipcode'] : trim($row_m['zipcode']);
                                } else {
                                    $origin_zipcode = trim($row_m['zipcode']);
                                }
                            }
                            foreach ($manufacturers[$m]['items'] as $kindex => $item) {//3
                                $Pounds = 1;
                                $Ounces = 1;
                                if ($item['uw'] == 'lb') {
                                    $Pounds = $item['weight'];
                                    if ($Pounds <= 0)
                                        $Pounds = 1;
                                }elseif ($item['uw'] == 'oz') {
                                    $Ounces = $item['weight'];
                                    if ($Ounces <= 0)
                                        $Ounces = 1;
                                }
                                $data__ = "http://production.shippingapis.com/shippingapi.dll?API=RateV4&XML=<RateV4Request USERID=\"$access_key\">
									<Package ID=\"1ST\">
										<Service>" . $row_2['USPS_service'] . "</Service>
										<FirstClassMailType>FLAT</FirstClassMailType>
										<ZipOrigination>$origin_zipcode</ZipOrigination>
										<ZipDestination>$shipping_Zip</ZipDestination>
										<Pounds>$Pounds</Pounds>
										<Ounces>$Ounces</Ounces>
										<Container/>
										<Size>REGULAR</Size>
										<Machinable>" . $row_2['Machinable'] . "</Machinable>
									</Package>
								</RateV4Request>";
                                $arr_price = array();
                                $data__ = $this->lib->__grabURL__($data__);

                                $content__ = file_get_contents($data__);

                                $array_xml = $xmlParser->GetXMLTree($content__);
                                if (isset($array_xml['RATEV4RESPONSE']) && count($array_xml['RATEV4RESPONSE']) > 0) { // if everything OK
                                    foreach ($array_xml['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value) {
                                        $arr_price[] = (float) $value['RATE'][0]['VALUE'];
                                    }
                                }
                                if (count($arr_price) == 0)
                                    $is_error = true;
                                $item['ship_rate'][$row['skey']] = $this->getaveArr($arr_price);
                                $manufacturers[$m]['items'][$kindex] = $item;
                            }//3
                        }//2
                    }else {//1
                        for ($m = 0; $m < count($manufacturers); $m++) {//2
                            $origin_zipcode = '';
                            $re_m = $this->db->query("select data_xml,zipcode from manufacturers where uid = '" . $manufacturers[$m]['uid'] . "'");
                            if ($re_m->num_rows() > 0) {
                                $row_m = $re_m->row_array();
                                if ($row_m['data_xml'] != null && $row_m['data_xml'] != '') {
                                    $data_xml = unserialize($row_m['data_xml']);
                                    $origin_zipcode = (isset($data_xml['origin_zipcode']) && $data_xml['origin_zipcode'] != '' && $data_xml['origin_zipcode'] != null) ? $data_xml['origin_zipcode'] : trim($row_m['zipcode']);
                                } else {
                                    $origin_zipcode = trim($row_m['zipcode']);
                                }
                            }
                            foreach ($manufacturers[$m]['items'] as $kindex => $item) {//3
                                $Pounds = 0;
                                $Ounces = 0;
                                $Width = $item['Width'];
                                $Length = $item['Length'];
                                $Height = $item['Height'];
                                if ($item['uw'] == 'lb') {
                                    $Pounds = $item['weight'];
                                    if ($Pounds <= 0)
                                        $Pounds = 1;
                                }elseif ($item['uw'] == 'oz') {
                                    $Ounces = $item['weight'];
                                    if ($Ounces <= 0)
                                        $Ounces = 1;
                                }
                                $data__ = "http://production.shippingapis.com/shippingapi.dll?API=IntlRateV2&XML=<IntlRateV2Request USERID=\"$access_key\">
								  <Revision>2</Revision>
								  <Package ID=\"1ST\">
									<Pounds>$Pounds</Pounds>
									<Ounces>$Ounces</Ounces>
									<Machinable>" . $row_2['Machinable'] . "</Machinable>
									<MailType>Package</MailType>
									<GXG>
										  <POBoxFlag>Y</POBoxFlag>
										  <GiftFlag>Y</GiftFlag>
									</GXG>
									<ValueOfContents></ValueOfContents>
									<Country>" . (isset($tblcontries[$shipping_Country]) ? $tblcontries[$shipping_Country] : $shipping_Country) . "</Country>
									<Container>RECTANGULAR</Container>
									<Size>REGULAR</Size>
									<Width>$Width</Width>
									<Length>$Length</Length>
									<Height>$Height</Height>
									<Girth>0</Girth>
									<OriginZip>$origin_zipcode</OriginZip>
									<CommercialFlag>N</CommercialFlag>
								  </Package>
								</IntlRateV2Request>";
                                $arr_price = array();
                                $data__ = $this->lib->__grabURL__($data__);
                                $content__ = file_get_contents($data__);
                                $xmlParser = new xmlparser();
                                $array_xml = $xmlParser->GetXMLTree($content__);
                                if (isset($array_xml['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE']) && count($array_xml['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE']) > 0) {
                                    foreach ($array_xml['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'] as $value) {
                                        $arr_price[] = $value['POSTAGE'][0]['VALUE'];
                                    }
                                }
                                if (count($arr_price) == 0)
                                    $is_error = true;
                                $item['ship_rate'][$row['skey']] = $this->getaveArr($arr_price);
                                $manufacturers[$m]['items'][$kindex] = $item;
                            }//3
                        }//2	
                    }//1	
                }//0
            }elseif ($row['shipping_method'] == 3) {//
            }
            if ($is_error == true)
                continue;
            $total_price = 0;
            for ($m = 0; $m < count($manufacturers); $m++) {//0
                $handling_fee_new = $row['handling_fee'];
                foreach ($manufacturers[$m]['items'] as $item) {//1
                    $itm_key = $item['key'];
                    for ($i = 0; $i < count($arr_items_shippings); $i++) {
                        if ($arr_items_shippings[$i]['pkey'] == $itm_key && $arr_items_shippings[$i]['skey'] == $row['skey']) {
                            if ($arr_items_shippings[$i]['handling'] >= 0) {
                                if ($handling_fee_new < $arr_items_shippings[$i]['handling'])
                                    $handling_fee_new = $arr_items_shippings[$i]['handling'];
                            }
                            break;
                        }
                    }
                }
                $ship_rate = $handling_fee_new;
                $count_ship_free = count($manufacturers[$m]['items']);

                foreach ($manufacturers[$m]['items'] as $item) {//1
                    if ($item['product_type'] == 1 || $item['product_type'] == 2) {
                        $count_ship_free--;
                        continue;
                    }

                    $itm_key = $item['key'];
                    $default_product_rate = $item['ship_rate'][$row['skey']];
                    //$default_product_rate = $item['ship_rate']['YWhztPgDIoZH38vXiYs9'];
                    for ($i = 0; $i < count($arr_items_shippings); $i++) {
                        if ($arr_items_shippings[$i]['pkey'] == $itm_key && $arr_items_shippings[$i]['skey'] == $row['skey']) {
                            $countries = $arr_items_shippings[$i]['countries'];
                            if (count($countries) > 0) {
                                foreach ($countries as $contry) {
                                    if ($contry['country_code'] == $shipping_Country) {
                                        if ($contry['rate_type'] == 1) {
                                            if ($contry['country_rate'] >= 0) {
                                                $default_product_rate = $contry['country_rate'];
                                            }
                                        } else {
                                            $states = $contry['states'];
                                            if (count($states) > 0) {
                                                foreach ($states as $state__) {
                                                    if ($state__['state_code'] == $shipping_State) {
                                                        if ($state__['state_rate'] >= 0) {
                                                            $default_product_rate = $state__['state_rate'];
                                                        }
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        break;
                                    }
                                }
                            }
                            break;
                        }
                    }
                    $default_product_rate_last = $default_product_rate_current = $default_product_rate * $item['sum'];
                    $check_shipping_free = false;
                    if (count($shopping_carts['promotions']) > 0) {//2
                        foreach ($shopping_carts['promotions'] as $promotions) {//3
                            if ($promotions['itm_key'] == $itm_key) {
                                if ((int) $promotions['promo_type'] == 3) {
                                    $check_shipping_free = true;
                                    if ($promotions['discount_type'] == 0) {
                                        $default_product_rate_last -= $default_product_rate_current * $promotions['discount'] / 100;
                                    } elseif ($promotions['discount_type'] == 1) {
                                        $default_product_rate_last -= round($promotions['discount'], 2);
                                    }
                                } elseif ((int) $promotions['promo_type'] == 4) {
                                    $check_ok = false;
                                    for ($i = 0; $i < count($promotions['countries']); $i++) {
                                        if ($promotions['countries'][$i]['code'] == $shipping_Country) {
                                            if (count($promotions['countries'][$i]['states']) > 0) {
                                                foreach ($promotions['countries'][$i]['states'] as $state_code) {
                                                    if ($state_code == $shipping_State) {
                                                        $check_ok = true;
                                                    }
                                                }
                                            } else {
                                                $check_ok = true;
                                            }
                                            break;
                                        }
                                    }
                                    if ($check_ok == true) {
                                        $check_shipping_free = true;
                                        $default_product_rate_last = 0;
                                    }
                                }
                            }
                        } //3
                    }//2
                    if ($default_product_rate_last <= 0) {
                        $default_product_rate_last = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                    $ship_rate += $default_product_rate_last;
                }//1
                if ($ship_rate == $handling_fee_new && $count_ship_free == 0)
                    $ship_rate = 0;
                $total_price += round($ship_rate, 2);
            }//0

            $items__[] = array(
                'skey' => $row['skey'],
                'label' => $row['label'] . ' &amp; handling fee',
                'price' => round($total_price, 2)
            );
        }//00
        if (isset($_SESSION['__manufacturers__']))
            unset($_SESSION['__manufacturers__']);
        $_SESSION['__manufacturers__'] = $manufacturers;
        $arr_merge = array_merge(array('shippings' => $items__, 'taxs' => $taxs, 'commissions' => $this->loadEarningsRep($this->author->objlogin->uid)), $shopping_carts);
        return json_encode($arr_merge);
    }

    function getaveArr($arr) {
        $value = 0;
        if (count($arr) == 1)
            return $arr[0];
        elseif (count($arr) > 1) {
            for ($i = 0; $i < count($arr) - 1; $i++) {
                for ($j = $i + 1; $j < count($arr); $j++) {
                    if ($arr[$i] > $arr[$j]) {
                        $tam = $arr[$i];
                        $arr[$i] = $arr[$j];
                        $arr[$j] = $tam;
                    }
                }
            }
            $index = round(count($arr) / 2);
            $value = isset($arr[$index]) ? $arr[$index] : $arr[0];
        }
        return $value;
    }

    function loadEarningsRep($uid) {
        $total_commission = 0;
        $roles = $this->author->loadRole($uid);
        if ($roles['rid'] == 9) {
            $sale_rep = new sale_rep($uid);
            $total_commission = $sale_rep->getTotalEarning();
        }
        return (float) $total_commission;
    }

    function __loadDataCountries2__() {
        $countries = array('US', 'CA');
        $arr = array();
        $re = $this->db->query("select * from tblcontries order by name asc");
        foreach ($re->result_array() as $row) {
            if (!in_array($row['code'], $countries))
                continue;
            $arr_states = array();
            $arr_cities = array();
            if ($row['status'] == 1) {
                $re_2 = $this->db->query("select * from tblsates where idcountry = " . $row['id'] . " order by name asc");
                foreach ($re_2->result_array() as $row_2) {
                    $arr_states[] = $row_2;
                }
                $re_cities = $this->db->query("select * from tblcities where idcountry = " . $row['id'] . " order by city asc ");
                foreach ($re_cities->result_array() as $row_cities) {
                    $arr_cities[] = $row_cities;
                }
            }
            $row['states'] = $arr_states;
            $row['cities'] = $arr_cities;
            $row['codedefault'] = $this->lib->getcode();
            $arr[] = $row;
        }
        return $arr;
    }

    function submit_Order() {
        $count = 0;
        $cart = array();
        if (isset($_SESSION['_CART'])) {
            $cart = $_SESSION['_CART'];
            if (isset($cart->items) && is_array($cart->items))
                $count += count($cart->items);
        }
        if ($count == 0) {
            header('Location: ' . $this->system->URL_server__() . 'shop/shome');
            exit;
        }
        include('application/libraries/payment_functions.php');
        if ($this->input->post('submitOrder') && $this->input->post('submitOrder') == 'yes') {
            $payment_obj = new payment();
            $check = true;
            foreach ($cart->items as $key => $quantity) {
                if (!$this->check_items_voucher($key)) {
                    $check = false;
                    break;
                }
            }
            if ($check) {
                $payment_obj->check_voucher = true;
            }
            echo $payment_obj->pay();
            exit;
        } else {
            echo "incorrect^You have just entered a wrong path!";
            exit;
        }
    }

    function submit_donate() {
        $payment_obj = new payment();
        $payment_obj->check_donate = true;
        echo $payment_obj->pay();
        exit;
    }

    function submit_autodelivery() {
        if (isset($_POST['submit_autodeli']) && $_POST['submit_autodeli'] == 'yes') {
            $payment_obj = new payAutoShip();
            echo $payment_obj->pay();
            exit;
        }else
            echo "incorrect^You have just entered a wrong path!";
    }

    function checkout_donate($dkey = '', $qtt = 0) {
        $data = array();
        $itm_key = $this->lib->escape($dkey);
        $qtt = is_numeric($this->lib->escape($qtt)) ? $this->lib->escape($qtt) : 0;
        $total_commission = 0;
        if ($itm_key != '') {
            $re_ = $this->db->query("select items_files.filename, items.itm_name, items.itm_model, items.origin, items.current_cost from items_files join items on items_files.tid = items.itm_id where items.itm_key = '" . $itm_key . "' ORDER BY items_files.weight ASC");
            if ($re_->num_rows() > 0) {
                $row = $re_->row_array();
                $price = (is_numeric($row['current_cost']) && $row['current_cost'] > 0) ? (float) $row['current_cost'] : 0;
                $re_price = $this->db->query("select product_markup.markup_percentage,product_markup.commission_member from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                if ($re_price->num_rows() > 0) {
                    $row_price = $re_price->row_array();
                    $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? (float) $row_price['markup_percentage'] : 0;
                    $price = $price + $price * $markup_percentage / 100;
                    $total_commission = $row_price['commission_member']; //insert code here 
                }
                $price = round($price, 2);
                $price_str = "$" . number_format($price, 2);
                $subtotal = number_format($price * $qtt, 2);
                $total_str = "$" . $subtotal;
                $data['total_commission'] = number_format(($price * $qtt * $total_commission) / 100, 2); //insert code here 

                $data['<!--product_name-->'] = $row['itm_name'];
                $data['<!--product_model-->'] = 'Model: ' . $row['itm_model'];
                $data['<!--product_price-->'] = 'Price: ' . $price_str;
                $data['<!--product_origin-->'] = $row['origin'];
                $data['<!--quatity-->'] = 'Quantity: ' . $qtt;
                $data['<!--total-->'] = 'Total: ' . $total_str;

                $commissions = $this->loadEarningsRep($this->author->objlogin->uid);

                $data["if('subtotal' == 'yes');"] = 'var subtotal = parseFloat(' . $subtotal . ');';
                $data["if('commission' == 'yes');"] = 'var commission = parseFloat(' . $commissions . ');';
                if (true) {
                    $data['<!--img_source-->'] = '<img src="' . $this->system->URL_server__() . 'shopping/data/img/thumb_home/' . $row['filename'] . '">';
                }else
                    $data['<!--img_source-->'] = '';
            }
        }
        $billing_country = '';
        $billing_state = '';
        $billing_Name = '';
        $billing_LastName = '';
        $billing_Address = '';
        $billing_City = '';
        $billing_Zip = '';
        $billing_Phone = '';
        $billing_Email = '';
        $cc_Card_Number = '';
        $cc_Card_Cvc = '';
        if (isset($REQUEST_METHOD) && $REQUEST_METHOD == 'POST') {
            $billing_Name = isset($_REQUEST['billing_Name']) ? $_REQUEST['billing_Name'] : '';
            $billing_LastName = isset($_REQUEST['billing_LastName']) ? $_REQUEST['billing_LastName'] : '';
            $billing_Address = isset($_REQUEST['billing_Address']) ? $_REQUEST['billing_Address'] : '';
            $billing_City = isset($_REQUEST['billing_City']) ? $_REQUEST['billing_City'] : '';
            $billing_Zip = isset($_REQUEST['billing_Zip']) ? $_REQUEST['billing_Zip'] : '';
            $billing_Phone = isset($_REQUEST['billing_Phone']) ? $_REQUEST['billing_Phone'] : '';
            $billing_Email = isset($_REQUEST['billing_Email']) ? $_REQUEST['billing_Email'] : '';
            $billing_country = isset($_REQUEST['billing_Country']) ? $_REQUEST['billing_Country'] : '';
            $billing_state = isset($_REQUEST['billing_State']) ? $_REQUEST['billing_State'] : '';
            $cc_Card_Number = isset($_REQUEST['cc_Card_Number']) ? $_REQUEST['cc_Card_Number'] : '';
            $cc_Card_Cvc = isset($_REQUEST['cc_Card_Cvc']) ? $_REQUEST['cc_Card_Cvc'] : '';

            $data['@cc_Card_Type_' . $_REQUEST['cc_Card_Type'] . '@'] = "selected";
            if (isset($_REQUEST['cc_Card_Month']))
                $month = $_REQUEST['cc_Card_Month'];
            if (isset($_REQUEST['cc_Card_Year']))
                $year = $_REQUEST['cc_Card_Year'];
        }else {
            if ($this->author->objlogin->login) {
                $r = $this->db->query("SELECT * FROM users WHERE uid=" . $this->author->objlogin->uid);
                if ($r->num_rows() > 0) {
                    $row = $r->row_array();
                    $billing_state = $row["state"];
                    $billing_country = $row["country"];
                    $billing_Name = $row["firstname"];
                    $billing_LastName = $row["lastname"];
                    $billing_Address = $row["address"];
                    $billing_City = $row["city"];
                    $billing_Zip = $row["zipcode"];
                    $billing_Phone = $row["phone"];
                    $billing_Email = $row["mail"];
                }
            } elseif (isset($_SESSION['__new_account__'])) {
                $billing_Name = isset($_SESSION['__new_account__']['firstname']) ? $_SESSION['__new_account__']['firstname'] : '';
                $billing_LastName = isset($_SESSION['__new_account__']['lastname']) ? $_SESSION['__new_account__']['lastname'] : '';
                $billing_Email = isset($_SESSION['__new_account__']['mail']) ? $_SESSION['__new_account__']['mail'] : '';
                $billing_Phone = isset($_SESSION['__new_account__']['phone']) ? $_SESSION['__new_account__']['phone'] : '';
            }
        }
        $data['<!--billing_Name-->'] = $billing_Name;
        $data['<!--billing_LastName-->'] = $billing_LastName;
        $data['<!--billing_Address-->'] = $billing_Address;
        $data['<!--billing_City-->'] = $billing_City;
        $data['<!--billing_Zip-->'] = $billing_Zip;
        $data['<!--billing_Phone-->'] = $billing_Phone;
        $data['<!--billing_Email-->'] = $billing_Email;
        $data['<!--cc_Card_Number-->'] = $cc_Card_Number;
        $data['<!--cc_Card_Cvc-->'] = $cc_Card_Cvc;
        $data['@billing_country@'] = $billing_country;
        $data['@billing_state@'] = $billing_state;
        $data['@pkey@'] = $itm_key;
        $data['@qtt@'] = $qtt;

        $data["if('load_countries'=='yes');"] = "dataCountries = " . json_encode($this->__loadDataCountries2__()) . ";";

        return $data;
    }

}

class payment extends CI_Model {

    var $uid = -1;
    var $suptotal = 0;
    var $ordertotal = 0;
    var $pay_last = 0;
    var $shipping_datas = array();
    var $arrPromotions = array();
    var $arr_items_shippings = array();
    var $arr_Manufacturers = array();
    var $arr_tax = array();
    var $tax = 0;
    var $tax_persen = 0;
    var $shippingfee = 0;
    var $okey = '';
    var $order_number = 0;
    var $r_ordernum = 'XXXXX';
    var $r_tdate = '';
    var $roles = array();
    var $total_commission = 0;
    var $sale_rep_obj;
    var $customerProfileId = '';
    var $customerPaymentProfileId = '';
    var $customerAddressId = '';
    var $mail_body_admin = '';
    var $mail_body_client = 'Thank you for ordering at Bellavie Network. Listed below is your order information. Please save this email until you receive your order.';
    var $schedule_delivery = array();
    var $check_commission = 0;
    var $card4digis = '';
    var $check_donate = false;
    var $check_voucher = false;
    var $arrCharities_notruct = array();
    var $arrCharities_truct = array();

    function __construct($uid = NULL) {
        $this->r_tdate = $this->lib->getTimeGMT();

        if (isset($_POST["cc_Card_Number"]) && $_POST["cc_Card_Number"] != '') {
            $this->card4digis = substr($_POST["cc_Card_Number"], strlen($_POST["cc_Card_Number"]) - 4, strlen($_POST["cc_Card_Number"]));
        }

        $this->getArrCharities();

        if (!isset($this->author->objlogin->login) && isset($_SESSION['__new_account__'])) {
            $this->CreateNewAccount();
        } elseif ($this->author->objlogin->login) {
            $this->uid = $this->author->objlogin->uid;
            $this->roles = $this->author->objlogin->role;
        }

        $this->check_commission = (isset($_POST['check_commission']) && $_POST['check_commission'] == 1) ? 1 : 0;
    }

    function pay() {

        $__payment_name__ = $this->lib->getMailInfor('paypal_settings', 'payment_name');
        if ($this->check_donate) {
            $this->checkItemAvailable_donate();
        } else {
            $this->checkItemAvailable();
        }
        if ($this->roles['rid'] == 9) {
            $this->sale_rep_obj = new sale_rep($this->uid);
            if ($this->check_commission == 1) {
                $this->total_commission = $this->sale_rep_obj->getTotalEarning();
                if ($this->total_commission > $this->pay_last)
                    $this->total_commission = $this->pay_last;
                $this->pay_last -= $this->total_commission;
            }
        }
        $okey = $this->lib->GeneralRandomNumberKey(8);
        $re = $this->db->query("select orderid from orders where okey = '$okey'");
        while ($re->num_rows() > 0) {
            $row = $re->row_array();
            $okey = $this->lib->GeneralRandomNumberKey(8);
            $re = $this->db->query("select orderid from orders where okey = '$okey'");
        }

        $this->okey = $okey;

        if ($this->pay_last <= 0) {
            return $this->saveOrder();
        } else {
            switch ($__payment_name__) {
                case 'authorize':
                    return $this->authorizePayment();
                    break;
                case 'paypal':
                    return $this->paypalCheckout();
                    break;
                case 'firstdata':
                    return $this->firstdataPayment();
                    break;
            }
        }
    }

    function paypalCheckout() {
        require_once 'application/libraries/constants.php';
        require_once 'application/libraries/CallerService.php';

        $firstName = urlencode($_POST['billing_Name']);
        $lastName = urlencode($_POST['billing_LastName']);
        $creditCardNumber = urlencode($_POST['cc_Card_Number']);
        $expDateMonth = urlencode($_POST['cc_Card_Month']);
        // Month must be padded with leading zero
        $padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

        $expDateYear = urlencode($_POST['cc_Card_Year']);
        $cvv2Number = urlencode($_POST['cc_Card_Cvc']);
        $address1 = urlencode($_POST['billing_Address']);
        $city = urlencode($_POST['billing_City']);
        $state = urlencode($_POST['billing_State']);
        $zip = urlencode($_POST['billing_Zip']);
        $amount = urlencode($this->pay_last);
        //$currencyCode=urlencode($_POST['currency']);
        $currencyCode = "USD";
        $paymentType = urlencode('Sale');
        $nvpstr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=" . $padDateMonth . $expDateYear . "&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state" .
                "&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode";

        $resArray = hash_call("doDirectPayment", $nvpstr);

        $ack = strtoupper($resArray["ACK"]);
        if ($ack != "SUCCESS") {
            return "incorrect^" . $resArray['L_LONGMESSAGE0'];
        } else {
            return $this->saveOrder();
        }
    }

    function authorizePayment() {
        global $DEBUGGING, $TESTING, $ERROR_RETRIES;

        /* $auth_net_login_id = '4ZuE3EFu64J9';
          $auth_net_tran_key = '7B8p9CDJ978hqh6V';
          $auth_net_url = 'testMode'; */

        $auth_net_login_id = $this->lib->getMailInfor('paypal_settings', 'auth_login_id');
        $auth_net_tran_key = $this->lib->getMailInfor('paypal_settings', 'auth_tran_key');
        $auth_net_url = $this->lib->getMailInfor('paypal_settings', 'auth_url');


        // Post credit Card info to authorize server
        if (!$this->check_donate) {
            $shipToList_firstName = '';
            $shipToList_lastName = '';
            if ($_POST["shipping_Name"] != '') {
                $shipping_Name = str_replace("  ", " ", $this->lib->escape($_POST["shipping_Name"]));
                $arr_ship_name = explode(" ", $shipping_Name);
                $shipToList_firstName = isset($arr_ship_name[0]) ? $arr_ship_name[0] : '';
                for ($i = 1; $i < count($arr_ship_name); $i++) {
                    $shipToList_lastName .= $arr_ship_name[$i] . ' ';
                }
                $shipToList_lastName = trim($shipToList_lastName);
            }
        }
        $billInfo = array(
            'billTo_firstName' => $this->lib->escape($_POST["billing_Name"]),
            'billTo_lastName' => $this->lib->escape($_POST["billing_LastName"]),
            'billTo_address' => $this->lib->escape($_POST["billing_Address"]),
            'billTo_city' => $this->lib->escape($_POST["billing_City"]),
            'billTo_state' => $this->lib->escape($_POST["billing_State"]),
            'billTo_zip' => $this->lib->escape($_POST["billing_Zip"]),
            'billTo_country' => $this->lib->escape($_POST["billing_Country"]),
            'billTo_phoneNumber' => $this->lib->escape($_POST["billing_Phone"]),
            'cardNumber' => $_POST['cc_Card_Number'],
            'expirationDate' => $_POST['cc_Card_Year'] . '-' . sprintf('%02d', $_POST['cc_Card_Month']),
            'cardCode' => $_POST['cc_Card_Cvc'],
            'shipToList_firstName' => ($this->check_donate) ? '' : $shipToList_firstName,
            'shipToList_lastName' => ($this->check_donate) ? '' : $shipToList_lastName,
            'shipToList_address' => ($this->check_donate) ? '' : $this->lib->escape($_POST["shipping_Address"]),
            'shipToList_city' => ($this->check_donate) ? '' : $this->lib->escape($_POST["shipping_City"]),
            'shipToList_state' => ($this->check_donate) ? '' : $this->lib->escape($_POST["shipping_State"]),
            'shipToList_zip' => ($this->check_donate) ? '' : $this->lib->escape($_POST["shipping_Zip"]),
            'shipToList_country' => ($this->check_donate) ? '' : $this->lib->escape($_POST["shipping_Country"]),
            'shipToList_phoneNumber' => ($this->check_donate) ? '' : $this->lib->escape($_POST["shipping_Phone"])
        );
        $profileTransAuthCapture = array(
            'amount' => $this->pay_last,
            'invoiceNumber' => $this->okey
        );

        require('application/libraries/authorize.class.php');
        $authorize = new authorize($auth_net_login_id, $auth_net_tran_key, $auth_net_url);
        $authorize->profile_mail = $this->author->objlogin->mail;
        $authorize->merchantCustomerId = sprintf('%08d', $this->uid);

        $authorize->set_ProfileInfo($billInfo);
        $data_user = $this->author->objlogin->data;
        if ($data_user == null || $data_user == '') {
            $authorize->createCustomerProfileFullRequest();
            $this->customerProfileId = $authorize->get_customerProfileId();
            if ($this->customerProfileId != '') {
                $data_new_user = '<customerProfileId>' . $this->customerProfileId . '</customerProfileId>';
                $this->db->update('users', array('data' => $data_new_user), 'uid = ' . $this->uid);
                $this->author->objlogin->data = $data_new_user;
            } else {
                return "incorrect^" . $authorize->get_error();
            }
        } else {
            $arr_data_user = $this->lib->partitionString("<customerProfileId>", "</customerProfileId>", $data_user);
            if (isset($arr_data_user[1]) && $arr_data_user[1] != '') {
                $authorize->createCustomerPaymentProfileRequest($arr_data_user[1]);
            } else {
                $authorize->createCustomerProfileFullRequest();
            }
            $this->customerProfileId = $authorize->get_customerProfileId();
            if ($this->customerProfileId != '') {
                $data_new_user = '';
                if (isset($arr_data_user[0]))
                    $data_new_user .= $arr_data_user[0];
                $data_new_user .= '<customerProfileId>' . $this->customerProfileId . '</customerProfileId>';
                if (isset($arr_data_user[2]))
                    $data_new_user .= $arr_data_user[2];
                $this->author->objlogin->data = $data_new_user;
                $this->db->update('users', array('data' => $data_new_user), 'uid = ' . $this->uid);
            }else {
                return "incorrect^" . $authorize->get_error();
            }
        }
        $this->customerPaymentProfileId = $authorize->get_customerPaymentProfileId();
        if ($this->customerProfileId == '' || $this->customerPaymentProfileId == '') {
            return "incorrect^" . $authorize->get_error();
        } else {
            $authorize->profileTransAuthCapture($profileTransAuthCapture);
            if ($authorize->responseCode == 1) {
                $this->r_ordernum = $authorize->transId;
                $this->customerAddressId = $authorize->customerAddressId;
                return $this->saveOrder();
            } else {
                return "incorrect^" . $authorize->get_error();
            }
        }
        return "incorrect^Your card is invalid! Please check your card information!";
    }

    function firstdataPayment() {
        global $__firstdata_host__, $__firstdata_port__, $__firstdata_store_number__, $__transaction__;

        return $this->saveOrder();

        require("application/libraries/lphp.php");
        $mylphp = new lphp();

        # constants
        $myorder["host"] = $__firstdata_host__;
        $myorder["port"] = $__firstdata_port__;
        $myorder["keyfile"] = "includes/firstdata/" . $__firstdata_store_number__ . ".pem"; # Change this to the name and location of your certificate file 
        $myorder["configfile"] = $__firstdata_store_number__;        # Change this to your store number 
        # transaction details
        $myorder["ordertype"] = 'SALE';
        $myorder["result"] = $__transaction__;
        $myorder["transactionorigin"] = 'ECI';
        $myorder["taxexempt"] = 'N';
        $myorder["terminaltype"] = 'UNSPECIFIED';
        $myorder["ip"] = $_SERVER['REMOTE_ADDR'];
        # totals
        //	$myorder["subtotal"]    = $ordertotal - $shippingfee - $tax;
        //	$myorder["tax"]         = $tax;
        //	$myorder["shipping"]    = $shippingfee;
        //	$myorder["vattax"]      = 0;
        $myorder["chargetotal"] = $this->pay_last;
        # card info
        $myorder["cardnumber"] = $_POST['cc_Card_Number'];
        $myorder["cardexpmonth"] = sprintf('%02d', $_POST['cc_Card_Month']);
        $myorder["cardexpyear"] = substr($_POST['cc_Card_Year'], 2, 2);
        $myorder["cvmindicator"] = 'provided';
        $myorder["cvmvalue"] = $_POST['cc_Card_Cvc'];
        # BILLING INFO
        $myorder["name"] = $_POST['billing_Name'] . ' ' . $_POST['billing_LastName'];
        $myorder["company"] = $_POST['billing_Name'];
        $myorder["address1"] = $_POST['billing_Address'];
        $myorder["address2"] = '';
        $myorder["city"] = $_POST['billing_City'];
        $myorder["state"] = $_POST['billing_State'];
        $myorder["country"] = $_POST['billing_Country'];
        $myorder["phone"] = $_POST['billing_Phone'];
        $myorder["fax"] = '';
        $myorder["email"] = $_POST['billing_Email'];
        $myorder["addrnum"] = $this->getFirstAddress($myorder["address1"]);
        $myorder["zip"] = $_POST['billing_Zip'];
        # SHIPPING INFO
        $myorder["sname"] = $_POST['shipping_Name'];
        $myorder["saddress1"] = $_POST['shipping_Address'];
        $myorder["saddress2"] = '';
        $myorder["scity"] = $_POST['shipping_City'];
        $myorder["sstate"] = $_POST['shipping_State'];
        $myorder["szip"] = $_POST['shipping_Zip'];
        $myorder["scountry"] = $_POST['shipping_Country'];
        $myorder["comments"] = "";
        $myorder["referred"] = "";
        $myorder["debugging"] = "false";

        $result = $mylphp->curl_process($myorder);  # use curl methods

        if ($result["r_approved"] != "APPROVED") {  // transaction failed, print the reason
            $error = '';
            if ($result["r_approved"] == "DECLINED" || $result["r_approved"] == "FRAUD")
                $error = "We're sorry, credit card declined.";
            else {
                $err = explode(":", $result["r_error"]);
                if (isset($err[0]) && isset($err[1])) {
                    switch ($err[0]) {
                        case "SGS-000001":
                            $error = "We're sorry, credit card declined.";
                            break;
                        default:
                            $error = $err[1];
                            if ($error == '')
                                $error = $err[0];
                            break;
                    }
                }
            }
            if ($error == '' || empty($error))
                $error = "We're sorry, credit card invalid.";
            return "incorrect^" . $error;
        }else { // success
            $this->r_ordernum = $result["r_ordernum"];
            $this->r_tdate = $result["r_tdate"];
            return $this->saveOrder();
        }
    }

    function saveOrder() {
        // $__email_to_get_order__ = 'nghiemhiep_18@yahoo.com';
        // $__signature__ = 'Bellavie Network.';

        $this->savingOrder();
        $mailcontent = $this->generateOrder();
        $mailcontent_client = str_replace("{mail_content}", "Dear " . ucfirst($_POST["billing_Name"]) . ' ' . ucfirst($_POST["billing_LastName"]) . "<br>" . $this->mail_body_client, $mailcontent);
        $this->lib->mail_simple($_POST['billing_Email'], "Order from Bellavie Network.", $this->lib->getMailInfor('site_info', 'email'), $this->lib->getMailInfor('site_info', 'signature'), $mailcontent_client);


        $mailcontent_admin = str_replace("{mail_content}", "Dear " . ucfirst($this->author->objlogin->firstname) . ' ' . ucfirst($this->author->objlogin->lastname) . "<br>" . $this->mail_body_admin, $mailcontent);
        $this->lib->mail_simple($this->lib->getMailInfor('site_info', 'email'), "Customer's order.", $_POST['billing_Email'], $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $mailcontent_admin);

        if ($this->check_donate)
            $strContent = $this->system->parse_templace("shop/payment_donate_complete.htm", $data = array(), true);
        else
            $strContent = $this->system->parse_templace("shop/payment_complete.htm", $data = array(), true);

        $strContent = str_replace('<!--name-->', '. ' . $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $strContent);
        $strContent = str_replace('<!--order_number-->', $this->okey, $strContent);
        $strContent = str_replace('{ck_commission}', ($this->check_commission == 1) ? $this->total_commission : '', $strContent);
        return $strContent;
    }

    function generateOrder() {
        $__email_to_get_order__ = 'nghiemhiep_18@yahoo.com';
        $__signature__ = 'Bellavie Network.';

        $check_order_voucher = true;
        $check_order_donate = true; //insert code
        for ($manu = 0; $manu < count($this->arr_Manufacturers); $manu++) {
            foreach ($this->arr_Manufacturers[$manu]['items'] as $item) {//1
                $arrKeys = ''; //insert code
                if ($this->check_donate) {
                    $arrKeys = explode(__keycode__, $item['key']);
                }else
                    $arrKeys = explode(__keycode__, $item['k']);

                if (count($arrKeys) == 0)
                    continue;
                if ($item['product_type'] == 0)
                    $check_order_voucher = false;
                break;
            }
            if (!$check_order_voucher || !$check_order_donate)//insert code
                break;
        }
        if ($this->check_donate) {
            $strContent = $this->system->parse_templace("shop/order_donate.htm", $data = array(), true);
        } elseif ($check_order_voucher)
            $strContent = $this->system->parse_templace("shop/order_mails_voucher.htm", $data = array(), true);
        else
            $strContent = $this->system->parse_templace("shop/order_mails.htm", $data = array(), true);

        $strContent = str_replace('<!--order_number-->', $this->okey, $strContent);
        $strContent = str_replace('<!--date-->', gmdate("m/d/Y"), $strContent);
        $strContent = str_replace('<!--billingName-->', $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $strContent);
        $strContent = str_replace('<!--billingAddress-->', $_POST["billing_Address"], $strContent);
        $strContent = str_replace('<!--billingCity-->', $_POST["billing_City"] . ', ' . $_POST["billing_State"] . ' ' . $_POST["billing_Zip"] . ', ' . $_POST['billing_Country'], $strContent);
        $strContent = str_replace('<!--billingPhone-->', $_POST["billing_Phone"], $strContent);
        $strContent = str_replace('<!--billingEmail-->', $_POST["billing_Email"], $strContent);
        $strContent = str_replace('<!--shippingName-->', $_POST["shipping_Name"], $strContent);
        $strContent = str_replace('<!--shippingAddress-->', $_POST["shipping_Address"], $strContent);
        $strContent = str_replace('<!--shippingCity-->', $_POST["shipping_City"] . ', ' . $_POST["shipping_State"] . ' ' . $_POST["shipping_Zip"] . ', ' . $_POST['shipping_Country'], $strContent);
        $strContent = str_replace('<!--shippingPhone-->', $_POST["shipping_Phone"], $strContent);
        $strContent = str_replace('<!--card_number-->', "XXXXX" . $this->card4digis, $strContent);

        $arr = $this->lib->partitionString("<!--startRows-->", "<!--endRows-->", $strContent);
        $strHeader = $arr[0];
        $strRow = $arr[1];
        $strFooter = $arr[2];
        $strRows = '';

        $locations = (isset($_SESSION['_CART']->locations) && is_array($_SESSION['_CART']->locations)) ? $_SESSION['_CART']->locations : array();
        $shipping_label = (isset($this->shipping_datas['label']) && $this->shipping_datas['label'] != '') ? $this->shipping_datas['label'] : '';

        $uid_com_monthly = 0;
        $akey = '';
        if ($this->roles['rid'] == 6 || $this->roles['rid'] == 9) {//representatives
            $uid_com_monthly = $this->uid;
        }
        $LevelSales = array();
        if ($uid_com_monthly > 0) {
            $commission_monthly = array(
                'uid' => $uid_com_monthly,
                'oid' => $this->order_number,
                'date_add' => gmdate("Y-m-d H:i:s")
            );
            $this->db->insert('commission_monthly', $commission_monthly);
            $LevelSales = $this->lib->__getLevelSale__($uid_com_monthly);
        }
        for ($manu = 0; $manu < count($this->arr_Manufacturers); $manu++) {
            $rows__ = '';
            $subtotal_manu = 0;
            $ship_rate = '';

            if (!$this->check_voucher && !$this->check_donate) {//insert code
                $ship_rate = $this->arr_Manufacturers[$manu]['handling_fee'];
            } else {
                $ship_rate = '';
            }
            $tax_manu = 0;

            $count_ship_free = count($this->arr_Manufacturers[$manu]['items']);
            foreach ($this->arr_Manufacturers[$manu]['items'] as $item) {//1
                $arrKeys = ''; //insert code 
                if ($this->check_donate) {
                    $arrKeys = explode(__keycode__, $item['key']);
                }else
                    $arrKeys = explode(__keycode__, $item['k']);
                if (count($arrKeys) == 0)
                    continue;

                $itm_key = $item['key'];
                $count = 0;
                $commission_charities = 0;
                $commission_trust_charity = 0;
                $commission_employees_bonus = 0;
                $credit_merchant = 0;
                $commission_member = 0;
                $commission_affiliate = 0;
                $markup_id = 0;
                $mkey = '';

                $current_cost = (is_numeric($item['current_cost']) && $item['current_cost'] > 0) ? (float) $item['current_cost'] : 0;
                $itm_price = $current_cost;
                $re_price = $this->db->query("select product_markup.* from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                if ($re_price->num_rows() > 0) {
                    $row_price = $re_price->row_array();
                    $markup_id = $row_price['id'];
                    $commission_charities = $row_price['commission_charities'];
                    $commission_trust_charity = $row_price['commission_trust_charity'];
                    $commission_employees_bonus = $row_price['commission_employees_bonus'];
                    $credit_merchant = $row_price['credit_merchant'];
                    $commission_member = $row_price['commission_member'];
                    $commission_affiliate = $row_price['commission_affiliate'];
                    $mkey = $row_price['mkey'];
                    $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? $row_price['markup_percentage'] : 0;
                    $itm_price = $itm_price + $itm_price * $markup_percentage / 100;
                }

                $file_ = $this->lib->__loadFileProduct__($item['itm_id'], 'thumb');
                $_filename = $file_['file'];

                $attributes = $this->get_attributes($arrKeys, $itm_key);

                $attributes_str = '';
                if (count($attributes) > 0) {
                    foreach ($attributes as $attri) {
                        $at_price = round($attri['price'], 2);
                        $current_cost += $at_price;
                        $itm_price += $at_price;
                        $attributes_str .= '<br><b>' . $attri['label'] . ': </b>' . $attri['name'];
                        if ($at_price > 0) {
                            $attributes_str .= '&nbsp;&nbsp;(+$' . number_format($at_price, 2) . ')';
                        }
                    }
                }
                $new_price = $itm_price = round($itm_price, 2);
                $new_current_cost = $current_cost = round($current_cost, 2);

                if ($this->check_donate) {//insert code 
                    $default_product_rate_ = '';
                }else
                    $default_product_rate_ = $item['default_product_rate'];

                $default_product_rate_last = $default_product_rate_current = round($default_product_rate_ * $item['sum'], 2);

                $promotions_ = '';
                $free_product_row = '';
				$check_shipping_free = false;
                $arr_show_promotions = array();
                if (count($this->arrPromotions) > 0) {
                    foreach ($this->arrPromotions as $promotions) {
                        if ($promotions['promo_type'] == 2 && $promotions['product_key'] == $itm_key) {
                            $bac_qty = floor($item['sum'] / $promotions['minqty']);
                            $qty_free = $bac_qty * $promotions['freeqty'];

                            $desc_free = '<div style="clear:both"><a href="' . $this->system->cleanUrl() . 'items/item&itmid=' . $promotions['itm_key'] . '" style="font-weight:bold">' . $promotions['itm_name'] . '</a><BR><br><b>Model: </b>' . $promotions['itm_model'] . '</div>';
                            $desc_free .= '<div style="clear:both; padding-top:10px">';
                            $desc_free .= '<table cellpadding="0" cellspacing="0" border="0">';
                            $desc_free .= '	<tr>';
                            $desc_free .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                            $desc_free .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Products</td>';
                            $desc_free .= '	</tr>';
                            $desc_free .= '</table>';
                            $desc_free .= '</div>';
                            $t_strList_ = $strRow;
                            $t_strList_ = str_replace("<!--img-->", $this->system->URL_server__() . '/data/img/thumb/' . $promotions['file'], $t_strList_);
                            $t_strList_ = str_replace("<!--Qty-->", number_format($qty_free), $t_strList_);
                            $t_strList_ = str_replace("<!--desc-->", $desc_free, $t_strList_);
                            $t_strList_ = str_replace("<!--price-->", '0.00', $t_strList_);
                            $t_strList_ = str_replace("<!--total-->", '0.00', $t_strList_);

                            $free_product_row .= $t_strList_;
                            $order_promotion = array(
                                'order_key' => $this->okey,
                                'promo_key' => $promotions['promo_code'],
                                'promo_type' => $promotions['promo_type'],
                                'product_key' => $promotions['product_key'],
                                'minqty' => $promotions['minqty'],
                                'freeqty' => $promotions['freeqty'],
                                'trigger_qty' => $bac_qty * $promotions['minqty'],
                                'result_qty' => $qty_free,
                                'manufacturer_id' => $promotions['uid'],
                                'itm_key' => $promotions['itm_key'],
                                'discount_type' => $promotions['discount_type'],
                                'discount' => $promotions['discount'],
                                'date_purchase' => date("Y-m-d H:i:s", $this->lib->getTimeGMT())
                            );
                            $this->db->insert('orders_promotions', $order_promotion);
                        }
                        if ($promotions['itm_key'] == $itm_key) {//0
                            switch ($promotions['promo_type']) {
                                case 1:
                                    $discount_str = '';
                                    if ($promotions['discount_type'] == 0) {
                                        $new_price -= $itm_price * $promotions['discount'] / 100;
                                        $new_current_cost -= $current_cost * $promotions['discount'] / 100;
                                        $discount_str = number_format($promotions['discount']) . '%';
                                    } else {
                                        $new_price -= round($promotions['discount'], 2);
                                        $discount_str = '$' . number_format($promotions['discount'], 2);
                                    }
                                    $promotions_ .= '<div style="clear:both; padding-top:10px">';
                                    $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                                    $promotions_ .= '	<tr>';
                                    $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                                    $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Product Discounts: ' . $discount_str . '</td>';
                                    $promotions_ .= '	</tr>';
                                    $promotions_ .= '</table>';
                                    $promotions_ .= '</div>';
                                    $order_promotion = array(
                                        'order_key' => $this->okey,
                                        'promo_key' => $promotions['promo_code'],
                                        'promo_type' => $promotions['promo_type'],
                                        'product_key' => $promotions['product_key'],
                                        'minqty' => $promotions['minqty'],
                                        'freeqty' => $promotions['freeqty'],
                                        'manufacturer_id' => $promotions['uid'],
                                        'itm_key' => $promotions['itm_key'],
                                        'discount_type' => $promotions['discount_type'],
                                        'discount' => $promotions['discount'],
                                        'date_purchase' => date("Y-m-d H:i:s", $this->lib->getTimeGMT())
                                    );
                                    $this->db->insert('orders_promotions', $order_promotion);
                                    break;
                                case 3:
                                    $check_shipping_free = true;
                                    $discount_str = '';
                                    if ($promotions['discount_type'] == 0) {
                                        $default_product_rate_last -= $default_product_rate_current * $promotions['discount'] / 100;
                                        $discount_str = number_format($promotions['discount']) . '%';
                                    } else {
                                        $default_product_rate_last -= round($promotions['discount'], 2);
                                        $discount_str = '$' . number_format($promotions['discount'], 2);
                                    }
                                    $promotions_ .= '<div style="clear:both; padding-top:10px">';
                                    $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                                    $promotions_ .= '	<tr>';
                                    $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                                    $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Shipping Discounts: ' . $discount_str . '</td>';
                                    $promotions_ .= '	</tr>';
                                    $promotions_ .= '</table>';
                                    $promotions_ .= '</div>';
                                    $order_promotion = array(
                                        'order_key' => $this->okey,
                                        'promo_key' => $promotions['promo_code'],
                                        'promo_type' => $promotions['promo_type'],
                                        'product_key' => $promotions['product_key'],
                                        'minqty' => $promotions['minqty'],
                                        'freeqty' => $promotions['freeqty'],
                                        'manufacturer_id' => $promotions['uid'],
                                        'itm_key' => $promotions['itm_key'],
                                        'discount_type' => $promotions['discount_type'],
                                        'discount' => $promotions['discount'],
                                        'date_purchase' => date("Y-m-d H:i:s", $this->lib->getTimeGMT())
                                    );
                                    $this->db->insert('orders_promotions', $order_promotion);
                                    break;
                                case 4:
                                    $check_ok = false;
                                    for ($i = 0; $i < count($promotions['countries']); $i++) {
                                        if ($promotions['countries'][$i]['code'] == $_POST["shipping_Country"]) {
                                            if (count($promotions['countries'][$i]['states']) > 0) {
                                                foreach ($promotions['countries'][$i]['states'] as $state_code) {
                                                    if ($state_code == $_POST["shipping_State"]) {
                                                        $check_ok = true;
                                                    }
                                                }
                                            } else {
                                                $check_ok = true;
                                            }
                                            break;
                                        }
                                    }
                                    if ($check_ok == true) {
                                        $default_product_rate_last = 0;
                                        $check_shipping_free = true;
                                        $promotions_ .= '<div style="clear:both; padding-top:10px">';
                                        $promotions_ .= '<table cellpadding="0" cellspacing="0" border="0">';
                                        $promotions_ .= '	<tr>';
                                        $promotions_ .= '		<td align="left" valign="top"><img src="' . $this->system->__path_to_theme__() . '/images/ico-gift.png" border="0" width="20px" /></td>';
                                        $promotions_ .= '		<td align="left" valign="top" style="padding-left:10px; font-weight:bold; font-size:16px; color:#D2D2D2">Free Shippings</td>';
                                        $promotions_ .= '	</tr>';
                                        $promotions_ .= '</table>';
                                        $promotions_ .= '</div>';

                                        $order_promotion = array(
                                            'order_key' => $this->okey,
                                            'promo_key' => $promotions['promo_code'],
                                            'promo_type' => $promotions['promo_type'],
                                            'product_key' => $promotions['product_key'],
                                            'minqty' => $promotions['minqty'],
                                            'freeqty' => $promotions['freeqty'],
                                            'manufacturer_id' => $promotions['uid'],
                                            'itm_key' => $promotions['itm_key'],
                                            'discount_type' => $promotions['discount_type'],
                                            'discount' => $promotions['discount'],
                                            'date_purchase' => date("Y-m-d H:i:s", $this->lib->getTimeGMT())
                                        );
                                        $this->db->insert('orders_promotions', $order_promotion);
                                    }
                                    break;
                            }
                        }//0
                    }
                }
                if ($new_price < 0)
                    $new_price = 0;
                $new_price = round($new_price, 2);
                $price_vs = round($new_price * $item['sum'], 2);

                if ($new_current_cost < 0)
                    $new_current_cost = 0;
                $new_current_cost = round($new_current_cost, 2);

                $origin = '';
                if ($item['origin'] != '' && $item['origin'] != null) {
                    $origin = '<br>' . $this->lib->ConvertToHtml($item['origin']);
                }

                $price_show = number_format($itm_price, 2);
                if ($new_price != $itm_price)
                    $price_show = number_format($new_price, 2) . '<br><span style="text-decoration:line-through">$' . number_format($itm_price, 2) . '</span>';

                $location_st = '';
                if (count($locations) > 0) {
                    $location_st .= '<div style="clear:both; padding-top:5px"><b>Locations:</b>';
                    foreach ($locations as $arr_location) {
                        if (count($arr_location) > 0) {
                            foreach ($arr_location as $item_location) {
                                if ($item_location['attributes'] == $itm_key) {
                                    $location_st .= "<br>" . $item_location['location'] . " ( x " . $item_location['quantity'] . ")";
                                }
                            }
                        }
                    }
                    $location_st .= '</div>';
                }

                $desc_ = '<div style="clear:both"><a href="' . $this->system->cleanUrl() . 'items/item&itmid=' . $itm_key . '" style="font-weight:bold">' . $item["itm_name"] . '</a><BR><b>Model: </b>' . $item["itm_model"] . $origin . $attributes_str . '</div>' . $promotions_ . $location_st;

                $t_strList = $strRow;
                $t_strList = str_replace("@img@", $this->system->URL_server__() . '/shopping/data/img/thumb/' . $_filename, $t_strList);
                $t_strList = str_replace("<!--Qty-->", number_format($item['sum']), $t_strList);
                $t_strList = str_replace("<!--desc-->", $desc_, $t_strList);
                $t_strList = str_replace("<!--price-->", $price_show, $t_strList);
                $t_strList = str_replace("<!--total-->", number_format($price_vs, 2), $t_strList);
                $strRows .= $t_strList;
                $strRows .= $free_product_row;

                // Row for manufactures
                $desc_ = '<div style="clear:both"><a href="' . $this->system->cleanUrl() . 'items/item&itmid=' . $itm_key . '" style="font-weight:bold">' . $item["itm_name"] . '</a><BR><b>Model: </b>' . $item["itm_model"] . $origin . $attributes_str . '</div>' . $location_st;
                $price_manu = round($new_current_cost * $item['sum'], 2);
                $t_strList = $strRow;
                $t_strList = str_replace("@img@", $this->system->URL_server__() . '/shopping/data/img/thumb/' . $_filename, $t_strList);
                $t_strList = str_replace("<!--Qty-->", number_format($item['sum']), $t_strList);
                $t_strList = str_replace("<!--desc-->", $desc_, $t_strList);
                $t_strList = str_replace("<!--price-->", number_format($current_cost, 2), $t_strList);
                $t_strList = str_replace("<!--total-->", number_format($price_manu, 2), $t_strList);

                $this->arr_Manufacturers[$manu]['rows'] .= $t_strList;

                $subtotal_manu += $price_manu;
                if ($item['product_type'] == 1 || $item['product_type'] == 2) {
                    $default_product_rate_ = 0;
                    $default_product_rate_current = 0;
                    $default_product_rate_last = 0;
                    $count_ship_free--;
                } else {
                    if ($default_product_rate_last <= 0) {
                        $default_product_rate_last = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                }

                if ($this->check_donate) //insert code
                    $item['tax_persen'] = 0;

                $tax_manu += $item['tax_persen'] * $price_manu / 100;
          
                $ship_rate += $default_product_rate_current;

                $odetail = $this->db->insert(
                        'order_detais', array(
                    'orderid' => $this->order_number,
                    'itemid' => $item['itm_id'],
                    'itemprice' => $new_price,
                    'last_itemprice' => $itm_price,
                    'current_cost' => $new_current_cost,
                    'last_cost' => $current_cost,
                    'quality' => $item['sum'],
                    'shipping_fee' => (is_numeric($default_product_rate_last) && $default_product_rate_last > 0) ? $default_product_rate_last : 0,
                    'last_shipping' => (is_numeric($default_product_rate_) && $default_product_rate_ > 0) ? $default_product_rate_ : 0,
                    'tax_persend' => $item['tax_persen']
                        )
                );
                $odetail = $this->db->insert_id();
                if ($this->check_voucher($item['itm_id'])) {
                    for ($it = 0; $it < (int) $item['sum']; $it++) {
                        $voucher_id = $this->lib->GeneralRandomNumberKey(10);
                        $re_key = $this->db->query("select voucher_id from voucher where voucher_id = '$voucher_id'");
                        foreach ($re_key->result_array() as $row_key) {
                            $voucher_id = $this->lib->GeneralRandomNumberKey(10);
                            $re_key = $this->db->query("select voucher_id from voucher where voucher_id = '$voucher_id'");
                        }
                        $vc_id = $this->db->insert(
                                'voucher', array(
                            'voucher_id' => $voucher_id,
                            'item_id' => $item['itm_id'],
                            'member_id' => $this->author->objlogin->name,
                            'order_id' => $this->order_number
                                )
                        );

                        $mailcontent = $this->generateOrderVoucher($voucher_id, $item['itm_id']);

                        $mailcontent_client = str_replace("{mail_content}", "Dear " . ucfirst($_POST["billing_Name"]) . ' ' . ucfirst($_POST["billing_LastName"]) . "<br>" . $this->mail_body_client, $mailcontent);
                        $this->lib->mail_simple($_POST['billing_Email'], "Order from Bellavie Network.", $this->lib->getMailInfor('site_info', 'email'), $this->lib->getMailInfor('site_info', 'signature'), $mailcontent_client);

                        $mailcontent_admin = str_replace("{mail_content}", "Dear " . ucfirst($this->author->objlogin->firstname) . ' ' . ucfirst($this->author->objlogin->lastname) . "<br>" . $this->mail_body_admin, $mailcontent);
                        $this->lib->mail_simple($this->lib->getMailInfor('site_info', 'email'), "Customer's order.", $_POST['billing_Email'], $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $mailcontent_admin);
                    }
                }

                $inventories = $this->database->db_result("select inventories from items where itm_id = " . $item['itm_id']);
                if (is_numeric($inventories)) {
                    $inventories = $inventories - $item['sum'];
                    $this->db->update("items", array('inventories' => $inventories), "itm_id = " . $item['itm_id']);
                }

                if (count($attributes) > 0) {
                    $dem__ = 0;
                    foreach ($attributes as $attri) {
                        $attri['odetail'] = $odetail;
                        $attri['weight'] = -$dem__;
                        $this->db->insert('orders_attributes', $attri);
                        $dem__++;
                    }
                }
                //-----Save commission -----//
                if ($uid_com_monthly > 0) {
                    // member comission
                    $re_com_sale = $this->db->query("select commission from commission_salerep_items where item_key = '$itm_key'");
                    if ($re_com_sale->num_rows() > 0) {
                        $row_com_sale = $re_com_sale->row_array();
                        if ($row_com_sale['commission'] != null && $row_com_sale['commission'] != '') {
                            $commission_sale = explode("|", $row_com_sale['commission']);
                            for ($cs = 0; $cs < count($LevelSales); $cs++) {
                                if ($LevelSales[$cs]['status'] == 1) {
                                    if (isset($commission_sale[$cs]) && is_numeric($commission_sale[$cs]) && $commission_sale[$cs] > 0) {

                                        $commission_monthly_items = array(
                                            'uid' => $LevelSales[$cs]['uid'],
                                            'upurchase' => $uid_com_monthly,
                                            'oid' => $this->order_number,
                                            'odetail' => $odetail,
                                            'commission' => $commission_sale[$cs],
                                            'purchase_date' => date("Y-m-d H:i:s", $this->lib->getTimeGMT())
                                        );
                                        $this->db->insert('commission_monthly_items', $commission_monthly_items);
                                    }
                                }
                            }
                        }
                    }
                    // Personal Discount
                    $commission_monthly_items = array(
                        'uid' => $uid_com_monthly,
                        'upurchase' => $uid_com_monthly,
                        'oid' => $this->order_number,
                        'odetail' => $odetail,
                        'commission' => $commission_member,
                        'purchase_date' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
                        'status' => 1,
                        'personal_discount' => 1
                    );
                    $this->db->insert('commission_monthly_items', $commission_monthly_items);
                }
                // Commission Charity

                if ($commission_charities > 0 && count($this->arrCharities_notruct) > 0) {
                    $count_charities = count($this->arrCharities_notruct);
                    foreach ($this->arrCharities_notruct as $charity_key) {
                        $data_commission = array(
                            'orderid' => $this->order_number,
                            'pkey' => $itm_key,
                            'mkey' => $mkey,
                            'commission' => round($commission_charities / $count_charities, 10),
                            'purchase_date' => gmdate("Y-m-d H:i:s"),
                            'odetail' => $odetail,
                            'rid' => 8,
                            'legal_business_id' => $charity_key
                        );
                        $this->db->insert('commission_charities', $data_commission);
                    }
                }
                if ($commission_trust_charity > 0 && count($this->arrCharities_truct) > 0) {
                    $count_charities = count($this->arrCharities_truct);
                    foreach ($this->arrCharities_truct as $charity_key) {
                        $data_commission = array(
                            'orderid' => $this->order_number,
                            'pkey' => $itm_key,
                            'mkey' => $mkey,
                            'commission' => round($commission_trust_charity / $count_charities, 10),
                            'purchase_date' => gmdate("Y-m-d H:i:s"),
                            'odetail' => $odetail,
                            'rid' => 8,
                            'legal_business_id' => $charity_key
                        );
                        $this->db->insert('commission_charities', $data_commission);
                    }
                }
                // Commission employees
                if ($commission_employees_bonus > 0) {
                    $data_commission = array(
                        'orderid' => $this->order_number,
                        'pkey' => $itm_key,
                        'mkey' => $mkey,
                        'commission' => $commission_employees_bonus,
                        'purchase_date' => gmdate("Y-m-d H:i:s"),
                        'odetail' => $odetail,
                        'rid' => 0
                    );
                    $this->db->insert('commission_charities', $data_commission);
                }
                //credit merchant
                if ($credit_merchant > 0) {
                    $data_commission = array(
                        'orderid' => $this->order_number,
                        'pkey' => $itm_key,
                        'mkey' => $mkey,
                        'commission' => $credit_merchant,
                        'purchase_date' => gmdate("Y-m-d H:i:s"),
                        'odetail' => $odetail,
                        'rid' => -1
                    );
                    $this->db->insert('commission_charities', $data_commission);
                }
                if ($akey != '') {//0
                    //commission affiliate
                    $data_commissoin_aff = array(
                        'akey' => $akey,
                        'orderid' => $this->order_number,
                        'pkey' => $itm_key,
                        'mkey' => $mkey,
                        'commission' => $commission_affiliate,
                        'purchase_date' => gmdate("Y-m-d H:i:s"),
                        'odetail' => $odetail,
                        'rid' => 6,
                        'status' => 0
                    );
                    $this->db->insert('commission_affiliate', $data_commissoin_aff);
                }//0
            }//1

            if ($this->check_donate) {//insert code 
                $ship_rate = 0;
            } else
            if ($count_ship_free == 0 && $ship_rate == $this->arr_Manufacturers[$manu]['handling_fee']) {
                $ship_rate = 0;
            }
            $this->arr_Manufacturers[$manu]['shipping'] = round($ship_rate, 2);
            $this->arr_Manufacturers[$manu]['tax'] = round($tax_manu, 2);
            $this->arr_Manufacturers[$manu]['subtotal'] = round($subtotal_manu, 2);
        }
        for ($manu = 0; $manu < count($this->arr_Manufacturers); $manu++) {
            $strFooter_ = $strFooter;
            $total_price = $this->arr_Manufacturers[$manu]['subtotal'] + $this->arr_Manufacturers[$manu]['tax'] + $this->arr_Manufacturers[$manu]['shipping'];

            $strFooter_ = str_replace("<!--suptotal-->", number_format($this->arr_Manufacturers[$manu]['subtotal'], 2), $strFooter_);
            $strFooter_ = str_replace("{order_total}", number_format($total_price, 2), $strFooter_);
            $strFooter_ = str_replace('<!--Tax-->', number_format($this->arr_Manufacturers[$manu]['tax'], 2), $strFooter_);
            $strFooter_ = str_replace('<!--ship_label-->', $shipping_label, $strFooter_);
            $strFooter_ = str_replace('<!--shipping_fee-->', number_format($this->arr_Manufacturers[$manu]['shipping'], 2), $strFooter_);

            $this->unshowMyWalleAccount($strFooter_);

            $mail_content = $strHeader . $this->arr_Manufacturers[$manu]['rows'] . $strFooter_;
            $mail_content = str_replace("{mail_content}", "Dear " . $this->arr_Manufacturers[$manu]['firstname'] . ' ' . $this->arr_Manufacturers[$manu]['lastname'] . "<br>" . $this->mail_body_admin, $mail_content);
            if (isset($this->arr_Manufacturers[$manu]['receive_order_mail']) && $this->arr_Manufacturers[$manu]['receive_order_mail'] == 1) {
                $this->lib->mail_simple($this->arr_Manufacturers[$manu]['mail'], "Customer's order.", $_POST['billing_Email'], $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $mail_content);
            }
            if (isset($this->arr_Manufacturers[$manu]['account']) && is_array($this->arr_Manufacturers[$manu]['account']) && count($this->arr_Manufacturers[$manu]['account']) > 0) {
                foreach ($this->arr_Manufacturers[$manu]['account'] as $mail_employees) {
                    $this->lib->mail_simple($mail_employees, "Customer's order.", $_POST['billing_Email'], $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $mail_content);
                }
            }
        }
        $strFooter = str_replace("<!--suptotal-->", number_format($this->suptotal, 2), $strFooter);
        $strFooter = str_replace("{order_total}", number_format($this->ordertotal, 2), $strFooter);
        $strFooter = str_replace('<!--Tax-->', number_format($this->tax, 2), $strFooter);
        $strFooter = str_replace('<!--ship_label-->', $shipping_label, $strFooter);
        $strFooter = str_replace('<!--shipping_fee-->', number_format($this->shippingfee, 2), $strFooter);

        $this->loadMyWalleAccount($strFooter);

        unset($_SESSION['_CART']);
        unset($_SESSION['__manufacturers__']);
        return $strHeader . $strRows . $strFooter;
    }

    private function getArrCharities() {
        $query = $this->db->query("select charities.legal_business_id,charities.trust from charities join users on charities.uid = users.uid where users.status = 1");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($row['trust'] == 1)
                    $this->arrCharities_truct[] = $row['legal_business_id'];
                else
                    $this->arrCharities_notruct[] = $row['legal_business_id'];
            }
        }
    }

    public function generateOrderVoucher($vid, $itm_id) {
        if (trim($vid) == '' || trim($itm_id) == '' || trim($this->author->objlogin->name) == '')
            return;

        $main_number = 0;
        $sub_number = 0;
        $voucher_price = 0;
        $voucher_expire = 0;
        $voucher_title = '';
        $voucher_content = '';
        $voucher_origin = '';
        $expiration_date = 0;
        $expiration_date_unit = 1;
        $unit = '';
        $member_id = $this->author->objlogin->name;
        $url_img = $this->system->URL_server__() . '/data/img/thumb_show/';

        $voucher_price = $this->database->db_result("select itemprice from order_detais where itemid = $itm_id");
        if (is_numeric($voucher_price) && $voucher_price > 0) {
            $arr = explode('.', $voucher_price);
            if (isset($arr[0]))
                $main_number = $arr[0];
            if (isset($arr[1]))
                $sub_number = $arr[1];
        }

        $url_file = $this->database->db_result("select filename from items_files where tid = $itm_id");
        $url_img .= $url_file;

        $re = $this->db->query("select expiration_date, expiration_date_unit, itm_name,origin, itm_description from items where itm_id = $itm_id");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $expiration_date = $row['expiration_date'];
            $voucher_title = $row['itm_name'];
            $voucher_origin = $row['origin'];
            $expiration_date_unit = $row['expiration_date_unit'];
            $voucher_content = $this->lib->SQLToFCK($row['itm_description']);
        }
        $today = $this->lib->getTimeGMT();
        switch ($expiration_date_unit) {
            case 1:
                $unit = 'day';
                break;
            case 30:
                $unit = 'month';
                break;
            case 365:
                $unit = 'year';
                break;
        }
        $new_date = strtotime('+ ' . $expiration_date . ' ' . $unit, $today);
        $voucher_expire = date('M, jS Y', $new_date);
        $voucher_expire_text = $voucher_expire;

        $voucher_content_text = '<div style="left:10px;">' . $voucher_content . '</div>';
        $strContent = $this->system->parse_templace("shop/vouchers.htm", $data = array(), true);

        $strContent = str_replace("<!--date-->", gmdate("M, jS Y"), $strContent);
        $strContent = str_replace("<!--member_id-->", $this->author->objlogin->name, $strContent);
        $strContent = str_replace("<!--voucher_id-->", $vid, $strContent);
        $strContent = str_replace("<!--main_number-->", $main_number, $strContent);
        $strContent = str_replace("<!--sub_number-->", '.' . $sub_number, $strContent);
        $strContent = str_replace("<!--voucher_title-->", $voucher_title, $strContent);
        $strContent = str_replace("<!--url_img-->", $url_img, $strContent);
        $strContent = str_replace("<!--voucher_expire-->", $voucher_expire_text, $strContent);
        $strContent = str_replace("<!--voucher_content-->", $voucher_content_text, $strContent);
        $strContent = str_replace("<!--voucher_origin-->", $voucher_origin, $strContent);

        return $strContent;
    }

    function check_order_voucher() {
        $check = true;
        $re = $this->db->query("select order_detais.itemid from order_detais where order_detais.orderid = '" . $this->order_number . "' ");
        foreach ($re->result() as $row) {
            $product_type = $this->database->db_result("select product_type from items where itm_id = '" . $row['itemid'] . "'");
            if ($product_type != 2) {
                $check = false;
                break;
            }
        }
        return $check;
    }

    function check_voucher($itm_id) {
        $check = false;
        $re = $this->db->query("select product_type from items where itm_id = '$itm_id'");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            if ($row['product_type'] == 2)
                $check = true;
        }
        return $check;
    }

    function get_attributes($arrKeys, $itm_key) {
        $attributes = array();
        for ($i = 1; $i < count($arrKeys); $i++) {//3
            $arr_attribute = array(
                'label' => '',
                'name' => '',
                'price' => 0
            );
            $arr_textfi = explode(__keyat__, $arrKeys[$i]);
            if (count($arr_textfi) == 2) {
                if ($arr_textfi[1] == '')
                    continue;

                $re_label = $this->db->query("select label from items_attributes where akey = '" . $arr_textfi[0] . "' and pkey = '$itm_key'");
                if ($re_label->num_rows() > 0) {
                    $row_label = $re_label->row_array();
                    $arr_attribute['label'] = $row_label['label'];
                } else {
                    $arr_attribute['label'] = $this->database->db_result("select label from attributes where status <> -1 and akey = '" . $arr_textfi[0] . "'");
                }
                $arr_attribute['name'] = $arr_textfi[1];
            } elseif (count($arr_textfi) == 1) {
                $arr_attribute['label'] = $this->database->db_result("select items_attributes.label from items_attributes join attrioptions on items_attributes.akey = attrioptions.akey where items_attributes.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                $re_pri = $this->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                if ($re_pri->num_rows() > 0) {
                    $row_pri = $re_pri->row_array();
                    $arr_attribute['name'] = $row_pri['name'];
                    $arr_attribute['price'] = $row_pri['price'];
                }
            }
            $attributes[] = $arr_attribute;
        }//3
        return $attributes;
    }

    function unshowMyWalleAccount(&$strFooter) {
        $arr_ = $this->lib->partitionString("{myWalle_account}", "{/myWalle_account}", $strFooter);
        $strFooter = $arr_[0] . $arr_[2];
    }

    function loadMyWalleAccount(&$strFooter) {
        $arr_ = $this->lib->partitionString("{myWalle_account}", "{/myWalle_account}", $strFooter);
        $myWalle_account = '';
        if ($this->check_commission == 1 && $this->total_commission > 0) {
            $myWalle_account = $arr_[1];
            $myWalle_account = str_replace("{myWalle_account}", number_format($this->total_commission, 2), $myWalle_account);
            $myWalle_account = str_replace("{total_last}", number_format($this->ordertotal - $this->total_commission, 2), $myWalle_account);
        }
        $strFooter = $arr_[0] . $myWalle_account . $arr_[2];
    }

    function saveOrderDelivery() {
        if (isset($_POST['schedule_delivery']) && is_array($_POST['schedule_delivery']) && count($_POST['schedule_delivery']) > 0) {
            foreach ($_POST['schedule_delivery'] as $date) {
                $data = array(
                    'oid' => $this->order_number,
                    'schedule_date' => $date
                );
                $this->db->insert('orders_auto_delivery', $data);
            }
        }
    }

    function savingOrder() {
        $handling_fee = '';
        if (!$this->check_voucher && !$this->check_donate) {
            $handling_fee = $this->shipping_datas['handling_fee'];
        } else {
            $handling_fee = '';
        }
        $shipping_key = (isset($this->shipping_datas['skey']) && $this->shipping_datas['skey'] != '') ? $this->shipping_datas['skey'] : '';

        $last_general_settup = $this->general->getLastGeneralSetting();

        $data = array(
            'r_ordernum' => $this->r_ordernum,
            'r_tdate' => $this->r_tdate,
            'okey' => $this->okey,
            "shipping_name" => $this->lib->escape($_POST["shipping_Name"]),
            "shipping_address" => $this->lib->escape($_POST["shipping_Address"]),
            "shipping_city" => $this->lib->escape($_POST["shipping_City"]),
            "shipping_state" => $this->lib->escape($_POST["shipping_State"]),
            "shipping_zip" => $this->lib->escape($_POST["shipping_Zip"]),
            "shipping_country" => $this->lib->escape($_POST["shipping_Country"]),
            "shipping_phone" => $this->lib->escape($_POST["shipping_Phone"]),
            "billing_name" => $this->lib->escape($_POST["billing_Name"] . ' ' . $_POST["billing_LastName"]),
            "billing_address" => $this->lib->escape($_POST["billing_Address"]),
            "billing_city" => $this->lib->escape($_POST["billing_City"]),
            "billing_state" => $this->lib->escape($_POST["billing_State"]),
            "billing_country" => $this->lib->escape($_POST['billing_Country']),
            "billing_zip" => $this->lib->escape($_POST["billing_Zip"]),
            "billing_phone" => $this->lib->escape($_POST["billing_Phone"]),
            "billing_email" => $this->lib->escape($_POST["billing_Email"]),
            "order_tax" => $this->tax_persen,
            "shipping_fee" => $handling_fee, //$this->shipping_datas['handling_fee'],
            "shipping_key" => $shipping_key,
            "order_total" => $this->ordertotal,
            "order_date" => gmdate("Y-m-d H:i:s"),
            "user_id" => $this->uid,
            "card_number" => $this->card4digis,
            "customerPaymentProfileId" => $this->customerPaymentProfileId,
            "customerProfileId" => $this->customerProfileId,
            "customerAddressId" => $this->customerAddressId,
            "cc_month" => is_numeric($_POST['cc_Card_Month']) ? $_POST['cc_Card_Month'] : 0,
            "cc_year" => is_numeric($_POST['cc_Card_Year']) ? $_POST['cc_Card_Year'] : 0,
            'com_set_id' => $last_general_settup['id']
        );
        $this->db->insert('orders', $data);
        $this->order_number = $this->db->insert_id();
//		$this->saveOrderDelivery();
        if (!$this->check_donate) {
            if (!$this->check_voucher) {
                $this->saveOrderHandling();
                $this->saveOrderLocations();
            }
        }

        $this->savePayments();
        $this->activeAccount();
        return $this->order_number;
    }

    function saveOrderLocations() {
        $locations = (isset($_SESSION['_CART']->locations) && is_array($_SESSION['_CART']->locations)) ? $_SESSION['_CART']->locations : array();
        if (count($locations) > 0) {
            foreach ($locations as $items) {
                if (count($items) > 0) {
                    foreach ($items as $item) {
                        $orders_locations = array(
                            'item_key' => $item['attributes'],
                            'qty' => $item['quantity'],
                            'locations' => $item['location'],
                            'oid' => $this->order_number
                        );
                        $this->db->insert("orders_locations", $orders_locations);
                    }
                }
            }
        }
    }

    function savePayments() {
        if ($this->total_commission > 0) {
            $legal_business_id = $this->sale_rep_obj->getLegalBusinessID();
            $legal_business_name = $this->sale_rep_obj->getLegalBusinessName();
            $pay_key = $this->lib->GeneralRandomKey(20);
            $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
            foreach ($re_key->result_array() as $row_key) {
                $pay_key = $this->lib->GeneralRandomKey(20);
                $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
            }
            $datas = array(
                'pkey' => $pay_key,
                'role' => 9,
                'legal_business_id' => $legal_business_id,
                'legal_business_name' => $legal_business_name,
                'pay' => $this->total_commission,
                'date_pay' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
                'pay_type' => 1
            );
            $id = $this->db->insert("payments", $datas);
            if ($id) {
                $payments_orders = array(
                    'pkey' => $pay_key,
                    'okey' => $this->okey,
                    'pay' => $this->total_commission
                );
                $this->db->insert("payments_orders", $payments_orders);
                //}
            }
        }
    }

    function saveOrderHandling() {
        for ($m = 0; $m < count($this->arr_Manufacturers); $m++) {//0
            $orders_handling = array(
                'oid' => $this->order_number,
                'uid' => $this->arr_Manufacturers[$m]['uid'],
                'handling' => $this->arr_Manufacturers[$m]['handling_fee']
            );
            $this->db->insert('orders_handling', $orders_handling);
        }
    }

    function activeAccount() {
        $this->db->update('representatives', array('purchase_active' => 1), "uid = " . $this->uid);
    }

    function CreateNewAccount() {
        $user_randomkey = $this->lib->GeneralRandomKey(20);
        $re = $this->db->query("select uid from users where ukey = '$user_randomkey'");
        foreach ($re->result_array() as $user_randomkey) {
            $user_randomkey = $this->lib->GeneralRandomKey(20);
            $re = $this->db->query("select uid from users where ukey = '$user_randomkey'");
        }
        $mail = isset($_SESSION['__new_account__']['mail']) ? $_SESSION['__new_account__']['mail'] : '';
        $arr_mail = explode("@", $mail);
        $user_name = $name = (isset($arr_mail[0]) && $arr_mail[0] != '') ? $arr_mail[0] : '';
        while (check_name_exists($user_name) > 0) {
            $user_name = $name . rand(1000);
        }
        $pass = $this->lib->GeneralRandomNumberKey(8);
        $data = array(
            'ukey' => $user_randomkey,
            'name' => $user_name,
            'pass' => $this->system->encode_password($pass),
            'mail' => $mail,
            'firstname' => isset($_SESSION['__new_account__']['firstname']) ? $this->lib->escape($_SESSION['__new_account__']['firstname']) : '',
            'lastname' => isset($_SESSION['__new_account__']['lastname']) ? $this->lib->escape($_SESSION['__new_account__']['lastname']) : '',
            'phone' => isset($_SESSION['__new_account__']['phone']) ? $this->lib->escape($_SESSION['__new_account__']['phone']) : '',
            'created' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
            'access' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
            'login' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
            'status' => 1
        );
        $user_id = $this->db->insert('users', $data);
        if (is_numeric($user_id) && $user_id > 0) {
            $variables_ = array(
                '!username' => $this->lib->escape($user_name),
                '!password' => $pass
            );
            $this->lib->sendmailtype($mail, __no_approval_required__, $variables_);
            $users_roles = array('uid' => $user_id, 'rid' => 2);
            $this->db->insert('users_roles', $users_roles);
            $obj_login = new login_class();
            if ($obj_login->checkLogin($user_name, $pass)) {
                $this->author->objlogin = $obj_login;
                $this->uid = $this->author->objlogin->uid;
                if ($this->uid > 0)
                    $this->roles = $this->author->loadRole($this->uid);
            }
        }
        return false;
    }

    function loadTax() {
        $re = $this->db->query("select * from tax_rates where status <> -1");
        foreach ($re->result_array() as $row) {
            if ($row['state'] != '' && $row['state'] != trim($_POST["shipping_State"]))
                continue;
            $this->arr_tax[] = $row;
        }
    }

    function loadItemsShippingsAndPromotions() {
        foreach ($_SESSION['_CART']->items as $k => $vs) {//0
            $arrKeys = explode(__keycode__, $k);
            if (count($arrKeys) > 0) {//1
                $itm_key = $arrKeys[0];

                $this->lib->loadPromotionsObject($this->arrPromotions, $itm_key, $vs);

                $re = $this->db->query("select * from items_shippings where pkey = '$itm_key' and skey = '" . $_POST['shipping_key'] . "'");
                foreach ($re->result_array() as $row) {
                    $countries = array();
                    $re_2 = $this->db->query("select * from items_shippings_countries where sid = " . $row['id']);
                    foreach ($re_2->result_array() as $row_2) {
                        $states = array();
                        $re_3 = $this->db->query("select * from items_shippings_states where country_id = " . $row_2['id']);
                        foreach ($re_3->result_array() as $row_3) {
                            $states[] = $row_3;
                        }
                        $row_2['states'] = $states;
                        $countries[] = $row_2;
                    }
                    $row['countries'] = $countries;
                    $this->arr_items_shippings[] = $row;
                }
            }//1
        }//0	
    }

    function loadShippingData() {
        if (isset($_POST['shipping_key']) && $_POST['shipping_key'] != '') {
            $re = $this->db->query("select * from shipping_rates where status = 1 and skey = '" . $_POST['shipping_key'] . "'");
            if ($re->num_rows() > 0) {
                $row = $re->row_array();
                $this->shipping_datas = $row;
            }
        }
    }

    function updateHandlingFee_for_Manufacturer() {
        for ($m = 0; $m < count($this->arr_Manufacturers); $m++) {//0
            $handling_fee_new = $this->shipping_datas['handling_fee'];
            foreach ($this->arr_Manufacturers[$m]['items'] as $item) {//1
                for ($i = 0; $i < count($this->arr_items_shippings); $i++) {
                    if ($this->arr_items_shippings[$i]['pkey'] == $item['key'] && $this->arr_items_shippings[$i]['skey'] == $_POST['shipping_key']) {
                        if ($this->arr_items_shippings[$i]['handling'] >= 0) {
                            if ($handling_fee_new < $this->arr_items_shippings[$i]['handling'])
                                $handling_fee_new = $this->arr_items_shippings[$i]['handling'];
                        }
                        break;
                    }
                }
            }
            $this->arr_Manufacturers[$m]['handling_fee'] = $handling_fee_new;
        }
    }

    function checkItemAvailable_donate() {
        $this->loadManufacturers_donate();
        for ($m = 0; $m < count($this->arr_Manufacturers); $m++) {//0
            for ($it = 0; $it < count($this->arr_Manufacturers[$m]['items']); $it++) {
                $item = $this->arr_Manufacturers[$m]['items'][$it];
                $itm_key = $item['key'];
                $amount = (is_numeric($item['item_price']) && $item['item_price'] > 0) ? round($item['item_price']) : 0;
                $amount_last = $amount;

                if ($amount_last < 0)
                    $amount_last = 0;
                $amount_last = round($amount_last, 2);
                $total_amount_last = round($amount_last * $item['sum'], 2);

                $this->suptotal += $total_amount_last;
            }//1
        }//0

        if ($this->suptotal < 0)
            $this->suptotal = 0;
        $this->suptotal = round($this->suptotal, 2);

        $this->ordertotal = round($this->suptotal, 2);

        $this->pay_last = $this->ordertotal;
        return $this->ordertotal;
    }

    function checkItemAvailable() {
        if ($this->check_voucher || $this->check_donate) {//insert code 
            $this->loadTax();
            $this->loadItemsShippingsAndPromotions();
            $this->loadManufacturers();
        } else {
            $this->loadTax();
            $this->loadItemsShippingsAndPromotions();
            $this->loadShippingData();
            $this->loadManufacturers();
            $this->updateHandlingFee_for_Manufacturer();
        }
        for ($m = 0; $m < count($this->arr_Manufacturers); $m++) {//0
            if ($this->check_voucher || $this->check_donate) { //insert code
                $ship_rate = 0;
                $count_ship_free = 0;
            } else {
                $ship_rate = $this->arr_Manufacturers[$m]['handling_fee'];
                $count_ship_free = count($this->arr_Manufacturers[$m]['items']);
            }
            for ($it = 0; $it < count($this->arr_Manufacturers[$m]['items']); $it++) {
                $item = $this->arr_Manufacturers[$m]['items'][$it];
                $itm_key = $item['key'];
                $arrKeys = explode(__keycode__, $item['k']);

                $default_product_rate_last = $default_product_rate_current = round($item['default_product_rate'] * $item['sum'], 2);

                $check_shipping_free = false;

                $amount = (is_numeric($item['current_cost']) && $item['current_cost'] > 0) ? round($item['current_cost']) : 0;
                $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                if ($re_price->num_rows() > 0) {
                    $row_price = $re_price->row_array();
                    $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? $row_price['markup_percentage'] : 0;
                    $amount += round($amount * $markup_percentage / 100, 2);
                }

                for ($i = 1; $i < count($arrKeys); $i++) {
                    $arr_textfi = explode(__keyat__, $arrKeys[$i]);
                    if (count($arr_textfi) == 1) {
                        $re_pri = $this->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                        if ($re_pri->num_rows() > 0) {
                            $row_pri = $re_pri->row_array();
                            $amount +=round($row_pri['price'], 2);
                        }
                    }
                }
                $amount_last = $amount;

                if (count($this->arrPromotions) > 0) {//3
                    foreach ($this->arrPromotions as $promotions) {//4
                        if ($promotions['itm_key'] == $itm_key) {//5
                            switch ((int) $promotions['promo_type']) {//6
                                case 1:
                                    if ($promotions['discount_type'] == 0) {
                                        $amount_last -= $amount * $promotions['discount'] / 100;
                                    } elseif ($promotions['discount_type'] == 1) {
                                        $amount_last -= round($promotions['discount'], 2);
                                    }
                                    break;
                                case 3:
                                    $check_shipping_free = true;
                                    if ($promotions['discount_type'] == 0) {
                                        $default_product_rate_last -= $default_product_rate_current * (float) $promotions['discount'] / 100;
                                    } elseif ($promotions['discount_type'] == 1) {
                                        $default_product_rate_last -= round($promotions['discount'], 2);
                                    }
                                    break;
                                case 4:
                                    $check_ok = false;
                                    for ($i = 0; $i < count($promotions['countries']); $i++) {
                                        if ($promotions['countries'][$i]['code'] == $_POST["shipping_Country"]) {
                                            if (count($promotions['countries'][$i]['states']) > 0) {
                                                foreach ($promotions['countries'][$i]['states'] as $state_code) {
                                                    if ($state_code == $_POST["shipping_State"]) {
                                                        $check_ok = true;
                                                    }
                                                }
                                            } else {
                                                $check_ok = true;
                                            }
                                            break;
                                        }
                                    }
                                    if ($check_ok == true) {
                                        $check_shipping_free = true;
                                        $default_product_rate_last = 0;
                                    }
                                    break;
                            }//6
                        }//5
                    }//4
                }//3
                if ($amount_last < 0)
                    $amount_last = 0;
                $amount_last = round($amount_last, 2);
                $total_amount_last = round($amount_last * $item['sum'], 2);

                $this->suptotal += $total_amount_last;

                if ($item['product_type'] == 1 || $item['product_type'] == 2) {
                    $default_product_rate_last = 0;
                    $count_ship_free--;
                } else {
                    if ($default_product_rate_last <= 0) {
                        $default_product_rate_last = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                }
                $this->arr_Manufacturers[$m]['items'][$it]['tax_persen'] = $this->calTax($itm_key, $item['product_type']);
                $this->tax += round($this->arr_Manufacturers[$m]['items'][$it]['tax_persen'] * $total_amount_last / 100, 2);
                $ship_rate += $default_product_rate_last;
            }//1
            if (isset($this->arr_Manufacturers[$m]['handling_fee']))
                if ($ship_rate == $this->arr_Manufacturers[$m]['handling_fee'] && $count_ship_free == 0)
                    $ship_rate = 0;
            $this->shippingfee += $ship_rate;
        }//0

        if ($this->suptotal < 0)
            $this->suptotal = 0;
        $this->suptotal = round($this->suptotal, 2);
        $this->tax = round($this->tax, 2);

        $this->shippingfee = round($this->shippingfee, 2);

        if ($this->check_voucher)
            $this->shippingfee = 0;

        $this->ordertotal = round($this->suptotal + $this->tax + $this->shippingfee, 2);

        $this->pay_last = $this->ordertotal;

        return $this->ordertotal;
    }

    function calTax($item_key, $item_type) {
        $tax = 0;
        $item_rid = $this->database->db_result("select rid from users_roles join items on users_roles.uid = items.uid where items.itm_key = '" . $item_key . "'");
        if ($item_type == 1 && $item_rid == 8)
            return $tax;
        if (count($this->arr_tax) > 0) {
            foreach ($this->arr_tax as $tax_obj) {
                $default_rate = $tax_obj['rate'];
                $re = $this->db->query("select tax_rate from items_tax where itm_key = '$item_key' and tax_id = " . $tax_obj['id']);
                if ($re->num_rows() > 0) {
                    $row = $re->row_array();
                    if ($row['tax_rate'] >= 0) {
                        $default_rate = $row['tax_rate'];
                    }
                }
                $tax += $default_rate;
            }
        }
        return $tax;
    }

    function loadManufacturers_donate() {
        $itm_key = isset($_POST['pkey']) ? $this->lib->escape($_POST['pkey']) : '';
        $qtt = (isset($_POST['qtt']) && is_numeric($_POST['qtt']) && $_POST['qtt'] > 0) ? $this->lib->escape($_POST['qtt']) : 0;
        $product_type = 0;
        $item_price = 0;
        $itm_id = 0;
        $current_cost = 0;
        $itm_name = '';
        $itm_model = '';
        $origin = '';
        $manufacturers = array();
        $re_2 = $this->db->query("select uid,product_type,current_cost,itm_id,itm_name,itm_model,origin from items where itm_key = '$itm_key'");

        if ($re_2->num_rows() > 0) {
            $row_2 = $re_2->row_array();
            $uid = $row_2['uid'];
            $product_type = $row_2['product_type'];
            $current_cost = $item_price = $row_2['current_cost'];
            $itm_id = $row_2['itm_id'];
            $itm_name = $row_2['itm_name'];
            $itm_model = $row_2['itm_model'];
            $origin = $row_2['origin'];

            if (!is_numeric($item_price))
                $item_price = 0;
            $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
            if ($re_price->num_rows() > 0) {
                $row_price = $re_price->row_array();
                $markup_percentage = $row_price['markup_percentage'];
                if (!is_numeric($markup_percentage))
                    $markup_percentage = 0;
                $item_price += $item_price * $markup_percentage / 100;
            }
        }

        $check_exist = false;
        for ($i = 0; $i < count($manufacturers); $i++) {
            if ($manufacturers[$i]['uid'] == $uid) {
                $check_exist = true;
                $manufacturers[$i]['items'][] = array('itm_id' => $itm_id, 'key' => $itm_key, 'sum' => $qtt, 'product_type' => $product_type, 'current_cost' => $current_cost, 'item_price' => $item_price, 'itm_name' => $itm_name, 'itm_model' => $itm_model, 'origin' => $origin);
                break;
            }
        }
        if ($check_exist == false) {
            $manufacturers[] = array('uid' => $uid, 'items' => array(array('itm_id' => $itm_id, 'key' => $itm_key, 'sum' => $qtt, 'product_type' => $product_type, 'current_cost' => $current_cost, 'item_price' => $item_price, 'itm_name' => $itm_name, 'itm_model' => $itm_model, 'origin' => $origin)));
        }

        if (isset($_SESSION['__charitys__']))
            unset($_SESSION['__charitys__']);
        $_SESSION['__charitys__'] = $manufacturers;
        $this->arr_Manufacturers = (isset($_SESSION['__charitys__']) && is_array($_SESSION['__charitys__'])) ? $_SESSION['__charitys__'] : array();

        for ($i = 0; $i < count($this->arr_Manufacturers); $i++) {
            $re = $this->db->query("select mail,firstname,lastname from users where uid = " . $this->arr_Manufacturers[$i]['uid']);
            if ($re->num_rows() > 0) {
                $row = $re->row_array();
                $this->arr_Manufacturers[$i]['mail'] = $row['mail'];
                $this->arr_Manufacturers[$i]['firstname'] = $row['firstname'];
                $this->arr_Manufacturers[$i]['lastname'] = $row['lastname'];
                $receive_order_mail = 0;
                $loadAccessUser = $this->lib->loadAccessUser($this->arr_Manufacturers[$i]['uid']);
                if (in_array('store/orders/receive_order_mail.php', $loadAccessUser)) {
                    $receive_order_mail = 1;
                }
                $this->arr_Manufacturers[$i]['receive_order_mail'] = $receive_order_mail;

                $account = array(); // Danh sach employees
                $re_2 = $this->db->query("select users.mail,users.uid from users join manufacturers on manufacturers.uid = users.uid where users.status = 1 and manufacturers.author = " . $this->arr_Manufacturers[$i]['uid']);
                foreach ($re_2->result_array() as $row_2) {
                    $loadAccessUser = $this->lib->loadAccessUser($row_2['uid']);
                    if (in_array('store/orders/receive_order_mail.php', $loadAccessUser)) {
                        $account[] = $row_2['mail'];
                    }
                }
                $this->arr_Manufacturers[$i]['account'] = $account;
                $this->arr_Manufacturers[$i]['rows'] = '';
                $this->arr_Manufacturers[$i]['subtotal'] = 0;
                $this->arr_Manufacturers[$i]['shipping'] = 0;
                $this->arr_Manufacturers[$i]['tax'] = 0;
            }
        }
    }

    function loadManufacturers() {
        $this->arr_Manufacturers = (isset($_SESSION['__manufacturers__']) && is_array($_SESSION['__manufacturers__'])) ? $_SESSION['__manufacturers__'] : array();
        for ($i = 0; $i < count($this->arr_Manufacturers); $i++) {
            $re = $this->db->query("select mail,firstname,lastname from users where uid = " . $this->arr_Manufacturers[$i]['uid']);
            if ($re->num_rows() > 0) {
                $row = $re->row_array();
                $this->arr_Manufacturers[$i]['mail'] = $row['mail'];
                $this->arr_Manufacturers[$i]['firstname'] = $row['firstname'];
                $this->arr_Manufacturers[$i]['lastname'] = $row['lastname'];
                $receive_order_mail = 0;
                $loadAccessUser = $this->lib->loadAccessUser($this->arr_Manufacturers[$i]['uid']);
                if (in_array('store/orders/receive_order_mail.php', $loadAccessUser)) {
                    $receive_order_mail = 1;
                }
                $this->arr_Manufacturers[$i]['receive_order_mail'] = $receive_order_mail;

                $account = array(); // Danh sach employees
                $re_2 = $this->db->query("select users.mail,users.uid from users join manufacturers on manufacturers.uid = users.uid where users.status = 1 and manufacturers.author = " . $this->arr_Manufacturers[$i]['uid']);
                foreach ($re_2->result_array() as $row_2) {
                    $loadAccessUser = $this->lib->loadAccessUser($row_2['uid']);
                    if (in_array('store/orders/receive_order_mail.php', $loadAccessUser)) {
                        $account[] = $row_2['mail'];
                    }
                }
                $this->arr_Manufacturers[$i]['account'] = $account;
                $this->arr_Manufacturers[$i]['rows'] = '';
                $this->arr_Manufacturers[$i]['subtotal'] = 0;
                $this->arr_Manufacturers[$i]['shipping'] = 0;
                $this->arr_Manufacturers[$i]['tax'] = 0;
            }
            for ($j = 0; $j < count($this->arr_Manufacturers[$i]['items']); $j++) {
                if (!$this->check_voucher)
                    $this->arr_Manufacturers[$i]['items'][$j]['default_product_rate'] = $this->get_default_product_rate($this->arr_Manufacturers[$i]['items'][$j]);
                else
                    $this->arr_Manufacturers[$i]['items'][$j]['default_product_rate'] = 0;
                $re = $this->db->query("SELECT itm_id,itm_name,itm_model,current_cost,uid,origin,product_type FROM items WHERE itm_key = '" . $this->arr_Manufacturers[$i]['items'][$j]['key'] . "' and itm_status <> -1 ");
                if ($re->num_rows() > 0) {
                    $row = $re->row_array();
                    $this->arr_Manufacturers[$i]['items'][$j] = array_merge($this->arr_Manufacturers[$i]['items'][$j], $row);
                }
            }
        }
    }

    function get_default_product_rate($item) {
        if (isset($item['ship_rate'][$_POST['shipping_key']]))
            $default_product_rate = $item['ship_rate'][$_POST['shipping_key']];
        else
            $default_product_rate = 0;
        for ($i = 0; $i < count($this->arr_items_shippings); $i++) {
            if ($this->arr_items_shippings[$i]['pkey'] == $item['key']) {
                $countries = $this->arr_items_shippings[$i]['countries'];
                if (count($countries) > 0) {
                    foreach ($countries as $contry) {
                        if ($contry['country_code'] == $_POST["shipping_Country"]) {
                            if ($contry['rate_type'] == 1) {
                                if ($contry['country_rate'] >= 0) {
                                    $default_product_rate = $contry['country_rate'];
                                }
                            } else {
                                $states = $contry['states'];
                                if (count($states) > 0) {
                                    foreach ($states as $state__) {
                                        if ($state__['state_code'] == $_POST["shipping_State"]) {
                                            if ($state__['state_rate'] >= 0) {
                                                $default_product_rate = $state__['state_rate'];
                                            }
                                            break;
                                        }
                                    }
                                }
                            }
                            break;
                        }
                    }
                }
                break;
            }
        }
        return $default_product_rate;
    }

    function getFirstAddress($str) {
        $result = "";
        $str = str_replace("  ", " ", trim($str));
        $arr_ = explode(" ", $str);
        if (isset($arr_[0]))
            $result = $arr_[0];
        return $result;
    }

}

class sale_rep extends CI_Model {

    private $uid = 0;
    private $legal_business_id = 0;
    private $legal_business_name = '';

    function __construct($uid = NULL) {
        if ($uid !== NULL) {
            $this->uid = $uid;
            $this->rep_key();
        }
    }

    function getTotalEarning($uid = NULL) {
        if ($uid !== NULL) {
            $this->uid = $uid;
            $this->rep_key();
        }
        $total_commission = 0;
        //$total_commission += $this->database->db_result("select sum(ad_commission.price) from ad_commission join ad_orders join tbaffiliates join users on tbaffiliates.uid = users.uid and ad_orders.okey = ad_commission.okey and ad_commission.akey = tbaffiliates.legal_business_id where users.status <> -1 and ad_commission.rid = 9 and ad_commission.akey = '".$this->legal_business_id."'");
        $re = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = " . $this->uid);
        foreach ($re->result_array() as $row) {
            $qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = " . $row['id'] . " and status = 1");
            $qty_buy = $row['quality'] - $qty_refund;
            if ($qty_buy < 0)
                $qty_buy = 0;
            $itemprice = round($row["itemprice"] * $qty_buy, 2);
            $total_commission += round($row['commission'] * $itemprice / 100, 2);
        }
        $total_commission = round($total_commission, 2);
        $paid = $this->database->db_result("select sum(pay) from payments where role = 9 and legal_business_id = '" . $this->legal_business_id . "'");
        $paid = round($paid, 2);
        $balance = $total_commission - $paid;

        if ($balance < 0)
            $balance = 0;
        return (float) $balance;
    }

    function getLegalBusinessID() {
        return $this->legal_business_id;
    }

    function getLegalBusinessName() {
        return $this->legal_business_name;
    }

    function rep_key() {
        $re = $this->db->query("select representatives.legal_business_id,representatives.legal_business_fname,representatives.legal_business_lname from representatives join users on representatives.uid = users.uid where users.status = 1 and users.uid = " . $this->uid);
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $this->legal_business_id = $row['legal_business_id'];
            $this->legal_business_name = $row['legal_business_fname'] . ' ' . $row['legal_business_lname'];
        }
    }

}

class schedule extends CI_Model {

    var $tblcontries = array();
    var $xmlParser;
    var $arr_tax = array();
    var $schedule_taxs = array();
    var $shopping_carts = array();
    var $shipping_Country = 'US';
    var $shipping_State = '';
    var $shipping_Zip = '';
    var $shipping_City = '';
    var $shipping_Address = '';
    var $shipping_Name = '';
    var $shipping_Phone = '';
    var $items__auto = array();
    var $arr_schedule = array();
    var $shipping_rates = array();
    var $is_error = false;
    var $skey = '';
    var $okey = ''; //insert nore

    function __construct() {
        $this->loadCountries();
        $this->loadXmlParse();
        $this->loadTax();
        $this->shopping_carts = $this->lib->loadshoppingcartAuto();

        $this->loadDeliveryInfo();

        $this->loadShippingRate();
        $this->build_schedule();
        $this->getItemsShipAuto();
    }

    function getItemsShipAuto() {
        if (count($this->arr_schedule) > 0) {
            for ($i = 0; $i < count($this->arr_schedule); $i++) {

                $promotions = array();
                if (count($this->shopping_carts['promotions']) > 0) {
                    foreach ($this->shopping_carts['promotions'] as $promo) {
                        if ($promo['ckey'] == $this->arr_schedule[$i]['ckey']) {
                            $promotions = $promo['items_content'];
                            break;
                        }
                    }
                }

                $items__ = array();

                if (count($this->shipping_rates) > 0) {
                    foreach ($this->shipping_rates as $row) {
                        $this->is_error = false;
                        $this->skey = $row['skey'];
                        switch ((int) $row['shipping_method']) {
                            case 0:// Ship manually
                                $this->shipManually($this->arr_schedule[$i]['manufacturer']);

                                break;
                            case 1:// Ship UPS

                                break;
                            case 2:// Ship USPS
                                $this->shipUSPS($this->arr_schedule[$i]['manufacturer']);
                                break;
                        }
                        if ($this->is_error == true)
                            continue;

                        $total_price = $this->getPriceFromManufacturer($this->arr_schedule[$i]['manufacturer'], $row['handling_fee'], $this->arr_schedule[$i]['item_ship'], $promotions);

                        $items__[] = array(
                            'skey' => $this->skey,
                            'label' => $row['label'] . ' &amp; handling fee',
                            'price' => round($total_price, 2)
                        );
                    }
                }
                $this->items__auto[] = array(
                    'item' => $items__,
                    'ckey' => $this->arr_schedule[$i]['ckey']
                );
            }
        }
    }

    function getPriceFromManufacturer($manufacturers, $handling_fee, $arr_items_shippings, $arr_promotions) {
        $total_price = 0;
        for ($m = 0; $m < count($manufacturers); $m++) {//0
            $handling_fee_new = $handling_fee;
            foreach ($manufacturers[$m]['items'] as $item) {//1
                $itm_key = $item['key'];
                for ($i = 0; $i < count($arr_items_shippings); $i++) {
                    if ($arr_items_shippings[$i]['pkey'] == $itm_key && $arr_items_shippings[$i]['skey'] == $this->skey) {
                        if ($arr_items_shippings[$i]['handling'] >= 0) {
                            if ($handling_fee_new < $arr_items_shippings[$i]['handling'])
                                $handling_fee_new = $arr_items_shippings[$i]['handling'];
                        }
                        break;
                    }
                }
            }
            $ship_rate = $handling_fee_new;
            $count_ship_free = count($manufacturers[$m]['items']);
            foreach ($manufacturers[$m]['items'] as $item) {//1
                if ($item['product_type'] == 1) {
                    $count_ship_free--;
                    continue;
                }
                $itm_key = $item['key'];
                $default_product_rate = $item['ship_rate'][$this->skey];
                //$default_product_rate = $item['ship_rate']['YWhztPgDIoZH38vXiYs9'];
                for ($i = 0; $i < count($arr_items_shippings); $i++) {
                    if ($arr_items_shippings[$i]['pkey'] == $itm_key && $arr_items_shippings[$i]['skey'] == $this->skey) {
                        $countries = $arr_items_shippings[$i]['countries'];
                        if (count($countries) > 0) {
                            foreach ($countries as $contry) {
                                if ($contry['country_code'] == $this->shipping_Country) {
                                    if ($contry['rate_type'] == 1) {
                                        if ($contry['country_rate'] >= 0) {
                                            $default_product_rate = $contry['country_rate'];
                                        }
                                    } else {
                                        $states = $contry['states'];
                                        if (count($states) > 0) {
                                            foreach ($states as $state__) {
                                                if ($state__['state_code'] == $this->shipping_State) {
                                                    if ($state__['state_rate'] >= 0) {
                                                        $default_product_rate = $state__['state_rate'];
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                        break;
                    }
                }

                $default_product_rate_last = $default_product_rate_current = $default_product_rate * $item['sum'];
                $check_shipping_free = false;
                if (count($arr_promotions) > 0) {//2
                    foreach ($arr_promotions as $promotions) {//3
                        if ($promotions['itm_key'] == $itm_key) {
                            if ((int) $promotions['promo_type'] == 3) {
                                $check_shipping_free = true;
                                if ($promotions['discount_type'] == 0) {
                                    $default_product_rate_last -= $default_product_rate_current * $promotions['discount'] / 100;
                                } elseif ($promotions['discount_type'] == 1) {
                                    $default_product_rate_last -= round($promotions['discount'], 2);
                                }
                            } elseif ((int) $promotions['promo_type'] == 4) {
                                $check_ok = false;
                                for ($i = 0; $i < count($promotions['countries']); $i++) {
                                    if ($promotions['countries'][$i]['code'] == $this->shipping_Country) {
                                        if (count($promotions['countries'][$i]['states']) > 0) {
                                            foreach ($promotions['countries'][$i]['states'] as $state_code) {
                                                if ($state_code == $this->shipping_State) {
                                                    $check_ok = true;
                                                }
                                            }
                                        } else {
                                            $check_ok = true;
                                        }
                                        break;
                                    }
                                }
                                if ($check_ok == true) {
                                    $check_shipping_free = true;
                                    $default_product_rate_last = 0;
                                }
                            }
                        }
                    } //3
                }//2
                if ($default_product_rate_last <= 0) {
                    $default_product_rate_last = 0;
                    if ($check_shipping_free == true)
                        $count_ship_free--;
                }
                $ship_rate += $default_product_rate_last;
            }//1
            if ($ship_rate == $handling_fee_new && $count_ship_free == 0)
                $ship_rate = 0;
            $total_price += round($ship_rate, 2);
        }//0

        return $total_price;
    }

    function shipUSPS(&$manufacturers) {
        $re_2 = $this->db->query("select * from shipping_usps where skey = '" . $this->skey . "'");
        if ($re_2->num_rows() > 0) {//0
            $row_2 = $re_2->row_array();
            $access_key = ($row_2['USPS_userid'] != null && $row_2['USPS_userid'] != '') ? $row_2['USPS_userid'] : '052BELLA6359';
            if ($this->shipping_Country == 'US') {//1
                for ($m = 0; $m < count($manufacturers); $m++) {//2
                    $origin_zipcode = '';
                    $re_m = $this->db->query("select data_xml,zipcode from manufacturers where uid = '" . $manufacturers[$m]['uid'] . "'");
                    if ($re_m->num_rows() > 0) {
                        $row_m = $re_m->row_array();
                        if ($row_m['data_xml'] != null && $row_m['data_xml'] != '') {
                            $data_xml = unserialize($row_m['data_xml']);
                            $origin_zipcode = (isset($data_xml['origin_zipcode']) && $data_xml['origin_zipcode'] != '' && $data_xml['origin_zipcode'] != null) ? $data_xml['origin_zipcode'] : trim($row_m['zipcode']);
                        } else {
                            $origin_zipcode = trim($row_m['zipcode']);
                        }
                    }
                    foreach ($manufacturers[$m]['items'] as $kindex => $item) {//3
                        $Pounds = 1;
                        $Ounces = 1;
                        if ($item['uw'] == 'lb') {
                            $Pounds = $item['weight'];
                            if ($Pounds <= 0)
                                $Pounds = 1;
                        }elseif ($item['uw'] == 'oz') {
                            $Ounces = $item['weight'];
                            if ($Ounces <= 0)
                                $Ounces = 1;
                        }
                        $data__ = "http://production.shippingapis.com/shippingapi.dll?API=RateV4&XML=<RateV4Request USERID=\"$access_key\">
							<Package ID=\"1ST\">
								<Service>" . $row_2['USPS_service'] . "</Service>
								<FirstClassMailType>FLAT</FirstClassMailType>
								<ZipOrigination>$origin_zipcode</ZipOrigination>
								<ZipDestination>" . $this->shipping_Zip . "</ZipDestination>
								<Pounds>$Pounds</Pounds>
								<Ounces>$Ounces</Ounces>
								<Container/>
								<Size>REGULAR</Size>
								<Machinable>" . $row_2['Machinable'] . "</Machinable>
							</Package>
						</RateV4Request>";
                        $arr_price = array();
                        $data__ = $this->lib->__grabURL__($data__);
                        $content__ = file_get_contents($data__);

                        $array_xml = $this->xmlParser->GetXMLTree($content__);
                        if (isset($array_xml['RATEV4RESPONSE']) && count($array_xml['RATEV4RESPONSE']) > 0) { // if everything OK
                            foreach ($array_xml['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value) {
                                $arr_price[] = (float) $value['RATE'][0]['VALUE'];
                            }
                        }
                        if (count($arr_price) == 0)
                            $this->is_error = true;
                        $item['ship_rate'][$this->skey] = $this->getaveArr($arr_price);
                        $manufacturers[$m]['items'][$kindex] = $item;
                    }//3
                }//2
            }else {//1
                for ($m = 0; $m < count($manufacturers); $m++) {//2
                    $origin_zipcode = '';
                    $re_m = $this->db->query("select data_xml,zipcode from manufacturers where uid = '" . $manufacturers[$m]['uid'] . "'");
                    if ($re_m->num_rows() > 0) {
                        $row_m = $re_m->row_array();
                        if ($row_m['data_xml'] != null && $row_m['data_xml'] != '') {
                            $data_xml = unserialize($row_m['data_xml']);
                            $origin_zipcode = (isset($data_xml['origin_zipcode']) && $data_xml['origin_zipcode'] != '' && $data_xml['origin_zipcode'] != null) ? $data_xml['origin_zipcode'] : trim($row_m['zipcode']);
                        } else {
                            $origin_zipcode = trim($row_m['zipcode']);
                        }
                    }
                    foreach ($manufacturers[$m]['items'] as $kindex => $item) {//3
                        $Pounds = 0;
                        $Ounces = 0;
                        $Width = $item['Width'];
                        $Length = $item['Length'];
                        $Height = $item['Height'];
                        if ($item['uw'] == 'lb') {
                            $Pounds = $item['weight'];
                            if ($Pounds <= 0)
                                $Pounds = 1;
                        }elseif ($item['uw'] == 'oz') {
                            $Ounces = $item['weight'];
                            if ($Ounces <= 0)
                                $Ounces = 1;
                        }
                        $data__ = "http://production.shippingapis.com/shippingapi.dll?API=IntlRateV2&XML=<IntlRateV2Request USERID=\"$access_key\">
						  <Revision>2</Revision>
						  <Package ID=\"1ST\">
							<Pounds>$Pounds</Pounds>
							<Ounces>$Ounces</Ounces>
							<Machinable>" . $row_2['Machinable'] . "</Machinable>
							<MailType>Package</MailType>
							<GXG>
								  <POBoxFlag>Y</POBoxFlag>
								  <GiftFlag>Y</GiftFlag>
							</GXG>
							<ValueOfContents></ValueOfContents>
							<Country>" . (isset($this->tblcontries[$this->shipping_Country]) ? $this->tblcontries[$this->shipping_Country] : $this->shipping_Country) . "</Country>
							<Container>RECTANGULAR</Container>
							<Size>REGULAR</Size>
							<Width>$Width</Width>
							<Length>$Length</Length>
							<Height>$Height</Height>
							<Girth>0</Girth>
							<OriginZip>$origin_zipcode</OriginZip>
							<CommercialFlag>N</CommercialFlag>
						  </Package>
						</IntlRateV2Request>";
                        $arr_price = array();
                        $data__ = $this->lib->__grabURL__($data__);
                        $content__ = file_get_contents($data__);
                        $xmlParser = new xmlparser();
                        $array_xml = $xmlParser->GetXMLTree($content__);
                        if (isset($array_xml['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE']) && count($array_xml['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE']) > 0) {
                            foreach ($array_xml['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'] as $value) {
                                $arr_price[] = $value['POSTAGE'][0]['VALUE'];
                            }
                        }
                        if (count($arr_price) == 0)
                            $this->is_error = true;
                        $item['ship_rate'][$this->skey] = $this->getaveArr($arr_price);
                        $manufacturers[$m]['items'][$kindex] = $item;
                    }//3
                }//2	
            }//1	
        }//0	
    }

    function shipManually(&$manufacturers) {
        $price = -1;
        $re2 = $this->db->query("select * from shipping_manually where skey = '" . $this->skey . "'");
        foreach ($re2->result_array() as $row2) {
            if ($row2['country'] == $this->shipping_Country) {
                if ($row2['rate_type'] == 1) {//Select Country
                    if ($row2['country_rate'] >= 0) {// Thoa man
                        $price = $row2['country_rate'];
                        break;
                    }
                } else {//
                    $re3 = $this->db->query("select * from shipping_manually_states where ship_country_id = " . $row2['id']);
                    foreach ($re3->result_array() as $row3) {
                        if ($row3['state'] == $this->shipping_State) {
                            if ($row3['state_rate'] >= 0) {// Thoa man
                                $price = $row3['state_rate'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        for ($m = 0; $m < count($manufacturers); $m++) {//2
            foreach ($manufacturers[$m]['items'] as $kindex => $item) {//3
                $item['ship_rate'][$this->skey] = $price;
                $manufacturers[$m]['items'][$kindex] = $item;
            }
        }
    }

    function loadShippingRate() {
        $re = $this->db->query("select * from shipping_rates where status = 1 order by weight DESC ");
        foreach ($re->result_array() as $row) {//0
            $this->shipping_rates[] = $row;
        }
    }

    function build_schedule() {
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {//0
            if (isset($_SESSION['_CART']) && is_array($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {//1
                foreach ($_SESSION['_CART'] as $ckey => $cart) {//2	
                    $manufacturers = array();
                    $arr_items_shippings = array();

                    $taxs = $this->arr_tax;
                    $schedule_tax = array();
                    $schedule_tax['ckey'] = $ckey;
                    $schedule_tax['arr_taxs'] = $taxs;
                    if (isset($cart->items))
                        foreach ($cart->items as $k => $vs) {//3
                            $arrKeys = explode(__keycode__, $k);
                            if (count($arrKeys) == 0)
                                continue;

                            $itm_key = $arrKeys[0];
                            $weight = 0;
                            $UnitOfPackageWeight = 'lb';
                            $Width = 1;
                            $Length = 1;
                            $Height = 1;
                            $uid = 1;
                            $product_type = 0;
                            $item_price = 0;

                            $re_2 = $this->db->query("select uid,weight,width,length,height,UnitOfPackageWeight,product_type,current_cost from items where itm_key = '$itm_key'");
                            if ($re_2->num_rows() > 0) {
                                $row_2 = $re_2->row_array();
                                $uid = $row_2['uid'];
                                $weight = $row_2['weight'];
                                $Width = $row_2['width'];
                                if ($Width <= 0)
                                    $Width = 1;
                                $Length = $row_2['length'];
                                if ($Length <= 0)
                                    $Length = 1;
                                $Height = $row_2['height'];
                                if ($Height <= 0)
                                    $Height = 1;
                                $UnitOfPackageWeight = $row_2['UnitOfPackageWeight'];
                                $product_type = $row_2['product_type'];
                                $item_price = $row_2['current_cost'];

                                //insert more code for markup price

                                if (!is_numeric($item_price))
                                    $item_price = 0;
                                $re_price = $this->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                                foreach ($re_price->result_array() as $row_price) {
                                    $markup_percentage = $row_price['markup_percentage'];
                                    if (!is_numeric($markup_percentage))
                                        $markup_percentage = 0;
                                    $item_price += $item_price * $markup_percentage / 100;
                                }
                                //end more code
                            }


                            $tax_rate_items = array();
                            $re_tax = $this->db->query("select * from items_tax where itm_key = '" . $itm_key . "'");
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
                                if (!isset($schedule_tax['arr_taxs'][$i]['tax_item_total']))
                                    $schedule_tax['arr_taxs'][$i]['tax_item_total'] = 0;
                                $schedule_tax['arr_taxs'][$i]['tax_item_total'] += ((float) $tax_rate * (float) $item_price * (float) $vs) / 100;
                            }

                            $check_exist = false;
                            for ($i = 0; $i < count($manufacturers); $i++) {
                                if ($manufacturers[$i]['uid'] == $uid) {
                                    $check_exist = true;
                                    $manufacturers[$i]['items'][] = array('key' => $itm_key, 'sum' => $vs, 'k' => $k, 'weight' => $weight, 'uw' => $UnitOfPackageWeight, 'Width' => $Width, 'Length' => $Length, 'Height' => $Height, 'product_type' => $product_type);
                                    break;
                                }
                            }
                            if ($check_exist == false) {
                                $manufacturers[] = array('uid' => $uid, 'items' => array(array('key' => $itm_key, 'sum' => $vs, 'k' => $k, 'weight' => $weight, 'uw' => $UnitOfPackageWeight, 'Width' => $Width, 'Length' => $Length, 'Height' => $Height, 'product_type' => $product_type)));
                            }
                            $re = $this->db->query("select * from items_shippings where pkey = '$itm_key'");
                            foreach ($re->result_array() as $row) {
                                $countries = array();
                                $re_2 = $this->db->query("select * from items_shippings_countries where sid = " . $row['id']);
                                foreach ($re_2->result_array() as $row_2) {
                                    $states = array();
                                    $re_3 = $this->db->query("select * from items_shippings_states where country_id = " . $row_2['id']);
                                    foreach ($re_3->result_array() as $row_3) {
                                        $states[] = $row_3;
                                    }
                                    $row_2['states'] = $states;
                                    $countries[] = $row_2;
                                }
                                $row['countries'] = $countries;
                                $arr_items_shippings[] = $row;
                            }
                        }//3
                    $this->schedule_taxs[] = $schedule_tax;
                    $this->arr_schedule[] = array(
                        'ckey' => $ckey,
                        'cdate' => $cart->cdate,
                        'manufacturer' => $manufacturers,
                        'item_ship' => $arr_items_shippings
                    );
                }//2
            }//1
        }//0
    }

    function loadDeliveryInfo() {
        if (isset($_POST['shipping_Country']) && trim($_POST['shipping_Country']) != '') {
            $this->shipping_Country = trim($_POST['shipping_Country']);
        }
        if (isset($_POST['shipping_State']) && trim($_POST['shipping_State']) != '') {
            $this->shipping_State = trim($_POST['shipping_State']);
        }
        if (isset($_POST['shipping_Zip']) && trim($_POST['shipping_Zip']) != '') {
            $this->shipping_Zip = trim($_POST['shipping_Zip']);
        }
        if (isset($_POST['shipping_City']) && trim($_POST['shipping_City']) != '') {
            $this->shipping_City = trim($_POST['shipping_City']);
        }
        if (isset($_POST['shipping_Address']) && trim($_POST['shipping_Address']) != '') {
            $this->shipping_Address = trim($_POST['shipping_Address']);
        }
        if (isset($_POST['shipping_Name']) && trim($_POST['shipping_Name']) != '') {
            $this->shipping_Name = trim($_POST['shipping_Name']);
        }
        if (isset($_POST['shipping_Phone']) && trim($_POST['shipping_Phone']) != '') {
            $this->shipping_Phone = trim($_POST['shipping_Phone']);
        }
    }

    function loadXmlParse() {
        include("application/libraries/xmlparser.php");
        $this->xmlParser = new xmlparser();
    }

    function loadCountries() {
        $re = $this->db->query("select * from tblcontries");
        foreach ($re->result_array() as $row) {
            $this->tblcontries[$row['code']] = $row['name'];
        }
    }

    function loadTax() {
        $re = $this->db->query("select * from tax_rates where status <> -1 order by weight DESC,name ASC");
        foreach ($re->result_array() as $row) {
            if ($row['state'] != '' && $row['state'] != $this->shipping_State)
                continue;
            $this->arr_tax[] = $row;
        }
    }

    function getaveArr($arr) {
        $value = 0;
        if (count($arr) == 1)
            return $arr[0];
        elseif (count($arr) > 1) {
            for ($i = 0; $i < count($arr) - 1; $i++) {
                for ($j = $i + 1; $j < count($arr); $j++) {
                    if ($arr[$i] > $arr[$j]) {
                        $tam = $arr[$i];
                        $arr[$i] = $arr[$j];
                        $arr[$j] = $tam;
                    }
                }
            }
            $index = round(count($arr) / 2);
            $value = isset($arr[$index]) ? $arr[$index] : $arr[0];
        }
        return $value;
    }

}

class payAutoShip extends CI_Model {

    var $uid = -1;
    var $skey = '';
    var $order_number = 0;
    var $card4digis = '';
    var $cardNumber = '';
    var $cc_Card_Month = 0;
    var $cc_Card_Year = 0;
    var $cc_Card_Cvc = 0;
    var $shipping_fName = '';
    var $shipping_lName = '';
    var $shipping_Address = '';
    var $shipping_City = '';
    var $shipping_State = '';
    var $shipping_Country = '';
    var $shipping_Zip = '';
    var $shipping_Phone = '';
    var $billing_fName = '';
    var $billing_lName = '';
    var $billing_Address = '';
    var $billing_City = '';
    var $billing_State = '';
    var $billing_Country = '';
    var $billing_Zip = '';
    var $billing_Phone = '';
    var $billing_Email = '';
    var $arr_shippingfee = array();
    var $arr_tax = array();
    var $tax_persen = 0;
    var $check_commission = 0;
    var $arr_schedule = array();
    var $total_pay = 0;
    var $total_commission = 0;
    var $arr_Manufacturers = array();
    var $suptotal_Schedule = 0;
    var $tax_Schedule = 0;
    var $shippingfee_Schedule = 0;
    var $schedule_total = 0;
    var $sale_rep_obj;
    var $authorize;
    var $customerPaymentProfileId = '';
    var $customerProfileId = '';
    var $customerAddressId = '';
    var $ordertotal = 0; //insert more 
    var $okeys = '';
    var $mail_body_admin = '';
    var $mail_body_client = 'Thank you for ordering at Bellavie Network. Listed below is your order information. Please save this email until you receive your order.';
    var $result = '';

    function __construct() {

        $this->getCreditCardInfo();
        $this->getShippingAddress();
        $this->getBillAddress();
        $this->getArrShippingFee();
        $this->loadTax();

        $this->checkItemAvailable(); //insert more

        $this->check_commission = (isset($_POST['check_commission']) && $_POST['check_commission'] == 1) ? 1 : 0;

        if (!is_numeric($this->author->objlogin->uid) || $this->author->objlogin->uid <= 0) {
            header('Location: ' . $this->system->URL_server__() . 'shop/shome');
            exit;
        } elseif ($this->author->objlogin->login) {
            $this->uid = $this->author->objlogin->uid;
            $this->roles = $this->author->loadRole($this->uid);
        }
    }

    function pay() {
        //global $__payment_name__;
        $__payment_name__ = 'authorize';

        $this->checkItemAvailable();
        if ($this->roles['rid'] == 9) {
            $this->sale_rep_obj = new sale_rep($this->uid);
            if ($this->check_commission == 1) {
                $this->total_commission = $this->sale_rep_obj->getTotalEarning();
                if ($this->total_commission > $this->total_pay)
                    $this->total_commission = $this->total_pay;
                $this->total_pay -= $this->total_commission;
            }
        }
        $this->buildSkey();

        //if($this->total_pay <= 0){
        return $this->saveDatabase();
        /* }else{
          switch($__payment_name__){
          case 'authorize':
          return $this->check_creditCard();
          break;
          case 'paypal':
          return $this->paypalCheckout();
          break;
          case 'firstdata':
          return $this->firstdataPayment();
          break;
          }
          } */
    }

    function buildSkey() {
        $this->skey = $this->lib->GeneralRandomNumberKey(8);
        $re = $this->db->query("select id from orders_schedule where skey = '" . $this->skey . "'");
        foreach ($re->result_array() as $row) {
            $this->skey = $this->lib->GeneralRandomNumberKey(8);
            $re = $this->db->query("select id from orders_schedule where skey = '" . $this->skey . "'");
        }
        return $this->skey;
    }

    function check_creditCard() {
        $this->loadAuthClass();
        $this->authorize->payment_type = 'creditCard';
        $this->authorize->cardNumber = $this->cardNumber;
        $this->authorize->expirationDate = $this->cc_Card_Year . '-' . sprintf('%02d', $this->cc_Card_Month);
        $this->authorize->cardCode = $this->cc_Card_Cvc;

        $this->check_AuthNet();
    }

    function check_AuthNet() {
        $billInfo = array(
            'billTo_firstName' => $this->lib->escape($this->billing_fName),
            'billTo_lastName' => $this->lib->escape($this->billing_lName),
            'billTo_address' => $this->lib->escape($this->billing_Address),
            'billTo_city' => $this->lib->escape($this->billing_City),
            'billTo_state' => $this->lib->escape($this->billing_State),
            'billTo_zip' => $this->lib->escape($this->billing_Zip),
            'billTo_country' => $this->lib->escape($this->billing_Country),
            'billTo_phoneNumber' => $this->lib->escape($this->billing_Phone),
            'shipToList_firstName' => $this->shipping_fName,
            'shipToList_lastName' => $this->shipping_lName,
            'shipToList_address' => $this->lib->escape($this->shipping_Address),
            'shipToList_city' => $this->lib->escape($this->shipping_City),
            'shipToList_state' => $this->lib->escape($this->shipping_State),
            'shipToList_zip' => $this->lib->escape($this->shipping_Zip),
            'shipToList_country' => $this->lib->escape($this->shipping_Country),
            'shipToList_phoneNumber' => $this->lib->escape($this->shipping_Phone)
        );
        $this->authorize->set_ProfileInfo($billInfo);
        $data_user = $this->author->objlogin->data;
        if ($data_user == null || $data_user == '') {
            $this->authorize->createCustomerProfileFullRequest();
            $this->customerProfileId = $this->authorize->get_customerProfileId();
            if ($this->customerProfileId != '') {
                $data_new_user = '<customerProfileId>' . $this->customerProfileId . '</customerProfileId>';
                $this->db->update('users', array('data' => $data_new_user), 'uid = ' . $this->uid);
                $this->author->objlogin->data = $data_new_user;
            } else {
                return "incorrect^" . $this->authorize->get_error();
            }
        } else {
            $arr_data_user = $this->lib->partitionString("<customerProfileId>", "</customerProfileId>", $data_user);
            if (isset($arr_data_user[1]) && $arr_data_user[1] != '') {
                $this->authorize->createCustomerPaymentProfileRequest($arr_data_user[1]);
            } else {
                $this->authorize->createCustomerProfileFullRequest();
            }
            $this->customerProfileId = $this->authorize->get_customerProfileId();
            if ($this->customerProfileId != '') {
                $data_new_user = '';
                if (isset($arr_data_user[0]))
                    $data_new_user .= $arr_data_user[0];
                $data_new_user .= '<customerProfileId>' . $this->customerProfileId . '</customerProfileId>';
                if (isset($arr_data_user[2]))
                    $data_new_user .= $arr_data_user[2];
                $this->author->objlogin->data = $data_new_user;
                $this->db->update('users', array('data' => $data_new_user), 'uid = ' . $this->uid);
            }else {
                return "incorrect^" . $this->authorize->get_error();
            }
        }
        if ($this->authorize->get_error() != '')
            return "incorrect^" . $this->authorize->get_error();
        $this->customerPaymentProfileId = $this->authorize->get_customerPaymentProfileId();
        if ($this->customerProfileId == '' || $this->customerPaymentProfileId == '') {
            return "incorrect^" . $this->authorize->get_error();
        } else {
            $this->customerAddressId = $this->authorize->customerAddressId;
            return $this->saveDatabase();
        }
    }

    function loadAuthClass() {
        /* $auth_net_login_id = '4ZuE3EFu64J9';
          $auth_net_tran_key = '7B8p9CDJ978hqh6V';
          $auth_net_url = 'testMode'; */
        $auth_net_login_id = $this->lib->getMailInfor('paypal_settings', 'auth_login_id');
        $auth_net_tran_key = $this->lib->getMailInfor('paypal_settings', 'auth_tran_key');
        $auth_net_url = $this->lib->getMailInfor('paypal_settings', 'auth_url');


        require('application/libraries/authorize.class.php');
        $this->authorize = new authorize($auth_net_login_id, $auth_net_tran_key, $auth_net_url);
        $this->authorize->profile_mail = $this->author->objlogin->mail;
        $this->authorize->merchantCustomerId = sprintf('%08d', $this->uid);
    }

    function saveDatabase() {
        // $__email_to_get_order__ = 'nghiemhiep_18@yahoo.com';
        // $__signature__ = 'Bellavie Network.';
        $this->saveOrders_Schedule();

        //send mail
        $mailcontent = $this->generateOrder();

        //echo $mailcontent; exit;

        $mailcontent_client = str_replace("{mail_content}", "Dear " . ucfirst($_POST["billing_Name"]) . ' ' . ucfirst($_POST["billing_LastName"]) . "<br>" . $this->mail_body_client, $mailcontent);
        $this->lib->mail_simple($_POST['billing_Email'], "Order from Bellavie Network.", $this->lib->getMailInfor('site_info', 'email'), $this->lib->getMailInfor('site_info', 'signature'), $mailcontent_client);

        $mailcontent_admin = str_replace("{mail_content}", "Dear " . ucfirst($this->author->objlogin->firstname) . ' ' . ucfirst($this->author->objlogin->lastname) . "<br>" . $this->mail_body_admin, $mailcontent);
        $this->lib->mail_simple($this->lib->getMailInfor('site_info', 'email'), "Customer's order.", $_POST['billing_Email'], $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $mailcontent_admin);

        $strContent = $this->system->parse_templace("shop/payment_auto_complete.htm", $data = array(), true);
        $strContent = str_replace('<!--name-->', '. ' . $_POST["billing_Name"] . ' ' . $_POST["billing_LastName"], $strContent);
        $strContent = str_replace('<!--order_number-->', $this->skey, $strContent);
        $strContent = str_replace('{ck_commission}', ($this->check_commission == 1) ? $this->total_commission : '', $strContent);
        return $strContent;
    }

    function generateOrder() {
        $strContent = $this->system->parse_templace("shop/auto_ship_mail.htm", $data = array(), true);
        $strContent = str_replace('<!--order_number-->', $this->skey, $strContent); //skey
        $strContent = str_replace('<!--date-->', gmdate("m/d/Y"), $strContent);
        $strContent = str_replace('<!--billingName-->', $this->billing_fName . ' ' . $this->billing_lName, $strContent);
        $strContent = str_replace('<!--billingAddress-->', $this->billing_Address . '<br>' . $this->billing_City . ', ' . $this->billing_State . ' ' . $this->billing_Zip . ', ' . $this->billing_Country, $strContent);
        $strContent = str_replace('<!--billingPhone-->', $this->billing_Phone, $strContent);
        $strContent = str_replace('<!--billingEmail-->', $this->billing_Email, $strContent);
        $strContent = str_replace('<!--shippingName-->', $this->shipping_fName . ' ' . $this->shipping_lName, $strContent);
        $strContent = str_replace('<!--shippingAddress-->', $this->shipping_Address . '<br>' . $this->shipping_City . ', ' . $this->shipping_State . ' ' . $this->shipping_Zip . ', ' . $this->shipping_Country, $strContent);
        $strContent = str_replace('<!--shippingPhone-->', $this->shipping_Country, $strContent);
        $strContent = str_replace('<!--card_number-->', "XXXXX" . $this->card4digis, $strContent);

        $arr_sch = $this->lib->partitionString("{schedule}", "{/schedule}", $strContent);
        $strHeader_sch = $arr_sch[0];
        $strRow_sch = $arr_sch[1];
        $strFooter_sch = $arr_sch[2];
        $strRows_sch = '';
        $schedule = 0;
        foreach ($_SESSION['_CART'] as $ckey => $cart) {
            $schedule++;
            $arr = $this->lib->partitionString("<!--startRows-->", "<!--endRows-->", $strRow_sch);
            $strHeader = $arr[0];
            $strRow = $arr[1];
            $strFooter = $arr[2];
            $strRows = '';

            $strHeader = str_replace("{schedule_number}", $schedule, $strHeader);
            $strHeader = str_replace("{Delivery_date}", $cart->cdate, $strHeader);

            $shipping_label = '';
            $ship_fee = 0;
            foreach ($this->arr_shippingfee as $ship) {
                if ($ship['ckey'] == $cart->ckey) {
                    $shipping_label = $this->loadShippingData($ship['shipping_key']);
                    $ship_fee = (float) $ship['shipping_fee'];
                    break;
                }
            }

            $strFooter = str_replace("<!--suptotal-->", number_format($this->suptotal_Schedule, 2), $strFooter);
            $strFooter = str_replace("{order_total}", number_format($this->schedule_total, 2), $strFooter);
            $strFooter = str_replace('<!--Tax-->', number_format($this->tax_Schedule, 2), $strFooter);
            $strFooter = str_replace('<!--ship_label-->', $shipping_label, $strFooter);
            $strFooter = str_replace('<!--shipping_fee-->', number_format($ship_fee, 2), $strFooter);

            $this->loadMyWalleAccount($strFooter);
            $strRows_sch .= $strHeader . $strRows . $strFooter;
        }
//		unset($_SESSION['_CART']);
//		unset($_SESSION['__manufacturers__']);
        return $strHeader_sch . $strRows_sch . $strFooter_sch;
    }

    function loadShippingData($shipping_key) {
        $re = $this->db->query("select label from shipping_rates where status = 1 and skey = '" . $shipping_key . "'");
        $ship_label = '';
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $ship_label = $row['label'];
        }
        return $ship_label;
    }

    function get_attributes($arrKeys, $itm_key) {
        $attributes = array();
        for ($i = 1; $i < count($arrKeys); $i++) {//3
            $arr_attribute = array(
                'label' => '',
                'name' => '',
                'price' => 0
            );
            $arr_textfi = explode(__keyat__, $arrKeys[$i]);
            if (count($arr_textfi) == 2) {
                if ($arr_textfi[1] == '')
                    continue;

                $re_label = $this->db->query("select label from items_attributes where akey = '" . $arr_textfi[0] . "' and pkey = '$itm_key'");
                if ($re_label->num_rows() > 0) {
                    $row_label = $re_label->row_array();
                    $arr_attribute['label'] = $row_label['label'];
                } else {
                    $arr_attribute['label'] = $this->database->db_result("select label from attributes where status <> -1 and akey = '" . $arr_textfi[0] . "'");
                }
                $arr_attribute['name'] = $arr_textfi[1];
            } elseif (count($arr_textfi) == 1) {
                $arr_attribute['label'] = $this->database->db_result("select items_attributes.label from items_attributes join attrioptions on items_attributes.akey = attrioptions.akey where items_attributes.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                $re_pri = $this->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                if ($re_pri->num_rows() > 0) {
                    $row_pri = $re_pri->row_array();
                    $arr_attribute['name'] = $row_pri['name'];
                    $arr_attribute['price'] = $row_pri['price'];
                }
            }
            $attributes[] = $arr_attribute;
        }//3
        return $attributes;
    }

    function unshowMyWalleAccount(&$strFooter) {
        $arr_ = $this->lib->partitionString("{myWalle_account}", "{/myWalle_account}", $strFooter);
        $strFooter = $arr_[0] . $arr_[2];
    }

    function loadMyWalleAccount(&$strFooter) {
        $arr_ = $this->lib->partitionString("{myWalle_account}", "{/myWalle_account}", $strFooter);
        $myWalle_account = '';
        if ($this->check_commission == 1 && $this->total_commission > 0) {
            $myWalle_account = $arr_[1];
            $myWalle_account = str_replace("{myWalle_account}", number_format($this->total_commission, 2), $myWalle_account);
            $myWalle_account = str_replace("{total_last}", number_format($this->ordertotal - $this->total_commission, 2), $myWalle_account);
        }
        $strFooter = $arr_[0] . $myWalle_account . $arr_[2];
    }

    function saveOrders_Schedule() {
        $orders_schedule = array(
            'skey' => $this->skey
        );
        $id = $this->db->insert("orders_schedule", $orders_schedule);

        if ($id) {
            $orders = new saveOrders();
            for ($i = 0; $i < count($this->arr_schedule); $i++) {
                $orders->set_schedule($this->arr_schedule[$i]);
                $orders->saveOrders();
                //insert more*/
                $orders->saveOrders_auto_delivery($this->skey);
            }
        }
    }

    function checkItemAvailable() {
        $this->loadSchedule();
        $this->updateHandlingFee_for_Manufacturer();

        for ($s = 0; $s < count($this->arr_schedule); $s++) {
            $this->suptotal_Schedule = 0;
            $this->tax_Schedule = 0;
            $this->shippingfee_Schedule = 0;
            $this->schedule_total = 0;
            $this->calcu_Schedule($this->arr_schedule[$s]);


            $this->arr_schedule[$s]['suptotal'] = $this->suptotal_Schedule;
            $this->arr_schedule[$s]['tax_fee'] = $this->tax_Schedule;
            $this->arr_schedule[$s]['ship_fee'] = $this->shippingfee_Schedule;
            $this->arr_schedule[$s]['ordertotal'] = $this->schedule_total;

            $this->total_pay += $this->schedule_total;
        }
    }

    function calcu_Schedule($schedule) {

        $this->arr_Manufacturers = $schedule['manufacturer'];
        for ($m = 0; $m < count($this->arr_Manufacturers); $m++) {//0
            $ship_rate = $this->arr_Manufacturers[$m]['handling_fee'];
            $count_ship_free = count($this->arr_Manufacturers[$m]['items']);
            foreach ($this->arr_Manufacturers[$m]['items'] as $item) {//1
                $itm_key = $item['key'];
                $arrKeys = explode(__keycode__, $item['k']);
                $default_product_rate_last = $default_product_rate_current = round($item['default_product_rate'] * $item['sum'], 2);

                $check_shipping_free = false;

                $amount = (is_numeric($item['current_cost']) && $item['current_cost'] > 0) ? round($item['current_cost']) : 0;
                $re_price = $this->db->query("select product_markup.markup_percentage,product_markup.commission_member from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");

                if ($re_price->num_rows() > 0) {
                    $row_price = $re_price->row_array();
                    $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? $row_price['markup_percentage'] : 0;
                    $amount += round($amount * $markup_percentage / 100, 2);
                }
                for ($i = 1; $i < count($arrKeys); $i++) {
                    $arr_textfi = explode(__keyat__, $arrKeys[$i]);
                    if (count($arr_textfi) == 1) {
                        $re_pri = $this->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                        if ($re_pri->num_rows() > 0) {
                            $row_pri = $re_pri->row_array();
                            $amount +=round($row_pri['price'], 2);
                        }
                    }
                }

                $amount_last = $amount;
                if (count($schedule['promotions']) > 0) {//3
                    foreach ($schedule['promotions'] as $promotions) {//4
                        if ($promotions['itm_key'] == $itm_key) {//5
                            switch ((int) $promotions['promo_type']) {//6
                                case 1:
                                    if ($promotions['discount_type'] == 0) {
                                        $amount_last -= $amount * $promotions['discount'] / 100;
                                    } elseif ($promotions['discount_type'] == 1) {
                                        $amount_last -= round($promotions['discount'], 2);
                                    }
                                    break;
                                case 3:
                                    $check_shipping_free = true;
                                    if ($promotions['discount_type'] == 0) {
                                        $default_product_rate_last -= $default_product_rate_current * (float) $promotions['discount'] / 100;
                                    } elseif ($promotions['discount_type'] == 1) {
                                        $default_product_rate_last -= round($promotions['discount'], 2);
                                    }
                                    break;
                                case 4:
                                    $check_ok = false;
                                    for ($i = 0; $i < count($promotions['countries']); $i++) {
                                        if ($promotions['countries'][$i]['code'] == $_POST["shipping_Country"]) {
                                            if (count($promotions['countries'][$i]['states']) > 0) {
                                                foreach ($promotions['countries'][$i]['states'] as $state_code) {
                                                    if ($state_code == $_POST["shipping_State"]) {
                                                        $check_ok = true;
                                                    }
                                                }
                                            } else {
                                                $check_ok = true;
                                            }
                                            break;
                                        }
                                    }
                                    if ($check_ok == true) {
                                        $check_shipping_free = true;
                                        $default_product_rate_last = 0;
                                    }
                                    break;
                            }//6
                        }//5
                    }//4
                }//3
                if ($amount_last < 0)
                    $amount_last = 0;
                $amount_last = round($amount_last, 2);

                $total_amount_last = round($amount_last * $item['sum'], 2);

                $this->suptotal_Schedule += $total_amount_last;
                if ($item['product_type'] == 1) {
                    $default_product_rate_last = 0;
                    $count_ship_free--;
                } else {
                    $this->tax_Schedule += $this->tax_persen * $total_amount_last / 100;
                    if ($default_product_rate_last <= 0) {
                        $default_product_rate_last = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                }
                $ship_rate += $default_product_rate_last;
            }//1
            if ($ship_rate == $this->arr_Manufacturers[$m]['handling_fee'] && $count_ship_free == 0)
                $ship_rate = 0;
            $this->shippingfee_Schedule += $ship_rate;
        }//0
        $this->schedule_total = $this->suptotal_Schedule + $this->tax_Schedule + $this->shippingfee_Schedule;
    }

    function updateHandlingFee_for_Manufacturer() {
        for ($s = 0; $s < count($this->arr_schedule); $s++) {
            $shipping_key = $this->getShipKeyForSchedule($this->arr_schedule[$s]['ckey']);

            $this->arr_schedule[$s]['items_ship_selected'] = $this->get_items_ship_selected($shipping_key, $this->arr_schedule[$s]['item_ship']);
            $this->arr_schedule[$s]['promotions'] = array(); //$this->get_Promotions_for_schedule($this->arr_schedule[$s]['manufacturer'], $this->arr_schedule[$s]['ckey']);
            $this->arr_schedule[$s]['shipping_datas'] = $this->getShipData_for_schedule($shipping_key);
            for ($m = 0; $m < count($this->arr_schedule[$s]['manufacturer']); $m++) {//0
                $handling_fee_new = $this->arr_schedule[$s]['shipping_datas']['handling_fee'];
                foreach ($this->arr_schedule[$s]['manufacturer'][$m]['items'] as $item) {//1
                    for ($i = 0; $i < count($this->arr_schedule[$s]['items_ship_selected']); $i++) {
                        if ($this->arr_schedule[$s]['items_ship_selected'][$i]['pkey'] == $item['key'] && $this->arr_schedule[$s]['items_ship_selected'][$i]['skey'] == $shipping_key) {
                            if ($this->arr_schedule[$s]['items_ship_selected'][$i]['handling'] >= 0) {
                                if ($handling_fee_new < $this->arr_schedule[$s]['items_ship_selected'][$i]['handling'])
                                    $handling_fee_new = $this->arr_schedule[$s]['items_ship_selected'][$i]['handling'];
                            }
                            break;
                        }
                    }
                }

                $this->arr_schedule[$s]['manufacturer'][$m]['handling_fee'] = $handling_fee_new;
            }
        }
    }

    function getShipData_for_schedule($shipping_key) {
        $shipping_datas = array();
        $re = $this->db->query("select * from shipping_rates where status = 1 and skey = '" . $shipping_key . "'");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $shipping_datas = $row;
        }
        return $shipping_datas;
    }

    function get_Promotions_for_schedule($arr_manufacturers, $sckey) {
        $arr_promotions = array();

        if (count($arr_manufacturers) > 0) {
            foreach ($arr_manufacturers as $manufacturer) {

                $items = $manufacturer['items'];
                $this->lib->loadPromotionsObject($arr_promotions, $items['key'], $items['sum'], $sckey);
            }
        }

        return $arr_promotions;
    }

    function get_items_ship_selected($shipping_key, $item_ship) {
        $arr_items_shippings = array();
        for ($i = 0; $i < count($item_ship); $i++) {
            if ($item_ship[$i]['pkey'] == $shipping_key) {
                $arr_items_shippings[] = $item_ship[$i];
                break;
            }
        }
        return $arr_items_shippings;
    }

    function getShipKeyForSchedule($sckey) {
        $shipping_key = '';
        if (count($this->arr_shippingfee) > 0) {
            foreach ($this->arr_shippingfee as $shipping) {
                if ($shipping['ckey'] == $sckey) {
                    $shipping_key = $shipping['shipping_key'];
                    break;
                }
            }
        }
        return $shipping_key;
    }

    function loadSchedule() {
        $this->arr_schedule = (isset($_SESSION['__manufacturers__']) && is_array($_SESSION['__manufacturers__'])) ? $_SESSION['__manufacturers__'] : array();
//		var_dump($this->arr_schedule[0]['manufacturer'][0]['items']); exit;
        for ($i = 0; $i < count($this->arr_schedule); $i++) {
            for ($j = 0; $j < count($this->arr_schedule[$i]['manufacturer']); $j++) {
                $re = $this->db->query("select mail,firstname,lastname from users where uid = " . $this->arr_schedule[$i]['manufacturer'][$j]['uid']);
                if ($re->num_rows() > 0) {
                    $row = $re->row_array();
                    $this->arr_schedule[$i]['manufacturer'][$j]['mail'] = $row['mail'];
                    $this->arr_schedule[$i]['manufacturer'][$j]['firstname'] = $row['firstname'];
                    $this->arr_schedule[$i]['manufacturer'][$j]['lastname'] = $row['lastname'];
                    $receive_order_mail = 0;
                    $loadAccessUser = $this->lib->loadAccessUser($this->arr_schedule[$i]['manufacturer'][$j]['uid']);
                    if (in_array('store/orders/receive_order_mail.php', $loadAccessUser)) {
                        $receive_order_mail = 1;
                    }
                    $this->arr_schedule[$i]['manufacturer'][$j]['receive_order_mail'] = $receive_order_mail;

                    $account = array(); // Danh sach employees
                    $re_2 = $this->db->query("select users.mail,users.uid from users join manufacturers on manufacturers.uid = users.uid where users.status = 1 and manufacturers.author = " . $this->arr_schedule[$i]['manufacturer'][$j]['uid']);
                    foreach ($re_2->result_array() as $row_2) {
                        $loadAccessUser = $this->lib->loadAccessUser($row_2['uid']);
                        if (in_array('store/orders/receive_order_mail.php', $loadAccessUser)) {
                            $account[] = $row_2['mail'];
                        }
                    }
                    $this->arr_schedule[$i]['manufacturer'][$j]['account'] = $account;
                    $this->arr_schedule[$i]['manufacturer'][$j]['rows'] = '';
                    $this->arr_schedule[$i]['manufacturer'][$j]['subtotal'] = 0;
                    $this->arr_schedule[$i]['manufacturer'][$j]['shipping'] = 0;
                    $this->arr_schedule[$i]['manufacturer'][$j]['tax'] = 0;
                }
                for ($tem = 0; $tem < count($this->arr_schedule[$i]['manufacturer'][$j]['items']); $tem++) {
                    $this->arr_schedule[$i]['manufacturer'][$j]['items'][$tem]['default_product_rate'] = $this->get_default_product_rate($this->arr_schedule[$i]['manufacturer'][$j]['items'][$tem], $this->arr_schedule[$i]['ckey']);
                    $re = $this->db->query("SELECT itm_id,itm_name,itm_model,current_cost,uid,origin FROM items WHERE itm_key = '" . $this->arr_schedule[$i]['manufacturer'][$j]['items'][$tem]['key'] . "' and itm_status <> -1 ");
                    if ($re->num_rows() > 0) {
                        $row = $re->row_array();
                        $this->arr_schedule[$i]['manufacturer'][$j]['items'][$tem] = array_merge($this->arr_schedule[$i]['manufacturer'][$j]['items'][$tem], $row);
                    }
                }
            }
        }
    }

    function get_default_product_rate($item, $ckey) {
        $default_product_rate = 0;
        for ($i = 0; $i < count($this->arr_shippingfee); $i++) {
            if ($this->arr_shippingfee[$i]['ckey'] == $ckey) {
                $default_product_rate = $item['ship_rate'][$this->arr_shippingfee[$i]['shipping_key']];
                break;
            }
        }/*
          for($i = 0; $i < count($this->arr_shippingfee); $i++){
          if($this->arr_shippingfee[$i]['pkey'] == $item['key']){
          $countries = $this->arr_shippingfee[$i]['countries'];
          if(count($countries) > 0){
          foreach($countries as $contry){
          if($contry['country_code'] == $_POST["shipping_Country"]){
          if($contry['rate_type'] == 1){
          if($contry['country_rate'] >= 0){
          $default_product_rate = $contry['country_rate'];
          }
          }else{
          $states = $contry['states'];
          if(count($states) > 0){
          foreach($states as $state__){
          if($state__['state_code'] == $_POST["shipping_State"]){
          if($state__['state_rate'] >= 0){
          $default_product_rate = $state__['state_rate'];
          }
          break;
          }
          }
          }
          }
          break;
          }
          }
          }
          break;
          }
          } */
        return $default_product_rate;
    }

    function getArrShippingFee() {
        if (isset($_POST['shipping_fee']) && is_array($_POST['shipping_fee']) && count($_POST['shipping_fee']) > 0) {
            $this->arr_shippingfee = $_POST['shipping_fee'];
        }
    }

    function loadTax() {
        $re = $this->db->query("select * from tax_rates where status <> -1 order by weight DESC,name ASC");
        foreach ($re->result_array() as $row) {
            if ($row['state'] != '' && $row['state'] != $this->shipping_State)
                continue;
            $this->arr_tax[] = $row;
            $this->tax_persen += $row['rate'];
        }
    }

    function getBillAddress() {
        if (isset($_POST['billing_Name']) && $_POST['billing_Name'] != '') {
            $billing_Name = trim($_POST['billing_Name']);
            $billing_Name = str_replace("  ", " ", $billing_Name);
            $arr = explode(" ", $billing_Name);
            $this->billing_fName = isset($arr[0]) ? $arr[0] : '';
            for ($i = 1; $i < count($arr); $i++) {
                $this->billing_lName .= $arr[$i] . ' ';
            }
            $this->billing_lName = trim($this->billing_lName);
        }
        if (isset($_POST['billing_Address'])) {
            $this->billing_Address = trim($_POST['billing_Address']);
        }
        if (isset($_POST['billing_City'])) {
            $this->billing_City = trim($_POST['billing_City']);
        }
        if (isset($_POST['billing_State'])) {
            $this->billing_State = trim($_POST['billing_State']);
        }
        if (isset($_POST['billing_Country'])) {
            $this->billing_Country = trim($_POST['billing_Country']);
        }
        if (isset($_POST['billing_Zip'])) {
            $this->billing_Zip = trim($_POST['billing_Zip']);
        }
        if (isset($_POST['billing_Phone'])) {
            $this->billing_Phone = trim($_POST['billing_Phone']);
        }
        if (isset($_POST['billing_Email'])) {
            $this->billing_Email = trim($_POST['billing_Email']);
        }
    }

    function getShippingAddress() {
        if (isset($_POST['shipping_Name']) && $_POST['shipping_Name'] != '') {
            $shipping_Name = trim($_POST['shipping_Name']);
            $shipping_Name = str_replace("  ", " ", $shipping_Name);
            $arr = explode(" ", $shipping_Name);
            $this->shipping_fName = isset($arr[0]) ? $arr[0] : '';
            for ($i = 1; $i < count($arr); $i++) {
                $this->shipping_lName .= $arr[$i] . ' ';
            }
            $this->shipping_lName = trim($this->shipping_lName);
        }
        if (isset($_POST['shipping_Address'])) {
            $this->shipping_Address = trim($_POST['shipping_Address']);
        }
        if (isset($_POST['shipping_City'])) {
            $this->shipping_City = trim($_POST['shipping_City']);
        }
        if (isset($_POST['shipping_State'])) {
            $this->shipping_State = trim($_POST['shipping_State']);
        }
        if (isset($_POST['shipping_Country'])) {
            $this->shipping_Country = trim($_POST['shipping_Country']);
        }
        if (isset($_POST['shipping_Zip'])) {
            $this->shipping_Zip = trim($_POST['shipping_Zip']);
        }
        if (isset($_POST['shipping_Phone'])) {
            $this->shipping_Phone = trim($_POST['shipping_Phone']);
        }
    }

    function getCreditCardInfo() {
        if (isset($_POST["cc_Card_Number"]) && $_POST["cc_Card_Number"] != '') {
            $this->cardNumber = trim($_POST["cc_Card_Number"]);
            $this->card4digis = substr($this->cardNumber, strlen($this->cardNumber) - 4, strlen($this->cardNumber));
        }
        if (isset($_POST['cc_Card_Month']) && is_numeric($_POST['cc_Card_Month']) && $_POST['cc_Card_Month'] > 0)
            $this->cc_Card_Month = $_POST['cc_Card_Month'];
        if (isset($_POST['cc_Card_Year']) && is_numeric($_POST['cc_Card_Year']) && $_POST['cc_Card_Year'] > 0)
            $this->cc_Card_Year = $_POST['cc_Card_Year'];
        if (isset($_POST['cc_Card_Cvc']))
            $this->cc_Card_Cvc = trim($_POST['cc_Card_Cvc']);
    }

}

class saveOrders extends payAutoShip {

    var $schedule = array();
    var $okey = '';
    var $r_ordernum = '';
    var $r_tdate = 0;
    var $order_number = 0;
    var $sale_rep_obj;
    var $autoShip;
    var $total_commission = 0;
    var $odetail = 0;
    var $commission_member = 0;
    var $arrCharities_truct = array();
    var $arrCharities_notruct = array();
    

    function __construct() {
        $this->r_tdate = $this->lib->getTimeGMT();
        parent::__construct();
        $this->getArrCharities();
    }

    function set_schedule($schedule) {
        $this->schedule = $schedule;
    }

    function buildOkey() {
        $this->okey = $this->lib->GeneralRandomNumberKey(8);
        $re = $this->db->query("select orderid from orders where okey = '" . $this->okey . "'");
        foreach ($re->result_array() as $row) {
            $this->okey = $this->lib->GeneralRandomNumberKey(8);
            $re = $this->db->query("select orderid from orders where okey = '" . $this->okey . "'");
        }
    }
    
     private function getArrCharities() {
        $query = $this->db->query("select charities.legal_business_id,charities.trust from charities join users on charities.uid = users.uid where users.status = 1");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($row['trust'] == 1)
                    $this->arrCharities_truct[] = $row['legal_business_id'];
                else
                    $this->arrCharities_notruct[] = $row['legal_business_id'];
            }
        }
    }
    function saveOrders() {
        $last_general_settup = $this->general->getLastGeneralSetting();
        $this->buildOkey();
        $data = array(
            'r_ordernum' => $this->r_ordernum,
            'r_tdate' => $this->r_tdate,
            'okey' => $this->okey,
            "shipping_name" => $this->lib->escape($this->shipping_fName . ' ' . $this->shipping_lName),
            "shipping_address" => $this->lib->escape($this->shipping_Address),
            "shipping_city" => $this->lib->escape($this->shipping_City),
            "shipping_state" => $this->lib->escape($this->shipping_State),
            "shipping_zip" => $this->lib->escape($this->shipping_Zip),
            "shipping_country" => $this->lib->escape($this->shipping_Country),
            "shipping_phone" => $this->lib->escape($this->shipping_Phone),
            "billing_name" => $this->lib->escape($this->billing_fName . ' ' . $this->billing_lName),
            "billing_address" => $this->lib->escape($this->billing_Address),
            "billing_city" => $this->lib->escape($this->billing_City),
            "billing_state" => $this->lib->escape($this->billing_State),
            "billing_country" => $this->lib->escape($this->billing_Country),
            "billing_zip" => $this->lib->escape($this->billing_Zip),
            "billing_phone" => $this->lib->escape($this->billing_Phone),
            "billing_email" => $this->lib->escape($this->billing_Email),
            "order_tax" => $this->tax_persen,
            "shipping_fee" => $this->schedule['ship_fee'],
            "shipping_key" => $this->schedule['shipping_datas']['skey'],
            "order_total" => $this->schedule['ordertotal'],
            "order_date" => gmdate("Y-m-d H:i:s"),
            "user_id" => $this->uid,
            "card_number" => $this->card4digis,
            "customerPaymentProfileId" => $this->customerPaymentProfileId,
            "customerProfileId" => $this->customerProfileId,
            "customerAddressId" => $this->customerAddressId,
            "cc_month" => $this->cc_Card_Month,
            "cc_year" => $this->cc_Card_Year,
            'com_set_id' => $last_general_settup['id']
        );
        $this->db->insert('orders', $data);
        $this->order_number = $this->db->insert_id();
        $this->saveOrderHandling();
        $this->activeAccount();
        $this->saveOrderDetail(); 
        $this->saveCommission_monthly();
        return $this->order_number;	
    }

    function saveOrderDetail() {

        for ($m = 0; $m < count($this->schedule['manufacturer']); $m++) {//0
            $commission_member = 0;
            $items = $this->schedule['manufacturer'][$m]['items'];
            $ship_rate = $this->schedule['manufacturer'][$m]['handling_fee'];
            for ($t = 0; $t < count($items); $t++) {
                $datail = $items[$t];
                $itm_key = $datail['key'];
                //insert code here 

                $commission_charities = 0;
                $commission_trust_charity = 0;
                $commission_employees_bonus = 0;
                $credit_merchant = 0;
                $commission_member = 0;
                $commission_affiliate = 0;
                $markup_id = 0;
                $mkey = '';

                //end insert code here
                $current_cost = $datail['current_cost'];
                $arrKeys = explode(__keycode__, $datail['k']);

                $re_price = $this->db->query("select product_markup.* from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");

                if ($re_price->num_rows() > 0) {
                    $row_price = $re_price->row_array();
                    $markup_percentage = (is_numeric($row_price['markup_percentage']) && $row_price['markup_percentage'] > 0) ? $row_price['markup_percentage'] : 0;
                    $current_cost += round($current_cost * $markup_percentage / 100, 2);
                    $commission_member = $row_price['commission_member'];
                    $markup_id = $row_price['id'];
                    $commission_charities = $row_price['commission_charities'];
                    $commission_trust_charity = $row_price['commission_trust_charity'];
                    $commission_employees_bonus = $row_price['commission_employees_bonus'];
                    $credit_merchant = $row_price['credit_merchant'];
                    $commission_affiliate = $row_price['commission_affiliate'];
                    $mkey = $row_price['mkey'];
                }

                $this->commission_member = $commission_member;
                for ($i = 1; $i < count($arrKeys); $i++) {
                    $arr_textfi = explode(__keyat__, $arrKeys[$i]);
                    if (count($arr_textfi) == 1) {
                        $re_pri = $this->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '{$datail['key']}' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                        if ($re_pri->num_rows() > 0) {
                            $row_pri = $re_pri->row_array();
                            $current_cost +=round($row_pri['price'], 2);
                        }
                    }
                }

                //insert code 
                $default_product_rate_last = $default_product_rate_current = round($datail['default_product_rate'] * $datail['sum'], 2);
                $amount = $current_cost;
                $amount_last = $amount;
                if (count($this->schedule['promotions']) > 0) {//3
                    foreach ($this->schedule['promotions'] as $promotions) {//4
                        if ($promotions['itm_key'] == $itm_key) {//5
                            switch ((int) $promotions['promo_type']) {//6
                                case 1:
                                    if ($promotions['discount_type'] == 0) {
                                        $amount_last -= $amount * $promotions['discount'] / 100;
                                    } elseif ($promotions['discount_type'] == 1) {
                                        $amount_last -= round($promotions['discount'], 2);
                                    }
                                    break;
                                case 3:
                                    $check_shipping_free = true;
                                    if ($promotions['discount_type'] == 0) {
                                        $default_product_rate_last -= $default_product_rate_current * (float) $promotions['discount'] / 100;
                                    } elseif ($promotions['discount_type'] == 1) {
                                        $default_product_rate_last -= round($promotions['discount'], 2);
                                    }
                                    break;
                                case 4:
                                    $check_ok = false;
                                    for ($i = 0; $i < count($promotions['countries']); $i++) {
                                        if ($promotions['countries'][$i]['code'] == $_POST["shipping_Country"]) {
                                            if (count($promotions['countries'][$i]['states']) > 0) {
                                                foreach ($promotions['countries'][$i]['states'] as $state_code) {
                                                    if ($state_code == $_POST["shipping_State"]) {
                                                        $check_ok = true;
                                                    }
                                                }
                                            } else {
                                                $check_ok = true;
                                            }
                                            break;
                                        }
                                    }
                                    if ($check_ok == true) {
                                        $check_shipping_free = true;
                                        $default_product_rate_last = 0;
                                    }
                                    break;
                            }//6
                        }//5
                    }//4
                }//3
                if ($amount_last < 0)
                    $amount_last = 0;
                $amount_last = round($amount_last, 2);

                $total_amount_last = round($amount_last * $datail['sum'], 2);
                if ($datail['product_type'] == 1) {
                    $default_product_rate_last = 0;
                    $count_ship_free--;
                } else {
                    $this->tax_Schedule = $this->tax_persen * $total_amount_last / 100;
                    if ($default_product_rate_last <= 0) {
                        $default_product_rate_last = 0;
                        if ($check_shipping_free == true)
                            $count_ship_free--;
                    }
                }
                $ship_rate += $default_product_rate_last;
                $pay = $total_amount_last + $this->tax_Schedule + $ship_rate;

                //end insert code 
                $order_detail = array(
                    'orderid' => $this->order_number,
                    'itemid' => $datail['itm_id'],
                    'itemprice' => $current_cost,
                    'last_itemprice' => $current_cost,
                    'current_cost' => $datail['current_cost'],
                    'last_cost' => $datail['current_cost'],
                    'quality' => $datail['sum'],
                    'shipping_fee' => (is_numeric($default_product_rate_last) && $default_product_rate_last > 0) ? $default_product_rate_last : 0,
                    'last_shipping' => (is_numeric($datail['default_product_rate']) && $datail['default_product_rate'] > 0) ? $datail['default_product_rate'] : 0,
                    'tax_persend' => $this->tax_persen
                );

                $this->db->insert('order_detais', $order_detail);
                $odetail = $this->db->insert_id();
                $this->saveCommission($odetail, $itm_key, $commission_trust_charity, $commission_charities, $commission_employees_bonus, $credit_merchant, $commission_affiliate, $mkey);
                $this->savePayments($pay);
            }
        }
    }

    function saveOrders_auto_delivery($skey) {
        $orders_auto_delivery = array(
            'oid' => $this->order_number,
            'schedule_date' => date("Y-m-d", strtotime($this->schedule['cdate'])),
            'skey' => $skey//$this->skey
        );
        $this->db->insert('orders_auto_delivery', $orders_auto_delivery);
    }

    function activeAccount() {
        $this->db->update('representatives', array('purchase_active' => 1), "uid = " . $this->uid);
    }

    function savePayments($total_commission) {

        $this->sale_rep_obj = new sale_rep($this->uid);
        // $this->total_commission = $this->sale_rep_obj->getTotalEarning();
        if ($this->check_commission > 0) {//$this->total_commission
            $legal_business_id = $this->sale_rep_obj->getLegalBusinessID();
            $legal_business_name = $this->sale_rep_obj->getLegalBusinessName();
            $pay_key = $this->lib->GeneralRandomKey(20);
            $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
            foreach ($re_key->result_array() as $row_key) {
                $pay_key = $this->lib->GeneralRandomKey(20);
                $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
            }
            $datas = array(
                'pkey' => $pay_key,
                'role' => 9,
                'legal_business_id' => $legal_business_id,
                'legal_business_name' => $legal_business_name,
                'pay' => $total_commission,
                'date_pay' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
                'pay_type' => 1
            );
            $id = $this->db->insert("payments", $datas);
            if ($id) {
                $payments_orders = array(
                    'pkey' => $pay_key,
                    'okey' => $this->okey,
                    'pay' => $total_commission
                );
                $this->db->insert("payments_orders", $payments_orders);
            }
        }
    }

    function saveOrderHandling() {
        for ($m = 0; $m < count($this->schedule['manufacturer']); $m++) {//0
            $orders_handling = array(
                'oid' => $this->order_number,
                'uid' => $this->schedule['manufacturer'][$m]['uid'],
                'handling' => $this->schedule['manufacturer'][$m]['handling_fee']
            );
            $this->db->insert('orders_handling', $orders_handling);
        }
    }

    //insert code here

    function saveCommission_monthly() {
        $uid_com_monthly = 0;
        if ($this->roles['rid'] == 6 || $this->roles['rid'] == 9) {//representatives
            $uid_com_monthly = $this->uid;
        }

        if ($uid_com_monthly > 0) {
            $commission_monthly = array(
                'uid' => $uid_com_monthly,
                'oid' => $this->order_number,
                'date_add' => gmdate("Y-m-d H:i:s")
            );
            $this->db->insert('commission_monthly', $commission_monthly);
        }
    }

    function saveCommission($odetail, $itm_key, $commission_trust_charity, $commission_charities, $commission_employees_bonus, $credit_merchant, $commission_affiliate, $mkey) {

        $uid_com_monthly = 0;
        $akey = '';
     
        if ($this->roles['rid'] == 6 || $this->roles['rid'] == 9) {//representatives
            $uid_com_monthly = $this->uid;
        }

        $LevelSales = array();
        if ($uid_com_monthly > 0) {
            $LevelSales = $this->lib->__getLevelSale__($uid_com_monthly);
        }
        if ($uid_com_monthly > 0) {
            $re_com_sale = $this->db->query("select commission from commission_salerep_items where item_key = '$itm_key'");
            if ($re_com_sale->num_rows() > 0) {
                $row_com_sale = $re_com_sale->row_array();
                if ($row_com_sale['commission'] != null && $row_com_sale['commission'] != '') {
                    $commission_sale = explode("|", $row_com_sale['commission']);
                    for ($cs = 0; $cs < count($LevelSales); $cs++) {
                        if ($LevelSales[$cs]['status'] == 1) {
                            if (isset($commission_sale[$cs]) && is_numeric($commission_sale[$cs]) && $commission_sale[$cs] > 0) {

                                $commission_monthly_items = array(
                                    'uid' => $LevelSales[$cs]['uid'],
                                    'upurchase' => $uid_com_monthly,
                                    'oid' => $this->order_number,
                                    'odetail' => $odetail,
                                    'commission' => $commission_sale[$cs],
                                    'purchase_date' => date("Y-m-d H:i:s", $this->lib->getTimeGMT())
                                );
                                $this->db->insert('commission_monthly_items', $commission_monthly_items);
                            }
                        }
                    }
                }
            }

            $commission_monthly_items = array(
                'uid' => $uid_com_monthly,
                'upurchase' => $uid_com_monthly,
                'oid' => $this->order_number,
                'odetail' => $odetail,
                'commission' => $this->commission_member,
                'purchase_date' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
                'status' => 1,
                'personal_discount' => 1
            );
            $this->db->insert('commission_monthly_items', $commission_monthly_items);
        }
        //save no trust charities
        if ($commission_charities > 0 && count($this->arrCharities_notruct) > 0 ) {
            $count_charities = count($this->arrCharities_notruct);
            foreach($this->arrCharities_notruct as $charities_key){
            $data_commission = array(
                'orderid' => $this->order_number,
                'pkey' => $itm_key,
                'mkey' => $mkey,
                'commission' => round($commission_charities / $count_charities, 10),
                'purchase_date' => gmdate("Y-m-d H:i:s"),
                'odetail' => $odetail,
                'rid' => 8,
                'legal_business_id' =>$charities_key
            );
            $this->db->insert('commission_charities', $data_commission);
            }
        }
        //save trust charities
         if ($commission_trust_charity > 0 && count($this->arrCharities_truct) > 0) {
                    $count_trust_charities = count($this->arrCharities_truct);
                    foreach ($this->arrCharities_truct as $charity_key) {
                        $data_commission = array(
                            'orderid' => $this->order_number,
                            'pkey' => $itm_key,
                            'mkey' => $mkey,
                            'commission' => round($commission_trust_charity / $count_trust_charities, 10),
                            'purchase_date' => gmdate("Y-m-d H:i:s"),
                            'odetail' => $odetail,
                            'rid' => 8,
                            'legal_business_id' => $charity_key
                        );
                        $this->db->insert('commission_charities', $data_commission);
                    }
                }

        if ($commission_employees_bonus > 0) {
            $data_commission = array(
                'orderid' => $this->order_number,
                'pkey' => $itm_key,
                'mkey' => $mkey,
                'commission' => $commission_employees_bonus,
                'purchase_date' => gmdate("Y-m-d H:i:s"),
                'odetail' => $odetail,
                'rid' => 0
            );
            $this->db->insert('commission_charities', $data_commission);
        }
        if ($credit_merchant > 0) {
            $data_commission = array(
                'orderid' => $this->order_number,
                'pkey' => $itm_key,
                'mkey' => $mkey,
                'commission' => $credit_merchant,
                'purchase_date' => gmdate("Y-m-d H:i:s"),
                'odetail' => $odetail,
                'rid' => -1
            );
            $this->db->insert('commission_charities', $data_commission);
        }
        if ($akey != '') {
            $data_commissoin_aff = array(
                'akey' => $akey,
                'orderid' => $this->order_number,
                'pkey' => $itm_key,
                'mkey' => $mkey,
                'commission' => $commission_affiliate,
                'purchase_date' => gmdate("Y-m-d H:i:s"),
                'odetail' => $odetail,
                'rid' => 6,
                'status' => 0
            );
            $this->db->insert('commission_affiliate', $data_commissoin_aff);
        }
        //end insert code here 
    }

}