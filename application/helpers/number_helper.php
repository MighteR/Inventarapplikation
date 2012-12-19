<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************************************************************************
 * Version: 1.0
 * 
 * 
 * 
 * Version  Developer   Description
 * 1.0                  Standard release
*******************************************************************************/

//Function for formating (including tousand seperator) and rounding to 0.05
function formatNumber($value, $style = FALSE){
    if($style){
        return number_format(round($value / 0.05) * 0.05, 2, '.', '\'');
    }else{
        return number_format(round($value / 0.05) * 0.05, 2, '.', '');
    }
}

/* End of file number_helper.php */
/* Location: ./application/helpers/number_helper.php */
?>
