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
        return $this->db->get_where('package_types',array('id' => $id));
    }
    
    public function get_package_type_by_name($name,$limit = array()){
        $query = "SELECT id, name
                    FROM package_types
                    WHERE name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleter IS NULL";

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
