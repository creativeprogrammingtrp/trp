<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Training extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("admin/training_model", "training");
    }

    function perms() {
        $perms['Training'] = array('index', 'doupload','showCompliance','saveDocument', 'showReviewMaterials','viewReviewMaterial','saveTrainingExamData','showUsersExamResult','examDetails','certificate');
        return $perms;
    }

    public function index() {
        $data = array('title_page' => "Training");
        $data['compiliance_test5'] = $this->system->checkcompiliance_test_status();
        $this->system->parse("training/training.htm", $data);
        
        
        //$this->system->parse("training/documents.htm",$data);
    }

    /*public function doupload() {
        $config['upload_path'] = 'data/training';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '10240';
        $this->load->library('upload', $config);
        $result = array(
            "error" => 1,
        );
        if (!$this->upload->do_upload("file")) {
            $result = array(
                "error" => $this->upload->display_errors(),
            );
        }
        echo json_encode($result);
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('training/documents.htm', $data);
            exit();
        }
    }*/
    
    
    
    public function examDetails() {
    	$data = array();
    	$data['title_page'] = "Training";
    	$examid = $this->uri->segment(5);
    	$uid = $this->uri->segment(4);
    	
    	//$data['compiliance_test5'] = $this->system->checkcompiliance_test_status();
    	
    	//if($examid != '' && $uid != ''){
    		$data['dataLoad'] = "dataClient = " . json_encode($this->training->loadOldExamData($uid,$examid));
    	//}
    	
    	
    	$this->system->parse('training/view_old_exam.htm',$data);
    	exit();
    
    }
    
    
    public function certificate() {
    	$data = array();
    	$data['title_page'] = "Training";
    	$examid = $this->uri->segment(5);
    	$uid = $this->uri->segment(4);
    	 
    	//$data['compiliance_test5'] = $this->system->checkcompiliance_test_status();
    	 
    	//if($examid != '' && $uid != ''){
    	//$data['dataLoad'] = "dataClient = " . json_encode($this->training->loadOldExamData($uid,$examid));
    	//}
    	 
    	 
    	$this->system->parse('training/certificate.htm',$data);
    	exit();
    
    }
    
    
    public function showReviewMaterials() {
    	$data = array();
    	$data['compiliance_test5'] = $this->system->checkcompiliance_test_status();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->training->loadDocument());
    	$data['compiliance_test5'] = $this->system->checkcompiliance_test_status();
    	$examresult = $this->training->loadPassedExamId();
    	//$data['passedExamId'] = $examresult[0]['exam_id'];
    	//$data['uid'] = $examresult[0]['uid'];
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('training/documents.htm', $data);
    		exit();
    	}
    }

    public function viewReviewMaterial() {
    	//$data = array();
    	$data = array();
    	$data['title_page'] = "Training";
    	$data['compiliance_test5'] = $this->system->checkcompiliance_test_status();
    		$this->system->parse('training/view_document.htm',$data);
    		exit();

    }
    
    public function saveTrainingExamData() {
    	if (isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes') {
    		echo json_encode($this->training->saveTrainingExamData());
    		exit();
    	}
    }
    
    public function showUsersExamResult(){
    	$data = array();
    	$data['dataLoad'] = "dataClient = " . json_encode($this->training->loadAllExamResult());
    	if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
    		$this->system->parse_templace('training/examlistofresult.htm', $data);
    		exit();
    	}
    }
    
    public function showCompliance() {
        $data = array();
        
        $data['dataLoad'] = "dataClient = " . json_encode($this->training->loadDocument());
        if (!empty($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->system->parse_templace('training/documents.htm', $data);
            exit();
        }
    }

    public function saveDocument() {
        if ($this->input->post('saveFile') && $this->input->post('saveFile') == 'yes') {
            echo json_encode($this->training->saveDocument());
            exit;
        }
    }

}

?>
