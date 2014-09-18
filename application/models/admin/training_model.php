<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Training_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function loadDocument() {
        $data = array();
        $re = $this->db->query("SELECT * FROM business_resource_documents");
        foreach ($re->result_array() as $row) {
            if (trim($row['label']) == "")
                $row['label'] = $row['file_name'];
            $data[] = $row;
        }
        return $data;
    }

    public function saveDocument() {
        if ($this->input->post('data') && is_array($this->input->post('data')) && count($this->input->post('data')) > 0) {
            $arr = $this->input->post('data');
            $arr_banner = array(
                'file_name' => $this->lib->escape($arr['original'])
            );
            $this->db->insert('business_resource_documents', $arr_banner);
        }
        return $this->loadDocument();
    }
    
    function loadExamInfo() {
    	 
    	$sql_1 = "select * from exam_results  where uid = '".$this->author->objlogin->uid."' ";
    	$res_1 = $this->db->query($sql_1);
    	if ($res_1->num_rows() > 0) {
    		$row_1 = $res_1->row_array();
    	}
    	 
    	$result = $row_1;
    	 
    	return $result;
    }
    
    public function loadPassedExamId() {
    	$data = array();
    	 
    	$sql = "select * from exam_results where uid = '".$this->author->objlogin->uid."' AND resultParcent = '100'";
    	 
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$data[] = $row;
    	}
    	return $data;
    }
    
    
    
    function saveTrainingExamData() {
    
    	$data = array(
    			'uid' => $this->author->objlogin->uid,
    			'Ques1' => $this->lib->escape($_POST['Ques1']),
    			'Ques1Ans' => $this->lib->escape($_POST['Ques1Ans']),
    			'Ques2' => $this->lib->escape($_POST['Ques2']),
    			'Ques2Ans' => $this->lib->escape($_POST['Ques2Ans']),
    			'Ques3' => $this->lib->escape($_POST['Ques3']),
    			'Ques3Ans' => $this->lib->escape($_POST['Ques3Ans']),
    			'Ques4' => $this->lib->escape($_POST['Ques4']),
    			'Ques4Ans' => $this->lib->escape($_POST['Ques4Ans']),
    			'Ques5' => $this->lib->escape($_POST['Ques5']),
    			'Ques5Ans' => $this->lib->escape($_POST['Ques5Ans']),
    			'resultParcent' => $this->lib->escape($_POST['resultPercentData']),
    			'rightAns' => $this->lib->escape($_POST['rightAnsCountdata']),
    			'exam_date' => date("Y-m-d H:i:s")
    	);
    	 
    	$data['uid'] = $this->author->objlogin->uid;
    	$this->db->insert("exam_results", $data);
    	
    	$datauser = array(
    			'status' => '2'
    	);
    	$this->db->where("uid", $this->author->objlogin->uid);
    	$this->db->update('users', $datauser);
    	
    	$dataero = array(
    			'status_ero' => '1'
    	);
    	$this->db->where("author", $this->author->objlogin->uid);
    	$this->db->update('ero', $dataero);
    	
    	return $this->loadExamInfo();
    }
    
    public function loadAllExamResult() {
    	$data = array();
    	
    		 $sql = "select * from exam_results where uid = '".$this->author->objlogin->uid."'";
    	
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$data[] = $row;
    	}
    	return $data;
    }
    
    public function loadOldExamData($uid, $examid){
    	$data = array();
    	
    		 $sql = "select * from exam_results where uid = '".$uid."' AND exam_id = '".$examid."'";
    	
    	$res = $this->db->query($sql);
    	foreach ($res->result_array() as $row) {
    		$data[] = $row;
    	}
    	return $data;
    	
    }
    

}

?>
