<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('user', $this->session->userdata('language'));
    }
    public function create(){
        $this->session->set_userdata('url',  uri_string());
        
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('username', 'lang:title_username', 'required|trim');
        $this->form_validation->set_rules('password', 'lang:title_password', 'required');
	$this->form_validation->set_rules('password_confirmation', 'lang:title_password_confirmation', 'required|matches[password]');

        if($this->form_validation->run()){
            $this->load->model('user_model');
            $this->user_model->insert();
            
            $data['title'] = 'asdfasfasdfasf';
            $data['info'] = 'sdfasdfasf';
            $data['title_next'] = 'bbbb';
            $data['url'] = 'afdf';
            $this->template->write_view('content','template/messages/info',$data);
        }else{
            $this->lang->load('form_validation', $this->session->userdata('language'));            
            
            $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');
            
            $data['title']          = $this->lang->line('title_create_user');
            $data['title_username'] = form_label($this->lang->line('title_username'),'username');
            $data['field_username'] = array('class' => 'formular',
                                            'name' => 'username',
                                            'id' => 'username',
                                            'value' => $this->form_validation->set_value('username'));
            
            $data['title_password'] = form_label($this->lang->line('title_password'),'password');
            $data['field_password'] = array('class' => 'formular',
                                            'name' => 'password',
                                            'id' => 'password');
            
            $data['title_password_confirmation'] = form_label($this->lang->line('title_password_confirmation'),'password_confirmation');
            $data['field_password_confirmation'] = array('class' => 'formular',
                                                         'name' => 'password_confirmation',
                                                         'id' => 'password_confirmation');
            
            $data['title_admin']    = form_label($this->lang->line('title_admin'),'admin');
            $data['field_admin']    = array('class' => 'formular',
                                            'name' => 'admin',
                                            'id' => 'admin',
                                            'checked' => $this->form_validation->set_value('admin'));
            
            $data['field_submit']    = array('name' => 'submit',
                                             'id' => 'submit',
                                             'value' => $this->lang->line('title_submit'));
                        
            $data['field_reset']    = array('name' => 'reset',
                                            'id' => 'reset',
                                            'value' => $this->lang->line('title_reset'));
            
            if(form_error('username')){
                $data['field_username']['class'] = 'formular_error';
            }
            
            if(form_error('password')){
                $data['field_password']['class'] = 'formular_error';
            }
            
            if(form_error('password_confirmation')){
                $data['field_password_confirmation']['class'] = 'formular_error';
            }
            
            $this->template->write_view('content','user/create',$data);
        }
        
        $this->template->render();
    }
    
    public function modify($id){
        //if admin rights
        if(true){
            $this->session->set_userdata('url',  uri_string());

            $this->load->model('user_model');
            
            $user_query = $this->user_model->get_user($id);
            
            if($user_query->num_rows() == 1){
                $old_username = '';
                $old_admin = '';
                
                if(empty($_POST)){
                    $data_user = $user_query->row_array();

                    $old_username = $data_user['username'];
                    $old_admin = $data_user['admin'];
                }
               
                $this->load->library('form_validation');
                $this->load->helper('form');

                $this->form_validation->set_rules('username', 'lang:title_username', 'required|trim');
                $this->form_validation->set_rules('password', 'lang:title_password','matches[password_confirmation]');
                $this->form_validation->set_rules('password_confirmation','lang:title_password_confirmation', 'matches[password]');

                if($this->form_validation->run()){
                    $this->load->model('user_model');
                    $this->user_model->update($id);
                    
                    $data['title'] = 'asdfasfasdfasf';
                    $data['info'] = 'sdfasdfasf';
                    $data['title_next'] = 'bbbb';
                    $data['url'] = 'afdf';
                    $this->template->write_view('content','template/messages/info',$data);
                }else{
                    $this->lang->load('form_validation', $this->session->userdata('language'));            

                    $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                    $data['title']          = $this->lang->line('title_modify_user');
                    $data['title_username'] = form_label($this->lang->line('title_username'),'username');
                    $data['field_username'] = array('class' => 'formular',
                                                    'name' => 'username',
                                                    'id' => 'username',
                                                    'value' => $this->form_validation->set_value('username',$old_username));

                    $data['title_password'] = form_label($this->lang->line('title_password'),'password');
                    $data['field_password'] = array('class' => 'formular',
                                                    'name' => 'password',
                                                    'id' => 'password');

                    $data['title_password_confirmation'] = form_label($this->lang->line('title_password_confirmation'),'password_confirmation');
                    $data['field_password_confirmation'] = array('class' => 'formular',
                                                                 'name' => 'password_confirmation',
                                                                 'id' => 'password_confirmation');

                    $data['title_admin']    = form_label($this->lang->line('title_admin'),'admin');
                    $data['field_admin']    = array('class' => 'formular',
                                                    'name' => 'admin',
                                                    'id' => 'admin',
                                                    'value' => 1,
                                                    'checked' => $this->form_validation->set_value('admin',$old_admin));

                    $data['field_submit']    = array('name' => 'submit',
                                                     'id' => 'submit',
                                                     'value' => $this->lang->line('title_submit'));

                    $data['field_reset']    = array('name' => 'reset',
                                                    'id' => 'reset',
                                                    'value' => $this->lang->line('title_reset'));

                    if(form_error('username')){
                        $data['field_username']['class'] = 'formular_error';
                    }

                    if(form_error('password')){
                        $data['field_password']['class'] = 'formular_error';
                    }

                    if(form_error('password_confirmation')){
                        $data['field_password_confirmation']['class'] = 'formular_error';
                    }

                    $this->template->write_view('content','user/modify',$data);
                }   
            }else{
                $data['title'] = 'error';
                $data['error'] = 'sdfasdfasf';
                $data['title_back'] = 'bbbb';
                $this->template->write_view('content','template/messages/error',$data);
            }
        }else{
            //no authorization
            $data['title'] = 'error';
            $data['error'] = 'sdfasdfasf';
            $data['title_back'] = 'bbbb';
            $this->template->write_view('content','template/messages/error',$data);
        }

       $this->template->render();
    }
    
    public function index($action = '',$page = ''){
        $this->session->set_userdata('url',  uri_string());
        
        if(true){
            if($action == 'list'){

            }else{
                $this->load->helper('form');
                
                $data['field_search_user'] = array('name' => 'search_user',
                                 'id' => 'search_user',
                                 'value' => $this->lang->line('title_submit'));

                $data['reset_user_search'] = array('name' => 'reset_user_search',
                                 'id' => 'reset_user_search',
                                 'value' => $this->lang->line('title_submit'));
                
                $this->template->write_view('content','user/index',$data);         
            }
        }else{
            //no authorization
            $data['title'] = 'error';
            $data['error'] = 'sdfasdfasf';
            $data['title_back'] = 'bbbb';
            $this->template->write_view('content','template/messages/error',$data);
        }
        $this->template->render();
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */