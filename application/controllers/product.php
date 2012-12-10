<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('product', $this->session->userdata('language'));
    }
    
     public function create(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->library('form_validation');
            $this->load->helper('form');
            
            $this->load->model('unit_model');
            $this->load->model('category_model');
           
            $data['changed'] = 'false';
            $data['old_categories']     = json_encode(array());
            $data['old_package_type']   = json_encode(array());
            $data['old_unit']           = json_encode(array());
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
                
                $query = $this->category_model->get_categories_by_id_list(explode(',', $this->input->post('categories')));
                
                if($query->num_rows() > 0){
                    $category_result = array();
                    
                    foreach($query->result() AS $category){
                        $list_array = array();
                        $list_array['id']           = $category->id;
                        $list_array['text']         = $category->name;
                        $list_array['inventory']    = $category->general_report;
                        
                        array_push($category_result, $list_array);
                    }
                    
                    $data['old_categories'] = json_encode($category_result);
                }

                $query = $this->unit_model->get_unit_by_id($this->input->post('unit'));
                
                if($query->num_rows() == 1){
                    $unit = $query->row();
                    
                    $list_array = array();
                    $list_array['id']   = $unit->id;
                    $list_array['text'] = $unit->name;
                    $data['old_unit'] = json_encode($list_array);
                }
                
                $query = $this->unit_model->get_unit_by_id($this->input->post('package_type'));
                
                if($query->num_rows() == 1){
                    $unit = $query->row();
                    
                    $list_array = array();
                    $list_array['id']   = $unit->id;
                    $list_array['text'] = $unit->name;
                    $data['old_package_type'] = json_encode($list_array);
                }
            }
            
            $this->form_validation->set_rules('name', 'lang:title_product_name', 'required|trim|is_unique[products.name]');
            $this->form_validation->set_rules('unit', 'lang:title_unit', 'required|trim|callback_unit_check');
            $this->form_validation->set_rules('categories', 'lang:title_categories', 'required|trim|callback_categories_check');
            $this->form_validation->set_rules('package_type', 'lang:title_unit', 'trim|callback_unit_check');
            $this->form_validation->set_rules('unit_price', 'lang:title_price_per_unit', 'required|trim|greater_than[0]');
            $this->form_validation->set_rules('package_price', 'lang:title_price_per_package', 'trim|greater_than[0]|callback_package_price_check');
            $this->form_validation->set_rules('unit_quantity', 'lang:title_quantity', 'trim|greater_than[-1]');
            $this->form_validation->set_rules('package_quantity', 'lang:title_quantity', 'trim|greater_than[-1]');
            
            if($this->form_validation->run()){
                $model_data['name']             = $this->input->post('name');
                $model_data['unit_id']          = $this->input->post('unit');
                $model_data['unit_price']       = $this->input->post('unit_price');
                $model_data['unit_quantity']    = $this->input->post('unit_quantity');
                $model_data['categories']       = $this->input->post('categories');
                $model_data['package_type']     = $this->input->post('package_type');
                $model_data['package_price']    = $this->input->post('package_price');
                $model_data['package_quantity'] = $this->input->post('package_quantity');

                $this->load->model('product_model');
                $this->product_model->create($model_data);

                $this->load->library('messages');
                $this->messages->get_message('info',$this->lang->line('info_product_created'),'product');
            }else{
                $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');

                $data['error_class_name'] = '';
                $data['error_class_unit'] = '';
                $data['error_class_categories'] = '';
                $data['error_class_package_type'] = '';
                $data['error_class_unit_price'] = '';
                $data['error_class_package_price'] = '';
                $data['error_class_unit_quantity'] = '';
                $data['error_class_package_quantity'] = '';
                
                if(form_error('name')){
                    $data['error_class_name'] = '_error';
                }
                
                if(form_error('categories')){
                    $data['error_class_categories'] = '_error';
                }
                
                if(form_error('unit')){
                    $data['error_class_unit'] = '_error';
                }
                
                if(form_error('package_type')){
                    $data['error_class_package_type'] = '_error';
                }
                
                if(form_error('unit_price')){
                    $data['error_class_unit_price'] = '_error';
                }
                
                if(form_error('package_price')){
                    $data['error_class_package_price'] = '_error';
                }
                
                if(form_error('unit_quantity')){
                    $data['error_class_unit_quantity'] = '_error';
                }
                
                if(form_error('package_quantity')){
                    $data['error_class_package_quantity'] = '_error';
                }

                $this->template->write_view('content','product/create', $data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
     }
     
    public function delete(){
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $this->load->model('product_model');
                
                $this->product_model->delete($this->input->post('id'));
            }
        }
    }
     
     public function modify($id){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->model('product_model');
            
            $product_query = $this->product_model->get_product_by_id($id);
            
            if($product_query->num_rows() == 1){
                $this->load->model('lock_model');
                $this->lock_model->set_info('product',$id);
                
                if($this->lock_model->check() AND empty($_POST)){
                    $this->load->library('messages');
                    $this->messages->get_message('error',$this->lang->line('error_product_locked_by').$this->lock_model->get_info());
                }else{
                    $data['actual_date']    = date('d.m.Y');
                    $data['actual_date_db'] = date('Ymd');
                    
                    $product = $product_query->row();

                    $data['old_name']   = $product->name;
                    $data['old_unit']   = $product->unit_name;
                    
                    $old_package_type = '';
                    if($product->package_name != NULL){
                        $old_package_type = $product->package_name;
                    }
                    
                    $old_package = $product->package_id;

                    $data['old_package_type'] = $old_package_type;

                    $query = $this->product_model->get_last_product_information($id);

                    $last_unit_update = '';
                    $old_unit_price = 0;
                    $old_unit_quantity = 0;

                    if($query->num_rows() == 1){
                        $unit = $query->row();

                        $last_unit_update       = $unit->timestamp;
                        $old_unit_price         = $unit->price;
                        $old_unit_quantity      = $unit->quantity;
                    }

                    $data['last_unit_update']           = $last_unit_update;
                    $data['old_unit_price']             = $old_unit_price;
                    $data['old_unit_quantity']          = $old_unit_quantity;
                    $data['old_unit_update_date']       = '';
                    $data['old_unit_update_date_db']    = '';

                    $query = $this->product_model->get_last_package_information($id);

                    $last_package_update = '';
                    $old_package_quantity = 0;
                    $old_package_price = 0;

                    if($query->num_rows() == 1){
                        $unit = $query->row();

                        $last_package_update    = $unit->timestamp;
                        $old_package_price      = $unit->price;
                        $old_package_quantity   = $unit->quantity;
                    }

                    $data['last_package_update']        = $last_package_update;
                    $data['old_package_price']          = $old_package_price;
                    $data['old_package_quantity']       = $old_package_quantity;
                    $data['old_package_update_date']    = '';
                    $data['old_package_update_date_db'] = '';
                    
                    if(empty($_POST)){
                        $data['changed'] = 'false';
                        $data['old_categories_list']     = json_encode(array());
                        
                        $this->lock_model->create();
                        
                         $query = $this->product_model->get_categories_by_product($id);

                        if($query->num_rows() > 0){
                            $old_categories = array();
                            $category_result = array();

                            foreach($query->result() AS $category){
                                $list_array = array();
                                $list_array['id']           = $category->id;
                                $list_array['text']         = $category->name;
                                $list_array['inventory']    = $category->general_report;

                                array_push($category_result, $list_array);
                                $old_categories[] = $list_array['id'];
                            }
                            
                            $data['old_categories'] = implode(',',$old_categories);
                            $data['old_categories_list'] = json_encode($category_result);
                        }
                    }else{
                        $this->load->model('unit_model');
                        $this->load->model('category_model');
                        
                        $data['changed'] = 'true';
                        
                        $data['old_product_name']           = $this->input->post('name');
                        $data['old_categories']             = $this->input->post('categories');
                        $data['old_unit_quantity']          = $this->input->post('unit_quantity');
                        $data['old_unit_price']             = $this->input->post('unit_quantity');
                        $data['old_unit_update_date']       = $this->input->post('unit_update_date');
                        $data['old_unit_update_date_db']    = $this->input->post('unit_update_date_db');
                        $data['old_package_quantity']       = $this->input->post('package_quantity');
                        $data['old_package_price']          = $this->input->post('package_price');
                        $data['old_package_update_date']    = $this->input->post('package_update_date');
                        $data['old_package_update_date_db'] = $this->input->post('package_update_date_db');
                        
                        $query = $this->category_model->get_categories_by_id_list(explode(',', $this->input->post('categories')));
                        
                        $data['old_categories_list'] = json_encode(array());
                        if($query->num_rows() > 0){
                            $category_result = array();

                            foreach($query->result() AS $category){
                                $list_array = array();
                                $list_array['id']           = $category->id;
                                $list_array['text']         = $category->name;
                                $list_array['inventory']    = $category->general_report;

                                array_push($category_result, $list_array);
                            }

                            $data['old_categories_list'] = json_encode($category_result);
                        }
                    }

                    $this->load->library('form_validation');
                    $this->load->helper('form');

                    $this->form_validation->set_rules('name', 'lang:title_product_name', 'required|trim');
                    $this->form_validation->set_rules('categories', 'lang:title_categories', 'required|trim|callback_categories_check');
                    $this->form_validation->set_rules('unit_price', 'lang:title_price_per_unit', 'required|trim|greater_than[0]');
                    $this->form_validation->set_rules('package_price', 'lang:title_price_per_package', 'trim|greater_than[0]');
                    $this->form_validation->set_rules('unit_quantity', 'lang:title_quantity', 'required|trim|greater_than[-1]');
                    $this->form_validation->set_rules('package_quantity', 'lang:title_quantity', 'trim|greater_than[-1]');
                    $this->form_validation->set_rules('unit_update_date', 'lang:title_price_update', 'trim|callback_date_check[unit_update_date]');
                    $this->form_validation->set_rules('package_update_date', 'lang:title_quantity', 'trim|callback_date_check[package_update_date]');

                    if($this->form_validation->run()){
                        $model_data['name']                 = $this->input->post('name');
                        $model_data['unit_price']           = $this->input->post('unit_price');
                        $model_data['unit_quantity']        = $this->input->post('unit_quantity');
                        $model_data['unit_update_date']     = $this->input->post('unit_update_date_db');
                        $model_data['categories']           = $this->input->post('categories');
                        $model_data['package_price']        = $this->input->post('package_price');
                        $model_data['package_quantity']     = $this->input->post('package_quantity');
                        $model_data['package_update_date']  = $this->input->post('package_update_date_db');
                        $model_data['old_unit_price']       = $old_unit_price;
                        $model_data['old_unit_quantity']    = $old_unit_quantity;
                        $model_data['old_package_price']    = $old_package_price;
                        $model_data['old_package_quantity'] = $old_package_quantity;
                        $model_data['old_package']          = $old_package;

                        $this->product_model->update($id,$model_data);

                        $this->load->library('messages');
                        $this->messages->get_message('info',$this->lang->line('info_product_modified'),'product');
                    }else{
                        $this->form_validation->set_error_delimiters('<div class="notice">', '</div>');
                        
                        $data['id'] = $id;

                        $data['error_class_name'] = '';
                        $data['error_class_unit'] = '';
                        $data['error_class_categories'] = '';
                        $data['error_class_package_type'] = '';
                        $data['error_class_unit_price'] = '';
                        $data['error_class_unit_update_date'] = '';
                        $data['error_class_package_price'] = '';
                        $data['error_class_package_update_date'] = '';
                        $data['error_class_unit_quantity'] = '';
                        $data['error_class_package_quantity'] = '';

                        if(form_error('name')){
                            $data['error_class_name'] = '_error';
                        }

                        if(form_error('categories')){
                            $data['error_class_categories'] = '_error';
                        }

                        if(form_error('unit')){
                            $data['error_class_unit'] = '_error';
                        }

                        if(form_error('package_type')){
                            $data['error_class_package_type'] = '_error';
                        }

                        if(form_error('unit_price')){
                            $data['error_class_unit_price'] = '_error';
                        }
                        
                        if(form_error('unit_update_date')){
                            $data['error_class_unit_update_date'] = '_error';
                        }

                        if(form_error('package_price')){
                            $data['error_class_package_price'] = '_error';
                        }
                        
                        if(form_error('package_update_date')){
                            $data['error_class_package_update_date'] = '_error';
                        }

                        if(form_error('unit_quantity')){
                            $data['error_class_unit_quantity'] = '_error';
                        }

                        if(form_error('package_quantity')){
                            $data['error_class_package_quantity'] = '_error';
                        }

                        $this->template->write_view('content','product/modify', $data);
                    }
                }
            }else{
                $this->load->library('messages');
                $this->messages->get_message('error',$this->lang->line('error_id_does_not_exist'));
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
     }
     
    public function index(){
      $this->session->set_userdata('url',  uri_string());

      if($this->session->userdata('admin')){
          $this->load->helper('form');

          $this->template->write_view('content','product/index');
      }else{
          $this->load->library('messages');
          $this->messages->get_message('error',$this->lang->line('error_no_access'));
      }

      $this->template->render();
    }
      
    public function indexList($page = 1){
        if($this->session->userdata('admin')){
            if($this->input->is_ajax_request() AND !empty($_POST)){
                $p_name = $this->input->post('name');
                $p_page_output = $this->input->post('page_output');

                $this->load->model('product_model');
                $this->load->library('pages');
                
                $query = $this->product_model->get_product_by_name($p_name);

                if($query->num_rows() > 0){
                    $this->pages->check_page($query->num_rows(),$page,true,$p_page_output);

                    $query = $this->product_model->get_product_by_name($p_name,$this->pages->get_limit());
                    $data['products'] = $query->result_object();

                    $data['entry'] = true;
                }else{
                    $data['entry'] = false;
                }                

                return $this->load->view('product/index_list',$data);
            }
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    //Form checks
    public function unit_check($str){
        if(!empty($str)){
            $this->load->model('unit_model');
            
            $query = $this->unit_model->get_unit_by_id($str);
            
            if($query->num_rows() == 0){
                $this->form_validation->set_message('unit_check', $this->lang->line('error_unit_doesnt_exist'));
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }
    
    public function categories_check($str){
        if(!empty($str)){
            $this->load->model('category_model');
            
            $query = $this->category_model->get_categories_by_id_list(explode(',',$str));

            if($query->num_rows() != count(explode(',', $str))){
                $this->form_validation->set_message('categories_check', $this->lang->line('error_categories_doesnt_exist'));
                return FALSE;
            }else{
                $inventory_category = 0;;
                
                foreach($query->result() AS $category){
                    if($category->general_report){
                        $inventory_category++;
                    }
                }
                
                if($inventory_category == 0){
                    $this->form_validation->set_message('categories_check', $this->lang->line('error_no_inventar_category'));    
                    return FALSE;
                }elseif($inventory_category > 1){
                    $this->form_validation->set_message('categories_check', $this->lang->line('error_max_inventar_category'));    
                    return FALSE;
                }
                
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }
    
    public function package_price_check($str){
        if(!empty($_POST['package_type']) AND empty($str)){
            $this->form_validation->set_message('package_price_check', $this->lang->line('error_price_is_mandatory'));
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
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
?>
