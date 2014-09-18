<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Orders extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/supplies_model", "supplies");
    }
    function perms(){
        $perms['Orders'] = array('index','cart','saveOrderFromModal');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"Orders");
        $this->system->parse("orders/orders.htm",$data);
    }
    
    public function cart(){
    	
    	
    	$data = array();
    	$data['title_page'] = "Orders";
    	$data['itemname'] = $this->lib->escape($_POST['item_name']);
    	$data['itemprice'] = $this->lib->escape($_POST['price']);
    	$this->system->parse("orders/cart.htm",$data);
    }
    
    public function saveOrderFromModal(){
    	$data = array();
    	$data['title_page'] = "Orders";
    	/*
    	$data['itemname'] = $this->lib->escape($_POST['item_name']);
    	$data['itemprice'] = $this->lib->escape($_POST['price']);
    	$this->system->parse("orders/cart.htm",$data);
    	*/
    	$data['dataLoad'] = "dataClient = " . json_encode($this->supplies->saveOrderInfoFromModal());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		//$this->system->parse_templace('settings/profilesettings.htm', $data);
    		exit();
    	}
    }
    
}
    
?>
