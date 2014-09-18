<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siteinfo extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('site_info', 'site');
		$this->load->library('lib');
		
	}	
	
	function perms(){
		$perms['Setup information system'] = array('Index','saveInfo');
		return $perms;			
	}
	
	public function Index(){
		$result = $this->site->View();
		if($result != '') {
			$data = array(
				  'id'          => $result[0]->id,
				  'site_name'   => $result[0]->site_name,
				  'email'       => $result[0]->email,
				  'enroll_email'=> $result[0]->enroll_email,
				  'sender_name' => $result[0]->sender_name,
				  'signature'	=> $result[0]->signature,
				  'keyword'	    => $result[0]->keyword,
				  'description' => $result[0]->description,
				  // default value
				  'title_page'       => 'Site Information',
          		  'keywords_site'    => 'keywords info',
		    	  'description_site' => 'description info',
				);
		} else {
			$data = array(
				  'id'          => '',
				  'site_name'   => '',
				  'email'       => '',
				  'enroll_email'=> '',
				  'sender_name' => '',
				  'signature'	=> '',
				  'keyword'	    => '',
				  'description' => '',
					//default value
				  'title_page'       => 'Site Information',
          		  'keywords_site'    => 'keywords info',
		    	  'description_site' => 'description info',	
				);
		}		
		$this->system->parse('siteinfo.htm', $data);
	}
		
	public function saveInfo(){	
		if($this->input->post('updateinfo') == 'yes') {
			$id = $this->input->post('id');
			$data = array(
			  'site_name'   => $this->lib->escape($this->input->post('site_name')),
			  'email'       => $this->lib->escape($this->input->post('mail_address')),
			  'enroll_email'=> $this->lib->escape($this->input->post('enroll_email')),
			  'sender_name' => $this->lib->escape($this->input->post('sender_name')),
			  'signature'	=> $this->lib->escape($this->input->post('signature')),
			  'keyword'	    => $this->lib->escape($this->input->post('keywords')),
			  'description' => $this->lib->escape($this->input->post('description'))
			);
			$this->site->save($data, $id);
			echo 1; 
		} else echo 0;
	}
}