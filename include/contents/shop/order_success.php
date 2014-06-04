<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

$result = false;

switch($menu->get(2)){
    case 'success':
        if( $_POST['agb'] ){
            $status = array();
            
            // Bestellung aufnehmen
            $res = $core->db()
                    //->singel()
                    ->insert('shop_order')
                    ->fields($_SESSION['shop']['order'])
                    ->init();
            
            if( $res ){
                $id = $core->db()->queryCell('SELECT MAX(order_id) FROM prefix_shop_order');

                // Artikel der Bestellung eintragen.
                foreach( $_SESSION['shop']['cart'] as $aid => $article ){

                    $article['order_id'] = $id;
                    $article['user_id'] = $_SESSION['authid'];

                    $status[] = $core->db()
                            ->insert('shop_order_article')
                            ->fields($article)
                            ->init();
                }
                
                
                // Wenn keine Fehler vorkommen, werden eMails Verschickt mit einer Übersicht über dem Einkauf
                if( !in_array( false, $status ) ){
                    $result = true;
                    
                    $user_mail = $core->db()
                            ->select('email')
                            ->from('user')
                            ->where('id', $_SESSION['authid'])
                            ->cell();
                            
                    
                    $article_id = array();
                    foreach ($_SESSION['shop']['cart'] as $key => $val){
                        $article_id[] = $val['article_id'];
                    }

                    if( is_array($article_id) && !empty($article_id) ){
                        $article = $core->db()->queryRows(standart_article_sql() . "
                            WHERE a.article_id IN(".implode(',', $article_id).");
                        ");
                    }

                    $address = $core->db()
                            ->select('*')
                            ->from('shop_address')
                            ->where('address_id', $_SESSION['shop']['order']['order_address'])
                            ->row();
                    
                    $tpl->assign('order_id', $id);
                    $tpl->assign('address', $address);
                    $tpl->assign('payment_type', payment_type($_SESSION['shop']['order']['order_payment']));
                    $tpl->assign('order_type', order_type($_SESSION['shop']['order']['order_type']));
                    $tpl->assign('article', $article);
                    $mail = $tpl->fetch('order_mail.tpl');
                    
                    icmail($user_mail, 'Ihre Bestellung beim Hofladen', $mail, $allgAr['email_noreplay'], true);
                    icmail($allgAr['shop_order_email'], 'Ihre Bestellung beim Hofladen', $mail, $user_mail, true);
                    
                    // Bestellung aus der Session  Löschen
                    unset($_SESSION['shop']);
                }
            }
            
        }
    break;
}

$design = new design ( $title , $hmenu );
$design->header();

$tpl->assign('order_status', $result);
$tpl->display('order_success.tpl');

$design->footer();
?>