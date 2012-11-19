<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
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
    
    public function get_category_by_id($id){
        return $this->db->get_where('categories',array('id' => $id));
    }
    
    public function get_categories_by_name($name,$limit = array()){
        $query = "SELECT id, name
                    FROM categories
                    WHERE username LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleter IS NULL";

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