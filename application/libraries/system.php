<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class System{
	var $CI;
	var $mod_rewrite = true;
	var $theme = 'TRP';
	var $mainpage = 'mainpage-admin.htm';
	var $access_right = array();
	
	var $header_site = '';
	var $content_site = '';
	var $footer_site = '';
	var $title_site = 'Scale Financial';
	var $title_page = '';
	var $keywords_site = '';
	var $description_site = '';
	var $account_content = '';
	var $hello_text = '';
	var $usefull_links = '';
	var $menu_system = '';
	var $nav_bar = '';
	var $nav_bar_bottom = '';
	var $db2 = 'mb_';
        var $footer_template = '';
        var $sign_up_account  = '';
        var $login_account = '';
        var $message = '';
        var $company_info = false;
        var $company_setting = false;
        var $compiliance_test_setting = false;
        var $order_supplies_setting = false;
        var $client_center_top = '';
        
        var $selectedCompanyName = '';
        var $selectedCompanyId = '';
		var $alloffice = '';
		
	var $timeclock = array(); 
	
	var $siteInfo = array('site_name' => '', 'email' => '','enroll_mail' => '', 'sender_name' => '', 'signature' => '', 'keyword' => '', 'description' => '');
	
	var $list_baner_left = array();
	var $list_baner_right = array();
	
	var $avatarPath = "data/users/avatars/";
	function __construct($_ldata_=NULL){
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$this->CI =& get_instance();
		
		if($_ldata_ === NULL){
			$this->CI->load->library('author');
			$this->CI->author->getDatas();
			$this->CI->author->isAccessSystem();
			
			$this->loadTheme();
	
			$this->CI->load->library('parser');
			$this->CI->load->library('database');
			$this->getSiteInfo();
                        
		}
	}
	
	function parse($templace, $data=array(), $type=false, $main_page=NULL){
		if(!is_array($data)) $data = array();
		if(isset($data['title_page']) && $data['title_page'] != ''){
			$this->title_page = $data['title_page'];
			$this->title_site = $this->title_page.' | '.$this->title_site;
		}
		if(isset($data['keywords_site'])) $this->keywords_site = $data['keywords_site'];
		if(isset($data['description_site'])) $this->description_site = $data['description_site'];
			
		$this->content_site = $this->CI->parser->parse($this->theme.'/templates/'.$templace, array(), true);
		$is_main = true;
		if(isset($_GET['ajax']) || isset($_POST['ajax'])) $is_main = false;
		
		if($is_main == true){
			$this->parse_data();
			if($main_page !== NULL) $this->mainpage = $main_page;
   			
			$data_main = array();
			if ($this->mainpage == 'mainpage-shop.htm'){
				$this->nav_bar = $this->load_categories_navbar2("","icon-caret-right","",1);
				try
				{
					$data_main['all_apartments'] = $this->load_categories_navbar2("","icon-caret-right","",0);
				}
				catch (Exception $e)
				{
					$data_main['all_apartments'] = "";
				}              
			}   
			$data_main['content_site'] = $this->content_site;
			$data_main['account_content'] = $this->account_content;
			$data_main['footer_template'] = $this->footer_template;
			$data_main['login_account']  = $this->login_account;
			$data_main['sign_up_account'] = $this->sign_up_account;
			$data_main['menu_system'] = $this->menu_system;
			$data_main['hello_text'] = $this->hello_text;
			$data_main['usefull_links'] = $this->usefull_links;
			$data_main['nav_bar'] = $this->nav_bar;
			$data_main['nav_bar_bottom'] = $this->nav_bar_bottom;
			$data_main['title_page'] = $this->title_page;
			$data_main['title_site'] = $this->title_site;
			$data_main['keywords_site'] = $this->keywords_site;
			$data_main['description_site'] = $this->description_site;

			$data_main['curPageURLServer'] = $this->URL_server__();
			$data_main['dir_theme'] = $this->theme;
			$data_main['url_base_path'] = $this->cleanUrl();

			$data_main['list_baner_left'] = $this->list_baner_left;
			$data_main['list_baner_right'] = $this->list_baner_right;
			$data_main['style'] = ($this->CI->author->objlogin->role['rid'] == 5)?'style="display:none"':'';
			$data_main['hidefromadmin'] = ($this->CI->author->objlogin->role['rid'] == 3)?'style="display:none"':'';
			$data_main['message'] = $this->message;
			$data_main['client_center_top'] = $this->client_center_top;
			if(count($this->timeclock) > 0){
				$data_main['month'] = $this->timeclock['thang'];
				$data_main['date'] = $this->timeclock['ngay'];
				$data_main['year'] = $this->timeclock['nam'];	
				$data_main['hour'] = $this->timeclock['gio'];
				$data_main['min'] = $this->timeclock['phut'];
				$data_main['sec'] = $this->timeclock['giay'];
			}
			$this->content_site = $this->CI->parser->parse($this->theme.'/'.$this->mainpage, $data_main, true);
		}
		$this->replaceURl();
		$this->content_site = $this->CI->parser->parse_string($this->content_site ,$data, true);
		if($type == true) return $this->content_site;
		else echo $this->content_site;
	}
	function check_ero_done(){
		$result = $this->CI->database->db_result("SELECT master_ero.uid FROM master_ero join users on master_ero.uid = users.uid WHERE master_ero.uid = ".$this->CI->author->objlogin->uid." & master_ero.efin <> '' AND master_ero.company_name <> '' AND master_ero.business_addr_1 <> '' AND master_ero.business_zip <> '' AND master_ero.business_city <> '' AND users.firstname <> '' AND users.lastname <> '' AND users.mail <> ''");
		if($result == NULL || $result == "") return 0;
	 	else return 1;
	}
        function check_ero_complete(){ 
                $sql = $this->CI->db->query("SELECT * FROM master_ero join users on master_ero.uid = users.uid WHERE master_ero.uid = ".$this->CI->author->objlogin->uid." AND master_ero.complete_status = '1' ");
                if($sql ->num_rows() > 0){
                    $res = $sql ->row_array();
                    if($res['data'] ==  1){
                        $this->company_setting = true;
                    }
                    $this->company_info = true;
                }	
        }
        
    function checkcompiliance_test(){
    	$sql = $this->CI->db->query("SELECT * FROM exam_results where uid = ".$this->CI->author->objlogin->uid." AND resultParcent = 100");
    	if($sql ->num_rows() > 0){
    		$res = $sql ->row_array();
    		
    		$this->compiliance_test_setting = true;
    	}
    }    
    
    
    
    function checkOrderSuppliesSetting(){
    	$sql = $this->CI->db->query("SELECT * FROM orders where uid = ".$this->CI->author->objlogin->uid." AND order_from = 1");
    	if($sql ->num_rows() > 0){
    		$res = $sql ->row_array();
    
    		$this->order_supplies_setting = true;
    	}
    }
    
    function checkcompiliance_test_status(){
    	$sql = $this->CI->db->query("SELECT * FROM exam_results where uid = ".$this->CI->author->objlogin->uid." AND resultParcent = 100");
    	if($sql ->num_rows() > 0){
    	//$res = $sql ->row_array();
    		return true;
    	}
    }
	function parse_templace($templace, $data=array(), $type=false){
		if(!is_array($data)) $data = array();
		$this->content_site = $this->CI->parser->parse($this->theme.'/templates/'.$templace, array(), true);
		$this->replaceURl();
		$this->content_site = $this->CI->parser->parse_string($this->content_site ,$data, true);	
		if($type == true) return $this->content_site;
		else echo $this->content_site;
	}
	
	function getOfficeInfo(){
		$htm = '';
		$check = false;
		
		if($this->CI->author->objlogin->role['rid'] == 5){
		$sql = $this->CI->db->query("SELECT users.uid , master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role, efin_pefin.efin as efin, efin_pefin.pefin as pefin, efin_pefin.status as efin_status
FROM users, efin_pefin, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND efin_pefin.uid = users.uid
AND efin_pefin.status = 1
AND users_roles.rid = 5
AND efin_pefin.pefin =".$this->CI->author->objlogin->efin."");
		
		if ($sql->num_rows() > 0) {
			$res = $sql->result_array();
			$check = true;
		}
		
		$sql1 = $this->CI->db->query("SELECT users.uid , master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role
FROM users, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND users_roles.rid = 5
AND users.efin =".$this->CI->author->objlogin->efin."");
		//echo $sql1->num_rows(); //$this->CI->author->objlogin->efin;
		//exit;
		if ($sql1->num_rows() > 0) {
			$res1 = $sql1->result_array();
		}
		}else{
			$sql1 = $this->CI->db->query("SELECT users.uid , master_ero.*, users.name AS username, users.efin AS user_efin, roles.name AS role
FROM users, roles, users_roles, master_ero
 Where users_roles.rid = roles.rid
AND users.uid = users_roles.uid
AND users.uid = master_ero.uid
AND users_roles.rid = 3
AND users.efin =".$this->CI->author->objlogin->efin."");
			if ($sql1->num_rows() > 0) {
				$res1 = $sql1->result_array();
			}
		}
		
		
		if($this->CI->author->objlogin->role['rid'] == 5)
			$selectedOffice = $res1[0]['company_name']; 
		else 
			 $selectedOffice = 'Company Name';//$_POST['office_list'];
		
		if ($check == true) {
			$result = array_merge($res1, $res);
		} else {
			$result = $res1;
		}
		//return $result;
		
		
		//if($result->num_rows() > 0){
			//$res = $result->result_array();
			foreach ($result as $resul){
				$htm .= '<option value="'.$resul["company_name"].'">'.$resul["company_name"].'</option>';
			}
			
			return  array('officeCombo' => $htm, 'selectedOffice' => $selectedOffice);
		//}
	}
	
	function parse_data(){
		$data = array();
		$check_done = $this->check_ero_done();
                $this->check_ero_complete();
                $this->checkcompiliance_test();
                $this->checkOrderSuppliesSetting();
		switch($this->CI->author->objlogin->role['rid']){
			case Anonymous_user:
				$this->mainpage = 'mainpage.htm';
				$this ->sign_up_account = $this->CI->parser->parse($this->theme.'/templates/signup.htm', array(), true);
                $this ->login_account = $this->CI->parser->parse($this->theme.'/templates/login.htm', array(), true);
				
				//$this ->nav_bar = $this->CI->parser->parse($this->theme.'/templates/guest_links.htm', array(), true);
				//$this ->nav_bar_bottom = $this->CI->parser->parse($this->theme.'/templates/guest_links_footer.htm', array(), true);	
				//$this->account_content = $this->CI->parser->parse($this->theme.'/templates/login_link.htm', array(), true);
				//$this->hello_text = $this->CI->parser->parse($this->theme.'/templates/shop/hello-guest.htm', array(), true);
				//$this->usefull_links = $this->CI->parser->parse($this->theme.'/templates/shop/usefull-links-guest.htm', array(), true);
				//$this->loadbanner_left();
//				$this->loadbanner_right();	
//				$this->loadClock();
				break;
			default:
				//$this->mainpage = 'mainpage-admin.htm';	
				// Depend on Role Main page will be load
				($this->CI->author->objlogin->role['rid'] == 5) ? $this->mainpage = 'mainpage-ero.htm' : $this->mainpage = 'mainpage-admin.htm'; 
				//$this->CI->load->library('menu');
				//if(! isset($this->CI->menu)){
//					$this->CI->menu = new Menu();	
//				}  

				//selectedOffice
                                $data['company_info'] = $this->company_info;
                                $data['company_setting'] = $this->company_setting;
                                $data['compiliance_test'] = $this->compiliance_test_setting;
                                $data['order_supplies_setting'] = $this->order_supplies_setting;

                if($this->CI->author->objlogin->isemployee == 0) { // if this is employee
                    $officedata = $this->getOfficeInfo();
                    $data['alloffice'] = $officedata['officeCombo'];
                    $data['selectedCompanyName'] = $officedata['selectedOffice'];
                }

                ($this->CI->author->objlogin->firstname != '') ? $data['login_name'] = $this->CI->author->objlogin->firstname.' '.$this->CI->author->objlogin->lastname : $data['login_name'] = $this->CI->author->objlogin->name;
				
                $data['user_avatar'] = $this->getAvatarByUserId();
				$data['ukey'] = $this ->CI ->author ->objlogin ->ukey;
				
				if($this->company_info == 1 && $this->company_setting == 1 && $this->compiliance_test_setting == 1 && $this->order_supplies_setting == 1){
					$this->client_center_top =  $this->CI->parser->parse($this->theme.'/templates/client_center_top.htm', $data,true);
				}else{
                     ($this->CI->author->objlogin->role['rid'] == 5 && $check_done == 0) ? $this->message = $this->CI->parser->parse($this->theme.'/templates/message.htm', $data, true) : "";
				}
				
				
				$this->account_content = $this->CI->parser->parse($this->theme.'/templates/header.htm', $data, true);
                $this->footer_template = $this->CI->parser->parse($this->theme.'/templates/footer.htm',$data, true);                                  		
		}	
	}
	private function getAvatarByUserId()
	{
		$avatar = $this->CI->database->db_result("SELECT picture FROM users WHERE uid = ".$this->CI->author->objlogin->uid." LIMIT 1");
		if($avatar == NULL || $avatar == "")
			$avatar = "default-avatar.png";
		return $this->URL_server__().$this->avatarPath.$avatar;
	}
	function loadClock(){
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
		$this->timeclock = $arr_time;
	}	
        //insert code here 

        function load_categories_navbar2($class="navbar_item",$icon="icon-caret-down",$wrap="div",$type=1)
	{
           
            $check_auto_deli = false;
            $product_type  = '';
            if(isset($_SESSION['auto_deli'])){
                $check_auto_deli = true;
                $product_type = ' and `product_type` = 0 ';
            }else{
                
                $check_auto_deli = false;
                $product_type = '';
            }
		if ($type == 0)
		$sql1 = "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid = 0 order by `cat_id` asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_items FROM `items` WHERE `itm_status` = 1 $product_type group by cat_id) T2 on T1.cat_id = T2.cid";
		else 
		$sql1 = "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid = 0  and status_display=1 order by `cat_id` asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_items FROM `items` WHERE `itm_status` = 1 $product_type  group by cat_id) T2 on T1.cat_id = T2.cid";
		$re1 = $this->CI->db->query($sql1);
		$rows_f = $re1->result_array();
		
		$sql2 = "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid <> 0 order by fid asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_items FROM `items` WHERE `itm_status` = 1 $product_type group by cat_id) T2 on T1.cat_id = T2.cid";
		$re2 = $this->CI->db->query($sql2);
                
               
		$rows_c = $re2->result_array();
		$info_cat = '';

		for ($i=0;$i<count($rows_f);$i++)
		{
			if ($rows_f[$i]['total_items'] == NULL) $rows_f[$i]['total_items'] = 0; else $rows_f[$i]['total_items'] = intval($rows_f[$i]['total_items']);
			$id = $rows_f[$i]['cat_id'];
			$child_str = $this->build_child_nav($id,$rows_c,"--",$wrap,$icon);
			if ($child_str == "")
			{
				if ($rows_f[$i]['total_items'] != 0) 
				$info_cat .= '<li><a pre="" href="'.$this->URL_server__() .'shop/pcategories?catkey='.$rows_f[$i]['cat_key'].'">'.$rows_f[$i]['cat_name'].'</a></li>';
			}
			else
			{
				if ($rows_f[$i]['total_items'] != 0) 
				$info_cat .= '<li><a pre="" href="'.$this->URL_server__() .'shop/pcategories?catkey='.$rows_f[$i]['cat_key'].'">'.$rows_f[$i]['cat_name'].'<i class="'.$icon.'"></i></a>'.$child_str.'</li>';
				else $info_cat .= '<li><a pre="" style="cursor:pointer;">'.$rows_f[$i]['cat_name'].'<i class="'.$icon.'"></i></a>'.$child_str.'</li>';
			}
		}
		return $info_cat;
	}

        //end insert code here
	
	public function build_child_nav($id,$c,$pre="",$wrap,$icon)
	{
		$info = "";
		for ($i = 0;$i<count($c);$i++)
		{
			if ($c[$i]['fid'] == $id)
			{
				$total = $c[$i]['total_items'];
				if ($total == NULL) $total = 0; else $total = intval($total);
				$child_str = $this->build_child_nav($c[$i]['cat_id'],$c,$pre."--",$wrap,$icon);
				if ($child_str == "")
				{
					if ($total != 0) $info .= '<li><a pre="'.$pre.'" href="'.$this->URL_server__() .'shop/pcategories?catkey='.$c[$i]['cat_key'].'" style="cursor:pointer">'.$c[$i]['cat_name'].'</a></li>';
				}
				else
				{
					if ($total != 0) $info .= '<li><a pre="'.$pre.'" href="'.$this->URL_server__() .'shop/pcategories?catkey='.$c[$i]['cat_key'].'" style="cursor:pointer">'.$c[$i]['cat_name'].' <i class="'.$icon.'"></i></a>'.$child_str.'</li>';
					else $info .= '<li><a pre="'.$pre.'" style="cursor:pointer">'.$c[$i]['cat_name'].' <i class="'.$icon.'"></i></a>'.$child_str.'</li>';
				}
			}
		}
		if ($info == "") return "";
		else {
		if ($wrap != "") return "<".$wrap."><ul>".$info."</ul></".$wrap.">";
		else return "<ul>".$info."</ul>";
		}
	}
	
	function replaceURl(){
		$this->content_site = str_replace("application/", $this->URL_server__()."application/", $this->content_site);
		$this->content_site = str_replace("shopping/", $this->URL_server__()."shopping/", $this->content_site);
		$this->content_site = str_replace("../", $this->URL_server__()."application/views/".$this->theme."/", $this->content_site);
		$this->content_site = str_replace("misc/", $this->URL_server__()."misc/", $this->content_site);
		$this->content_site = str_replace("resource/", $this->URL_server__()."resource/", $this->content_site);
		$this->content_site = str_replace("ajax-upload/", $this->URL_server__()."ajax-upload/", $this->content_site);
		$this->content_site = str_replace("plupload/", $this->URL_server__()."plupload/", $this->content_site);
	
		$this->content_site = str_replace("ultis/", $this->URL_server__()."ultis/", $this->content_site);
		$this->content_site = str_replace("nbproject/", $this->URL_server__()."nbproject/", $this->content_site);
		
		if($this->mod_rewrite == true){
			$this->content_site = str_replace("index.php/", $this->URL_server__(), $this->content_site);	
		}
		$this->content_site = str_replace("download.php", $this->URL_server__()."download.php", $this->content_site);	
	}
	// base url
	function URL_server__() {
		$pageURL = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
		$pageURL .= "://".$_SERVER['HTTP_HOST'];
		$pageURL .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
		if($this->CI->author->objlogin->role['rid'] == MANUFACTURER){
			//$pageURL .= 'shopping/';	
		}
		return $pageURL;
	}
	// clean Url
	function cleanUrl(){
		$st = $this->URL_server__();
		if($this->mod_rewrite == false){
			$st .= 'index.php?q=';		
		}
		return $st;
	}
	// goto page
	function URLgoto($page){
		if(isset($page) && $page != ''){
			if($page[0] == '/') $page = substr($page, 1);
			header('Location: '.$this->cleanUrl().$page);		
		}else{
			header('Location: '.$this->URL_server__());		
		}
	}
	function checkPageURL() {
		$pageURL = 'http';
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		
		$host  = $_SERVER['HTTP_HOST'];
		if(substr($host, 0, 3) != 'www'){
			$pageURL .= "www.".$host.$_SERVER["REQUEST_URI"];
			echo '<script language="javascript">window.location = "'.$pageURL.'";</script>';
			exit;	
		}
	}
	function curPageURL() {
		$pageURL = 'http';
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$pageURL .= $host.$_SERVER["REQUEST_URI"];
	 
		return $pageURL;
	}
	function __path_to_theme__(){
		if(!isset($this->theme) || $this->theme == '') $this->theme = 'bella';
		return $this->URL_server__()."application/views/".$this->theme;
	}
	// Check Mod Rewrite
	function load_rewrite(){
		if (isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE'] == 'On') {
			$this->mod_rewrite = true;
		}elseif(function_exists('apache_get_modules')){
			$this->mod_rewrite = in_array('mod_rewrite', apache_get_modules());	
		}else{
			$this->mod_rewrite = getenv('HTTP_MOD_REWRITE')=='On' ? true : false ;	
		}
	}
       
        
	function get_general_setting($id = 0)
	{
		if ($id == 0)
		{
			$query = $this->CI->db->query("SELECT  `id` ,  `minimum_purchased` AS  'Minimum_purchased',  `limit_time_purchase` ,  `units_purchase` ,  `number_of_level` AS  `Number_of_level` ,  `direct_sponsor` AS  `Direct_sponsor` ,  `to_be_active` AS  `To_be_active` , `units_active` ,  `date_apply` ,  `minimum_payment` AS  `Minimum_payment` ,  `limit_time_payment` ,  `units_payment` ,  `time_purchase_actived` ,  `units_time_purchase` ,  `date_update` ,  `date_holding_account`  FROM  `general_setting`  order by id desc LIMIT 1 ");
			if ($query->num_rows()<=0)
			{
				return $this->get_sysvals('sale_rep_setting', array());
			}
			return $query->row_array();
		}
		if ($id > 0)
		{
			$query = $this->CI->db->query("SELECT  `id` ,  `minimum_purchased` AS  'Minimum_purchased',  `limit_time_purchase` ,  `units_purchase` ,  `number_of_level` AS  `Number_of_level` ,  `direct_sponsor` AS  `Direct_sponsor` ,  `to_be_active` AS  `To_be_active` , `units_active` ,  `date_apply` ,  `minimum_payment` AS  `Minimum_payment` ,  `limit_time_payment` ,  `units_payment` ,  `time_purchase_actived` ,  `units_time_purchase` ,  `date_update` ,  `date_holding_account`  FROM  `general_setting`  where `id` = '".$id."'  ");
			if ($query->num_rows()<=0)
			{
				return $this->get_sysvals('sale_rep_setting', array());
			}
			return $query->row_array();
		}
             
	}
	function get_sysvals($title,$value){
		if(!isset($value)) $value = '';
		$query = $this->CI->db->query("select sysval_value from sysvals where sysval_title = '$title'");
		if ($query->num_rows() > 0){
			$row = $query->row_array();
			if($row['sysval_value']!=null && $row['sysval_value'] != '')
				$value = unserialize($row['sysval_value']); 
		}else{
			$this->set_sysvals($title,$value);	
		}
		return $value;
	}
	function set_sysvals($title,$value){
		$value = serialize($value);
		$sysval_id = 0;
		$query = $this->CI->db->query("select sysval_id,sysval_value from sysvals where sysval_title = '$title'");
		if ($query->num_rows() > 0){
			$row = $query->row_array();
			$sysval_id = $row['sysval_id']; 
			$this->CI->db->where('sysval_title', $title);
			$this->CI->db->update("sysvals", array('sysval_value'=>$value));	
		}else{
			$this->CI->db->insert("sysvals", array('sysval_value'=>$value, 'sysval_title'=>$title));
			$sysval_id = mysql_insert_id();
		}
		return $sysval_id;
	}
	function loadTheme(){
		$query = $this->CI->db->query("select sysval_value from sysvals where sysval_title = '_themes_'");
		if ($query->num_rows() > 0){
			$row = $query->row_array();
			if($row['sysval_value']!=null && $row['sysval_value'] != '' && is_dir("application/views/".$row['sysval_value'])) $this->theme = $row['sysval_value'];
			else $this->CI->db->update("sysvals", array('sysval_value'=>$this->theme), "sysval_title = '_themes_'");
		}else{
			$this->CI->db->insert("sysvals", array('sysval_value'=>$this->theme, 'sysval_title'=>'_themes_'));	
		}
	}
	function getSiteInfo(){
		$query = $this->CI->db->query("select * from site_info order by id desc limit 1");
		if ($query->num_rows() > 0){
			$this->siteInfo = $query->row_array();	
		}	
		if(isset($this->siteInfo['site_name']) && $this->siteInfo['site_name'] != '') $this->title_site = ucwords(strtolower($this->siteInfo['site_name']));
		if(isset($this->siteInfo['description']) && $this->siteInfo['description'] != '') $this->description_site = ucfirst(strtolower($this->siteInfo['description']));	
	}
}