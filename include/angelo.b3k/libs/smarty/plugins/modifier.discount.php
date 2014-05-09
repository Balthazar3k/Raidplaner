<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

function smarty_modifier_discount($price, $discount = false)
{
    global $allgAr;

    if( $discount ){
        $res = round(($price * $discount)/100, 2);
        $price -= $res;
        $price = sprintf("%01.2f", $price);
    } 
    
    return str_replace('.', ',', $price).' '.$allgAr['currency'];
}
?>