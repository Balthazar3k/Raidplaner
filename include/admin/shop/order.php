<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch($menu->get(1)){
    case 'orders': $orderModule = 'orders.php'; break;
    case 'details': $orderModule = 'order_details.php'; break;
}

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

$new = $core->db()->queryCell("SELECT COUNT(order_id) FROM prefix_shop_order WHERE order_process=0;");
$cur = $core->db()->queryCell("SELECT COUNT(order_id) FROM prefix_shop_order WHERE order_process=1;");
$old = $core->db()->queryCell("SELECT COUNT(order_id) FROM prefix_shop_order WHERE order_process=2;");
$sto = $core->db()->queryCell("SELECT COUNT(order_id) FROM prefix_shop_order WHERE order_process=3;");

$tpl->assign('new', $new);
$tpl->assign('cur', $cur);
$tpl->assign('old', $old);
$tpl->assign('sto', $sto);
$tpl->display('order_index.tpl');

if( !empty($orderModule) ){
    include('include/admin/shop/'.$orderModule);
}

echo '<br style="clear: both;" />';

$design->footer();
?>