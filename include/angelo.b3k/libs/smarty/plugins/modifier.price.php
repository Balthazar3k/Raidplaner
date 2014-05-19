<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

function smarty_modifier_price($price)
{   
    global $allgAr;
    $price = sprintf("%01.2f", $price);
    $price = str_replace('.', ',', $price).' '.$allgAr['currency'];
    
    return $price;
}
?>