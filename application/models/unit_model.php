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
    
    public function get_unit_by_id($id, $deleted = FALSE){
        $query = "SELECT * FROM units
                    WHERE   id = ".$this->db->escape($id);
        
        if(!$deleted){
            $query .= " AND deleted = 0";
        }
        
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
        
        $this->config->load('database_options');
        
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        if(!$this->config->item('changelog_trigger')){
            $old_query  = $this->get_unit_by_id($id, TRUE);
            $old_data   = $old_query->row_array();
        }

        $this->db->update('units', $data, array('id' => $id));
        
        if(!$this->config->item('changelog_trigger')){
            $this->load->model('changelog_model');
            
            $changelog_type = 'unit';

            $changelog_data = array();
            $changelog_data['id']           = $id;
            $changelog_data['user_id']      = $data['modifier'];
            $changelog_data['timestamp']    = $data['modification_timestamp'];

            if(isset($data['name']) AND $data['name'] != $old_data['name']){
                $changelog_data['field']    = 'name';
                $changelog_data['from']     = $old_data['name'];
                $changelog_data['to']       = $data['name'];

                $this->changelog_model->create($changelog_type, $changelog_data);
            }
            
            if(isset($data['package_type']) AND $data['package_type'] != $old_data['package_type']){
                $changelog_data['field']    = 'package_type';
                $changelog_data['from']     = $old_data['package_type'];
                $changelog_data['to']       = $data['package_type'];

                $this->changelog_model->create($changelog_type, $changelog_data);
            }
            
            if(isset($data['deleted']) AND $data['deleted'] != $old_data['deleted']){
                $changelog_data['field']    = 'deleted_user';
                $changelog_data['from']     = $old_data['deleted'];
                $changelog_data['to']       = $data['deleted'];

                $this->changelog_model->create($changelog_type, $changelog_data);
            }
        }
        
        $this->db->trans_complete();
    }
}
?>
