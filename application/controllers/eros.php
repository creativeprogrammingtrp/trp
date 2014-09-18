<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class EROs extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this ->load ->model("manufacturer_model");
	}//end __construct function
	
	public function index()
	{
		if(isset($_POST['loadDataUsers']) && $_POST['loadDataUsers'] == 'yes')
		{
			echo json_encode($this -> manufacturer_model ->loadDataUsers());
			exit;
		}
		$data['keyword'] = (!empty($_GET['keyword']))? urldecode($_GET['keyword']):'';
		$data['status0'] = (!empty($_GET['status']) && $_GET['status']== 2)? 'selected="selected"':'';
		$data['status1'] = (!empty($_GET['status']) && $_GET['status']==1)? 'selected="selected"':'';
		$data['title_page'] = 'ERO';
		$data['addnewbt'] = '';
		if($this ->author ->isAccessPerm('Manufacturers','add'))
		{
			$data['addnewbt'] = '<input type="button" class="btn btn-primary margin-10" value="+ Add Account" onclick="AddNewAccount()" />';
		}
		
		$data['exportbt'] = '';
		if($this ->author ->isAccessPerm('Manufacturers','export'))
		{
			$data['exportbt'] = '<input type="submit" class="btn btn-info margin-10" value="Export to Excel" onclick="return Export_to_Excel()"/>';	
		}
		$this ->system ->parse('manufacturers.htm',$data);
		
	}//end index class
	
	public function view($key='')
	{
		if(isset($_POST['loadData']) && $_POST['loadData'] == 'yes')
		{
			echo json_encode($this ->manufacturer_model ->view_loadData());
			exit;
		}
		$data['title_page'] = 'View ERO';
		$data['key'] = trim($key);
		$data['business_type'] =$this ->lib ->loadBusinessType();
		$data['legal_title'] = $this ->lib ->loadLegalTitle();
		$this ->system ->parse('view_manufacturer.htm',$data);
	}//end view function
	
	public function add()
	{
		if(isset($_POST['saveUser']))
		{
			echo json_encode($this ->manufacturer_model ->add_saveUser());
			exit;
		}
		$data['business_type'] = $this ->lib ->loadBusinessType();
		$data['legal_title'] = $this ->lib ->loadLegalTitle();
		$data['load_countries'] = "dataCountries = ".json_encode($this ->lib -> __loadDataCountries__()).";";
		$data['title_page'] = 'Add ERO';
		$this ->system ->parse('add_manufacturer.htm',$data);
		
	}//end add function
	
	public function edit($key='')
	{         
		if(isset($_POST['saveUser']))
		{
			echo json_encode($this ->manufacturer_model ->edit_saveUser());
			exit;
		}
		if(isset($_POST['loadData']) && $_POST['loadData'] == 'yes')
		{
			echo json_encode($this ->manufacturer_model ->edit_loadData());
			exit;
		}
		if(isset($_POST['sendmail']) && $_POST['sendmail'] == 'yes')
		{
			echo json_encode($this ->lib ->__sendmail_profile__());
			exit;
		}
		$data['key'] = trim($key);
		$data['business_type'] = $this ->lib ->loadBusinessType();
		$data['legal_title'] = $this ->lib ->loadLegalTitle();
		$data['load_countries'] = "dataCountries = ".json_encode($this ->lib -> __loadDataCountries__()).";";
		$data['title_page'] = 'Edit ERO'; 
		$this ->system ->parse('edit_manufacturer.htm',$data);
	}
	
	public function delete($key='')
	{
		if(isset($_POST['delete_client']) && $_POST['delete_client'] == 'yes')
		{
			if(isset($_POST['cid']) && $_POST['cid'] != '')
			{
				echo $this ->manufacturer_model -> delete_client();
			}
			exit;
		}
		$data['ukey'] = trim($key);	
		$this ->system->parse_templace('delete_manufacturers.htm',$data);
	}//end delete function
	
	public function export()
	{
		$type = 'xls';
		if(isset($_POST['type']) && $_POST['type'] != '')
		{
			$type = $_POST['type'];
		}
		if (strcasecmp($type,'xls') == 0)
		{
			echo json_encode($this ->Excel_export());
		}
		exit;
		
	}//end export function
	
	private function Excel_export()
	{
		set_include_path(get_include_path() . PATH_SEPARATOR . 'shopping/excel_class/');
		include 'PHPExcel.php';
		/** PHPExcel_IOFactory */
		include 'PHPExcel/IOFactory.php';
		require_once 'PHPExcel/RichText.php';
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()-> setTitle('Manufacturers');
		
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(9); 
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
		$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(25);
		$objPHPExcel->getActiveSheet()->setShowGridlines(false);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(24);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(24);
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		//mergecell
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
		
		//Richtext
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16); 
		$objPHPExcel->getActiveSheet()->getStyle('A1:F4')->getFont()->setBold(true);
		
		//center text
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
		//set value	
		$objItem = $this ->manufacturer_model ->getObjItem();
		$total = count($objItem);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ERO Report');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Total: '.$total);
		$objPHPExcel->getActiveSheet()->setCellValue('A4', '#');
		$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Legal Business ID');
		$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Legal Business Name');
		$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Email');
		$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Create Date');
		$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Status');
		
		for($i = 0; $i < $total; $i++){
			$temp = $i+5;
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$temp)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$temp)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$temp)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$temp)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$temp)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$temp)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$temp)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$temp)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$temp)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$temp)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getRowDimension($temp)->setRowHeight(25);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$temp, ($i+1));
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$temp, $objItem[$i]['legal_business_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$temp, $objItem[$i]['legal_business_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$temp, $objItem[$i]['mail']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$temp, $objItem[$i]['created_str']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$temp, $objItem[$i]['status']);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$temp.':F'.$temp)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$temp.':F'.$temp)->getBorders()->getBottom()->getColor()->setRGB('D3D3D3');
		}
		
	//////////////////////////////////
		
		//xuat file
		//$file_name = "resource/business/Manufacturers.xls";
//		header('Content-Type: application/vnd.ms-excel');
//		header('Content-Disposition: attachment;filename="'.$file_name.'"');
//		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter->save('php://output');
		$objWriter->save('resource/business/Manufacturers_Report.xls');
		return array('error'=>'');
	}//end Excel_export function
	
	public function employee($key='')
	{
		if(isset($_POST['loadDataUsers']) && $_POST['loadDataUsers'] == 'yes')
		{
			echo json_encode($this ->manufacturer_model ->em_loadDataUsers());
			exit;
		}
		$data['key'] = trim($key);
		$this ->system ->parse_templace('employee.htm',$data);
	}//end employee function
	
	public function perms()
	{
		$perms['View ERO List'] = array('index');
		$perms['View ERO'] = array('view');
		$perms['Add ERO'] = array('add');
		$perms['Delete ERO'] = array('delete');
		$perms['Modify ERO'] = array('edit');
		$perms['Export Excel ERO'] = array('export');
		return $perms;		
	}//end perms class
}//end Manufactures class