<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function check_language($language) {
        $query = $this->db->get_where('languages',array('name' => $language));
        
        if($query->num_rows() == 1) {
            return true;
        }
        return false;
    }
}

/* End of file language_model.php */
/* Location: ./application/models/language_model.php */