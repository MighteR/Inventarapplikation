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
            //$data['old_parent_category'] = json_encode(array());
            
            if(!empty($_POST)){
                $data['changed'] = 'true';

                /*$query = $this->category_model->get_category_by_id($this->input->post('parent_category'));
                
                if($query->num_rows() == 1){
                    $category = $query->row();
                    
                    $list_array = array();
                    $list_array['id'] = $category->id;
                    $list_array['text'] = $category->name;
                    $data['old_parent_category'] = json_encode($list_array);
                }*/
            }

            $this->form_validation->set_rules('name', 'lang:title_category_name', 'required|trim|is_unique[categories.name]');
            //$this->form_validation->set_rules('parent_category', 'lang:title_parent_category', 'callback_parent_category_check');
            $this->form_validation->set_rules('general_report');
            
            if($this->form_validation->run()){
                $model_data['name'] = $this->input->post('name');
                //$model_data['parent_category']  = (!$this->input->post('parent_category')) ? NULL : $this->input->post('parent_category');
                $model_data['general_report']  = $this->input->post('general_report');

                $this->category_model->create($model_data);

                $this->load->library('messages');
                $this->messages->get_message('info',$this->lang->line('info_category_created'),'category');
            }else{
                /*$query = $this->category_model->get_all_categories();

                $data['categories_exists'] = false;

                if($query->num_rows() > 0){
                    $data['categories_exists'] = true;
                }*/
                
                $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                $data['error_class_name'] = '';
                //$data['error_class_parent_category'] = '';
                
                if(form_error('name')){
                    $data['error_class_name'] = '_error';
                }
                /*if(form_error('parent_category')){
                    $data['error_class_parent_category'] = '_error';
                }*/

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
        
        if($this->session->userdata('admin')){
            $this->load->model('category_model');
            
            $category_query = $this->category_model->get_category_by_id($id);
            
            if($category_query->num_rows() == 1){
                $this->load->model('lock_model');
                $this->lock_model->set_info('category',$id);
                
                if($this->lock_model->check() AND empty($_POST)){
                    $this->load->library('messages');
                    $this->messages->get_message('error',$this->lang->line('error_category_locked_by').$this->lock_model->get_info());
                }else{
                    $this->lock_model->create();
                    
                    if(empty($_POST)){
                        $data['changed'] = 'false';
                        //$data['old_parent_category'] = json_encode(array());
                        
                        $category = $category_query->row();

                        $data['old_name']   = $category->name;
                        
                       /* $list_array = array();
                        $old_category_id = '';
                        
                        if($category->parent_id != NULL){
                            $list_array['id']   =   $category->parent_id;
                            $list_array['text'] =   $category->parent_name;
                            
                            $old_category_id    = $category->parent_id;
                        }
                        
                        $data['old_parent_category'] = json_encode($list_array);
                        $data['old_parent_category_list'] = $old_category_id;*/

                        $data['old_general_report']    = ($category->general_report == 1) ? TRUE : FALSE;
                    }else{
                        $data['changed'] = 'true';
                        
                        $data['old_name']   = $this->input->post('name');
                        $data['old_general_report']     = ($this->input->post('general_report') == 1) ? TRUE : FALSE;
                        
                        /*$query = $this->category_model->get_category_by_id($this->input->post('parent_category'));

                        $list_array = array();
                        if($query->num_rows() == 1){
                            $category = $query->row();

                            $list_array['id'] = $category->id;
                            $list_array['text'] = $category->name;
                        }
                        
                        $data['old_parent_category']        = json_encode($list_array);
                        $data['old_parent_category_list']   = $this->input->post('parent_category');*/
                    }

                    $this->load->library('form_validation');
                    $this->load->helper('form');

                    $this->form_validation->set_rules('name', 'lang:title_category_name', 'required|trim');
                    //$this->form_validation->set_rules('parent_category', 'lang:title_parent_category', 'callback_parent_category_check');
                    $this->form_validation->set_rules('generale_report');

                    if($this->form_validation->run()){
                        $model_data['name'] = $this->input->post('name');
                        //$model_data['parent_category']  = (!$this->input->post('parent_category')) ? NULL : $this->input->post('parent_category');
                        $model_data['general_report']  = $this->input->post('general_report');

                        $this->category_model->update($id,$model_data);
                        
                        $this->lock_model->remove();

                        $this->load->library('messages');
                        $this->messages->get_message('info',$this->lang->line('info_category_modified'),'category');
                    }else{
                        $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                        $data['id'] = $id;

                        $data['error_class_name'] = '';
                        //$data['error_class_parent_category'] = '';

                        if(form_error('name')){
                            $data['error_class_name'] = '_error';
                        }
                        
                        /*if(form_error('parent_category')){
                            $data['error_class_parent_category'] = '_error';
                        }*/

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
                
                $model_data = array();
                $model_data['deleted'] = 1;
                
                $this->category_model->update($this->input->post('id'), $model_data);
            }
        }
    }
    
    public function reactivate(){
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $this->load->model('category_model');
                
                $model_data = array();
                $model_data['deleted'] = 0;
                
                $this->category_model->update($this->input->post('id'), $model_data);
            }
        }
    }
    
    public function simple_search_list(){
        if($this->input->is_ajax_request() AND !empty($_POST)){
            $p_category_id  = $this->input->post('id');
            $p_inventory    = $this->input->post('inventory');
            $p_name         = $this->input->post('name');
            $p_page         = $this->input->post('page');

            $this->load->model('category_model');

            $query = $this->category_model->get_category_simple_list($p_name, $p_category_id, $p_inventory);

            $data_return['total']   = $query->num_rows();
            $data_return['results'] = array();
            
            if($query->num_rows() > 0){
                $this->load->library('pages');
                $this->pages->check_page($query->num_rows(),$p_page);

                $query = $this->category_model->get_category_simple_list($p_name, $p_category_id, $p_inventory, $this->pages->get_limit());

                $categories = $query->result_object();
                $data = array();
                
                $i = 0;
                foreach ($categories as $category){
                    $row_array['id']        = $category->id;
                    $row_array['text']      = $category->name;
                    $row_array['inventory'] = $category->general_report;
                    
                    array_push($data,$row_array);
                }
                
                $data_return['results'] = $data;
            }
            
            echo json_encode($data_return);
            return;
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
                //$p_category_with_child = $this->input->post('category_with_child');
                $p_general_report = $this->input->post('general_report');
                $p_page_output = $this->input->post('page_output');

                $this->load->model('category_model');
                
                /*if($p_category_with_child == 'all'){
                    $p_category_with_child = NULL;
                }*/
                
                $query = $this->category_model->get_category_list($p_name, $p_general_report);
                
                $data['entry'] = false;
                $data['pages'] = '';
                $data['categories'] = '';

                if($query->num_rows() > 0){
                    $this->load->library('pages');
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);
                    $data['pages'] = $this->pages->get_links('categories','search_category');

                    $query = $this->category_model->get_category_list($p_name, $p_general_report, NULL , $this->pages->get_limit());
                    $data['categories'] = $query->result_object();

                    $data['entry'] = true;
                }         

                return $this->load->view('category/index_list',$data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    //Form checks
    public function parent_category_check($str){
        if(!empty($str)){
            $this->load->model('category_model');
            
            $query = $this->category_model->get_category_by_id($str);
            
            if($query->num_rows() == 0){
                $this->form_validation->set_message('parent_category_check', $this->lang->line('error_category_doesnt_exist'));
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }
}

/* End of file category.php */
/* Location: ./application/controllers/category.php */