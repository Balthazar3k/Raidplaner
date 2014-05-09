<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

function smarty_modifier_price($price)
{   
    global $allgAr;
    $price = str_replace('.', ',', $price).' '.$allgAr['currency'];
    
    return $price;
}
?>