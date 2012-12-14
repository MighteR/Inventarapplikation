<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $data['creator'] = $this->session->userdata('id');
        $data['creation_timestamp'] = date('Y-m-d H:i:s');
        
        if($data['parent_category'] != NULL){
            $query = $this->get_category_by_name($data['parent_category']);
            
            if($query->num_rows() == 1){
                $parent_category = $query->row();
                $data['parent_category'] = $parent_category->id;
            }
        }

        $this->db->insert('categories', $data);
    }
    
    public function delete($id){
        $data['deleted'] = 1;
        $data['modifier'] = $this->session->userdata('id');
        $data['modification_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('categories', $data, array('id' => $id));
    }
    
    public function get_all_categories($parent = FALSE){
        $query = "SELECT id, name
                    FROM categories
                    WHERE deleted = 0";


        if($parent){
            $query .= " AND categories.id IN (SELECT parent_category FROM categories)";
        }
        
        return $this->db->query($query);
    }
    
    public function get_category_by_id($id){
        $query = "SELECT categories.*,
                         parent.id AS 'parent_id',
                         parent.name AS 'parent_name'
                    FROM categories
                    LEFT JOIN categories parent ON
                        parent.id = categories.parent_category
                    WHERE   categories.id = ".$this->db->escape($id)." AND
                            categories.deleted = 0";

        return $this->db->query($query);
    }
    
    public function get_categories_by_id_list($ids){        
        for($i = 0; $i < count($ids); $i++){
            $ids[$i] = $this->db->escape($ids[$i]);
        }
        $ids = (implode(',', $ids));
        
        $query = "SELECT categories.*,
                         parent.name AS 'parent_name'
                    FROM categories
                    LEFT JOIN categories parent ON
                        parent.id = categories.parent_category
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
        $query = "SELECT categories.*,
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
                        parent.deleter = 0";

        if($general_report == '1'){
            $query .= " AND categories.general_report = 1";
        }elseif($general_report == '0'){
            $query .= " AND categories.general_report = 0";
        }

        if($parent != NULL){
            $query .= " AND categories.parent_category = ".$this->db->escape($parent);
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

        $this->db->update('categories', $data, array('id' => $id));
    }
}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */