<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_mywallet extends CI_Model {
    
    var $legal_business_id = '';
    var $legal_business_name = '';
    var $okey = '';
    
    public function __construct() {
        parent::__construct();
        $this->rep_key();
        $this->buildOkey();
    }


    public function loadMyWalletAmount() {
        $total_commission = 0;
        $amount_tran =  0;
        if ($this->author->objlogin->role['rid'] == Sale_Representatives)
            $repkey = $this->database->db_result("select representatives.legal_business_id from representatives join users on representatives.uid = users.uid where users.uid = " . $this->author->objlogin->uid); $total_commission = $this->loadCommissionMonthlyItems();
        $paid = $this->database->db_result("select sum(pay) from payments where role = " . Sale_Representatives . " and legal_business_id = '" . $repkey . "'", 0);
        $balance = number_format($total_commission - $paid, 2);

        $query = "select * from users where uid = " . $this->author->objlogin->uid;
        $user = $this->db->query($query);
        $row = $user->row_array();
        $data = array(
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'email' => $row['mail'],
            'address' => $row['address'] . ", " . $row['city'] . ", " . $row['state'] . ", " . $row['country'],
            'balance' => $balance,
        );
        return $data;
    }

//function loadMyWalletAmount	

    private function loadCommissionMonthlyItems() {
        $re = $this->db->query("select commission_monthly_items.commission,order_detais.id,order_detais.itemprice,order_detais.quality from commission_monthly_items join order_detais on order_detais.id = commission_monthly_items.odetail where commission_monthly_items.status = 1 and commission_monthly_items.uid = " . $this->author->objlogin->uid);
        $result = 0;
        if ($re->num_rows() > 0) {
            foreach ($re->result_array() as $row) {
                $qty_refund = $this->database->db_result("select sum(qty) from odetail_return where odetail = " . $row['id'] . " and status = 1", 0);
                $qty_buy = $row['quality'] - $qty_refund;
                if ($qty_buy < 0)
                    $qty_buy = 0;
                $itemprice = round($row["itemprice"] * $qty_buy, 2);
                $result += round($row['commission'] * $itemprice / 100, 2);
            }
        }
        return $result;
    }

    //function loadCommissionMonthlyItems
    public function saveMoney() {
        $amount = $this->input->post('amount') ? $this->input->post('amount') : '';
        $data = array(
            'uid' => $this->author->objlogin->uid,
            'amount' => $amount,
            'date_transfer' => gmdate("Y-m-d H:i:s")
        );
        $this->db->insert('holding_account', $data); 
        $id = $this->db->insert_id();
        if($id){
            $this->savePayments($amount);
        }
    }
    
     public function rep_key() {
        $re = $this->db->query("select representatives.legal_business_id,representatives.legal_business_fname,representatives.legal_business_lname from representatives join users on representatives.uid = users.uid where users.status = 1 and users.uid = " . $this->author->objlogin->uid);
        if ($re->num_rows() > 0) {
            $row = $re->row_array();
            $this->legal_business_id = $row['legal_business_id'];
            $this->legal_business_name = $row['legal_business_fname'] . ' ' . $row['legal_business_lname'];
        }
    }
    
      function savePayments($amount) {
   
            $pay_key = $this->lib->GeneralRandomKey(20);
            $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
            foreach ($re_key->result_array() as $row_key) {
                $pay_key = $this->lib->GeneralRandomKey(20);
                $re_key = $this->db->query("select id from payments where pkey = '$pay_key'");
            }
            $datas = array(
                'pkey' => $pay_key,
                'role' => 9,
                'legal_business_id' => $this->legal_business_id,
                'legal_business_name' => $this->legal_business_name,
                'pay' => $amount,
                'date_pay' => date("Y-m-d H:i:s", $this->lib->getTimeGMT()),
                'pay_type' => 1
            );
            $id = $this->db->insert("payments", $datas);
            if ($id) {
                $payments_orders = array(
                    'pkey' => $pay_key,
                    'okey' => $this->okey,
                    'pay' => $amount
                );
                $this->db->insert("payments_orders", $payments_orders);
            }
    }
    
     function buildOkey() {
        $this->okey = $this->lib->GeneralRandomNumberKey(8);
        $re = $this->db->query("select orderid from orders where okey = '" . $this->okey . "'");
        foreach ($re->result_array() as $row) {
            $this->okey = $this->lib->GeneralRandomNumberKey(8);
            $re = $this->db->query("select orderid from orders where okey = '" . $this->okey . "'");
        }
    }
}

//class M_mywallet