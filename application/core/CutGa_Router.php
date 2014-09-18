<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CutGa_Router extends CI_Router {
	var $ses_login;
	var $sess_encrypt_cookie		= FALSE;
	var $sess_use_database			= FALSE;
	var $sess_table_name			= '';
	var $sess_expiration			= 7200;
	var $sess_expire_on_close		= FALSE;
	var $sess_match_ip				= FALSE;
	var $sess_match_useragent		= TRUE;
	var $sess_cookie_name			= 'ci_session';
	var $cookie_prefix				= '';
	var $cookie_path				= '';
	var $cookie_domain				= '';
	var $cookie_secure				= FALSE;
	var $sess_time_to_update		= 1000;
	var $encryption_key				= '';
	var $flashdata_key				= 'flash';
	var $time_reference				= 'time';
	var $gc_probability				= 5;
	var $userdata					= array();
	var $now;
	var $role__ 					= array('rid'=>1, 'name'=>'Anonymous user', 'rlink'=>'login');
	function __construct(){
        parent::__construct();
		
		include(APPPATH.'libraries/objlogin.php');
		$this->loadRole();
    }
	
	function _set_routing()
	{
		// Are query strings enabled in the config file?  Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		$segments = array();
		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			if (isset($_GET[$this->config->item('directory_trigger')]))
			{
				$this->set_directory(trim($this->uri->_filter_uri($_GET[$this->config->item('directory_trigger')])));
				$segments[] = $this->fetch_directory();
			}

			if (isset($_GET[$this->config->item('controller_trigger')]))
			{
				$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));
				$segments[] = $this->fetch_class();
			}

			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
				$segments[] = $this->fetch_method();
			}
		}

		// Load the routes.php file.
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}
		elseif (is_file(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');
		}
		
		if(isset($this->ses_login->role['rlink']) && $this->ses_login->role['rlink'] != '' && $this->ses_login->role['rlink'] != null){
			$route['default_controller'] = 	$this->ses_login->role['rlink'];
		}

		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);

		// Set the default controller so we can display it in the event
		// the URI doesn't correlated to a valid controller.
			
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

		// Were there any query string segments?  If so, we'll validate them and bail out since we're done.
		if (count($segments) > 0)
		{
			return $this->_validate_request($segments);
		}

		// Fetch the complete URI string
		$this->uri->_fetch_uri_string();

		// Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
		if ($this->uri->uri_string == '')
		{
			return $this->_set_default_controller();
		}

		// Do we need to remove the URL suffix?
		$this->uri->_remove_url_suffix();

		// Compile the segments into an array
		$this->uri->_explode_segments();

		// Parse any custom routing that may exist
		$this->_parse_routes();

		// Re-index the segment array so that it starts with 1 rather than 0
		$this->uri->_reindex_segments();
	}
	function _serialize($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else
		{
			if (is_string($data))
			{
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}

		return serialize($data);
	}
	function _unserialize($data)
	{
		$data = @unserialize($this->strip_slashes($data));

		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('{{slash}}', '\\', $val);
				}
			}

			return $data;
		}

		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}
	function strip_slashes($str)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str[$key] = $this->strip_slashes($val);
			}
		}
		else
		{
			$str = stripslashes($str);
		}

		return $str;
	}
	
	function loadRole(){
		include(APPPATH.'config/config.php');
		$this->sess_encrypt_cookie = isset($config['sess_encrypt_cookie'])?$config['sess_encrypt_cookie']:false;
		$this->encryption_key = isset($config['encryption_key'])?$config['encryption_key']:'';
		if(isset($config['sess_expiration'])) 	$this->sess_expiration = $config['sess_expiration'];
		if(isset($config['sess_cookie_name'])) 	$this->sess_cookie_name = $config['sess_cookie_name'];
		if(isset($config['cookie_path'])) 		$this->cookie_path = $config['cookie_path'];
		if(isset($config['cookie_domain'])) 	$this->cookie_domain = $config['cookie_domain'];
		if(isset($config['cookie_secure'])) 	$this->cookie_secure = $config['cookie_secure'];
		if(isset($config['sess_expire_on_close'])) 	$this->sess_expire_on_close = $config['sess_expire_on_close'];
		if(isset($config['time_reference'])) 	$this->time_reference = $config['time_reference'];
		if(isset($config['default_role'])) 	$this->role__ = $config['default_role'];
		if(isset($config)) unset($config);
		$this->now = $this->_get_time();
		
		if(isset($_COOKIE['ci_session'])){
			$this->userdata = $this->_unserialize($_COOKIE['ci_session']);
	//		$this->sess_update();
		}else{
			$this->sess_create();	
		}
		if(isset($this->userdata['ses_login'])){
		//	echo '0<br>';
		//	var_dump($this->userdata['ses_login']);
			$this->ses_login = $this->userdata['ses_login'];
		}else{
		//	echo 'Add new';
			$this->loadAnonymous();	
		}
		if(count($this->ses_login->role) == 0){
			$this->ses_login->role = $this->role__;	
		}
		
	}
	function sess_update()
	{
		// We only update the session every five minutes by default
		if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
		{
			return;
		}
		// Save the old session id so we know which record to
		// update in the database if we need it
		$old_sessid = $this->userdata['session_id'];
		$new_sessid = '';
		while (strlen($new_sessid) < 32)
		{
			$new_sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$new_sessid .= $this->getRealIpAddr();

		// Turn it into a hash
		$new_sessid = md5(uniqid($new_sessid, TRUE));

		// Update the session data in the session data array
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;

		// _set_cookie() will handle this for us if we aren't using database sessions
		// by pushing all userdata to the cookie.
		$cookie_data = NULL;

		// Write the cookie
		$this->_set_cookie($cookie_data);
	}
	function sess_create(){
		$ip = $this->getRealIpAddr();
		
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}
		$sessid .= $ip;
		// To make the session ID even more secure we'll combine it with the user's IP
		//$sessid .= $this->CI->input->ip_address();

		$this->userdata = array(
							'session_id'	=> md5(uniqid($sessid, TRUE)),
							'ip_address'	=> $ip,
							'user_agent'	=> substr(( ! isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'], 0, 120),
							'last_activity'	=> $this->now,
							'user_data'		=> ''
							);

		// Write the cookie
		$this->_set_cookie();
	}
	function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	function loadAnonymous()
	{
		//connect to database and find the category
		include(APPPATH.'config/database'.EXT);
	
		$conn = mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']);
		mysql_select_db($db['default']['database'], $conn);
		
		$sql = sprintf("select * from roles where rid = 1");
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		if(is_array($row) && count($row) > 0){
			$this->role__['rid'] = $row['rid'];
			$this->role__['name'] = $row['name'];
			$this->role__['rlink'] = $row['rlink'];
		}
		
		$objlogin = new objlogin();
		$objlogin->role = $this->role__;
		$this->ses_login = $objlogin;
		$this->set_userdata('ses_login', $objlogin);
		
		mysql_close($conn);	
	}
	function _get_time()
	{
		if (strtolower($this->time_reference) == 'gmt')
		{
			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
		}
		else
		{
			$time = time();
		}

		return $time;
	}
	function set_userdata($newdata = array(), $newval = '')
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}

		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$this->userdata[$key] = $val;
			}
		}

		$this->_set_cookie();
	}
	function _set_cookie($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}

		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);
		$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);

		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();

		// Set the cookie
		setcookie(
					$this->sess_cookie_name,
					$cookie_data,
					$expire,
					$this->cookie_path,
					$this->cookie_domain,
					$this->cookie_secure
				);
	}

}