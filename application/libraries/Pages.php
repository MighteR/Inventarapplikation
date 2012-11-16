<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pages{
    var $CI;
    var $begin;
    var $limit = 10;
    var $links;
    var $output;
    var $page;
    var $pages;
    function __construct(){
        $this->CI =& get_instance();
    }
    function check_page($rows, $page, $output = TRUE, $limit = NULL){
            if($limit != NULL || $limit > 0){
                $this->limit = $limit;
            }
            
            $this->output = $output;
        
        
            $this->pages = ceil($rows/$this->limit);
            if(!isset($page) || $page < 1 || !is_numeric($page)){
                    $this->page = 1;
            }elseif($page > $this->pages){
                    $this->page = $this->pages;
            }else{
                    $this->page = $page;
            }
            $this->begin = ($this->page-1)*$this->limit;
    }
    function get_links($div, $function = 'limit', $url = ''){
            if($this->pages > 0){
                    if($this->page != 1){
                            $back = $this->page-1;
                            if(!empty($div)){
                                    $this->links[] = '<a onClick="'.$function.'(\''.$url.'/1\')" style="cursor:pointer;"><<</a>';
                                    $this->links[] = '<a onClick="'.$function.'(\''.$url.'/'.$back.'\')" style="cursor:pointer;"><</a>';
                            }else{
                                    $this->links[] = '<a href="{path}'.$url.'1/" style="cursor:pointer;"><<</a>';
                                    $this->links[] = '<a href="{path}'.$url.'/'.$back.'/" style="cursor:pointer;"><</a>';
                            }
                    }
                    for($i=1; $i <= $this->pages; $i++){
                            if($i == $this->page){
                                    $this->links[] = '<span class="actual_page">'.$i.'</span>';
                            }elseif($this->page - $this->limit <= $i AND $i <= $this->page + $this->limit){
                                    if(!empty($div)){
                                            $this->links[] = '<a onClick="'.$function.'(\''.$url.'/'.$i.'\')" style="cursor:pointer;">'.$i.'</a>';
                                    }else{
                                            $this->links[] = '<a href="{path}'.$url.'/'.$i.'" style="cursor:pointer;">'.$i.'</a>';
                                    }
                            }
                    }
                    if($this->page != $this->pages){
                            $next = $this->page+1;
                            if(!empty($div)){
                                    $this->links[] = '<a onClick="'.$function.'(\''.$url.'/'.$next.'\')" style="cursor:pointer;">></a>';
                                    $this->links[] = '<a onClick="'.$function.'(\''.$url.'/'.$this->pages.'\')" style="cursor:pointer;">>></a>';
                            }else{
                                    $this->links[] = '<a href="{path}'.$url.'/'.$next.'" style="cursor:pointer;">></a>';
                                    $this->links[] = '<a href="{path}'.$url.'/'.$this->pages.'" style="cursor:pointer;">>></a>';
                            }
                    }

                    $this->links[] = '<a onClick="if(document.getElementById(\''.$div.'_page\').value.match(/^\d+$/) > 0){
                                                            '.$function.'(\''.$url.'/\' + document.getElementById(\''.$div.'_page\').value)
                                                    }else{
                                                            alert(\''.$this->CI->lang->line('error_no_number').'\');
                                                    }" style="cursor:pointer;">'.$this->CI->lang->line('title_goto').'</a>&nbsp;<input class="formular" id="'.$div.'_page" size="3" value='.$this->page.' onKeyUp="if(event.keyCode == 13){
                                                    if(document.getElementById(\''.$div.'_page\').value.match(/^\d+$/) > 0){
                                                            '.$function.'(\''.$url.'/\' + document.getElementById(\''.$div.'_page\').value)
                                                    }else{
                                                            alert(\''.$this->CI->lang->line('error_no_number').'\');
                                                    }
                                                    }"> / '.$this->pages.'';

                    if($this->output){
                            $output = '<select class="formular" id="'.$div.'_output" name="'.$div.'_output" onChange="'.$function.'(\''.$url.'/s1\')">';
                            
                            $selected_10 = '';
                            $selected_20 = '';
                            $selected_50 = '';
                            $selected_100 = '';
                            
                            if($this->limit == 10){
                                    $selected_10 = ' selected="true"';
                            }
                            if($this->limit == 20){
                                    $selected_20 = ' selected="true"';
                            }
                            if($this->limit == 50){
                                    $selected_50 = ' selected="true"';
                            }
                            if($this->limit == 100){
                                    $selected_100 = ' selected="true"';
                            }
                            $output .= '<option value="10"'.$selected_10.'>10</option><option value="20"'.$selected_20.'>20</option><option value="50"'.$selected_50.'>50</option><option value="100"'.$selected_100.'>100</option>';
                            $output .= '</select>';
                            $this->links[] = $output;
                    }
                    return implode('&nbsp;|&nbsp;',$this->links);
            }
    }
    function get_limit(){
        return(array('begin' => $this->begin,
                     'limit' => $this->limit));
	}
}
?>
