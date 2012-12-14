<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('report', $this->session->userdata('language'));
    }
    
    public function inventory(){
        $this->session->set_userdata('url',  uri_string());
        
        $this->lang->load('category', $this->session->userdata('language'));
        $this->lang->load('inventory', $this->session->userdata('language'));

        $inventory_category = array();
        $inventory_category['id'] = 0;
        $inventory_category['text'] = $this->lang->line('title_whole_inventory');

        $data['inventory_category'] = json_encode($inventory_category);

        $this->template->write_view('content','report/inventory',$data);

        $this->template->render();
    }
    
    public function excel(){
        
        $p_id       =   $this->input->post('id');
        $p_due_date =   $this->input->post('set_due_date');
        
        $this->load->model('product_model');
        
        $query = $this->product_model->get_inventory($p_id,$p_due_date);
        
        $result = array();
        
        if($query->num_rows() > 0){
            $result['verify'] = true;
            
            require_once '../excel/PHPExcel.php';
            
            $excel = new PHPExcel();
            
            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $objWriter->save('php://output');
            
        }else{
            $result['verify'] = false;
        }
        

                

        $result['output'] = $this->input->post('id').$this->input->post('set_due_date');

        echo json_encode($result);    
        return;
    }
    
    public function price(){
        $this->session->set_userdata('url',  uri_string());
        
        if($this->session->userdata('admin')){
            $this->load->library('form_validation');
            $this->load->helper('form');
            
            $data['changed'] = 'false';
            
            if(!empty($_POST)){
                $data['changed'] = 'true';
            }
            
            $this->template->write_view('content','report/price',$data);
        }else{
            $this->load->library('messages');
            $this->messages->get_message('error',$this->lang->line('error_no_access'));
        }
        
        $this->template->render();
    }
    
    public function price_picture(){
        if($this->input->is_ajax_request() AND !empty($_POST)){
            $this->load->model('product_model');
            $this->load->helper('currency_helper');
            
            $p_id           = $this->input->post('id');
            $p_date_from    = $this->input->post('date_from');
            $p_date_to      = $this->input->post('date_to');

            $verify = true;
            $result = array();
            
            if(empty($p_date_from)){
                $verify = false;
                
                $result['error']['date_from'] = 'no_date';
            }elseif(!checkdate(substr($p_date_from,4,2),substr($p_date_from,6,2),substr($p_date_from,0,4))){
                $verify = false;
                
                $result['error']['date_from'] = 'not_a_date';
            }
            
            if(empty($p_date_to)){
                $verify = false;
                
                $result['error']['date_to'] = 'no_date';
            }elseif(!checkdate(substr($p_date_to,4,2),substr($p_date_to,6,2),substr($p_date_to,0,4))){
                $verify = false;
                
                $result['error']['date_to'] = 'not_a_date';
            }elseif(!empty($p_date_from) && $p_date_from > $p_date_to){
                $result['error']['date_from'] = 'from kann nicht später als to sein';
                $result['error']['date_to'] = 'from kann nicht später als to sein';
            }

            $product_query = $this->product_model->get_product_by_id($p_id);
            
            if($product_query->num_rows() == 0){
                $verify = false;
                
                $result['error']['product'] = 'no_product_found';
            }
            
            $result['verify'] = $verify;
            
            if($verify){
                $query = $this->product_model->get_product_trends($p_id, $p_date_from, $p_date_to);
   
                if($query->num_rows() > 0){
                    $result['unit_data'] = array();

                    foreach($query->result_object() AS $product){
                        array_push($result['unit_data'], array($product->timestamp*1000, (double)formatCurrency($product->price), (double)$product->quantity));
                    }

                    array_push($result['unit_data'], array(time()*1000, (double)formatCurrency($product->price), (double)$product->quantity));


                    $query = $this->product_model->get_package_trends($p_id, $p_date_from, $p_date_to);

                    if($query->num_rows() > 0){
                        $result['package_data'] = array();

                        foreach($query->result_object() AS $package){
                            array_push($result['package_data'], array($package->timestamp*1000, (double)formatCurrency($package->price), (double)$package->quantity));
                        }

                        array_push($result['package_data'], array(time()*1000, (double)formatCurrency($package->price), (double)$package->quantity));
                    }
                }else{
                    $result['verify'] = false;
                    $result['error']['trend'] = 'no_data';
                }
            }

            echo json_encode($result);
            return;
        }
    }
    
    function price_index(){
        
    }
}
?>