<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_tax extends CI_Model {

    public function loadState($page = 1) {

        $arr_state = array();
        $arr_states = array_merge(array("" => "All states"), $this->lib->GetSystemValues('States'));
        $num_per_pager = 20;
        $page = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit = $num_per_pager * ($page - 1);
        $sql_allState = "select state,sum(rate) as rate from tax_rates where status = 1 and state=''";
        $temp_allState = $this->db->query($sql_allState);
        $temp_rowAllState = $temp_allState->row_array();
        $allState = $temp_rowAllState['rate'];
        foreach ($temp_allState->result_array() as $rowAll) {
            $states = ($rowAll['state'] == NULL) ? '' : $rowAll['state'];
            if (isset($arr_states[$states]))
                $rowAll['state'] = $arr_states[$states];
            else
                $rowAll['state'] = 'None';
            $arr_state[] = $rowAll;
        }
        $sql = "select count(distinct state) as count_state from tax_rates where status <>-1 and state<>'' order by state DESC";
        $temp_query = $this->db->query($sql);
        $temp_row = $temp_query->row_array();
        $maxlength = count($temp_row) > 0 ? $temp_row['count_state'] : 0;
        $re = $this->db->query("select *,sum(rate) as total from tax_rates where status <> -1 and state <> '' group by state limit $limit," . $num_per_pager);
        foreach ($re->result_array() as $row) {
            $state = ($row['state'] == NULL) ? '' : $row['state'];
            if (isset($arr_states[$state]))
                $row['state'] = $arr_states[$state];
            else
                $row['state'] = 'None';
            $row['rate'] = number_format($allState + $row['total'], 2);
            $arr_state[] = $row;
        }
        return array('data' => $arr_state, 'rid' => (int) $this->author->objlogin->role['rid'], 'maxlength' => (int) $maxlength, 'page' => (int) $page);
    }

}

