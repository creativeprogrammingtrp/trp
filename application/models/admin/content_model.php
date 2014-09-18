<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Content_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function loadEfinAndEin(){
        $efin_arr = array();
        $ein_arr = array();
        $sql = "select * from efins";
        $res = $this->db->query($sql);
        foreach ($res ->result_array() as $row_efin){
            $efin_arr [] = $row_efin;
        }
        
        $sql_1 = "select * from eins";
        $res_1 = $this->db->query($sql_1);
        foreach ($res_1 ->result_array() as $row_ein){
            $ein_arr [] = $row_ein;
        }
        
        return array(
            'efins' => $efin_arr,
            'eins' => $ein_arr
        );
    }

    public function deleteEfin($efin){
        $sql = "delete from efins where efin = ".$efin;
        $this->db->query($sql);
        return $this->loadEfinAndEin();
    }
    public function deleteEin($ein){
        $sql = "delete from eins where ein = ".$ein;
        $this->db->query($sql);
        return $this->loadEfinAndEin();
    }
}
?>
