<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class lib {

    var $CI;
    var $shop_data_url_string = 'shopping/data/img/';

    function __construct() {
        $this->CI = & get_instance();
    }

    public function shop_data_url() {
        return $this->CI->system->URL_server__() . $this->shop_data_url_string;
    }

    public function cleanSpecialCharsFromUrl($string) {
        if (isset($string) && !empty($string)) {
            return preg_replace('/[^A-Za-z0-9\-\_]/', '', $string);
        }
    }

    function escape($str) {
        $str = trim($str);
        if (is_string($str)) {
            $str = htmlentities($str, ENT_QUOTES, 'utf-8');
        } elseif (is_bool($str)) {
            $str = ($str === FALSE) ? 0 : 1;
        } elseif (is_null($str)) {
            $str = 'NULL';
        }
        return $str;
    }

    function ConvertToHtml($str) {
        $array = array("\r\n", "\n\r", "\n", "\r");
        $str = str_replace($array, "<br>", $str);
        $str = str_replace('\"', '&quot;', $str);
        $str = str_replace('"', '&quot;', $str);
        $str = str_replace("\'", '&acute;', $str);
        $str = str_replace("'", '&acute;', $str);
        $str = str_replace("\\\\", "\\", $str);
        return $str;
    }

    function ConvertToTest($str) {
        $replace = array("\\0", "\n", "\r", "\Z", "\'", '\"');
        $search = array("\0", "\\n", "\\r", "\x1a", "\'", '\"');
        $str = html_entity_decode($str, ENT_QUOTES, 'utf-8');
        return str_replace($replace, $search, $str);
    }

    function ConvertToSQL($str) {
        $str = trim($str);
        $str = str_replace("\\", "\\\\", $str);
        $str = str_replace("\n", "\\n", $str);
        $str = str_replace("\r", "\\r", $str);
        $str = str_replace("\x1a", "\\Z", $str);
        $str = str_replace("'", "\'", $str);
        $str = str_replace('"', '\"', $str);
        return $str;
    }

    function FCKToSQL($str) {
        $str = trim($str);
        $str = str_replace("\\", "\\\\", $str);
        $str = str_replace("\n", "\\n", $str);
        $str = str_replace("\r", "\\r", $str);
        $str = str_replace("\x1a", "\\Z", $str);
        $str = str_replace("'", "\'", $str);
        $str = str_replace('"', '\"', $str);
        return $str;
    }

    function SQLToFCK($str) {
        $str = trim($str);
        $str = str_replace("\\\\", "\\", $str);
        $str = str_replace("\\n", "\n", $str);
        $str = str_replace("\\r", "\r", $str);
        $str = str_replace("\'", "'", $str);
        $str = str_replace('\"', '"', $str);
        return $str;
    }

    function GetSystemValues($title) {
        $arr = array();
        $query = $this->CI->db->query("SELECT sysval_value FROM sysvals WHERE sysval_title = '$title' LIMIT 1");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $sep1 = "\n"; // item separator
            $sep2 = "|"; // alias separator
            $temp = explode($sep1, $row['sysval_value']);
            if (is_array($temp) && count($temp) > 0) {
                foreach ($temp as $item) {
                    if ($item != '' && $item != null) {
                        $temp2 = explode($sep2, $item);
                        if (isset($temp2[1])) {
                            $arr[trim($temp2[0])] = trim($temp2[1]);
                        } else {
                            $arr[trim($temp2[0])] = trim($temp2[0]);
                        }
                    }
                }
            }
        }
        return $arr;
    }

    function GeneralRandomKey($size) {
        $keyset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_";
        $randkey = "";
        for ($i = 0; $i < $size; $i++)
            $randkey .= substr($keyset, rand(0, strlen($keyset) - 1), 1);
        return $randkey;
    }

    function GeneralRandomReferralCode($size) {
        $keyset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randkey = "";
        for ($i = 0; $i < $size; $i++)
            $randkey .= substr($keyset, rand(0, strlen($keyset) - 1), 1);
        return $randkey;
    }

    function GeneralRandomNumberKey($size) {
        $keyset = "0123456789";
        $randkey = "";
        for ($i = 0; $i < $size; $i++)
            $randkey .= substr($keyset, rand(0, strlen($keyset) - 1), 1);
        return $randkey;
    }
	
	//Old function load countries.
    function __loadDataCountries2__() {
        $arr = array();
        $query = $this->CI->db->query("select * from tblcontries order by name asc");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $arr_states = array();
                $arr_cities = array();
                if ($row['status'] == 1) {
                    $query2 = $this->CI->db->query("select * from tblsates where idcountry = " . $row['id'] . " order by name asc");
                    if ($query2->num_rows() > 0) {
                        foreach ($query2->result_array() as $row_2) {
                            $arr_states[] = $row_2;
                        }
                    }
                    $query3 = $this->CI->db->query("select * from tblcities where idcountry = " . $row['id'] . " order by city asc");
                    if ($query3->num_rows() > 0) {
                        foreach ($query3->result_array() as $row_3) {
                            $arr_cities[] = $row_3;
                        }
                    }
                }
                $row['states'] = $arr_states;
                $row['cities'] = $arr_cities;
                $row['codedefault'] = $this->getcode();
                $arr[] = $row;
            }
        }
        return $arr;
    }

    //Qui Add
    function __loadDataCountries__() {
        $query_country = $this->CI->db->query("SELECT id, name, code FROM tblcontries ORDER BY id ASC");
        $re_co = $query_country->result_array();
        $query_city = $this->CI->db->query("SELECT id, idcountry, city FROM tblcities ORDER BY idcountry, city ASC");
        $re_ci = $query_city->result_array();
        $query_state = $this->CI->db->query("SELECT id, idcountry, name, code  FROM tblsates ORDER BY idcountry, name ASC");
        $re_st = $query_state->result_array();

        if ($query_country->num_rows() <= 0)
            return array();

        $position_ci = 0;
        $position_st = 0;

        for ($i = 0; $i < count($re_co); $i++) {
            $re_co[$i]['states'] = array();
            $re_co[$i]['cities'] = array();
			$re_co[$i]['codedefault'] = $this->getcode();
            $id = $re_co[$i]['id'];
            while ($position_ci < count($re_ci) && $re_ci[$position_ci]['idcountry'] <= $id) {
                if ($re_ci[$position_ci]['idcountry'] == $id) {
                    unset($re_ci[$position_ci]['idcountry']);
                    $re_co[$i]['cities'][] = $re_ci[$position_ci];
                }
                $position_ci++;
            }

            while ($position_st < count($re_st) && $re_st[$position_st]['idcountry'] <= $id) {
                if ($re_st[$position_st]['idcountry'] == $id) {
                    unset($re_st[$position_st]['idcountry']);
                    $re_co[$i]['states'][] = $re_st[$position_st];
                }
                $position_st++;
            }
        }
        $re_co = $this->sorts_array($re_co, 'name', 'asc');
        return $re_co;
    }

    function getcode() {
        $this->CI->load->helper('geoip');
        // open the database file that you've purchased from MaxMind
        $gi = geoip_open("application/helpers/GeoIP.dat", GEOIP_STANDARD);
        // The remote client/visitor's IP address
        $remote_ip = $this->getIP();
        $ip = geoip_country_code_by_addr($gi, $remote_ip);
        geoip_close($gi);
        return $ip;
    }

    function getIP() {
        $ip = '';
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (isset($_SERVER["REMOTE_ADDR"]))
            $ip = $_SERVER["REMOTE_ADDR"];
        return $ip;
    }

    function loadWeightListtings($weight = 0) {
        $str = '';
        for ($i = 50; $i > -51; $i--) {
            $select = '';
            if ($i == $weight)
                $select = 'selected="selected"';
            $str .= '<option value="' . $i . '" ' . $select . '>' . $i . '</option>';
        }
        return $str;
    }

    function __loadBoss__() {
        $ong_chu = $this->CI->author->objlogin->uid;
        $role = $this->CI->author->loadRole($ong_chu);
        if ($role['rid'] == MANUFACTURER) {
            $check_ok = false;
            while (!$check_ok) {
                $author = 0;
                $re = $this->CI->db->query("select author from manufacturers where uid = " . $ong_chu);
                if ($re->num_rows() > 0) {
                    $row = $re->row_array();
                    $author = $row['author'];
                }
                //$author = $this->CI->database->db_result("select author from manufacturers where uid = ".$ong_chu);
                $role = $this->CI->author->loadRole($author);
                if ($role['rid'] != MANUFACTURER) {
                    return $ong_chu;
                } else {
                    $ong_chu = $author;
                }
            }
        }
        return $ong_chu;
    }

    function showMoney($num, $decimal = 2) {
        $str = '';
        if ($num < 0) {
            $str = '$(' . number_format(abs($num), $decimal) . ')';
        } else {
            $str = '$' . number_format(abs($num), $decimal);
        }
        return $str;
    }

    function getTimeGMT() {
		date_default_timezone_set('Asia/Ho_Chi_Minh');
        //return strtotime(gmdate("Y-m-d H:i:s"));
        return time();
    }

    /* -------------------File Product------------------ */

    function __loadFileProduct__($itemID, $folder = 'thumb_home') {
        if (!isset($folder) || $folder == null || $folder == '')
            $folder = 'thumb_home';
        $video = 0;
        $file = '';
        $file_ = $this->CI->database->db_result("select filename from items_files where tid = " . $itemID . " order by weight DESC ");
        if (is_file($this->shop_data_url_string . $folder . "/$file_"))
            $file = $file_;
        else {
            $arr_file_del = explode(".", $file_);
            if (count($arr_file_del) > 0) {
                $fileid_del = '';
                for ($i = 0; $i < count($arr_file_del) - 1; $i++) {
                    $fileid_del .= $arr_file_del[$i] . '.';
                }
                if ($fileid_del != '') {
                    $fileid_del .= 'jpg';
                }

                if (is_file($this->shop_data_url_string . $folder . "/$fileid_del")) {
                    $file = $fileid_del;
                    $video = 1;
                }
            }
        }

        return array('video' => $video, 'file' => $file);
    }

    function load_items_promotion($itm_key) {
        $arr_promotions = array();
        $re_2 = $this->CI->db->query("select promotions.start_date,promotions.end_date,promotions.promo_name from items_promotion join promotions on promotions.promo_code = items_promotion.promo_key where items_promotion.item_key = '$itm_key' and promotions.status = 1");
        foreach ($re_2->result_array() as $row_2) {
            if ($row_2['start_date'] == null || $row_2['start_date'] == '')
                $row_2['start_date'] = 0;
            if ($row_2['end_date'] == null || $row_2['end_date'] == '')
                $row_2['end_date'] = 0;
            if ($row_2['start_date'] <= time()) {
                if ($row_2['end_date'] == 0 || ($row_2['end_date'] != 0 && $row_2['end_date'] >= time())) {
                    $arr_promotions[] = array(
                        'promo_name' => $row_2['promo_name']
                    );
                }
            }
        }
        return $arr_promotions;
    }

//load_items_promotion

    function load_items_stars($itm_id) {
        $str = '<ul class="rating">';
        $itm_id = intval($itm_id);
        $sql = "SELECT itm_id, ROUND(SUM(`rating`)/COUNT(*)) AS stars FROM `reviews` where status = 1 group by itm_id having itm_id = $itm_id";
        $query = $this->CI->db->query($sql);
        $star = 0;
        if (count($query->result_array()) > 0) {
            $row = $query->row_array();
            $star = $row['stars'];
        }
        for ($i = 1; $i <= $star; $i++) {
            $str .= '<li><i class="star-on"></i></li>';
        }
        for ($i = 1; $i <= (5 - $star); $i++) {
            $str .= '<li><i class="star-off"></i></li>';
        }
        $str .= '</ul>';
        return $str;
    }

    function load_items_starsreviews($itm_id) {
        $str = '<ul class="rating">';
        $itm_id = intval($itm_id);
        $sql = "SELECT itm_id, ROUND(SUM(`rating`)/COUNT(*)) AS stars, COUNT(*) AS numberofreviews FROM `reviews` where status = 1 group by itm_id having itm_id = $itm_id";
        $query = $this->CI->db->query($sql);
        $star = 0;
        $review = 0;
        if (count($query->result_array()) > 0) {
            $row = $query->row_array();
            $star = $row['stars'];
            $review = $row['numberofreviews'];
        }
        var_dump($review);
        for ($i = 1; $i <= $star; $i++) {
            $str .= '<li><i class="star-on"></i></li>';
        }
        for ($i = 1; $i <= (5 - $star); $i++) {
            $str .= '<li><i class="star-off"></i></li>';
        }
        $str .= '</ul>';
        $str .= '<strong><a href="#">' . $review . ' Reviews</a></strong>';
        return $str;
    }

    function loadParentCategories($value = 0, $owned = 0, $fild = 'cat_id') {
        $str = '';
        if (!isset($fild) || $fild == '')
            $fild = 'cat_id';
        $re = $this->CI->db->query("select cat_id,cat_name,cat_key from categories where status = 1 and fid = 0 and $fild <> '$owned' order by weight DESC,cat_name ASC");
        foreach ($re->result_array() as $row) {
            $select = '';
            if ($row[$fild] == $value)
                $select = 'selected="selected"';
            $str .= '<option value="' . $row[$fild] . '" ' . $select . '>' . $row['cat_name'] . '</option>';
            $this->loadParentCategories_child($str, $row['cat_id'], '---', $value, $owned, $fild);
        }
        return $str;
    }

    function loadParentCategories_child(&$str, $id, $padding_left, $value, $owned, $fild) {
        $re = $this->CI->db->query("select cat_id,cat_name,cat_key from categories where status = 1 and fid = '$id' and $fild <> '$owned' order by weight DESC,cat_name ASC");
        foreach ($re->result_array() as $row) {
            $select = '';
            if ($row[$fild] == $value)
                $select = 'selected="selected"';
            $str .= '<option value="' . $row[$fild] . '" ' . $select . '>' . $padding_left . ' ' . $row['cat_name'] . '</option>';
            $this->loadParentCategories_child($str, $row['cat_id'], $padding_left . '---', $value, $owned, $fild);
        }
    }

    function loadSiteInfo() {
        $data = array();
        $re = $this->CI->db->query("select * from  site_info");
        if ($re->num_rows() > 0) {
            $data = $re->row_array();
        }
        return $data;
    }

   /* function sendmailtype($mailto, $mailtype, $variables = array()) {
        $subject = '';
        $mailcontent = '';

        $variables_ = array(
            '!username' => isset($variables['!username']) ? $variables['!username'] : '',
            '!full_name' => isset($variables['!full_name']) ? $variables['!full_name'] : '',
            '!site' => $this->CI->system->siteInfo['site_name'],
            '!password' => isset($variables['!password']) ? $variables['!password'] : '',
            '!uri' => '<a href="' . $this->CI->system->URL_server__() . '">' . $this->CI->system->siteInfo['site_name'] . '</a>',
            '!mailto' => isset($variables['!mailto']) ? $variables['!mailto'] : $mailto,
            '!date' => date('m/d/Y'),
            '!login_uri' => '<a href="' . $this->CI->system->cleanUrl() . 'login">' . $this->CI->system->cleanUrl() . 'login</a>',
            '!edit_uri' => '<a href="' . $this->CI->system->cleanUrl() . 'login">' . $this->CI->system->cleanUrl() . 'user/myaccount</a>',
            '!login_url' => '<a href="' . $this->CI->system->cleanUrl() . 'login">' . $this->CI->system->cleanUrl() . 'login</a>',
            '!signature' => $this->CI->system->siteInfo['signature'],
            '!order_key' => isset($variables['!order_key']) ? $variables['!order_key'] : '',
            '!reason' => isset($variables['!reason']) ? $variables['!reason'] : '',
            '!Irep_firstname' => isset($variables['!Irep_firstname']) ? $variables['!Irep_firstname'] : '',
            '!Irep_lastname' => isset($variables['!Irep_lastname']) ? $variables['!Irep_lastname'] : '',
            '!Irep_ID' => isset($variables['!Irep_ID']) ? $variables['!Irep_ID'] : '',
            '!Irep_mail' => isset($variables['!Irep_mail']) ? $variables['!Irep_mail'] : '',
            '!enroll_date' => isset($variables['!enroll_date']) ? $variables['!enroll_date'] : '',
            '!Minimum_purchased' => (isset($variables['!Minimum_purchased']) && is_numeric($variables['!Minimum_purchased'])) ? number_format($variables['!Minimum_purchased'], 2) : '0.00'
        );

        $re = $this->CI->db->query("select titlemail,contentmail from mailconfig where name = '$mailtype'");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $subject = $this->SQLToFCK($row['titlemail']);
            $subject = str_replace("!username", $variables_['!username'], $subject);
            $subject = str_replace("!site", $variables_['!site'], $subject);
            $subject = str_replace("!password", $variables_['!password'], $subject);
            $subject = str_replace("!uri", $variables_['!uri'], $subject);
            $subject = str_replace("!mailto", $variables_['!mailto'], $subject);
            $subject = str_replace("!date", $variables_['!date'], $subject);
            $subject = str_replace("!login_uri", $variables_['!login_uri'], $subject);
            $subject = str_replace("!edit_uri", $variables_['!edit_uri'], $subject);
            $subject = str_replace("!login_url", $variables_['!login_url'], $subject);
            $subject = str_replace("!signature", $variables_['!signature'], $subject);
            $subject = str_replace("!order_key", $variables_['!order_key'], $subject);
            $subject = str_replace("!reason", $variables_['!reason'], $subject);
            $subject = str_replace("!Minimum_purchased", $variables_['!Minimum_purchased'], $subject);

            $mailcontent = $this->SQLToFCK($row['contentmail']);
            $mailcontent = str_replace("!username", $variables_['!username'], $mailcontent);
            $mailcontent = str_replace("!site", $variables_['!site'], $mailcontent);
            $mailcontent = str_replace("!password", $variables_['!password'], $mailcontent);
            $mailcontent = str_replace("!uri", $variables_['!uri'], $mailcontent);
            $mailcontent = str_replace("!mailto", $variables_['!mailto'], $mailcontent);
            $mailcontent = str_replace("!date", $variables_['!date'], $mailcontent);
            $mailcontent = str_replace("!login_uri", $variables_['!login_uri'], $mailcontent);
            $mailcontent = str_replace("!edit_uri", $variables_['!edit_uri'], $mailcontent);
            $mailcontent = str_replace("!login_url", $variables_['!login_url'], $mailcontent);
            $mailcontent = str_replace("!signature", $variables_['!signature'], $mailcontent);
            $mailcontent = str_replace("!order_key", $variables_['!order_key'], $mailcontent);
            $mailcontent = str_replace("!full_name", $variables_['!full_name'], $mailcontent);
            $mailcontent = str_replace("!Irep_firstname", $variables_['!Irep_firstname'], $mailcontent);
            $mailcontent = str_replace("!Irep_lastname", $variables_['!Irep_lastname'], $mailcontent);
            $mailcontent = str_replace("!Irep_ID", $variables_['!Irep_ID'], $mailcontent);
            $mailcontent = str_replace("!Irep_mail", $variables_['!Irep_mail'], $mailcontent);
            $mailcontent = str_replace("!enroll_date", $variables_['!enroll_date'], $mailcontent);
            $mailcontent = str_replace("!Minimum_purchased", $variables_['!Minimum_purchased'], $mailcontent);
        }
        $this->CI->load->library('email');
        $this->CI->email->from($this->CI->system->siteInfo['email'], $this->CI->system->siteInfo['sender_name']);
        $this->CI->email->to($mailto);
        $this->CI->email->subject($subject);
        $this->CI->email->message($mailcontent);
        $this->CI->email->send();
    }*/

    public function buildUrlWS($host) {
        $domain = "";
        if (substr($host, 0, 7) != 'http://' && substr($host, 0, 8) != 'https://')
            $domain = "http://";
        $domain .= $host;
        return $domain;
    }

