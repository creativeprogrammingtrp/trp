<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tracking_model extends CI_Model {
	
	var $trackingID = '';
	
	function SetTrackingID($tracking_id){
		$this->trackingID = $tracking_id;
	}
	function checkTrackingID(){
		$re = $this->db->query("select skey,shipping_method,destination_firstname,destination_lastname,destination_address,destination_city,destination_state,destination_zipcode,destination_country,ship_date,expected_delivery from shipments where tracking_number = '".$this->trackingID."'");
		$arr = array();
		if($row = $re->row_array()){
			$shipping_method = $row['shipping_method'];
			if($shipping_method == 0){
				$re_1 = $this->db->query("select * from packages where shipment_ID = ".$row['skey']);
				$packages = array();
				foreach($re_1->result_array() as $row_1){
					$id = $row_1['id'];
					$products = array();
					$re_2 = $this->db->query("select packages_items.qty,items.itm_model from packages_items join items on packages_items.product_id = items.itm_id where packages_items.package_id = ".$id);
					foreach($re_2->result_array() as $row_2){
						$products[count($products)] = $row_2['qty']." x ".$row_2['itm_model'];
					}
					$packages[count($packages)] = array('packages' => $row_1, 'products' => $products);	
				}
				$arr = array('trackinfo' => $row, 'trackpack' => $packages);
				return array('tracking' => $this->trackingID, 'type' => '0', 'data' => $arr);
			}else if($shipping_method == 1){
				$url = 'http://www.ups.com/WebTracking/track?loc=en_US&WT.svl=PriNav';
				return array('tracking' => $this->trackingID, 'type' => '1', 'data' => $url);
			}else if($shipping_method == 2){
				$url = 'https://tools.usps.com/go/TrackConfirmAction_input';
				return array('tracking' => $this->trackingID, 'type' => '1', 'data' => $url);
			}else if($shipping_method == 3){
				$url = 'http://www.fedex.com/Tracking';
				return array('tracking' => $this->trackingID, 'type' => '1', 'data' => $url);
			}
		}
		return array('tracking' => '', 'type' => '', 'data' => '');	
	}
}