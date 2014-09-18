<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rolepermission extends CI_Controller {
	var $access = array();

	function __construct(){
		parent::__construct();
		$this->load->model('Rpermission_model','mod');
	}
	
	function index(){
		$rid = $this->input->get('key')!==FALSE && is_numeric($this->input->get('key'))?$this->input->get('key'):0;
		$title = $this->mod->nameRoles($rid);
		if ($title == '')
		{
			header('location:roles');
			exit();
		}
		
		$this->mod->getRole($rid);
		$this->getAllAccessFromAllControllers();
		$this->showTablePermission();
		$data = array(
			'title_page' => $title.' Permissions',
			'key'=>$rid
		);
		$data = array_merge($data, $this->showTablePermission());
		$this->system->parse('rolepermission.htm', $data);	
	}
	
	function saveperm(){
		$this->getAllAccessFromAllControllers();
		$this->mod->saveRolesAccess($this->access);
	}
	
	function showTablePermission(){
		$arr_disabled = array();
		$arr_disabled[3] = array(
			'Management Permissions System'
		);
		
		$th_role = array();
		$tbody = array();
		$dataRoles = array();
		if(count($this->mod->arrRoles) > 0){
			foreach($this->mod->arrRoles as $role){
				$role_new = $role;
				if(isset($role_new['per'])) unset($role_new['per']);
				$dataRoles[] = $role_new;
				
				$th_role[] = array('role_name' => $role['name']);	
			}
			if(count($this->access) > 0){
				foreach($this->access as $controller => $perms){
					$tbody_role = array();
					if(is_array($perms) && count($perms) > 0){
						$count = 0;
						foreach($perms as $access_name => $functions){
							$background = '';
							if($count%2==0) $background = 'class="row_perm"';
							$count ++;
							$td_role_access = array();
							foreach($this->mod->arrRoles as $role){
								$check = '';
								$disabled = '';
								if(isset($arr_disabled[$role['rid']]) && is_array($arr_disabled[$role['rid']])){
									if(in_array($access_name, $arr_disabled[$role['rid']])){
										$check = 'checked="checked"';
										$disabled = 'disabled="disabled"';		
									}	
								}
								if(isset($role['per']) && is_array($role['per']) && count($role['per']) > 0){
									foreach($role['per'] as $per){
										if($per['permission'] == $access_name & $per['controller'] == $controller){
											$check = 'checked="checked"';
											break;	
										}	
									}
								}
								$td_role_access[] = array(
									'value' => $access_name.'|'.$controller,
									'rid' => $role['rid'],
									'check' => $check,
									'disabled' => $disabled
								);	
							}
							$tbody_role[] = array(
								'background' => $background,
								'perm' => $access_name,
								'td_role_access' => $td_role_access
							);	
						}	
					}
					
					$tbody[] = array(
						'module' => $controller,
						'colspan' => count($this->mod->arrRoles)+1,
						'tbody_role' => $tbody_role
					);	
				}	
			}
		}
		
		$arr_replace = array(
			'th_role' => $th_role,
			'tbody' => $tbody,
			'dataRoles' => 'dataRoles='.json_encode($dataRoles).';'
		);
		return $arr_replace;
	}
	
	function getAllAccessFromAllControllers(){
		$this->access['Rolepermission'] = $this->perms();
		
		$this->load->library('files');
		$arr_class = $this->files->getFiles('application/controllers', array('php','PHP'));
		
		if(is_array($arr_class) && count($arr_class) > 0){
			foreach($arr_class as $filepath){
					
				$arr_name = explode("/", $filepath);
				$class = $arr_name[count($arr_name)-1];
				$class = str_replace(".php", "", $class);
				$class = ucfirst($class);
				if ($class == 'Rolepermission') continue;
				if(!class_exists($class)){
					require($filepath);
					if(class_exists($class)){
						$hookf = new $class();
						if($hookf->perms()){
							$perms = $hookf->perms();
							if(is_array($perms) && count($perms) > 0)
								$this->access[$class] = $perms;		
						}
					}
				}else{
					echo $filepath.' is exist controller.<br>';	
				}
			}	
		}
	}	
	
	function perms(){
		$perms['Management Permissions Role'] = array('index', 'saveperm');
		return $perms;		
	}
}