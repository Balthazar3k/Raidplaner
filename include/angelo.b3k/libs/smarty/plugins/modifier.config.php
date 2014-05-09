<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

function smarty_modifier_config($schl)
{
    global $allgAr;  
    return $allgAr[$schl];
}
?>