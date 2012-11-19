<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('category', $this->session->userdata('language'));
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
            
            $this->template->write_view('content','category/index');
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

                $this->load->model('category_model');
                $this->load->library('pages');
                
                $query = $this->user_model->get_categories_by_name($p_name);

                if($query->num_rows() > 0){
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);

                    $query = $this->user_model->get_categories_by_name($p_name,$this->pages->get_limit());
                    $data['users'] = $query->result_object();

                    $data['entry'] = true;
                }else{
                    $data['entry'] = false;
                }                

                $content = $this->load->view('category/index_list',$data,true);
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

/* End of file category.php */
/* Location: ./application/controllers/category.php */