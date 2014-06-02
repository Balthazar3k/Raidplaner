<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

$order_id = escape($menu->get(2), integer);

switch($menu->getA(3)){
    case 'c':
        echo $core->confirm()
                ->message('Möchten Sie wirklich die bestellungs Status wechseln, es wird eine eMail an den Käufer geschickt, das die Bestellung fertig gestellt wurde?')
                ->onTrue('admin.php?shop-details-'.$menu->get(2).'-p'.$menu->getE(3), 'Auftrag ist erledigt!')
                ->onFalse('admin.php?shop-details-'.$menu->get(2))
                ->panel('Aktion bestätigen!');
        
        $design->footer();
        exit();
    break;

    case 'p':
        if( $menu->getE(3) == 2 ){
            $user_mail = $core->db()->queryCell("
                SELECT 
                    b.email
                FROM prefix_shop_order AS a
                    LEFT JOIN prefix_user AS b ON b.id = a.order_user
                WHERE
                    a.order_id = '". $order_id ."'
            ");
            
            $mail = $tpl->fetch('mail_completed.tpl');
            
            icmail($user_mail, 'Ihre Bestellung beim Hofladen', $mail, 'noreplay@niggshofladen.li', true);
            
            $core->db()
                ->update('shop_order_article')
                ->fields(array('order_process' => $menu->getE(3)))
                ->where('order_id', $order_id)
                ->init();
        }
          
        $core->db()
            ->update('shop_order')
            ->fields(array('order_process' => $menu->getE(3)))
            ->where('order_id', $order_id)
            ->init();
    break;

    case 'a':
        $core->db()
            ->update('shop_order_article')
            ->fields(array('order_process' => $menu->getE(3)))
            ->where(array(
                'article_id' => $menu->get(4),
                'order_id' => $order_id
            ))
            ->init();
    break;

    case 'd':
        $core->db()
            ->delete('shop_order')
            ->where('order_id', $order_id)
            ->init();
        
        $core->db()
            ->delete('shop_order_article')
            ->where('order_id', $order_id)
            ->init();
        
        wd('admin.php?shop-orders','Vielen Dank, auf Wiedersehen :)',0);
        $design->footer();
        exit();
    break;
}



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
$tpl->display('order_details.tpl');
?>