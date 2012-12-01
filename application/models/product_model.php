<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {
    var $id = '';
    
    public function __construct() {
        parent::__construct();
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
}