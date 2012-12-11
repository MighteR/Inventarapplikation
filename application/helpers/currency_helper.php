<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function formatCurrency($value,$style = FALSE){
    if($style){
        return number_format(round($value / 0.05) * 0.05, 2, '.', '\'');
    }else{
        return number_format(round($value / 0.05) * 0.05, 2, '.', '');
    }
}
?>
