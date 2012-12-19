<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        //Define urls which should not be forwarded
        $url_exception[] = 'user/login';
        $url_exception[] = 'user/logout';
        $url_exception[] = 'help';
        
        if(!$this->session->userdata('id') AND !in_array(uri_string(),$url_exception)){
            $this->session->set_userdata('url', uri_string());
            
            redirect('/user/login', 'refresh');
        }

        $data['logged_in']  = $this->session->userdata('id');
        $data['admin']      = $this->session->userdata('admin');
        
        if($this->input->cookie('language')){
            $this->config->set_item('language', $this->input->cookie('language'));
            $this->lang->load('template', $this->input->cookie('language'));            
        }else{
            $this->lang->load('template', 'german');
        }
        
        $this->template->write('title',$this->lang->line('title'));
        //Loader title ändern
        $this->template->write('title_loader',$this->lang->line('title_loader'));
                        
        //Write information to template file
        $this->template->write_view('menu','template/menu',$data);
        
        $data_footer['logged_in']   = $data['logged_in'];
        $data_footer['version']     = '1.0';
        
        $data_footer['username']    = $this->session->userdata('username');
        $data_footer['last_login']  = $this->session->userdata('last_login');
        
        $this->template->write_view('footer','template/footer',$data_footer);
    }
}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */