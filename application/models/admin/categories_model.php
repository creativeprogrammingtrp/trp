<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Categories_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function loadData(){
        $arrUsers = array();
        $modify = 'no';
		$del = 'no';
        if($this->author->isAccessPerm('categories','editCate')) $modify = 'yes';
        if($this->author->isAccessPerm('categories','delCate')) $del = 'yes';
        
        $count_product_inactive = $this->database->db_result("select COUNT(*) from items where cat_id = '0' AND itm_status > 0");
		$arrUsers[] = array(
							'products'			=>$count_product_inactive,
							'del'				=>'no',
							'modify'			=>'no',
							'cat_key'			=>0,
							'cat_name'			=>'Inactived Product',
							'weight'			=>50
					);	
		$re = $this->db->query("select * from categories where status = 1 order by weight DESC,cat_name ASC");
		foreach($re->result_array() as $row){
			$row['products'] = $this->database->db_result("select COUNT(*) from items where cat_id = '".$row['cat_id']."' AND itm_status > 0");	
			$row['del'] 		= $del;
			$row['modify'] 		= $modify;		
			$arrUsers[] 		= $row;
		}
		return $arrUsers;
    }
            
    public function delete_obj($key){
        $error = '';
        if(isset($key)){
            $re = $this->db->query("select cat_id from categories where cat_key = '$key'");
            foreach($re->result_array() as $row){
                $this->db->update('categories', array('status'=>-1), "cat_id = ".$row['cat_id']);
                $this->db->update('categories', array('status'=>-1), "fid = ".$row['cat_id']);
                $this->db->update('items', array('cat_id'=>0), "cat_id = ".$row['cat_id']);
            }
        }
		return $this->loadData();
    }
    
    public function option(){
        $list = array(
                '@weight@' => $this->lib->loadWeightListtings(0),
                '@parent@' => $this->lib->loadParentCategories(0, 0)
        );
        return $list;
    }
    
    public function saveObj(){
		$key = $this->lib->GeneralRandomKey(20);
		$re = $this->db->query("select cat_id from categories where cat_key = '$key'");
		foreach($re->result_array() as $row){
			$key = $this->lib->GeneralRandomKey(20);
			$re = $this->db->query("select cat_id from categories where cat_key = '$key'");
		}
		$error = '';
		$data_ = array(
						'cat_key'				=> $key,
						'cat_name' 				=> $this->lib->escape($this->input->post('cat_name')),
						'description'           => $this->lib->escape($this->input->post('description')),
						'fid'                   => $this->lib->escape($this->input->post('fid')),
						'weight'                => $this->input->post('weight'),
						'status_display'		=> $this->input->post('display')=="true"?1:0
					);
	
		$id = $this->db->insert('categories', $data_);
		$last_id = $this->db->insert_id();
		if(is_numeric($id) || $id < 0)
		{
			$error = _error_cannot_insert_db_;
		}
		else
		{
			$arr = json_decode($this->input->post('img'));
			$destination = 'resource/catbanner/'.$last_id;
			$source = 'resource/catbanner';
			if (count($arr) > 0)
			{
				if(!is_dir($destination)){		
					$oldumask = umask(0) ;
					mkdir( $destination, 0777);
					umask( $oldumask ) ;
				}
				for ($i = 0;$i<count($arr);$i++)
				{
					@rename($source.'/'.$arr[$i], $destination.'/'.$arr[$i]);
				}
			}
		}
	
		return array('error' => $error);
    }
    
    public function loadValue($ckey){
		$cat_name = '';
		$weight = 0;
		$link = '';
        $key = $ckey;
		$description = '';
		$parent = 0;
		$cat_id = 0;
		$check = 0;
		$arr_file = array();
	
        $re = $this->db->query("select * from categories where cat_key = '$key'");
        foreach($re->result_array() as $row){
        	$cat_id = $row['cat_id'];
			$arr_file = $this->directoryToArray('resource/catbanner/'.$cat_id);
            $cat_name = $this->lib->ConvertToTest($row['cat_name']);
            $weight = $row['weight'];
            $description = $row['description'];
            $parent = $row['fid'];
			$check = $row['status_display'];
        }
        $str = array();
		$str['check'] = $check;
		$str['id'] = $cat_id;
        $str['@key@']               = $key;
        $str['@cat_name@']          = $cat_name;
        $str['@description@']       = $description;
        $str['@weight@']            = $this->lib->loadWeightListtings($weight);
        $str['@parent@']            = $this->lib->loadParentCategories($parent, $cat_id);
		$str['img'] = $arr_file;
        
        return $str;
    }
    
    public function saveObjEdit(){
        $key = (isset($_POST['key']) && $_POST['key']!='')? $_POST['key']:'';
		$arr_img_new = json_decode($this->input->post('img'));
		$arr_img_old = $this->directoryToArray('resource/catbanner/'.$_POST['id'].'/');
		
		for ($i = 0;$i<count($arr_img_old);$i++)
		{
			$has = false;
			for ($j = 0;$j<count($arr_img_new);$j++)
			{
				if (strrpos($arr_img_old[$i]['list_img'],$arr_img_new[$j]) !== FALSE)
				{
					$has = true;
					break;
				}
			}
			$arr_name = explode('/',$arr_img_old[$i]['list_img']);
			if (!$has) @unlink('resource/catbanner/'.$_POST['id'].'/'.$arr_name[count($arr_name)-1]);
		}
		
		$error = '';
		$data_ = array(
						'cat_name' 				=> $this->lib->escape($this->input->post('cat_name')),
						'description'           => $this->lib->escape($this->input->post('description')),
						'fid'                   => $this->lib->escape($this->input->post('fid')),
						'weight'                => $this->input->post('weight'),
						'status_display'		=> $this->input->post('display')=='true'?1:0
				);
		$this->db->update('categories', $data_, "cat_key = '$key'");
	
		return array('error' => $error);
    }
    
    public function saveform(){
		$error = '';
		$saveform = $_POST['saveform'];
		if(is_array($saveform) && count($saveform) > 0){
			foreach($saveform as $data_){
				$this->db->update('categories', $data_, "cat_key = '".$data_['cat_key']."'");	
			}	
		}
		return array('error' => $error);
    }
	public function directoryToArray($directory) {
		$array_items = array();
		if (!is_dir($directory)) return $array_items;
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$file = $directory . "/" . $file;
					$array_items[]['list_img'] = $this->system->URL_server__().preg_replace("/\/\//si", "/", $file);
				}
			}
			closedir($handle);
		}
		return $array_items;
	}
}