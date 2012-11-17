<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class Messages{
    var $CI;
    
    function __construct(){
        $this->CI =& get_instance();
    }
    
    public function get_message($type, $message, $url = NULL){
        switch($type){
            case 'error':
                $data['title'] = $this->CI->lang->line('title_error');
                $data['title_back'] = $this->CI->lang->line('title_back');
                break;
            case 'info':
                $data['title'] = $this->CI->lang->line('title_info');
                $data['title_next'] = $this->CI->lang->line('title_next');
                $data['url'] = $url;
                break;
        }
        
        $data['message'] = $message;
        
        $this->CI->template->write_view('content','template/messages/'.$type,$data);
    }
}
?>
