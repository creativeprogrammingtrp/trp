<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner_model extends CI_Model {
	
	function Lists(){
		$del = 'no';
		if($this->author->isAccessPerm('banner','del')){
			$del = 'yes';
		}
		$query = $this->db->query('select * from banner order by weight DESC');
		$arr_data = array();
		foreach($query->result_array() as $row){
			$row['link'] = $this->system->URL_server__().'resource/banner/'.$row['link'];
			$row['del'] = $del;
			$arr_data[] = $row;
		}
		return $arr_data;
               
	}
	function saveData(){
		$error = '';
		if($this->input->post('data') && is_array($this->input->post('data')) && count($this->input->post('data')) > 0){
			$arr = $this->input->post('data');
			$arr_banner = array(
				'link' => $this->lib->escape($arr['file_id']).'.'.$this->lib->escape($arr['ext']),
				'status' => 0
			);
                        $this->db->insert('banner',$arr_banner);	
		}
		return array('error' => $error);
	}
	function saveForm(){
		if($this->input->post('datas') && is_array($this->input->post('datas')) && count($this->input->post('datas')) > 0){
			$datas = $this->input->post('datas');
			$Enabled = ($this->input->post('Enabled') && is_array($this->input->post('Enabled')))?$this->input->post('Enabled'):array();
			$re = $this->db->query("select * from banner WHERE status <> -1");
			foreach($re->result_array() as $row){
				for($i = 0; $i < count($datas); $i++){
					if($datas[$i]['bid'] == $row['bid']){
						$data_update = array('weight' => -$i);
						if(in_array($row['bid'], $Enabled)) $data_update['status'] = 1;
						else $data_update['status'] = 0;
						$data_update['side'] = $datas[$i]['side'];
						$data_update['url'] = $datas[$i]['url'];
						$this->db->where('bid',$row['bid']);
						$this->db->update('banner', $data_update);
						break;	
					}	
				}
			}       
		}
		return $this->Lists();
	}
	function del(){
		if($this->input->post('bid') && trim($this->input->post('bid')) != ''){
			$filename = $this->database->db_result("select link from banner where bid = ".$this->lib->escape($this->input->post('bid')));
			$this->db->where('bid',$this->input->post('bid'));
			if($this->db->delete('banner') == 1){
				if(is_file('resource/banner/'.$filename)){
					unlink('resource/banner/'.$filename);	
				}
			}
		}
		return $this->Lists();
	}   
}