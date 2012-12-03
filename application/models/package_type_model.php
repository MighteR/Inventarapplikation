<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package_type_model extends CI_Model {
    var $id = '';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $data['creator'] = $this->session->userdata('id');
        $data['creation_timestamp'] = date('Y-m-d H:i:s');
        
        $this->db->insert('package_types', $data);
    }
    
    public function delete($id){
        $data['deleter'] = $this->session->userdata('id');
        $data['deletion_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('package_types', $data, array('id' => $id));
    }
    
    public function get_package_type_by_id($id){
        $query = "SELECT * FROM package_types
                    WHERE   id = ".$this->db->escape($id)." AND
                            deleter IS NULL";
        
        return $this->db->query($query);
    }
    
    public function get_package_type_by_name($name, $exact_match = TRUE){
        if($exact_match){
            $query = "SELECT * FROM package_types
                        WHERE   name = ".$this->db->escape($name)." AND
                                deleter IS NULL";
        }else{
            $query = "SELECT * FROM package_types
                        WHERE   name LIKE ".$this->db->escape("%".$name."%")." AND
                                deleter IS NULL";
        }

        return $this->db->query($query);
    }
    
    public function get_package_type_list($name,$limit = array()){
        $query = "SELECT id, name
                    FROM package_types
                    WHERE name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleter IS NULL";

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function get_package_type_simple_list($name, $except = NULL, $limit = array()){
        $query = "SELECT id, name
                    FROM package_types
                    WHERE name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleter IS NULL";

        if($except != NULL){
            $query .= " AND id != ".$this->db->escape($except);
        }
        
        $query .= " ORDER BY name ASC";
        
        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }
        
        return $this->db->query($query);
    }
    
    public function update($id,$data){
        $data['modifier'] = $this->session->userdata('id');
        $data['modification_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('package_types', $data, array('id' => $id));
    }
}
?>
