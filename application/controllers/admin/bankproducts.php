<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Bankproducts extends CI_Controller{
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/clientcenter_model", "m_clientcenter");
        $this->load->model("admin/mycompany_model", "m_com");
    }
    function perms(){
        $perms['Bank Products'] = array('index','chkprint');
        return $perms;			
    }
    public function index()
    {
        $data = array('title_page' => "BankProducts");
        $data['readytoprintCoutBadgeValue'] = $this->m_clientcenter->countReadyToPrintApplication();
        $data['countPendingFundsApplication'] = $this->m_clientcenter->countPendingFundsApplication();
        $data['countPrintedCheckApplication'] = $this->m_clientcenter->countPrintedCheckApplication();
        $data['countVoidCheckApplication'] = $this->m_clientcenter->countVoidCheckApplication();
        $data['countAllApplication'] = $this->m_clientcenter->countAllApplication();
        $data['countAllPaidApplication'] = $this->m_clientcenter->countAllPaidApplication();
        $data['countAllVoidedPaymentApplication'] = $this->m_clientcenter->countAllVoidedPaymentApplication();

        $this->system->parse("clientCenter/clientcenter.htm",$data);
    }


    public function chkprint()
    {
        $data = array();

        $data['title_page'] = "BankProducts";

        $data['readytoprintCoutBadgeValue'] = $this->m_clientcenter->countReadyToPrintApplication();
        $data['countPendingFundsApplication'] = $this->m_clientcenter->countPendingFundsApplication();
        $data['countPrintedCheckApplication'] = $this->m_clientcenter->countPrintedCheckApplication();
        $data['countVoidCheckApplication'] = $this->m_clientcenter->countVoidCheckApplication();
        $data['countAllApplication'] = $this->m_clientcenter->countAllApplication();
        $data['countAllPaidApplication'] = $this->m_clientcenter->countAllPaidApplication();
        $data['countAllVoidedPaymentApplication'] = $this->m_clientcenter->countAllVoidedPaymentApplication();

        $ids = $this->uri->segment(7);
        $ajaxvalue = $this->uri->segment(5);
        $checkNo = $this->uri->segment(8);

        //if ($ajax == 'print' && $ajaxvalue == 1) {
        //if (isset($_GET['print']) && $_GET['print'] == 1) {
        if ($ajaxvalue == 1 && $ids != '') {

            $this->load->helper('file');
            $this->load->helper(array('dompdf', 'file'));
            //  $dompdf = $this->load->helper('dompdf');

            if($this->author->objlogin->parentUid > 0){
                $parrentUid = $this->author->objlogin->parentUid;
            }
            else{
                $parrentUid = $this->author->objlogin->uid;
            }

            //$startingCheckNo = $_GET['startp'];

            $assignChecksNo = $this->m_clientcenter->loadUncompletedAssignCheckRange();
/*
            $lastPrintedCheckNo = $this->m_clientcenter->loadLastPrintedCheckNo();

            if($lastPrintedCheckNo != ''){
                $lastPrintedCheckNo = intval($lastPrintedCheckNo)+1;
            }else{
                $lastPrintedCheckNo = intval($assignChecksNo['starting_no']);
            }
*/
            //if($_GET['ids'] != ''){
            if($ids != ''){

                //$data['dataLoad'] = "dataClientPrint = " . json_encode($this->m_clientcenter->loadSelectedReadyToPrintApplication($_GET['ids']));
                $dataLoad = $this->m_clientcenter->loadSelectedReadyToPrintApplication($ids);
            }else{
                $dataLoad = $this->m_clientcenter->loadAllReadyToPrintApplication();
            }
            //  $data['states'] = $this->m_com->loadStatesList();
            //  if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
                //$this->system->parse_templace('clientCenter/modal_ready_to_print_applications.htm', $data);
                //exit();

                // get last started check no
                // $i = 1;
                foreach($dataLoad as $datas) {

                    $data1 = array(
                        'check_no' => $checkNo,
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

                    $this->db->insert("app_check", $data1);
                    $lastCheckId = $this->db->insert_id();

                    $sql2 = "update  new_app set status = 2 where app_id = '" . $datas['app_id'] . "' ";
                    $this->db->query($sql2);

                    // updating inhand check no from assign_cehck table

                    $assignChecks = $this->m_clientcenter->loadUncompletedAssignCheckRange();
                    $lastInHandChecks = $assignChecks['in_hand'];
                    $currentAvilable = $lastInHandChecks - 1;

                    $sql = 'UPDATE assign_check SET in_hand = "' . $currentAvilable . '" WHERE assign_check_id = "' . $assignChecks['assign_check_id'] . '"';
                    $this->db->query($sql);

                    // $startingCheckNo = $startingCheckNo+$i;
                    // $i++;
                    // }


                    // }




                    // page info here, db calls, etc.
                    //$html = $this->load->view('controller/viewfile', $data, true);
                    //pdf_create($html, 'filename');
                    // or

                    $html = '
<style>@page {
            margin-top: 0.4em;
            margin-left: 0.4em;
        }</style>
        <div>
            <div style="width: 30.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px; height:60px;"><img src="' . base_url() . 'img/logo_s.png" border="0" /></div>
            <div style="width: 30.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">WELLS FARGO</div>
            <div style="text-align: center; width: 10.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><u>12-123</u><br>6789</div>
            <div style="text-align: right; width: 10.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"> <div>' . $checkNo . '</div></div>

            <div class="clear1" style="margin-bottom: 30px; clear: both;"></div>
            <div class="col-md-1 border1" style="width: 8.33333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">PAY<br>TO THE<br> ORDER <br> OF</div>
            <div class="col-md-7" style="width: 50.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="border-bottom: 1px solid;  margin-top: 50px;">'.$datas['first_name'].' '.$datas['last_name'].'</div></div>
            <div class="col-md-2 border1" style=" margin-top: 20px; width: 10.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">DATE
            <BR>
                <div style="border-bottom: 1px solid;  margin-top: 15px;">'.gmdate("d/m/Y").'</div>
            </div>
            <div class="col-md-2 border1" style="margin-top: 20px; width: 13.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">AMOUNT <br> <div class="border" style=" border:1px solid; font-weight: bold; margin-top: 3px; padding: 6px;"> $'.number_format($datas['app_actual_refund_amount'],2).'</div> </div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-1 border1"  style=" margin-top: 55px; width: 8.33333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><br><br><br><br>MEMO</div>
            <div class="col-md-7" style="width: 50.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="border-bottom: 1px solid;  margin-top: 60px;">&nbsp;</div></div>
            <div class="col-md-4 border1" style=" margin-top: 75px; width: 30.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;">
                <div style="border-bottom: 1px solid;"></div>
            </div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-8" style="width: 62.6667%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div>&nbsp;</div></div>
            <div class="col-md-4" style="width: 33.3333%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style=" margin-top: 5px; text-align: center;">AUTHORIZED SIGNATURE</div></div>
            <div class="clear1" style="clear: both;"></div>

            <div class="col-md-12" style="width: 100%; float: left; min-height: 1px; padding-left: 15px; padding-right: 15px;"><div style="borderbottom: 1px solid;  margin-top: 0px; text-align: center"><br> ." '.$checkNo.' :. 5284810933 :. 111900659 ".</div></div>
            <div class="clear1" style="clear: both;"></div>

<br>
            <div style="border-top:1px dotted #999999;">&nbsp;</div>

        <div>
            <table  width="100%">
                <tr>
                    <td width="40%">SCALE FINANCIAL</td>
                    <td width="60%" style="text-align:right">' . $checkNo . '</td>
                </tr>
                <tr>
                    <td width="40%" valign="top"  style="font-size:10px;">'.$datas['first_name'].' '.$datas['last_name'].' <br> '.$datas['street_address_1'].' <br> '.$datas['city'].', '.$datas['state'].', '.$datas['zip_code'].' <br> '.$datas['cell_phone'].'</td>
                    <td width="60%">
                        <table width="100%">
                            <tr>
                                <td width="50%" ></td>
                                <td width="25%" >ADDED</td>
                                <td width="25%" >ACTUAL</td>
                            </tr>

                            <tr>
                                <td>REFUND AMOUNT</td>
                                <td>'.$datas['app_total_refund_amt'].'</td>
                                <td>'.$datas['deposit_amount'].'</td>
                            </tr>

                            <tr>
                                <td  style="font-size:10px;">Tax Preparation</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_tax_preparation_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Bank Transmission</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_bank_transmission_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">SB Fee</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_sb_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Add On Fee</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_add_on_fee'].'</td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;">Audit Guard</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['actual_audit_guard_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Benefits</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_benefit'].'</td>
                            </tr>

                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Refund Amount</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_refund_amount'].'</td>
                            </tr>


                        </table>
                    </td>
                </tr>
            </table>
        </div>

<br>
    <div style="border-top:1px dotted #999999;">&nbsp;</div>
        <div>
            <table width="100%">
                <tr>
                    <td width="40%">SCALE FINANCIAL</td>
                    <td width="60%" style="text-align:right">' . $checkNo . '</td>
                </tr>
                <tr>
                    <td width="40%"  valign="top" style="font-size:10px;">'.$datas['first_name'].' '.$datas['last_name'].' <br> '.$datas['street_address_1'].' <br> '.$datas['city'].', '.$datas['state'].', '.$datas['zip_code'].' <br> '.$datas['cell_phone'].'</td>
                    <td width="60%">
                        <table width="100%">
                            <tr>
                                <td width="50%" ></td>
                                <td width="25%" >ADDED</td>
                                <td width="25%" >ACTUAL</td>
                            </tr>

                            <tr>
                                <td>REFUND AMOUNT</td>
                                <td>'.$datas['app_total_refund_amt'].'</td>
                                <td>'.$datas['deposit_amount'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Tax Preparation</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_tax_preparation_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Bank Transmission</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_bank_transmission_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">SB Fee</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_sb_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Add On Fee</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_add_on_fee'].'</td>
                            </tr>

                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Audit Guard</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['actual_audit_guard_fee'].'</td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;">Benefits</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_benefit'].'</td>
                            </tr>

                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Refund Amount</td>
                                <td>&nbsp;</td>
                                <td>'.$datas['app_actual_refund_amount'].'</td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </div>
        </div>
        ';
                    //$data = pdf_create($html, '', false);
                    pdf_create($html, 'pdfgeneratedtest');

                }
        }

        $this->system->parse("clientCenter/clientcenter.htm",$data);

        //if (isset($_GET['print']) && $_GET['print'] == 1) {
        if ($ajaxvalue == 1 && $ids != '') {
            echo '<script type="text/javascript" language="javascript">
                             window.open("http://scalefinancial.com/printitem/pdfgeneratedtest.pdf", "_blank");
                              // window.open("http://localhost/trpgit/printitem/pdfgeneratedtest.pdf", "_blank");
                        </script>';
        }
    }
    
    

}
    
?>
