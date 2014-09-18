<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Dashboard_model');
        $this->load->model("admin/clientcenter_model", "m_clientcenter");
        $this->load->model("admin/mycompany_model", "m_com");
        
    }

    public function perms() {
        $perms['Dashboard'] = array('index', 'getAllEvents', 'getDataUserChart');

        return $perms;
    }
/*
    public function index() {
        // Users
        $total_users = $this->Dashboard_model->countTotalUsers();
        $str_users = $this->Dashboard_model->getDataUserOfPreviousEightMonths();
        $str_users_length = strlen($str_users);
        $data_users = substr($str_users, 0, $str_users_length - 1);

        // Orders
        $total_orders = $this->Dashboard_model->countTotalOrders();
        $str_orders = $this->Dashboard_model->getDataOrderOfPreviousEightMonths();
        $str_orders_length = strlen($str_orders);
        $data_orders = substr($str_orders, 0, $str_orders_length - 1);

        // Money
        $total_money_sql = $this->Dashboard_model->countTotalMoney();
        $total_money = 0;
        if(is_null($total_money_sql))
            $total_money = 0;
        else 
            $total_money = $total_money_sql;
        
        $str_money = $this->Dashboard_model->getDataMoneyOfPreviousEightMonths();
        $str_money_length = strlen($str_money);
        $data_money = substr($str_money, 0, $str_money_length - 1);

        // Vouchers
        $total_vouchers = $this->Dashboard_model->countTotalVouchers();
        $str_vouchers = $this->Dashboard_model->getDataVouchersOfPreviousEightMonths();
        $str_vouchers_length = strlen($str_vouchers);
        $data_vouchers = substr($str_vouchers, 0, $str_vouchers_length - 1);

        // Events
        // :::::::: //
        $data['title_page'] = 'Dashboard';

        // Users
        $data['total_users'] = $total_users;
        $data['data_users'] = $data_users;

        // Orders
        $data['total_orders'] = $total_orders;
        $data['data_orders'] = $data_orders;

        // Money
        $data['total_money'] = $total_money;
        $data['data_money'] = $data_money;

        // Vouchers
        $data['total_vouchers'] = $total_vouchers;
        $data['data_vouchers'] = $data_vouchers;

        $this->system->parse("dashboard.htm", $data);
    }

//end index function
*/
    
    public function index(){
    	$data = array();
    	$data['title_page'] = 'Dashboard';
    	
    	$data['dataLoad'] = "dataClient = " . json_encode($this->m_clientcenter->loadLastFiveRecentApplication());
    	$data['dataLoad1'] = "dataClientLastFiveReadyToPrint = " . json_encode($this->m_clientcenter->loadLastFiveReadyToPrintApplication());
    	$data['totalreadytoprintapp'] = $this->m_clientcenter->countReadyToPrintApplication();
    	$data['totalrecentapp'] = $this->m_clientcenter->countRecentApplication();
    	$data['totalPendingapp'] = $this->m_clientcenter->countPendingFundsApplication();
    	
    	$data['states'] = $this->m_com->loadStatesList();
    	$this->system->parse("dashboard.htm", $data);
    }
    public function getAllEvents() {
        $all_events = $this->Dashboard_model->getAllDataEvents();
        foreach ($all_events->result() as $event) {
            $arr_data[] = array(
                'title' => $event->title,
                'start' => $event->start_date,
                'end' => $event->end_date
            );
        }
        echo json_encode($arr_data);
    }

    public function getDataUserChart() {
        $year = $_POST['year'];
        
//        $str_month_year = $this->Dashboard_model->getMonthYear($year);
        // User
        $str_users = $this->Dashboard_model->getDataUserChart($year);        
        // Order
        $str_orders = $this->Dashboard_model->getDataOrderChart($year);
        // Money
        $str_money = $this->Dashboard_model->getDataMoneyChart($year);
        // Voucher
        $str_voucher = $this->Dashboard_model->getDataVoucherChart($year);
        
        $data = '{"data_user_chart": "' . $str_users . '"'
                . ', "data_order_chart": "' . $str_orders . '"'
                . ', "data_money_chart": "' . $str_money . '"'
                . ', "data_voucher_chart": "' . $str_voucher . '"'
                . '}';
        echo json_encode($data);
    }
    
    

}

?>