<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tax_model extends CI_Model 
{
	public function loadTaxs()
	{
		$arrUsers = array();
		$arr_states = array_merge(array(""=>"All states"), $this->lib ->GetSystemValues('States'));
		$modify = 'no';
		$del = 'no';
		if($this ->author ->isAccessPerm('Tax','edit'))
		{
			$modify = 'yes';	
		}
		if($this ->author ->isAccessPerm('Tax','delete'))
		{
			$del = 'yes';	
		}
		$re = $this ->db ->query("select * from tax_rates WHERE status <> -1 order by weight DESC");
		foreach($re ->result_array() as $row)
		{
			$state = ($row['state']==NULL)?'':$row['state'];
			if(isset($arr_states[$state]))
				$row['state'] = $arr_states[$state];
			else $row['state'] = 'None';
				
			$row['del'] = $del;
			$row['modify'] = $modify;		
			$arrUsers[] = $row;
		}
		return $arrUsers;
	}//end loadTaxs function
	
	public function delete_tax()
	{
		if(isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0)
		{
			$this ->db ->where('id',$this ->lib ->escape($_POST['id']));
			$this ->db ->update("tax_rates", array('status'=> -1));	
		}
		return $this ->loadTaxs();
	}//end delete_tax function
	
	function saveDatas(){
		if(isset($_POST['datas']) && is_array($_POST['datas']) && count($_POST['datas']) > 0)
		{
			$datas = $_POST['datas'];
			$re = $this ->db ->query("select * from tax_rates WHERE status <> -1");
			foreach($re ->result_array() as $row)
			{
				for($i = 0; $i < count($datas); $i++)
				{
					if($datas[$i]['id'] == $row['id'])
					{
						$this ->db ->where('id',$row['id']);
						$this ->db ->update("tax_rates", array('weight' => -$i));
						break;	
					}	
				}
			}	
		}
		return $this ->loadTaxs();
	}//end saveDatas function
	
	public function add_saveTax()
	{
		$error = '';
		$rate = ($_POST['rate']<0)?0:$_POST['rate'];
		$data_tax = array(
			'name' 		=> $this ->lib ->escape($_POST['name']),
			'rate' 		=> $rate,
			'state'		=> $_POST['state'],
			'weight'	=> $_POST['weight']	
		);
		$this ->db ->insert('tax_rates', $data_tax);
		
		return array('error' => $error, 'data' => $this ->loadTaxs());
	}//end saveTax function
	
	public function edit_loadValue($key='')
	{
		$name = '';
		$rate = '';
		$state = '';
		$weight = 0;
		$id = 0;
		
		if(is_numeric($key) && $key > 0){
			$id = $key;
			$re = $this ->db ->query("select * from tax_rates where id = '$id'");
			if($re->num_rows() >0)
			{
				$row  = $re->row_array();
				$state = ($row['state']==NULL)?'':$row['state'];
				$name = $row['name'];
				$rate = $row['rate'];
				$weight = $row['weight'];
					
			}	
		}
		$arr_states = $this ->lib ->GetSystemValues('States');
		$arr_states = array_merge(array(""=>"All states"), $arr_states);
		$states = $this ->lib ->OutputSelectBox($arr_states, 'states', 'states', 'style="WIDTH: 170px"', strtoupper($state));
		$data = array(
			'state' =>$states ,
			'id' => $id,
			'name' => $name,
			'rate' =>$rate ,
			'weight' =>  $this ->loadWeight($weight),
		);
		return $data;
	}//end edit_loadValue function
	
	private function loadWeight($weight)
	{
		$str = '<select id="weight" style="width:80px">';
		for($i = 10; $i > -11; $i--){
			$select = '';
			if($i == $weight) $select = 'selected="selected"';
			$str .= '<option value="'.$i.'" '.$select.'>'.$i.'</option>';	
		}
		$str .= '</select>';
		return $str;				
	}//end loadWeight function
	
	public function editTax()
	{
		$error = '';
		if(isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0){
			$rate = ($_POST['rate']<0)?0:$_POST['rate'];
			$data_tax = array(
				'name' 		=> $this ->lib ->escape($_POST['name']),
				'rate' 		=> $rate,
				'state'		=> $_POST['state'],
				'weight'	=> $_POST['weight']	
			);
			$this ->db ->where('id',$this ->lib ->escape($_POST['id']));
			$this ->db ->update('tax_rates', $data_tax);		
		}
		
		return array('error' => $error, 'data' => $this ->loadTaxs());
	}//end editTax function

}//end Tax_model class