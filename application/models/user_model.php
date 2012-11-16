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
    
    public function create($data) {
        $data['creation_timestamp'] = date('Y-m-d H:i:s');
        
        $this->db->insert('users', $data);
    }
    
    public function get_user($id){
        return $this->db->get_where('users',array('id' => $id));
    }
    
    public function get_users_by_username($username,$limit = array()){
        $query = "SELECT id, username
                            FROM users
                            WHERE username LIKE ".$this->db->escape('%'.$username.'%');

        if(!empty($limit)){
            $query .= " LIMIT ".$limit['begin'].",".$limit['limit'];
        }

        return $this->db->query($query);
    }
    
    public function update($id,$data){
        $data['modification_timestamp'] = date('Y-m-d H:i:s');
        
        $this->db->update('users', $data, array('id' => $id));
    }
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */