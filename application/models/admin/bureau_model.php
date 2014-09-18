<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Bureau_model extends CI_Model{
    public  function __construct() {
        parent::__construct();
    }
    
    public function showAllService(){
        $data = array();
        $sql = "select * from service ";
        $res = $this->db->query($sql);
        foreach ($res->result_array() as $row) {
           
            $row['logo'] = '<img  style="width:80px;" src="' . $this->system->URL_server__() . 'data/logo/' . $row['logo_service'] . '">';
            $row['cost'] = number_format($row['cost'],2);
            $row['sb_saleprice'] = number_format($row['sb_saleprice'],2);
            $row['ero_saleprice'] = number_format($row['ero_saleprice'],2);
            $data[] = $row;
        }
        return $data;
    }

}
    
?>
