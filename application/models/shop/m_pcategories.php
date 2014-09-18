<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_pcategories extends CI_Model {

    private $recordPerPage = NUMROWPERPAGE;
    private $catKey = '';
    private $page = 1;
    private $orderBy = "items.itm_date";
    private $direction = "DESC";
    private $keyword = "";
    private $offset = 0;
    private $fieldsInSqlComparision = array(
        "items" => array("itm_key", "itm_name", "itm_model", "itm_description"),
        "categories" => array("cat_name", "description"),
    );
    private $fieldsInSqlSelection = array(
        "items" => array("itm_id", "itm_key", "itm_name", "itm_model", "origin", "inventories", "itm_featured", "product_type"),
    );

    public function getCatnameByKey($key) {
        if ($key == "")
            return "";
        return $this->database->db_result("SELECT cat_name FROM categories WHERE cat_key = '$key' LIMIT 1");
    }

//function getCatnameByKey
    public function getImgBanner($key) {
        $arr_img = array();
        if ($key == "")
            return $arr_img;
        $sql = "select cat_id FROM categories WHERE cat_key = '$key' LIMIT 1";
        $re = $this->db->query($sql);
        if (count($re->result_array()) > 0) {
            $row = $re->row_array();
            $id = $row['cat_id'];
            $arr_img = $this->directoryToArray('resource/catbanner/' . $id);
        }
        return $arr_img;
    }

    public function getRecordPerPage() {
        return $this->recordPerPage;
    }

//function getRecordPerPage

    private function setRecordPerPage($recordPerPage = 0) {
        if (!is_numeric($recordPerPage) || $recordPerPage < 0)
            $recordPerPage = NUMROWPERPAGE;
        $this->recordPerPage = $recordPerPage;
    }

//function setRecordPerPage

    private function setCategoryKey($catkey = '') {
        if (is_string($catkey))
            $this->catKey = trim($catkey);
    }

//function setCategoryKey

    private function setPage($page = 1) {
        if (is_numeric($page) && $page > 1)
            $this->page = $page;
    }

//function setPage

    private function setOrderBy($orderBy = '') {
        switch ($orderBy) {
            case 'name':
                $this->orderBy = 'items.itm_name';
                break;
            case 'model':
                $this->orderBy = 'items.itm_model';
                break;
            case 'price':
                $this->orderBy = 'itm_price';
                break;
        }
    }

//function setOrderBy

    private function setDirection($direction = '') {
        if ($direction == 'asc' || $direction == 'desc')
            $this->direction = strtoupper($direction);
    }

//function setDirection

    private function setKeyword($keyword = '') {
        if (is_string($keyword))
            $this->keyword = trim($keyword);
    }

//function setKeyword

    private function setOffset() {
        $this->offset = ($this->page - 1) * $this->recordPerPage;
    }

//function setOffset

    private function buildSqlKeywordComparisionString() {
        if (!$this->keyword || $this->keyword == "")
            return "";
        $keyword = $this->lib->escape($this->lib->replaceSpecChar($this->keyword));
        $arrKeys = explode(" ", $keyword);
        if (count($arrKeys) <= 0 || count($this->fieldsInSqlComparision) <= 0)
            return "";
        $result = "";
        foreach ($arrKeys as $key) {
            foreach ($this->fieldsInSqlComparision as $table => $fields) {
                if ($result != "")
                    $result .= " OR ";
                if (is_array($fields)) {
                    $first = true;
                    $result .=" (";
                    foreach ($fields as $field) {
                        if ($first == true)
                            $first = false;
                        else
                            $result .= " OR ";
                        $result .= "$table.$field LIKE '%$key%'";
                    }
                    $result .=")";
                }
                else
                    $result .= " $table.$fields LIKE '%$key%'";
            }
        }
        if ($result != "")
            $result = " AND (" . $result . ")";
        return $result;
    }

//function buildSqlKeywordComparisionString

    private function buildSelectedFieldsString() {
        $result = "(items.current_cost + items.current_cost*IFNULL(product_markup.markup_percentage,0)/100) AS itm_price,";
        foreach ($this->fieldsInSqlSelection as $table => $fields) {
            if (is_array($fields)) {
                foreach ($fields as $field)
                    $result .= " $table.$field,";
            } else if ($fields != '')
                $result .= " $table.$fields,";
        }
        return substr($result, 0, -1);
    }

//function buildSelectedFieldsString

    private function buildSqlWhereClause() {
        if ($this->catKey == "")
            return -1; //category key is empty
        $result = " items.cat_id = categories.cat_id AND items.itm_status = 1 AND categories.cat_key = '" . $this->lib->escape($this->catKey) . "'";
        $result .= $this->buildSqlKeywordComparisionString();
        return $result;
    }

//function buildSqlWhereClause

    private function setVaribales() {
        $this->setRecordPerPage($this->input->post("recordsPerPage"));
        $this->setPage($this->input->post("page"));
        $this->setOrderBy($this->input->post("sortby"));
        $this->setDirection($this->input->post("direction"));
        $this->setKeyword($this->input->post("keyword"));
        $this->setOffset();
    }

//function setObjVars

    public function loadProductsByCategoryKey() {
        $this->setCategoryKey($this->input->post("catkey"));
        if (!$this->catKey || $this->catKey == "")
            return -1; //category key is empty
        $this->setVaribales();
        $arrItems = array();
        $sql = "SELECT " . $this->buildSelectedFieldsString() . " FROM (product_markup JOIN items_markup ON items_markup.mkey = product_markup.mkey AND product_markup.status <> -1) RIGHT JOIN items ON items_markup.pkey = items.itm_key,categories WHERE  " . $this->buildSqlWhereClause() . " ORDER BY " . $this->orderBy . " " . $this->direction;
        $query = $this->db->query($sql);
        $records = $query->num_rows();
        if ($records > $this->recordPerPage) {
            $sql .= " LIMIT " . $this->offset . "," . $this->recordPerPage;
            $query = $this->db->query($sql);
        }
        foreach ($query->result_array() as $item) {
            $arr_file = $this->lib->__loadFileProduct__($item['itm_id'], 'thumb_home');
            $item['video'] = $arr_file['video'];
            $item['file'] = $arr_file['file'];

            $item['outOfStock'] = (is_numeric($item['inventories']) && $item['inventories'] > 0) ? '' : "(Out Of Stock)";
            $item['promotions'] = $this->lib->load_items_promotion($item['itm_key']);
            $item['itm_price'] = number_format($item['itm_price'], 2);
            $item['stars'] = $this->lib->load_items_stars($item['itm_id']);

            $cartButton = $this->lib->addToCartButton($item['itm_key'], $item['product_type'], $item['inventories']);
            $stringButton = explode('|', $cartButton);
            $item['addCart'] = $stringButton[0];
            $item['Wishlist'] = $stringButton[1];
            $arrItems[] = $item;
        }

        return array(
            'data' => $arrItems,
            'records' => $records,
        );
    }

//function loadProductsByCategoryKey

    public function directoryToArray($directory) {
        $array_items = array();
        if (!is_dir($directory))
            return $array_items;
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $file = $directory . "/" . $file;
                    $array_items[]['list_img'] = $this->system->URL_server__() . preg_replace("/\/\//si", "/", $file);
                }
            }
            closedir($handle);
        }
        return $array_items;
    }

    function load_categories_navbar($aClass = "", $subClass = "", $isLeftCat = false, $activeCatKey = "") {
        $this->load->library('lib');

        $str = '';
        $product_type = '';
        $check_auto_deli = false;

        if (isset($_SESSION['auto_deli'])) {
            $check_auto_deli = true;
            $product_type = ' and `product_type` = 0 '; 
        } else {
            $check_auto_deli = false;
            $product_type = '';
        }
       /* if ($check_auto_deli == false) {

            $sql1 = "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid = 0 order by `cat_id` asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_item FROM `items` WHERE `itm_status` = 1 group by cat_id) T2 on T1.cat_id = T2.cid";
            $re1 = $this->db->query($sql1);
            $f = $re1->result_array();

            $sql2 = "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid <> 0 order by fid asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_item FROM `items` WHERE `itm_status` = 1 group by cat_id) T2 on T1.cat_id = T2.cid";
            $re2 = $this->db->query($sql2);
            $c = $re2->result_array();
        }*/ 
            $sql1 = "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid = 0 order by `cat_id` asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_item FROM `items` WHERE `itm_status` = 1 $product_type group by cat_id) T2 on T1.cat_id = T2.cid";
            $re1 = $this->db->query($sql1);
            $f = $re1->result_array();

            $sql2 = "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid <> 0 order by fid asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_item FROM `items` WHERE `itm_status` = 1 $product_type group by cat_id) T2 on T1.cat_id = T2.cid";
            $re2 = $this->db->query($sql2);
            $c = $re2->result_array();

        $submenuIndent = (!$isLeftCat) ? '<span>-</span>' : '';
        for ($j = 0; $j < count($c); $j++) {
            $c[$j]['total_item'] = $c[$j]['total_item'] == NULL ? 0 : $c[$j]['total_item'];
        }
        for ($i = 0; $i < count($f); $i++) {
            $f[$i]['total_item'] = $f[$i]['total_item'] == NULL ? 0 : $f[$i]['total_item'];
            $total = $f[$i]['total_item'];
            $child_str = $this->get_left_child($aClass, $subClass, $isLeftCat, $activeCatKey, $f[$i]['cat_id'], $f[$i]['cat_key'], $f[$i]['cat_name'], $f[$i]['total_item'], $c, $total);
            if ($child_str != "" && $total != 0)
                $str .= $child_str;
        }
        return $str;
    }

    private function get_left_child($aClass = "", $subClass = "", $isLeftCat = false, $activeCatKey = "", $id, $cat_key, $catname, $total_item, $arr_child, &$total) {
        $str = "";
        $info_cat = "";
        $isactive = FALSE;
        $open = "";
        if ($cat_key == $activeCatKey)
            $isactive = TRUE;

        for ($i = 0; $i < count($arr_child); $i++) {
            if ($arr_child[$i]['fid'] == $id) {
                $total_c = $arr_child[$i]['total_item'];
                $child_str = $this->get_left_child($aClass, $subClass, $isLeftCat, $activeCatKey, $arr_child[$i]['cat_id'], $arr_child[$i]['cat_key'], $arr_child[$i]['cat_name'], $arr_child[$i]['total_item'], $arr_child, $total_c);
                $total += $total_c;
                if ($child_str != "" && $total_c != 0) {
                    $info_cat .= $child_str;
                }
            }
        }
        if (strrpos($info_cat, 'invarseColor active') !== FALSE) {
            $isactive = TRUE;
            $open = ' class = "open"';
        }

        if ($isactive)
            $str .= '<a class="' . $aClass . ' active" href="' . $this->system->URL_server__() . 'shop/pcategories?catkey=' . $cat_key . '"> ' . $catname . ($total_item > 0 ? " (" . $total_item . ")" : "");
        else
            $str .= '<a class="' . $aClass . '" href="' . $this->system->URL_server__() . 'shop/pcategories?catkey=' . $cat_key . '"> ' . $catname . ($total_item > 0 ? " (" . $total_item . ")" : "");
        if ($info_cat != "")
            $str = '<li' . $open . '>' . $str . '<i class="icon-caret-down"></i></a>' . "<ul class='" . $subClass . "'>" . $info_cat . "</ul>" . '</li>';
        else
            $str = '<li>' . $str . '</a></li>';

        return $str;
    }

}

//class M_pcategories