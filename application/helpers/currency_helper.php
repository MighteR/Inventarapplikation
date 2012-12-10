<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function formatCurrency($value){
    return number_format(round($value*20)/20,2);
}
?>
