<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('product', $this->session->userdata('language'));
    }
    
     public function create(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->helper('form');
            
            $data['error_class_name'] = '';
            $data['changed'] = 'false';
            
            $this->template->write_view('content','product/create', $data);
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
     }
     
     public function delete(){
         
     }
     
     public function modify($id){
         
     }
     
      public function index(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->helper('form');
            
            $this->template->write_view('content','product/index');
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
      }
      
      public function indexList($page = 1){
          
      }
}
?>