//end buildUrlWS function

    public function GetStatusByID($id) {
        $ctype = "";
        switch ($id) {
            case "1": $ctype = "Active";
                break;
            case "0": $ctype = "Block";
                break;

            default: $ctype = "Block";
        }
        return $ctype;
    }

//end GetStatusByID function

    function GetNameStateByCode($value, $ctcode) {
        $str = '';
        $idcountry = '';

        $re_1 = $this->CI->db->query("select id from tblcontries where code = '$ctcode'");
        foreach ($re_1->result_array() as $row_1) {
            $idcountry = $this->ConvertToTest($row_1['id']);
        }
        $re = $this->CI->db->query("select name from tblsates where idcountry = '$idcountry' and code = '$value'");
        foreach ($re->result_array() as $row) {
            $str .= $this->ConvertToTest($row['name']);
        }
        if ($str == '')
            $str = $value;

        return $str;
    }

//end GetNameStateByCode function

    function GetTittleNameByID($value = 0) {
        $str = '';
        $re = $this->CI->db->query("select * from title where id=$value");
        foreach ($re->result_array() as $row) {
            $str .= $this->ConvertToTest($row['name']);
        }
        return $str;
    }

//end GetTittleNameByID function

    function check_name_exists($name) {
        $exists = 0;
        $r = $this->CI->db->query("SELECT uid FROM users WHERE status <> -1 and name = '$name' ");
        foreach ($r->result_array() as $row)
            $exists++;
        return $exists;
    }

//end check_name_exists function

    function check_name_exists_2($name, $ukey) {
        $exists = 0;
        $r = $this->CI->db->query("SELECT uid FROM users WHERE status <> -1 and name = '$name' and ukey <> '$ukey' ");
        foreach ($r->result_array() as $row)
            $exists++;
        return $exists;
    }

//end check_name_exists_2 function	

   /* function __sendmail_profile__() {
        $error = '';
        $mail_title = (isset($_POST['mail_title']) && trim($_POST['mail_title']) != '') ? $this->SQLToFCK($_POST['mail_title']) : '';
        $mail_body = (isset($_POST['mail_body']) && trim($_POST['mail_body']) != '') ? ($_POST['mail_body']) : '';
        $ukey = (isset($_POST['ukey']) && $_POST['ukey'] != '') ? $_POST['ukey'] : '';
        $re = $this->CI->db->query("select mail from users where ukey = '$ukey'");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();

            $this->CI->load->library('email');
            $this->CI->email->from($this->CI->system->siteInfo['email'], $this->CI->system->siteInfo['sender_name']);
            $this->CI->email->to($row['mail']);
            $this->CI->email->subject($mail_title);
            $this->CI->email->message($mail_body);
            $this->CI->email->send();
        }
        return array('error' => $error);
    }*/

//end __sendmail_profile__ function

    function GetNameBusinessTypeByID($value = 0) {
        $str = '';
        $re = $this->CI->db->query("select * from business_type where id=$value");
        foreach ($re->result_array() as $row) {
            $str .= $this->ConvertToTest($row['name']);
        }
        return $str;
    }

//end GetNameBusinessTypeByID function

    function GetNameCountryByCode($value) {
        $str = '';
        $re = $this->CI->db->query("select name from tblcontries where code = '$value'");
        foreach ($re->result_array() as $row) {
            $str .= $this->ConvertToTest($row['name']);
        }
        return $str;
    }

