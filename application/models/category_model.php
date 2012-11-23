<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
    var $id = '';
    /*var $username = '';
    var $password = '';
    var $admin = '';
    var $creator = '';
    var $creation_timestamp = '';
    var $modifier = '';
    var $modification_timestamp = '';
    var $deleter = '';
    var $deletion_timestamp = '';*/
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $data['creator'] = $this->session->userdata('id');
        $data['creation_timestamp'] = date('Y-m-d H:i:s');

        $this->db->insert('categories', $data);
    }
    
    public function delete($id){
        $data['deleter'] = $this->session->userdata('id');
        $data['deletion_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('categories', $data, array('id' => $id));
    }
    
    public function get_all_categories($parent = FALSE){
        $query = "SELECT id, name FROM categories";
        
        if($parent){
            $query .= " WHERE id IN (SELECT parent_category FROM categories)";
        }
        
        return $this->db->query($query);
    }
    
    public function get_category_by_id($id){
        return $this->db->get_where('categories',array('id' => $id));
    }
    
    public function get_category_list($name,$parent = NULL, $limit = array()){
        $query = "SELECT categories.id, categories.name,
                         parent.id AS 'parent_id', parent.name AS 'parent_name'
                    FROM categories
                    LEFT JOIN categories parent ON
                        parent.id = categories.parent_category AND
                        parent.deleter IS NULL
                    WHERE categories.name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND categories.deleter IS NULL";
        
        if($parent != NULL){
            $query .= " AND categories.parent_category = ".$this->db->escape($parent);
        }

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function update($id,$data){
        $data['modifier'] = $this->session->userdata('id');
        $data['modification_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('categories', $data, array('id' => $id));
    }
}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */