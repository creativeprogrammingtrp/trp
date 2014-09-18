<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_model extends CI_Model{
    
    function getVouchers(){
        $voucher = array();
        $expiration_date_unit = 1;
        $moreUnit = '';
        $voucherId = $this->input->post('vid');
        $memberId = $this->input->post('mid');
        //$query = $this->db->query("select vo.*,it.uid from items as it ,voucher as vo,order_detais as od where vo.order_id = od.orderid and od.itemid = it.itm_id and vo.voucher_id = '{$voucherId}' and vo.member_id = '{$memberId}'  ");
        $query = $this->db->query("select *,vo.status as statusv from items as it ,voucher as vo,order_detais as od,orders as o where o.orderid = od.orderid and vo.order_id = od.orderid and od.itemid = it.itm_id and vo.voucher_id = '{$voucherId}' and vo.member_id = '{$memberId}'  ");
        foreach($query->result_array() as $row){
            $expiration_date_unit = $row['expiration_date_unit'];
            if($expiration_date_unit == 30){
                $moreUnit = 'month';
            }elseif($expiration_date_unit == 365){
                $moreUnit = 'year';
            }else{
                $moreUnit = 'day';
            }
            $row['date_format'] = strtotime(date("Y-m-d h:m:s", strtotime($row['order_date'])) . "+".$row['expiration_date']." ".$moreUnit);
            $row['end_date'] = strtotime(date("Y-m-d h:m:s", strtotime($row['order_date'])) . "+".$row['expiration_date']." ".$moreUnit);
            $row['current_date']= strtotime(date('Y-m-d H:i:s'));
            $row['actived_date'] = date('Y-m-d');
            $row['date_format'] = date("Y-m-d",$row['date_format']);
            $row['order_format'] = date("Y-m-d",strtotime($row['order_date']));
            $itemId = $row['itm_id'];
            $arr_file = $this ->lib ->__loadFileProduct__($itemId);
            $row['image'] = $arr_file['file'];
            $voucher[] = $row;
        } 
        return $voucher;
    }
    
    function checkPermission(){
         $uid = array();
         $query = $this->db->query("select it.uid from items as it ,voucher as vo,order_detais as od where vo.order_id = od.orderid and od.itemid = it.itm_id ");
         foreach($query->result_array() as $row){
              $uid[] = $row;
         }
         return array('data' =>$uid);
    }
    function updateVouchers(){
       $voucherId = str_replace('/\s+/gi','',trim($this->lib->escape($this->input->post("vid"))));
       $memberId  = str_replace('/\s+/gi','',trim($this->lib->escape($this->input->post("mid"))));
       $active_date = time();
       $this->db->query("update voucher set status = '1',active_date ='{$active_date}' where voucher_id ='{$voucherId}' and member_id = '{$memberId}'");
    }   
}
?>