<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('user', $this->session->userdata('language'));
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

            $this->form_validation->set_rules('username', 'lang:title_username', 'required|trim');
            $this->form_validation->set_rules('password', 'lang:title_password', 'required');
            $this->form_validation->set_rules('password_confirmation', 'lang:title_password_confirmation', 'required|matches[password]');
            $this->form_validation->set_rules('admin');
            
            if($this->form_validation->run()){
                $model_data['username'] = $this->input->post('username');
                $model_data['password'] = $this->input->post('password');
                $model_data['admin']    = $this->input->post('admin');

                $this->load->model('user_model');
                $this->user_model->create($model_data);

                $this->load->library('messages');
                $this->messages->get_message('info',$this->lang->line('info_user_created'),'user');
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
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $this->load->model('user_model');
                
                $this->user_model->delete($this->input->post('id'));
            }
        }
    }
    
    public function modify($id){
        if($this->session->userdata('admin')){
            $this->session->set_userdata('url',  uri_string());

            $this->load->model('user_model');
            
            $user_query = $this->user_model->get_user_by_id($id);
            
            if($user_query->num_rows() == 1){
                $this->load->model('lock_model');
                $this->lock_model->set_info('user',$id);
                
                if($this->lock_model->check() AND empty($_POST)){
                    $this->load->library('messages');
                    $this->messages->get_message('error',$this->lang->line('error_user_locked_by').$this->lock_model->get_info());
                }else{
                    if(empty($_POST)){
                        $data['changed'] = 'false';
                        
                        $this->lock_model->create();
                        
                        $data_user = $user_query->row_array();

                        $data['old_username']   = $data_user['username'];
                        $data['old_admin']      = ($data_user['admin'] == 1) ? TRUE : FALSE;
                        
                    }else{
                        $data['changed'] = 'true';
                        
                        $data['old_username']   = '';
                        $data['old_admin']      = ($this->input->post('admin') == 1) ? TRUE : FALSE;
                    }

                    $this->load->library('form_validation');
                    $this->load->helper('form');

                    $this->form_validation->set_rules('username', 'lang:title_username', 'required|trim');
                    $this->form_validation->set_rules('password', 'lang:title_password','matches[password_confirmation]');
                    $this->form_validation->set_rules('password_confirmation','lang:title_password_confirmation', 'matches[password]');
                    $this->form_validation->set_rules('admin');

                    if($this->form_validation->run()){
                        $model_data['username'] = $this->input->post('username');

                        if($this->input->post('password')){
                            $model_data['password'] = md5($this->input->post('password'));
                        }
                        $model_data['admin'] = $this->input->post('admin');

                        $this->user_model->update($id,$model_data);

                        $this->load->library('messages');
                        $this->messages->get_message('info',$this->lang->line('info_user_modified'),'user');
                    }else{
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
        
        if($this->session->userdata('admin')){
            $this->load->helper('form');
            
            $this->template->write_view('content','user/index');
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    public function indexList($page = 1){
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $p_username = $this->input->post('username');
                $p_page_output = $this->input->post('page_output');

                $this->load->model('user_model');
                
                $query = $this->user_model->get_users_by_username($p_username);
                
                $data['entry'] = false;
                $data['pages'] = '';
                $data['users'] = '';

                if($query->num_rows() > 0){
                    $this->load->library('pages');
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);
                    $data['pages'] = $this->pages->get_links('users','search_user');
                    
                    $query = $this->user_model->get_users_by_username($p_username,$this->pages->get_limit());
                    $data['users'] = $query->result_object();

                    $data['entry'] = true;
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
    
    public function login(){
        if(!$this->session->userdata('id')){
            $this->session->set_userdata('url',  uri_string());

            $this->load->library('form_validation');
            $this->load->helper('form');

            $this->form_validation->set_rules('login', '', 'callback_login_check');
            $this->form_validation->set_rules('username', 'lang:title_username', 'required|trim');
            $this->form_validation->set_rules('password', 'lang:title_password', 'required');

            if($this->form_validation->run()){
                $this->load->library('messages');
                $this->messages->get_message('info',$this->lang->line('info_user_logged_in'),$this->session->userdata('login_url'));
            }else{
                $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                $data['error_class_username'] = '';
                $data['error_class_password'] = '';

                 if(form_error('username') OR form_error('login')){
                      $data['error_class_username'] = '_error';
                  }

                  if(form_error('password') OR form_error('login')){
                      $data['error_class_password'] = '_error';
                  }

                    $this->template->write_view('content','user/login',$data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('info',$this->lang->line('info_user_already_logged_in'),$this->session->userdata('url'));
        }
      
        $this->template->render();
    }
    
    public function logout(){
        $this->load->library('messages');
        
        if($this->session->userdata('id')){
            $this->session->sess_destroy();

            $this->messages->get_message('info',$this->lang->line('info_user_logged_out'),'user/login');
        }else{
            $this->messages->get_message('info',$this->lang->line('info_user_not_logged_in'),'user/login');
        }
        $this->template->render();
    }
    
    //Form checks
    public function login_check(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if($username AND $password){
            $this->load->model('user_model');

            $user = $this->user_model->get_user_by_login($username,$password);

            if($user){
                $this->session->set_userdata('id',$user->id);
                $this->session->set_userdata('username',$user->username);
                $this->session->set_userdata('admin',$user->admin);

                return TRUE;
            }else{
                $this->form_validation->set_message('login_check',$this->lang->line('error_login'));
                return FALSE;
            }
        }else{
            return TRUE;
        }
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */