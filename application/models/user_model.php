<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $data['creator'] = $this->session->userdata('id');
        $data['creation_timestamp'] = date('Y-m-d H:i:s');
        
        $this->db->insert('users', $data);
    }
    
    public function get_user_by_id($id, $deleted = FALSE){
        $query = "SELECT * FROM users
                    WHERE   id = ".$this->db->escape($id);
                        
        if(!$deleted){
            $query .= " AND deleted = 0";
        }
        
        return $this->db->query($query);
    }
    
    public function get_users_by_username($username,$limit = array()){
        $query = "SELECT users.*,
                         creator.username AS 'creator_name',
                         modifier.username AS 'modifier_name'
                    FROM users
                        INNER JOIN users creator ON
                            users.username LIKE ".$this->db->escape('%'.$username.'%')." AND
                            creator.id = users.creator
                        LEFT JOIN users modifier ON
                            modifier.id = users.modifier";

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function get_user_by_login($username, $password){
        $query = "SELECT id, username, admin, last_login
                            FROM users
                            WHERE username =  ".$this->db->escape($username)." AND
                                  password = MD5(".$this->db->escape($password).") AND
                                  deleted = 0";
        
        $query = $this->db->query($query);
        
        if($query->num_rows() == 1){
            return $query->row();
        }else{
            return FALSE;
        }
    }
    
    public function update($id,$data){
        $data['modifier'] = $this->session->userdata('id');
        $data['modification_timestamp'] = date('Y-m-d H:i:s');
        
        $this->config->load('database_options');
        
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        if(!$this->config->item('changelog_trigger')){
            $old_query  = $this->get_user_by_id($id, TRUE);
            $old_data   = $old_query->row_array();
        }

        $this->db->update('users', $data, array('id' => $id));
        
        if(!$this->config->item('changelog_trigger')){
            $this->load->model('changelog_model');
            
            $changelog_type = 'user';

            $changelog_data = array();
            $changelog_data['id']           = $id;
            $changelog_data['user_id']      = $data['modifier'];
            $changelog_data['timestamp']    = $data['modification_timestamp'];

            if(isset($data['username']) AND $data['username'] != $old_data['username']){
                $changelog_data['field']    = 'username';
                $changelog_data['from']     = $old_data['username'];
                $changelog_data['to']       = $data['username'];

                $this->changelog_model->create($changelog_type, $changelog_data);
            }
            
            if(isset($data['admin']) AND $data['admin'] != $old_data['admin']){
                $changelog_data['field']    = 'admin';
                $changelog_data['from']     = $old_data['admin'];
                $changelog_data['to']       = $data['admin'];

                $this->changelog_model->create($changelog_type, $changelog_data);
            }

            if(isset($data['password']) AND $data['password'] != $old_data['password']){
                $changelog_data['field']    = 'password_change';
                $changelog_data['from']     = '****';
                $changelog_data['to']       = '****';

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

    public function update_last_login($id){
        $data['last_login'] = date('Y-m-d H:i:s');

        $this->db->update('users', $data, array('id' => $id));
    }
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */