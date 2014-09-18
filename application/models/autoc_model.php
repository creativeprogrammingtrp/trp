<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autoc_model extends CI_Model {
	var $Minimum_purchased = 0;
	
	var $To_be_active = 0;
	var $units_active = 1;
	var $rang_active = 0;
	
	var $limit_time_purchase = 1;
	var $units_purchase = 1;
	var $rang_purchase = 0;
	
	var $date_purchase_to_be_actived = 1;
	
	var $date_apply = '';
	var $date_apply_int = 0;
	var $today = 0;
	
	var $arr_rep = array();
	var $arr_manufacturers = array();
	
	public function __construct(){
		parent::__construct();
		$this->loadSale_rep_setting();
		$this->load->library('general');
		$this->today = $this->lib->getTimeGMT();
		
		$this->loadRepList();
		$this->autorunRep();
		$this->autorunManufacturer();
	}
	
	function loadSale_rep_setting(){
		$last_general_settup = $this->general->getLastGeneralSetting();
		
		$this->Minimum_purchased = $last_general_settup['minimum_purchased'];

		$this->To_be_active = $last_general_settup['to_be_active'];
		$this->units_active = $last_general_settup['units_active'];
		$this->rang_active = $this->To_be_active * $this->units_active;
		
		$this->limit_time_purchase = $last_general_settup['limit_time_purchase'];
		$this->units_purchase = $last_general_settup['units_purchase'];
		$this->rang_purchase = $this->limit_time_purchase * $this->units_purchase;
		
		$time_purchase_actived = $last_general_settup['time_purchase_actived'];
		$units_time_purchase = $last_general_settup['units_time_purchase'];
		$this->date_purchase_to_be_actived = $time_purchase_actived * $units_time_purchase;
		
		$this->date_apply = $last_general_settup['date_apply'];
		if($this->date_apply != ''){
			$this->date_apply_int = strtotime($this->date_apply);
		}
	}
	
	function loadRepList(){
		if($this->date_apply != ''){
			$re2 = $this->db->query("select representatives.uid,representatives.purchase_active,representatives.author from representatives join users on representatives.uid = users.uid where users.status <> -1");
			if($re2->num_rows() > 0){
				foreach($re2->result_array() as $rows){
					$this->arr_rep[] = $rows;	
				}	
			}	
		}
	}
	
	function autorunRep(){
		if(count($this->arr_rep) > 0){
			$repObj = new repObj($this->date_apply_int, $this->rang_purchase, $this->rang_active, $this->today, $this->Minimum_purchased);
			$repObj->date_purchase_to_be_actived = $this->date_purchase_to_be_actived;
			foreach($this->arr_rep as $row2){
				$repObj->purchase_active = $row2['purchase_active'];
				$repObj->author = $row2['author'];
				$repObj->setUid($row2['uid']);
				$repObj->resetData();
				$repObj->getTime();
				
				$repObj->calCommission();
				$repObj->ActiveAccount();
			}	
		}
	}
	
	function autorunManufacturer(){
		$this->scanItemsManufacturers();
		$this->sendMailRestockingReminder();	
	}
	
	function sendMailRestockingReminder(){
		if(count($this->arr_manufacturers) > 0){
			foreach($this->arr_manufacturers as $manufacturer){
				if(isset($manufacturer['mails']) && is_array($manufacturer['mails']) && count($manufacturer['mails']) > 0){
					if(isset($manufacturer['items']) && is_array($manufacturer['items']) && count($manufacturer['items']) > 0){
						$items_list = '';
						$items_list .= '<table cellpadding="0" cellspacing="0" border="0">';
						$items_list .= '	<tr>';
						$items_list .= '		<td align="left" valign="middle" style="padding-top:5px; padding-bottom:5px"><b>Item</b></td>';
						$items_list .= '		<td align="right" valign="middle" style="padding-top:5px; padding-bottom:5px; padding-left:20px"><b>In Stock</b></td>';
						$items_list .= '	</tr>';
						foreach($manufacturer['items'] as $item){
							$items_list .= '	<tr>';
							$items_list .= '		<td align="left" valign="top" style="padding-top:5px; padding-bottom:5px"><b>'.$item['itm_name'].'</b><br /><i>'.$item['itm_model'].'</i></td>';
							$items_list .= '		<td align="right" valign="top" style="padding-top:5px; padding-bottom:5px; padding-left:20px"><b>'.number_format($item['inventories']).'</b></td>';
							$items_list .= '	</tr>';		
						}
						$items_list .= '</table>';
						$variables_ = array(
							'!full_name' => $manufacturer['legal_business_name'],
							'!items_list' => $items_list
						);
						foreach($manufacturer['mails'] as $mail){
							$this->lib->sendmailtype($mail, __restocking_reminder__, $variables_);			
						}		
					}
						
				}	
			}	
		}	
	}
	
	function scanItemsManufacturers(){
		$sql = "select itm_name,itm_model,minimum_in_stock,uid,inventories from items where itm_status = 1";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				if((int)$row['inventories'] > (int)$row['minimum_in_stock']) continue;
				
				$uid = $row['uid'];
				unset($row['uid']);
				
				$checkExist = false;
				for($i = 0; $i < count($this->arr_manufacturers); $i++){
					if($this->arr_manufacturers[$i]['uid'] == $uid){
						$this->arr_manufacturers[$i]['items'][] = $row;
						$checkExist = true;
						break;	
					}	
				}
				if($checkExist == false){
					
					$this->arr_manufacturers[] = array(
						'uid'=> $uid,
						'mails' => $this->loadEmailManuList($uid),
						'items' => array($row)
					);	
				}	
			}	
		}	
	}
	
	function loadEmailManuList($uid){
		$arr_mail = array();
		$query = $this->db->query("select manufacturers_restocking_reminder.email_list,manufacturers.legal_business_name from manufacturers_restocking_reminder join manufacturers on  manufacturers.mid = manufacturers_restocking_reminder.mid where manufacturers.uid = '$uid'");
		if($query->num_rows() > 0){
			$row = $query->row_array();
			if($row['email_list'] != '' && $row['email_list'] != null){
				$arr_mail = explode("\n", $row['email_list']);	
			}	
		}
		return $arr_mail;	
	}
	
}

class repObj extends CI_Model{
	var $uid = 0;
	var $from_date = 0;
	var $to_date = 0;
	
	var $from_date_active = 0;
	
	var $Minimum_purchased = 0;
	var $date_apply_int = 0;
	var $rang_purchase = 0; 
	var $rang_active = 0;
	var $today = 0;
	
	var $rang_notify_inactive = 0;
	var $user = array();
	var $full_name = '';
	
	var $date_purchase_to_be_actived = 1;
	var $purchase_active = 0;
	var $author = 1;
	
	public function __construct($date_apply_int, $rang_purchase, $rang_active, $today, $Minimum_purchased){
		parent::__construct();
		$this->date_apply_int = $date_apply_int;
		$this->rang_purchase = $rang_purchase;
		$this->rang_active = $rang_active;
		$this->today = $today;
		$this->rang_notify_inactive = 2*24*60*60;
		$this->Minimum_purchased = $Minimum_purchased;
	}
	
	function loadUser(){
		$re = $this->db->query("select firstname,middlename,lastname,mail,created from users where uid = ".$this->uid);
		if($re->num_rows() > 0){
			$this->user = $re->row_array();
			$this->full_name = $this->user['firstname'].' '.$this->user['middlename'].' '.$this->user['lastname'];	
		}	
	}
	
	function setUid($uid){
		$this->uid = $uid;
		$this->loadUser();	
	}
	
	function resetData(){
		$this->from_date = 0;
		$this->from_date_active = 0;
		$this->to_date = 0;	
	}
	
	function getTime(){
		$this->from_date = $this->from_date_active = $this->date_apply_int;
		$this->to_date = $this->from_date + $this->rang_purchase*24*60*60 - 1;	
	}
	
