<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('report', $this->session->userdata('language'));
    }
    
    public function inventory(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->library('form_validation');
            $this->load->helper('form');
            $this->lang->load('category', $this->session->userdata('language'));
            $this->lang->load('inventory', $this->session->userdata('language'));
            
            $inventory_category = array();
            $inventory_category['id'] = 0;
            $inventory_category['text'] = $this->lang->line('title_whole_inventory');
        
            $data['inventory_category'] = json_encode($inventory_category);
            
            $data['changed'] = 'false';
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
            }
            
            $this->form_validation->set_rules('set_due_date', 'lang:title_due_date', 'required|trim');
            
            if($this->form_validation->run()){
                            //To Do
            }else{
                $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');
                
                $data['error_class_set_due_date'] = '';

                if(form_error('set_due_date')){
                    $data['error_class_set_due_date'] = '_error';
                }
            }
            
            $this->template->write_view('content','report/inventory',$data);
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    public function price(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->library('form_validation');
            $this->load->helper('form');
            
            $data['changed'] = 'false';
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
            }
            
            $this->template->write_view('content','report/price',$data);
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
}
?>