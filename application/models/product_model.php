<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {
    var $id = '';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $creator            = $this->session->userdata('id');
        $creation_timestamp = date('Y-m-d H:i:s');
        
        $product_data = array();
        $product_data['creator'] = $creator;
        $product_data['creation_timestamp'] = $creation_timestamp;
        $product_data['name'] = $data['name'];
        $product_data['unit_id'] = $data['unit_id'];
        
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        $this->db->insert('products', $product_data);
        
        $product_id = $this->db->insert_id();
        
        $product_price_data = array();
        $product_price_data['creator'] = $creator;
        $product_price_data['creation_timestamp'] = $creation_timestamp;
        $product_price_data['product_id'] = $product_id;
        $product_price_data['timestamp'] = $creation_timestamp;
        $product_price_data['quantity'] = $data['unit_quantity'];
        $product_price_data['price'] = $data['unit_price'];
        
        $this->insert_product_price($product_price_data);
        
        $product_categories_data['product_id'] = $product_id;
        $product_categories_data['categories'] = $data['categories'];
        
        $this->insert_product_categories($product_categories_data);
        
        if(!empty($data['package_type'])){
            $package_type_data = array();
            
            $package_type_data['creator'] = $creator;
            $package_type_data['creation_timestamp'] = $creation_timestamp;
            $package_type_data['product_id'] = $product_id;
            $package_type_data['unit_id'] = $data['package_type'];
            $package_type_data['timestamp'] = $creation_timestamp;
            $package_type_data['quantity'] = $data['package_quantity'];
            $package_type_data['price'] = $data['package_price'];
        
            $this->insert_package_type_price($package_type_data);
        }
        
        $this->db->trans_complete();
    }
    
    public function update($id, $data){
        $modifier               = $this->session->userdata('id');
        $modification_timestamp = date('Y-m-d H:i:s');

        $product_data = array();
        
        $product_data['name'] = $data['name'];
        $product_data['modifier'] = $modifier;
        $product_data['modification_timestamp'] = $modification_timestamp;
  
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        
        $this->db->update('products', $product_data, array('id' => $id));

        if($data['unit_price'] != $data['old_unit_price'] OR
                $data['unit_quantity'] != $data['old_unit_quantity']){
            $product_price_data = array();
            
            $timestamp = $data['unit_update_date'];
            $timestamp = mktime(date('H'),date('i'),date('s'),substr($timestamp,4,2),substr($timestamp,6,2),substr($timestamp,0,4));
            
            $product_price_data['creator']              = $modifier;
            $product_price_data['creation_timestamp']   = $modification_timestamp;
            $product_price_data['product_id']           = $id;    
            $product_price_data['timestamp']            = date('Y-m-d H:i:s', $timestamp);
            $product_price_data['price']                = $data['unit_price'];
            $product_price_data['quantity']             = $data['unit_quantity'];

            $this->insert_product_price($product_price_data);
        }
        
        if($data['package_price'] != $data['old_package_price'] OR
                $data['package_quantity'] != $data['old_package_quantity']){
            $package_type_price_data = array();
            
            $timestamp = $data['package_update_date'];
            $timestamp = mktime(date('H'),date('i'),date('s'),substr($timestamp,4,2),substr($timestamp,6,2),substr($timestamp,0,4));
            
            $package_type_price_data['creator']                 = $modifier;
            $package_type_price_data['creation_timestamp']      = $modification_timestamp;
            $package_type_price_data['product_id']              = $id;
            $package_type_price_data['unit_id']                 = $data['old_package']; 
            $package_type_price_data['timestamp']               = date('Y-m-d H:i:s', $timestamp);
            $package_type_price_data['price']                   = $data['unit_price'];
            $package_type_price_data['quantity']                = $data['unit_quantity'];

            $this->insert_package_type_price($package_type_price_data);
        }
        
        $categories_query   = $this->get_categories_by_product($id);
        $categories         = array_unique(explode(',',$data['categories']));
        $old_categories     = array();
        
        foreach($categories_query->result() AS $category){
            $old_categories[] = $category->id;
            
            if(!in_array($category->id, $categories)){
                $this->db->delete('product_categories', array('product_id' => $id,
                                                              'category_id' => $category->id));
            }
        }

        $product_categories = array();
        $product_categories['product_id'] = $id;        

        for($i = 0; $i < count($categories); $i++){
            if(!in_array($categories[$i],$old_categories)){
                $product_categories['category_id'] = $categories[$i];
                
                $this->db->insert('product_categories', $product_categories);
            }
        }
        
        $this->db->trans_complete();
    }
    
    public function insert_product_price($data){
        if(empty($data['creator'])){
            $data['creator'] = $this->session->userdata('id');
            $data['creation_timestamp'] = date('Y-m-d H:i:s');
        }

        $this->db->insert('product_prices', $data);
    }
    
    public function insert_package_type_price($data){
        if(empty($data['creator'])){
            $data['creator'] = $this->session->userdata('id');
            $data['creation_timestamp'] = date('Y-m-d H:i:s');
        }
        
        $this->db->insert('package_type_prices', $data);
    }
    
    public function insert_product_categories($data){
        $product_categories_data = array();
        $product_categories_data['product_id'] = $data['product_id'];
        
        $categories = array_unique(explode(',', $data['categories']));
        
        for($i = 0; $i < count($categories); $i++){
           $product_categories_data['category_id'] = $categories[$i];

           $this->db->insert('product_categories', $product_categories_data);
        }
    }
    
    public function get_product_by_name($name,$limit = array()){
        $query = "SELECT id, name
                    FROM products
                    WHERE name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleter IS NULL";

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function get_product_by_id($id){
        $query = "SELECT DISTINCT products.*,
                         units.id AS 'unit_id',
                         units.name AS 'unit_name',
                         package.id AS 'package_id',
                         package.name AS 'package_name'
                    FROM products
                    INNER JOIN units ON
                        units.id = products.unit_id AND
                        products.id = ".$this->db->escape($id)." AND
                        products.deleter IS NULL
                    LEFT JOIN package_type_prices ON
                        package_type_prices.product_id = ".$this->db->escape($id)." AND
                        package_type_prices.deleter IS NULL
                    LEFT JOIN units package ON
                    package.id = package_type_prices.unit_id AND
                    package.deleter IS NULL";

        return $this->db->query($query);
    }
    
    public function get_categories_by_product($id){
        $query = "SELECT categories.id, categories.name, categories.general_report
                    FROM product_categories
                    INNER JOIN categories ON
                        product_categories.product_id = ".$this->db->escape($id)." AND
                        categories.id = product_categories.category_id AND
                        categories.deleter IS NULL";

        return $this->db->query($query);
    }
    
    public function get_last_product_information($id){
        $query = "SELECT price, quantity, timestamp
                    FROM product_prices
                    WHERE   product_id = ".$this->db->escape($id)." AND
                            deleter IS NULL
                    ORDER BY timestamp DESC
                    LIMIT 1";

        return $this->db->query($query);
    }
    
    public function get_last_package_information($id){
        $query = "SELECT price, quantity, timestamp
                    FROM package_type_prices
                    WHERE   product_id = ".$this->db->escape($id)." AND
                            deleter IS NULL
                    ORDER BY timestamp DESC
                    LIMIT 1";

        return $this->db->query($query);
    }
    
    public function get_inventory($category, $due_date = NULL){
        if($due_date == NULL){
            $due_date = date('Y-m-d');
        }
        
        return $this->db->query("call getInventory(".$category.",'".$due_date."')");
    }
    
    public function delete($id){
        $data['deleter'] = $this->session->userdata('id');
        $data['deletion_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('products', $data, array('id' => $id));
        $this->db->update('product_prices', $data, array('product_id' => $id));
        $this->db->update('products', $data, array('product_id' => $id));
    }
}