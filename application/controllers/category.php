<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('category', $this->session->userdata('language'));
    }
    
    public function create(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){     
            $this->load->library('form_validation');
            $this->load->helper('form');
            
            $this->load->model('category_model');
            
            $data['changed'] = 'false';
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
             }

            $this->form_validation->set_rules('name', 'lang:title_name', 'required|trim');
            
            if($this->form_validation->run()){
                $model_data['name']             = $this->input->post('name');                
                $model_data['parent_category']  = ($this->input->post('parent_category') == 'NULL') ? NULL : $this->input->post('parent_category');

                $this->category_model->create($model_data);

                $this->load->library('messages');
                $this->messages->get_message('info',$this->lang->line('info_category_created'),'category');
            }else{
                $query = $this->category_model->get_all_categories();

                $data['categories_exists'] = false;

                if($query->num_rows() > 0){
                    $data['categories_exists'] = true;
                    
                    $data['categories'] = $query->result_object();
                }
                
                $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                $data['error_class_name'] = '';
                if(form_error('name')){
                    $data['error_class_name'] = '_error';
                }
                
                $this->template->write_view('content','category/create',$data);
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
            
            $this->load->model('category_model');
            $query = $this->category_model->get_all_categories(TRUE);
            
            $data['categories_exists'] = false;
            
            if($query->num_rows() > 0){
                $data['categories_exists'] = true;
                $data['categories'] = $query->result_object();
            }
            
            $this->template->write_view('content','category/index', $data);
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
                $p_category_with_child = $this->input->post('category_with_child');
                $p_page_output = $this->input->post('page_output');

                $this->load->model('category_model');
                
                if($p_category_with_child == 'all'){
                    $p_category_with_child = NULL;
                }
                
                $query = $this->category_model->get_category_list($p_name, $p_category_with_child);
                
                $data['entry'] = false;
                $data['pages'] = '';
                $data['categories'] = '';

                if($query->num_rows() > 0){
                    $this->load->library('pages');
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);
                    $data['pages'] = $this->pages->get_links('categories','search_category');

                    $query = $this->category_model->get_category_list($p_name, $p_category_with_child, $this->pages->get_limit());
                    $data['categories'] = $query->result_object();

                    $data['entry'] = true;
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