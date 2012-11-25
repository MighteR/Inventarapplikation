<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package_type extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('package_type', $this->session->userdata('language'));
    }
   
    public function create(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->library('form_validation');
            $this->load->helper('form');
            
            $data['changed'] = 'false';
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
            }
            
            $this->form_validation->set_rules('package_type', 'lang:title_package_type', 'required|trim');
            
            if($this->form_validation->run()){
                $model_data['name'] = $this->input->post('package_type');
                
                $this->load->model('package_type_model');
                $this->package_type_model->create($model_data);
                
                $this->load->library('messages');
                $this->messages->get_message('info',$model_data['name'].' '.$this->lang->line('info_package_type_created'),'package_type');
                
            }else{
            $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');
            
            $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');
            $data['error_class_package_type'] = '';
            
            if(form_error('package_type')){
                $data['error_class_package_type'] = '_error';
            }
            $this->template->write_view('content','package_type/create',$data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        $this->template->render();
        
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
