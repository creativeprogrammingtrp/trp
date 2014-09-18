<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menus extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('menu_model','menus');
	}
	function perms(){
		$perms['Management Menu System'] = array('index','savelist','lists');
		$perms['Add Menu'] = array('add','saveadd');
		$perms['Edit Menu'] = array('edit','saveedit');
		$perms['Delete Menu'] = array('delete');
		return $perms;		
	}
	function index(){
		$add_new = '';
		if($this->author->isAccessPerm('menus', 'add')){
			$add_new = '<input type="button" class="btn btn-primary" value="+ Add a Menu" onclick="AddNewObjects()" />';
		}
		$data['addnewbt'] = $add_new;
		$data['weight'] = $this->lib->loadWeightListtings(0);
		$data['title_page'] = 'Menus';
		$this->system->parse('menu.htm', $data);
	}
	//List
	function lists(){
		if($this->input->post('loadObj') && $this->input->post('loadObj') == 'yes'){
			echo json_encode($this->menus->Lists());
			exit;
		}
	}
	function delete(){
		if($this->input->post('delete_obj') && $this->input->post('delete_obj') == 'yes'){
			$this->menus->DeleteObj();
		}
		$this->lists();
	}
	function savelist(){
		if($this->input->post('save_form') && $this->input->post('save_form') == 'yes'){
			echo json_encode($this->menus->SaveForm());
			exit;
		}
	}
	//Add
	function add(){
		$data = $this->menus->Add();
		$this->system->parse('add_menu.htm',$data);
	}
	function saveadd(){
		if($this->input->post('saveObj') && $this->input->post('saveObj') == 'yes'){
			echo json_encode($this->menus->SaveObj());
			exit;
		}
	}
	//Edit
	function edit($key){
		if($this->lib->escape($key) != ''){
			$data = $this->menus->LoadValue($key);
			$this->system->parse('edit_menu.htm',$data);
		}
	}
	function saveedit(){
		if($this->input->post('saveObj') && $this->input->post('saveObj') == 'yes'){
			echo json_encode($this->menus->saveEdit());
			exit;
		}
	}
}
?>