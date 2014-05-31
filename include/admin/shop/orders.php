<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$orders = $core->db()->queryRows("
    SELECT 
        a.*,
        b.name AS order_name,
        (SELECT COUNT(article.order_id) FROM prefix_shop_order_article AS article WHERE  article.order_id = a.order_id) AS num
    FROM prefix_shop_order AS a
        LEFT JOIN prefix_user AS b ON b.id = a.order_user
    WHERE
        a.order_process = '". escape($menu->get(2), integer) ."'
    ORDER BY
        a.order_date ASC
");

$tpl->assign('orders', $orders);
$tpl->assign('order_type', order_type());
$tpl->assign('payment_type', payment_type());
$tpl->display('orders.tpl');
?>