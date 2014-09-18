<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Massmail extends CI_Controller {

	var $permissions = array();
	function __construct(){
		parent::__construct();
		$this->load->model('massmail_model', 'mod');
		$this->load->library('lib');
	}
	function perms(){
		$perms['List Mass E-mail Messages'] = array('index');
		$perms['Add Mass E-mail Messages'] = array('add');
		$perms['Edit Mass E-mail Messages'] = array('edit');
		$perms['Delete Mass E-mail Messages'] = array('delete');
		return $perms;			
	}

	public function index(){
		if ($this->input->post('getdata')!==FALSE && $this->input->post('getdata')=='yes')
		{
			echo $this->mod->display();
			exit;
		}
		$data = array();
		$this->generate_permission($data);
		$this->generate_keyword($data);
		$data['title_page'] = 'Mass E-mail Messages';
		$data['data'] = $this->mod->display();
		$this->system->parse('mass_email_messages.htm',$data);
	}

	public function add(){
		if ($this->input->post('add')!==FALSE && $this->input->post('add')=='yes')
		{
			echo json_encode($this->mod->add());
			exit;
		}
		$data['title_page'] = 'Create E-mail Message Template';
		$data['from_mail'] = EMAIL_SUPPORT;
		$data['roles'] = $this->mod->displayRoles();
		$this->system->parse('add_mail_messages.htm',$data);
	}

	public function edit(){
		if ($this->input->post('edit')!==FALSE && $this->input->post('edit')=='yes')
		{
			echo json_encode($this->mod->edit());
			exit;
		}
		if ($this->input->get('key') !== FALSE)
		{
			
			$data['title_page'] = 'Edit E-mail Message Template';
			$this->mod->loadvalue($data,$this->input->get('key'));
			$this->system->parse('edit_mail_messages.htm',$data);
		}
	}

	public function delete()
	{
		echo $this->mod->delete();
		
	}
	public function generate_permission(&$data)
	{
		$data['addnew'] = $this->author->isAccessPerm("Massmail", "add")?'<input type="button" class="button" value="+ Add E-mail Messages Template" style="float:left; margin-right:5px;" onclick="AddNewAccount()" />':"";
		$data["edit"] = $this->author->isAccessPerm("Massmail", "edit")?"yes":"no";
		$data['delete'] = $this->author->isAccessPerm("Massmail", "delete")?"yes":"no";
	}
	
	public function generate_keyword(&$data)
	{
		$data['key'] = '';
		$data['col'] = 0;
		$data['direction'] = 'asc';
		$data['page'] = 1;
		if ($this->input->get('key')!==FALSE) $data['key_word'] = htmlentities($this->input->get('key'));
		if ($this->input->get('col')!==FALSE && is_numeric($this->input->get('col')) && $this->input->get('col') >= 0) $data['col'] = $this->input->get('col');
		if ($this->input->get('direction')!==FALSE && in_array($this->input->get('direction'),array("asc","desc"))) $data['direction'] = $this->input->get('direction');
		if ($this->input->get('page')!==FALSE && is_numeric($this->input->get('page')) && $this->input->get('page')>0) $data['page'] = $this->input->get('page');
	}
}

