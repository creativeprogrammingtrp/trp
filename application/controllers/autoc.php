<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autoc extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
	}//end __construct function
	
	public function index(){
		$this->load->library('orderlib');
		$this->load->library('email');
		$this->load->model("autoc_model", "autoc");
		
		$content = date('Y-m-d H:i:s',$this->lib->getTimeGMT());
		$this->email->from($this->system->siteInfo['email'], $this->system->siteInfo['sender_name']);
		$this->email->to('dong@nailsalontv.com');
		$this->email->subject('GMT time email');
		$this->email->message($content);
		$this->email->send();
		
		echo 'Done';
	}//end index function
	
	function truncate(){
		$tables = array(
			'checks','commission_acquisitions','commission_affiliate','commission_charities','commission_monthly','commission_monthly_items','invoices','odetail_return','orders','orders_attributes','orders_auto_delivery','orders_handling','orders_payment',
			'orders_promotions','orders_return','orders_schedule','order_detais','packages','packages_items','payments','payments_orders','raises','shipments', 'voucher'
		);
		foreach($tables as $table){
			$this->db->query("TRUNCATE TABLE $table");		
		}
	}
	
	public function perms(){
		$perms['Auto Application'] = array('index');
		$perms['Truncate Commissoin and Orders'] = array('truncate');
		return $perms;
	}//end perms function
}//end representative class