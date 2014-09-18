<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_banners_home extends CI_Model 
{
	function Lists()
	{
		$del = 'no';
		if($this->author->isAccessPerm('banners_home','del'))
			$del = 'yes';
		
		$query = $this->db->query('select * from banners_home order by weight DESC');
		$arr_data = array();
		foreach($query->result_array() as $row)
		{
			$row['link'] = $this->system->URL_server__().'resource/banner/shome/'.$row['link'];
			$row['del'] = $del;
			$arr_data[] = $row;
		}
		return $arr_data;
	}
	function saveData(){
		if($this->input->post('data') && is_array($this->input->post('data')) && count($this->input->post('data')) > 0)
		{
			$arr = $this->input->post('data');
			if(empty($arr["file_id"]) || empty($arr["ext"]))
				return -1;//invalid data
			$filename = $arr['file_id'].'.'.$arr['ext'];
			$filepath = $this->system->URL_server__().'resource/banner/shome/'.$filename;
			
			$arr_banner = array
			(
				'link' => $this->lib->escape($filename),
				'status' => 0
			);
			$this->db->insert('banners_home',$arr_banner);	
			return 1;
		}
		return 0;
	}
	function saveForm()
	{
		if($this->input->post('datas') && is_array($this->input->post('datas')) && count($this->input->post('datas')) > 0)
		{
			$datas = $this->input->post('datas');
			$Enabled = ($this->input->post('Enabled') && is_array($this->input->post('Enabled')))?$this->input->post('Enabled'):array();
			$re = $this->db->query("select * from banners_home WHERE status <> -1");
			foreach($re->result_array() as $row)
			{
				for($i = 0; $i < count($datas); $i++)
				{
					if($datas[$i]['bid'] == $row['bid'])
					{
						$data_update = array('weight' => -$i);
						if(in_array($row['bid'], $Enabled)) $data_update['status'] = 1;
						else $data_update['status'] = 0;
						$data_update['url'] = $datas[$i]['url'];
						$this->db->where('bid',$row['bid']);
						$this->db->update('banners_home', $data_update);
						break;	
					}	
				}
			}       
		}
		return $this->Lists();
	}
	function del()
	{
		if($this->input->post('bid') && trim($this->input->post('bid')) != '')
		{
			$filename = $this->database->db_result("select link from banners_home where bid = ".$this->lib->escape($this->input->post('bid')));
			$this->db->where('bid',$this->input->post('bid'));
			if($this->db->delete('banners_home') == 1)
				$this->deleteFile('resource/banner/shome/'.$filename);
		}
		return $this->Lists();
	} 
	private function deleteFile($filePath="")
	{
		if(is_file($filePath))
			unlink($filePath);	
	}//function deleteFile
}//class M_banners_home