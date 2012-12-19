<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

require_once APPPATH.'/third_party/excel/PHPExcel.php';

class Excel extends PHPExcel{
   
    function __construct(){
        parent::__construct();
    }
}

/* End of file Excel.php */
/* Location: ./application/libraries/Excel.php */
?>