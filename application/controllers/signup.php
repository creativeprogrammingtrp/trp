<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller{

	public function perms(){
		$perms['Sign up'] = array('index','register','saveUserDatas','checkUserlogin','forgotpassword');
		return $perms;			
	}
	
	
	
	public function index(){
		$data = array();
		$data['title_page'] = 'Signup';
		//$this ->system ->parse("signup.htm",$data);
		$data['url_base_path'] = $this->system->cleanUrl();
		$data['curPageURLServer'] = $this->system->URL_server__();
		$this->system->parse_templace('signup.htm', $data);

	}
	
	public function register(){
		if($this ->input ->post('save_data') == 'yes'){
			echo $this ->saveUserDatas();
			exit;
		}
	}
	
	private function signupTemplate(){
		$data = array();
		$data['title_page'] = 'Signup';
		$this ->system ->parse('signup.htm',$data);
	}
	
	public function forgotpassword(){
		$this ->load ->model("signup_model");
		$result = $this ->signup_model->getPassword();
		$fromName = "ScaleFinancial.com";
		$fromEmail = "info@scalefinancial.com";
		$mailcontent = '';
		$mailTo = $result[0]['mail'];
		
		$mailcontent .= "Dear ".$result[0]['name'];
		$mailcontent .= "<p>We got password recovery request from you.</p>";
		$mailcontent .= "<p>Your login info:</p>";
		$mailcontent .= "<p>Username: ".$result[0]['name']."</p>";
		$mailcontent .= "<p>Password: ".$result[0]['pass']."</p>";
		$mailcontent .= "<p>Regards,<br>".$fromName."</p>";
		$mailcontent .= "<p>&nbsp;</p>";
		
		//$to = 'zishanmomin@gmail.com';
		$to = 'shuvro@osourcebd.com';
		$subject = 'Forgot Password request from ScaleFinancial.com';
		
		$body = "<html><head><title>$subject</title></head><body> {$mailcontent} <br> <br> <br>Regards,<br><b>Priyank</b></body></html>";
		
		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$fromName.'<'.$fromEmail.'>' . "\r\n";
		
		
		if(mail($to, $subject,$body, $headers)){
			echo "ok";
		}else{
			echo "error";
		}
		//$this->lib->mail_simple($mailTo, "Password Recovery Email.", $this->lib->getMailInfor('site_info', 'email'), $this->lib->getMailInfor('site_info', 'signature'), $mailcontent);
		
	}
	
	
	private function saveUserDatas(){
		$this->load->library('form_validation');
		$mailcontent = '';
		$this ->load ->model("signup_model");
                if(isset($_POST['email'])) {
                	$mailTo = $this ->input ->post('email');
                }else{
                	$mailTo = '';
                }
                $page = "no";
                
                $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
                
                $check =  $this ->author ->check_efin_exists($this ->input ->post('efin'));
                $checkUsername =  $this ->author ->check_usernaem_exists($this ->input ->post('username'));
                $checkEmail =  $this ->author ->check_email_exists($this ->input ->post('email'));
                if($check == 'exit'){
                   // $page = "EFIN were registed. Please choose another EFIN or login with this account.";
                	$page = "EFIN Number is already in use.";
                }else if($check == 'no'){
                    $page = "EFIN Number is not valid.";
                }else if($checkEmail == 'mailexit'){
                	$page = "Email address is already in use.";
                }elseif($this->form_validation->run() == FALSE) {
				$page = validation_errors();
				//return false;	
				}
                else if($checkUsername == 'nameexit'){
                	$page = "Username is already in use.";
                }elseif($this ->input ->post('confpassword') != $this ->input ->post('password')){
                	$page = "The passwords do not match please re-enter.";
                }else{
                    $this ->signup_model ->saveUserDatas();
                    $page = "OK";
                }
		if($page == 'OK'){
			if($this->author->checkSignUp($this ->input ->post('efin'))){
				$this->session->set_userdata('sess_login', $this->author->objlogin);
			}
			$mailcontent .= "Welcome to Tax Refund Products, ".$this ->input ->post('username');
			$mailcontent .= "<p>We're thrilled to have you on board. Be sure to save these account details for future reference: </p>";
			$mailcontent .= "<p>Your username is: ". $this ->input ->post('username') ."</p>";
			$mailcontent .= "<p>Forgot your password? <a href='#'>Find it here</a></p>";
			$mailcontent .= "<strong>LET’S GET STARTED! </strong>";
			$mailcontent .= "<p>To get started go to TRPPlus.com and sign in. Once your signed in you will need to complete the company registration and finish the training</p>";
			$mailcontent .= "<p>&nbsp;</p>";
			$mailcontent .= "<p>&nbsp;</p>";
			$mailcontent .= "<p>Questions? Concerns? Don’t hesitate to get in touch.</p>";
			$mailcontent .= "<p>&nbsp;</p>";
			$mailcontent .= "<p>&nbsp;</p>";
			$mailcontent .= "<p>Happy working,<br>The Scale Financial Crew </p>";
			$mailcontent .= "<p>&nbsp;</p>";
			
			$this->lib->mail_simple($mailTo, "Confirm Email.", $this->lib->getMailInfor('site_info', 'email'), $this->lib->getMailInfor('site_info', 'signature'), $mailcontent);     
			
			// check user login info
			$page = $this->checkUserlogin($this ->input ->post('efin'), $this ->input ->post('username'), $this ->input ->post('password'));
		}

		echo $page;		
	}

	function checkUserlogin($efin, $username, $password){
                
		$page = 'no';      
		if($this->author->checkLogin($efin,$username, $password)){
			$this->session->set_userdata('sess_login', $this->author->objlogin);    
                $page = "OK";
		}
		echo $page;	
	}	
}