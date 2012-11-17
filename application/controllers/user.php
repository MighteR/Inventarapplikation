<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('user', $this->session->userdata('language'));
    }
    public function create(){
        $this->session->set_userdata('url',  uri_string());
        
        if(true){        
            $this->load->library('form_validation');
            $this->load->helper(array('form','language'));
            
            if(empty($_POST)){
                 $data['changed'] = 'false';
             }else{
                 $data['changed'] = 'true';
             }

            $this->form_validation->set_rules('username', 'lang:title_username', 'required|trim');
            $this->form_validation->set_rules('password', 'lang:title_password', 'required');
            $this->form_validation->set_rules('password_confirmation', 'lang:title_password_confirmation', 'required|matches[password]');
            $this->form_validation->set_rules('admin', '', '');

            if($this->form_validation->run()){
                $model_data['username'] = $this->input->post('username');
                $model_data['password'] = $this->input->post('password');
                $model_data['admin']    = $this->input->post('admin');

                $this->load->model('user_model');
                $this->user_model->create($model_data);

                $this->load->library('messages');
                $this->messages->get_message('info',$this->lang->line('info_user_created'),base_url().'user');
            }else{
                $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                $data['error_class_username'] = '';
                $data['error_class_password'] = '';
                $data['error_class_password_confirmation'] = '';
                if(form_error('username')){
                    $data['error_class_username'] = '_error';
                }

                if(form_error('password')){
                    $data['error_class_password'] = '_error';
                }

                if(form_error('password_confirmation')){
                    $data['error_class_password_confirmation'] = '_error';
                }

                $this->template->write_view('content','user/create',$data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    public function delete(){
        //if admin rights
        if(true){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $this->load->model('user_model');
                
                $this->user_model->delete($this->input->post('id'));
            }
        }
    }
    
    public function modify($id){
        //if admin rights
        if(true){
            $this->session->set_userdata('url',  uri_string());

            $this->load->model('user_model');
            
            $user_query = $this->user_model->get_user($id);
            
            if($user_query->num_rows() == 1){
                $this->load->helper('language');
                $this->load->model('lock_model');
                $this->lock_model->set_info('user',$id);
                
                if(empty($_POST) AND $this->lock_model->check()){
                    $this->load->library('messages');
                    $this->messages->get_message('error',$this->lang->line('error_user_locked_by').$this->lock_model->get_info());
                }else{
                    $data['old_username']   = '';
                    $data['old_admin']      = '';

                    if(empty($_POST)){
                        $this->lock_model->create();
                        
                        $data_user = $user_query->row_array();

                        $data['old_username']   = $data_user['username'];
                        $data['old_admin']      = ($data_user['admin'] == 1) ? TRUE : FALSE;
                        
                        $data['changed'] = 'false';
                    }else{
                        $data['changed'] = 'true';
                    }

                    $this->load->library('form_validation');
                    $this->load->helper('form');

                    $this->form_validation->set_rules('username', 'lang:title_username', 'required|trim');
                    $this->form_validation->set_rules('password', 'lang:title_password','matches[password_confirmation]');
                    $this->form_validation->set_rules('password_confirmation','lang:title_password_confirmation', 'matches[password]');
                    $this->form_validation->set_rules('admin', '', '');


                    if($this->form_validation->run()){
                        $model_data['username'] = $this->input->post('username');

                        if($this->input->post('password')){
                            $model_data['password'] = md5($this->input->post('password'));
                        }
                        $model_data['admin'] = $this->input->post('admin');
                        //$data['modifier'] = '';

                        $this->user_model->update($id,$model_data);

                        $this->load->library('messages');
                        $this->messages->get_message('info',$this->lang->line('info_user_modified'),base_url().'user');
                    }else{
                        $this->lang->load('form_validation', $this->session->userdata('language'));            

                        $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                        $data['id'] = $id;

                        $data['error_class_username'] = '';
                        $data['error_class_password'] = '';
                        $data['error_class_password_confirmation'] = '';
                        if(form_error('username')){
                            $data['error_class_username'] = '_error';
                        }

                        if(form_error('password')){
                            $data['error_class_password'] = '_error';
                        }

                        if(form_error('password_confirmation')){
                            $data['error_class_password_confirmation'] = '_error';
                        }

                        $this->template->write_view('content','user/modify',$data);
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
    
    public function index(){
        $this->session->set_userdata('url',  uri_string());
        
        if(true){
            $this->load->helper(array('form','language'));
            
            $this->template->write_view('content','user/index');
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        $this->template->render();
    }
    
    public function indexList($page = 1){
        if(true){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $p_username = $this->input->post('username');
                $p_page_output = $this->input->post('page_output');

                $this->load->model('user_model');
                $this->load->library('pages');
                $this->load->helper('language');
                
                $query = $this->user_model->get_users_by_username($p_username);

                if($query->num_rows() > 0){
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);

                    $query = $this->user_model->get_users_by_username($p_username,$this->pages->get_limit());
                    $data['users'] = $query->result_object();

                    $data['entry'] = true;
                }else{
                    $data['entry'] = false;
                }
                

                $content = $this->load->view('user/index_list',$data,true);
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

/* End of file user.php */
/* Location: ./application/controllers/user.php */