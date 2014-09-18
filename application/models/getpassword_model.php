<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getpassword_model extends CI_Model {
	
	function GetPass(){
		$alert = '';
		$user = '';
		$email = '';
		$siteInfo = $this->lib->loadSiteInfo();
		$user = $this->lib->escape($this->input->post('username'));
		$email = $this->lib->escape($this->input->post('email'));
		$pass = $this->Password($email,$user);
		if($pass){	
			$content = "Your login information is provided below.<br>Email: <i>".$pass[3]."</i><br>Password: <i>".$this->author->decode_password($pass[0])."</i>";
			if($pass[4] != 1) $content = "Your account have been disabled.";
			
			$mailcontent['fullname'] = $pass[2];
			$mailcontent['email_content'] = $content;
			$mailcontent['signature'] = $siteInfo['signature'];
			$mailcontents = $this->system->parse_templace('general_email_content.htm', $mailcontent, TRUE);	
			
			$this->load->library('email');
			
			$this->email->from($siteInfo['email'], $siteInfo['sender_name']);
			$this->email->to($pass[3]);
			$this->email->subject($siteInfo['sender_name']." password");
			$this->email->message($mailcontents);
			
			$this->email->send();
				
			$alert = "Your login information has been sent to your email. Please check your email!";
		}else{
			$alert = "Your account doesn't exist. Please check your email address!";
		}
		echo $alert;
		exit;
	}
	private function Password($email,$name){
		$pass		= array();
		$useremail 	= $email;
		$r = $this->db->query("SELECT pass,name,firstname,lastname,mail,status FROM users WHERE mail = '$useremail' and name ='$name' ");
		if ($row = $r->row_array()){
			$pass[0] = $row["pass"]; 
			$pass[1] = $row["name"]; 
			$pass[2] = $row["firstname"]." ".$row['lastname']; 
			$pass[3] = $row["mail"]; 
			$pass[4] = $row["status"];  
		}
		return $pass;
	}
}