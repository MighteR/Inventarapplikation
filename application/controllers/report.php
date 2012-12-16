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
        
        if($this->input->is_ajax_request() AND !empty($_POST)){

            $this->load->library('excel');

            //Delete existing .xlsx-files || DON'T TOUCH OR BE CAREFUL!!!
            $this->load->helper('file');
            delete_files(APPPATH.'/third_party/excel/output/');

            $this->lang->load('product', $this->session->userdata('language'));
            $this->lang->load('inventory', $this->session->userdata('language'));

            $p_id       =   $this->input->post('id');
            $p_due_date =   $this->input->post('set_due_date');
            $p_due_date_string = substr($p_due_date,6,2).'.'.substr($p_due_date,4,2).'.'.substr($p_due_date,0,4);

            $this->load->model('product_model');
            $this->load->model('category_model');

            $query = $this->product_model->get_inventory($p_id,$p_due_date);

            //Formatting the style for the document title
            $format_title = array(
                'font' => array(
                    'size' => 20,
                )
            );

            //Formatting the style for the category titles
            $format_cat = array(
                'font' => array(
                    'bold' => true,
            ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                    'rgb' => '81BEF7'
                    )
                )
            );
            
            $verify = true;
            $result  = array();
            
            if(empty($p_due_date)){
                $verify = false;
                
                $result['error']['due_date'] = $this->lang->line('error_no_date');
            }elseif(!checkdate(substr($p_due_date,4,2),substr($p_due_date,6,2),substr($p_due_date,0,4))){
                $verify = false;
                
                $result['error']['due_date'] = $this->lang->line('error_no_date_format');
            }
            
            $category_query = $this->category_model->get_category_by_id($p_id);
            
            if($category_query->num_rows() == 0 AND $p_id != 0){
                $verify = false;
                $result['error']['category'] = $this->lang->line('error_no_category_found');
            }
            
            if($verify){
                if($query->num_rows() > 0){
                    $row = 4;
                    $cat_name   = '';
                    $cat_tmp    = '';
                    $total_price = 0;

                    //Set sheet properties
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle('Inventar');
                    $this->excel->getProperties()->setCreator($this->session->userdata('username'));
                    $this->excel->getProperties()->setTitle($this->lang->line('title_inventory_by').' '.$p_due_date_string);

                    //Set AutoSize from comlumn 0 to 11
                    for($i = 0; $i < 12; $i++){

                        $this->excel->getActiveSheet()->getColumnDimensionByColumn($i,1)->setAutoSize(true);

                    }

                    //Set Sheet Title
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow(0,1,3,1);
                    $this->excel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($format_title);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,1, $this->lang->line('title_inventory_by').' '.$p_due_date_string);

                    //Set Column Titles
                    $this->excel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,3,$this->lang->line('title_product_name'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,3,$this->lang->line('title_unit'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,3,$this->lang->line('title_quantity'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,3,$this->lang->line('title_price_per_unit'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,3,$this->lang->line('title_sum'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,3,$this->lang->line('title_package_type'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,3,$this->lang->line('title_quantity'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8,3,$this->lang->line('title_price_per_package'));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9,3,$this->lang->line('title_sum'));

                    //Import data into Excel          
                    foreach($query->result_object() AS $product){

                        $cat_tmp = $product->category_name;

                        if($cat_tmp != $cat_name){

                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '');
                            $row++;
                            $cat_name = $cat_tmp;
                            $this->excel->getActiveSheet()->getStyle($row)->getFont()->setBold(true);
                            $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->applyFromArray($format_cat);
                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product->category_name);
                            $row++;

                        }

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product->product_name);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product->unit_name);
                        $this->excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product->unit_quantity);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product->unit_price);
                        $this->excel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ((double)$product->unit_price*(double)$product->unit_quantity));
                        $total_price += ((double)$product->unit_price*(double)$product->unit_quantity);

                        if($product->package_id != NULL){

                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product->package_name);
                            $this->excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $product->package_quantity);
                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $product->package_price);
                            $this->excel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, ((double)$product->package_price*(double)$product->package_quantity));
                            $total_price += ((double)$product->package_price*(double)$product->package_quantity);

                        }

                        $row++;

                    }

                    //Set Total Price
                    $row++;
                    $this->excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->applyFromArray($format_cat);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Total CHF');
                    $this->excel->getActiveSheet()->getStyle('B'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $total_price);

                    //Set filename
                    $filename = $this->lang->line('title_inventory').'_'.substr($p_due_date,6,2).substr($p_due_date,4,2).substr($p_due_date,0,4).'.'.'xlsx';

                   // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                   // header('Content-Disposition: attachment;filename="'.$filename.'"');
                   // header('Cache-Control: max-age=0'); //no cache

                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                    $objWriter->save(APPPATH.'/third_party/excel/output/'.$filename);
                    
                    $result['filename'] = $filename;
                }else{
                    $verify = false;
                    
                    $result['error']['excel'] = $this->lang->line('error_no_data');
                }
            }
            
            $result['verify'] = $verify;

            echo json_encode($result);
            return;
        
        }
    
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

                $result['error']['date_from'] = $this->lang->line('error_no_date');
            }elseif(!checkdate(substr($p_date_from,4,2),substr($p_date_from,6,2),substr($p_date_from,0,4))){
                $verify = false;
                
                $result['error']['date_from'] = $this->lang->line('error_not_a_date');
            }

            if(empty($p_date_to)){
                $verify = false;
                
                $result['error']['date_to'] = $this->lang->line('error_no_date');
            }elseif(!checkdate(substr($p_date_to,4,2),substr($p_date_to,6,2),substr($p_date_to,0,4))){
                $verify = false;
                
                $result['error']['date_to'] = $this->lang->line('error_not_a_date');
            }elseif(!empty($p_date_from) && $p_date_from > $p_date_to){
                $verify = false;
                
                $result['error']['date_from']   = $this->lang->line('error_date_from_in_future');
                $result['error']['date_to']     = $this->lang->line('error_date_from_in_future');
            }

            $product_query = $this->product_model->get_product_by_id($p_id);
            
            if($product_query->num_rows() == 0){
                $verify = false;
                
                $result['error']['product'] = $this->lang->line('error_no_product_found');
            }
            
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
                    $verify = false;
                    
                    $result['error']['trend'] = $this->lang->line('error_no_data');
                }
            }
            
            $result['verify'] = $verify;

            echo json_encode($result);
            return;
        }
    }
    
    function price_index(){
       if(true){
       //if($this->input->is_ajax_request() AND !empty($_POST)){
            $this->load->model('product_model');
            $this->load->helper('number_helper');
            
            $this->lang->load('product', $this->session->userdata('language'));
            
            $p_id           = 18;//$this->input->post('id');
            $p_date_from    = '20121201';//$this->input->post('date_from');
            $p_date_to      = '20121231';//$this->input->post('date_to');
            /*$p_id           = $this->input->post('id');
            $p_date_from    = $this->input->post('date_from');
            $p_date_to      = $this->input->post('date_to');*/

            $verify = true;
            $result = array();
            
            if(empty($p_date_from)){
                $verify = false;

                $result['error']['date_from'] = $this->lang->line('error_no_date');
            }elseif(!checkdate(substr($p_date_from,4,2),substr($p_date_from,6,2),substr($p_date_from,0,4))){
                $verify = false;
                
                $result['error']['date_from'] = $this->lang->line('error_not_a_date');
            }

            if(empty($p_date_to)){
                $verify = false;
                
                $result['error']['date_to'] = $this->lang->line('error_no_date');
            }elseif(!checkdate(substr($p_date_to,4,2),substr($p_date_to,6,2),substr($p_date_to,0,4))){
                $verify = false;
                
                $result['error']['date_to'] = $this->lang->line('error_not_a_date');
            }elseif(!empty($p_date_from) && $p_date_from > $p_date_to){
                $verify = false;
                
                $result['error']['date_from']   = $this->lang->line('error_date_from_in_future');
                $result['error']['date_to']     = $this->lang->line('error_date_from_in_future');
            }

            $product_query = $this->product_model->get_product_by_id($p_id);
            
            if($product_query->num_rows() == 0){
                $verify = false;
                
                $result['error']['product'] = $this->lang->line('error_no_product_found');
            }
            
            if($verify){
                $product_data = $product_query->row();
                
                $query = $this->product_model->get_product_prices($p_id, $p_date_from, $p_date_to);
   
                if($query->num_rows() > 0){
                    $data['prices'] = $query->result_object();              

                    $result['output'] = $this->load->view('report/price_list',$data, TRUE);
                }else{
                    $verify = false;
                    
                    $result['error']['trend'] = $this->lang->line('error_no_data');
                }
            }
            
            $result['verify'] = $verify;

            echo json_encode($result);
            return;
        }
    }
}
?>
