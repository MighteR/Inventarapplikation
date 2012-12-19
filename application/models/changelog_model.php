<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

class Changelog_model extends CI_Model {    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_logs($id, $type){
        $table = $type.'_logs';
        
        $query = "SELECT    ".$table.".timestamp,
                            ".$table.".field,
                            ".$table.".from,
                            ".$table.".to,
                            users.username
                    FROM ".$table."
                    INNER JOIN users ON
                        ".$table.".id = ".$this->db->escape($id)." AND
                        users.id = ".$table.".user_id
                    ORDER BY ".$table.".timestamp DESC";
        
        return $this->db->query($query);
    }
    
    public function create($type, $data){
        $this->db->insert($type.'_logs', $data);
    }
}

/* End of file changelog_model.php */
/* Location: ./application/models/changelog_model.php */
?>