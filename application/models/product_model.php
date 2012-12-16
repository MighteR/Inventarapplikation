<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $creator            = $this->session->userdata('id');
        $time               = time();
        $creation_timestamp = date('Y-m-d H:i:s', $time);
        $timestamp          = date('Y-m-d 00:00:00', $time);

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
        $product_price_data['timestamp'] = $timestamp;
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
            $package_type_data['timestamp'] = $timestamp;
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
        
        if(isset($data['name'])){
            $product_data['name'] = $data['name'];
        }
        
        if(isset($data['deleted'])){
            $product_data['deleted'] = $data['deleted'];
        }
        
        $product_data['modifier'] = $modifier;
        $product_data['modification_timestamp'] = $modification_timestamp;
  
        $this->config->load('database_options');
        
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        if(!$this->config->item('changelog_trigger')){
            $old_query  = $this->get_product_by_id($id, TRUE);
            $old_data   = $old_query->row_array();
            
            $this->load->model('changelog_model');
            $changelog_type = 'product';
        }
        
        $this->db->update('products', $product_data, array('id' => $id));
        
        if(!$this->config->item('changelog_trigger')){
            $changelog_data = array();
            $changelog_data['id']           = $id;
            $changelog_data['user_id']      = $modifier;
            $changelog_data['timestamp']    = $modification_timestamp;

            if(isset($data['name']) AND $data['name'] != $old_data['name']){
                $changelog_data['field']    = 'name';
                $changelog_data['from']     = $old_data['name'];
                $changelog_data['to']       = $data['name'];

                $this->changelog_model->create($changelog_type, $changelog_data);
            }
            
            if(isset($data['deleted']) AND $data['deleted'] != $old_data['deleted']){
                $changelog_data['field']    = 'deleted_product';
                $changelog_data['from']     = $old_data['deleted'];
                $changelog_data['to']       = $data['deleted'];

                $this->changelog_model->create($changelog_type, $changelog_data);
            }
        }
        
        if(isset($data['unit_price']) AND isset($data['unit_quantity'])){
            if($data['unit_price'] != $data['old_unit_price'] OR
                    $data['unit_quantity'] != $data['old_unit_quantity']){
                $product_price_data = array();

                $timestamp = $data['unit_update_date'];
                $timestamp = date('Y-m-d H:i:s', mktime(0,0,0,substr($timestamp,4,2),substr($timestamp,6,2),substr($timestamp,0,4)));

                $product_price_data['creator']              = $modifier;
                $product_price_data['creation_timestamp']   = $modification_timestamp;
                $product_price_data['product_id']           = $id;    
                $product_price_data['timestamp']            = $timestamp;
                $product_price_data['price']                = $data['unit_price'];
                $product_price_data['quantity']             = $data['unit_quantity'];
                
                $this->insert_product_price($product_price_data);
                
                if(!$this->config->item('changelog_trigger')){
                    $changelog_data['field']    = 'unit_price';
                    $changelog_data['from']     = $data['old_unit_price'];
                    $changelog_data['to']       = $data['unit_price'];

                    $this->changelog_model->create($changelog_type, $changelog_data);
                     
                    $changelog_data['field']    = 'unit_quantity';
                    $changelog_data['from']     = $data['old_unit_quantity'];
                    $changelog_data['to']       = $data['unit_quantity'];

                    $this->changelog_model->create($changelog_type, $changelog_data);
                     
                    $changelog_data['field']    = 'unit_timestamp';
                    $changelog_data['from']     = $data['old_unit_update_date'];
                    $changelog_data['to']       = $timestamp;

                    $this->changelog_model->create($changelog_type, $changelog_data);
                 }
            }
        }
        
        if(isset($data['package_price']) AND isset($data['package_quantity'])){
            if($data['package_price'] != $data['old_package_price'] OR
                    $data['package_quantity'] != $data['old_package_quantity']){
                $package_type_price_data = array();

                $timestamp = $data['package_update_date'];
                $timestamp = date('Y-m-d H:i:s', mktime(0,0,0,substr($timestamp,4,2),substr($timestamp,6,2),substr($timestamp,0,4)));

                $package_type_price_data['creator']                 = $modifier;
                $package_type_price_data['creation_timestamp']      = $modification_timestamp;
                $package_type_price_data['product_id']              = $id;
                $package_type_price_data['unit_id']                 = $data['old_package']; 
                $package_type_price_data['timestamp']               = $timestamp;
                $package_type_price_data['price']                   = $data['package_price'];
                $package_type_price_data['quantity']                = $data['package_quantity'];
                
                if(!$this->config->item('changelog_trigger')){
                    $old_query  = $this->get_last_package_information($id);
                    $old_data   = $old_query->row_array();
                }

                $this->insert_package_type_price($package_type_price_data);
                
                if(!$this->config->item('changelog_trigger')){
                    $changelog_data['field']    = 'package_price';
                    $changelog_data['from']     = $data['old_package_price'];
                    $changelog_data['to']       = $data['package_price'];

                    $this->changelog_model->create($changelog_type, $changelog_data);

                    $changelog_data['field']    = 'package_quantity';
                    $changelog_data['from']     = $data['old_package_quantity'];
                    $changelog_data['to']       = $data['package_quantity'];

                    $this->changelog_model->create($changelog_type, $changelog_data);

                    $changelog_data['field']    = 'package_timestamp';
                    $changelog_data['from']     = $data['old_package_update_date'];
                    $changelog_data['to']       = $timestamp;

                    $this->changelog_model->create($changelog_type, $changelog_data);
                 }
            }
        }
        
        if(isset($data['categories'])){
            $categories_query   = $this->get_categories_by_product($id);
            $categories         = array_unique(explode(',',$data['categories']));
            $old_categories     = array();

            foreach($categories_query->result() AS $category){
                $old_categories[] = $category->id;

                if(!in_array($category->id, $categories)){
                    $this->db->delete('product_categories', array('product_id' => $id,
                                                                  'category_id' => $category->id));
                    
                    if(!$this->config->item('changelog_trigger')){
                        $changelog_data['field']    = 'product_category_removed';
                        $changelog_data['from']     = $category->id;
                        $changelog_data['to']       = '';

                        $this->changelog_model->create($changelog_type, $changelog_data);
                     }
                }
            }

            $product_categories = array();
            $product_categories['product_id'] = $id;        

            for($i = 0; $i < count($categories); $i++){
                if(!in_array($categories[$i],$old_categories)){
                    $product_categories['category_id'] = $categories[$i];

                    $this->db->insert('product_categories', $product_categories);
                    
                    if(!$this->config->item('changelog_trigger')){
                        $changelog_data['field']    = 'product_category_added';
                        $changelog_data['from']     = '';
                        $changelog_data['to']       = $categories[$i];

                        $this->changelog_model->create($changelog_type, $changelog_data);
                     }
                }
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
        $query = "SELECT products.*,
                         creator.username AS 'creator_name',
                         modifier.username AS 'modifier_name'
                    FROM products
                    INNER JOIN users creator ON
                        products.name LIKE ".$this->db->escape('%'.$name.'%')." AND
                        creator.id = products.creator
                    LEFT JOIN users modifier ON
                        modifier.id = products.modifier";

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function get_product_by_id($id, $deleted = FALSE){
        $deleted_products = '';
        
        if(!$deleted){
            $deleted_products = " AND products.deleted = 0";
        }
        
        $query = "SELECT DISTINCT products.*,
                         units.id AS 'unit_id',
                         units.name AS 'unit_name',
                         package.id AS 'package_id',
                         package.name AS 'package_name'
                    FROM products
                    INNER JOIN units ON
                        units.id = products.unit_id AND
                        products.id = ".$this->db->escape($id).$deleted_products."
                    LEFT JOIN package_type_prices ON
                        package_type_prices.product_id = ".$this->db->escape($id)."
                    LEFT JOIN units package ON
                        package.id = package_type_prices.unit_id";

        return $this->db->query($query);
    }
    
    public function get_categories_by_product($id){
        $query = "SELECT categories.id, categories.name, categories.general_report
                    FROM product_categories
                    INNER JOIN categories ON
                        product_categories.product_id = ".$this->db->escape($id)." AND
                        categories.id = product_categories.category_id AND
                        categories.deleted = 0";

        return $this->db->query($query);
    }
    
    public function get_last_product_information($id, $deleted = FALSE){
        if(!$deleted){
            $deleted = " AND deleted = 0";
        }
        
        $query = "SELECT price, quantity, timestamp
                    FROM product_prices
                    WHERE   product_id = ".$this->db->escape($id).$deleted."
                    ORDER BY timestamp DESC
                    LIMIT 1";

        return $this->db->query($query);
    }
    
    public function get_last_package_information($id, $deleted = FALSE){
        if(!$deleted){
            $deleted = " AND deleted = 0";
        }
        
        $query = "SELECT price, quantity, timestamp
                    FROM package_type_prices
                    WHERE   product_id = ".$this->db->escape($id).$deleted."
                    ORDER BY timestamp DESC
                    LIMIT 1";

        return $this->db->query($query);
    }
    
    public function get_inventory($category, $due_date = NULL){
        if($due_date == NULL){
            $due_date = date('Y-m-d');
        }
        
        $this->config->load('database_options');

        if($this->config->item('changelog_trigger')){
            return $this->db->query("call getInventory(".$category.",'".$due_date."')");
        }else{
            $query = "DROP TABLE IF EXISTS inventory";
            $this->db->query($query);

            $query = "CREATE TEMPORARY TABLE inventory(
                            product_id 	INT(10) UNSIGNED NOT NULL PRIMARY KEY,
                            product_name VARCHAR(128) NOT NULL,
                            category_id INT(10) UNSIGNED NOT NULL,
                            category_name VARCHAR(128) NOT NULL,
                            unit_id INT(10) UNSIGNED NOT NULL,
                            unit_name VARCHAR(128) NOT NULL,
                            unit_price DOUBLE NULL,
                            unit_quantity DOUBLE NULL,
                            unit_timestamp timestamp NULL,
                            package_id INT(10) UNSIGNED NULL,
                            package_name VARCHAR(128) NULL,
                            package_price DOUBLE NULL,
                            package_quantity DOUBLE NULL,
                            package_timestamp timestamp NULL);";
            $this->db->query($query);

            $query = "INSERT INTO inventory
                    SELECT DISTINCT 
                            products.id AS 'product_id',
                            products.name AS 'product_name',
                            categories.id AS 'category_id',
                            categories.name AS 'category_name',
                            units.id AS 'unit_id',
                            units.name AS 'unit_name',
                            '',
                            '',
                            '',
                            package.id AS 'package_id',
                            package.name AS 'package_name',
                            '',
                            '',
                            ''
                    FROM categories
                    INNER JOIN product_categories ON
                        categories.general_report = 1 AND
                        categories.deleted = 0 AND
                        product_categories.category_id = categories.id
                    INNER JOIN products ON
                        products.id = product_categories.product_id AND
                        products.deleted = 0
                    INNER JOIN units ON
                        units.id = products.unit_id
                    LEFT JOIN package_type_prices ON
                        package_type_prices.product_id = products.id
                    LEFT JOIN units package ON
                        package.id = package_type_prices.unit_id";
            
            $this->db->query($query);
            
            $inventory = "SELECT * FROM inventory";
            
            $inventory_query = $this->db->query($inventory);
            
            if($inventory_query->num_rows() > 0){
                $inventory = $inventory_query->result_object();
                
                foreach ($inventory as $product){
                    $query = "SELECT price, quantity, timestamp
                                FROM product_prices
                                WHERE   product_id = ".$product->product_id." AND
                                        date(timestamp) <= '".$due_date."'
                                ORDER BY timestamp DESC
                                LIMIT 1";

                    $query = $this->db->query($query);
                    
                    if($query->num_rows() > 0){
                        $unit = $query->row();
                        
                        $inventory_update_query = "UPDATE inventory
                                                    SET unit_price = ".$unit->price.",
                                                        unit_quantity = ".$unit->quantity.",
                                                        unit_timestamp = '".$unit->timestamp."'
                                                    WHERE product_id = ".$product->product_id;
                        
                        $this->db->query($inventory_update_query);
                        
                    }
                    
                    if($product->package_id != NULL){
                        $query = "SELECT price, quantity, timestamp
                                    FROM package_type_prices
                                    WHERE   product_id = ".$product->product_id." AND
                                            date(timestamp) <= '".$due_date."'
                                    ORDER BY timestamp DESC
                                    LIMIT 1";

                        $query = $this->db->query($query);

                        if($query->num_rows() > 0){
                            $package = $query->row();

                            $inventory_update_query = "UPDATE inventory
                                                        SET package_price = ".$package->price.",
                                                            package_quantity = ".$package->quantity.",
                                                            package_timestamp = '".$package->timestamp."'
                                                        WHERE product_id = ".$product->product_id;

                            $this->db->query($inventory_update_query);
                        }
                    }
                }
            }
            
            $query = "SELECT * FROM inventory ORDER BY category_id ASC";
            
            return $this->db->query($query);
        }
    }
    
    public function delete($id){
        $data['deleted'] = 1;
        $data['modifier'] = $this->session->userdata('id');
        $data['modification_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('products', $data, array('id' => $id));
        $this->db->update('product_prices', $data, array('product_id' => $id));
        $this->db->update('package_type_prices', $data, array('product_id' => $id));
    }
    
    public function get_product_trends($id, $date_from, $date_to){
        $query = "SELECT UNIX_TIMESTAMP(timestamp) AS 'timestamp', price, quantity
                    FROM product_prices
                    WHERE  product_id = ".$this->db->escape($id)." AND
                        deleted = 0 AND
                        DATE(timestamp) BETWEEN ".$this->db->escape($date_from)." AND ".$this->db->escape($date_to)."
                    ORDER BY timestamp ASC";
        
        return $this->db->query($query);
    }
    
    public function get_package_trends($id){
        $query = "SELECT UNIX_TIMESTAMP(timestamp) AS 'timestamp', price, quantity
                    FROM package_type_prices
                    WHERE  product_id = ".$this->db->escape($id)." AND
                        deleted = 0
                    ORDER BY timestamp ASC";
        
        return $this->db->query($query);
    }
    
    public function get_product_simple_list($name, $limit = array()){
        $query = "SELECT id, name
                    FROM products
                    WHERE name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleted = 0
                    ORDER BY name ASC";
        
        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }
        
        return $this->db->query($query);
    }
}