<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package_type extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('package_type', $this->session->userdata('language'));
    }
   
    public function create(){
        $this->session->set_userdata('url',  uri_string());
        
    }
    
    public function modify($id){
        $this->session->set_userdata('url',  uri_string());
        
    }
    
    public function delete(){
        
    }
    
    public function index(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->helper('form');
            
            $this->template->write_view('content','package_type/index');
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    public function indexList($page = 1){
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $p_name = $this->input->post('name');
                $p_page_output = $this->input->post('page_output');

                $this->load->model('package_type_model');
                $this->load->library('pages');
                
                $query = $this->package_type_model->get_package_type_by_name($p_name);

                if($query->num_rows() > 0){
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);

                    $query = $this->package_type_model->get_package_type_by_name($p_name,$this->pages->get_limit());
                    $data['package_types'] = $query->result_object();

                    $data['entry'] = true;
                }else{
                    $data['entry'] = false;
                }                

                $content = $this->load->view('package_type/index_list',$data,true);
                echo $content;
            }
            exit();
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
}
?>
