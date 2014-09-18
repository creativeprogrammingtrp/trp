<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypal_model extends CI_Model 
{
	public function loaddata()
	{
		$authorize_gateway = '';
		$paypal_gateway = '';
		$first_data_gateway = '';
		$firstdata_host = '';
		$firstdata_port = '';
		$firstdata_store_number = '';
		$authorize_0 = '';
		$authorize_1 = '';
		$auth_net_login_id = '';
		$auth_net_tran_key = '';
		$API_USERNAME = '';
		$API_PASSWORD = '';
		$API_SIGNATURE = '';
		$email_to_get_order = '';
		$signature = '';
		$first_data_0 = '';
		$first_data_1 = '';
		$re = $this ->db ->query("SELECT * FROM paypal_settings");
		
		if($re->num_rows() > 0){
			$row = $re->row_array();
			switch($row['payment_name']){
				case 'authorize':
					$authorize_gateway = 'checked="checked"';
					break;
				case 'paypal':
					$paypal_gateway = 'checked="checked"';
					break;
				case 'firstdata':
					$first_data_gateway = 'checked="checked"';
					break;		
			}
			$firstdata_host = (!empty($row['firstdata_host']))?$row['firstdata_host']:'';
			$firstdata_port = (!empty($row['firstdata_port']))?$row['firstdata_port']:'';
			$firstdata_store_number = (!empty($row['firstdata_store_number']))?$row['firstdata_store_number']:'';
			if(isset($row['transaction']) && $row['transaction'] == 'LIVE'){
				$first_data_1 = 'checked="checked"';	
			}else{
				$first_data_0 = 'checked="checked"';		
			}
			if(isset($row['auth_url']) && $row['auth_url'] == 'liveMode'){
				$authorize_1 = 'checked="checked"';	
			}else{
				$authorize_0 = 'checked="checked"';		
			}
			$auth_net_login_id = (!empty($row['auth_login_id']))?$row['auth_login_id']:'';
			$auth_net_tran_key = (!empty($row['auth_tran_key']))?$row['auth_tran_key']:'';
			$API_USERNAME = (!empty($row['api_username']))?$row['api_username']:'';
			$API_PASSWORD = (!empty($row['api_password']))?$row['api_password']:'';
			$API_SIGNATURE = (!empty($row['api_signature']))?$row['api_signature']:'';
			$email_to_get_order = (!empty($row['email_order']))?unserialize($row['email_order']):'';
			$signature = (!empty($row['signature']))?$row['signature']:'';
		}
		return array(
			'authorize_gateway' =>$authorize_gateway,
			'paypal_gateway' =>$paypal_gateway,
			'first_data_gateway' =>$first_data_gateway,
			'__firstdata_host__' =>$firstdata_host,
			'__firstdata_port__' =>$firstdata_port,
			'__firstdata_store_number__' =>$firstdata_store_number,
			'first_data_0' =>$first_data_0,
			'first_data_1' =>$first_data_1,
			'auth_net_login_id' =>$auth_net_login_id,
			'auth_net_tran_key' =>$auth_net_tran_key,
			'authorize_0' =>$authorize_0,
			'authorize_1' =>$authorize_1,
			'API_USERNAME' =>$API_USERNAME,
			'API_PASSWORD' =>$API_PASSWORD,
			'API_SIGNATURE' =>$API_SIGNATURE,
			'email_to_get_order'=>(is_array($email_to_get_order) && count($email_to_get_order)>0)?$email_to_get_order[0] :'',
			'email_obj' =>"email_obj = ".json_encode($email_to_get_order),
			'signature' =>$signature,
		);
	}//end loaddata function
	
	public function savePayment()
	{
		$auth_net_url = 'testMode';
		$__payment_name__  =(isset($_POST['payment_type']) && is_array($_POST['payment_type']))?$__payment_name__ = $_POST['payment_type'][0]:'';	
		$authorize_mode = (isset($_POST['authorize_mode']) && is_array($_POST['authorize_mode']))?$_POST['authorize_mode'][0]:0;
		if($authorize_mode == 1) $auth_net_url = 'liveMode';
		
		$auth_net_login_id = (!empty($_POST['auth_net_login_id']))?$_POST['auth_net_login_id']:'';
		$auth_net_tran_key = (!empty($_POST['auth_net_tran_key']))?$_POST['auth_net_tran_key']:'';	
		$API_USERNAME = (!empty($_POST['API_USERNAME']))?$_POST['API_USERNAME']:'';		
		$API_PASSWORD = (!empty($_POST['API_PASSWORD']))?$_POST['API_PASSWORD']:'';	
		$API_SIGNATURE = (!empty($_POST['API_SIGNATURE']))?$_POST['API_SIGNATURE']:'';	
		$__email_to_get_order__ = (isset($_POST['email_to_get_order']) && is_array($_POST['email_to_get_order']))? $_POST['email_to_get_order']:'';	
		$__signature__ = (!empty($_POST['signature']))? $_POST['signature']:'';	
		
		$__firstdata_host__ = (!empty($_POST['__firstdata_host__']))?$_POST['__firstdata_host__']:'';	
		$__firstdata_port__ =(!empty($_POST['__firstdata_port__']))?	$_POST['__firstdata_port__']:'';	
		$__firstdata_store_number__ = (!empty($_POST['__firstdata_store_number__']))?$_POST['__firstdata_store_number__']:'';
		$__transaction__ = (isset($_POST['first_data_mode']) && is_array($_POST['first_data_mode']))?$_POST['first_data_mode'][0]:'';			
		$data = array(
			'payment_name' =>$this->lib->escape($__payment_name__),
			'auth_login_id' =>$this->lib->escape($auth_net_login_id),
			'auth_tran_key' =>$this->lib->escape($auth_net_tran_key),
			'auth_url' =>$this->lib->escape($auth_net_url),
			'email_order' =>serialize($__email_to_get_order__),
			'signature' =>$this->lib->escape($__signature__),
			'api_username' =>$this->lib->escape($API_USERNAME),
			'api_password' =>$this->lib->escape($API_PASSWORD),
			'api_signature' =>$this->lib->escape($API_SIGNATURE),
			'firstdata_host' =>$this->lib->escape($__firstdata_host__),
			'firstdata_port' =>$this->lib->escape($__firstdata_port__),
			'firstdata_store_number' =>$this->lib->escape($__firstdata_store_number__),
			'transaction' =>$this->lib->escape($__transaction__),
		);
		$this ->db->truncate('paypal_settings');
		$this ->db ->insert('paypal_settings',$data);
	}//end SaveFilePayment function
}//end Paypal_model class