//end GetNameCountryByCode function

    function loadBusinessType($value = 0) {
        $str = '';
        $re = $this->CI->db->query("select * from business_type where status <> -1 order by weight DESC");
        foreach ($re->result_array() as $row) {
            $select = '';
            if ($row['id'] == $value)
                $select = 'selected="selected"';
            $str .= '<option value="' . $row['id'] . '" ' . $select . '>' . $this->ConvertToTest($row['name']) . '</option>';
        }
        return $str;
    }

//end loadBusinessType function

    public function OutputSelectBox(&$arr, $select_name, $select_id, $select_attribs = '', $selected = '', $onchange = '') {
        if (!is_array($arr)) {
            return '';
        }
        reset($arr);
        $s = "<select name=\"$select_name\" id=\"$select_id\" $select_attribs>";
        $did_selected = 0;
        foreach ($arr as $k => $v) {
            $s .= "<option value=\"" . $k . '"' . (($k == $selected && !$did_selected) ? ' selected="selected"' : '') . ">" . $v . '</option>';
            if ($k == $selected) {
                $did_selected = 1;
            }
        }
        $s .= "</select>";
        return $s;
    }

//end OutputSelectBox function

    public function replaceSpecChar($str = '') {
        if ($str != '') {
            $str = trim($str);
            $str = str_replace('  ', ' ', $str);
            $str = str_replace("%", "\%", $str);
            $str = str_replace("_", "\_", $str);
        }
        return $str;
    }

//end function replaceSpecChar

    public function __loadProductsAvailable__($cat_id = 0) {
        $arr = array();
        $sql_cat_id = '';
        if (isset($cat_id) && is_numeric($cat_id) && $cat_id > 0)
            $sql_cat_id = " and items.cat_id = '$cat_id'";
        $re = $this->CI->db->query("select items.itm_name,items.itm_id,items.itm_key,items.itm_model,items.itm_description,items.cat_id,categories.cat_name from items join categories on items.cat_id = categories.cat_id where items.itm_status = 1 and categories.status = 1 $sql_cat_id");
        foreach ($re->result_array() as $row) {
            $arr_file = $this->__loadFileProduct__($row['itm_id'], 'thumb');

            $arr[] = array(
                'value' => $row['itm_key'],
                'label' => "<b>" . $row['itm_name'] . "</b><br><b>Model: </b>" . $row['itm_model'],
                'desc' => $row['itm_description'],
                'icon' => $arr_file['file'],
                'category' => $row['cat_name'],
                'cat_id' => $row['cat_id']
            );
        }
        return $arr;
    }

//__loadProductsAvailable__ function

    public function get_special_products($id = 0) {
        $this->CI->load->model("shop/m_special");
        $items = $this->CI->m_special->getSpecialProducts();
        $notemptyHeader = array();
        $notemptyFooter = array();
        if (is_array($items) && count($items) > 0)
            $notemptyHeader = array(array("productType" => "Special", "pageLink" => "specialproducts"));
        $notemptyFooter = array(array("key" => 1));

        $data = array(
            "notemptyHeader" => $notemptyHeader,
            "items" => $items,
            "notemptyFooter" => $notemptyFooter,
        );
        return $this->CI->system->parse_templace("shop/special-featured.htm", $data, true);
    }

//get_special_products

    public function getFeaturedProducts() {
        $this->CI->load->model("shop/m_featured");
        $items = $this->CI->m_featured->getFeaturedProducts();
        $notemptyHeader = array();
        $notemptyFooter = array();
        if (is_array($items) && count($items) > 0)
            $notemptyHeader = array(array("productType" => "Featured", "pageLink" => "featureproducts"));
        $notemptyFooter = array(array("key" => 1));

        $data = array(
            "notemptyHeader" => $notemptyHeader,
            "items" => $items,
            "notemptyFooter" => $notemptyFooter,
        );
        return $this->CI->system->parse_templace("shop/special-featured.htm", $data, true);
    }

//getFeaturedProducts

    public function loadProductType($value = '') {
        $str = '';
        $product_type = array(
            0 => 'Product',
            1 => 'Service',
            2 => 'Voucher'
        );
        foreach ($product_type as $key => $name) {
            $select = '';
            if ($key == $value)
                $select = 'selected="selected"';
            $str .= '<option value="' . $key . '" ' . $select . '>' . $this->ConvertToTest($name) . '</option>';
        }
        return $str;
    }

//end loadProductType function

    public function loadUnitDays($num_of_days = 1) {
        $arr_unit_days = array(
            1 => 'Day(s)',
            30 => 'Month(s)',
            365 => 'Year(s)'
        );
        $st = '';

        foreach ($arr_unit_days as $num => $name) {
            $select = '';
            if ($num == $num_of_days)
                $select = 'selected="selected"';
            $st .= '<option value="' . $num . '" ' . $select . '>' . $name . '</option>';
        }
        return $st;
    }

//end loadUnitDays function

    public function CreateImageFromVideo($video, $file_img) {
        if (is_file($video)) {
            $mov = new ffmpeg_movie($video);
            $total = $mov->getFrameCount();
            $height = $mov->getFrameHeight();
            $width = $mov->getFrameWidth();

            $second = 10;
            $reCall = true;
            while ($reCall == true && $second < $total) {
                $frame = $mov->getFrame($second);
                $image = $frame->toGDImage();

                $dem_black = 0;
                if ($width / 2 > 0 && $height / 2 > 0) {
                    for ($i = 0; $i < round($width / 2); $i++) {
                        for ($j = 0; $j < round($height / 2); $j++) {
                            $rgb = imagecolorat($image, $i, $j);
                            $r = ($rgb >> 16) & 0xFF;
                            $g = ($rgb >> 8) & 0xFF;
                            $b = $rgb & 0xFF;
                            if ($r <= 40 && $g <= 40 && $b <= 40) {
                                $dem_black++;
                            }
                        }
                    }
                }
                if ($dem_black <= 0.65 * (round($width / 2) * round($height / 2))) {
                    $reCall = false;
                    @imagejpeg($image, $file_img, 100);
                }
                $second++;
            }
        }
    }

//CreateImageFromVideo function

    public function __grabURL__($data) {
        $data = trim($data);
        $data = str_replace("\n", "", $data);
        $data = str_replace("\t", "", $data);
        $data = str_replace("<", "%3C", $data);
        $data = str_replace(">", "%3E", $data);
        $data = str_replace("  ", " ", $data);
        $data = str_replace(" ", "%20", $data);
        $data = str_replace('"', "%22", $data);
        $data = str_replace("'", "%27", $data);
        return $data;
    }

//end __grabURL__ function

    public function partitionString($strStart, $strEnd, $strContent) {
        $intStart = strpos($strContent, "$strStart", 0);
        $intEnd = strpos($strContent, "$strEnd", 0);
        if ($intStart === false || $intStart === false) {
            $dat[0] = $strContent;
            $dat[1] = "";
            $dat[2] = "";
            return $dat;
        }
        $strHeader = substr($strContent, 0, $intStart);
        $strRow = substr($strContent, $intStart + strlen($strStart), $intEnd - $intStart - strlen($strStart));
        $strFooter = substr($strContent, $intEnd + strlen($strEnd), strlen($strContent));

        $dat = array();
        $dat[0] = $strHeader;
        $dat[1] = $strRow;
        $dat[2] = $strFooter;
        return $dat;
    }

