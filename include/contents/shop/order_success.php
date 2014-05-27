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

                if( !in_array( false, $status ) ){
                    $result = true;
                    unset($_SESSION['shop']);
                }
            }
            
        }
    break;
}

$design = new design ( $title , $hmenu );
$design->header();

$core->func()->ar($result);

$design->footer();
?>