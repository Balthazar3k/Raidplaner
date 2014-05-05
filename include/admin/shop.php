<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch($menu->get(1)){
    default: $shopModule = 'category.php'; break;
    case 'category': $shopModule = 'category.php'; break;
}

if( !empty( $shopModule )){
    include 'include/admin/shop/'.$shopModule;
}
?>