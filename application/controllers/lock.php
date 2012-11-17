<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lock extends CI_Controller {
    public function delete(){
        if($this->input->is_ajax_request() AND !empty($_POST)){
            $this->load->model('lock_model');
            
            $this->lock_model->remove($this->input->post('type'),$this->input->post('id'));
        }
    }
}

/* End of file lock.php */
/* Location: ./application/controllers/lock.php */