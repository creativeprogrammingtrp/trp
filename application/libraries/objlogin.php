<?php
if(!class_exists('objlogin')){
	class Objlogin{
		var $uid = 0;
		var $ukey = '';			
		var $name = '';	
		var $pass = '';			
		var $mail = '';
		var $repid = '';
		var $firstname = '';
		var $lastname = '';
		var $phone = '';
		var $mobile = '';
		var $address = '';
		var $city = '';        	
		var $state = '';        	
		var $zipcode = '';
		var $country = '';
		var $created = 0;
		var $access = 0;
		var $login = 0;
		var $status = 1;
		var $picture = '';
		var $permission = array();
		var $role = array();
		var $data = '';
		var $parentUid = '';
		var $efin = '';
        var $ptin = '';
        var $isemployee = 0;
	}
}