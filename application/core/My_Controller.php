<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if($this->input->cookie('language')){
            $this->lang->load('template', $this->input->cookie('language'));
        }else{
            $this->lang->load('template', 'english');
        }
        
        $this->template->write('title',$this->lang->line('title'));
        $this->template->write('version','0.1');
    }
}
/* End of file My_Controller.php */
/* Location: ./application/core/My_Controller.php */