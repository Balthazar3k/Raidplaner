<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );

/* Load Shop Files */
require_once 'include/angelo.b3k/func.shop.php';

$core->header()->get('core');
$tpl = $core->smarty();
$tpl->assign('menu', $menu->menu_ar);

switch($menu->get(1)){
    default: $shopModule = 'article.php'; break;
    case 'ajax': $shopModule = 'ajax.php'; break;
}

if( !empty( $shopModule )){
    include 'include/contents/shop/'.$shopModule;
}

/* Set last Category ID */
if( $_SESSION['shop']['last_category'] != $menu->get(2) ){
    $_SESSION['shop']['last_category'] = $menu->get(2);
} else {
    $_SESSION['shop']['last_category'] = 0;
}
?>