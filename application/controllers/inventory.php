<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends MY_Controller {
    public function __construct() {
        parent::__construct();

        $this->lang->load('inventory', $this->session->userdata('language'));
        $this->lang->load('category', $this->session->userdata('language'));
    }
    
    public function index(){
        $this->session->set_userdata('url',  uri_string());        

        $this->load->helper('form');
        
        $inventory_category['id'] = 0;
        $inventory_category['text'] = $this->lang->line('title_whole_inventory');
        
        $data['inventory_category'] = json_encode($inventory_category);

        $this->template->write_view('content','inventory/index',$data);
        
        $this->template->render();
    }
    
    public function indexList(){
        if($this->input->is_ajax_request() AND !empty($_POST)){
            $p_category = $this->input->post('category');

            $this->load->model('product_model');
            $this->load->helper('currency_helper');

            $query = $this->product_model->get_inventory($p_category);

            $data['entry'] = false;

            if($query->num_rows() > 0){
                $data['inventory_list'] = $query->result_object();

                $data['entry'] = true;
            }         

            return $this->load->view('inventory/index_list',$data);
        }
        
        $this->template->render();
    }
}

/* End of file category.php */
/* Location: ./application/controllers/category.php */