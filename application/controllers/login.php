<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{
	var $name = '';
	var $pass = '';
        var $efin = '';
	function __construct(){
		parent::__construct();
	}
	function index(){
		$data = array(
			'title_page' => 'Login'
		);
		$data['url_base_path'] = $this->system->cleanUrl();
		$data['curPageURLServer'] = $this->system->URL_server__();
		//$this->system->parse_templace('login.htm', $data);
		$this->system->parse('login.htm', $data);
	}
	
	function checklogin(){
        $this->efin 	= $this->lib->escape($_POST["e"]);
		$this->name 	= $this->lib->escape($_POST["u"]);
		$this->pass 	= $this->lib->escape($_POST["p"]);
                
		$page = 'no';
        $checkStatus = $this->author->checkLogin($this->efin,$this->name, $this->pass);
		if($checkStatus == 1){
		//if($this->author->checkLogin($this->name, $this->pass)){
			$this->session->set_userdata('sess_login', $this->author->objlogin);    
                $page = "OK";
		}elseif($checkStatus == 2){
            $page = "Reject";
        }
		echo $page;	
	}
	
	function sendmessage(){
		
		$to = 'zishanmomin@gmail.com';
		$fromName = "ScaleFinancial.com";
		$fromEmail = "info@scalefinancial.com";
		$subject = 'Message From Customer at scalefinancial.com';
		
				
		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$fromName.'<'.$fromEmail.'>' . "\r\n";
		
		$cname = $this->lib->escape($_POST['cname']);
		$cemail = $this->lib->escape($_POST['cemail']);
		$cphone = $this->lib->escape($_POST['cphone']);
		$cmessage = $this->lib->escape($_POST['cmessage']);
		
		$html = "Dear Admin,<br><br>";
		$html .= "Contact person information :<br><br>";
		$html .= "<strong>Name:</strong> ".$cname. "<br>";
		$html .= "<strong>Email:</strong> ".$cemail. "<br>";
		$html .= "<strong>Phone:</strong> ".$cphone. "<br>";
		$html .= "<strong>Message:</strong> ".$cmessage. "<br>";
		
		$body = "<html><head><title>$subject</title></head><body> {$html} <br> <br> <br>Regards,<br><b>Priyank</b></body></html>";
		if(mail($to, $subject,$body, $headers)){
			echo "ok";
		}else{
			echo "error";
		}
		
		
		//$mailTo = 'shuvro@osourcebd.com';
		
		//$this->lib->mail_simple($mailTo, "Message From Customer.", $this->lib->getMailInfor('site_info', 'email'), $this->lib->getMailInfor('site_info', 'signature'), $html);
		//$this->email->send();
		
		
	}

	function perms(){
		$perms['Login Site'] = array('index', 'checklogin','sendmessage');
		return $perms;		
	}
}