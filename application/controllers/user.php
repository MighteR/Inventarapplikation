<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
    public function create(){
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        $this->session->set_userdata('url',  uri_string());
        
        $this->lang->load('user', $this->session->userdata('language'));

//$this->lang->line('title_username')
        $this->form_validation->set_rules('username', $this->lang->line('title_username'), 'required|trim');
        $this->form_validation->set_rules('usernamae', $this->lang->line('title_username'), 'required|trim');

        if($this->form_validation->run()){
            
        }else{
            $this->lang->load('form_validation', $this->session->userdata('language'));
            
            
            $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');
            
            $data['title']          = $this->lang->line('title');
            $data['title_username'] = form_label($this->lang->line('title_username'),'username');
            $data['field_username'] = array('class' => 'formular',
                                            'name' => 'username',
                                            'id' => 'username',
                                            'value' => $this->form_validation->set_value('username'));
            
            if(form_error('username')){
                $data['field_username']['class'] = 'formular_error';
            }
            print_r($data);
            $this->template->write_view('content','user/create',$data);
        }
        
        $this->template->render();
    }
    
    public function index(){ 
        
            $data['firstname'] = 'Michel';
            $this->session->set_userdata('url',  uri_string());
            //$this->template->write_view('content','welcome_message',$data);
            $this->template->render();
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */