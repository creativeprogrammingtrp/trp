<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
		$this->load->library('menu');
	}
	//List
	public function Lists(){
		
		$modify = 'no';
		$del = 'no';
		
		if($this->author->isAccessPerm('menus', 'edit')){
			$modify = 'yes';	
		}
		if($this->author->isAccessPerm('menus', 'delete')){
			$del = 'yes';	
		}
		
		$data = array();
		$query = $this->db->query("select * from ".$this->system->db2."menu order by weight DESC,name ASC");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$link = $row['link'];
				$link = str_replace('index.php?q=', '', $link);
				$link = $this->system->cleanUrl().$link;
				$row['link'] = $link;
				$row['del'] = $del;
				$row['modify'] = $modify;
				$data[] = $row;	
			}
		}
		return $data;
	}
	public function SaveForm(){
		$err = '';
		$saveform = $this->input->post('saveform');
		if(is_array($saveform) && count($saveform) > 0){
			foreach($saveform as $data){
				$this->db->where('mkey', $data['mkey']);
				$this->db->update($this->system->db2.'menu', $data);
			}
		}
		return $err;
	}
	public function DeleteObj(){
		$key = $this->lib->escape($this->input->post('key'));
		if(isset($key) && $this->lib->escape($key) != ''){
			$query = $this->db->query("select id from ".$this->system->db2."menu where mkey = '".$key."'");
			if($query->num_rows() > 0){
				foreach($query->result_array() as $row){
					$this->db->delete($this->system->db2."menu","id = ".$row['id']);
					$this->db->delete($this->system->db2."menu","parent = ".$row['id']);	
				}		
			}
		}
		return true;
	}
	//Add
	public function Add(){
		$data = array(
			'title_page' 	=> 'Add Menu',
			'weight' 		=> $this->lib->loadWeightListtings(0),
			'parent' 		=> $this->menu->loadParentListtings(0, 0),
			'permission' 	=> $this->displayRoles()
		);
		return $data;
	}
	private function displayRoles(){
		$str_roles = '';
		$arr_roles = $this->author->__dataRoles__();
		if(count($arr_roles) > 0){
		$str_roles .= '<table cellpadding="0" cellspacing="0" border="0">';
			foreach($arr_roles as $role){
				$str_roles .= '<tr>';
				$str_roles .= '<td align="left" valign="middle" width="20px" style="padding-bottom:5px"><input type="checkbox" class="input-checkbox" name="menu_access[]" value="'.$role['rid'].'" checked="checked" /></td>';
				$str_roles .= '<td align="left" valign="middle" style="padding-bottom:5px">'.$role['name'].'</td>';
				$str_roles .= '</tr>';		
			}
			$str_roles .= '</table>';	
		}
		return $str_roles;	
	}
	public function SaveObj(){
		$error = '';
		$key = $this->lib->GeneralRandomKey(20);
		$query = $this->db->query("select id from ".$this->system->db2."menu where mkey = '$key'");
		while($query->num_rows() > 0){
			$key = $this->db->GeneralRandomKey(20);
			$query = $this->db->query("select id from ".$this->system->db2."menu where mkey = '$key'");
		}
		$perm = array();
		if(is_array($this->input->post('menu_access')) && count($this->input->post('menu_access')) > 0){
			foreach($this->input->post('menu_access') as $rid){
				$perm[] = $rid;	
			}
		}
		$perm = serialize($perm);
		$data = array(
			'mkey'			=> $key,
			'icon'			=> $this->lib->escape($this->input->post('icon')),
			'name' 			=> $this->lib->escape($this->input->post('name')),
			'description' 	=> $this->lib->escape($this->input->post('description')),
			'link' 			=> $this->lib->escape($this->input->post('link')),
			'parent' 		=> $this->lib->escape($this->input->post('parent')),
			'weight'		=> $this->lib->escape($this->input->post('weight')),
			'perm' 			=> $perm
		);
		$id = $this->db->insert($this->system->db2.'menu', $data);
		if(!isset($id) || $id < 0)
		$error = 'Can not insert to database';
		return $error;
	}
	//Edit
	public function LoadValue($gkey){
		$data_load = array();
		$data = array();
		$key = $gkey;
		if($key != ''){
			$query = $this->db->query("select * from ".$this->system->db2."menu where mkey = '$key'");
			if($query->num_rows() > 0){
				$data_load = $query->row_array();
			}
		}
		if(is_array($data_load) && count($data_load)){
			$perm = array();
			if(!is_null($data_load['perm'])){
				$perm = unserialize($data_load['perm']);
				$data_load['permission'] = 	$this->displayRolesEdit($perm);	
			}
			$data['title_page'] = 'Edit Menu';
			$data['key'] = $data_load['mkey'];
			$data['icon'] = $data_load['icon'];
			$data['name'] = $data_load['name'];
			$data['link'] = $data_load['link'];
			$data['description'] = $data_load['description'];
			$data['weight'] = $this->lib->loadWeightListtings($data_load['weight']);
			$data['parent'] = $this->menu->loadParentListtings($data_load['parent'], $data_load['id']);
			$data['permission'] = $data_load['permission'];
		}	
		return $data;
	}
	function displayRolesEdit($perm){
		$str_roles = '';
		$arr_roles = $this->author->__dataRoles__();
		if(count($arr_roles) > 0){
			$str_roles .= '<table cellpadding="0" cellspacing="0" border="0">';
			foreach($arr_roles as $role){
				$check_ = '';
				if(in_array($role['rid'], $perm)) $check_ = 'checked="checked"';
				$str_roles .= '<tr>';
				$str_roles .= '<td align="left" valign="middle" width="20px" style="padding-bottom:5px"><input type="checkbox" class="input-checkbox" name="menu_access[]" value="'.$role['rid'].'" '.$check_.' /></td>';
				$str_roles .= '<td align="left" valign="middle" style="padding-bottom:5px">'.$role['name'].'</td>';
				$str_roles .= '</tr>';		
			}
			$str_roles .= '</table>';	
		}
		return $str_roles;
	}
	function saveEdit(){
		$key = $this->input->post('key');
		$error = '';
		$perm = array();
		if(is_array($this->input->post('menu_access')) && count($this->input->post('menu_access')) > 0){
			foreach($this->input->post('menu_access') as $rid){
				$perm[] = $rid;	
			}
		}
		$perm = serialize($perm);
		
		$data_ = array(
			'icon' => $this->lib->escape($this->input->post('icon')),
			'name' 		=> $this->lib->escape($this->input->post('name')),
			'description' => $this->lib->escape($this->input->post('description')),
			'link' => $this->lib->escape($this->input->post('link')),
			'parent' => $this->lib->escape($this->input->post('parent')),
			'weight'	=> $this->input->post('weight'),
			'perm' => $perm		
		);
		
		$this->db->where('mkey', $key);
		$this->db->update($this->system->db2.'menu', $data_);
		return array('error' => $error);	
	}
}