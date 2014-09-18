<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transfer_model extends CI_Model {

    var $month_sql = '';
    var $year_sql = '';

    public function __construct() {
        parent::__construct();
    }

    public function loadMoneyTransfer($page, $col, $sortby_) {
        $data_transfer = array();
        $num_per_pager = NUMROWPERPAGE;
        $limit = $num_per_pager * ($page - 1);

        $sort_type = '';
        $sort_by = 'desc';

        if ($sortby_ == 0)
            $sort_by = 'asc';

        switch ($col) {
            case 0:
                $sort_type = 'users.ukey';
                break;
            case 1:
                $sort_type = 'users.firstname';
                break;
            case 2:
                $sort_type = 'users.mail';
                break;
             case 3:
                $sort_type = 'users.address';
                break;
             case 4:
                $sort_type = 'holding_account.amount';
                break;
        }
        $query = $this->db->query("select sum(amount) as amount,ukey,firstname,lastname,mail,address,city,country,state,zipcode from users inner join holding_account on users.uid = holding_account.uid where 1=1 ".$this->month_sql.$this->year_sql." group by holding_account.uid,MONTH(date_transfer),YEAR(date_transfer) limit $limit,".$num_per_pager);
        foreach ($query->result_array() as $row) {
            $data_transfer[] = $row;
        }
        
        $maxlength = $this->database->db_result("select count(ukey) as maxlength,ukey,firstname,lastname,mail,address,city,country,state,zipcode from users inner join holding_account on users.uid = holding_account.uid  group by holding_account.uid,MONTH(date_transfer),YEAR(date_transfer)");
        return array('data' => $data_transfer,'maxlength'=>(int)$maxlength, 'page'=> (int)$page,);
    }

    public function check_valid(&$varible) {
        if (isset($varible) && trim($varible) != '') {
            return $this->lib->escape($varible);
        }
        return '';
    }

    public function set_month_sql($month) {
        if ($this->check_valid($month) != '' && is_numeric($this->check_valid($month))) {
            $this->month_sql = " and MONTH(holding_account.date_transfer) = '" . $month . "'";
        }
    }

    public function set_year_sql($year) {
        if ($this->check_valid($year) != '' && is_numeric($this->check_valid($year))) {
            $this->year_sql = " and YEAR(holding_account.date_transfer) = '" . $year . "'";
        }
    }

}

?>
