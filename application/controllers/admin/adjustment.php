<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Adjustment extends CI_Controller{
    public  function __construct() {
        parent::__construct();
     //   $this->load->model("admin/ero_model","ero");
     //   $this->load->model("admin/mycompany_model", "m_com");
        $this->load->model("admin/clientcenter_model", "m_clientcenter");
    }
    
    function perms(){
        $perms['Bank Adjustment'] = array('index','ShowPenddingApprove','generateFile','download','generateExcel','generateACHFile');
        return $perms;			
    }
    
  
    
    public function index()
    {
        $this->load->helper('file');
        $data = array('title_page' => "Bank Adjustment");
        $html = '';
        $result = array();
        //$message = '';
        $data['style1'] = 'display:none;';
        if (isset($_POST['submit'])) {
            $hidendata = json_decode($_POST['hiddenresult']);
            /*echo sizeof($hidendata);
            echo '<pre>';
            print_r($hidendata);
            echo '</pre>';*/


            $s = 0;
            foreach ($hidendata as $datar) {

                $fullAppData = $this->m_clientcenter->checkAccountNo($datar->w_account_no);

                if (sizeof($fullAppData) <= 0) {
                    // insert into Queu file

                    $data6 = array(
                        'w_application' => $datar->w_application,
                        'w_group_number' => $datar->w_group_number,
                        'w_process_date' => $datar->w_process_date,
                        'w_group_name' => $datar->w_group_name,
                        'w_tran' => $datar->w_tran,
                        'w_account_no' => $datar->w_account_no,
                        'w_amount' => $datar->w_amount,
                        'w_entry_desc' => $datar->w_entry_desc,
                        'w_spin' => $datar->w_spin,
                        'w_org' => $datar->w_org,
                        'w_individual_id' => $datar->w_individual_id,
                        'w_individual_name' => $datar->w_individual_name,
                        'w_descr_date' => '',
                        'w_ccls' => $datar->w_ccls,
                        'w_company_id' => $datar->w_company_id,
                        'w_company_name' => $datar->w_company_name,
                        'w_discr_date' => '',
                        'w_trace' => $datar->w_trace
                    );

                    $this->db->insert("transjournal_queu_account", $data6);

                } else {
                    // update founded account no
                    $bankingFees = floatval($fullAppData['app_tax_preparation_fee']) + floatval($fullAppData['app_bank_transmission_fee']) + floatval($fullAppData['app_sb_fee']) + floatval($fullAppData['app_add_on_fee']);
                    $productFees = floatval($fullAppData['app_benefit']);
                    $amount = str_replace(',', '', $datar->w_amount);

                    $actualRefundAmount = floatval($amount) - (floatval($bankingFees) + floatval($productFees));

                    $data5 = array(
                        'w_application' => $datar->w_application,
                        'w_group_number' => $datar->w_group_number,
                        'w_process_date' => $datar->w_process_date,
                        'w_group_name' => $datar->w_group_name,
                        'w_tran' => $datar->w_tran,
                        'w_account_no' => $datar->w_account_no,
                        'w_amount' => $datar->w_amount,
                        'w_entry_desc' => $datar->w_entry_desc,
                        'w_spin' => $datar->w_spin,
                        'w_org' => $datar->w_org,
                        'w_individual_id' => $datar->w_individual_id,
                        'w_individual_name' => $datar->w_individual_name,
                        'w_descr_date' => '',
                        'w_ccls' => $datar->w_ccls,
                        'w_company_id' => $datar->w_company_id,
                        'w_company_name' => $datar->w_company_name,
                        'w_discr_date' => '',
                        'w_trace' => $datar->w_trace,
                        'imp_file_name' => $datar->imp_file_name,

                        'app_actual_tax_preparation_fee' => $fullAppData['app_tax_preparation_fee'],
                        'app_actual_bank_transmission_fee' => $fullAppData['app_bank_transmission_fee'],
                        'app_actual_sb_fee' => $fullAppData['app_sb_fee'],
                        'app_actual_add_on_fee' => $fullAppData['app_add_on_fee'],
                        'actual_audit_guard_fee' => $fullAppData['audit_guard_fee'],
                        'deposit_amount' => $amount,
                        'posted_date' => $this->lib->getTimeGMT(),
                        'status' => '1',
                        'app_actual_refund_amount' => $actualRefundAmount
                    );

                    $this->db->where('assign_acc_no', $datar->w_account_no);
                    $this->db->update('new_app', $data5);
                    $afftectedRows = $this->db->affected_rows();
                    //$s++;

                } //

                $data['message1'] = htmlspecialchars('Your Data Imported Successfully.');
                $data['style1'] = 'display:block;';

            } // end of foreach

            $getItemsAcc = $_POST['itemsAcc'];
            if($getItemsAcc != ''){
            // for add old trans selected account info
            foreach ($_POST['itemsAcc'] as $selected) {

                // get old trans journal file info by id
                $fullTransAppData = $this->m_clientcenter->loadSelectedQueTransJournalAccountInfo($selected);

                // print_r($fullTransAppData);
                foreach ($fullTransAppData as $fTransData) {

                    $fullAppData1 = $this->m_clientcenter->checkAccountNo($fTransData['w_account_no']);

                    if (sizeof($fullAppData1) > 0) {
                        // update founded account no
                        $bankingFees = floatval($fullAppData1['app_tax_preparation_fee']) + floatval($fullAppData1['app_bank_transmission_fee']) + floatval($fullAppData1['app_sb_fee']) + floatval($fullAppData1['app_add_on_fee']);
                        $productFees = floatval($fullAppData1['app_benefit']);
                        $amount = str_replace(',', '', $fTransData->w_amount);

                        $actualRefundAmount = floatval($amount) - (floatval($bankingFees) + floatval($productFees));

                        $data7 = array(
                            'w_application' => $fTransData['w_application'],
                            'w_group_number' => $fTransData['w_group_number'],
                            'w_process_date' => $fTransData['w_process_date'],
                            'w_group_name' => $fTransData['w_group_name'],
                            'w_tran' => $fTransData['w_tran'],
                            'w_account_no' => $fTransData['w_account_no'],
                            'w_amount' => $fTransData['w_amount'],
                            'w_entry_desc' => $fTransData['w_entry_desc'],
                            'w_spin' => $fTransData['w_spin'],
                            'w_org' => $fTransData['w_org'],
                            'w_individual_id' => $fTransData['w_individual_id'],
                            'w_individual_name' => $fTransData['w_individual_name'],
                            'w_descr_date' => '',
                            'w_ccls' => $fTransData->w_ccls,
                            'w_company_id' => $fTransData['w_company_id'],
                            'w_company_name' => $fTransData['w_company_name'],
                            'w_discr_date' => '',
                            'w_trace' => $fTransData['w_trace'],
                            'imp_file_name' => $fTransData['imp_file_name'],

                            'app_actual_tax_preparation_fee' => $fullAppData1['app_tax_preparation_fee'],
                            'app_actual_bank_transmission_fee' => $fullAppData1['app_bank_transmission_fee'],
                            'app_actual_sb_fee' => $fullAppData1['app_sb_fee'],
                            'app_actual_add_on_fee' => $fullAppData1['app_add_on_fee'],
                            'actual_audit_guard_fee' => $fullAppData1['audit_guard_fee'],
                            'deposit_amount' => $amount,
                            'posted_date' => $this->lib->getTimeGMT(),
                            'status' => '1',
                            'app_actual_refund_amount' => $actualRefundAmount
                        );

                        $this->db->where('assign_acc_no', $fTransData->w_account_no);
                        $this->db->update('new_app', $data7);


                        // delete item from transjournal queu section
                        if ($this->db->affected_rows() > 0) {
                            $this->db->where('transjournal_queu_id', $selected);
                            $this->db->delete('transjournal_queu_account');

                            $data['message1'] = htmlspecialchars('Your Data Imported Successfully form Queue List.');
                            $data['style1'] = 'display:block;';
                        } else {
                            //  return false; // your code
                            $data['message1'] = htmlspecialchars('All Account information is not Imported form Queue List.');
                            $data['style1'] = 'display:block;';
                        }
                    } else {
                        $data['message1'] = htmlspecialchars('Selected Account info is not Exist or Already adjust with our system.');
                        $data['style1'] = 'display:block;';
                    }


                }


            }
        }


            // rename("/path/to/uploaded-file.txt", "/path/to/new-filename.txt");
        }


        //if($_FILES['uploadFile']['tmp_name'] != ''){
        //if($submit == 'Submit'){
        if (isset($_POST['upload'])) {
            //$file = fopen($_FILES['uploadFile']['tmp_name'], 'rb');
            if (isset($_FILES['uploadFile']["name"]) && !empty($_FILES['uploadFile']["name"])) {
                $file = fopen($_FILES['uploadFile']['tmp_name'], "r") or exit("Unable to open file!");

                $filename = $_FILES['uploadFile']['name'];

                $cehckFileName = $this->m_clientcenter->checkImportedFile($filename);

                if (!$cehckFileName) {

                    // reading & storing all record into array line by line.
                    while (!feof($file)) {
                        //echo $i.'<br>';

                        //echo fgets($file)."<br>";
                        $line = fgets($file);
                        $lineArray[] = $line;
                    }

                    // remove last blank line from text file.
                    if (end($lineArray) == "") {
                        array_pop($lineArray);
                    }

                    //	echo '<pre>';
                    // 	print_r($lineArray);
                    // 	echo '</pre>';


                    // assigning global variable.
                    $i = 0;

                    $application = '';
                    $groupnumber = '';
                    $groupname = '';
                    $processdata = '';

                    $trans = '';
                    $accountno = '';
                    $amount = '';
                    $entrydesc = '';
                    $spin = '';

                    $org = '';
                    $individualid = '';
                    $individualname = '';
                    $ccls = '';

                    $companyid = '';
                    $companyname = '';
                    $trace = '';

                    $flag = 0;
                    $totalupdatedRow = 0;
                    $state = 0;

                    //echo '<pre>';

                    $html .= '<table width="100%" class="table table-striped">';

                    //	while(! feof($file))
                    if (strpos($lineArray[2], 'APPLICATION...:') !== false) {
                        foreach ($lineArray as $line) {

                            // expload each line for make array for read individual section.
                            $eachline = explode('  ', $line);

                            //print_r($eachline);
                            /*if (strpos($eachline[0], 'SPIN..........:') !== false){
                                $spinall = explode(' ', $eachline[0]);
                                echo 'Spin: '.$spinno = $spinall[1].'<br>';
                               }*/

                            // this part will be same for all transaction with single file

                            if (strpos($eachline[0], 'APPLICATION...:') !== false) {
                                $applicationall = explode(' ', $eachline[0]);
                                //echo 'APPLICATION: '.$application = trim($applicationall[1]);
                                $application = trim($applicationall[1]);
                                //echo '<br>';
                                $html .= '<tr>
       			<td colspan="7">APPLICATION...: ' . $application . '</td>
       			</tr>';
                            }


                            if (strpos($eachline[0], 'GROUP NUMBER..:') !== false) {
                                $groupnumberall = explode(' ', $eachline[0]);
                                //echo 'GROUP NUMBER: '.$groupnumber = trim($groupnumberall[2]);
                                $groupnumber = trim($groupnumberall[2]);
                                //echo '<br>';
                                $processdataall = explode(' ', $eachline[18]);
                                //echo 'Process Date: '.$processdata = trim($processdataall[3]);
                                $processdata = trim($processdataall[3]);
                                //echo '<br>';

                                $html .= '<tr>
       			<td colspan="7">GROUP NUMBER..: ' . $groupnumber . '</td>
       			</tr>';

                            }

                            if (strpos($eachline[0], 'NAME') !== false) {
                                //echo 'GROUP NAME: '.$groupname = trim(substr($eachline[0], 15));
                                $groupname = trim(substr($eachline[0], 15));
                                //echo '<br>';
                                $html .= '<tr>
       			<td colspan="7">GROUP NAME....: ' . $groupname . '</td>
       			</tr>';
                                $html .= '

<tr><th></th>
    	<th>ACCOUNT NUMBER</th>
    	<th>NAME</th>
    	<th>SPIN</th>
    	<th>PROCESS DATE</th>
    	<th>TRANS</th>
    	<th>AMOUNT</th>
    	</tr>';

                            }
                            // End - this part will be same for all transaction with single file

                            // this part will be repeted depend on how meny record is exist


                            if ($state == 0) {


                            }

                            $state++;


                            // for get transaction info
                            if (strpos($eachline[0], 'CR') !== false || strpos($eachline[0], 'DR') !== false) {

                                $flag = 1;

                                $line = preg_replace('/\s+/', ' ', $line); // replace all whitespace
                                $eachline1 = explode(' ', $line);
                                //echo 'TRAN: '.$trans = $eachline1[0];
                                $trans = $eachline1[0];
                                //echo '<br>';
                                //echo 'ACCOUNT NO: '.$accountno = trim($eachline1[1]);
                                $accountno = trim($eachline1[1]);
                                //echo '<br>';
                                //echo 'AMOUNT: '.$amount = trim($eachline1[2]);
                                $amount1 = trim($eachline1[2]);
                                $amount = preg_replace('/,/', '', $amount1); // replace all , sighn
                                //echo '<br>';
                                //echo 'ENTRY: '.$entrydesc = trim($eachline1[3]);
                                $entrydesc = trim($eachline1[3]);
                                //echo '<br>';
                                //echo 'SPIN: '.$spin = trim($eachline1[4]);
                                $spin = trim($eachline1[4]);
                                //echo '<br>';

                            }

                            // for get individual id
                            if (strpos($eachline[0], '1') !== false && strlen($eachline[0]) == 1) {

                                $flag = 2;

                                $eachline1 = explode('  ', $line);

                                //echo 'ORG: '.$org = trim($eachline1[0]);
                                $org = trim($eachline1[0]);
                                //echo '<br>';
                                //echo 'INDIVIDUAL ID: '.$individualid = trim($eachline1[2]);
                                $individualid = trim($eachline1[2]);
                                //echo '<br>';
                                //echo 'INDIVIDUAL NAME: '.$individualname = trim($eachline1[7]);
                                $individualname = trim($eachline1[7]);
                                //echo '<br>';
                                //echo 'CCLS: '.$ccls = trim($eachline1[26]);
                                $ccls = trim($eachline1[26]);
                                //echo '<br>';
                            }

                            // for get company info
                            if (preg_match('/[0-9]/i', $eachline[2]) && $eachline[0] == '' && $eachline[2] != '') {
                                $flag = 3;

                                $eachline1 = explode('  ', $line);
                                //print_r($eachline1);
                                //echo 'COMPANY ID: '.$companyid = trim($eachline1[2]);
                                $companyid = trim($eachline1[2]);
                                //echo '<br>';
                                //echo 'COMPANY NAME: '.$companyname = trim($eachline1[6]);
                                $companyname = trim($eachline1[6]);
                                //echo '<br>';
                                //echo 'TRACE: '.$trace = trim($eachline1[20]);
                                $trace = trim($eachline1[20]);
                                //echo '<br>';
                            }


                            if ($flag == 3) {

                                $data1 = array(
                                    'w_application' => $application,
                                    'w_group_number' => $groupnumber,
                                    'w_process_date' => $processdata,
                                    'w_group_name' => $groupname,
                                    'w_tran' => $trans,
                                    'w_account_no' => $accountno,
                                    'w_amount' => $amount,
                                    'w_entry_desc' => $entrydesc,
                                    'w_spin' => $spin,
                                    'w_org' => $org,
                                    'w_individual_id' => $individualid,
                                    'w_individual_name' => $individualname,
                                    'w_descr_date' => '',
                                    'w_ccls' => $ccls,
                                    'w_company_id' => $companyid,
                                    'w_company_name' => $companyname,
                                    'w_discr_date' => '',
                                    'w_trace' => $trace,
                                    'imp_file_name' => $filename
                                );


                                $result[] = $data1;


                                //$this->db->where('assign_acc_no', $accountno);
                                //$this->db->update('new_app', $data);
                                //$afftectedRows = $this->db->affected_rows();

                                $checkAccount = $this->m_clientcenter->checkAccountNo($accountno);

                                $html .= '<tr>
       					<td><input type="checkbox" name="items[]"';
                                if (sizeof($checkAccount) <= 0) {
                                    $html .= 'disabled="disabled"';
                                } else {
                                    $html .= 'checked="checked" disabled="disabled"';
                                }
                                $html .= '></td>
	       				<td>' . $accountno . '</td>
	       				<td>' . $individualname . '</td>
	       				<td>' . $spin . '</td>
	       				<td>' . $processdata . '</td>
	       				<td>' . $trans . '</td>
	       				<td style="text-align:right;">' . $amount . '</td>
       				</tr>';

                                //if($afftectedRows != 0){
                                //$totalupdatedRow = $afftectedRows+1;
                                ///}
                                //$afftectedRows=$this->db->affected_rows();
                                //echo "<pre>";
                                //echo $afftectedRows;
                                //echo "</pre>";
                                $trans = '';
                                $accountno = '';
                                $amount = '';
                                $entrydesc = '';
                                $spin = '';

                                $org = '';
                                $individualid = '';
                                $individualname = '';
                                $ccls = '';

                                $companyid = '';
                                $companyname = '';
                                $trace = '';

                                $flag = 0;

                                //if($updatedata)
                                //echo "Data saved";
                            }


                            //print_r($eachline);
                            /*
                            if($i == 10){
                                //echo "it's position 11.";
                                $lin = $line;
                                echo $line;
                                $line = preg_replace('/\s+/', ' ',$line);
                                $eachline = explode(' ', $line);
                                print_r($eachline);
                                /*
                                echo $record_type = 'Record type: '.substr($line, 1, 1)."<br>";
                                if ($record_type == '1'){ 	// A - File header (type 1) record.
                                    echo $priority_code = 'Priority code: '.substr($line, 2, 2)."<br>";
                                    echo $group_number = 'Group number: '.substr($line, 4, 10)."<br>";
                                    echo $wells_fargo_r_t_number = 'Wells Fargo R/T number: '.substr($line, 14, 10)."<br>";
                                    echo $file_creation_date = 'File creation date: '.substr($line, 24, 6)."<br>";
                                    echo $file_creation_time = 'File creation time: '.substr($line, 30, 4)."<br>";
                                    echo $file_id_modifier = 'File ID modifier: '.substr($line, 34, 1)."<br>";
                                    echo $record_size = 'Record size: '.substr($line, 35, 3)."<br>";
                                    echo $blocking_factor = 'Blocking factor: '.substr($line, 38, 2)."<br>";
                                    echo $format_code = 'Format code: '.substr($line, 40, 1)."<br>";
                                    echo $company_name = 'Company name: '.substr($line, 41, 23)."<br>";
                                    echo $origination_bank = 'Origination bank: '.substr($line, 64, 23)."<br>";
                                    echo $reference_code = 'Reference code: '.substr($line, 87, 8)."<br>";
                                }

                                if ($record_type == '5'){ 	// B - Company/batch header (type 5) record.
                                }
                                if ($record_type == '6'){ 	// C - Entry detail (type 6) record.
                                }
                                if ($record_type == '7'){ 	// D - Addenda records.
                                }
                                if ($record_type == '8'){ 	// E - Company/batch control (type 8) record.
                                }
                                if ($record_type == '6'){ 	// D - Addenda records.
                                }
                                if ($record_type == '6'){ 	// D - Addenda records.
                                }
                                */
                            /*}
                            if($i == 11){
                                //echo "it's position 11.";
                                $lin = $line;
                                echo $line;

                                $line = preg_replace('/\s+/', ' ',$line);
                                $eachline = explode(' ', $line);
                                print_r($eachline);
                            }
                            if($i == 14){
                                //echo "it's position 11.";
                                $lin = $line;
                                echo $line;

                                $line = preg_replace('/\s+/', ' ',$line);
                                $eachline = explode(' ', $line);
                                print_r($eachline);
                            }
                            if($i == 26){
                                //echo "it's position 11.";
                                //$lin = $line;
                                echo $line;

                                $line = preg_replace('/\s+/', ' ',$line);

                                $eachline = explode(' ', $line);
                                print_r($eachline);
                            }*/


                            $i++;
                        }
                    } else {

                        $data['message1'] = htmlspecialchars('Please choose correct data file. Application # not found.');
                        $data['style1'] = 'display:block;';
                        //break;

                    }
                } else {
                    $data['message1'] = htmlspecialchars('This File already imported. Please try with new file.');
                    $data['style1'] = 'display:block;';
                }
                $html .= '</table>';
                //echo '</pre>';
                fclose($file);

            }

        } else {
            //$data['result'] = array();
        }

        // get Queu transjournal account info.
        $oldTransjournalFile = $this->m_clientcenter->loadOldTransJournalAccontInfo();

        $html_old = '';
        $html_old .= '<table width="100%" class="table table-striped">';
        $html_old .= '<tr><th colspan="7"> Previous Not Adjusted Accounts (You can adjust following accounts by select check box.)</th></tr>';
        $html_old .= '<tr><th></th>
    	<th>ACCOUNT NUMBER</th>
    	<th>NAME</th>
    	<th>SPIN</th>
    	<th>PROCESS DATE</th>
    	<th>TRANS</th>
    	<th>AMOUNT</th>
    	</tr>';
        foreach ($oldTransjournalFile as $oldtransAccount) {

        $html_old .= '<tr
}>
       					<td><input type="checkbox" name="itemsAcc[]" value="'.$oldtransAccount['transjournal_queu_id'].'"></td>
	       				<td>' . $oldtransAccount['w_account_no'] . '</td>
	       				<td>' . $oldtransAccount['w_individual_name'] . '</td>
	       				<td>' . $oldtransAccount['w_spin'] . '</td>
	       				<td>' . $oldtransAccount['w_process_date'] . '</td>
	       				<td>' . $oldtransAccount['w_tran'] . '</td>
	       				<td style="text-align:right;">' . $oldtransAccount['w_amount'] . '</td>
       				</tr>';
        }
        $html_old .= '</table>';
        $html_old .= '<input type="hidden" name="itemsAcc[]" value="">';

    	$data['result'] = htmlspecialchars(json_encode($result));
    	//echo htmlspecialchars($string, ENT_COMPAT,'ISO-8859-1', true);
    	$data['htmls'] = $html;
        $data['html_old'] = $html_old;

        $data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadWxportedFileList());

        $data['dataLoadACH'] = "dataClientACH = " . json_encode($this->m_clientcenter->loadACHWxportedFileList());
    	
    	//print_r($result);
    	$this->system->parse("bank/datafile.htm",$data);
    }
    
    
    public function deleteUser() {
        if (isset($_POST['delete']) && !empty($_POST['delete']) && $_POST['delete'] == 'yes') {
            echo json_encode($this->ero->deleteUser());
            exit();
        }
    }

    public function generateFile() {
        $data = array();
        $filename = 'I'.date('mdyhis');
        $myfile = fopen("./arp_inbound/".$filename.".txt", "w") or die("Unable to open file!");

        // Header Part
        $default = '*03';
        $w_bankid = '808';
        $bankid = str_pad($w_bankid, 5, "0", STR_PAD_LEFT);
        $w_accountNo = '5284810933';
        $accountNo =  str_pad($w_accountNo, 15, "0", STR_PAD_LEFT);
        $filestatus = '0';

        $fullHeader = str_pad($default.''.$bankid.''.$accountNo.''.$filestatus, 165)."\n";

        fwrite($myfile, $fullHeader);

        // body part

        $printedCheck = $this->m_clientcenter->loadAllPrintedCheckForExport();
        $totalCheckAmount = 0.00;
//print_r($printedCheck);
        foreach($printedCheck as $pcheck) {
            $w_serialno = $pcheck['check_no'];//'00001001';
            $serial_no = str_pad($w_serialno, 10, "0", STR_PAD_LEFT); // 10 digit

            $issueDate = $pcheck['formated_check_issue_date']; //'022802'; // 6 digit
//echo $issueDate;
            $w_accountno = '5284810933'; //'2000008988773'; // 15 digit
            $accountno = str_pad($w_accountno, 15, "0", STR_PAD_LEFT);

            $transactionCode = $pcheck['transaction_code']; // '320'; // 3 digit

            $w_amount = str_replace(".","",$pcheck['check_amount']); // '26420';
            //str_replace($vowels, "", "Hello World of PHP");
            $amount = str_pad($w_amount, 10, "0", STR_PAD_LEFT); // 10 digit

            $w_additional = $pcheck['w_individual_name'];  // 'MARY MOORE';
            $additioanl = str_pad($w_additional, 120); // 110 digit

            $fullbody = $serial_no . '' . $issueDate . '' . $accountno . '' . $transactionCode . '' . $amount . '' . $additioanl."\n";
            //$fullbody = $serial_no . '' . $issueDate . '' . $accountno . '' . $transactionCode . '' . $amount ."\n";

            fwrite($myfile, $fullbody);

            $totalCheckAmount = floatval($totalCheckAmount)+floatval($pcheck['check_amount']);

            // update exported time

            $this->m_clientcenter->updateExportTImeForAllPrintedCheckForExport($pcheck['check_id']);



        }
            // Trailer part

            $spaceWithandsign = str_pad('&', 15); // 15 digit

            $countedRec = sizeof($printedCheck); // 4;
            $recordCount = str_pad($countedRec, 7, "0", STR_PAD_LEFT); // 7 digit

            $blankSpace = str_pad('', 3); // 3 digit

            $w_tamount = str_replace(".","",$totalCheckAmount); //116564;
            $tamount = str_pad($w_tamount, 13, "0", STR_PAD_LEFT);// 13 digit

            $blankspace2 = str_pad('', 126);// 126 digit

            $fulltrailer = $spaceWithandsign . '' . $recordCount . '' . $blankSpace . '' . $tamount . '' . $blankspace2."\n";
        //$fulltrailer = $spaceWithandsign . '' . $recordCount . '' . $blankSpace . '' . $tamount."\n";

            fwrite($myfile, $fulltrailer);

        fclose($myfile);


        if(sizeof($printedCheck) > 0){

            // add exported file name into database for download
            $this->m_clientcenter->addNewFileInfoWithAllPrintedCheckForExport($filename);
            echo json_encode($this->m_clientcenter->loadWxportedFileList());
        }else{
            unlink("./arp_inbound/".$filename.".txt");
            echo json_encode("No new record found for generate file. Please try again when record will be available.");
        }

        //echo "test2";

       // exit();

    }

    function download(){

        $filepath = './arp_inbound/'.$this->uri->segment(4).'.txt';

        $this->load->helper('download');
        $data = file_get_contents($filepath);
        force_download($this->uri->segment(4).'.txt', $data);

    }

    function generateExcel(){
        $filename = 'I'.date('mdyhis');
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');

        $printedCheck = $this->m_clientcenter->loadAllPrintedCheckForExport();
        $totalCheckAmount = 0.00;
        //print_r($printedCheck);
       // $latter = 'A';
        $rowNumber = 1;
        $columnCount = 7;
        foreach($printedCheck as $pcheck) {
           // $w_accountno = substr($pcheck['w_account_no'],4); //'2000008988773'; // 15 digit
           // $accountno = str_pad($w_accountno, 15, "0", STR_PAD_LEFT);

            if($pcheck['transaction_code'] == '430') { $amount = 0.00; }else{ $amount = $pcheck['check_amount'];};
            if($pcheck['transaction_code'] == '620') { $issudate = 00-00-0000; }else{ $issudate = $pcheck['formated_check_issue_date_full'];};

            //set cell A1 content with some text
           // for($number = 1; $number <= 7; $number++) {
                $this->excel->getActiveSheet()->setCellValue('A'.$rowNumber, '111900659');
                $this->excel->getActiveSheet()->setCellValue('B'.$rowNumber, '5284810933');
                $this->excel->getActiveSheet()->setCellValue('C'.$rowNumber, $pcheck['check_no']);
                $this->excel->getActiveSheet()->setCellValue('D'.$rowNumber, $issudate);
                $this->excel->getActiveSheet()->setCellValue('E'.$rowNumber, $amount);
                $this->excel->getActiveSheet()->setCellValue('F'.$rowNumber, $pcheck['transaction_code']);
                $this->excel->getActiveSheet()->setCellValue('G'.$rowNumber, $pcheck['w_individual_name']);
               // $latter++;
            //}
           // $latter = 'A';
            $rowNumber++;
        }
        //change the font size
      //  $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        //make the font become bold
       // $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
       // $this->excel->getActiveSheet()->mergeCells('A1:D1');
           //set aligment to center for that merged cell (A1 to D1)
       // $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $filename=$filename.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }


    public function generateACHFile()
    {
        $data = array();
        $filename = 'AE' . date('mdyhis');
        $myfile = fopen("./arp_inbound/" . $filename . ".txt", "w") or die("Unable to open file!");

        $totalAmount = 0;

        $last_file_ID_modifier = $this->m_clientcenter->loadLastFileIDModifier();

        $totalPayAmount = 0;
        $totalDebitPayAmount = 0;
        $t8_entry_hash = 0;
        $totalEntry_hash = 0;

        // Header Part
        $t1_record_type = '1'; // Constant
        $t1_priority_code = '01'; // Constant
        $t1_Wells_fargo_r_t_no = str_pad('091000019', 10, " ", STR_PAD_LEFT); // Constant b091000019, (b=space)
        $t1_file_ID = 'EDIMN00004'; // Wells Fargo- assigned ID
        $t1_file_creation_date = date('ymd');
        $t1_file_creation_time = date('hi');

        // incremental (A, B, C...)
        if (count($last_file_ID_modifier) > 0) {
            $file_ID_modifier1 = $last_file_ID_modifier['file_ID_modifier'];
            $file_ID_modifier = $file_ID_modifier1++;
        } else {
            $file_ID_modifier = 'A';
        }

        $t1_record_size = '094'; // Constant
        $t1_blocking_factor = '10'; // Constant
        $t1_format_code = '1'; // Constant
        $t1_origination_bank = str_pad('WELLS FARGO', 23); // Constant
        $t1_company_name = str_pad('TRP SOLUTION', 23); // Constant
        $t1_reference_code = str_pad('', 8); // Constant

        $fullHeader = $t1_record_type . "" . $t1_priority_code . "" . $t1_Wells_fargo_r_t_no . "$t1_file_ID" . "$t1_file_creation_date" . "$t1_file_creation_time" . "$file_ID_modifier" . "$t1_record_size" . "$t1_blocking_factor" . "$t1_format_code" . "$t1_origination_bank" . "$t1_company_name" . "$t1_reference_code" . "\n";
        fwrite($myfile, $fullHeader);

        //===================================

        // Have to be start loop here batch wise.
        $eroList = $this->m_clientcenter->loadListOfEROForACHExport();
        $ecount = 1;
        $batchTotal = 0;
        foreach ($eroList as $erol) {
            $batchTotal++;

            $t5_record_type = "1"; // Constant
            $t5_service_class_code = "200";
            $t5_company_name = str_pad("SCALE FINANCIAL", 16);
            $t5_company_discretionary_data = str_pad("", 20);
            $t5_company_ID = "1464683532";
            $t5_SEC_code = "CCD";
            $t5_company_entry_description = str_pad("PAYOUT", 10);

            $todate = strtoupper(date('mdy'));

            $t5_company_descriptive_date = $todate;
            $t5_effective_entry_date = $todate; // need talk with Ishan
            $t5_settlement_date = str_pad("", 3); // Constant
            $t5_originator_status_code = "1"; // Constant
            $t5_wells_fargo_r_t_no = "09100001"; // Constant
            $t5_batch_number = str_pad($ecount, 7, "0", STR_PAD_LEFT); // Assigned starting from 0000001 in ascending sequence for each company/batch header record.

            $fullType5 = $t5_record_type . "" . $t5_service_class_code . "" . $t5_company_name . "" . $t5_company_discretionary_data . "" . $t5_company_ID . "" . $t5_SEC_code . "" . $t5_company_entry_description . "" . $t5_company_descriptive_date . "" . $t5_effective_entry_date . "" . $t5_settlement_date . "" . $t5_originator_status_code . "" . $t5_wells_fargo_r_t_no . "" . $t5_batch_number."\n";
            fwrite($myfile, $fullType5);

            // body part
            $dipositedApp = $this->m_clientcenter->loadAllPaidACHApplicationForExportIntoACHByERO($erol['uid']);
            $totalCheckAmount = 0.00;
            //print_r($printedCheck);
            $k = 0;
            foreach ($dipositedApp as $dipApp) {
                //1 1 1 9  0 0 6  5
                //3 7 1 3  7 1 3  7
                //3 7 1 27 0 0 18 35 =  91

                // 100 - 91 = 9

                // calculate amount that have to be pay
                $taxPay = floatval($dipApp['app_actual_tax_preparation_fee_sum']);
                $AddOnPay = floatval($dipApp['app_actual_add_on_fee_sum']);

                $taxPayCommission = floatval($dipApp['tax_pre_commission']);
                $AddOnPayCommission = floatval($dipApp['add_on_commission']);

                $taxPayCommissionType = intval($dipApp['tax_pre_commission_type']); // 1 = Fixed & 2 = percentage
                $AddOnPayCommissionType = intval($dipApp['add_on_commission_type']); // 1 = Fixed & 2 = percentage

                $txaAmount = 0;
                $AddonAmount = 0;

                if ($taxPayCommissionType == 1) {
                    $txaAmount = floatval($taxPay) - floatval($taxPayCommission);
                } else {
                    $txaAmountPer = floatval($taxPay) * intval($taxPayCommission) / 100;
                    $txaAmount = floatval($taxPay) - floatval($txaAmountPer);
                }


                if ($AddOnPayCommissionType == 1) {
                    $AddonAmount = floatval($AddOnPay) - floatval($AddOnPayCommission);
                } else {
                    $AddonAmountPer = floatval($AddOnPay) * intval($AddOnPayCommission) / 100;
                    $AddonAmount = floatval($AddOnPay) - floatval($AddonAmountPer);
                }

                $totalPayAmount = floatval($txaAmount) + floatval($AddonAmount);

                $t6_record_type = "6"; // Constant
                $t6_transaction_code = "27"; //  debit code
                $t6_receiving_DFI_r_t_number = substr($dipApp['bank_routing'], 0, -1); // have to be get first 8 digit from bank_routing
                $t6_r_t_number_check_digit = substr($dipApp['bank_routing'], -1);; // have to be get last digit from bank_routing
                $t6_receiving_DFI_account_number = substr($dipApp['bank_account'], 0, 17); // bank_account

                $w_tamount = str_replace(".","",$totalPayAmount); //116564;
                $tamount = str_pad($w_tamount, 10, "0", STR_PAD_LEFT);// 13 digit


                $t6_amount = $tamount; // w_amount

                $t6_individual_ID = str_pad($dipApp['efin'], 15, "0", STR_PAD_LEFT);

                $eroname = $dipApp['firstname']." ".$dipApp['lastname'];
                //$lastname =
                $t6_individual_name = str_pad($eroname, 22);

                $t6_discretionary_data = str_pad('', 2); // 3 digit
                $t6_Addenda_record_indicator = "0";

                $t6_trace_number = "09100001".str_pad(1, 7, "0", STR_PAD_LEFT);

                $fullType6 = $t6_record_type . "" . $t6_transaction_code . "" . $t6_receiving_DFI_r_t_number . "" . $t6_r_t_number_check_digit . "" . $t6_receiving_DFI_account_number . "" . $t6_amount . "" . $t6_individual_ID . "" . $t6_individual_name . "" . $t6_discretionary_data . "" . $t6_Addenda_record_indicator . "" . $t6_trace_number."\n";

                fwrite($myfile, $fullType6);

                $t7_record_type = "7";
                $t7_addenda_type_code = "05";
                $t7_payment_related_information = str_pad("", 80); // need to talk with
                $t7_addenda_sequence_number = "0001"; // Sequential number beginning with 0001; Resets to 0001 for the beginning of each Entry Detail record
                $t7_entry_detail_sequence_number = str_pad(1, 7, "0", STR_PAD_LEFT); // Same as the last seven digits of the trace number of the related entry detail record.

                $fulType7 = $t7_record_type . "" . $t7_addenda_type_code . "" . $t7_payment_related_information . "" . $t7_addenda_sequence_number . "" . $t7_entry_detail_sequence_number."\n";

                fwrite($myfile, $fulType7);

                $t8_record_type = "8";
                $t8_service_class_code = "200";
                $t8_entry_addenda_count = str_pad(2, 6, "0", STR_PAD_LEFT); // Total number of entry detail and addenda records in the batch. Right-justify and zero-fill the field.
                $t8_entry_hash = str_pad(substr($dipApp['bank_routing'], 0, -1), 10, "0", STR_PAD_LEFT);
                $t8_total_batch_debit_entry_dollar_amount = str_pad($w_tamount, 12, "0", STR_PAD_LEFT); // The sum of entry detail debit totals within the batch. Right-justify and zero-fill the field.
                $t8_Total_batch_credit_entry_dollar_amount = str_pad("", 12, "0"); // The sum of entry detail credit totals within the batch. Right-justify and zero-fill the field.
                $t8_company_ID = "1464683532"; // Should be the same company ID used in the company/batch header record for this batch.
                $t8_message_authentication_code = str_pad("", 19);
                $t8_blank = str_pad("", 6);
                $t8_wells_fargo_r_t_number = "09100001";
                $t8_batch_number = str_pad($ecount, 7, "0", STR_PAD_LEFT);


                $fullType8 = $t8_record_type . "" . $t8_service_class_code . "" . $t8_entry_addenda_count . "" . $t8_entry_hash . "" . $t8_total_batch_debit_entry_dollar_amount . "" . $t8_Total_batch_credit_entry_dollar_amount . "" . $t8_company_ID . "" . $t8_message_authentication_code . "" . $t8_blank . "" . $t8_wells_fargo_r_t_number . "" . $t8_batch_number."\n";

                fwrite($myfile, $fullType8);

                $totalAmount = floatval($totalAmount) + floatval($totalPayAmount);

                // update exported time

                //$this->m_clientcenter->updateACHExportTImeForAllPrintedCheckForExport($pcheck['check_id']);

                $k++;

            }
            $totalDebitPayAmount += $totalPayAmount;
            $totalEntry_hash += $t8_entry_hash;
            $ecount++;
        }

        $t9_record_type = "9"; //
        $t9_batch_count = str_pad($batchTotal, 6, "0", STR_PAD_LEFT); // Number of batches in the file. Right-justify and zero-fill the field.
        $t9_block_count = str_pad("", 8); // Total number of records in the file, divided by ten and rounded up. Right-justify and zero-fill the field. All records in the file, including this one, are included in the block count.  Example: a file with 95 records would have a block count of 000010.
        $t9_entry_addenda_record_count = ""; // otal number of entry detail and addenda records in the file. Right-justify and zero-fill the field.
        $t9_entry_hash_total = str_pad($totalEntry_hash, 6, "0", STR_PAD_LEFT);
        $t9_total_file_debit_entry_amount = str_pad($totalDebitPayAmount, 12, "0", STR_PAD_LEFT);
        $t9_total_file_credit_entry_amount = str_pad("", 12, "0", STR_PAD_LEFT);
        $t9_filler = str_pad("", 39);

        $t9_full9 = $t9_record_type."".$t9_batch_count."".$t9_block_count."".$t9_entry_addenda_record_count."".$t9_entry_hash_total."".$t9_total_file_debit_entry_amount."".$t9_total_file_credit_entry_amount."".$t9_filler."\n";

        fwrite($myfile, $t9_full9);

        fclose($myfile);


        if(sizeof($dipositedApp) > 0){

            // add exported file name into database for download
            $this->m_clientcenter->addNewFileInfoWithAllEROPaymentInfoForExport($filename);
            echo json_encode($this->m_clientcenter->loadACHWxportedFileList());
        }else{
            unlink("./arp_inbound/".$filename.".txt");
            echo json_encode("No new record found for generate file. Please try again when record will be available.");
        }

        //echo "test2";

        // exit();

    }
}
?>