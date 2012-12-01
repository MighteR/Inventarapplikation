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
            
            $this->load->model('package_type_model');
            $this->load->model('category_model');
           
            $data['changed'] = 'false';
            $data['old_categories'] = json_encode(array());
            $data['old_package_type'] = json_encode(array());
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
                
                $query = $this->category_model->get_categories_by_id_list(explode(',', $this->input->post('categories')));
                
                if($query->num_rows() > 0){
                    $category_result = array();
                    
                    foreach($query->result() AS $category){
                        $list_array = array();
                        $list_array['id']   = $category->id;
                        $list_array['text'] = $category->name;
                        
                        array_push($category_result, $list_array);
                    }
                    
                    $data['old_categories'] = json_encode($category_result);
                }

                $query = $this->package_type_model->get_package_type_by_id($this->input->post('package_type'));
                
                if($query->num_rows() == 1){
                    $package_type = $query->row();
                    
                    $list_array = array();
                    $list_array['id']   = $package_type->id;
                    $list_array['text'] = $package_type->name;
                    $data['old_package_type'] = json_encode($list_array);
                }
            }
            
            $data['error_class_name'] = '';
            $data['error_class_package_type'] = '';
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
      
    //Form checks
    /*public function package_type_check($str){
        //Need to enhance check if name already exists...
        if(!empty($str)){
            $this->load->model('package_type');
            
            $query = $this->package_type->get_package_type_by_id($str);
            
            if($query->num_rows() == 0){
                $this->form_validation->set_message('package_type_check', $this->lang->line('error_package_type_doesnt_exist'));
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }*/
}
?>