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
        
        $p_id       =   0;//$this->input->post('id');
        $p_due_date =   '20121231';//$this->input->post('set_due_date');
        
        $this->load->model('product_model');
        
        $query = $this->product_model->get_inventory($p_id,$p_due_date);
        
        $result = array();
        
        if($query->num_rows() > 0){
            $row = 1;
            $result['verify'] = true;
            $this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Inventar');
            
            $cat_name   = '';
            $cat_tmp    = '';
                      
            foreach($query->result_object() AS $product){
                
                $cat_tmp = $product->category_name;
                if($cat_tmp != $cat_name){
                    $cat_name = $cat_tmp;
                    $this->excel->getActiveSheet()->getStyle($row)->getFont()->setBold(true);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product->category_name);
                    $row++;
                }
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product->product_name);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product->unit_name);  
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product->unit_price);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product->unit_quantity);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ((double)$product->unit_price*(double)$product->unit_quantity));
            if($product->package_id != NULL){
                //$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, '');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product->package_name);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $product->package_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $product->package_quantity);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, ((double)$product->package_price*(double)$product->package_quantity));
              
            }
            $row++;
            }
            for($i = 0; $i < 12; $i++){
                $this->excel->getActiveSheet()->getColumnDimensionByColumn($i,1)->setAutoSize(true);
            }
            /*
            $filename='just_some_random_name.xlsx'; 
            

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
=======
            $filename = APPPATH.'third_party/excel/output/'.time().'.xlsx';
            $objWriter->save($filename);
            $result['filename'] = $filename;
*/
            
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
            $this->load->helper('number_helper');
            
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
                        array_push($result['unit_data'], array($product->timestamp*1000, (double)formatNumber($product->price), (double)$product->quantity));
                    }

                    array_push($result['unit_data'], array(time()*1000, (double)formatNumber($product->price), (double)$product->quantity));


                    $query = $this->product_model->get_package_trends($p_id, $p_date_from, $p_date_to);

                    if($query->num_rows() > 0){
                        $result['package_data'] = array();

                        foreach($query->result_object() AS $package){
                            array_push($result['package_data'], array($package->timestamp*1000, (double)formatNumber($package->price), (double)$package->quantity));
                        }

                        array_push($result['package_data'], array(time()*1000, (double)formatNumber($package->price), (double)$package->quantity));
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
