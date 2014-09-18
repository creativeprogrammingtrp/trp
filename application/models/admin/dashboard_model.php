<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard_model extends CI_Model {
        
    var $current_month = 0;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('date');
    }
    
    // :::: Start Users :::: //
    public function countTotalUsers() {
        $this->db->where_not_in('uid', 1);
        $this->db->from('users');
        return $this->db->count_all_results();
    }

    public function getDataUserOfPreviousEightMonths() {   
        $data = '';
        $date_month = "%m";  
        $date_year = "%Y";  
        
        $time = time();
        $current_month = mdate($date_month, $time);
        $current_year = mdate($date_year, $time);
        $m = 0;
        $y = 1;
        
        for($i = 8; $i > 0 ; $i--) {
            $month = $current_month - $i;
            $previous_month = 0;
            $previous_year = 0;
            if($month <= 0){
                $previous_month = 12 - $m;
                $previous_year = $current_year - 1;
                $m++;
            } else {
                $previous_month = $month;
                $previous_year = $current_year;
            }
            //$data .= $previous_month.'/'.$previous_year.', ';
            $data .= $this->countTotalUsersOfEachMonth($previous_month, $previous_year).',';
        }
        
        return $data;
    }
    
    public function countTotalUsersOfEachMonth($month, $previous_year) {
        $condition = array('year(FROM_UNIXTIME(created))' => $previous_year, 'month(FROM_UNIXTIME(created))' => $month);
        $this->db->where($condition);
        $this->db->from('users');
        return $this->db->count_all_results();
    }
    // :::: End Users :::: //
    
    // :::: Start Orders :::: //
    public function countTotalOrders() {
        $this->db->from('orders');
        return $this->db->count_all_results();
    }
    
    public function getDataOrderOfPreviousEightMonths() {   
        $data = '';
        $date_month = "%m";  
        $date_year = "%Y";  
        
        $time = time();
        $current_month = mdate($date_month, $time);
        $current_year = mdate($date_year, $time);
        $m = 0;
        $y = 1;
        
        for($i = 8; $i > 0 ; $i--) {
            $month = $current_month - $i;
            $previous_month = 0;
            $previous_year = 0;
            if($month <= 0){
                $previous_month = 12 - $m;
                $previous_year = $current_year - 1;
                $m++;
            } else {
                $previous_month = $month;
                $previous_year = $current_year;
            }
            //$data .= $previous_month.'/'.$previous_year.', ';
            $data .= $this->countTotalOrdersOfEachMonth($previous_month, $previous_year).',';
        }
        
        return $data;
    }
    
    public function countTotalOrdersOfEachMonth($month, $previous_year) {
        $condition = array('year(order_date)' => $previous_year, 'month(order_date)' => $month);
        $this->db->where($condition);
        $this->db->from('orders');
        return $this->db->count_all_results();
    }

    // :::: End Orders :::: //
    
    // :::: Start Money :::: //   
    public function countTotalMoney() {
        $this->db->select_sum('order_total', 'money');
        return $this->db->get('orders')->row()->money;        
    }
    
    public function getDataMoneyOfPreviousEightMonths() {   
        $data = '';
        $date_month = "%m";  
        $date_year = "%Y";  
        
        $time = time();
        $current_month = mdate($date_month, $time);
        $current_year = mdate($date_year, $time);
        $m = 0;
        $y = 1;
        
        for($i = 8; $i > 0 ; $i--) {
            $month = $current_month - $i;
            $previous_month = 0;
            $previous_year = 0;
            if($month <= 0){
                $previous_month = 12 - $m;
                $previous_year = $current_year - 1;
                $m++;
            } else {
                $previous_month = $month;
                $previous_year = $current_year;
            }
            //$data .= $previous_month.'/'.$previous_year.', ';
            $money_sql = $this->countTotalMoneyOfEachMonth($previous_month, $previous_year);
            if(is_null($money_sql))
                $data .= '0,';
            else 
                $data .= $money_sql . ',';
        }
        
        return $data;
    }
    
    public function countTotalMoneyOfEachMonth($month, $previous_year) {
        $condition = array('year(order_date)' => $previous_year, 'month(order_date)' => $month);
        $this->db->select_sum('order_total', 'money');
        $this->db->where($condition);
        $this->db->from('orders');
        return $this->db->get()->row()->money;
    }
    // :::: End Money :::: //    
    
    // :::: Start Vouchers :::: // 
    public function countTotalVouchers() {
        $this->db->from('voucher'); 
        return $this->db->count_all_results();
    }
    
    public function getDataVouchersOfPreviousEightMonths() {   
        $data = '';
        $date_month = "%m";  
        $date_year = "%Y";  
        
        $time = time();
        $current_month = mdate($date_month, $time);
        $current_year = mdate($date_year, $time);
        $m = 0;
        $y = 1;
        
        for($i = 8; $i > 0 ; $i--) {
            $month = $current_month - $i;
            $previous_month = 0;
            $previous_year = 0;
            if($month <= 0){
                $previous_month = 12 - $m;
                $previous_year = $current_year - 1;
                $m++;
            } else {
                $previous_month = $month;
                $previous_year = $current_year;
            }
            //$data .= $previous_month.'/'.$previous_year.', ';
            $data .= $this->countTotalVouchersOfEachMonth($previous_month, $previous_year).',';
        }
        
        return $data;
    }
    
    public function countTotalVouchersOfEachMonth($month, $previous_year) {
        $condition = array('year(orders.order_date)' => $previous_year, 'month(orders.order_date)' => $month);        
        $this->db->from('voucher');
        $this->db->join('orders', 'orders.orderid = voucher.id');
        $this->db->where($condition);
        return $this->db->count_all_results();
    }
    // :::: End Vouchers :::: //   
    
    // :::: Start Events :::: //   
    public function getAllDataEvents() {
        $this->db->select('title, content, start_date, end_date');
        $this->db->from('events');
        return $this->db->get();
    }
    // :::: End Events :::: //   
    
    // :::: Start Chart :::: // 
        // Start Users
        public function getDataUserChart($year) {
            $data = '';
            for($i = 1; $i < 13 ; $i++) {
                $data .= $this->countTotalUsersOfEachMonth($i, $year).',';
            }
            return $data;
        }
        // End Users    
         
        // Start Orders
        public function getDataOrderChart($year) {
            $data = '';
            for($i = 1; $i < 13 ; $i++) {
                $data .= $this->countTotalOrdersOfEachMonth($i, $year).',';
            }
            return $data;
        }
        // End Orders
         
        // Start Money
        public function getDataMoneyChart($year) {
            $data = '';
            for($i = 1; $i < 13 ; $i++) {
                $money_sql = $this->countTotalMoneyOfEachMonth($i, $year);
            if(is_null($money_sql))
                $data .= '0,';
            else 
                $data .= $money_sql . ',';
            }
            return $data;
        }
        // End Money
         
        // Start Voucher
        public function getDataVoucherChart($year) {
            $data = '';
            for($i = 1; $i < 13 ; $i++) {
                $data .= $this->countTotalVouchersOfEachMonth($i, $year).',';
            }
            return $data;
        }
        // End Voucher
    // :::: End Chart :::: // 
    
//    public function getMonthYear($year) {   
//        $data = '';
//        for($i = 1; $i < 13 ; $i++) {
//            $data .= $i.'-'.$year.', ';
//        }
//        
//        return $data;
//    }
}
?>