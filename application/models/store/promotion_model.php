<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Promotion_model extends CI_Model
{
	public function loadObj()
	{
		$arrUsers = array();
		$modify = 'no';
		$del = 'no';
		if($this->author->isAccessPerm("promotion","edit")){
			$modify = 'yes';	
		}
		if($this->author->isAccessPerm("promotion","delete")){
			$del = 'yes';	
		}
		$key_word_sql = '';
		if(isset($_POST['key_word']) && trim($_POST['key_word']) != ''){
			$key_word = $this ->lib ->replaceSpecChar($_POST['key_word']);
			$key_word = $this ->lib ->escape($key_word);
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0){
				foreach($arr_key as $key){
					if($key != ''){
						$key_word_sql .= " and (";
						$key_word_sql .= " promo_code like '%$key%'";
						$key_word_sql .= " or promo_name like '%$key%'";
						$key_word_sql .= " or description like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
	
		if(isset($_POST['status_promo']) && $_POST['status_promo'] != ''){
			$status_user_sql = " and status = '".$_POST['status_promo']."' ";		
		}else{
			$status_user_sql = " and status <> -1 ";	
		}
	
		$re = $this ->db ->query("select * from promotions where 1=1 $status_user_sql $key_word_sql order by date_modify DESC");
		foreach($re->result_array() as $row){
			$row['date_create_str'] = date("m/d/Y", $row['date_create']);
			$row['date_modify_str'] = date("m/d/Y", $row['date_modify']);
			
			$row['start_date_str'] = 'None';
			if($row['start_date'] != '' && $row['start_date'] != null) $row['start_date_str'] = date("m/d/Y", $row['start_date']);
			$row['end_date_str'] = 'None';
			if($row['end_date'] != '' && $row['end_date'] != null) $row['end_date_str'] = date("m/d/Y", $row['end_date']);
			
			$row['del'] = $del;
			$row['modify'] = $modify;
			
			$orders_promotions = array();
			$re_1 = $this ->db ->query("select * from orders_promotions where promo_key = '".$row['promo_code']."'");
			foreach($re_1->result_array() as $row_1){
				$row_1['product_name']='';
				$row_1['itm_name'] = '';
				$temp = $this ->db ->query("select itm_name from items where itm_key = '".$row_1['product_key']."'");
				if($temp->num_rows() >0) {
					$temp_row = $temp->row_array();
					$row_1['product_name'] = $temp_row['itm_name'];
				}
				$temp = $this ->db ->query("select itm_name from items where itm_key = '".$row_1['itm_key']."'");
				if($temp->num_rows() >0) {
					$temp_row = $temp->row_array();
					$row_1['itm_name'] = $temp_row['itm_name'];
				}
				
				$row_1['date_purchase_str'] = 'None';
				if($row_1['date_purchase'] != '' && $row_1['date_purchase'] != null) $row_1['date_purchase_str'] = date("m/d/Y", $row_1['date_purchase']);
				$orders_promotions[] = $row_1;	
			}
			$row['orders_promotions'] = $orders_promotions;
					
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end loadObj function
	
	public function loadPromoType($value = '')
	{
		$pro_arr = array();
		$re = $this ->db->query("select * from promotion_type where status <> -1 order by id ASC");
		foreach($re->result_array() as $row){
			$checked = '';
			if($value == $row['id']) $checked = 'checked="checked"';
			$pro_arr[] = array(
				'promo_type_name' =>$row['name'],
				'id' =>$row['id'],
				'checked' =>$checked
			);
		}
		return $pro_arr;
	}//loadPromoType function
	public function checkPromoCode()
	{
		$error = '';
		if(isset($_POST['promo_code']) && $_POST['promo_code'] != ''){
			$re = $this ->db->query("select id from promotions where promo_code = '".$_POST['promo_code']."'");
			if($re->num_rows()>0){
				$error = 'Promo code already exists in the database. Please choose another.';	
			}
		}	
		return array('error'=>$error);
	}//end checkPromoCode function
	
	public function saveObj()
	{
		$error = '';
		
		$key = (isset($_POST['promo_code']) && trim($_POST['promo_code']) != '')?trim($_POST['promo_code']):'';
		if($key == '' || (isset($_POST['auto_gencode']) && $_POST['auto_gencode'] == 1)){
			$key = $this ->lib ->GeneralRandomReferralCode(10);
			$re = $this ->db->query("select id from promotions where promo_code = '$key'");
			while($re ->num_rows() >0){
				$key = $this ->lib ->GeneralRandomReferralCode(10);
				$re = $this ->db->query("select id from promotions where promo_code = '$key'");
			}		
		}		
		$start_date = (isset($_POST['start_date']) && trim($_POST['start_date']) != '')?strtotime(trim($_POST['start_date'])):0;
		$end_date = (isset($_POST['end_date']) && trim($_POST['end_date']) != '')?strtotime(trim($_POST['end_date'])):0;
		
		$minqty = (isset($_POST['minqty']) && is_numeric($_POST['minqty']) && $_POST['minqty'] > 0)?$_POST['minqty']:1;
			
		$datas = array(
			'uid' => $this ->author ->objlogin->uid,
			'auto_gencode' => $_POST['auto_gencode'],
			'promo_code' => $key,
			'promo_name' => $this ->lib ->escape($_POST['promo_name']),
			'description' => $this ->lib ->escape($_POST['description']),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'subtotal' => $_POST['subtotal'],
			'minqty' => $minqty,
			'date_create' => time(),
			'date_modify' => time()
		);
		$this ->db ->insert('promotions', $datas);
		$promo_id = $this ->db ->insert_id();
		if(!is_numeric($promo_id) || $promo_id <= 0){
			$error = _error_cannot_insert_db_;	
		}
			
		if($error == ''){
			if(isset($_POST['promotion_type']) && is_array($_POST['promotion_type']) && count($_POST['promotion_type']) > 0){
				foreach($_POST['promotion_type'] as $promo_type){	//2
					$data_2 = array(
						'promo_type' => $promo_type,
						'promo_id' => $promo_id,
					);
					$this ->db ->insert('promotions_group', $data_2);
					$promo_group = $this ->db ->insert_id();
					if(!is_numeric($promo_group) || $promo_group <= 0){
						$error = 'Can not insert promotions_group';	
					}
					if($error == ''){
						switch($promo_type){
							case 1:
								if(isset($_POST['product_discount_select']) && is_array($_POST['product_discount_select']) && count($_POST['product_discount_select']) > 0){
									foreach($_POST['product_discount_select'] as $item){
										$discount = (isset($item['discount']) && is_numeric($item['discount']))?$item['discount']:0;
										$discount_type = (isset($item['discount_type']) && is_numeric($item['discount_type']))?$item['discount_type']:0;
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value'],
											'discount' => $discount,
											'discount_type' => $discount_type
										);
										$this ->db ->insert('promotions_products', $date_3);
										$promotions_products_id = $this ->db ->insert_id();
										if(!is_numeric($promotions_products_id) || $promotions_products_id <= 0){
											$error = 'Can not insert promotions_products';	
										}	
									}	
								}
								break;
							case 2:
								if(isset($_POST['free_product_select']) && is_array($_POST['free_product_select']) && count($_POST['free_product_select']) > 0){
									foreach($_POST['free_product_select'] as $item){
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value'],
											'freeqty' => $item['freeqty']
										);
										$this ->db ->insert('promotions_products', $date_3);
										$promotions_products_id = $this ->db ->insert_id();
										if(!is_numeric($promotions_products_id) || $promotions_products_id <= 0){
											$error = 'Can not insert promotions_products';	
										}	
									}	
								}
								break;
							case 3:
								if(isset($_POST['shipping_discount_select']) && is_array($_POST['shipping_discount_select']) && count($_POST['shipping_discount_select']) > 0){
									foreach($_POST['shipping_discount_select'] as $item){
										$discount = (isset($item['discount']) && is_numeric($item['discount']))?$item['discount']:0;
										$discount_type = (isset($item['discount_type']) && is_numeric($item['discount_type']))?$item['discount_type']:0;
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value'],
											'discount' => $discount,
											'discount_type' => $discount_type
										);
										$this ->db ->insert('promotions_products', $date_3);
										$promotions_products_id = $this ->db ->insert_id();
										if(!is_numeric($promotions_products_id) || $promotions_products_id <= 0){
											$error = 'Can not insert promotions_products';	
										}	
									}	
								}
								break;
							case 4:
								if(isset($_POST['free_shippings_select']) && is_array($_POST['free_shippings_select']) && count($_POST['free_shippings_select']) > 0){
									if(isset($_POST['countries_promotions']) && is_array($_POST['countries_promotions']) && count($_POST['countries_promotions']) > 0){
										foreach($_POST['countries_promotions'] as $country_promo){
											if($country_promo['country_value'] != ''){
												$promotions_products_countries = array(
													'promo_id' => $promo_group,
													'country_code' => $country_promo['country_value']
												);
												
												$this->db->insert('promotions_products_countries', $promotions_products_countries);
												$country_id = $this ->db ->insert_id();
												if(is_numeric($country_id) && $country_id > 0){
													if(isset($country_promo['state_value']) && is_array($country_promo['state_value']) && count($country_promo['state_value']) > 0){
														foreach($country_promo['state_value'] as $state_promo){
															if($state_promo != ''){
																$promotions_products_states = array(
																	'country_id' => $country_id,
																	'state_code' => $state_promo
																);
																$this ->db->insert('promotions_products_states', $promotions_products_states);
															}
														}	
													}	
												}
											}
												
										}	
									}
									foreach($_POST['free_shippings_select'] as $item){
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value']
										);
										$promotions_products_id = $this ->db->insert('promotions_products', $date_3);	
									}	
								}
								break;		
						}		
					}	
				}	//2
			}
		}
		return array('error' => $error);
	}//end saveObj function
	
	public function loadValue($pkey)
	{
		$key = '';
		$promo_code = '';
		$promo_name = '';
		$description = '';
		$start_date = '';
		$end_date = '';
		$minqty = '';
		$promo_id = 0;
		$promo_countries = array();
		
		if(!empty($pkey)){
			$key = $pkey;
			$re = $this ->db->query("select * from promotions where promo_code = '$key'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				$promo_id = $row['id'];
				$promo_code = $row['promo_code'];
				$promo_name = $row['promo_name'];
				$description = $row['description'];
				$start_date = ($row['start_date'] != null && $row['start_date'] != '')?date("m/d/Y", $row['start_date']):'';
				$end_date = ($row['end_date'] != null && $row['end_date'] != '')?date("m/d/Y", $row['end_date']):'';
				$minqty = $row['minqty'];
				
				$re2 = $this ->db->query("select id,promo_type from promotions_group where promo_id = ".$promo_id);
				foreach($re2 ->result_array() as $row2){
					if($row2['promo_type'] == 4){
						$re3 = $this ->db->query("select * from promotions_products_countries where promo_id = ".$row2['id']);
						foreach($re3 ->result_array() as $row3){
							$country = array('code'=>$row3['country_code'],'states'=>array());
							$re4 = $this ->db->query("select * from promotions_products_states where country_id = ".$row3['id']);
							foreach($re4 ->result_array() as $row4){
								$country['states'][] = $row4['state_code'];	
							}
							$promo_countries[] = $country;
						}
					}	
				}		
			}
		}
		return array(
			'promo_code' =>$promo_code,
			'promo_name' =>$promo_name,
			'description' =>$description,
			'start_date' =>$start_date,
			'end_date' =>$end_date,
			'minqty' =>$minqty,
			'key' =>$key,
			'loadPromoCountries' =>"dataCountryPromos = ".json_encode($promo_countries).";",
			'loadPromoType' =>$this ->edit_loadPromoType($promo_id)
		);
	}//end loadValue function
	
	public function edit_loadPromoType($promo_id)
	{
		$arr_type = array();
		$arr_group = array();
		$re = $this ->db->query("select * from promotions_group where promo_id = '$promo_id' and status = 1");
		foreach($re->result_array() as $row){
			$arr_type[] = $row['promo_type'];	
		}
		
		$re = $this ->db->query("select * from promotion_type where status <> -1 order by id ASC");
		foreach($re->result_array() as $row){
			$checked = '';
			if(in_array($row['id'], $arr_type)) $checked = 'checked="checked"';
			
			$arr_group[] = array(
				'promo_type_name' =>$row['name'],
				'id' =>$row['id'],
				'checked' =>$checked
			);
		}
		return $arr_group;
	}//end edit_loadPromoType function
	
	public function loadDatasProducts()
	{
		$arr_promo_products = array();
		
		if(isset($_POST['promo_code']) && $_POST['promo_code'] != ''){
			$re = $this ->db->query("select * from promotions where promo_code = '".$_POST['promo_code']."'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				$re_1 = $this ->db->query("select * from promotions_group where promo_id = '".$row['id']."' and status = 1");
				foreach($re_1 ->result_array() as $row_1){
					$promo_type = $row_1['promo_type'];
					$re_2 = $this ->db->query("select * from promotions_products where promo_group = '".$row_1['id']."' and status = 1");
					foreach($re_2 ->result_array() as $row_2){
						$row_2['promo_type'] = $promo_type;
						$arr_promo_products[] = $row_2;	
					}
				}
			}
		}
		return array('productsAvailable'=>$this ->lib ->__loadProductsAvailable__(), 'promotions_products'=>$arr_promo_products);
	}//end loadDatasProducts function
	
	public function edit_saveObj()
	{
		$error = '';
		
		$key = (isset($_POST['promo_code']) && trim($_POST['promo_code']) != '')?trim($_POST['promo_code']):'';
		
		$start_date = (isset($_POST['start_date']) && trim($_POST['start_date']) != '')?strtotime(trim($_POST['start_date'])):0;
		$end_date = (isset($_POST['end_date']) && trim($_POST['end_date']) != '')?strtotime(trim($_POST['end_date'])):0;
		
		$minqty = (isset($_POST['minqty']) && is_numeric($_POST['minqty']) && $_POST['minqty'] > 0)?$_POST['minqty']:1;
			
		$datas = array(
			'promo_name' => $this ->lib ->escape($_POST['promo_name']),
			'description' => $this ->lib ->escape($_POST['description']),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'subtotal' => $_POST['subtotal'],
			'minqty' => $minqty,
			'date_modify' => time()
		);
		$promo_id = 0;
		$re = $this ->db ->query("select id from promotions where promo_code = '$key'");
		if($re->num_rows() >0){
			$row = $re->row_array();
			$promo_id = $row['id'];	
			$this ->db->update('promotions', $datas, "promo_code = '$key'");
			
			$re_1 = $this ->db->query("select id,promo_type from promotions_group where promo_id = '$promo_id' and status = 1");
			foreach($re_1 ->result_array() as $row_1){
				$this ->db->query("delete from promotions_products where promo_group = ".$row_1['id']);
				if($row_1['promo_type'] == 4){
					$re_2 = $this ->db->query("select id from promotions_products_countries where promo_id = ".$row_1['id']);
					foreach($re_2 ->result_array() as $row_2){
						$this ->db->query("delete from promotions_products_states where country_id = ".$row_2['id']);	
					}
					$this ->db->query("delete from promotions_products_countries where promo_id = ".$row_1['id']);		
				}
			}
			$this ->db->query("delete from promotions_group where promo_id = $promo_id");
		}
		if(!is_numeric($promo_id) || $promo_id <= 0){
			$error = _error_cannot_insert_db_;	
		}
			
		if($error == ''){
			if(isset($_POST['promotion_type']) && is_array($_POST['promotion_type']) && count($_POST['promotion_type']) > 0){
				foreach($_POST['promotion_type'] as $promo_type){	//2
					$data_2 = array(
						'promo_type' => $promo_type,
						'promo_id' => $promo_id,
					);
					$this ->db ->insert('promotions_group', $data_2);
					$promo_group = $this ->db->insert_id();
					if(!is_numeric($promo_group) || $promo_group <= 0){
						$error = 'Can not insert promotions_group';	
					}
					if($error == ''){
						switch($promo_type){
							case 1:
								if(isset($_POST['product_discount_select']) && is_array($_POST['product_discount_select']) && count($_POST['product_discount_select']) > 0){
									foreach($_POST['product_discount_select'] as $item){
										$discount = (isset($item['discount']) && is_numeric($item['discount']))?$item['discount']:0;
										$discount_type = (isset($item['discount_type']) && is_numeric($item['discount_type']))?$item['discount_type']:0;
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value'],
											'discount' => $discount,
											'discount_type' => $discount_type
										);
										$this ->db ->insert('promotions_products', $date_3);
										$promotions_products_id = $this ->db->insert_id();
										if(!is_numeric($promotions_products_id) || $promotions_products_id <= 0){
											$error = 'Can not insert promotions_products';	
										}	
									}	
								}
								break;
							case 2:
								if(isset($_POST['free_product_select']) && is_array($_POST['free_product_select']) && count($_POST['free_product_select']) > 0){
									foreach($_POST['free_product_select'] as $item){
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value'],
											'freeqty' => $item['freeqty']
										);
										$this ->db ->insert('promotions_products', $date_3);
										$promotions_products_id = $this ->db->insert_id();
										if(!is_numeric($promotions_products_id) || $promotions_products_id <= 0){
											$error = 'Can not insert promotions_products';	
										}	
									}	
								}
								break;
							case 3:
								if(isset($_POST['shipping_discount_select']) && is_array($_POST['shipping_discount_select']) && count($_POST['shipping_discount_select']) > 0){
									foreach($_POST['shipping_discount_select'] as $item){
										$discount = (isset($item['discount']) && is_numeric($item['discount']))?$item['discount']:0;
										$discount_type = (isset($item['discount_type']) && is_numeric($item['discount_type']))?$item['discount_type']:0;
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value'],
											'discount' => $discount,
											'discount_type' => $discount_type
										);
										$this ->db ->insert('promotions_products', $date_3);
										$promotions_products_id = $this ->db->insert_id();
										if(!is_numeric($promotions_products_id) || $promotions_products_id <= 0){
											$error = 'Can not insert promotions_products';	
										}	
									}	
								}
								break;
							case 4:
								if(isset($_POST['free_shippings_select']) && is_array($_POST['free_shippings_select']) && count($_POST['free_shippings_select']) > 0){
									if(isset($_POST['countries_promotions']) && is_array($_POST['countries_promotions']) && count($_POST['countries_promotions']) > 0){
										foreach($_POST['countries_promotions'] as $country_promo){
											if($country_promo['country_value'] != ''){
												$promotions_products_countries = array(
													'promo_id' => $promo_group,
													'country_code' => $country_promo['country_value']
												);
												$this ->db ->insert('promotions_products_countries', $promotions_products_countries);
												$country_id = $this ->db->insert_id();
												if(is_numeric($country_id) && $country_id > 0){
													if(isset($country_promo['state_value']) && is_array($country_promo['state_value']) && count($country_promo['state_value']) > 0){
														foreach($country_promo['state_value'] as $state_promo){
															if($state_promo != ''){
																$promotions_products_states = array(
																	'country_id' => $country_id,
																	'state_code' => $state_promo
																);
																$this ->db ->insert('promotions_products_states', $promotions_products_states);
															}
														}	
													}	
												}
											}
												
										}	
									}
									foreach($_POST['free_shippings_select'] as $item){
										$date_3 = array(
											'promo_group' => $promo_group,
											'item_key' => $item['value']
										);
										$this ->db ->insert('promotions_products', $date_3);
										$promotions_products_id = $this ->db->insert_id();
										if(!is_numeric($promotions_products_id) || $promotions_products_id <= 0){
											$error = 'Can not insert promotions_products';	
										}	
									}	
								}
								break;		
						}		
					}	
				}	//2
			}
		}
		return array('error' => $error);
	}//edit_saveObj function
	
	public function delete_obj()
	{
		if(!empty($_POST['key'])){
			$re = $this ->db ->query("select id from promotions where promo_code = '".$_POST['key']."'");
			if($re->num_rows() >0){
				$row = $re->row_array();
				$re_1 = $this ->db ->query("select id from promotions_group where promo_id = ".$row['id']);
				foreach($re_1 ->result_array() as $row_1){
					$this ->db ->update("promotions_products", array('status'=> -1), "promo_group = ".$row_1['id']);		
				}
				$this ->db ->update("promotions_group", array('status'=> -1), "promo_id = ".$row['id']);	
				$this ->db ->update("promotions", array('status'=> -1), "promo_code = '".$_POST['key']."'");		
			}	
		}
		return $this ->loadObj();
	}//delete_obj function
	
	public function view_loadData()
	{
		$return_arr = array();
		$re = $this ->db ->query("select * from promotions WHERE status <> -1 order by date_modify DESC");
		foreach($re->result_array() as $row){
			$id = $row['id'];
			
			$promotions_group = '';
			$re_2 = $this ->db ->query("select * from promotions_group where promo_id = $id and status = 1 order by promo_type ASC");
			foreach($re_2->result_array() as $row_2){
				$promo_type = $row_2['promo_type'];
				$promo_group = $row_2['id'];
				
				$arr_promotions_products = array();
				$sql_ = "select promotions_products.discount,promotions_products.discount_type,promotions_products.freeqty,promotions_products.item_key as itm_key from promotions_products where promotions_products.promo_group = $promo_group and promotions_products.status = 1 and promotions_products.item_key = '"._same_product_."'";
				$re_3 = $this ->db ->query($sql_);	
				foreach($re_3->result_array() as $row_3){
					$arr_promotions_products[] = $row_3;	
				}
				$sql_ = "select promotions_products.discount,promotions_products.discount_type,promotions_products.freeqty,items.itm_name,items.itm_id,items.itm_key,items.itm_model from promotions_products join items on items.itm_key = promotions_products.item_key where promotions_products.promo_group = $promo_group and promotions_products.status = 1 and items.itm_status = 1";
				$re_3 = $this ->db ->query($sql_);	
				foreach($re_3->result_array() as $row_3){
					$arr_file = $this ->lib ->__loadFileProduct__($row_3['itm_id'], 'thumb');
					$row_3['file'] = $arr_file['file'];
					$arr_promotions_products[] = $row_3;	
				}
				if(count($arr_promotions_products) > 0){
					switch($promo_type){
						case 1:
							$promotions_products = '';
							foreach($arr_promotions_products as $row_3){
								$discount = '';
								if($row_3['discount_type'] == 0){
									$discount = number_format($row_3['discount']).'%';	
								}else{
									$discount = '$'.number_format($row_3['discount'], 2);	
								}
								if($row_3['itm_key'] == _same_product_){
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px;">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top">';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden;">';
									$promotions_products .= '<span style="font-weight:bold; color:#961F5F">&radic;&nbsp;&nbsp;The products in the promotion.</span>';
									$promotions_products .= '</div>';
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px; padding-left:100px;">';
									$promotions_products .= '<b>Discount:</b> '.$discount;
									$promotions_products .= '</div>';
									
									$promotions_products .= '</td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';
								}else{
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px; margin-top:10px; border-top:1px dashed #d2d2d2">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top"><img src="shopping/data/img/thumb/'.$row_3['file'].'" border="0" width="90px" class="img_box_radi" /></td>';
									$promotions_products .= '		<td align="left" valign="top" style="padding-left:10px">';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden;">';
									$promotions_products .=  '<a href="'.$this ->system->cleanUrl().'product/edit&itemid='.$row_3['itm_key'].'" style="font-weight:bold">'.$row_3['itm_name'].'</a>'."<br><b>Model: </b>".$row_3['itm_model'];
									$promotions_products .= '</div>';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px;">';
									$promotions_products .= '<b>Discount:</b> '.$discount;
									$promotions_products .= '</div>';
									
									$promotions_products .= '</td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';
								}
							}
							if($promotions_products != ''){
								$promotions_group .= '<fieldset style="margin-top:10px; padding-top:10px; border:1px solid #d2d2d2;">';
								$promotions_group .= '	<legend style="text-transform:capitalize; font-size:20px; color:#d2d2d2">Product discounts</legend>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; font-weight:bold;">';
								$promotions_group .= '		Applied to the following products:';
								$promotions_group .= '	</div>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; width:100%">';
								$promotions_group .= $promotions_products;		
								$promotions_group .= '	</div>';
								$promotions_group .= '</fieldset>';	
							}
							break;
						case 2:
							$promotions_products = '';
							foreach($arr_promotions_products as $row_3){
								if($row_3['itm_key'] == _same_product_){
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top">';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden;">';
									$promotions_products .= '<span style="font-weight:bold; color:#961F5F">&radic;&nbsp;&nbsp;The products in the promotion.</span>';
									$promotions_products .= '</div>';
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px; padding-left:100px;">';
									$promotions_products .= '<b>Quantity:</b> '.number_format($row_3['freeqty']);
									$promotions_products .= '</div>';
									
									$promotions_products .= '</td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';
								}else{
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px; margin-top:10px; border-top:1px dashed #d2d2d2">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top"><img src="shopping/data/img/thumb/'.$row_3['file'].'" border="0" width="90px" class="img_box_radi" /></td>';
									$promotions_products .= '		<td align="left" valign="top" style="padding-left:10px">';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden;">';
									$promotions_products .=  '<a href="'.$this ->system->cleanUrl().'product/edit&itemid='.$row_3['itm_key'].'" style="font-weight:bold">'.$row_3['itm_name'].'</a>'."<br><b>Model: </b>".$row_3['itm_model'];
									$promotions_products .= '</div>';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px;">';
									$promotions_products .= '<b>Quantity:</b> '.number_format($row_3['freeqty']);
									$promotions_products .= '</div>';
									
									$promotions_products .= '</td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';
								}
							}
							if($promotions_products != ''){
								$promotions_group .= '<fieldset style="margin-top:10px; padding-top:10px; border:1px solid #d2d2d2;">';
								$promotions_group .= '	<legend style="text-transform:capitalize; font-size:20px; color:#d2d2d2">Free Products</legend>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; font-weight:bold;">';
								$promotions_group .= '		Applied to the following products:';
								$promotions_group .= '	</div>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; width:100%">';
								$promotions_group .= $promotions_products;		
								$promotions_group .= '	</div>';
								$promotions_group .= '</fieldset>';	
							}
							break;
						case 3:
							$promotions_products = '';
							foreach($arr_promotions_products as $row_3){
								$discount = '';
								if($row_3['discount_type'] == 0){
									$discount = number_format($row_3['discount']).'%';	
								}else{
									$discount = '$'.number_format($row_3['discount'], 2);	
								}
								if($row_3['itm_key'] == _same_product_){
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px;">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top">';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden;">';
									$promotions_products .= '<span style="font-weight:bold; color:#961F5F">&radic;&nbsp;&nbsp;The products in the promotion.</span>';
									$promotions_products .= '</div>';
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px; padding-left:100px;">';
									$promotions_products .= '<b>Discount:</b> '.$discount;
									$promotions_products .= '</div>';
									
									$promotions_products .= '</td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';
								}else{
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px; margin-top:10px; border-top:1px dashed #d2d2d2">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top"><img src="shopping/data/img/thumb/'.$row_3['file'].'" border="0" width="90px" class="img_box_radi" /></td>';
									$promotions_products .= '		<td align="left" valign="top" style="padding-left:10px">';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden;">';
									$promotions_products .=  '<a href="'.$this ->system->cleanUrl().'product/edit&itemid='.$row_3['itm_key'].'" style="font-weight:bold">'.$row_3['itm_name'].'</a>'."<br><b>Model: </b>".$row_3['itm_model'];
									$promotions_products .= '</div>';
									
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px;">';
									$promotions_products .= '<b>Discount:</b> '.$discount;
									$promotions_products .= '</div>';
									
									$promotions_products .= '</td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';		
								}		
							}
							if($promotions_products != ''){
								$promotions_group .= '<fieldset style="margin-top:10px; padding-top:10px; border:1px solid #d2d2d2;">';
								$promotions_group .= '	<legend style="text-transform:capitalize; font-size:20px; color:#d2d2d2">Shipping discounts</legend>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; font-weight:bold;">';
								$promotions_group .= '		Applied to the following products:';
								$promotions_group .= '	</div>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; width:100%">';
								$promotions_group .= $promotions_products;		
								$promotions_group .= '	</div>';
								$promotions_group .= '</fieldset>';	
							}
							break;
						case 4:
							$promotions_products = '';
							foreach($arr_promotions_products as $row_3){
								if($row_3['itm_key'] == _same_product_){
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top"><span style="font-weight:bold; color:#961F5F">&radic;&nbsp;&nbsp;The products in the promotion.</span></td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';
								}else{
									$promotions_products .= '<div style="clear:both; overflow:hidden; padding-top:10px; margin-top:10px; border-top:1px dashed #d2d2d2">';
									$promotions_products .= '<table cellpadding="0" cellspacing="0" border="0">';
									$promotions_products .= '	<tr>';
									$promotions_products .= '		<td align="left" valign="top"><img src="shopping/data/img/thumb/'.$row_3['file'].'" border="0" width="90px" class="img_box_radi" /></td>';
									$promotions_products .= '		<td align="left" valign="top" style="padding-left:10px"><a href="'.$this ->system->cleanUrl().'product/edit&itemid='.$row_3['itm_key'].'" style="font-weight:bold">'.$row_3['itm_name'].'</a>'."<br><b>Model: </b>".$row_3['itm_model'].'</td>';
									$promotions_products .= '	</tr>';
									$promotions_products .= '</table>';
									$promotions_products .= '</div>';
								}
							}
							if($promotions_products != ''){
								$promotions_group .= '<fieldset style="margin-top:10px; padding-top:10px; border:1px solid #d2d2d2;">';
								$promotions_group .= '	<legend style="text-transform:capitalize; font-size:20px; color:#d2d2d2">free shippings</legend>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; font-weight:bold;">';
								$promotions_group .= '		Applied to the following products:';
								$promotions_group .= '	</div>';
								$promotions_group .= '	<div style="clear:both; overflow:hidden; width:100%">';
								$promotions_group .= $promotions_products;		
								$promotions_group .= '	</div>';
								$promotions_group .= '</fieldset>';	
							}
							break;
					}		
				}
			}
			if($promotions_group == '') continue;
			$start_date = 'None';
			if(is_numeric($row['start_date'])) $start_date = date("m/d/Y", $row['start_date']);
			
			$end_date = 'None';
			if(is_numeric($row['end_date'])) $end_date = date("m/d/Y", $row['end_date']);
			$return_arr[] = array(
				'promotion_products' =>$promotions_group,
				'promo_code' =>$row['promo_code'],
				'promo_name' =>$row['promo_name'],
				'start_date' =>$start_date,
				'end_date' =>$end_date,
				'description' =>$this ->lib ->ConvertToHtml($row['description']),
				'subtotal' =>number_format($row['subtotal'], 2),
				'minqty' =>number_format($row['minqty']),
			);
		}
		return $return_arr;
	}//view_loadData function
	
}//end Promotion_model class