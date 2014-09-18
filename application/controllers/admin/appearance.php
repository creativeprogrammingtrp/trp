<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appearance extends CI_Controller {


	var $path;
	function __construct(){
		parent::__construct();
		$this->load->model('appearance_model', 'appear');
		$this->load->helper('file');
	}

	function perms(){
		$perms['Management Appearance System'] = array('index', 'saveTheme');
		return $perms;		
	}
	
	public function index(){
		$this->path = "application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR;
		$dirs = get_dir_file_info($this->path);
		$arrDirs = array();
		foreach($dirs as $key => $value){
			if(is_dir($dirs[$key]['server_path'])){ $arrDirs[] = $dirs[$key]['name']; }
		}

		$result = $this->appear->view();
		if($result != ''){
			$value = $result[0]->sysval_value;
			$checked = 'checked = "checked"';
			for($i = 0; $i < sizeof($arrDirs); $i++){
				$active = '';
				if(strcmp(trim($value), trim(strtolower($arrDirs[$i]))) === 0) $active = $checked;
				$send[] = array(
					'id'      => $result[0]->sysval_id,
					'title'   => $arrDirs[$i],
					'theme'   => $arrDirs[$i],
					'checked' => $active
				);
			}
		} else {
			for($i = 0; $i < sizeof($arrDirs); $i++){
				$send[] = array(
					'id'      => '',
					'title'   => $arrDirs[$i],
					'theme'   => $arrDirs[$i],
					'checked' => ''
				);
			}
		}
		$data = array('themes' => $send);
		$data['title_page'] = 'Appearance';
		$this->system->parse('themes.htm', $data);
	}

	public function saveTheme(){		
		if($this->input->post('save') == 'yes'){
			$id = $this->input->post('id');
			$value = trim($this->input->post('title'));
			if(empty($id)) {
				$data = array(
					'sysval_title' => '_themes_',
					'sysval_value' => strtolower($value)
				);
			} else {
				$data = array(
					'sysval_value' => strtolower($value) );
			}
			$re = $this->appear->update($data, $id);
			if($re) echo 1;
			else echo 0;
		} else echo 0;
	}	


}