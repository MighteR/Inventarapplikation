<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('help', $this->session->userdata('language'));
    }
    
    public function index(){
        $this->load->helper('form');

        $this->template->write_view('content','help/index');
        
        $this->template->render();
    }
}
?>