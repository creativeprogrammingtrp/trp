<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Countries_model extends CI_Model {

    public function getCountries($status) {
        $this->db->order_by("name", "asc");
        $query = $this->db->get_where("tblcontries", array("status" => $status));
        return $query->result_array();
    }

//end function getCountries

    public function countStates($countryId) {
        $this->db->where("idcountry", $countryId);
        $this->db->from('tblsates');
        return $this->db->count_all_results();
    }

//end function countStates

    public function statusCountry($countryId, $status) {
        $this->db->where('id', $countryId);
        $this->db->update('tblcontries', array('status' => $status));
    }

//end function addCountries

    public function getStateNameByCountry($countryId) {
        $this->db->select("name");
        $query = $this->db->get_where("tblcontries", array("id" => $countryId));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->name;
        }
        else
            return '';
    }

//end function getStatesByCountry

    public function getStatesByCountry($countryId) {
        $this->db->order_by("name", "asc");
        $query = $this->db->get_where("tblsates", array("idcountry" => $countryId));
        return $query->result_array();
    }

//end function getStatesByCountry

    public function addState($data) {
        $this->db->insert("tblsates", $data);
    }

//end function addState

    public function deleteState($skey) {
        $this->db->where('id', $skey);
        $this->db->delete('tblsates');
    }

//end function deleteState

    public function getStateById($skey) {
        $this->db->select("name,code,idcountry");
        $query = $this->db->get_where("tblsates", array("id" => $skey));
        return $query->row_array();
    }

//end function getStateById

    public function updateState($skey, $data) {
        $this->db->where('id', $skey);
        $this->db->update('tblsates', $data);
    }

//end function updateState

    public function getCitiesNameByCountry($countryId) {
        $this->db->select("name");
        $query = $this->db->get_where("tblcontries", array("id" => $countryId));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->name;
        }
        else
            return '';
    }

//end function getCitiesNameByCountry

    public function getCitiesByCountry($countryId, $page) {

        $segment_array = $this->uri->segment_array();
        $keyword = '';
        for ($i = 1; $i < count($segment_array); $i++) {
            if ($i == 5) {
                $page = $this->lib->escape($segment_array[$i + 1]);
            } elseif ($i == 6) {
                $keyword = $this->lib->escape($segment_array[$i + 1]);
            }
        }
        $sql = '';
		$segment = urldecode($keyword);
		//if (!$this->input->get('key_word'))
//        $segment = preg_replace('/[^a-zA-Z0-9\-_\s]/', '', $keyword);
//		else $segment = urldecode($this->input->get('key_word'));
        if (isset($segment)) {
            $sql .= " and city like '%$segment%' ";
        } else {
            $sql .= '';
        }
        $num_per_pager = 20;
        $page = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit = $num_per_pager * ($page - 1);
        $this->db->order_by("city", "asc");
        $query = $this->db->query("select * from tblcities where idcountry = $countryId $sql LIMIT $limit," . $num_per_pager);
        $arr_data = array();
        foreach ($query->result_array() as $row) {
            $arr_data[] = $row;
        }

        $total = $this->db->query("select count(*) as maxlength from tblcities where idcountry = {$countryId}");
        $maxlength = 0;
        if ($total->num_rows() > 0)
            $maxlength = $total->row()->maxlength;
        return array('data' => $arr_data, 'page' => (int) $page, 'maxlength' => $maxlength);
    }

//end function getCitiesByCountry

    public function deleteCity($ctkey) {
        $this->db->where('id', $ctkey);
        $this->db->delete('tblcities');
    }

//end function deleteCities

    public function addCity($data) {
        $this->db->insert("tblcities", $data);
    }

//end function addCity

    public function updateCity($skey, $data) {
        $this->db->where('id', $skey);
        $this->db->update('tblcities', $data);
    }

//end function updateCity

    public function getCityById($skey) {
        $this->db->select("city,idcountry");
        $query = $this->db->get_where("tblcities", array("id" => $skey));
        return $query->row_array();
    }

//end function getCityById

    public function countCities($idcountry) {
        $this->db->where("idcountry", $idcountry);
        $this->db->from("tblcities");
        return $this->db->count_all_results();
    }

//end function countCities

    public function addExcel($data, $idcountry) {
        return $this->db->query("INSERT INTO `tblcities`(`id`, `city`, `idcountry`) VALUES (NULL,'{$data}','{$idcountry}')");
    }

//end function addExcel

    public function deleteCityByCountry($idcountry) {
        $this->db->query("DELETE city.* FROM `tblcities` city, `tblcontries` country WHERE city.idcountry ='{$idcountry}'");
    }

//end function deleteCityByCountry
}

//end class Mailconfig