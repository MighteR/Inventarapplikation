<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

class Language extends CI_Controller {
    public function set($language) {
        if(!empty($language)){
            $this->load->model('language_model');

            if($this->language_model->check_language($language)){
                $this->input->set_cookie('language',$language,time()+60*60*24);
                $this->session->set_userdata('language',$language);
            }

            redirect($this->session->userdata('url'));
        }
    }
}

/* End of file language.php */
/* Location: ./application/controllers/language.php */