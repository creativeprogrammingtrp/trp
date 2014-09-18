<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timegmt extends CI_Controller{
	
	function __construct(){
		parent::__construct();
	}
	function perms(){
		$perms['Clock GMT'] = array('index','gettimeGMT');
		return $perms;		
	}
	
	function index(){
		if($this->input->post('getTimeGMT') && $this->input->post('getTimeGMT') == 'yes'){
			echo json_encode($this->gettimeGMT());
		}
		exit;
	}
	function gettimeGMT(){
		$now = time();
			$arr_time = array(
				'thu' => gmdate('l', $now),
				'ngay' => gmdate('j', $now),
				'thang' => gmdate('m', $now),
				'nam' => gmdate('Y', $now),
				'gio' => gmdate('G', $now),
				'phut' => gmdate('i', $now),
				'giay' => gmdate('s', $now)
			);
		return $arr_time;
	}
}