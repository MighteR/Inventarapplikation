<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
    var $id = '';
    var $username = '';
    var $password = '';
    var $admin = '';
    var $creator = '';
    var $creation_timestamp = '';
    var $modifier = '';
    var $modification_timestamp = '';
    var $deleter = '';
    var $deletion_timestamp = '';    
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create($data){
        $data['creator'] = $this->session->userdata('id');
        $data['creation_timestamp'] = date('Y-m-d H:i:s');
        
        $this->db->insert('users', $data);
    }
    
    public function delete($id){
        $data['deleter'] = $this->session->userdata('id');
        $data['deletion_timestamp'] = date('Y-m-d H:i:s');

        $this->db->update('users', $data, array('id' => $id));
    }
    
    public function get_user_by_id($id){
        $query = "SELECT * FROM users
                    WHERE   id = ".$this->db->escape($id)." AND
                            deleter IS NULL";
        
        return $this->db->query($query);
    }
    
    public function get_users_by_username($username,$limit = array()){
        $query = "SELECT id, username
                    FROM users
                    WHERE username LIKE ".$this->db->escape('%'.$username.'%')."
                          AND deleter IS NULL";

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function get_user_by_login($username, $password){
        $query = "SELECT id, username, admin
                            FROM users
                            WHERE username =  ".$this->db->escape($username)."
                                  AND password = MD5(".$this->db->escape($password).")
                                  AND deleter IS NULL";
        
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

        $this->db->update('users', $data, array('id' => $id));
    }
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */