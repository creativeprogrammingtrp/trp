<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events_model extends CI_Model 
{
	public function loadEventList($page =1)
	{
		$keyword = isset($_GET['keyword'])?urldecode($_GET['keyword']):'';
		$start_date = isset($_GET['start_date'])?urldecode($_GET['start_date']):'';
		$data['start_date'] = str_replace("-","/",$start_date);
		$keyword = str_replace("%",'\%',$keyword);
		$keyword = str_replace("_",'\_',$keyword);
		$num_per_pager = 20;
		$page = (is_numeric($page) && $page>0)?$page:1;
		$limit = $num_per_pager*($page-1);
		
		$arr_products = array();
		$sql_start_date = '';
		if($start_date !='')
		{
			$start_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$start_date)));
			$sql_start_date = "and start_date = '$start_date'";	
		}
		$key_word_sql = '';
		if(trim($keyword)  !='')
		{
			$key_word = $this ->lib ->escape($keyword);
			$key_word = str_replace("  ", " ", $key_word);
			$arr_key = explode(" ", $key_word);
			if(count($arr_key) > 0)
			{
				foreach($arr_key as $key)
				{
					if($key != '')
					{
						$key_word_sql .= " and (";
						$key_word_sql .= " ekey like '$key'";
						$key_word_sql .= " or title like '%$key%'";
						$key_word_sql .= " or content like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		$where = "$sql_start_date $key_word_sql";
		$query = $this ->db ->query("SELECT COUNT(id) AS maxlength FROM events WHERE 1=1 $where ORDER BY start_date DESC");
		$maxlength =0;
		if($query ->num_rows() >0) $maxlength = $query ->row() ->maxlength;
		$query = $this ->db ->query("SELECT * FROM events WHERE 1=1 $where ORDER BY start_date DESC LIMIT $limit,".$num_per_pager);
		foreach($query ->result_array() as $row)
		{
			$row['start_date'] = gmdate("F j, Y", strtotime($row['start_date']));
			$row['end_date'] = gmdate("F j, Y", strtotime($row['end_date']));
			$row['content'] = $this ->lib ->SQLToFCK($row['content']);
			$arr_products[] = $row;
		}
		return array('data'=>$arr_products, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page);
	}//end loadingproducts function
	
	public function addEvent()
	{
		$error = '';
		$title = ($this ->input ->post("title"))?$this ->lib ->escape($this ->input ->post("title")):'';
		$start_date = ($this ->input ->post('start_date') && $this ->input ->post('start_date') != '')?gmdate("Y-m-d", strtotime($this ->input ->post('start_date'))):gmdate('Y-m-d');
		$end_date = ($this ->input ->post('end_date') && $this ->input ->post('end_date') != '')?gmdate("Y-m-d", strtotime($this ->input ->post('end_date'))):$start_date;
		$itm_key = $this ->lib ->GeneralRandomKey(20);
		$re = $this ->db ->query("SELECT id FROM events WHERE ekey = '$itm_key'");
		while($re ->num_rows() >0)
		{
			$itm_key = $this ->lib ->GeneralRandomKey(20);
			$re = $this ->db ->query("SELECT id FROM events WHERE ekey = '$itm_key'");
		}
		$events = array
		(
			'ekey' => $itm_key,
			'title' => $title,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'content' => $this ->lib ->FCKToSQL($_POST['content']),
			//'uid' => $_SESSION['ses_login']->uid
			'uid' =>1
		);
		$this ->db ->insert('events', $events);
		$id = $this ->db ->insert_id();
		if(!is_numeric($id) || $id == 0) $error = _error_cannot_insert_db_;
		return $error;	
	}//end addEvent function
	
	public function deleteEvent()
	{
		if($this ->input ->post("itemid") && $this ->input ->post("itemid") !='')
		{
			$this ->db ->where("ekey",$this ->input ->post("itemid"));
			$this ->db ->delete("events");
		}
		return;
	}//end deleteEvent function
	
	public function updateEvent()
	{
		$error = '';
		$title = ($this ->input ->post("title"))?$this ->lib ->escape($this ->input ->post("title")):'';
		$start_date = ($this ->input ->post('start_date') && $this ->input ->post('start_date') != '')?gmdate("Y-m-d", strtotime($this ->input ->post('start_date'))):gmdate('Y-m-d');
		$end_date = ($this ->input ->post('end_date') && $this ->input ->post('end_date') != '')?gmdate("Y-m-d", strtotime($this ->input ->post('end_date'))):$start_date;
		$itm_key =$this ->input ->post('key');
		$events = array
		(
			'title' => $title,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'content' => $this ->lib ->FCKToSQL($this ->input ->post('content'))
		);
		$this ->db ->where("ekey",$itm_key);
		$this ->db ->update('events', $events);
		return $error;	
	}//end updateEvent function
	
	public function loadValue($key)
	{
		$key = $this ->lib ->escape($key);
		$title = '';
		$start_date = '';
		$end_date = '';
		$body = '';
		$re = $this ->db ->get_where("events",array("ekey" => $key));
		if($re -> num_rows() >0)
		{
			$row = $re ->row_array();	
			$title = $row['title'];
			$start_date = gmdate("m/d/Y", strtotime($row['start_date']));
			$end_date = gmdate("m/d/Y", strtotime($row['end_date']));
			$body = $this ->lib ->SQLToFCK($row['content']);	
		}
		$data = array
		(
			"todate" => gmdate("m/d/Y"),
			"title" => $title,
			"key" => $key,
			"start_date" => $start_date,
			"end_date" => $end_date,
			"body" => $body
		);
		return $data;	
	}//end loadValue function
	public function loadStores($page=1)
	{
		$arr_stores = array();
		$num_per_pager = 20;
		$page = (is_numeric($page)&& $page >0)?$page:1;
		$limit = $num_per_pager*($page-1);
		
		$key = isset($_GET['key'])?$_GET['key']:'';
		$sql_key = '';
		if($key != ''){
			$sql_key = " and ekey = '$key'";	
		}
		$query = $this ->db ->query("select count(id) as maxlength from events where 1=1 $sql_key");
		
		$maxlength =0;
		if($query ->num_rows() >0) $maxlength = $query ->row() ->maxlength;
		$query = $this ->db ->query("select * from events where 1=1 $sql_key order by start_date DESC");
		foreach($query ->result_array()  as $row)
		{
			$row['event_month'] = gmdate("M", strtotime($row['start_date']));
			$row['event_day'] = gmdate("d", strtotime($row['start_date']));
			$row['event_year'] = gmdate("Y", strtotime($row['start_date']));
			$row['start_date_s'] = gmdate("F j, Y", strtotime($row['start_date']));
			$row['end_date_s'] = gmdate("F j, Y", strtotime($row['end_date']));
			$row['content'] = $this ->lib->SQLToFCK($row['content']);
			$arr_stores[] = $row;	
		}	
		return array('data'=>$arr_stores, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page);
	}//end loadStores function
}//end Events_model class