//end partitionString function

  /*  public function __sendMailShipment__($sid) {
        $__package_type__ = array(
            '02' => 'Customer Supplied Package',
            '01' => 'UPS Letter',
            '03' => 'Tube',
            '04' => 'PAK',
            '21' => 'UPS Express Box',
            '24' => 'UPS 25KG Box',
            '25' => 'UPS 10KG Box',
            '30' => 'Pallet',
            '2a' => 'Small Express Box',
            '2b' => 'Medium Express Box',
            '2c' => 'Large Express Box'
        );
        $okey = '';
        $skey = '';
        $billing_Email = '';
        $shipping_method = 0;
        $Tracking_number = '';
        $Ship_date = '';
        $Expected_delivery = '';
        $re = $this->CI->db->query("select okey,skey,tracking_number,ship_date,expected_delivery,shipping_method from shipments where id = $sid");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $okey = $row['okey'];
            $skey = $row['skey'];
            $shipping_method = $row['shipping_method'];
            if ($shipping_method == 0) {
                $Tracking_number = '<a href="' . $this->CI->system->cleanUrl() . 'store/orders/tracking" target="_blank">' . $row['tracking_number'] . '</a>';
            } else if ($shipping_method == 1) {
                $Tracking_number = '<a href="http://www.ups.com/WebTracking/track?loc=en_US&WT.svl=PriNav" target="_blank">' . $row['tracking_number'] . '</a>';
            } else if ($shipping_method == 2) {
                $Tracking_number = '<a href="https://tools.usps.com/go/TrackConfirmAction_input" target="_blank">' . $row['tracking_number'] . '</a>';
            } else if ($shipping_method == 3) {
                $Tracking_number = '<a href="http://www.fedex.com/Tracking" target="_blank">' . $row['tracking_number'] . '</a>';
            }
            $Ship_date = $row['ship_date'];
            $Expected_delivery = $row['expected_delivery'];
        }//0
        $tblcontries = array();
        $re = $this->CI->db->query("select * from tblcontries");
        foreach ($re->result_array() as $row) {
            $tblcontries[$row['code']] = $row['name'];
        }

        $order_number = '';
        $date = '';
        $billingName = '';
        $billingAddress = '';
        $billingCity = '';
        $billingPhone = '';
        $billingEmail = '';
        $shippingName = '';
        $shippingAddress = '';
        $shippingCity = '';
        $shippingPhone = '';
        $payment_method = '';
        $card_number = '';

        $r = $this->CI->db->query("SELECT * FROM orders WHERE okey = '$okey'");
        if ($r->num_rows() > 0) {
            $row = $r->row_array();
            $oid = $row['orderid'];

            $billingName = $row["billing_name"];
            $billingAddress = $row["billing_address"];
            $billing_City = $row["billing_city"];
            $billing_State = $row["billing_state"];
            $billing_Zip = $row["billing_zip"];
            $billingPhone = $row["billing_phone"];
            $billingEmail = $row["billing_email"];

            $shippingName = $row["shipping_name"];
            $shippingAddress = $row["shipping_address"];
            $shipping_City = $row["shipping_city"];
            $shipping_State = $row["shipping_state"];
            $shipping_Zip = $row["shipping_zip"];
            $shippingPhone = $row["shipping_phone"];
            $order_status = $row['status'];

            $order_number = $okey;
            $date = date("m/d/Y", strtotime($row["order_date"]));
            $billingCity = $billing_City . ', ' . $billing_State . ' ' . $billing_Zip . ', ' . (isset($tblcontries[$row["billing_country"]]) ? $tblcontries[$row["billing_country"]] : '');
            $shippingCity = $shipping_City . ', ' . $shipping_State . ' ' . $shipping_Zip . ', ' . (isset($tblcontries[$row["shipping_country"]]) ? $tblcontries[$row["shipping_country"]] : '');
            //$payment_method = $row['card_type'];	
            $card_number = 'xxxxxxxxxxxx' . substr($row["card_number"], strlen($row["card_number"]) - 4);
        }
        $details_arr = array();
        $arr_manufactured = array();
        $re = $this->CI->db->query("select * from packages where shipment_ID = '$skey'");
        foreach ($re->result_array() as $row) {
            $id = $row['id'];
            $Products = '';
            $re_1 = $this->CI->db->query("select packages_items.qty,items.itm_model,items.uid from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = $id ");
            foreach ($re_1->result_array() as $row_1) {
                $Products .= $row_1['qty'] . " x " . $row_1['itm_model'] . '<br>';
                if (!in_array($row_1['uid'], $arr_manufactured))
                    $arr_manufactured[] = $row_1['uid'];
            }
            if ($Products != '') {
                $Products = substr($Products, 0, strlen($Products) - 4);
            } elseif ($Products == '')
                continue;

            $package_type = $row['package_type'];
            if ($shipping_method == 1)
                $package_type = isset($__package_type__[$row['package_type']]) ? $__package_type__[$row['package_type']] : $row['package_type'];

            $details_arr[] = array(
                'PACKAGE_ID' => $row['pkey'],
                'PRODUCTS' => $Products,
                'PACKAGE_TYPE' => $package_type
            );
        }

        $data = array(
            'details_arr' => $details_arr,
            'Tracking_number' => $Tracking_number,
            'Ship_date' => $Ship_date,
            'Expected_delivery' => $Expected_delivery, $order_number = '',
            'date' => $date,
            'order_number' => $okey,
            'billingName' => $billingName,
            'billingAddress' => $billingAddress,
            'billingCity' => $billingCity,
            'billingPhone' => $billingPhone,
            'billingEmail' => $billingEmail,
            'shippingName' => $shippingName,
            'shippingAddress' => $shippingAddress,
            'shippingCity' => $shippingCity,
            'shippingPhone' => $shippingPhone,
            'payment_method' => $payment_method,
            'card_number' => $card_number,
        );
        $strContent = $this->CI->system->parse_templace("mail_shipments.htm", $data, true);

        $this->mail_simple($billing_Email, "Shipment Order # $okey", __email_to_get_order__, SIGNATURE, $strContent);
        $this->mail_simple(__email_to_get_order__, "Shipment Order # $okey", EMAIL_SUPPORT, SENDER_NAME, $strContent);
        if (count($arr_manufactured) > 0) {
            $sql_uid = implode(",", $arr_manufactured);
            $re = $this->CI->db->query("select mail,uid from users where uid in ($sql_uid)");
            foreach ($re->result_array() as $row) {
                $this->mail_simple($row['mail'], "Shipment Order # $okey", EMAIL_SUPPORT, SENDER_NAME, $strContent);
                $re_2 = $this->CI->db->query("select users.mail,users.uid from users join manufacturers on manufacturers.uid = users.uid where users.status = 1 and manufacturers.author = " . $row['uid']);
                foreach ($re_2->result_array() as $row_2) {
                    if ($this->CI->author->isAccessPerm("orders", "receive_order_mail")) {
                        $this->mail_simple($row_2['mail'], "Shipment Order # $okey", EMAIL_SUPPORT, SENDER_NAME, $strContent);
                    }
                }
            }
        }
    }*/

//end __sendMailShipment__ function

    public function mail_simple($mailto, $subject, $sender_name, $sender_email, $mailcontent) {
        $this->CI->load->library('email');
        $this->CI->email->from($sender_name, $sender_email);
        $this->CI->email->to($mailto);
        $this->CI->email->subject($subject);
        $this->CI->email->message($mailcontent);
        $this->CI->email->send();
    }

