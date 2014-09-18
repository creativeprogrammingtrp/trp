<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Product_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function loadData(){
        
    }

        public function option(){
        $list = array(
                        '@add_product@' => 'abc',
        );
    }
}