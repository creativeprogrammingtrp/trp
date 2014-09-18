<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplies_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function saveOrderInfoFromModal() {
        $data = array(
            'uid' => $this->author->objlogin->uid,
            'order_from' => '1',
            'order_date' => $this->lib->getTimeGMT()
        );
        
            //$data['uid'] = $this->author->objlogin->uid;
            //$data['efin'] = $this->lib->escape($_POST['efin']);
            $this->db->insert("orders", $data);
        
        return true;
    }


}

?>
