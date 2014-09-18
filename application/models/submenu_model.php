<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submenu_model extends CI_Model {
	var $mkey = '';
	var $data_parse = array();
	var $title_page = '';
	var $description = '';
	var $breadcrumb = array();
	var $menu_child = '';
	
	var $role = array();
	var $menu_segment;
	
	public function __construct(){
		parent::__construct();
		$this->role = $this->author->objlogin->role;
	}
	
	function setkey($mkey=NULL){
		if($mkey !== NULL){
			$this->mkey = $mkey;	
		}	
	}
	
	function loadRootMenu(){
		$re = $this->db->query("select * from ".$this->system->db2."menu where status = 1 and mkey = '".$this->mkey."' order by weight DESC,name ASC");
		if($re->num_rows() > 0){
			$row = $re->row_array();
			$perm = array();
			if(!is_null($row['perm'])){
				$perm = unserialize($row['perm']);	
			}
			if(!in_array($this->role['rid'], $perm)) return false;
			
			$this->load->library('menu');
			if(! isset($this->menu)){
				$this->menu = new Menu();	
			}
			
			$this->menu_segment = new Menu_segments();

			$link = str_replace('index.php?q=', '', $row['link']);
			$link = str_replace('index.php/', '', $link);
			if($link != ""){
				if($link[0] == '/') $link = substr($link, 1);
				$link = $this->menu_segment->set_uri_string($link);
				$segments = $this->menu->explode_segments($link);
				$this->menu_segment->getController($segments);
			}
			
			if($link != ''){
				if(! $this->author->isAccessPerm($this->menu_segment->class, $this->menu_segment->method)){
					return false;
				}
			}  
			
			$this->title_page = $row['name'];	
			$this->description = $row['description'];
			$this->breadcrumb[] = array(
				'breadtitle' => $this->title_page
			);
			$this->loadFather($row['parent']);
			$this->menu_child = $this->loadChild($row['id']);	
		}	
	}
	
	function loadChild($id){
		$_menus_ = '';
		$query = $this->db->query("select * from ".$this->system->db2."menu where status = 1 and parent = $id order by weight DESC,name ASC");
		if($query->num_rows() > 0){//0
			foreach($query->result_array() as $row){//1
				$perm = array();
				if(!is_null($row['perm'])){
					$perm = unserialize($row['perm']);	
				}
				if(!in_array($this->role['rid'], $perm)) continue;
				
				$_menus_child_ = $this->loadChild($row['id']);
				
				$link = str_replace('index.php?q=', '', $row['link']);
				$link = str_replace('index.php/', '', $link);
				if($link != ""){
					if($link[0] == '/') $link = substr($link, 1);
					$link = $this->menu_segment->set_uri_string($link);
					$segments = $this->menu->explode_segments($link);
					$this->menu_segment->getController($segments);
				}
				
				if($link != ''){
					if(! $this->author->isAccessPerm($this->menu_segment->class, $this->menu_segment->method)){
						continue;	
					}
					$link = $this->system->cleanUrl().$link;
				}else{
					if($_menus_child_ == '') continue;
					$link = $this->system->cleanUrl().'admin/submenu/mkey/'.$row['mkey'];
				}  
				
				$_menus_ .= '<li><a href="'.$link.'" class="menu-link-title">'.$row['name'].'</a><br />'.$row['description'];
				$_menus_ .= $_menus_child_ != '' ? '<br />'.$_menus_child_ : '';
				$_menus_ .= '</li>';
			}
		}
		if($_menus_ != ''){
			$_menus_ = '<ul>'.$_menus_.'</ul>';		
		}
		return $_menus_;
	}
	
	function loadFather($parent){
		$re = $this->db->query("select * from ".$this->system->db2."menu where status = 1 and id = $parent order by weight DESC,name ASC");
		if($re->num_rows() > 0){
			$row = $re->row_array();
			
			$perm = array();
			if(!is_null($row['perm'])){
				$perm = unserialize($row['perm']);	
			}
			if(!in_array($this->role['rid'], $perm)) return false;
			
			$link = str_replace('index.php?q=', '', $row['link']);
			$link = str_replace('index.php/', '', $link);
			if($link != ""){
				if($link[0] == '/') $link = substr($link, 1);
				$link = $this->menu_segment->set_uri_string($link);
				$segments = $this->menu->explode_segments($link);
				$this->menu_segment->getController($segments);
			}
			
			if($link != ''){
				if(! $this->author->isAccessPerm($this->menu_segment->class, $this->menu_segment->method)){
					return false;
				}
				$link = $this->system->cleanUrl().$link;
			}else{
				$link = $this->system->cleanUrl().'admin/submenu/mkey/'.$row['mkey'];
			}  
				
			$this->breadcrumb[] = array(
				'breadtitle' => '<a href="'.$link.'">'.$row['name'].'</a>'
			);
			$this->loadFather($row['parent']);	
		}
	}
	
	function parse(){
		$this->loadRootMenu();
		$this->data_parse['title_page'] = $this->title_page;
		$this->data_parse['description'] = ($this->description != '') ? '<div class="description">'.$this->description.'</div>' : '';
		$this->data_parse['breadcrumb'] = count($this->breadcrumb) > 0 ? array_reverse($this->breadcrumb) : $this->breadcrumb;
		$this->data_parse['menu_child'] = $this->menu_child;
		return $this->data_parse;
	}
}