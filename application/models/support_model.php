<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support_model extends CI_Model 
{
	public function support_sendmail()
	{
		$parent_id = $this->input->post('parent_id');
		$result = $this->db->query("select status,idparent from tblreply where id = '{$parent_id}'");
		$row_state ='';
		$row_parent ='';
		foreach($result ->result_array() as $row)
		{
		  $row_state = $row['status'];
		  $row_parent = $row['idparent'];
		}		
		$this->load->library('email');
		$currentUser = $this->session->userdata('ses_login');
		$email = $this->getUserMail()?$this->getUserMail() :'';
		$com = $this ->input ->post('com')?$this ->input ->post('com') :'';
		$subj = $this->input->post('subj')? $this->input->post('subj'):'';
		$arr_data = array(
			'email' =>$email ,
			'content' => $com,
			'time_send' => date('Y-m-d: H:i:s', time()),
			'idparent'=> $this->input->post('parent_id'),
			'subject' =>$subj,
			'firstname'=>$currentUser->firstname,
			'lastname'=>$currentUser->lastname,
			'status'=>'0'
		);
		if($row_state == '' && $row_parent == '')
		  $this->db->insert('tblreply',$arr_data);
		
		else{
		  $this->db->query("update tblreply set status = 0 where id = '{$parent_id}' ");
		  $this->db->insert('tblreply',$arr_data);
		
		}
                
		//$this->db->insert('tblreply',$arr_data);
		/*$content .= "<strong>Customer's email: </strong>".$email.'<br/>';
		$content .= "<strong>Customer's comment: </strong>".'<br/>';
		$content .= $com;
		if($email!='')
		{
			$this->email->from($this->system->siteInfo['email'], $this->system->siteInfo['sender_name']);
			$this->email->to($this->system->siteInfo['email']);
			$this->email->subject('Support email');
			$this->email->message($content);
			$this->email->send();
		}*/
	}//end support_sendmail function
	
	public function delete()
	{
		if($this ->input ->post("itemid") && $this ->input ->post("itemid") !='')
		{
			$this ->db ->where("id",$this ->input ->post("itemid"));
                        $this->db->or_where("idparent",$this->input->post("itemid"));
			$this ->db ->delete("tblreply");
                        
		}
		return;
	}//end deleteEvent function
	       
        public function saveData($com,$key)
        {
                $content =  $this ->lib ->FCKToSQL($com);
                $currentUser = $this->session->userdata('ses_login');
                $time_send = date('Y-m-d: H:i:s', time());
                $arr_data = array(
			'email' =>$currentUser->mail,
			'content' => $content,
			'time_send' => $time_send,
                        'idparent'=>$key,
                        'firstname'=>$currentUser->firstname,
                        'lastname' =>$currentUser->lastname,
                        'status' =>'0'
		);
		$this->db->insert('tblreply',$arr_data);
        }//end function saveData
        
    public function updateStatus($parent_id){
         $this->db->query("update tblreply set status = 1 where id = '{$parent_id}' ");
    }
    public function getUserMail()
        {
                $mail = $this->session->userdata('ses_login');
                $query = $this->db->query("SELECT mail FROM users WHERE mail = '{$mail->mail}'");
                if ($query->num_rows() > 0)
                {
                    $row = $query->row(); 
                    return $row->mail;
                }
        }//end function getUserMail
        
        public function getSupportAdmin($page =1)
         {
            $keyword = isset($_GET['keyword'])?urldecode($_GET['keyword']):'';
            $start_date = isset($_GET['start_date'])?urldecode($_GET['start_date']):'';
            $keyword = str_replace("%",'\%',$keyword);
            $keyword = str_replace("_",'\_',$keyword);
            $num_per_pager =10;
            $page = (is_numeric($page) && $page>0)?$page:1;
            $limit = $num_per_pager*($page-1);
            $arr_products = array();
            $sql_start_date = '';
            if($start_date !='')
            {
                    $start_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$start_date)));
                    $sql_start_date = "and time_send = '$start_date'";	
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
						$key_word_sql .= " or (";
                                                $key_word_sql .= " id like '%$key%'";
						$key_word_sql .= " or email like '%$key%'";
						$key_word_sql .= " or content like '%$key%'";
                                                $key_word_sql .= " or subject like '%$key%'";
                                                $key_word_sql .= " or firstname like '%$key%'";
                                                $key_word_sql .= "or lastname like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
            $where = " $sql_start_date $key_word_sql";
            $query = $this ->db ->query("SELECT * FROM tblreply WHERE idparent<>0 $where LIMIT $limit,".$num_per_pager);
            foreach($query ->result_array() as $row)
            {
                    $row['start_date'] = gmdate("F j, Y H:i", strtotime($row['time_send']));
                    $row['comment'] = $this ->lib ->SQLToFCK($row['content']);
                    $arr_products[] = $row;
            }
            $total = $this ->db ->query("SELECT COUNT(id) AS maxlength FROM tblreply where  idparent =0 ORDER BY time_send DESC");// WHERE 1=1 $where
            $maxlength =0;
            if($total ->num_rows() >0) $maxlength = $total ->row() ->maxlength;
            return array('data'=>$arr_products,'page'=> (int)$page,'maxlength'=>$maxlength);
        }//end getSupportAdmin function
       
        function getSupportUser($page =1){
            $start_date = isset($_GET['start_date'])?urldecode($_GET['start_date']):'';
            $mail = $this->getUserMail();
            $num_per_pager =10;
            $page = (is_numeric($page) && $page>0)?$page:1;
            $limit = $num_per_pager*($page-1);
            $arr_products = array();
            $sql_start_date = '';
            if($start_date !='')
            {
                    $start_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$start_date)));
                    $sql_start_date = "and time_send = '$start_date'";	
            }
            $query = $this ->db ->query("SELECT * FROM tblreply where email = '{$mail}' and idparent =0 LIMIT $limit,".$num_per_pager);
            foreach($query ->result_array() as $row)
            {
                    $row['start_date'] = gmdate("F j, Y H:i", strtotime($row['time_send']));
                    $row['comment'] = $this ->lib ->SQLToFCK($row['content']);
                    $arr_products[] = $row;
            }
            return array('data'=>$arr_products,'page'=> (int)$page);
    }
    
    function getSupportSettingUser($page =1)
    {
        $arr_products = array();
        $num_per_pager =10;
        $page = (is_numeric($page) && $page>0)?$page:1;
        $limit = $num_per_pager*($page-1);
        $query =  $this ->db ->query("select * from tblreply where idparent <>0 LIMIT $limit,".$num_per_pager);
        foreach($query ->result_array() as $row)
        {
                $row['start_date'] = gmdate("F j, Y H:i", strtotime($row['time_send']));
                $row['comment'] = $this ->lib ->SQLToFCK($row['content']);
                $arr_products[] = $row;
        }
       
        return array('data'=>$arr_products, 'page'=> (int)$page);
    }
    
    public function getSupportSettingAdmin($page =1)
    {
        
       $keyword = isset($_GET['keyword'])?urldecode($_GET['keyword']):'';
       $start_date = isset($_GET['start_date'])?urldecode($_GET['start_date']):'';
       $keyword = str_replace("%",'\%',$keyword);
       $keyword = str_replace("_",'\_',$keyword);
       $arr_products = array();
       $num_per_pager =10;
       $page = (is_numeric($page) && $page>0)?$page:1;
       $limit = $num_per_pager*($page-1);
       
           $sql_start_date = '';
            if($start_date !='')
            {
                    $start_date = gmdate("Y-m-d", strtotime(str_replace("-","/",$start_date)));
                    $sql_start_date = "and time_send = '$start_date'";	
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
                                                $key_word_sql .= " id like '%$key%'";
						$key_word_sql .= " or email like '%$key%'";
						$key_word_sql .= " or content like '%$key%'";
                                                $key_word_sql .= " or subject like '%$key%'";
                                                $key_word_sql .= " or firstname like '%$key%'";
                                                $key_word_sql .= "or lastname like '%$key%'";
						$key_word_sql .= " ) ";	
					}
				}	
			}	
		}
       $where = " $sql_start_date $key_word_sql";
       $query =  $this ->db ->query("select * from tblreply where idparent =0 $where LIMIT $limit,".$num_per_pager);
       foreach($query ->result_array() as $row)
        {
                $row['start_date'] = gmdate("F j, Y H:i", strtotime($row['time_send']));
                $row['comment'] = $this ->lib ->SQLToFCK($row['content']);
                $arr_products[] = $row;
        }
      $total = $this ->db ->query("SELECT COUNT(id) AS maxlength FROM tblreply where idparent =0 ORDER BY time_send DESC");
      $maxlength =0;
      if($total ->num_rows() >0) $maxlength = $total ->row() ->maxlength;
      return array('data'=>$arr_products, 'page'=> (int)$page,'maxlength'=>$maxlength);
    }//end getSupportSettingAdmin function
                             
}//end Support_model function