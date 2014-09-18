<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_employees extends CI_Model{	
    public function __construct(){
		parent::__construct();
    }
    
    public function loadData(){
        $total_commission = 0;
        $paid = 0;
        $balance = 0;
        $re = $this->db->query("select commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality,orders.okey,orders.status from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 0");
        foreach($re->result_array() as $row){
			$status_order = 3;
			$packages = $this->get_packages($row['okey']);
			$status_order = $this->getOrderStatus($row, $packages);
			if($status_order !=3 ) continue;
			$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1");
			$qty_buy = $row['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$itemprice = round($row['itemprice']*$qty_buy, 2);
			
			$total_commission += round($row['commission'] * $itemprice / 100, 2); 
        }
			
        $total_commission += $this->database->db_result("select sum(raise) from raises where role = 0");
        $total_commission = round($total_commission, 2);
        $paid = $this->database->db_result("select sum(pay) from payments where role = 0");
        $paid = round($paid, 2);
        $balance = $total_commission - $paid;
        $balance = round($balance, 2);
        $arr_data = array(
            'total_commission' => $total_commission,
            'paid'=> $paid,
            'balance' => $balance
        );
        return $arr_data;
    } 
    
    public function loadSalesChart(){
		$arr_years = array();
		$arr_dataCharts = array();
		$min_Year = $year = (int)date('Y');
		$re_3 = $this->db->query("select pay,MONTH(date_pay) as month_pay,YEAR(date_pay) as year_pay from payments where role = 0");
		foreach($re_3->result_array() as $row_3){
            $month_chart = (int)$row_3['month_pay'];
            $year_chart = (int)$row_3['year_pay'];
            if($min_Year > $year_chart) $min_Year = $year_chart;
            $check_ = false;
            for($i = 0; $i < count($arr_dataCharts); $i++){
				if($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart){
                    $arr_dataCharts[$i]['paid'] += (float)$row_3['pay'];
                    $check_ = true;
                    break;	
				}	
            }
            if($check_ == false){
				$arr_dataCharts[] = array(
                                    'year' => $year_chart,
                                    'month' => $month_chart,
                                    'paid' => (float)$row_3['pay'],
                                    'YTD_earnings' => 0
                );		
            }	
		}
		$re_2 = $this->db->query("select commission_charities.purchase_date,commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality,orders.okey,orders.status from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 0");
		foreach($re_2->result_array() as $row_2){
			$status_order = 3;
			$packages = $this->get_packages($row_2['okey']);
			$status_order = $this->getOrderStatus($row_2, $packages);
			if($status_order !=3 ) continue;
			
            $check_ = false;
            $qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row_2['id']." and status = 1");
            $qty_buy = $row_2['quality'] - $qty_refund;
            if($qty_buy < 0) $qty_buy = 0;
            $YTD_sales = round($row_2['itemprice']*$qty_buy, 2);
            $YTD_earnings = round($row_2['commission'] * $YTD_sales / 100, 2);
		
            $purchase_date = strtotime($row_2['purchase_date']);
            $month_chart = (int)date("m", $purchase_date);
            $year_chart = (int)date("Y", $purchase_date);
            if($min_Year > $year_chart) $min_Year = $year_chart;
            for($i = 0; $i < count($arr_dataCharts); $i++){
            	if($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart){
                    $arr_dataCharts[$i]['YTD_earnings'] += (float)$YTD_earnings;
                    $check_ = true;
                    break;
				}	
            }
            if($check_ == false){
				$arr_dataCharts[] = array(
                                    'year' => $year_chart,
                                    'month' => $month_chart,
                                    'paid' => 0,
                                    'YTD_earnings' => (float)$YTD_earnings
				);		
            }	
		}
		$re_2 = $this->db->query("select MONTH(raises.date_raise) as month_chart,YEAR(raises.date_raise) as year_chart,raises.raise from raises join charities join users on raises.legal_business_id = charities.legal_business_id and charities.uid = users.uid where users.status = 1 and raises.role = 0");
		foreach($re_2->result_array() as $row_2){
            $YTD_earnings = round($row_2['raise'], 4);
            $month_chart = (int)$row_2['month_chart'];
            $year_chart = (int)$row_2['year_chart'];
            for($i = 0; $i < count($arr_dataCharts); $i++){
				if($arr_dataCharts[$i]['month'] == $month_chart && $arr_dataCharts[$i]['year'] == $year_chart){
                    $arr_dataCharts[$i]['YTD_earnings'] += (float)$YTD_earnings;
                    $check_ = true;
                    break;	
				}	
            }
            if($check_ == false){
                $arr_dataCharts[] = array(
                    'year' => $year_chart,
                    'month' => $month_chart,
                    'paid' => 0,
                    'YTD_earnings' => (float)$YTD_earnings
                );		
            }	
		}
		for($i = $min_Year; $i < $year+1; $i++){
                    $arr_years[] = (int)$i;	
		}
		return array(
					'objYear' => $arr_years,
					'chart' => $arr_dataCharts
				);
                                
    }
	
    public function payment(){
		$key = '';
		if(isset($_GET['key']) && $_GET['key'] != ''){
			$key = $_GET['key'];
		}
		$paid = 0;
		$total = 0;
        $total_commission = 0;
	
		$re = $this->db->query("select commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality,orders.okey,orders.status from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 0");
		foreach($re->result_array() as $row){
			$status_order = 3;
			$packages = $this->get_packages($row['okey']);
			$status_order = $this->getOrderStatus($row, $packages);
			if($status_order !=3 ) continue;
			$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1");
			$qty_buy = $row['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$itemprice = round($row['itemprice']*$qty_buy, 2);
			$total_commission += round($row['commission'] * $itemprice / 100, 2);
		}
		$total_commission += $this->database->db_result("select sum(raise) from raises where role = 0");
		$legal_business_name = "Employees";
		$legal_business_id = "None";
		$paid = $this->database->db_result("select sum(pay) from payments where role = 0");
		$total = $total_commission - $paid;
		
		if($total < 0) $total = 0;
		$total = round($total, 2);
        $content_check = array(
                          		'date'                   => date("F j, Y, g:i a"),
                           		'legal_business_name'    => $legal_business_name,
                           		'legal_business_id'      => $legal_business_id,
                           		'total'                  => number_format($total, 2),
                           		'@total@'                => $total,
                           		'total_paid'             => number_format($paid, 2),
                           		'total_commission'       => number_format($total_commission, 2),
                           		'key'                    => $key,
       					 );
		return $content_check;
    }
    
    public function paymentsub(){
        $key = '';
        $error = '';
		$pay = 0;
        
        $pay_money = (($this->input->post('pay_money') && is_numeric($this->input->post('pay_money')) && count($this->input->post('pay_money')) > 0))? $this->input->post('pay_money'):0;
		$pay_key = $this->lib->GeneralRandomKey(20);
		$re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
		foreach($re_key->result_array() as $row_key){
			$pay_key = $this->lib->GeneralRandomKey(20);
			$re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
		}
		$datas = array(
						'pkey' 					=> $pay_key,
						'check_number' 			=> $this->lib->escape($this->input->post('check_number')),
						'role' 					=> 0,
						'legal_business_id' 	=> $key,
						'legal_business_name' 	=> $this->lib->escape($this->input->post('legal_business_name')),
						'pay' 					=> $pay_money,
						'date_pay' 				=> date("Y-m-d H:i:s")
        		);
		$id = $this->db->insert('payments',$datas);
        if($id != true) $error = 'Can not save to database.';
		else{
			$pay = $this->database->db_result("select sum(pay) from payments where legal_business_id = '$key' and role = 8");
		}
		return array('error'=>$error, 'paid'=>$pay, 'pay_key'=>$pay_key);
    }

    public function paymentprint($key){
		$created_str = '';
		$legal_business_name = '';
		$address = '';
		$invoi_number = 1;
		$pay = 0;
		$title = '';
		$orders_ID = '';
		$tblcontries = array();
		$re = $this->db->query("select * from tblcontries");
		foreach($re->result_array() as $row){
			$tblcontries[$row['code']] = $row['name'];	
		}
		$re = $this->db->query("select * from payments where pkey = '$key'");
		if($re->num_rows() > 0){
        	$row = $re->row_array();
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
			}
			if($row['role'] == 0){
				$legal_business_id = "None";
				$address = "None";	
			}else{
				$re_2 = $this->db->query("select legal_business_name,address,city,state,zipcode,country from $table where legal_business_id = '".$row['legal_business_id']."' ");
			if($re_2->num_row() > 0){
	            $row_2 = $re_2->row_array();
				$address = $row_2['address'].'<br>'.$row_2['city'].', '.$row_2['state'].' '.$row_2['zipcode'].'<br>'.(isset($tblcontries[$row_2['country']])?$tblcontries[$row_2['country']]:$row_2['country']);
			}
		}
			if($row['role'] == 5){
				$re_2 = $this->db->query("select okey,pay from payments_orders where pkey = '".$row['pkey']."' ");
				foreach($re_2->result_array() as $row_2){
					$pay += $row_2['pay'];
					$orders_ID .= $row_2['okey'].', ';
				}	
			}else{
				$pay = $row['pay'];		
			}
		}
        $this->load->library('num2text');
		$n2s = $this->num2text->convertDolla($pay);
		if($orders_ID != ''){
			$orders_ID = substr($orders_ID, 0, strlen($orders_ID) - 2);
            $dataprint = array();
            $dataprint['order_key'] = '<td align="left" valign="top"><b>Order # :</b></td><td align="left" valign="top">'.$orders_ID.'</td></tr>';
		}
        $dataprint['title']           = $title;
        $dataprint['date']            = $created_str;
        $dataprint['pay_to']          = $legal_business_name;
        $dataprint['address']         = $address;
        $dataprint['invoice_number']  = sprintf('%06d', $invoi_number);
        $dataprint['Total']           = number_format($pay, 2);
        $dataprint['n2s']             = $n2s;
        
        return $dataprint;
    }
    
    public function Excel_export(){
		//set_include_path(get_include_path() . PATH_SEPARATOR . 'shopping/excel_class/');
		include 'shopping/excel_class/PHPExcel.php';
		//PHPExcel_IOFactory
		include 'shopping/excel_class/PHPExcel/IOFactory.php';
		require_once 'shopping/excel_class/PHPExcel/RichText.php';
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()-> setTitle('Employees');
		
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(10);
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(9); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
		$objPHPExcel->getActiveSheet()->setShowGridlines(false);
		
		$objPHPExcel->setActiveSheetIndex(0);
		//mergecell
		$objPHPExcel->getActiveSheet()->mergeCells('A1:M1');
		$objPHPExcel->getActiveSheet()->mergeCells('B2:C2');
		$objPHPExcel->getActiveSheet()->mergeCells('B4:C9');
		$objPHPExcel->getActiveSheet()->mergeCells('B11:C11');
		$objPHPExcel->getActiveSheet()->mergeCells('D11:E11');
		$objPHPExcel->getActiveSheet()->mergeCells('D4:G4');
		$objPHPExcel->getActiveSheet()->mergeCells('D5:G5');
		$objPHPExcel->getActiveSheet()->mergeCells('D6:G6');
		$objPHPExcel->getActiveSheet()->mergeCells('D7:G7');
		$objPHPExcel->getActiveSheet()->mergeCells('D8:G8');
		$objPHPExcel->getActiveSheet()->mergeCells('D9:G9');
		
		//Richtext
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16); 
		$objPHPExcel->getActiveSheet()->getStyle('A1:M10')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B12:M12')->getFont()->setBold(true);
	
		//center text
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:M30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		//chen hinh
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath('shopping/themes/purple/images/Dashboard_hinhgi.jpg');
		$objDrawing->setHeight(70);
		$objDrawing->setCoordinates('B5');
		$objDrawing->setOffsetX(200);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
		//load data
		$total_commission = 0;
		$paid = 0;
	
		$re = $this->db->query("select commission_charities.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_charities join orders join order_detais on commission_charities.orderid = orders.orderid and commission_charities.odetail = order_detais.id where commission_charities.rid = 0");
		foreach($re->result_array() as $row){
			$qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = ".$row['id']." and status = 1");
			$qty_buy = $row['quality'] - $qty_refund;
			if($qty_buy < 0) $qty_buy = 0;
			$itemprice = round($row['itemprice']*$qty_buy, 2);
			$total_commission += round($row['commission'] * $itemprice / 100, 2);
		}
		$paid = $this->database->db_result("select sum(pay) from payments where role = 0");                
		$total_commission = round($total_commission, 2);
		$paid = round($paid, 2);
		$balance = $total_commission - $paid;
		if($balance < 0) $balance = 0;
		$balance = round($balance, 2);
        

		//set value	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Employees Report');
		$objPHPExcel->getActiveSheet()->setCellValue('B2', '1. Summary');
		$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Total Earnings:  $'.number_format($total_commission, 2));
		$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Paid:  $'.number_format($paid, 2));
		$objPHPExcel->getActiveSheet()->setCellValue('D6', 'Balance:  $'.number_format($balance, 2));

////////////////////////////////////////////////////////////////////////////////////////////////////	

		//style
		$objPHPExcel->getActiveSheet()->getStyle('B13:M15')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
	
		$objPHPExcel->getActiveSheet()->getStyle('B12:M12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6A4D97');
		$objPHPExcel->getActiveSheet()->getStyle('B12:M12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B13:M16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
		$objPHPExcel->getActiveSheet()->getStyle('A11:M12')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A13:A21')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B12:M12')->getFont()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('A13:M13')->getFont()->getColor()->setRGB('6F13CC');
		$objPHPExcel->getActiveSheet()->getStyle('A14:M14')->getFont()->getColor()->setRGB('00A651');
		$objPHPExcel->getActiveSheet()->getStyle('A15:M15')->getFont()->getColor()->setRGB('AD5A07');
	
		$objPHPExcel->getActiveSheet()->getStyle('B12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('B12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('C12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('C12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('D12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('D12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('E12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('E12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('F12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('F12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('G12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('G12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('H12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('H12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('I12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('I12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('J12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('J12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('K12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('K12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('L12')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('L12')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
	
		//set value
		$year_selected = isset($_POST['year'])&&($_POST['year'])!='' ? $_POST['year']:'';
		$objDataChart = isset($_POST['month_report'])?$_POST['month_report']:array();
		
		$objPHPExcel->getActiveSheet()->setCellValue('B11', '2. Month Report ');
		$objPHPExcel->getActiveSheet()->setCellValue('D11', 'Year: '.$objDataChart[0]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('A13', 'Total Earnings');
		$objPHPExcel->getActiveSheet()->setCellValue('A14', 'Paid');
		$objPHPExcel->getActiveSheet()->setCellValue('A15', 'Balance');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B12', 'Jan');
		$objPHPExcel->getActiveSheet()->setCellValue('B13', $objDataChart[0]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('B14', $objDataChart[0]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('B15', $objDataChart[0]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('C12', 'Feb');
		$objPHPExcel->getActiveSheet()->setCellValue('C13', $objDataChart[1]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('C14', $objDataChart[1]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('C15', $objDataChart[1]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('D12', 'Mar');
		$objPHPExcel->getActiveSheet()->setCellValue('D13', $objDataChart[2]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('D14', $objDataChart[2]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('D15', $objDataChart[2]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('E12', 'Apr');
		$objPHPExcel->getActiveSheet()->setCellValue('E13', $objDataChart[3]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('E14', $objDataChart[3]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('E15', $objDataChart[3]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('F12', 'May');
		$objPHPExcel->getActiveSheet()->setCellValue('F13', $objDataChart[4]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('F14', $objDataChart[4]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('F15', $objDataChart[4]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('G12', 'Jun');
		$objPHPExcel->getActiveSheet()->setCellValue('G13', $objDataChart[5]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('G14', $objDataChart[5]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('G15', $objDataChart[5]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('H12', 'Jul');
		$objPHPExcel->getActiveSheet()->setCellValue('H13', $objDataChart[6]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('H14', $objDataChart[6]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('H15', $objDataChart[6]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('I12', 'Aug');
		$objPHPExcel->getActiveSheet()->setCellValue('I13', $objDataChart[7]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('I14', $objDataChart[7]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('I15', $objDataChart[7]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('J12', 'Sep');
		$objPHPExcel->getActiveSheet()->setCellValue('J13', $objDataChart[8]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('J14', $objDataChart[8]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('J15', $objDataChart[8]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('K12', 'Oct');
		$objPHPExcel->getActiveSheet()->setCellValue('K13', $objDataChart[9]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('K14', $objDataChart[9]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('K15', $objDataChart[9]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('L12', 'Nov');
		$objPHPExcel->getActiveSheet()->setCellValue('L13', $objDataChart[10]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('L14', $objDataChart[10]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('L15', $objDataChart[10]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('M12', 'Dec');
		$objPHPExcel->getActiveSheet()->setCellValue('M13', $objDataChart[11]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('M14', $objDataChart[11]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('M15', $objDataChart[11]['balance']);
	
	/////////////////////////////////////////////////////////////////////
	
		//style
		$objPHPExcel->getActiveSheet()->getStyle('B19:M21')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
	
		$objPHPExcel->getActiveSheet()->getStyle('B18:M18')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6A4D97');
		$objPHPExcel->getActiveSheet()->getStyle('B18:M18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B19:M21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
		$objPHPExcel->getActiveSheet()->getStyle('A17:M18')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A18:A21')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B18:M18')->getFont()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('A19:M19')->getFont()->getColor()->setRGB('6F13CC');
		$objPHPExcel->getActiveSheet()->getStyle('A20:M20')->getFont()->getColor()->setRGB('00A651');
		$objPHPExcel->getActiveSheet()->getStyle('A21:M21')->getFont()->getColor()->setRGB('AD5A07');
	
		$objPHPExcel->getActiveSheet()->getStyle('B18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('B18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('C18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('C18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('D18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('D18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('E18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('E18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('F18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('F18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('G18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('G18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('H18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('H18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('I18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('I18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('J18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('J18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('K18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('K18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('L18')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('L18')->getBorders()->getRight()->getColor()->setRGB(PHPExcel_Style_Color::COLOR_WHITE);
	
		$objPHPExcel->getActiveSheet()->mergeCells('B17:C17');
		$objPHPExcel->getActiveSheet()->mergeCells('D17:E17');
		
		//set value
		$objDataChartY = isset($_POST['year_report'])&&$_POST['year_report']!=''?$_POST['year_report']:array();
		
		$objPHPExcel->getActiveSheet()->setCellValue('B17', '3. Year Report');
		$objPHPExcel->getActiveSheet()->setCellValue('D17', 'Year: '.$objDataChartY[0]['year'].'-'.$year_selected);
		$objPHPExcel->getActiveSheet()->setCellValue('A19', 'Purchased');
		$objPHPExcel->getActiveSheet()->setCellValue('A20', 'Paid');
		$objPHPExcel->getActiveSheet()->setCellValue('A21', 'Balance');
		
		$objPHPExcel->getActiveSheet()->setCellValue('B18', $objDataChartY[0]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('B19', $objDataChartY[0]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('B20', $objDataChartY[0]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('B21', $objDataChartY[0]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('C18', $objDataChartY[1]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('C19', $objDataChartY[1]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('C20', $objDataChartY[1]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('C21', $objDataChartY[1]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('D18', $objDataChartY[2]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('D19', $objDataChartY[2]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('D20', $objDataChartY[2]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('D21', $objDataChartY[2]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('E18', $objDataChartY[3]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('E19', $objDataChartY[3]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('E20', $objDataChartY[3]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('E21', $objDataChartY[3]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('F18', $objDataChartY[4]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('F19', $objDataChartY[4]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('F20', $objDataChartY[4]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('F21', $objDataChartY[4]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('G18', $objDataChartY[5]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('G19', $objDataChartY[5]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('G20', $objDataChartY[5]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('G21', $objDataChartY[5]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('H18', $objDataChartY[6]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('H19', $objDataChartY[6]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('H20', $objDataChartY[6]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('H21', $objDataChartY[6]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('I18', $objDataChartY[7]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('I19', $objDataChartY[7]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('I20', $objDataChartY[7]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('I21', $objDataChartY[7]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('J18', $objDataChartY[8]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('J19', $objDataChartY[8]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('J20', $objDataChartY[8]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('J21', $objDataChartY[8]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('K18', $objDataChartY[9]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('K19', $objDataChartY[9]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('K20', $objDataChartY[9]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('K21', $objDataChartY[9]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('L18', $objDataChartY[10]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('L19', $objDataChartY[10]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('L20', $objDataChartY[10]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('L21', $objDataChartY[10]['balance']);
		$objPHPExcel->getActiveSheet()->setCellValue('M18', $objDataChartY[11]['year']);
		$objPHPExcel->getActiveSheet()->setCellValue('M19', $objDataChartY[11]['YTD_earnings']);
		$objPHPExcel->getActiveSheet()->setCellValue('M20', $objDataChartY[11]['paid']);
		$objPHPExcel->getActiveSheet()->setCellValue('M21', $objDataChartY[11]['balance']);
	
//////////////////////////////////
	
		//xuat file
	//	$file_name = "data/testexcel.xls";
	//	header('Content-Type: application/vnd.ms-excel');
	//	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	//	header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	//	$objWriter->save('php://output');
		$objWriter->save('shopping/uploads/Employees_Report.xls');
		return array('error'=>'');
	}
	
	function getOrderStatus($row_2, $packages){
		$status_order = $row_2['status'];
		$okey = $row_2['okey'];
		$check_manufacturer = false;
		$subtotal = 0;
		$tax = 0;

		$order_status_level = array();
		$arr_manufacturers = array();
		$re_1 = $this->db->query("select order_detais.id,order_detais.Status,order_detais.current_cost,order_detais.quality,items.itm_key,items.uid,items.product_type,order_detais.tax_persend,order_detais.itemid from order_detais join items on order_detais.itemid = items.itm_id where order_detais.id = ".$row_2['id']);
		foreach($re_1->result_array() as $row_1){
			$check_exist = false;
			for($m = 0; $m < count($arr_manufacturers); $m++){
				if($arr_manufacturers[$m]['uid'] == $row_1['uid']){
					$arr_manufacturers[$m]['items'][] = $row_1;
					$check_exist = true;
					break;	
				}	
			}
			if($check_exist == false){
				$arr_manufacturers[] = array('uid'=>$row_1['uid'], 'items'=>array($row_1));		
			}	
		}
		for($m = 0; $m < count($arr_manufacturers); $m++){//0
			foreach($arr_manufacturers[$m]['items'] as $row_1){//1
				$itemid = $row_1['itemid'];
				$qty_ship = 0;
				$qty_par = 0;
				if(count($packages) > 0){
					foreach($packages as $package){
						$items = $package['items'];
						for($i = 0; $i < count($items); $i++){
							if($items[$i]['product_id'] == $itemid){
								if($package['ship'] == 0){
									$qty_par += $items[$i]['qty'];
								}elseif($package['ship'] == 1){
									$qty_ship += $items[$i]['qty'];
								}
								break;	
							}	
						}	
					}	
				}
				$status_item = 1;
				if($row_1['product_type'] == 0){
					
					if($qty_ship == $row_1["quality"]){
						$status_item = 3;	
					}elseif($qty_par > 0 || $qty_ship > 0){
						$status_item = 2;		
					}
				}else{
					$status_item = 3;
				}	
				$order_status_level[] = $status_item;
			}//1
		}//0
		$min_level = 3;
		$Canceled_status = 0;
		$Refunded_status = 0;
		if(count($order_status_level) > 0){
			foreach ($order_status_level as $level){
				if($level == 4) $Canceled_status = 1;
				elseif($level == 5) $Refunded_status = 1;
				elseif($level < 4){
					if($level < $min_level){
						$min_level = $level;
					}		
				}
			}
		}
		if($status_order == 4) $Canceled_status = 1;
		if($Canceled_status == 1) $min_level = 4;
		elseif($Refunded_status == 1) $min_level = 5;
		return $min_level;
	}
	
	function get_packages($okey){
		$packages = array();
		$re_1 = $this->db->query("select id,pkey,shipment_ID from packages where okey = '".$okey."'");
		foreach($re_1->result_array() as $row_3){
			$ship = 0;
			$re_4 = $this->db->query("select id from shipments where skey = '".$row_3['shipment_ID']."' and okey = '".$okey."'");
			if($re_4->num_rows() > 0){
				$ship = 1;	
			}
			
			$items = array();
			$re_4 = $this->db->query("select product_id,qty from packages_items where package_id = ".$row_3['id']);
			foreach($re_4->result_array() as $row_4){
				$items[] = $row_4;	
			}
			
			$packages[] = array(
				'ship' => $ship,
				'items' => $items
			);
		}
		return $packages;	
	}
}