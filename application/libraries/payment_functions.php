<?php
function getFirstWord($str){
	$result = "";
	for($i=0;$i<strlen($str);$i++)
		if($str[$i]!=" ")
			$result .= $str[$i];
		else
			break;
	return $result;
}
function savingDemoGeo(){
	$income = array();
	if(isset($_POST['income']) && is_array($_POST['income'])){
		$income = $_POST['income'];	
	}
	$Age = array();
	if(isset($_POST['age']) && is_array($_POST['age'])){
		$Age = $_POST['age'];	
	}
	$Ethnicity = array();
	if(isset($_POST['ethnicity']) && is_array($_POST['ethnicity'])){
		$Ethnicity = $_POST['ethnicity'];	
	}
	$geographicId = 0;
	$demographic_id = 0;
	if(count($income) > 0 || count($Age) > 0 || count($Ethnicity) > 0){
		$demographic_id = insert_Demographic($income,$Age,$Ethnicity);
	}
	if(isset($_POST['miles']) && is_numeric($_POST['miles']) && $_POST['miles'] > 0){
		$mile 	= $_POST['miles'];
		$arr_addr = array();
		if(isset($_POST['address_0']) && trim($_POST['address_0']) != ''){
			$arr_addr[] = trim($_POST['address_0']);	
		}
		if(isset($_POST["moreAddress"]) && is_array($_POST["moreAddress"]) && count($_POST["moreAddress"]) > 0){
			$arr_addr = array_merge($arr_addr, $_POST["moreAddress"]);	
		}
		$geographicId = insert_Geographic($mile);
		if(is_numeric($geographicId) && $geographicId > 0){
			if(is_array($arr_addr) && count($arr_addr) > 0){
				foreach ($arr_addr as $str_address){
					$str_address = trim($str_address);
					if($str_address != '' && $str_address != 'ZERO'){
						insertAddressSearch($geographicId, $str_address);
					}
				}
			}		
		}	
	}
	return array($geographicId, $demographic_id);	
}
function insertAddressSearch($id_geographic,$address){
	$arr_address = array(
		"id_geographic"	=> $id_geographic,
		"address"		=> escape($address)
	);
	return db_insert_array('ad_address',$arr_address);
}
function insert_Geographic($mile){
	$arr_mile = array(
		"mile"				=> $mile,
		'campaign_type'		=> 1
	);
	return db_insert_array('ad_geographic',$arr_mile);
}
function insert_Demographic($income,$Age,$Ethnicity){
	$income = (count($income) > 0)?implode("|", $income):'';
	$Age = (count($Age) > 0)?implode("|", $Age):'';
	$Ethnicity = (count($Ethnicity) > 0)?implode("|", $Ethnicity):'';
	$data = array(
		"median_age"			=> $Age,
		'income_per_household'	=> $income,
		'ethnicity'				=> $Ethnicity,
		'campaign_type'			=> 1
	);
	return db_insert_array('ad_demographic',$data);
}
function save_ad_orders($okey, $total, $r_ordernum, $r_tdate, $start_date, $end_date, $months){
	$uid = '-1';
	if(isset($_POST['uid']) && is_numeric($_POST['uid']) && $_POST['uid'] > 0){
		$uid = $_POST['uid'];	
	}elseif(isset($_SESSION['ses_login'])){
		$uid = $_SESSION['ses_login']->uid;
	}
	$arr_degeo = savingDemoGeo();
	$data = array(
		'okey' => $okey,
		'billing_name' => escape($_POST['billing_name']),
		'billing_address' => escape($_POST['billing_address']),
		'billing_city' => escape($_POST['billing_city']),
		'billing_state' => escape($_POST['billing_state']),
		'billing_zip' => escape($_POST['billing_zip']),
		'billing_country' => escape($_POST['billing_country']),
		'billing_phone' => escape($_POST['billing_phone']),
		'billing_email' => escape($_POST['billing_email']),
		'tax' => 0,
		'total_price' => $total,
		'uid' => $uid,
		'order_date' => date("Y-m-d h:i:s"),
		'r_ordernum' => $r_ordernum,
		'r_tdate' => $r_tdate,
		'payment_method' => isset($_POST['payment_method'])?$_POST['payment_method']:1,
		'card_type' => escape($_POST['card_type']),
		'card_number' => $_POST['card_number'],
		'cc_month' => (isset($_POST['cc_month']) && is_numeric($_POST['cc_month']))?$_POST['cc_month']:1,
		'cc_year' => (isset($_POST['cc_year']) && is_numeric($_POST['cc_year']))?$_POST['cc_year']:1970,
		'cc_Cvv' => $_POST['cc_Cvv'],
		'start_date' => $start_date,
		'end_date' => $end_date,
		'months' => $months,
		'id_geographic'	=> $arr_degeo[0],
		'id_demographic' => $arr_degeo[1]    
	);
	$oid = db_insert_array('ad_orders', $data);
	return $oid;
}
function save_ad_orders_items($okey,$products){
	for($i = 0; $i < count($products); $i++){
		$adkey = $products[$i]['adkey'];
		$re = db_query("select * from ad_items where adkey = '$adkey'");
		if($row = db_fetch_array($re)){
			$price = $current_cost = $row['current_cost'];
			if(!is_numeric($current_cost)) $current_cost = 0;
			$re_price = db_query("select product_markup.markup_percentage from product_markup join ad_items_markup on ad_items_markup.mkey = product_markup.mkey where product_markup.status = 1 and ad_items_markup.pkey = '$adkey'");
			if($row_price = db_fetch_array($re_price)){
				$markup_percentage = $row_price['markup_percentage'];
				if(!is_numeric($markup_percentage)) $markup_percentage = 0;
				$price += $current_cost * $markup_percentage / 100;	
			}
			$ad_orders_items = array(
				'okey' => $okey,
				'adkey' => $adkey,
				'price' => $price,
				'cost' => $current_cost,
				'duration' => $row['duration']
			);
			$oitemsID = db_insert_array("ad_orders_items", $ad_orders_items);
			if(is_numeric($oitemsID) && $oitemsID > 0){
				$locations = $products[$i]['locations'];
				if(count($locations) > 0){
					foreach($locations as $location){
						$ad_orders_locations = array(
							'oitemsID' => $oitemsID,
							'locationID' => $location['id']
						);
						db_insert_array("ad_orders_locations", $ad_orders_locations);	
					}	
				}	
			}			
		}	
	}
}
function mail_to_advertiser($okey){
	global $__email_to_get_order__, $__signature__;
	$mail_content = loadAjaxTemplate("ad_order_mails.htm");
	$re2 = db_query("select * from ad_orders where okey = '$okey'");
	if($row2 = db_fetch_array($re2)){
		$mail_content = str_replace("<!--date-->", date("m/d/Y", strtotime($row2['order_date'])), $mail_content);
		$mail_content = str_replace("<!--order_number-->", $okey, $mail_content);
		$mail_content = str_replace("<!--payment_method-->", $row2['card_type'], $mail_content);
		$mail_content = str_replace("<!--card_number-->", 'xxxxxxxxxxxx'.substr($row2['card_number'], strlen($row2['card_number'])-4, 4), $mail_content);
		$mail_content = str_replace("<!--billingName-->", $row2['billing_name'], $mail_content);
		$mail_content = str_replace("<!--billingAddress-->", $row2['billing_address'], $mail_content);
		$mail_content = str_replace("<!--billingCity-->", $row2['billing_city'].', '.$row2['billing_state'].' '.$row2['billing_zip'].', '.$row2['billing_country'], $mail_content);
		$mail_content = str_replace("<!--billingPhone-->", $row2['billing_phone'], $mail_content);
		$mail_content = str_replace("<!--billingEmail-->", $row2['billing_email'], $mail_content);
		$mail_content = str_replace("<!--Schedule-->", date("m/d/Y", strtotime($row2['start_date'])).' to '.date("m/d/Y", strtotime($row2['end_date'])), $mail_content);
		$mail_content = str_replace("<!--of_months-->", number_format($row2['months']), $mail_content);
		$Locations = 0;
	
		$str_products = '';
		$str_locations = '';
		$arr_1 = partitionString("<!--@start_product@-->", "<!--@end_product@-->", $mail_content);
		$arr_2 = partitionString("<!--startRows-->", "<!--endRows-->", $arr_1[2]);
		
		$re = db_query("select ad_orders_items.id,ad_orders_items.price,ad_orders_items.upload_ad,ad_orders_items.status_ad,ad_orders_items.duration,network_venue.images,network_venue.VenueTypeName,ad_items.adsize,ad_items.aspect_ratio,ad_items.adformat,ad_items.audio,ad_type.name as ad_type_name from ad_orders_items join ad_items join network_venue join ad_type on ad_orders_items.adkey = ad_items.adkey and ad_items.venuetype = network_venue.id and ad_items.adtype = ad_type.id where ad_orders_items.okey = '$okey' and ad_orders_items.order_type = 1");
		while($row = db_fetch_array($re)){
			$venue_img = '';
			$img = '';
			if($row['images'] != '' && $row['images'] != null){
				$images = unserialize($row['images']);
				if(isset($images[0]) && is_file('data/venues/'.$images[0])) $img = URL_server__().'/data/venues/'.$images[0];
				if(isset($images[3]) && is_file('data/venues/'.$images[3])) $venue_img = URL_server__().'/data/venues/'.$images[3];			
			}
			$product_row = $arr_1[1];
			$product_row = str_replace("@src@", $venue_img, $product_row);
			$product_row = str_replace("<!--venue_name-->", $row['VenueTypeName'], $product_row);
			$product_row = str_replace("<!--duration-->", $row['duration'], $product_row);
			$product_row = str_replace("<!--Type-->", $row['ad_type_name'], $product_row);
			$product_row = str_replace("<!--Size-->", $row['adsize'], $product_row);
			$product_row = str_replace("<!--Aspect_Ratio-->", $row['aspect_ratio'], $product_row);
			$product_row = str_replace("<!--Format-->", $row['adformat'], $product_row);
			$product_row = str_replace("<!--Audio-->", ($row['audio'] == 1)?'Yes':'No', $product_row);
			$str_products .= $product_row;
			$re3 = db_query("select venue_location.address,venue_location.city,venue_location.state,venue_location.zipcode,venue_location.country from ad_orders_locations join venue_location on venue_location.id = ad_orders_locations.locationID where ad_orders_locations.oitemsID = ".$row['id']);
			while($row3 = db_fetch_array($re3)){
				$Locations ++;
				$str_row = $arr_2[1];
				$str_row = str_replace("@src@", $img, $str_row);
				$str_row = str_replace("<!--@address@-->", '<b>'.$row['VenueTypeName'].'</b>&nbsp;&nbsp;|&nbsp;&nbsp;'.$row3['address'].'<br>'.$row3['city'].', '.$row3['state'].' '.$row3['zipcode'].', '.$row3['country'], $str_row);
				$str_row = str_replace("<!--price-->", number_format($row['price']*$row2['months'], 2), $str_row);
				$str_locations .= $str_row;	
			}
		}
		$mail_content = $arr_1[0].$str_products.$arr_2[0].$str_locations.$arr_2[2];
		$mail_content = str_replace("<!--Locations-->", number_format($Locations), $mail_content);
		$mail_content = str_replace("<!--suptotal-->", number_format($row2['total_price'], 2), $mail_content);	
		$mail_content = str_replace("<!--Total-->", number_format($row2['total_price'], 2), $mail_content);
		sendmail($row2['billing_email'], "Order from Bellavie Network.", $__email_to_get_order__, $__signature__, $mail_content);
		sendmail($__email_to_get_order__, "Customer's order.", $row2['billing_email'], $row2['billing_name'], $mail_content);				
	}	
}
function CalcCommission($okey){
	$re = db_query("select months from ad_orders where okey = '$okey'");
	if($row = db_fetch_array($re)){
		$re_price = db_query("select ad_orders_items.price,ad_orders_items.id,product_markup.id as mid,product_markup.mkey,product_markup.commission_charities,product_markup.commission_employees_bonus,product_markup.commission_affiliate from ad_orders_items join ad_items_markup join product_markup on ad_orders_items.adkey = ad_items_markup.pkey and ad_items_markup.mkey = product_markup.mkey where ad_orders_items.okey = '$okey' and ad_orders_items.order_type = 1");
		while($row_price = db_fetch_array($re_price)){
			$commission_charities = $row_price['commission_charities'];
			$commission_employees_bonus = $row_price['commission_employees_bonus'];
			$commission_affiliate = $row_price['commission_affiliate'];
			$mkey = $row_price['mkey'];
			$price = 0;
			$price_venue = round($row_price['price']*$row['months'], 2);
			
			$re3 = db_query("select venue_location.affiliateID from ad_orders_locations join venue_location on venue_location.id = ad_orders_locations.locationID where ad_orders_locations.oitemsID = ".$row_price['id']);
			while($row3 = db_fetch_array($re3)){
				$akey = db_result(db_query("select legal_business_id from tbaffiliates where id = ".$row3['affiliateID']));
				$data_commission = array(
					'rid' => 6,
					'akey' => $akey,
					'date_purchase' => date("Y-m-d H:i:s"),
					'okey' => $okey,
					'oitem' => $row_price['id'],
					'commission' => $commission_affiliate,
					'price' => $price_venue * $commission_affiliate / 100,
					'mkey' => $mkey
				);
				db_insert_array('ad_commission', $data_commission);
				$price += $price_venue;
			}
			if(is_numeric($commission_charities) && $commission_charities > 0){
				$data_commission = array(
					'rid' => 8,
					'date_purchase' => date("Y-m-d H:i:s"),
					'okey' => $okey,
					'oitem' => $row_price['id'],
					'commission' => $commission_charities,
					'price' => $price * $commission_charities / 100,
					'mkey' => $mkey
				);
				db_insert_array('ad_commission', $data_commission);				
			}
			if(is_numeric($commission_employees_bonus) && $commission_employees_bonus > 0){
				$data_commission = array(
					'rid' => 0,
					'date_purchase' => date("Y-m-d H:i:s"),
					'okey' => $okey,
					'oitem' => $row_price['id'],
					'commission' => $commission_charities,
					'price' => $price * $commission_employees_bonus / 100,
					'mkey' => $mkey
				);
				db_insert_array('ad_commission', $data_commission);				
			}
		}
	}
}