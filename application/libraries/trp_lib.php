<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class trp_lib {

    var $CI;
    var $separator = ',';
    var $field_names = false;
    var $error_msg  = '';

    function __construct() {
        $this->CI = & get_instance();
    }

    function importCsv($table,$arr_data,$url) {
        if (isset($arr_data) && is_array($arr_data) && count($arr_data) > 0) {
            $filename = $arr_data['original'];
            $url = $url.$filename;
            if (file_exists($url)) {
                $data = file_get_contents($url);
                if ($data != "") {
                    $lines = explode("\n", $data);

                    if (is_array($lines) && count($lines)) {

                        $insert_string = '';
                        $data_array = array();
                        if ($this->field_names == true)
                            $start_ind = 0;
                        else
                            $start_ind = 1;

                        for ($i = $start_ind; $i < count($lines); $i++) {
                            if ($lines[$i] != '') {
                                $data_array = explode($this->separator, $lines[$i]);
                                if (is_array($data_array) && count($data_array) > 0) {
                                    $t_str = '';
                                    foreach ($data_array as $data_row) {
                                        $data_row = str_replace("\r","",$data_row);
                                        $data_row = str_replace("'","\'",$data_row);
                                        $t_str .= "'" . $data_row . "',";
                                    }
                                }
                                $insert_string .= '( "",' .trim($t_str, ","). '),';
                            }
                        }
                        $insert_string = trim($insert_string,",");
                        if ($insert_string != '') {
                            $insert_query = "INSERT INTO ".$table."  VALUES  $insert_string";
                            $ins = $this->CI->db->query($insert_query);
                            if ($ins)
                                return $this->error_msg = "Data Inserted Successfully...";
                            else
                                return $this->error_msg = "Problem While Inserting Data...";
                        }
                    }
                }
            }
        }
    }
    
}

?>
