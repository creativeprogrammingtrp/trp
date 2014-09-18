<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Representatives_setting extends CI_Model {
	private $table = 'general_setting';
	function __construct(){
        parent::__construct();
    }	

	function loadManufacturers($value=''){
		$str = '';
		$re = $this->db->query("select manufacturers.legal_business_name,manufacturers.uid from manufacturers join users join users_roles where (manufacturers.uid = users.uid and users.status <> -1 ) and (manufacturers.author = users_roles.uid and users_roles.rid <> 5) order by manufacturers.legal_business_name ASC");
		foreach($re->result_array() as $row){
			$select = '';
			if($row['uid'] == $value) $select = 'selected="selected"';
			$str .= '<option value="'.$row['uid'].'" '.$select.'>'.$row['legal_business_name'].'</option>';		
		}
		return $str;				
	}
	function saveForm($sale_rep_setting){
		$this->system->set_sysvals('sale_rep_setting', $sale_rep_setting);
		return array();	
	}
	public function save_general_setting()
	{
		$date_apply = ($this->input->post('date_apply') && $this->input->post('date_apply') != '')?$this->lib->escape($this->input->post('date_apply')):'';
		$sale_rep_setting = array(
			'minimum_purchased' => $this->input->post('minimum_purchased'),
			'limit_time_purchase' => $this->input->post('limit_time_purchase'),
			'units_purchase' => $this->input->post('units_purchase'),
			'number_of_level' => $this->input->post('number_of_level'),
			'direct_sponsor' => $this->input->post('direct_sponsor'),
			'to_be_active' => $this->input->post('to_be_active'),
			'units_active' => $this->input->post('units_active'),
			'date_apply' => $date_apply!=''?date('Y-m-d',strtotime($date_apply)):'',
			'minimum_payment' => $this->input->post('minimum_payment'),
			'limit_time_payment' => $this->input->post('limit_time_payment'),
			'units_payment' =>$this->input->post('units_payment'),
			'time_purchase_actived' => $this->input->post('time_purchase_actived'),
			'units_time_purchase' => $this->input->post('units_time_purchase'),
			'date_holding_account'=> $this->input->post('date_holding_account'),
			'date_update'=>date("Y-m-d H:i:s")
		);
		$this->db->insert('general_setting',$sale_rep_setting);
		$id = $this->db->insert_id();
		return ($id>0);
	}
	public function get_general_setting()
	{
		$query = $this->db->query('select * from '.$this->table." order by id desc");
		$return = array();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if(isset($row['date_apply']) && $row['date_apply'] != '')
				{			
					$row['date_apply'] = date("m/d/Y",strtotime($row['date_apply']));	
				}
				$return[] = $row;
			}
		}
		return $return;
	}
	public function get_last_setting()
	{
		$sale_rep_setting_default = array(
			'minimum_purchased' => 0,
			'limit_time_purchase' => 0,
			'units_purchase' => 1,
			'number_of_level' => 0,
			'direct_sponsor' => 0,
			'to_be_active' => 1,
			'units_active' => 1,
			'date_apply' => '',
			'minimum_payment' => 0,
			'limit_time_payment' => 0,
			'units_payment' => 1,
			'time_purchase_actived' => 1,
			'units_time_purchase' => 1,
			'date_holding_account' => 20,
		);
		$query = $this->db->query('select * from '.$this->table." order by id desc limit 1");
		if ($query->num_rows() > 0) return $query->row_array();
		return $sale_rep_setting_default;
	}
	public function output_select_date()
	{
		$query = $this->db->query('select id, date_update from '.$this->table." order by id desc");
		if ($query->num_rows() > 0)
		{
			$color = "style = 'color:#FF0000;' ";
			$label = '<label id="day_update_lable">Date Update:</label>';
			$output = '<select id="day_update" onchange="change_value()">';
			foreach ($query->result_array() as $row)
			{
				$output .= '<option '.$color.' myid='.$row['id'].'>'.$row['date_update'].'</option>';
				$color = '';
			}
			$output .= '</select>';
			return $label.$output;
		}
		return '';
	}
	public function select_holding_date($value = 20)
	{
		$output = '<select id="date_holding_account">';
		for ($i = 1; $i<=28; $i++)
		{
			if ($value == $i)
			$output .= '<option selected>'.$i.'</option>';
			else $output .= '<option>'.$i.'</option>';
		}
		$output .='<select>';
		return $output;
	}
}