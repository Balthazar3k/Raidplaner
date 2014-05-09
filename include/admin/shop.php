<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$core->header()->get('font-awesome', 'jquery', 'core', 'bootstrap');
$tpl = $core->smarty();
$tpl->assign('menu', $menu->menu_ar);

switch($menu->get(1)){
    default: $shopModule = 'category.php'; break;
    case 'category': $shopModule = 'category.php'; break;
    case 'article': $shopModule = 'article.php'; break;
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