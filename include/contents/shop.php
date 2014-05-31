<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );

/* Balthazar3k Core */
require_once ('include/angelo.b3k/core.php');
require_once ('include/angelo.b3k/func.shop.php');
$core = new Core();

$core->header()
    ->set('core/core.css')
    ->set('core/core.js');

$core->header()->get('core');
$tpl = $core->smarty();
$tpl->assign('menu', $menu->menu_ar);

switch($menu->get(1)){
    default: $shopModule = 'article.php'; break; 
    case 'details': $shopModule = 'details.php'; break;
    case 'shoppingcart': $shopModule = 'shoppingcart.php'; break;
    case 'order': $shopModule = 'order.php'; break;
    case 'ajax': $shopModule = 'ajax.php'; break;
}

if( !empty( $shopModule )){
    include 'include/contents/shop/'.$shopModule;
}

/* Set last Category ID */
if( $menu->get(1) == 'article' && $_SESSION['shop']['last_category'] != $menu->get(2) ){
    $_SESSION['shop']['last_category'] = $menu->get(2);
} else if( $menu->get(1) == 'article' ) {
    $_SESSION['shop']['last_category'] = 0;
}
?>