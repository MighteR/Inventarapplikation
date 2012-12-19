<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

class Unit extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('unit', $this->session->userdata('language'));
    }
   
    public function create(){
        if($this->session->userdata('admin')){
            $this->load->library('form_validation');
            $this->load->helper('form');
            
            $data['changed'] = 'false';
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
            }
            
            $this->form_validation->set_rules('name', 'lang:title_unit_name', 'required|trim');
            $this->form_validation->set_rules('package_type');
            
            if($this->form_validation->run()){
                $model_data['name']         = $this->input->post('name');
                $model_data['package_type'] = $this->input->post('package_type');
                
                $this->load->model('unit_model');
                $this->unit_model->create($model_data);
                
                $this->load->library('messages');
                $this->messages->get_message('info',$this->lang->line('info_unit_created'),'unit');                
            }else{
                $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                $data['error_class_name'] = '';

                if(form_error('name')){
                    $data['error_class_name'] = '_error';
                }
                
                $this->template->write_view('content','unit/create',$data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();        
    }
    
    public function modify($id){
        if($this->session->userdata('admin')){
            $this->load->model('unit_model');
            
            $unit_query = $this->unit_model->get_unit_by_id($id);
            
            if($unit_query->num_rows() == 1){
                $this->load->model('lock_model');
                $this->lock_model->set_info('unit',$id);
                
                if($this->lock_model->check() AND empty($_POST)){
                    $this->load->library('messages');
                    $this->messages->get_message('error',$this->lang->line('error_unit_locked_by').$this->lock_model->get_info());
                }else{
                    $this->lock_model->create();
                    
                    if(empty($_POST)){
                        $data['changed'] = 'false';
                        
                        
                        $data_query = $unit_query->row_array();

                        $data['old_name']           = $data_query['name'];
                        $data['old_package_type']   = ($data_query['package_type'] == 1) ? TRUE : FALSE;
                        
                    }else{
                        $data['changed'] = 'true';
                        
                        $data['old_name']           = '';
                        $data['old_package_type']   = ($this->input->post('old_package_type') == 1) ? TRUE : FALSE;
                    }

                    $this->load->library('form_validation');
                    $this->load->helper('form');

                    $this->form_validation->set_rules('name', 'lang:title_unit_name', 'required|trim');
                    $this->form_validation->set_rules('package_type');

                    if($this->form_validation->run()){
                        $model_data['name']         = $this->input->post('name');
                        $model_data['package_type'] = $this->input->post('package_type');

                        $this->unit_model->update($id,$model_data);
                        
                        $this->lock_model->remove();

                        $this->load->library('messages');
                        $this->messages->get_message('info',$this->lang->line('info_unit_modified'),'unit');
                    }else{
                        $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                        $data['id'] = $id;

                        $data['error_class_name'] = '';

                        if(form_error('name')){
                            $data['error_class_name'] = '_error';
                        }

                        $this->template->write_view('content','unit/modify',$data);
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
                $this->load->model('unit_model');
                
                $model_data = array();
                $model_data['deleted'] = 1;
                
                $this->unit_model->update($this->input->post('id'), $model_data);
            }
        }
        
    }
    
    public function reactivate(){
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $this->load->model('unit_model');
                
                $model_data = array();
                $model_data['deleted'] = 0;
                
                $this->unit_model->update($this->input->post('id'), $model_data);
            }
        }
    }
    
    public function simple_search_list(){
        if($this->input->is_ajax_request() AND !empty($_POST)){
            $p_unit_id      = $this->input->post('id');
            $p_name         = $this->input->post('name');
            $p_unit_type    = $this->input->post('unit_type');
            $p_page         = $this->input->post('page');

            $this->load->model('unit_model');

            $query = $this->unit_model->get_unit_simple_list($p_name, $p_unit_id, $p_unit_type);

            $data_return['total']   = $query->num_rows();
            $data_return['results'] = array();
            
            if($query->num_rows() > 0){
                $this->load->library('pages');
                $this->pages->check_page($query->num_rows(),$p_page);

                $query = $this->unit_model->get_unit_simple_list($p_name, $p_unit_id, $p_unit_type, $this->pages->get_limit());

                $units = $query->result_object();
                $data = array();
                
                $i = 0;
                foreach ($units as $unit){
                    $row_array['id']    = $unit->id;
                    $row_array['text']  = $unit->name;
                    array_push($data,$row_array);
                }
                
                $data_return['results'] = $data;
            }
            
            echo json_encode($data_return);
            return;
        }
    }
    
    public function index(){
        if($this->session->userdata('admin')){
            $this->load->helper('form');
            
            $this->template->write_view('content','unit/index');
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

                $this->load->model('unit_model');
                $this->load->library('pages');
                
                $query = $this->unit_model->get_unit_list($p_name);

                if($query->num_rows() > 0){
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);

                    $query = $this->unit_model->get_unit_list($p_name,$this->pages->get_limit());
                    $data['units'] = $query->result_object();

                    $data['entry'] = true;
                }else{
                    $data['entry'] = false;
                }                

                return $this->load->view('unit/index_list',$data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
}

/* End of file unit.php */
/* Location: ./application/controllers/unit.php */
?>
