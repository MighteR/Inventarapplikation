<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lock_model extends CI_Model {
    var $firstname      = '';
    var $lastname       = '';
    var $table_suffix   = '_locks';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function check($type, $id){
        $data['id'] = $id;
        
        //$this->db->select($type.$this->table_suffix.'.id');
        //$this->db->select('users.firstname, users.lastname');        
        //$this->db->from($type.$this->table_suffix);
        //$this->db->join('comments', 'comments.id = blogs.id AND comments.status = "type"'); 
        //$this->db->join('users', 'user_locks.id = 1');
        //$this->db->join('users',$type.$this->table_suffix.'.id = "1" AND users.id = $type.$this->table_suffix.user_id','INNER');
        //$query = $this->db->get();
        $query = $this->db->query("SELECT   $type$this->table_suffix.id,
                                            users.firstname,
                                            users.lastname
                                    FROM $type$this->table_suffix
                                    INNER JOIN users ON
                                        $type$this->table_suffix.id = ".$this->db->escape($id)." AND
                                        users.id = $type$this->table_suffix.user_id");
        
        if($query->num_rows() == 1){
            $data = $query->row();
            
            $this->firstname    = $data->firstname;
            $this->lastname     = $data->lastname;
            
            return true;
        }
        
        return false;
    }
    
    public function create($type, $id){
        $data = array(
            'id' => $id,
            'user_id' => 1);
        
        $this->db->insert($type.$this->table_suffix, $data);
    }
    
    public function remove($type, $id){
        $this->db->delete($type.$this->table_suffix, array('id' => $id));
    }
    
    public function getInfo(){
        
    }
}

/* End of file lock_model.php */
/* Location: ./application/models/lock_model.php */