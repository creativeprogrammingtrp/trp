<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu{
	var $CI;
	var $menuID = 0;
	var $owned = 0;
	var $menu_segment;
	
	function __construct(){
		$this->CI =& get_instance();
	}
	
	function loadMenusSystem(){
		$_menus_ = '';
		
		$role = $this->CI->author->objlogin->role;
		
		$active_menu = ' active ';
		$active_menu_li = " active ";
		$active_menu_a = ' menuActive';
		$active__ = false;
		
		$rlink = $this->CI->uri->uri_string();
		if($rlink == '') $rlink = $role['rlink'];
		
		$this->menu_segment = new Menu_segments();
		
		$query = $this->CI->db->query("select * from ".$this->CI->system->db2."menu where status = 1 and parent = 0 order by weight DESC,name ASC");
		if($query->num_rows() > 0){//0
			foreach($query->result_array() as $row){//1
				$perm = array();
				if(!is_null($row['perm'])){
					$perm = unserialize($row['perm']);	
				}
				if(!in_array($role['rid'], $perm)) continue;	
				
				//$arr_menuChild = $this->__loadMenu_child__($row['id']);
				$_menus_child_ = $arr_menuChild['menu_link'];
				
				$link = str_replace('index.php?q=', '', $row['link']);
				$link = str_replace('index.php/', '', $link);
				if($link != ""){
					if($link[0] == '/') $link = substr($link, 1);
					$link = $this->menu_segment->set_uri_string($link);
					$segments = $this->explode_segments($link);
					$this->menu_segment->getController($segments);
				}
				
				$active = '';
				$active_a ='';
				if($active__ == false){
					if($rlink == $link){
						$active = $active_menu;	
						$active_a = $active_menu_a;
					}else{
						$active = $arr_menuChild['active'];	
						$active_a = ($active!='')?$active_menu_a:'';
					}
					if($active == $active_menu){
						$active__ = true;	
					}
				}
				if($link != ''){
					$arr_link = explode("/", $link);
					if(! (isset($arr_link[0]) && strcasecmp($arr_link[0], 'shopping') == 0)){
						if(! $this->CI->author->isAccessPerm($this->menu_segment->class, $this->menu_segment->method)){
							continue;	
						}
						$link = $this->CI->system->cleanUrl().$link;		
					}
				}else{
					if($_menus_child_ == '') continue;
					$link = $this->CI->system->cleanUrl().'admin/submenu/mkey/'.$row['mkey'];
				}
				$menu_num = ($arr_menuChild['menu_num']>0 && $this ->CI ->system ->theme == 'bootstrap')? ' <span class="label">'.$arr_menuChild['menu_num'].'</span>':'';

				$_menus_ .= '<li class="'.$active.'">';
				if ($_menus_child_ != '')
				{
                                        $_menus_ .= '<a href="'.$link.'" ><i class="'.$row['icon'].'"></i>'.$row['name'].'</a>';
					$_menus_ .= '<ul>'.$_menus_child_.'</ul>';	
				}
				else
				{
					$_menus_ .= '<a  href="'.$link.'"><i class="'.$row['icon'].'"></i>'.$row['name'].'</a>';
					$_menus_ .= '<ul>'.$_menus_child_.'</ul>';
				}
				$_menus_ .= '</li>';
				
			}//1	
		}//0
		
		if($_menus_ != ''){
			$_menus_ = ''.$_menus_.' ';		
		}
		return $_menus_;
	}
	function __loadMenu_child__($id){
		$role = $this->CI->author->objlogin->role;
		$active_menu = ' active';
		$active_menu_a = ' menuActive';
		$active__ = false;
		
		$rlink = $this->CI->uri->uri_string();
		if($rlink == '') $rlink = $role['rlink'];
		
		$str = '';
		$query = $this->CI->db->query("select * from ".$this->CI->system->db2."menu where status = 1 and parent = '$id' order by weight DESC,name ASC");
		$count =0;
		if($query->num_rows() > 0){//0
			foreach($query->result_array() as $row){
				$perm = array();
				if(!is_null($row['perm'])){
					$perm = unserialize($row['perm']);	
				}
				if(!in_array($role['rid'], $perm)) continue;
                                $arr_menuChild = $this->__loadMenu_child__($row['id']);
				$_menus_child_ = $arr_menuChild['menu_link'];
       
				$link = str_replace('index.php?q=', '', $row['link']);
				$link = str_replace('index.php/', '', $link);
				if($link != ""){
					if($link[0] == '/') $link = substr($link, 1);
					$link = $this->menu_segment->set_uri_string($link);
					$segments = $this->explode_segments($link);
					$this->menu_segment->getController($segments);
				}
				
				$active = '';
				$active_a = '';
				if($active__ == false){
					if($rlink == $link){
						$active = $active_menu;	
						$active_a = $active_menu_a;
					}else{
						$active = $arr_menuChild['active'];	
						$active_a = $arr_menuChild['active'];	
					}
					if($active == $active_menu){
						$active__ = true;	
					}
				}
				
				if($link != ''){
					$arr_link = explode("/", $link);
					if(! (isset($arr_link[0]) && strcasecmp($arr_link[0], 'shopping') == 0)){
						if(! $this->CI->author->isAccessPerm($this->menu_segment->class, $this->menu_segment->method)){
							continue;	
						}
						$link = $this->CI->system->cleanUrl().$link;
					}
				}else{
					if($_menus_child_ == '') continue;
					$link = $this->CI->system->cleanUrl().'admin/submenu/mkey/'.$row['mkey'];
				}  
				$count++;
				$class = 'bg_nobo';
				$submenu = '';
				if($_menus_child_ != '') {
					$class = 'bg_child';
					$submenu = 'my-submenu ';
				}
				$icon = $row['icon'];
				if($icon == null || $icon == '') $icon = 'glyphicon-cloud';
				$str .= '<li><a href="'.$link.'"><i class="'.$icon.'"></i> '.$row['name'].'</a><ul>'.$_menus_child_.'</ul></li>';
				
			}
		}
		
		$parent = ($active__ == true)? $active_menu:'';
		return array('menu_link' => $str, 'active' => $parent,'menu_num' =>$count);
	}

	function explode_segments($uri_string){
		$segments = array();
		foreach (explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $uri_string)) as $val){
			// Filter segments for security
			$val = trim($this->filter_uri($val));
			if ($val != ''){
				$segments[] = $val;
			}
		}
		return $segments;
	}
	function filter_uri($str){
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');
		return str_replace($bad, $good, $str);
	}
	
	function loadParentListtings($value=0, $owned=0){
		$str = '';
		
		$query = $this->CI->db->query("select id,name from ".$this->CI->system->db2."menu where status = 1 and parent = 0 and id <> '$owned' order by weight DESC,name ASC");
		foreach($query->result_array() as $row){
			$select = '';
			if($row['id'] == $value) $select = 'selected="selected"';
			$str .= '<option value="'.$row['id'].'" '.$select.'>'.$row['name'].'</option>';	
			$this->loadParent_child($str, $row['id'], '---', $value, $owned);
		}
		return $str;				
	}
	function loadParent_child(&$str, $id, $padding_left, $value, $owned){
		$query = $this->CI->db->query("select id,name from ".$this->CI->system->db2."menu where status = 1 and parent = '$id' and id <> '$owned' order by weight DESC,name ASC");
		foreach($query->result_array() as $row){
			$select = '';
			if($row['id'] == $value) $select = 'selected="selected"';
			$str .= '<option value="'.$row['id'].'" '.$select.'>'.$padding_left.' '.$row['name'].'</option>';	
			$this->loadParent_child($str, $row['id'], $padding_left.'---', $value, $owned);
		}
	}
}

