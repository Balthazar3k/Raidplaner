<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

$core->header()->init('bootstrap', 'font-awesome');

$order_id = escape($menu->get(2), integer);

$data = array();

$data['order'] = $core->db()->queryRow("
    SELECT 
        a.*, c.*,
        b.name AS order_name
    FROM prefix_shop_order AS a
        LEFT JOIN prefix_user AS b ON b.id = a.order_user
        LEFT JOIN prefix_shop_address AS c ON c.address_id = a.order_address
    WHERE
        a.order_id = '". $order_id ."'
    ORDER BY
        a.order_date ASC
");

$res = $core->db()
        ->select('*')
        ->from('shop_order_article')
        ->where('order_id', $order_id)
        ->rows();

foreach($res as $k => $v){
    $article = array();
    $article['article'] = $core->db()->queryRow(standart_article_sql() . "
        WHERE article_id = '".$v['article_id']."'
        LIMIT 1;
    ");
    $article['order'] = $v;
    
    $data['article'][$v['article_id']] = $article;
}

//$core->func()->ar($data);

$tpl->assign('data', $data);
$tpl->assign('order_type', order_type());
$tpl->assign('payment_type', payment_type());
$tpl->display('print_order.tpl');
?>