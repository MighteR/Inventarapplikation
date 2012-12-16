<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $data['creator'] = $this->session->userdata('id');
        $data['creation_timestamp'] = date('Y-m-d H:i:s');
        
        /*if($data['parent_category'] != NULL){
            $query = $this->get_category_by_name($data['parent_category']);
            
            if($query->num_rows() == 1){
                $parent_category = $query->row();
                $data['parent_category'] = $parent_category->id;
            }
        }*/

        $this->db->insert('categories', $data);
    }
    
    public function get_all_categories($parent = FALSE){
        $query = "SELECT id, name
                    FROM categories
                    WHERE deleted = 0";


        /*if($parent){
            $query .= " AND categories.id IN (SELECT parent_category FROM categories)";
        }*/
        
        return $this->db->query($query);
    }
    
    public function get_category_by_id($id, $deleted = FALSE){
        /*$query = "SELECT categories.*,
                         parent.id AS 'parent_id',
                         parent.name AS 'parent_name'
                    FROM categories
                    LEFT JOIN categories parent ON
                        parent.id = categories.parent_category
                    WHERE   categories.id = ".$this->db->escape($id)." AND
                            categories.deleted = 0";*/
        $query = "SELECT *
                    FROM categories
                    WHERE   id = ".$this->db->escape($id);
        
        if(!$deleted){
            $query .= " AND deleted = 0";
        }

        return $this->db->query($query);
    }
    
    public function get_categories_by_id_list($ids){        
        for($i = 0; $i < count($ids); $i++){
            $ids[$i] = $this->db->escape($ids[$i]);
        }
        $ids = (implode(',', $ids));
        
/*        $query = "SELECT categories.*,
                         parent.name AS 'parent_name'
                    FROM categories
                    LEFT JOIN categories parent ON
                        parent.id = categories.parent_category
                    WHERE   categories.id IN (".$ids.") AND
                            categories.deleted = 0";*/
        $query = "SELECT categories.*
                    FROM categories
                    WHERE   categories.id IN (".$ids.") AND
                            categories.deleted = 0";

        return $this->db->query($query);
    }
    
    /*public function get_category_by_name($name, $exact_match = TRUE){
        if($exact_match){
            $query = "SELECT * FROM categories
                        WHERE   name = ".$this->db->escape($name)." AND
                                deleter IS NULL";
        }else{
            $query = "SELECT * FROM categories
                        WHERE   name LIKE ".$this->db->escape("%".$name."%")." AND
                                deleter IS NULL";
        }

        return $this->db->query($query);
    }*/
    
    public function get_category_list($name, $general_report, $parent = NULL, $limit = array()){
        /*$query = "SELECT categories.*,
                         creator.username AS 'creator_name',
                         modifier.username AS 'modifier_name',
                         parent.id AS 'parent_id', parent.name AS 'parent_name'
                    FROM categories
                    INNER JOIN users creator ON
                        categories.name LIKE ".$this->db->escape('%'.$name.'%')." AND
                        creator.id = categories.creator
                    LEFT JOIN users modifier ON
                        modifier.id = categories.modifier
                    LEFT JOIN categories parent ON
                        parent.id = categories.parent_category AND
                        parent.deleter = 0";*/
        $query = "SELECT categories.*,
                         creator.username AS 'creator_name',
                         modifier.username AS 'modifier_name'
                    FROM categories
                    INNER JOIN users creator ON
                        categories.name LIKE ".$this->db->escape('%'.$name.'%')." AND
                        creator.id = categories.creator
                    LEFT JOIN users modifier ON
                        modifier.id = categories.modifier";

        if($general_report == '1'){
            $query .= " AND categories.general_report = 1";
        }elseif($general_report == '0'){
            $query .= " AND categories.general_report = 0";
        }

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function get_category_simple_list($name, $except = NULL, $inventory = FALSE, $limit = array()){
        $query = "SELECT id, name, general_report
                    FROM categories
                    WHERE name LIKE ".$this->db->escape('%'.$name.'%')."
                          AND deleted = 0";
        
        if($except != NULL){
            $query .= " AND id != ".$this->db->escape($except);
        }
        
        if($inventory){
            $query .= " AND general_report = 1";
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
        
        /*if($data['parent_category'] != NULL){
            $query = $this->get_category_by_name($data['parent_category']);
            
            if($query->num_rows() == 1){
                $parent_category = $query->row();
                $data['parent_category'] = $parent_category->id;
            }
        }*/
        
        $this->config->load('database_options');
        
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        if(!$this->config->item('changelog_trigger')){
            $old_query  = $this->get_category_by_id($id, TRUE);
            $old_data   = $old_query->row_array();
        }

        $this->db->update('categories', $data, array('id' => $id));
        
        if(!$this->config->item('changelog_trigger')){
            $this->load->model('changelog_model');
            
            $changelog_type = 'category';

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
            
            if(isset($data['general_report']) AND $data['general_report'] != $old_data['general_report']){
                $changelog_data['field']    = 'inventory_relevant';
                $changelog_data['from']     = $old_data['admin'];
                $changelog_data['to']       = $data['admin'];

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

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */