<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

class Changelog extends MY_Controller {
    public function __construct() {
        parent::__construct();

        $this->lang->load('changelog', $this->session->userdata('language'));
    }
    
    public function index(){
        if($this->input->is_ajax_request() AND !empty($_POST)){
            $p_id   = $this->input->post('id');
            $p_type = $this->input->post('type');
            
            $this->load->model('changelog_model');
            
            $query = $this->changelog_model->get_logs($p_id, $p_type);
            
            $data['entry'] = false;
            
            if($query->num_rows() > 0){
                $data['entry'] = true;
                
                $data['logs'] = $query->result_object();
            }
            
            return $this->load->view('changelog/index',$data);
        }
    }
}

/* End of file changelog.php */
/* Location: ./application/controllers/changelog.php */