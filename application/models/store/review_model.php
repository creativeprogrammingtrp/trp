<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Review_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function loadName() {
        $reviews_array = array();
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $num_per_pager = 10;
        $page = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit = $num_per_pager * ($page - 1);

        $keyword = isset($_GET['keyword']) ? urldecode($_GET['keyword']) : '';
        $key_word_sql = $this->checkKeyWord($keyword);
        $where = $key_word_sql;
        $sql = "select distinct(items.itm_id),items.itm_name from reviews join items on reviews.itm_id = items.itm_id where 1=1 $where limit $limit ," . $num_per_pager;
        $rs = $this->db->query($sql);
        foreach ($rs->result_array() as $rows) {
            $reviews_array[] = $rows;
        }
        $total = $this->db->query("SELECT COUNT(distinct(items.itm_id)) AS maxlength from reviews join items on reviews.itm_id = items.itm_id");
        $maxlength = 0;
        if ($total->num_rows() > 0)
            $maxlength = $total->row()->maxlength;


        return array($reviews_array, $page, $maxlength);
    }

    public function checkKeyWord($keyword) {
        $keyword = str_replace("%", '\%', $keyword);
        $keyword = str_replace("_", '\_', $keyword);
        $key_word_sql = '';
        if (trim($keyword) != '') {
            $key_word = $this->lib->escape($keyword);
            $key_word = str_replace("  ", " ", $key_word);
            $arr_key = explode(" ", $key_word);
            if (count($arr_key) > 0) {
                foreach ($arr_key as $key) {
                    if ($key != '') {
                        $key_word_sql .= " and (";
                        //$key_word_sql .= " reviews.itm_id like '%$key%'";
                       // $key_word_sql .= " or reviews.rname like '%$key%'";
                        $key_word_sql .= " items.itm_name like '%$key%'";
                        $key_word_sql .= " ) ";
                    }
                }
            }
        }
        return $key_word_sql;
    }

    public function loadReview() {
        $reviews_array = array();
        $sql = "select reviews.* from reviews join items on reviews.itm_id = items.itm_id order by rid DESC";
        $rs = $this->db->query($sql);
        foreach ($rs->result_array() as $rows) {
            $rows['rcontent'] = $this->lib->SQLToFCK($rows['rcontent']);
            $rows['rname'] = $this->lib->SQLToFCK($rows['rname']);
            $reviews_array[] = $rows;
        }
        return $reviews_array;
    }
    
    public function loadItmById(){
        $reviews_array = array();
        $rname = '';
        $rcontent = '';
        $rating = '';
        
        $author = '';
        $reject = '';
        $active = '';
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $id = $_POST['id'];
        }else{
            $id = '';
        }
        $sql = "select * from reviews where reviews.itm_id = '$id' ";
        $rs = $this->db->query($sql);
        foreach ($rs->result_array() as $rows) {
            $rows['rcontent'] = $this->lib->SQLToFCK($rows['rcontent']);
            $rows['rname'] = $this->lib->SQLToFCK($rows['rname']);
            $reviews_array[] = $rows;
            
        }
        return  $reviews_array;
       
    }

    public function deleteReview() {
        if (empty($_POST['rid']) || empty($_POST['itm']))
            return '';
        $rid = $_POST['rid'];

        if ($rid > 0) {

            $this->db->query("delete from reviews where rid = $rid");
        }
    }

    public function activate() {
        if (isset($_POST['rid']) && isset($_POST['status'])) {
            $rid = $_POST['rid'];
            $status = $_POST['status'];

            if ($status == 0) {
                $status = 1;
            } else {
                $status = 0;
            }

            $this->db->query("update reviews set status = $status  where rid = $rid ");
        }
    }
  

}

?>
