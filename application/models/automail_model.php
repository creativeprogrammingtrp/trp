<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Automail_model extends CI_Model 
{
	public function &getMailConfig()
	{
		$mailconfig = array();
		$query = $this -> db ->get("mailconfig");
		foreach($query ->result_array() as $row)
		{
			$mailconfig[] = $row;	
		}
		return $mailconfig;
	}//end function getMailConfig	
	
	public function truncateMailConfig()
	{
		$this ->db ->truncate("mailconfig");
	}//end truncateMailConfig function
	public function updateMailConfig($data)
	{
		$this ->db ->insert('mailconfig', $data);
	}//end function updateMailConfig
	
}//end class Mailconfig