//end mail_simple function


    /* ----------------- Shopping Cart --------------------- */

    function loadshoppingcart() {
        $arrShoppings = array();
        $arrPromotions = array();
        $arrLocations = array();
        if (isset($_SESSION['_CART'])) {
            foreach ($_SESSION['_CART']->items as $k => $Qty) {
                $arrKeys = explode(__keycode__, $k);
                if (count($arrKeys) > 0) {//0
                    $itm_key = $arrKeys[0];
                    $re = $this->CI->db->query("SELECT itm_id,itm_key,itm_name,origin,itm_model,voucher_value,current_cost,product_type, inventories,minimum_in_stock FROM items WHERE itm_key = '$itm_key' and itm_status = 1 ");
                    if ($re->num_rows() > 0) {//1
                        $row = $re->row_array();
                        $itm_id = $row['itm_id'];
                        $arr_file = $this->__loadFileProduct__($itm_id);
                        $row['file'] = $arr_file['file'];
                        $row['qty'] = $Qty;
                        $current_cost = $row['current_cost'];
                        $total_commission = 0;//
                        if (!is_numeric($current_cost))
                            $current_cost = 0;
                        $re_price = $this->CI->db->query("select product_markup.markup_percentage,product_markup.commission_member from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                        if ($re_price->num_rows() > 0) {
                            $row_price = $re_price->row_array();
                
                            $markup_percentage = $row_price['markup_percentage'];
                 
                            if (!is_numeric($markup_percentage))
                                $markup_percentage = 0;
                            $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
                            $total_commission = $row_price['commission_member'];//
                        }
           
                        $current_cost = round($current_cost, 2);
                        $row['itm_price'] = $current_cost;
                        $row['total_commission'] =  ($row['itm_price'] * $total_commission)/100;//
                        $attributes = array();
                        for ($i = 1; $i < count($arrKeys); $i++) {
                            $arr_attribute = array(
                                'label' => '',
                                'name' => '',
                                'price' => 0
                            );
                            $arr_textfi = explode(__keyat__, $arrKeys[$i]);
                            if (count($arr_textfi) == 2) {
                                if ($arr_textfi[1] == '')
                                    continue;

                                $re_label = $this->CI->db->query("select label from items_attributes where akey = '" . $arr_textfi[0] . "' and pkey = '$itm_key'");
                                if ($re_label->num_rows() > 0) {
                                    $row_label = $re_label->row_array();
                                    $arr_attribute['label'] = $row_label['label'];
                                } else {
                                    $arr_attribute['label'] = $this->CI->database->db_result("select label from attributes where status <> -1 and akey = '" . $arr_textfi[0] . "'");
                                }
                                $arr_attribute['name'] = $arr_textfi[1];
                            } elseif (count($arr_textfi) == 1) {
                                $arr_attribute['label'] = $this->CI->database->db_result("select items_attributes.label from items_attributes join attrioptions on items_attributes.akey = attrioptions.akey where items_attributes.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                                $re_pri = $this->CI->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                                if ($re_pri->num_rows() > 0) {
                                    $row_pri = $re_pri->row_array();
                                    $arr_attribute['name'] = $row_pri['name'];
                                    $arr_attribute['price'] = $row_pri['price'];
                                }
                            }
                            $attributes[] = $arr_attribute;
                        }
                        $row['attributes'] = $attributes;
                        $row['attributes_key'] = $k;
                        $arrShoppings[] = $row;

                        $this->loadPromotionsObject($arrPromotions, $itm_key, $Qty);
                    }//1
                    if (isset($_SESSION['_CART']->locations) && count($_SESSION['_CART']->locations) > 0) {
                        $arrLocations = $_SESSION['_CART']->locations;
                        for ($g = 0; $g < count($arrLocations); $g++) {
                            $arr_loc = $arrLocations[$g];
                            for ($h = 0; $h < count($arr_loc); $h++) {
                                $re_location = $this->CI->db->query("select * from items_locations where ikey = '" . $itm_key . "' and id = '" . $arr_loc[$h]['id'] . "'  and status = 1");
                                foreach ($re_location->result_array() as $row_location) {
                                    if (trim($row_location['location']) != '') {
                                        $arrLocations[$g][$h]['pkey'] = $itm_key;
                                        $arrLocations[$g][$h]['location'] = $row_location['location'];
                                    }
                                }
                            }
                        }
                    }
                }//0
            }
        }
        return array('shopping' => $arrShoppings, 'promotions' => $arrPromotions, 'locations' => $arrLocations);
    }

