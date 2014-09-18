<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compliancetest extends CI_Controller {

    public function __construct() {
        parent::__construct();
       // $this->load->model('admin/Dashboard_model');
    }

    public function perms() {
        $perms['Compliance Test'] = array('index');
        return $perms;
    }

    public function index() {
       
        $data['title_page'] = 'Pass Compliance Test';

         $this->system->parse("setuperoinfo/compliancetest.htm", $data);
    }

//end index function

    

}

?>