<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_model extends CI_Model 
{
	public function contact_sendmail()
	{
		$this->load->library('email');
		$title = $this ->input ->post('t_title')?	$this ->input ->post('t_title') :'';
		$email = $this ->input ->post('t_email')?	$this ->input ->post('t_email') :'';
		$t_content = $this ->input ->post('t_content')?	$this ->input ->post('t_content') :'';
		
		$arr_data = array(
			'subject' => $title,
			'email' => $email,
			'comment' => $t_content,
			'time_submit' => date('Y-m-d: H:i:s', time())
		);
		$this->db->insert('contact',$arr_data);
		
		$content = "<strong>Email title: </strong>".$title.'<br/>';
		$content .= "<strong>Customer's email: </strong>".$email.'<br/>';
		$content .= "<strong>Customer's comment: </strong>".'<br/>';
		$content .= $t_content;
		if($email!='')
		{
			$this->email->from($this->system->siteInfo['email'], $this->system->siteInfo['sender_name']);
			$this->email->to($this->system->siteInfo['email']);
			$this->email->subject('Contact email');
			$this->email->message($content);
			$this->email->send();
		}
	}//end contact_sendmail function
	
	public function load_content($page =1)
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
			$sql_start_date = "and time_submit = '$start_date'";	
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
						$key_word_sql .= " subject like '%$key%'";
						$key_word_sql .= " or email like '%$key%'";
						$key_word_sql .= " or comment like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
		$where = "$sql_start_date $key_word_sql";
		
		$query = $this ->db ->query("SELECT COUNT(id) AS maxlength FROM contact WHERE 1=1 $where ORDER BY time_submit DESC");
		$maxlength =0;
		if($query ->num_rows() >0) $maxlength = $query ->row() ->maxlength;
		$query = $this ->db ->query("SELECT * FROM contact WHERE 1=1 and status = 0 $where ORDER BY time_submit DESC LIMIT $limit,".$num_per_pager);
		foreach($query ->result_array() as $row)
		{
			$row['start_date'] = gmdate("F j, Y H:i", strtotime($row['time_submit']));
			$row['comment'] = $this ->lib ->SQLToFCK($row['comment']);
			$arr_products[] = $row;
		}
		return array('data'=>$arr_products, 'maxlength'=>(int)$maxlength, 'page'=> (int)$page);
	}//end loadingproducts function
	
	public function delete()
	{
		if($this ->input ->post("itemid") && $this ->input ->post("itemid") !='')
		{
			$this ->db ->where("id",$this ->input ->post("itemid"));
			$this ->db ->delete("contact");
		}
		return;
	}//end deleteEvent function
	
	public function update()
	{
		$error = '';
		$contact = array
		(
			'name_content'	=> 'contact',
			'content' => $this ->lib ->FCKToSQL($this ->input ->post('content'))
		);
		$result = $this ->database ->db_result("select id from web_content where name_content = 'contact'");
		if($result > 0)
			$this ->db ->update('web_content', $contact,"name_content = 'contact'");
		else
			$this ->db ->insert('web_content', $contact);
		return $error;	
	}
	
	public function load_information()
	{
		$body = '';
		$re = $this ->db ->query("select * from web_content where name_content = 'contact'");
		if($re -> num_rows() >0)
		{
			$row = $re ->row_array();
			$body = $this ->lib ->SQLToFCK($row['content']);	
		}
		return $body;	
	}
        
        public function load_infor($key)
        {
                $body = ''; 
                $re = $this ->db ->query("select * from contact where id = '{$key}'");
                if($re -> num_rows() >0)
                {
                        $row = $re ->row_array();
                        $body = $this ->lib ->SQLToFCK($row['email']);	
                }
                return $body;
        }                            
                    
}//end Contact_model function