	function ActiveAccount(){
		if($this->purchase_active == 0){
			if($this->author > 1){
				$rang = $this->today - $this->user['created'];
				$rang_purchase_active = $this->date_purchase_to_be_actived * 24 * 60 * 60 - 1;
				if($rang >= $rang_purchase_active){
					$this->db->update('users', array('status'=>-1), "uid = ".$this->uid);
					$this->mail_del_account();
					echo 'Delete Account : '.$this->full_name.' - '.$this->user['mail'].'<br>';	
				}
			}
		}else{
			if($this->rang_active > 0){
				$last_date_order = $this->getLastDateOrder();
				$todate_active = $last_date_order + $this->rang_active*24*60*60 - 1;
				if($todate_active < $this->today){
					if($this->author > 1){
						$this->db->update('users', array('status'=>-1), "uid = ".$this->uid);
						$this->mail_del_account();
						echo 'Delete Account : '.$this->full_name.' - '.$this->user['mail'].'<br>';
					}
				}elseif($todate_active > $this->today){
					$limit_time = $todate_active - $this->today;
					if($limit_time <= $this->rang_notify_inactive){
						$this->mail_notify_inactive();
						echo 'Notify Inactive Account : '.$this->full_name.' - '.$this->user['mail'].'<br>';	
					}
					$this->monthly_reminder_full_fill();	
				}
			}
		}
	}
	
	function monthly_reminder_full_fill(){
		$from_date_str = gmdate("Y-m-d H:i:s", $this->from_date);
		$to_date_str = gmdate("Y-m-d H:i:s", $this->to_date);
		$Purchase = $this->getPurchaseMonthly($from_date_str, $to_date_str);
		if($Purchase < $this->Minimum_purchased){
			$variables_ = array(
				'!full_name' => $this->full_name,
				'!Minimum_purchased' => $this->Minimum_purchased
			);
			$this->lib->sendmailtype($this->user['mail'], __monthly_reminder__, $variables_);	
		}
	}
	
	function mail_del_account(){
		$variables_ = array(
			'!username' => $this->full_name
		);
		$this->lib->sendmailtype($this->user['mail'], __account_deleted__, $variables_);			
	}
	
	function mail_notify_inactive(){
		$this->lib->sendmailtype($this->user['mail'], __notify_inactive_member__, array('!full_name'=>$this->full_name));	
	}
	
	function getLastDateOrder(){
		$order_date = 0;
		$re = $this->db->query("select order_date from orders where user_id = ".$this->uid." and status <> 4 order by order_date DESC limit 0,1");
		if($re->num_rows() > 0){
			$row = $re->row_array();
			$order_date = strtotime($row['order_date']);	
		}
		return $order_date;	
	}
	
	function calCommission(){
		while($this->to_date < $this->today){
			$this->updateCommision();
			$this->from_date = $this->to_date+1;
			$this->to_date = $this->from_date + $this->rang_purchase*24*60*60 - 1;
		}	
	}
	
	function updateCommision(){
		$from_date_str = gmdate("Y-m-d H:i:s", $this->from_date);
		$to_date_str = gmdate("Y-m-d H:i:s", $this->to_date);
		$Purchase = $this->getPurchaseMonthly($from_date_str, $to_date_str);
		if($Purchase >= $this->Minimum_purchased){
			$this->db->update('commission_monthly_items', array('status'=>1), "status = 0 and uid = ".$this->uid." and purchase_date >= '".$from_date_str."' and purchase_date <= '".$to_date_str."'");
		}else{
			$this->db->update('commission_monthly_items', array('status'=>-1), "status = 0 and uid = ".$this->uid." and purchase_date >= '".$from_date_str."' and purchase_date <= '".$to_date_str."'");	
		}
	}
	
	function getPurchaseMonthly($from_date, $to_date){
		$Purchase = 0;
		$re = $this->db->query("select oid from commission_monthly where uid = ".$this->uid." and date_add >= '".$from_date."' and date_add <= '".$to_date."'");
		if($re->num_rows() > 0){
			foreach($re->result_array() as $row){
				$Purchase += $this->orderlib->__getSubTotalOrder__($row['oid']);
			}	
		}
		return $Purchase;
	}
}