//Load for autodelivery

    function loadshoppingcartAuto() {
        $arr_Shoppings = array();
        $arr_Promotions = array();
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            if (isset($_SESSION['_CART'])) {
                foreach ($_SESSION['_CART'] as $key => $cart) {
                    $arrShop = array();
                    $arrPro = array();
                    if (isset($cart->items))
                        foreach ($cart->items as $k => $Qty) {
                            $arrKeys = explode(__keycode__, $k);
                            if (count($arrKeys) > 0) {//0
                                $itm_key = $arrKeys[0];
                                $re = $this->CI->db->query("SELECT itm_id,itm_key,itm_name,itm_model,current_cost,inventories,minimum_in_stock  FROM items WHERE itm_key = '$itm_key' and itm_status = 1 ");
                                if ($re->num_rows() > 0) {//1
                                    $row = $re->row_array();
                                    $itm_id = $row['itm_id'];
                                    $arr_file = $this->__loadFileProduct__($itm_id);
                                    $row['file'] = $arr_file['file'];

                                    $row['qty'] = $Qty;
                                    
                                    $current_cost = $row['current_cost'];
                                    $total_commission = 0;
                                    if (!is_numeric($current_cost))
                                        $current_cost = 0;
                                    $re_price = $this->CI->db->query("select product_markup.markup_percentage,product_markup.commission_member from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '$itm_key'");
                                    if ($re_price->num_rows() > 0) {
                                        $row_price = $re_price->row_array();
                                        $markup_percentage = $row_price['markup_percentage'];
                                        if (!is_numeric($markup_percentage))
                                            $markup_percentage = 0;
                                        $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
                                        $total_commission = $row_price['commission_member'];//
                                   
                                    }
                                    $current_cost = round($current_cost, 2);
                                    $row['itm_price'] = $current_cost;
                                    $row['total_commission'] =  ($row['itm_price'] * $total_commission )/100;

                                    $attributes = array();
                                    for ($i = 1; $i < count($arrKeys); $i++) {
                                        $arr_attribute = array(
                                            'label' => '',
                                            'name' => '',
                                            'price' => 0
                                        );
                                        $arr_textfi = explode(__keyat__, $arrKeys[$i]);
                                        if (count($arr_textfi) == 2) {
                                            if ($arr_textfi[1] == '')
                                                continue;

                                            $re_label = $this->CI->db->query("select label from items_attributes where akey = '" . $arr_textfi[0] . "' and pkey = '$itm_key'");
                                            if ($re_label->num_rows() > 0) {
                                                $row_label = $re_label->row_array();
                                                $arr_attribute['label'] = $row_label['label'];
                                            } else {
                                                $arr_attribute['label'] = $this->CI->database->db_result("select label from attributes where status <> -1 and akey = '" . $arr_textfi[0] . "'");
                                            }
                                            $arr_attribute['name'] = $arr_textfi[1];
                                        } elseif (count($arr_textfi) == 1) {
                                            $arr_attribute['label'] = $this->CI->database->db_result("select items_attributes.label from items_attributes join attrioptions on items_attributes.akey = attrioptions.akey where items_attributes.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                                            $re_pri = $this->CI->db->query("select attrioptions.name,items_options.price from attrioptions join items_options on attrioptions.okey = items_options.okey where items_options.pkey = '$itm_key' and attrioptions.okey = '" . $arrKeys[$i] . "'");
                                            if ($re_pri->num_rows() > 0) {
                                                $row_pri = $re_pri->row_array();
                                                $arr_attribute['name'] = $row_pri['name'];
                                                $arr_attribute['price'] = $row_pri['price'];
                                            }
                                        }
                                        $attributes[] = $arr_attribute;
                                    }
                                    $row['attributes'] = $attributes;
                                    $row['attributes_key'] = $k;

                                    $row['ckey'] = (string) $key;
                                    $arrShop[] = $row;

                                    $this->loadPromotionsObject($arrPro, $itm_key, $Qty, $key);
                                }//1	
                            }//0
                        }
                    $arrShoppings['ckey'] = (string) $key;
                    if ($cart->cdate != NULL && $cart->cdate != '')
                        $arrShoppings['cdate'] = gmdate("l, F j, Y", strtotime($cart->cdate));
                    else
                        $arrShoppings['cdate'] = '';
                    $arrPromotions['ckey'] = (string) $key;

                    $arrShoppings['items_content'] = $arrShop;
                    $arrPromotions['items_content'] = $arrPro;

                    $arr_Shoppings[] = $arrShoppings;
                    $arr_Promotions[] = $arrPromotions;
                }
            }
        }
        return array('shopping' => $arr_Shoppings, 'promotions' => $arr_Promotions);
    }

    function loadPromotionsObject(&$arrPromotions, $itm_key, $Qty, $ckey = '') {
        $re = $this->CI->db->query("select promotions.* from items_promotion join promotions on promotions.promo_code = items_promotion.promo_key where items_promotion.item_key = '$itm_key' and promotions.status = 1");
        foreach ($re->result_array() as $row) {
            $check_promo = 0;
            if ($row['start_date'] == null || $row['start_date'] == '')
                $row['start_date'] = 0;
            if ($row['end_date'] == null || $row['end_date'] == '')
                $row['end_date'] = 0;
            if ($row['start_date'] <= $this->getTimeGMT()) {
                if ($row['end_date'] == 0 || ($row['end_date'] != 0 && $row['end_date'] >= $this->getTimeGMT())) {
                    if ($row['minqty'] <= $Qty)
                        $check_promo = 1;
                }
            }
            if ($check_promo == 1) {
                $id = $row['id'];
                $re_2 = $this->CI->db->query("select * from promotions_group where promo_id = $id and status = 1");
                foreach ($re_2->result_array() as $row_2) {//0
                    $promo_type = $row_2['promo_type'];
                    $promo_group = $row_2['id'];
                    $promo_countries = array();
                    if ($promo_type == 4) {
                        $re_country = $this->CI->db->query("select * from promotions_products_countries where promo_id = " . $promo_group);
                        foreach ($re_country->result_array() as $row_country) {
                            $country = array('code' => $row_country['country_code'], 'states' => array());
                            $re_state = $this->CI->db->query("select state_code from promotions_products_states where country_id = " . $row_country['id']);
                            foreach ($re_state->result_array() as $row_state) {
                                $country['states'][] = $row_state['state_code'];
                            }
                            $promo_countries[] = $country;
                        }
                    }

                    $sql_ = "select promotions_products.discount,promotions_products.discount_type,promotions_products.freeqty,items.itm_name,items.itm_id,items.itm_key,items.itm_model,items.current_cost,items.uid from promotions_products,items where promotions_products.promo_group = $promo_group and promotions_products.status = 1 and items.itm_status = 1 and items.itm_key = '$itm_key' and promotions_products.item_key = '" . _same_product_ . "'";
                    $re_3 = $this->CI->db->query($sql_);
                    foreach ($re_3->result_array() as $row_3) {
                        $arr_file = $this->__loadFileProduct__($row_3['itm_id'], 'thumb');
                        $row_3['file'] = $arr_file['file'];
                        $row_3['product_key'] = $itm_key;
                        $row_3['promo_type'] = $promo_type;
                        $row_3['minqty'] = $row['minqty'];
                        $row_3['promo_code'] = $row['promo_code'];
                        $row_3['countries'] = $promo_countries;

                        $current_cost = $row_3['current_cost'];
                        if (!is_numeric($current_cost))
                            $current_cost = 0;
                        $re_price = $this->CI->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '" . $row_3['itm_key'] . "'");
                        if ($re_price->num_rows() > 0) {
                            $row_price = $re_price->row_array();
                            $markup_percentage = $row_price['markup_percentage'];
                            if (!is_numeric($markup_percentage))
                                $markup_percentage = 0;
                            $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
                        }
                        $current_cost = round($current_cost, 2);
                        $row_3['itm_price'] = $current_cost;

                        //					$row_3 = (string)$ckey; // For auto-delivery
                        $arrPromotions[] = $row_3;
                    }

                    $sql_ = "select promotions_products.discount,promotions_products.discount_type,promotions_products.freeqty,items.itm_name,items.itm_id,items.itm_key,items.itm_model,items.current_cost,items.uid from promotions_products join items on items.itm_key = promotions_products.item_key where promotions_products.promo_group = $promo_group and promotions_products.status = 1 and items.itm_status = 1";
                    $re_3 = $this->CI->db->query($sql_);
                    foreach ($re_3->result_array() as $row_3) {
                        $arr_file = $this->__loadFileProduct__($row_3['itm_id'], 'thumb');
                        $row_3['file'] = $arr_file['file'];
                        $row_3['product_key'] = $itm_key;
                        $row_3['promo_type'] = $promo_type;
                        $row_3['minqty'] = $row['minqty'];
                        $row_3['promo_code'] = $row['promo_code'];
                        $row_3['countries'] = $promo_countries;

                        $current_cost = $row_3['current_cost'];
                        if (!is_numeric($current_cost))
                            $current_cost = 0;
                        $re_price = $this->CI->db->query("select product_markup.markup_percentage from product_markup join items_markup on items_markup.mkey = product_markup.mkey where product_markup.status <> -1 and items_markup.pkey = '" . $row_3['itm_key'] . "'");
                        if ($re_price->row_array() > 0) {
                            $row_price = $re_price->row_array();
                            $markup_percentage = $row_price['markup_percentage'];
                            if (!is_numeric($markup_percentage))
                                $markup_percentage = 0;
                            $current_cost = $current_cost + $current_cost * $markup_percentage / 100;
                        }
                        $current_cost = round($current_cost, 2);
                        $row_3['itm_price'] = $current_cost;

                        //					$row_3 = (string)$ckey; // For auto-delivery
                        $arrPromotions[] = $row_3;
                    }
                }//0
            }
        }
    }

    function GetLatLongFromAddress($mapaddress) {
        $mapaddress = urlencode($mapaddress);
        if (isset($mapaddress) && $mapaddress != '') {
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $mapaddress . '&sensor=false');
            $output = json_decode($geocode);
            if ($output->status == 'ZERO_RESULTS') {
                return $_SERVER['HTTP_REFERER'];
            }
            $latitude = $output->results[0]->geometry->location->lat;
            if (is_numeric($latitude))
                $latitude = (float) $latitude;
            $longitude = $output->results[0]->geometry->location->lng;
            if (is_numeric($longitude))
                $longitude = (float) $longitude;
            // Output the coordinates
            $longlat = "";
            $longlat .= $longitude . ";";
            $longlat .= $latitude;
            return $longlat;
        }
    }

    function loadAccessUser($uid = 0) {
        $access_users = array();
        if ($uid == 0) {
            if ($this->author->objlogin)
                $uid = $this->author->objlogin->uid;
        }
        if ($uid > 0) {
            $r = $this->CI->db->query("select * from access_users where uid = '$uid'");
            foreach ($r->result_array() as $row_access) {
                if ($row_access['perm'] != null && $row_access['perm'] != '') {
                    $access_users = array_merge($access_users, unserialize($row_access['perm']));
                }
            }
        }
        return $access_users;
    }

    function __getLevelSale__($uid) {
        $sale_rep_setting = $this->get_sysvals('sale_rep_setting', array());
        $maxlevel = $sale_rep_setting['Number_of_level'];
        $arr_sales = array();
        $this->__loadLevelSale__($uid, $arr_sales, $maxlevel);
        return $arr_sales;
    }

    function __loadLevelSale__($uid, &$arr_sales, $maxlevel) {
        $sql = "select tbaffiliates.ucreate,users.status from tbaffiliates join users on tbaffiliates.uid = users.uid WHERE users.uid = $uid";
        $re = $this->CI->db->query($sql);
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            if (count($arr_sales) < $maxlevel) {
                $role = $this->CI->author->loadRole($row['ucreate']);
                if ($role['rid'] != 3 && $role['rid'] != 4) {
                    $arr_sales[] = array('uid' => $row['ucreate'], 'status' => $row['status']);
                    if (count($arr_sales) < $maxlevel)
                        $this->__loadLevelSale__($row['ucreate'], $arr_sales, $maxlevel);
                }
            }
        }else {
            $sql = "select representatives.author,users.status from representatives join users on representatives.uid = users.uid WHERE users.uid = $uid";
            $re = $this->CI->db->query($sql);
            if ($re->num_rows() > 0) {
                $row = $re->row_array();
                if (count($arr_sales) < $maxlevel) {
                    $role = $this->CI->author->loadRole($row['author']);
                    if ($role['rid'] != 3 && $role['rid'] != 4) {
                        $arr_sales[] = array('uid' => $row['author'], 'status' => $row['status']);
                        if (count($arr_sales) < $maxlevel)
                            $this->__loadLevelSale__($row['author'], $arr_sales, $maxlevel);
                    }
                }
            }
        }
    }

    function get_sysvals($title, $value) {
        if (!isset($value))
            $value = '';
        $re = $this->CI->db->query("select sysval_value from sysvals where sysval_title = '$title'");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            if ($row['sysval_value'] != null && $row['sysval_value'] != '')
                $value = unserialize($row['sysval_value']);
        }else {
            $this->set_sysvals($title, $value);
        }
        return $value;
    }

    function set_sysvals($title, $value) {
        $value = serialize($value);
        $sysval_id = 0;
        $re = $this->CI->db->query("select sysval_id,sysval_value from sysvals where sysval_title = '$title'");
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $sysval_id = $row['sysval_id'];
            $this->CI->db->update("sysvals", array('sysval_value' => $value), "sysval_title = '$title'");
        } else {
            $sysval_id = $this->CI->db->insert("sysvals", array('sysval_value' => $value, 'sysval_title' => $title));
        }
        return $sysval_id;
    }

    function loadRoles($uid = 0) {
        $roles = array();
        if ($uid == 0) {
            if (isset($this->author->objlogin))
                $uid = $this->author->objlogin->uid;
        }
        if ($uid > 0) {
            $res = $this->CI->db->query("select roles.rid as rid,roles.name,roles.rlink from roles join users_roles on roles.rid = users_roles.rid where users_roles.uid = '$uid'");
            $row = $res->row_array();
            $roles = $row;
        } else {
            $re = $this->CI->db->query("select * from roles where rid = 1");
            foreach ($re->result_array() as $row) {
                $roles = $row;
            }
        }
        return $roles;
    }

    function addToCartButton($key, $product_type,$inventories) {

        $wishlist_button = $this->CI->author->objlogin->uid > 0 ? '<button class="btn Wishlist btn-small" data-toggle="tooltip" data-title="+To Wishlist" onClick="addToWishlist(\'' . $key . '\')" ><i class="icon-heart"></i></button>' : '';
        $cart_button = '';
        $arr_location = array();
        //auto delivery
        if (isset($_SESSION['auto_deli']) && $_SESSION['auto_deli'] == 'on') {
            if (isset($_SESSION['_CART']) && is_array($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                $cart_button .= '<button class="btn btn-small btn-primary" style="margin-right:3px" onclick=" schedule(\'' . $key . '\',1)" data-title="+To Cart" data-toggle="tooltip" ><i class="icon-eye-open"></i></button>';
            }
        } else {
           
            if ($product_type == 1) {
                $re_location = $this->CI->db->query("select * from items_locations where ikey = '" . $key . "' and status = 1");
                foreach ($re_location->result_array() as $row_location) {
                    if (trim($row_location['location']) != '')
                        $arr_location[] = $row_location;
                }
                if (count($arr_location) < 1) {

                    $cart_button .= '<span id="add2cart" style="padding-right:3px">';

                    $item_rid = $this->CI->database->db_result("select rid from users_roles join items on users_roles.uid = items.uid where items.itm_key = '" . $key . "'");
                    if ($item_rid != 8){
                        if($inventories == null || $inventories <=0)
                        $cart_button .= '<button class="btn btn-primary btn-small" onclick=" return a2c(\'a\', \'' . $key . '\',1);" disabled = "disabled"><i class="icon-shopping-cart"></i></button>';
                        else
                          $cart_button .= '<button class="btn btn-primary btn-small" onclick=" return a2c(\'a\', \'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>'; 
                        
                    }
                    else{
                    if (isset($_SESSION['_CART']) && count($_SESSION['_CART']) > 0) {
                        
                        $array = get_object_vars($_SESSION['_CART']);
                        if(is_array($array['items']) && !empty($array['items'])){
                            $cart_button .= '<input type="hidden" value ="1" id="p_qlty" >';
                            if($inventories == null || $inventories <=0)
                            $cart_button .= '<button class="btn btn-primary btn-small" onclick="return a2c(\'a\', \'' . $key . '\',1);" disabled = "disabled"><i class="icon-shopping-cart"></i></button>';
                            else
                               $cart_button .= '<button class="btn btn-primary btn-small" onclick="return a2c(\'a\', \'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip" ><i class="icon-shopping-cart"></i></button>';
                        }else{
                             $cart_button .= '<input type="hidden" value ="1" id="p_qlty" >';
                              if($inventories == null || $inventories <=0)
                             $cart_button .= '<button class="btn btn-primary btn-small" onclick="return donate(\'' . $key . '\',1);" disabled = "disabled" ><i class="icon-shopping-cart"></i></button>';
                              else
                                   $cart_button .= '<button class="btn btn-primary btn-small" onclick="return donate(\'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>';
                              }

                       // $cart_button .= '<button class="btn btn-primary btn-small" onclick="return a2c(\'a\', \'' . $key . '\',1);"><i class="icon-shopping-cart"></i></button>';
                    } //else {
                       // $cart_button .= '<button class="btn btn-primary btn-small" onclick="return donate(\'' . $key . '\',1);"><i class="icon-shopping-cart"></i></button>';
                    //}
                    }
                    
                    
                    $cart_button .= '</span>';
                } else {
                    $cart_button .= '<button class="btn btn-primary findlocation btn-small" style="margin-right:3px" onclick="return find_location(\'' . $key . '\',1)" data-title="Find location" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>';
                }
            } else {
                $cart_button .= '<input type="hidden" value ="1" id="p_qlty" >'; //insert more default value
                $cart_button .= '<span id="add2cart" style="padding-right:3px">';
                if($inventories == null || $inventories <= 0)
                $cart_button .= '<button id= "related" class="btn btn-primary btn-small" onclick="return a2c(\'a\', \'' . $key . '\',1);" disabled = "disabled"><i class="icon-shopping-cart"></i></button>';
                else
                       $cart_button .= '<button id= "related" class="btn btn-primary btn-small" onclick="return a2c(\'a\', \'' . $key . '\',1);" data-title="+To Cart" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button>';
                $cart_button .= '</span>';
            }
        }

        return $cart_button . '|' . $wishlist_button;
    }

    //Sort data
    public function sorts_array($array, $sortby, $direction = 'asc') {
        $sortedArr = array();
        $tmp_Array = array();
        foreach ($array as $k) {
            $tmp_Array[] = $k[$sortby];
        }
        if ($direction == 'asc') {
            asort($tmp_Array);
        } else {
            arsort($tmp_Array);
        }
        foreach ($tmp_Array as $k => $tmp) {
            $sortedArr[] = $array[$k];
        }
        return $sortedArr;
    }

    function getMailInfor($table,$columnName) {
        $column = '';
        $sql = $this->CI->db->query("select * from ". $table);
        foreach ($sql->result_array() as $row) {
            $column = $row[$columnName];
        }
        return $column;
    }
    

}