class Menu_segments{
	var $segments = array();
	var $routes			= array();
	var $class			= '';
	var $method			= 'index';
	var $directory		= '';
	
	function __construct($segments=NULL){
		$this->CI =& get_instance();
		if(is_array($segments)){
			$this->segments = $segments;	
		}
		
		// Load the routes.php file.
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/routes.php')){
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}elseif (is_file(APPPATH.'config/routes.php')){
			include(APPPATH.'config/routes.php');
		}
		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);
	}
	
	function restore(){
		$this->segments = array();
		$this->class = '';
		$this->method = 'index';	
		$this->directory = '';
	}
	
	function getController($segments=NULL){
		$this->restore();
		
		if(is_array($segments)){
			$this->segments = $segments;	
		}
		$this->validate_request();

		$this->set_class($this->segments[0]);

		if (isset($this->segments[1])){
			$this->set_method($this->segments[1]);
		}else{
			$this->set_method('index');
		}
	}
	
	function validate_request(){
		if (count($this->segments) == 0){
			return false;
		}
		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$this->segments[0].'.php')){
			return false;
		}
		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$this->segments[0])){
			// Set the directory and remove it from the segment array
			$this->set_directory($this->segments[0]);
			$this->segments = array_slice($this->segments, 1);

			if (count($this->segments) > 0){
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->directory.$this->segments[0].'.php')){
					if ( ! empty($this->routes['404_override'])){
						$x = explode('/', $this->routes['404_override']);
						$this->set_directory('');
						$this->set_class($x[0]);
						$this->set_method(isset($x[1]) ? $x[1] : 'index');
						$this->segments = $x;
					}
				}
			}
		}
	}
	
	function set_uri_string($str){
		$uri_string = '';
		// Filter out control characters
		$str = remove_invisible_characters($str, FALSE);

		// If the URI contains only a slash we'll kill it
		$uri_string = ($str == '/') ? '' : $str;
		return $uri_string;
	}
	
	function set_directory($dir){
		$this->directory = str_replace(array('/', '.'), '', $dir).'/';
	}
	function set_class($class){
		$this->class = str_replace(array('/', '.'), '', $class);
	}
	function set_method($method){
		$this->method = $method;
	}
}
?>