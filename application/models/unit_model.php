<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unit_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $data['creator'] = $this->session->userdata('id');
        $data['creation_timestamp'] = date('Y-m-d H:i:s');
        
        $this->db->insert('units', $data);
    }
    
    public function delete($id){
        $data['deleted'] = 1;
        $data['modifier'] = $this->session->userdata('id');
        $data['modification_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('units', $data, array('id' => $id));
    }
    
    public function get_unit_by_id($id){
        $query = "SELECT * FROM units
                    WHERE   id = ".$this->db->escape($id)." AND
                            deleted = 0";
        
        return $this->db->query($query);
    }
    
/*    public function get_unit_by_name($name, $exact_match = TRUE){
        if($exact_match){
            $query = "SELECT * FROM units
                        WHERE   name = ".$this->db->escape($name)." AND
                                deleted = 1";
        }else{
            $query = "SELECT * FROM units
                        WHERE   name LIKE ".$this->db->escape("%".$name."%")." AND
                                deleted = 1";
        }

        return $this->db->query($query);
    }*/
    
    public function get_unit_list($name, $limit = array()){
        $query = "SELECT units.*,
                         creator.username AS 'creator_name',
                         modifier.username AS 'modifier_name'
                    FROM units
                        INNER JOIN users creator ON
                            units.name LIKE ".$this->db->escape('%'.$name.'%')." AND
                            creator.id = units.creator
                        LEFT JOIN users modifier ON
                            modifier.id = units.modifier";

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function get_unit_simple_list($name, $except = NULL, $unit_type, $limit = array()){
        $query = "SELECT id, name
                    FROM units
                    WHERE name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleted = 0";

        if($except != NULL){
            $query .= " AND id != ".$this->db->escape($except);
        }
        
        if($unit_type == 'package_type'){
            $query .= " AND package_type = 1";
        }elseif($unit_type == 'unit'){
            $query .= " AND package_type = 0";
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

        $this->db->update('units', $data, array('id' => $id));
    }
}
?>
