<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Categories extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/categories_model','categories');
    }
    
    public function index(){
        $list = $this->categories->option();
        $data = array(  'title_page'    => 'Categories',
                        '@weight@'      => $list['@weight@']
                     );
        $data['add_cate_btn'] = $this->addbtn();
        $this->system->parse('taxonomy.htm', $data);
    }
    
    public function perms(){
        $perms['List Categories'] = array('index', 'listCate');
        $perms['Process Categories'] = array('addbtn', 'editCate', 'delCate', 'add', 'del','delete');
        $perms['Add Categories'] = array('subadd', 'edit', 'saveEdit', 'saveFormList');
		
        return $perms;
    }
    
    public function listCate(){
        if($this->input->post('loadObj') && $this->input->post('loadObj') == 'yes'){
            echo json_encode($this->categories->loadData());
            exit;
        }
    }

    public function addbtn(){
        $strContent = '';
        if($this->author->isAccessPerm('categories', 'addbtn')){
            $strContent = '<input type="button" class="btn btn-primary" value="+ Add Category" onclick="AddNewObjects()" />';
        }
        return $strContent;
    }
    
    public function add(){
        $list = $this->categories->option();
        $data = array('title_page' => 'Add a Category', '@weight@' => $list['@weight@'], '@parent@' => $list['@parent@']);
        $this->system->parse('add_category.htm', $data);
    }
    
    public function subadd(){
        if($this->input->post('saveObj') && $this->input->post('saveObj') == 'yes'){
            echo json_encode($this->categories->saveObj());
            exit;
        }
    }
    
    public function edit($key = ''){
        $str = $this->categories->loadValue($key);
        $data = array();
		$data['id'] = $str['id'];
        $data['@key@']              = $str['@key@'];
        $data['@cat_name@']         = $str['@cat_name@'];
        $data['@description@']      = $str['@description@'];
        $data['@weight@']           = $str['@weight@'];
        $data['@parent@']           = $str['@parent@'];
		$data['check']  = $str['check']==1?"checked":"";
		$data['img'] = $str['img'];
        
        $this->system->parse('edit_category.htm', $data);
    }
    
    public function saveEdit(){
        if($this->input->post('saveObj') && $this->input->post('saveObj') == 'yes'){
            echo json_encode($this->categories->saveObjEdit());
            exit;
        }
    }

    public function del($key = ''){
        if($key != '') $data = array('key' => $key);
        $this->system->parse_templace('delete_category.htm', $data);
    }
    public function delete($key = ''){
        if($this->input->post('delete_obj') && $this->input->post('delete_obj') == 'yes'){
            echo json_encode($this->categories->delete_obj($key));
            exit;
        }
    }
    public function saveFormList(){
        $this->categories->saveform();
    }
}