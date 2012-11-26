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

            $this->form_validation->set_rules('name', 'lang:title_category', 'required|trim');
            $this->form_validation->set_rules('parent_category');
            $this->form_validation->set_rules('report');
            
            if($this->form_validation->run()){
                $model_data['name'] = $this->input->post('name');
                
                if(!$this->input->post('parent_category') OR
                        $this->input->post('parent_category') == 'NULL'){
                    $model_data['parent_category'] = NULL;
                }else{
                    $model_data['parent_category'] = $this->input->post('parent_category');
                }
                //$model_data['parent_category']  = (!$this->input->post('parent_category') OR $this->input->post('parent_category') == 'NULL') ? NULL : $this->input->post('parent_category');
                $model_data['generate_report']  = $this->input->post('report');

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
        if($this->session->userdata('admin')){
            $this->session->set_userdata('url',  uri_string());

            $this->load->model('category_model');
            
            $category_query = $this->category_model->get_category_by_id($id);
            
            if($category_query->num_rows() == 1){

                $this->load->model('lock_model');
                $this->lock_model->set_info('category',$id);
                
                if($this->lock_model->check() AND empty($_POST)){
                    $this->load->library('messages');
                    $this->messages->get_message('error',$this->lang->line('error_category_locked_by').$this->lock_model->get_info());
                }else{
                    if(empty($_POST)){
                        $data['changed'] = 'false';
                        
                        $this->lock_model->create();
                        
                        $data_category = $category_query->row_array();

                        $data['old_name']               = $data_category['name'];
                        $data['old_parent_category']    = $data_category['parent_category'];
                        $data['old_report']             = ($data_category['generate_report'] == 1) ? TRUE : FALSE;
                    }else{
                        $data['changed'] = 'true';
                        
                        $data['old_name']   = '';
                        $data['old_parent_category']    = $this->input->post('parent_category');
                        $data['old_report']             = ($this->input->post('generate_report') == 1) ? TRUE : FALSE;
                    }

                    $this->load->library('form_validation');
                    $this->load->helper('form');

                    $this->form_validation->set_rules('name', 'lang:title_category', 'required|trim');
                    $this->form_validation->set_rules('parent_category');
                    $this->form_validation->set_rules('report');

                    if($this->form_validation->run()){
                        $model_data['name'] = $this->input->post('name');

                        if(!$this->input->post('parent_category') OR
                                $this->input->post('parent_category') == 'NULL'){
                            $model_data['parent_category'] = NULL;
                        }else{
                            $model_data['parent_category'] = NULL;
                        }
                        //$model_data['parent_category']  = (!$this->input->post('parent_category') OR $this->input->post('parent_category') == 'NULL') ? NULL : $this->input->post('parent_category');
                        $model_data['generate_report']  = $this->input->post('report');

                        $this->user_model->update($id,$model_data);

                        $this->load->library('messages');
                        $this->messages->get_message('info',$this->lang->line('info_user_modified'),'user');
                    }else{
                        $query = $this->category_model->get_all_categories(FALSE, $id);

                        $data['categories_exists'] = false;

                        if($query->num_rows() > 0){
                            $data['categories_exists'] = true;

                            $data['categories'] = $query->result_object();
                        }

                        $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                        $data['id'] = $id;

                        $data['error_class_name'] = '';

                        if(form_error('name')){
                            $data['error_class_name'] = '_error';
                        }

                        $this->template->write_view('content','category/modify',$data);
                    }
                }    
            }else{
                $this->load->library('messages');
                $this->messages->get_message('error',$this->lang->line('error_id_does_not_exist'));
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }

       $this->template->render();
    }
    
    public function delete(){
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $this->load->model('category_model');
                
                $this->category_model->delete($this->input->post('id'));
            }
        }
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