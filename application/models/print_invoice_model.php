<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_invoice_model extends CI_Model 
{
	function loadData($key='')
	{
		$key = $this ->lib ->escape($key);
		$created_str = '';
		$legal_business_name = '';
		$address = '';
		$invoi_number = 1;
		$pay = 0;
		$title = '';
		$orders_ID = '';
		$tblcontries = array();
		$re = $this ->db ->query("select * from tblcontries");
		foreach($re ->result_array() as $row)
		{
			$tblcontries[$row['code']] = $row['name'];	
		}
		$sql = "select * from payments where pkey = '$key'";
		$re = $this ->db ->query($sql);
		
		if($re->num_rows()>0){
			$row = $re ->row_array();
			$invoi_number = $row['id'];
			$created_str = date("m/d/Y", strtotime($row['date_pay']));
			$legal_business_name = $row['legal_business_name'];	
			$table = '';
			switch($row['role']){
				case 0:
					$table = 'acquisition_agent';
					$title = 'Commission Employees';
					break;
				case 12:
					$table = 'acquisition_agent';
					$title = 'Commission acquisition agent';
					break;
				case 6:
					$table = 'tbaffiliates';
					$title = 'Commission affiliates';
					break;	
				case 5:
					$table = 'manufacturers';
					$title = 'Manufacturers';
					break;
				case 8:
					$table = 'charities';
					$title = 'Commission charities';
					break;
				case 9:
					$table = 'representatives';
					$title = 'Commission sale representatives';
					break;		
			}			if($row['role'] == 0){
				$legal_business_id = "None";
				$address = "None";	
			}else{
				$re_2 = $this ->db ->query("select legal_business_name,address,city,state,zipcode,country from $table where legal_business_id = '".$row['legal_business_id']."' ");
				if($re_2->num_rows()>0){
					$row_2 = $re_2 ->row_array();
					$address = $row_2['address'].'<br>'.$row_2['city'].', '.$row_2['state'].' '.$row_2['zipcode'].'<br>'.(isset($tblcontries[$row_2['country']])?$tblcontries[$row_2['country']]:$row_2['country']);
				}
			}
			if($row['role'] == 5){
				$re_2 = $this ->db ->query("select okey,pay from payments_orders where pkey = '".$row['pkey']."' ");
				foreach($re_2 ->result_array() as $row_2)
				{
					$pay += $row_2['pay'];
					$orders_ID .= $row_2['okey'].', ';
				}	
			}else{
				$pay = $row['pay'];		
			}
		}
		$this ->load ->library('num2text');
		$n2s = $this ->num2text->convertDolla($pay);
		$data = array(
			'title' => $title,
			'date' => $created_str,
			'pay_to' => $legal_business_name,
			'address' => $address,
			'invoice_number' => sprintf('%06d', $invoi_number),
			'Total' => number_format($pay, 2),
			'n2s' => $n2s,
			'order_key' =>''
		);
		if($orders_ID != ''){
			$orders_ID = substr($orders_ID, 0, strlen($orders_ID) - 2);
			$data['order_key'] = '<td align="left" valign="top"><b>Order # :</b></td><td align="left" valign="top">'.$orders_ID.'</td></tr>';
		}
		return $data;
	}//end loadData function
}//end Print_invoice_model function