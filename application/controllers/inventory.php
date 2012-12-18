<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends MY_Controller {
    public function __construct() {
        parent::__construct();

        $this->lang->load('inventory', $this->session->userdata('language'));
    }
    
    public function index(){
        $this->lang->load('category', $this->session->userdata('language'));
        
        $inventory_category['id'] = 0;
        $inventory_category['text'] = $this->lang->line('title_whole_inventory');
        
        $data['inventory_category'] = json_encode($inventory_category);

        $this->template->write_view('content','inventory/index',$data);
        
        $this->template->render();
    }
    
    public function indexList(){
        if($this->input->is_ajax_request() AND !empty($_POST)){
            $this->lang->load('product', $this->session->userdata('language'));
            
            $p_category = $this->input->post('category');

            $this->load->model('product_model');
            $this->load->helper('number');

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
        $this->lang->load('product', $this->session->userdata('language'));
        
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->helper('number');

        $category_query = $this->category_model->get_category_by_id($category);

        if($category_query->num_rows() == 1 OR $category == 0){
            /*$this->load->model('lock_model');
            $this->lock_model->set_info('category',$id);

            if($this->lock_model->check() AND empty($_POST)){
                $this->load->library('messages');
                $this->messages->get_message('error',$this->lang->line('error_category_locked_by').$this->lock_model->get_info());
            }else{*/
                $data['actual_date']    = date('d.m.Y');
                $data['actual_date_db'] = date('Ymd');
                
                if(empty($_POST)){
                    $data['changed'] = 'false';

                    $query = $this->product_model->get_inventory($category);

                    $data['entry'] = false;

                    if($query->num_rows() > 0){
                        $data['entry'] = true;
                        
                        $this->load->model('lock_model');

                        $data['inventory_list'] = $query->result_array();
                    }
                    
                    $locked_product = array();
                }else{
                    $data['changed']    = 'true';
                    $data['entry']      = true;
                    
                    $this->load->model('lock_model');

                    $product_ids        = $this->input->post('product_id');
                    $locked_product     = $this->input->post('locked_product');

                    $inventory  = array();

                    for($i = 0; $i < count($product_ids); $i++){
                        $result = array();
                        
                        $product_id = $product_ids[$i];
                        
                        $result['category_name']        = $this->input->post('category_name_'.$product_id);
                        $result['product_id']           = $product_id;
                        $result['product_name']         = $this->input->post('product_name_'.$product_id);
                        $result['unit_id']              = $this->input->post('unit_id_'.$product_id);
                        $result['unit_name']            = $this->input->post('unit_name_'.$product_id);
                        $result['unit_price']           = $this->input->post('unit_price_'.$product_id);
                        $result['unit_quantity']        = $this->input->post('unit_quantity_'.$product_id);
                        $result['unit_update_date']     = $this->input->post('unit_update_date_'.$product_id);
                        $result['unit_update_date_db']  = $this->input->post('unit_update_date_db_'.$product_id);
                        $result['old_unit_price']       = $this->input->post('old_unit_price_'.$product_id);
                        $result['old_unit_quantity']    = $this->input->post('old_unit_quantity_'.$product_id);
                        $result['old_unit_quantity']    = $this->input->post('old_unit_quantity_'.$product_id);
                        
                        $package_id = $this->input->post('package_id_'.$product_id);
                        if(!empty($package_id)){
                            $result['package_id']               = $package_id;
                            $result['package_name']             = $this->input->post('package_name_'.$product_id);
                            $result['package_price']            = $this->input->post('package_price_'.$product_id);
                            $result['package_quantity']         = $this->input->post('package_quantity_'.$product_id);
                            $result['old_package_price']        = $this->input->post('old_package_price_'.$product_id);
                            $result['old_package_quantity']     = $this->input->post('old_package_quantity_'.$product_id);
                            $result['package_update_date']      = $this->input->post('package_update_date_'.$product_id);
                            $result['package_update_date_db']   = $this->input->post('package_update_date_db_'.$product_id);
                        
                        }else{
                            $result['package_id']               = '';
                            $result['package_name']             = '';
                            $result['package_price']            = '';
                            $result['package_quantity']         = '';
                            $result['old_package_price']        = '';
                            $result['old_package_quantity']     = '';
                            $result['package_update_date']      = '';
                            $result['package_update_date_db']   = '';
                        }
                        
                        array_push($inventory, $result);
                    }
                    
                    $data['inventory_list'] = $inventory;                    
                }

                $this->load->library('form_validation');
                $this->load->helper('form');
                
                for($i = 0; $i < count($data['inventory_list']); $i++){
                    $product_id = $data['inventory_list'][$i]['product_id'];

                    $this->form_validation->set_rules('unit_quantity_'.$product_id, 'lang:title_quantity', 'required|trim|greater_than[-1]');
                    $this->form_validation->set_rules('unit_price_'.$product_id, 'lang:title_price_per_unit', 'required|trim|greater_than[0]');
                    $this->form_validation->set_rules('unit_update_date', 'lang:title_date', 'trim|callback_date_check[unit_update_date]');

                    if(!empty($result['package_id'])){
                        $this->form_validation->set_rules('package_quantity_'.$product_id, 'lang:title_quantity', 'required|trim|greater_than[-1]');
                        $this->form_validation->set_rules('package_price_'.$product_id, 'lang:title_price_per_package', 'required|trim|greater_than[0]');
                        $this->form_validation->set_rules('package_update_date', 'lang:title_date', 'trim|callback_date_check[package_update_date]');
                    }                    
                }
                
                if($this->form_validation->run()){
                    for($i = 0; $i < count($data['inventory_list']); $i++){
                        $product_id = $data['inventory_list'][$i]['product_id'];
                        
                        $model_data = array();
                    
                        $model_data['unit_price']           = $data['inventory_list'][$i]['unit_price'];
                        $model_data['unit_quantity']        = $data['inventory_list'][$i]['unit_quantity'];
                        $model_data['unit_update_date']     = $data['inventory_list'][$i]['unit_update_date_db'];
                        $model_data['package_price']        = $data['inventory_list'][$i]['package_price'];
                        $model_data['package_quantity']     = $data['inventory_list'][$i]['package_quantity'];
                        $model_data['package_update_date']  = $data['inventory_list'][$i]['package_update_date_db'];
                        
                        $model_data['old_unit_price']       = $data['inventory_list'][$i]['old_unit_price'];
                        $model_data['old_unit_quantity']    = $data['inventory_list'][$i]['old_unit_quantity'];
                        $model_data['old_package_price']    = $data['inventory_list'][$i]['old_package_price'];
                        $model_data['old_package_quantity'] = $data['inventory_list'][$i]['old_package_quantity'];
                        $model_data['old_package']          = $data['inventory_list'][$i]['package_id'];

                        $this->product_model->update($product_id,$model_data);
                    }

                    $this->load->library('messages');
                    $this->messages->get_message('info',$this->lang->line('info_inventory_modified'),'inventory');
                }else{
                    $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');
                    
                    $locked = array();

                    for($i = 0; $i < count($data['inventory_list']); $i++){
                        $product_id = $data['inventory_list'][$i]['product_id'];

                        $data['error_class_unit_quantity_'.$product_id]      = '';
                        $data['error_class_unit_price_'.$product_id]         = '';
                        $data['error_class_unit_update_date_'.$product_id]         = '';
                        $data['error_class_package_quantity_'.$product_id]   = '';
                        $data['error_class_package_price_'.$product_id]      = '';
                        $data['error_class_package_update_date_'.$product_id]      = '';
                        
                        if(form_error('unit_quantity_'.$product_id)){
                            $data['error_class_unit_quantity_'.$product_id] = '_error';
                        }
                        
                        if(form_error('unit_price_'.$product_id)){
                            $data['error_class_unit_price_'.$product_id] = '_error';
                        }
                        
                        if(form_error('package_quantity_'.$product_id)){
                            $data['error_class_package_quantity_'.$product_id] = '_error';
                        }
                        
                        if(form_error('package_price_'.$product_id)){
                            $data['error_class_package_price_'.$product_id] = '_error';
                        }
                        
                        $this->lock_model->set_info('product',$product_id);

                        if($this->lock_model->check() AND !in_array($product_id, $locked_product)){
                            $locked[$product_id] = $this->lang->line('error_product_locked_by').$this->lock_model->get_info();
                        }else{
                            $this->lock_model->create();
                        }
                    }

                    $data['locked'] = $locked;

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