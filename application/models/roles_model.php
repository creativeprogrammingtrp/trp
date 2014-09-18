<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Roles_model extends CI_Model 
{
	public function loadDataRoles()
	{
		$this ->db ->order_by("rid","asc");
		$query = $this ->db ->get("roles");
		return $query ->result_array();
	}//end loadDataRoles function
	
	public function saveRole(){
		if($this ->input ->post("rolename"))
		{
			$data = array(
				'name' => $this ->lib ->escape($this ->input ->post("rolename")),
				'rlink' => $this ->lib ->escape($this ->input ->post("rolelink"))
			);
			$this ->db ->insert("roles", $data);		
		}
		return $this ->loadDataRoles();
	}//end saveRole function
	
	public function updateRole()
	{
		if(!is_numeric($this ->input ->post("rid"))) return $this ->loadDataRoles();
		if($this ->input ->post("rname"))
		{
			$this ->db ->where("rid",$this ->input ->post("rid"));
			$this ->db ->update("roles",array("name" =>$this ->input ->post("rname")));		
		}
		if($this ->input ->post("rlink"))
		{
			$this ->db ->where("rid",$this ->input ->post("rid"));
			$this ->db ->update("roles",array("rlink" =>$this ->input ->post("rlink")));		
		}
		return $this ->loadDataRoles();
	}//end updateRoles function
	
	public function deleteRole()
	{
		if(is_numeric($this ->input ->post("rid")) && $this ->input ->post("rid") > 3)
		{
			$this ->db ->where("rid",$this ->input ->post("rid"));
			$this ->db ->delete("roles");
		}
		return $this ->loadDataRoles();
	}//end deleteRole function
}//end Roles_model class