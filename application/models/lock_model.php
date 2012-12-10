<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lock_model extends CI_Model {
    var $id             = '';
    var $table_suffix   = '_locks';
    var $timeout        = 900;
    var $type           = '';
    var $username       = '';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function set_info($type, $id){
        $this->id = $id;
        $this->type = $type;
    }
    
    public function check(){
        $query = $this->db->query("SELECT   $this->type$this->table_suffix.id,
                                            $this->type$this->table_suffix.timestamp,
                                            users.username
                                    FROM $this->type$this->table_suffix
                                    INNER JOIN users ON
                                        $this->type$this->table_suffix.id = ".$this->db->escape($this->id)." AND
                                        users.id = $this->type$this->table_suffix.user_id");

        if($query->num_rows() == 1){
            $data = $query->row();

            if($data->timestamp <= date('Y-m-d H:i:s', time() - $this->timeout)){
                $this->remove($this->type, $this->id);

                return false;
            }
            
            $this->username = $data->username;
            
            return true;
        }
        
        return false;
    }
    
    public function create(){
        $data = array(
            'id' => $this->id,
            'user_id' => $this->session->userdata('id'),
            'timestamp' => date('Y-m-d H:i:s'));
        
        $this->db->insert($this->type.$this->table_suffix, $data);
    }
    
    public function remove($type,$id){
        $this->db->delete($type.$this->table_suffix, array('id' => $id));
    }
    
    public function get_info(){
        return $this->username;
    }
}

/* End of file lock_model.php */
/* Location: ./application/models/lock_model.php */