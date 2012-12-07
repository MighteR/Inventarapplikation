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
        
        $categories = explode(',', $data['categories']);
        
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
        $query = "SELECT products.*,
                         units.id AS 'unit_id',
                         units.name AS 'unit_name'
                    FROM products
                    INNER JOIN units ON
                        units.id = products.unit_id
                    WHERE   products.id = ".$this->db->escape($id)." AND
                            products.deleter IS NULL";

        return $this->db->query($query);
    }
    
    public function get_categories_by_product($id){
        $query = "SELECT categories.id, categories.name
                    FROM product_categories
                    INNER JOIN categories ON
                        product_categories.product_id = ".$this->db->escape($id)." AND
                        categories.id = product_categories.category_id AND
                        categories.deleter IS NULL";
echo $query;
        return $this->db->query($query);
    }
}