<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {
    public function index(){
        
        
            $data['firstname'] = 'Michel';
            $this->session->set_userdata('url',  uri_string());
            //$this->template->write_view('content','welcome_message',$data);
            $this->template->render();
    }
}

/* End of file category.php */
/* Location: ./application/controllers/category.php */