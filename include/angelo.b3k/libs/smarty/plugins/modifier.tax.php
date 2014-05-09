<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

function smarty_modifier_tax($price, $tax)
{
    global $allgAr;

    if( $tax ){
        $res = round((($price * $tax)/100), 2);
        $price += $res;
        $price = sprintf("%01.2f", $price);
    } 
    return str_replace('.', ',', $price).' '.$allgAr['currency'];
}
?>