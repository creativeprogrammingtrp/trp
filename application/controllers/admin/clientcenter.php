<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Clientcenter extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/clientcenter_model", "m_clientcenter");
        $this->load->model("admin/mycompany_model", "m_com");
    }
    function perms(){
        $perms['Client Center'] = array('index','newapp','saveNewApplicationInfo','showRecentApplication','nextstep','updateApplicationInfo','showPendingFundsApplication','showReadyToPrintApplication','showSelectedReadyToPrintApplication','showAllApplication', 'showPrintedApplication','showVoidedApplication','generatePdfSelectedReadyToPrintApplication','setCheckAsVoid','setCheckAsVoidAndReprint','showSelectedReadyToPrintApplicationFromAdmin','showSelectedDirectDepositApplicationFromAdmin','showSelectedDirectDepositApplication','makeAllApplicationAsPaid','showPaidApplication','showVoidedPaymentApplication','makePaymentAsVoid','pdf','viewcheck','showSelectedCheckApp','checkCheckNoValidaty');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"clientCenter");
        $this->system->parse("clientCenter/clientcenter.htm",$data);
    }
    
    public function newapp(){
    	$data = array();
    	$data['title_page'] = "BankProducts";
        $data['curPageURLServer'] = $this->system->URL_server__();
    	//$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadBankingData());
    	$resultdata = $this->m_clientcenter->loadBankingData();
    	
    	if(is_array($resultdata) && count($resultdata)){
    		$data['tax_preparation_fee'] = $resultdata['tax_preparation_fee'];
    		$data['bank_transmission_fee'] = $resultdata['bank_transmission_fee'];
    		$data['sb_fee'] = $resultdata['sb_fee'];
    		//$data['e_file_fee'] = $data_load['e_file_fee'];
    		$data['add_on_fee'] = $resultdata['add_on_fee'];
    	}
    	
    /*	$data['message'] = '<div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <strong>Success!</strong> Your Application is Submitted!
                    </div>';
    	
    	*/
    	$data['statusmessage'] = '';
    	$action = $this->uri->segment (4);
    	if($action == 'save'){
    		$result = $this->m_clientcenter->saveNewApplicationInfo();
    		if($result){
    			$data['statusmessage'] = '<div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <strong>Success!</strong> Your Application is Submitted!
                    </div>';
    		}else{
    			$data['statusmessage'] = '<div class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <strong>Error!</strong> Sorry! Your Application is not successfully submited. Please Try Again.
                    </div>';
    		}
    	}
    	// print_r($data);
    	// exit;
    	$data['states'] = $this->m_com->loadStatesList();
    	$data['countries'] = $this->m_com->loadCountryList();
    	
    	$this->system->parse("clientCenter/new_application.htm",$data);
    }
    
    public function saveNewApplicationInfo() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->m_clientcenter->saveNewApplicationInfo());
    		exit();
    	}
    }
    
    public function nextstep() {
    	if (isset($_POST['next_step']) && !empty($_POST['next_step']) && $_POST['next_step'] == 'yes') {
    		//echo json_encode($this->m_clientcenter->saveNewApplicationInfo());
    		echo json_encode("data:1");
    		exit();
    	}
    }
    
    
    
    public function showRecentApplication() {
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadRecentApplication());
		//print_r($this->m_clientcenter->loadRecentApplication());exit;
    	$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('clientCenter/recent_applications.htm', $data);
    		exit();
    	}
    }
    
    public function showPendingFundsApplication() {
    	$data = array();
    	
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){

            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPendingFundsApplication());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/pending_funds_applications.htm', $data);
                    exit();
                }
            }else{ // if employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPendingFundsApplicationForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/pending_funds_applications_employee.htm', $data);
                    exit();
                }
            }

    	}else{ // if admin
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPendingFundsApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/pending_funds_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
	
    
    public function showReadyToPrintApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadReadyToPrintApplication());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/ready_to_print_applications.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadReadyToPrintApplicationForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/ready_to_print_applications_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadReadyToPrintApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/ready_to_print_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    
    public function showPrintedApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPrintedApplication());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/printed_applications.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPrintedApplicationForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/printed_applications_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPrintedApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/printed_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showVoidedApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedApplication());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/voided_applications.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedApplicationForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/voided_applications_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/voided_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }


    public function showPaidApplication() {
        $data = array();
        $data['states'] = $this->m_com->loadStatesList();

        if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPaidApplication());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/paid_applications.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPaidApplicationForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/paid_applications_employee.htm', $data);
                    exit();
                }
            }
        }else{
            $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadPaidApplicationForAdmin());
            if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                $this->system->parse_templace('clientCenter/paid_applications_admin.htm', $data);
                exit();
            }
        }
    }

    public function showVoidedPaymentApplication() {
        $data = array();
        $data['states'] = $this->m_com->loadStatesList();

        if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedPaymentApplication());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/voided_payment_applications.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedPaymentApplicationForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/voided_payment_applications_employee.htm', $data);
                    exit();
                }
            }
        }else{
            $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadVoidedPaymentApplicationForAdmin());
            if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                $this->system->parse_templace('clientCenter/voided_payment_applications_admin.htm', $data);
                exit();
            }
        }
    }

    
    public function showAllApplication() {
    	$data = array();
    	$data['states'] = $this->m_com->loadStatesList();
    	if($this->author->objlogin->uid != '1'){
            if($this->author->objlogin->isemployee != 1) { // if not employee
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadAllApplication());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/all_applications.htm', $data);
                    exit();
                }
            }else{
                $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadAllApplicationForEmployee());
                if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                    $this->system->parse_templace('clientCenter/all_applications_employee.htm', $data);
                    exit();
                }
            }
    	}else{
    		$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadAllApplicationForAdmin());
    		if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    			$this->system->parse_templace('clientCenter/all_applications_admin.htm', $data);
    			exit();
    		}
    	}
    }
    
    public function showSelectedReadyToPrintApplication() {
    	$data = array();
    	if($_GET['ids'] != ''){
    		$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']));
    	}else{
    		$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadAllReadyToPrintApplication());
    	}

       // print_r($data['dataLoad']);

        // check Assign Check list that will got from Wells Fargo

        //if($_GET['ids'] != '') {
        //    $assignChecksNo = $this->m_clientcenter->loadUncompletedAssignCheckRangeById($_GET['ids']);
        //}else{
            $assignChecksNo = $this->m_clientcenter->loadUncompletedAssignCheckRange();
        //}

        // check last generated check no.
        $lastPrintedCheckNo = $this->m_clientcenter->loadLastPrintedCheckNo();

        if($lastPrintedCheckNo != ''){
            $lastPrintedCheckNo = intval($lastPrintedCheckNo)+1;
        }else{
            $lastPrintedCheckNo = intval($assignChecksNo['starting_no']);
        }

        $data['lastPrintedCheckNo'] = json_encode($lastPrintedCheckNo);


    	//$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('clientCenter/modal_ready_to_print_applications.htm', $data);
    		exit();
    	}
    }


    public function showSelectedCheckApp() {
        $data = array();
        if($_GET['ids'] != ''){
            $data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']));
        }else{
            $data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadAllReadyToPrintApplication());
        }

        // print_r($data['dataLoad']);

        // check Assign Check list that will got from Wells Fargo

        //if($_GET['ids'] != '') {
        //    $assignChecksNo = $this->m_clientcenter->loadUncompletedAssignCheckRangeById($_GET['ids']);
        //}else{
        $assignChecksNo = $this->m_clientcenter->loadUncompletedAssignCheckRange();
        //}

        // check last generated check no.
        $lastPrintedCheckNo = $this->m_clientcenter->loadLastPrintedCheckNo();

        if($lastPrintedCheckNo != ''){
            $lastPrintedCheckNo = intval($lastPrintedCheckNo)+1;
        }else{
            $lastPrintedCheckNo = intval($assignChecksNo['starting_no']);
        }

        $data['lastPrintedCheckNo'] = json_encode($lastPrintedCheckNo);


        //$data['states'] = $this->m_com->loadStatesList();
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('clientCenter/modal_print_check_applications.htm', $data);
            exit();
        }
    }


    public function showSelectedReadyToPrintApplicationFromAdmin() {
        $data = array();
        $selectedApp = $this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']);
      //  print_r($selectedApp);
        //if($_GET['ids'] != ''){
            $data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']));
        //}else{
            //$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadReadyToPrintApplication());
        //}

        // print_r($data['dataLoad']);

        // check Assign Check list that will got from Wells Fargo

        //if($_GET['ids'] != '') {
            $assignChecksNo = $this->m_clientcenter->loadUncompletedAssignCheckRangeById($selectedApp[0]['uid']);
        //}else{
        //$assignChecksNo = $this->m_clientcenter->loadUncompletedAssignCheckRange();
        //}

        // check last generated check no.
        $lastPrintedCheckNo = $this->m_clientcenter->loadLastPrintedCheckNoById($selectedApp[0]['uid']);

        if($lastPrintedCheckNo != ''){
            $lastPrintedCheckNo = intval($lastPrintedCheckNo)+1;
        }else{
            $lastPrintedCheckNo = intval($assignChecksNo['starting_no']);
        }

        $data['lastPrintedCheckNo'] = json_encode($lastPrintedCheckNo);


        //$data['states'] = $this->m_com->loadStatesList();
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('clientCenter/modal_ready_to_print_applications.htm', $data);
            exit();
        }
    }


    public function showSelectedDirectDepositApplicationFromAdmin() {
        $data = array();
        //$selectedApp = $this->m_clientcenter->loadSelectedDirectDepositApplication($_GET['ids']);
        //  print_r($selectedApp);
       // if($_GET['ids'] != '') {
            $data['dataLoad'] = "dataClientDirectDeposit = " . json_encode($this->m_clientcenter->loadSelectedDirectDepositApplication($_GET['ids']));
        //}else{
          //  $data['dataLoad'] = "dataClientDirectDeposit = " . json_encode($this->m_clientcenter->loaAllDirectDepositApplication();
        //}
        //$data['states'] = $this->m_com->loadStatesList();
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('clientCenter/modal_direct_deposit_applications.htm', $data);
            exit();
        }
    }

    public function showSelectedDirectDepositApplication() {
        $data = array();
        //$selectedApp = $this->m_clientcenter->loadSelectedDirectDepositApplication($_GET['ids']);
        //  print_r($selectedApp);
        if($_GET['ids'] != '') {
            $data['dataLoad'] = "dataClientDirectDeposit = " . json_encode($this->m_clientcenter->loadSelectedDirectDepositApplication($_GET['ids']));
        }else{
            $data['dataLoad'] = "dataClientDirectDeposit = " . json_encode($this->m_clientcenter->loadAllDirectDepositApplication());
        }

        //$data['states'] = $this->m_com->loadStatesList();
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('clientCenter/modal_direct_deposit_applications.htm', $data);
            exit();
        }
    }

    public function generatePdfSelectedReadyToPrintApplication() {//////////
    	$data = array();

        if($this->author->objlogin->parentUid > 0){
            $parrentUid = $this->author->objlogin->parentUid;
        }
        else{
            $parrentUid = $this->author->objlogin->uid;
        }

        $startingCheckNo = $_GET['startp'];
/*
    	if($_GET['ids'] != ''){
    		//$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']));
            $dataLoad = $this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']);
    	}else{
    		$dataLoad = $this->m_clientcenter->loadAllReadyToPrintApplication();
    	}
    	//$data['states'] = $this->m_com->loadStatesList();
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		//$this->system->parse_templace('clientCenter/modal_ready_to_print_applications.htm', $data);
    		//exit();

            // get last started check no
            $i = 1;
            foreach($dataLoad as $datas) {

                $data = array(
                    'check_no' => $startingCheckNo,
                    'uid' => $parrentUid,
                    'app_id' => $datas['app_id'],
                    'transaction_code' => '320',
                    'issue_date' => $this->lib->getTimeGMT(),
                    'check_amount' => $datas['app_actual_refund_amount'],
                    'additional_data' => '',

                    'status' => '1',
                    'action_date' => $this->lib->getTimeGMT(),
                    'author_id' => $this->author->objlogin->uid,
                );

                $this->db->insert("app_check", $data);
                $lastCheckId = $this->db->insert_id();

                $sql2 = "update  new_app set status = 2 where app_id = '" . $datas['app_id'] . "' ";
                $this->db->query($sql2);

                $startingCheckNo = $startingCheckNo+$i;
                $i++;
            }


    	}*/


        //for pdf generate
        $this->load->helper('pdf_helper');

        tcpdf();
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 005');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 005', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        //$pdf->SetFont('times', '', 10);
// set font
        $pdf->SetFont('helvetica', '', 10);

        // add a page
        $pdf->AddPage();



        //$pdf->Write(0, 'Example of HTML Justification', '', 0, 'L', true, 0, false, false, 0);

        // create some HTML content
       // $html = '<img src="'.base_url().'img/logo.png" border="0" /><span style="text-align:justify;">a <u>abc</u> abcdefghijkl (abcdef) abcdefg <b>abcdefghi</b> a ((abc)) abcd <img src="'.base_url().'img/1.png" border="0" height="41" width="41" /> <img src="'.base_url().'img/1.png" alt="test alt attribute" width="80" height="60" border="0" /> abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a <u>abc</u> abcd abcdef abcdefg <b>abcdefghi</b> a abc \(abcd\) abcdef abcdefg <b>abcdefghi</b> a abc \\\(abcd\\\) abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg abcdefghi a abc abcd <a href="http://tcpdf.org">abcdef abcdefg</a> start a abc before <span style="background-color:yellow">yellow color</span> after a abc abcd abcdef abcdefg abcdefghi a abc abcd end abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi<br />abcd abcdef abcdefg abcdefghi<br />abcd abcde abcdef</span>';
        $html = '<div>
            <div style="width: 200px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><img src="'.base_url().'img/logo.png" border="0" /></div>
            <div style="width: 200px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">WELLS FARGO</div>
            <div style="text-align: center; width: 120px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><u>12-123</u><br>6789</div>
            <div style="text-align: right; width: 120px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"> <div  >123456</div></div>

            <div class="clear1" style="margin-bottom: 30px; clear: both;"></div>
            <div class="col-md-1 border1" style="width: 8.33333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">PAY<br>TO THE<br> ORDER <br> OF</div>
            <div class="col-md-7" style="width: 58.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="border-bottom: 1px solid;  margin-top: 55px;">Tow thousend fifty nine.</div></div>
            <div class="col-md-2 border1" style=" margin-top: 20px; width: 16.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">DATE
            <BR>
                <div style="border-bottom: 1px solid;  margin-top: 15px;">03/12/2014</div>
            </div>
            <div class="col-md-2 border1" style="margin-top: 20px; width: 16.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">AMOUNT <br> <div class="border" style=" font-weight: bold; margin-top: 3px; padding: 6px;"> $2059.00</div> </div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-1 border1"  style=" margin-top: 55px; width: 8.33333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">MEMO</div>
            <div class="col-md-7" style="width: 58.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="border-bottom: 1px solid;  margin-top: 55px;">This is test memo for this check</div></div>
            <div class="col-md-4 border1" style=" margin-top: 75px; width: 33.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">
                <div style="border-bottom: 1px solid;"></div>
            </div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-8" style="width: 66.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="borderbottom: 1px solid;  margin-top: 55px;"></div></div>
            <div class="col-md-4" style="width: 33.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style=" margin-top: 5px; text-align: center;">AUTHORIZED SIGNATURE</div></div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-12" style="width: 100%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="borderbottom: 1px solid;  margin-top: 0px; text-align: center"> ." 123456 :. 873366637388333 :. 837887890 ".</div></div>
            <div class="clear1" style="clear: both;"></div>
        </div>';

        // set core font
        //$pdf->SetFont('helvetica', '', 10);

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
       // $pdf->writeHTML($html, true, 0, true, true);

        //$pdf->Ln();

        // set UTF-8 Unicode font
        //$pdf->SetFont('dejavusans', '', 10);

        // output the HTML content
        //$pdf->writeHTML($html, true, 0, true, true);

        // move pointer to last page
        $pdf->lastPage();

        //$pdf->writeHTML($content, true, false, true, false, '');
        $pdf->Output('printitem/output.pdf', 'F');

        //$this->load->view('pdfreport', $data);

       // $this->m_clientcenter->();
       // echo $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadReadyToPrintApplication());
        echo "All check printed successfully.";

    }

    function pdf()
    {
        $this->load->helper('file');
        $this->load->helper(array('dompdf', 'file'));
      //  $dompdf = $this->load->helper('dompdf');

        // page info here, db calls, etc.
        //$html = $this->load->view('controller/viewfile', $data, true);
        //pdf_create($html, 'filename');
       // or

        $html = '<div>
            <div style="width: 200px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><img width="172" height="61" src="'.base_url().'img/logo.png" border="0" /></div>
            <div style="width: 200px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">WELLS FARGO</div>
            <div style="text-align: center; width: 120px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><u>12-123</u><br>6789</div>
            <div style="text-align: right; width: 120px; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"> <div  >123456</div></div>

            <div class="clear1" style="margin-bottom: 30px; clear: both;"></div>
            <div class="col-md-1 border1" style="width: 8.33333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">PAY<br>TO THE<br> ORDER <br> OF</div>
            <div class="col-md-7" style="width: 55.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="border-bottom: 1px solid;  margin-top: 55px;">Tow thousend fifty nine.</div></div>
            <div class="col-md-2 border1" style=" margin-top: 20px; width: 16.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">DATE
            <BR>
                <div style="border-bottom: 1px solid;  margin-top: 15px;">03/12/2014</div>
            </div>
            <div class="col-md-2 border1" style="margin-top: 20px; width: 16.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">AMOUNT <br> <div class="border" style=" font-weight: bold; margin-top: 3px; padding: 6px;"> $2059.00</div> </div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-1 border1"  style=" margin-top: 55px; width: 8.33333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">MEMO</div>
            <div class="col-md-7" style="width: 55.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="border-bottom: 1px solid;  margin-top: 55px;">This is test memo for this check</div></div>
            <div class="col-md-4 border1" style=" margin-top: 75px; width: 33.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">
                <div style="border-bottom: 1px solid;"></div>
            </div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-8" style="width: 63.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="borderbottom: 1px solid;  margin-top: 55px;"></div></div>
            <div class="col-md-4" style="width: 33.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style=" margin-top: 5px; text-align: center;">AUTHORIZED SIGNATURE</div></div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-12" style="width: 100%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="borderbottom: 1px solid;  margin-top: 0px; text-align: center"> ." 123456 :. 873366637388333 :. 837887890 ".</div></div>
            <div class="clear1" style="clear: both;"></div>
        </div>';

        //$data = pdf_create($html, '', false);
        pdf_create($html, 'pdfgeneratedtest');

        echo '<script type="text/javascript" language="javascript">
                window.open("http://localhost/trpgit/pdfgeneratedtest.pdf", "_blank");
            </script>';

       // $output = $dompdf->output();
        //$file_to_save = './printitem/file2.pdf';
        //file_put_contents($file_to_save, $output);
       // $dompdf->load_html($html);
       // $dompdf->render();
       // $output = $this->output();
       // $file_to_save = './printitem/file2.pdf';
       // file_put_contents($file_to_save, $output);
        //write_file('name', $data);
        //if you want to write it to disk and/or send it as an attachment
    }

    function makeAllApplicationAsPaid(){

        if($_GET['ids'] != ''){
            //$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']));
            $dataLoad = $this->m_clientcenter->loadSelectedDirectDepositApplication($_GET['ids']);
        }else{
            //$dataLoad = $this->m_clientcenter->loadAllReadyToPrintApplication();
        }

        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            foreach($dataLoad as $datas) {
                 $sql2 = "update  new_app set status = 4, direct_deposit_time = '".$this->lib->getTimeGMT()."' where app_id = '" . $datas['app_id'] . "' "; // status = 4 = it's paid deposit.
                 $this->db->query($sql2);
            }
            echo "Selected Application is Changed as Paid.";
        }
    }

    function checkCheckNoValidaty(){
        $checkAvailability = $this->m_clientcenter->loadAndCheckUncompletedAssignCheckRange($_GET['cehckNo']);

        if(sizeof($checkAvailability) > 0 AND $checkAvailability['starting_no'] != ''){
            // This check no is is valid. so now need to check that is this check already printed or not.
            $checkAlreadyPrinted = $this->m_clientcenter->CheckPrintedAssignCheckRange($_GET['cehckNo']);
            if($checkAlreadyPrinted){
                echo "Your given check no is already used.";
            }
        }else{
            echo "This check no is not valid.";
        }
    }

    public function setCheckAsVoid(){

        $appid =  $this->input->post("id");

        $currenttime = $this->lib->getTimeGMT();

        $getcheckDetails = $this->m_clientcenter->loadCheckDetailsByAppId($appid);

        $issuetime = $getcheckDetails[0]["issue_date"];  // integer

        //$issuetimeFormated = gmdate("Y-m-d g:i:a", $issuetime); // issue time formated
        //echo $issuetimeFormated. "<br>";

        $timeAfterOneHour = $issuetime+60*60; // next one hours integer

        // $timeAfterOneHourFormated =  gmdate("Y-m-d g:i:a",$timeAfterOneHour);
        // echo $timeAfterOneHourFormated;

        //current time formated
        //$currentTimeFormated =  gmdate("Y-m-d g:i:a",$currenttime);

        // echo $currentTimeFormated;

        if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour){
            $sql = "update  app_check set transaction_code = '430' where app_id = '".$appid."' AND transaction_code = '320' AND re_print IS NULL";
                    $this->db->query($sql);

            $sql2 = "update  new_app set status = 3 where app_id = '".$appid."'";
                  $this->db->query($sql2);

            echo json_encode('<div class="col-md-12 tempmessage"><div class="alert alert-success"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Selected Check Voided Successfully.</strong></div></div> <br>');

        }else{
            echo json_encode('<div class="col-md-12 tempmessage"><div class="alert alert-warning"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Time Out, You can\'t  Void this check. Contact with Admin.</strong></div></div> <br>');
        }


  //      echo json_encode('<div class="col-md-12 "><div class="alert alert-success"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Selected Check Voided Successfully.</strong></div></div> <br><br>');
       // exit;
    }

    public function setCheckAsVoidAndReprint(){

        $appid =  $this->input->post("id");

        $currenttime = $this->lib->getTimeGMT();

        $getcheckDetails = $this->m_clientcenter->loadCheckDetailsByAppId($appid);

        $issuetime = $getcheckDetails[0]["issue_date"];  // integer

        $timeAfterOneHour = $issuetime+60*60; // next one hours integer

        if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour) {

            // print new cehck wiht new check no

            // check last generated check no.
            $lastPrintedCheckNo = $this->m_clientcenter->loadLastPrintedCheckNo();

            if($lastPrintedCheckNo != ''){
                $lastPrintedCheckNo = intval($lastPrintedCheckNo)+1;
            }

            $sql = "Select * from  app_check  where app_id = '" . $appid . "' AND transaction_code = '320' AND re_print IS NULL";
            $res = $this->db->query($sql);
            $resData = $res->result_array();
            $lastCheckId = '';
            if(sizeof($resData) > 0){
                $dataRePrint = array(

                        'check_no' => $lastPrintedCheckNo,
                        'uid' => $resData[0]['uid'],
                        'app_id' => $resData[0]['app_id'],
                        'transaction_code' => '320',
                        'issue_date' => $this->lib->getTimeGMT(),
                        'check_amount' => $resData[0]['check_amount'],
                        'additional_data' => $resData[0]['additional_data'],

                        'status' => $resData[0]['status'],
                        'action_date' => $this->lib->getTimeGMT(),
                        're_print_check_id' => $resData[0]['check_id'],
                        'author_id' => $this->author->objlogin->uid,

                );
                $this->db->insert("app_check", $dataRePrint);
                $lastCheckId = $this->db->insert_id();

            }

            $sql = "update  app_check set transaction_code = '430', re_print = 'Yes' , re_print_check_id = '".$lastCheckId."' where app_id = '" . $appid . "' AND transaction_code = '320' AND re_print IS NULL AND check_no = '".$resData[0]['check_no']."'";
            $this->db->query($sql);

            //$sql2 = "update  new_app set status = 3 where app_id = '" . $appid . "'";
            //$this->db->query($sql2);

            echo json_encode('<div class="col-md-12 tempmessage"><div class="alert alert-success"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Selected Check Voided & re-printed Successfully.</strong></div></div> <br><br>');
            // exit;
        }else{
            echo json_encode('<div class="col-md-12 tempmessage"><div class="alert alert-warning"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Time Out, You can\'t  Void or re-print this check. Contact with Admin.</strong></div></div> <br>');
        }
    }



    public function makePaymentAsVoid(){

        $appid =  $_GET["id"];

        $currenttime = $this->lib->getTimeGMT();

        $sql = "select na.*, u.name, u.firstname, u.lastname, a.*, e.company_name, FROM_UNIXTIME(na.create_date) as  create_date1 from new_app na, master_ero e, users u, new_applicent a  where a.applicent_id = na.applicent_id AND na.status = '4' AND u.uid = na.author_id AND e.uid = na.uid ORDER BY na.app_id DESC";

        $res = $this->db->query($sql);
        $row = $res->result_array();


        $issuetime = $row[0]["direct_deposit_time"];  // integer

        $timeAfterOneHour = $issuetime+60*60; // next one hours integer


        if($currenttime >= $issuetime && $currenttime <= $timeAfterOneHour){
           // $sql = "update  app_check set transaction_code = '430' where app_id = '".$appid."' AND transaction_code = '320' AND re_print IS NULL";
           // $this->db->query($sql);

            $sql2 = "update  new_app set status = 5, direct_deposit_time = ".$currenttime." where app_id = '".$appid."'";
            $this->db->query($sql2);

           // echo json_encode('<div class="col-md-12 tempmessage"><div class="alert alert-success"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Selected Application Voided Successfully.</strong></div></div> <br>');
            echo json_encode('Selected Application Voided Successfully.');
        }else{
            echo json_encode('Time Out, You can\'t  Void this Application.');
           // echo json_encode('<div class="col-md-12 tempmessage"><div class="alert alert-warning"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Time Out, You can\'t  Void this Application.</strong></div></div> <br>');
        }


        //      echo json_encode('<div class="col-md-12 "><div class="alert alert-success"><i class="icon-print"></i> &nbsp; &nbsp;  STATUS: <strong id="app_status">Selected Check Voided Successfully.</strong></div></div> <br><br>');
        // exit;
    }


    public function updateApplicationInfo(){
    	if( isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes' ){
    		echo json_encode($this->m_clientcenter->updateApplicationInfo());
    		exit;
    	}
    }


    public function viewcheck(){
        $data = array('title_page'=>"View Check");
        //$this->system->parse("clientCenter/viewpdf.htm",$data);
        $this->system->parse_templace('clientCenter/viewpdf.htm', $data);
    }
}
    
?>
