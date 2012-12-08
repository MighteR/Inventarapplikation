<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('report', $this->session->userdata('language'));
    }
    
    public function inventar(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->helper('form');
            
            $this->template->write_view('content','report/inventar');
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    public function price(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->helper('form');
            
            $this->template->write_view('content','report/price');
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
}
?>