<?php

$sum = array(
    'new' => $core->db()->queryCell("SELECT SUM(order_price) FROM prefix_shop_order WHERE order_process=0;"),
    'process' => $core->db()->queryCell("SELECT SUM(order_price) FROM prefix_shop_order WHERE order_process=1;"),
    'done' => $core->db()->queryCell("SELECT SUM(order_price) FROM prefix_shop_order WHERE order_process=2;"),
    'total' => $core->db()->queryCell("SELECT SUM(order_price) FROM prefix_shop_order;")
);

$tpl->assign('sum', $sum);
$tpl->display('box_total.tpl');
?>