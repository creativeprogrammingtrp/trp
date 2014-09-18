<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Content extends CI_Controller{
    
    public  function __construct() {
        parent::__construct();
        $this->load->model("admin/content_model","content");
    }
    function perms(){
        $perms['Conetent'] = array('index','importEfin','importEin','deleteEfin','deleteEin');
        return $perms;			
    }
    public function index(){
        $data = array('title_page'=>"Content");
        if(isset($_POST['load']) && !empty($_POST['load']) && $_POST['load'] == 'yes'){
            echo json_encode($this->content->loadEfinAndEin()); 
            exit;
        }
         
        $this->system->parse("content/content.htm",$data);
    }
    public function importEfin(){
         $arr_data = $this->input->post('obj') ? $this->input->post('obj') : array();
         $url = 'resource/importxls/';
         $this->trp_lib->importCsv("efins",$arr_data,$url);
         if(isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes'){
            echo json_encode($this->content->loadEfinAndEin()); 
            exit;
        }

    }
      public function importEin(){
         $arr_data = $this->input->post('obj') ? $this->input->post('obj') : array();
         $url = 'resource/importxls/';
         $this->trp_lib->importCsv("eins",$arr_data,$url); 
         if(isset($_POST['senddata']) && !empty($_POST['senddata']) && $_POST['senddata'] == 'yes'){
            echo json_encode($this->content->loadEfinAndEin()); 
            exit;
        }
    }
    
    public function deleteEfin(){    
        if(!empty($_GET['efin'])){
            $efin = $this->lib->escape($_GET['efin']);
        }else{
            $efin = "";
        }
      header("Content-type: application/json");
      echo  json_encode($this->content->deleteEfin($efin));
      exit();
    }
   
    public function deleteEin(){    
        if(!empty($_GET['ein'])){
            $ein = $this->lib->escape($_GET['ein']);
        }else{
            $ein = "";
        }
      header("Content-type: application/json");
      echo  json_encode($this->content->deleteEin($ein));
      exit();
    }
}
    
?>
