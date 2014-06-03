<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

/* Balthazar3k Core */
require_once ('include/angelo.b3k/core.php');
require_once ('include/angelo.b3k/func.shop.php');
$core = new Core();

$core->header()
    ->set('font-awesome/css/font-awesome.min.css')
        
    ->set('jquery/js/jquery-1.10.2.js')
    ->set('jquery/js/jquery-ui-1.10.4.custom.min.js')
    ->set('jquery/css/ui-darkness/jquery-ui-1.10.4.custom.min.css')
        
    ->set('core/core.css')
    ->set('core/core.js')

    ->set('bootstrap/css/bootstrap.min.css')
    ->set('bootstrap/css/bootstrap-theme.min.css')
    ->set('bootstrap/js/bootstrap.min.js');

$core->header()->get('font-awesome', 'jquery', 'core', 'bootstrap');
$tpl = $core->smarty();
$tpl->assign('menu', $menu->menu_ar);

switch($menu->get(1)){
    default: $shopModule = 'order.php'; break;
    case 'category': $shopModule = 'category.php'; break;
    case 'article': $shopModule = 'article.php'; break;
    case 'print': $shopModule = 'printing.php'; break;
}

if( !empty( $shopModule )){
    include 'include/admin/shop/'.$shopModule;
}

/* Set last Category ID */
if( $_SESSION['shop']['last_category'] != $menu->get(2) ){
    $_SESSION['shop']['last_category'] = $menu->get(2);
} else {
    $_SESSION['shop']['last_category'] = 0;
}
?>