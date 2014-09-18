<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dressing_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function loadData(){
        $categories_select = $this->system->get_sysvals('dressing_room', array());
        $categories = array();
		$re = $this->db->query("select cat_name,cat_id from categories where status = 1 order by weight DESC,cat_name ASC");
		foreach($re->result_array() as $row){
			$select = 0;
			if(in_array($row['cat_id'], $categories_select)) $select = 1;
            	$categories[] = array(
                                       'id' 		=> (int)$row['cat_id'],
                                       'name' 		=> $row['cat_name'],
                                       'select_' 	=> $select
                                    );
		}
        $categories["if('load_categories'=='yes');"] = "categories=".json_encode($categories).";";
        
        return $categories;
    }
    
    public function submit_category_dressing(){
                $arrData = array();
                if(isset($_POST['categories_select'])){
                    $arrData = $_POST['categories_select'];
                    if(count($arrData) == 0) $arrData = array();
                }
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $this->system->set_sysvals('dressing_room', $arrData);
                }
                
		return $arrData; // array();
    }
}
