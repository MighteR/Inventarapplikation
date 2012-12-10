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
    
    public function update($category){
        $this->session->set_userdata('url',  uri_string());
        
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->helper('currency_helper');

        $category_query = $this->category_model->get_category_by_id($category);

        if($category_query->num_rows() == 1 OR $category == 0){
            /*$this->load->model('lock_model');
            $this->lock_model->set_info('category',$id);

            if($this->lock_model->check() AND empty($_POST)){
                $this->load->library('messages');
                $this->messages->get_message('error',$this->lang->line('error_category_locked_by').$this->lock_model->get_info());
            }else{*/
                if(empty($_POST)){
                    $data['changed'] = 'false';

                    $query = $this->product_model->get_inventory($category);

                    $data['entry'] = false;

                    if($query->num_rows() > 0){
                        $data['inventory_list'] = $query->result_array();

                        $data['entry'] = true;
                    }  
                }else{
                    $data['changed']    = 'true';
                    $data['entry']      = true;
                    
                    $category_names = $this->input->post('category_name');
                    $product_ids = $this->input->post('product_id');
                    $product_names = $this->input->post('product_name');
                    $unit_ids = $this->input->post('unit_id');
                    $unit_names = $this->input->post('unit_name');
                    $unit_prices = $this->input->post('unit_price');
                    $unit_quantities = $this->input->post('unit_quantity');
                    $package_ids = $this->input->post('package_id');
                    $package_names = $this->input->post('package_name');
                    $package_prices = $this->input->post('package_price');
                    $package_quantities = $this->input->post('package_quantity');

                    $inventory = array();
                    for($i = 0; $i < count($product_ids); $i++){
                        $result = array();
                        $result['category_name'] = $category_names[$i];
                        $result['product_id'] = $product_ids[$i];
                        $result['product_name'] = $product_names[$i];
                        $result['unit_id'] = $unit_ids[$i];
                        $result['unit_name'] = $unit_names[$i];
                        $result['unit_price'] = $unit_prices[$i];
                        $result['unit_quantity'] = $unit_quantities[$i];
                        if(!empty($package_ids[$i])){
                            $result['package_id'] = $package_ids[$i];
                            $result['package_name'] = $package_names[$i];
                            $result['package_price'] = $package_prices[$i];
                            $result['package_quantity'] = $package_quantities[$i];
                        }else{
                            $result['package_id'] = '';
                            $result['package_name'] = '';
                            $result['package_price'] = '';
                            $result['package_quantity'] = '';
                        }
                        
                        array_push($inventory, $result);
                    }
                    
                    $data['inventory_list'] = $inventory;
                    
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('unit_quantity[]', 'lang:title_quantity', 'required|trim|greater_than[-1]');
                    $this->form_validation->set_rules('unit_price[]', 'lang:title_price', 'required|trim|greater_than[0]');
                    $this->form_validation->set_rules('package_quantity[]', 'lang:title_quantity', 'required|trim|greater_than[-1]');
                    $this->form_validation->set_rules('package_price[]', 'lang:title_price', 'required|trim|greater_than[0]');
                }

                $this->load->helper('form');
                
                if($this->form_validation->run()){
                    $model_data['name'] = $this->input->post('name');
                    //$model_data['parent_category']  = (!$this->input->post('parent_category')) ? NULL : $this->input->post('parent_category');
                    $model_data['general_report']  = $this->input->post('report');

                    //$this->category_model->update($id,$model_data);

                    $this->load->library('messages');
                    $this->messages->get_message('info',$this->lang->line('info_category_modified'),'category');
                }else{
                    $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                    //$data['id'] = $id;

                    $data['error_class_name'] = '';
                    //$data['error_class_parent_category'] = '';

                    if(form_error('name')){
                        $data['error_class_name'] = '_error';
                    }

                    /*if(form_error('parent_category')){
                        $data['error_class_parent_category'] = '_error';
                    }*/

                    $this->template->write_view('content','inventory/modify',$data);
                }
            //}
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_id_does_not_exist'));
        }
        
        $this->template->render();
    }
    
    //Form checks    
    public function date_check($str, $type){
        if(!empty($str)){
            $date_db = $_POST[$type.'_db'];

            if(!checkdate(substr($date_db,4,2),substr($date_db,6,2),substr($date_db,0,4))){
                $this->form_validation->set_message('date_check', $this->lang->line('error_no_date'));
                return FALSE;
            }else{
                return TRUE;
            }

        }else{
            return TRUE;
        }
    }
}

/* End of file category.php */
/* Location: ./application/controllers/category.php */