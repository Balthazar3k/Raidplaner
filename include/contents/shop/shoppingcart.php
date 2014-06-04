<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

defined ('main') or die ( 'no direct access' );

switch ($menu->get(2)){
    case "recalc":
        $i = (object) $_GET;
        
        /* Setze anzahl der Menge neu */
        $amount = $_SESSION['shop']['cart'][$i->article_id]['user_amount'];
        if( $amount > $i->article_amount ) {
            $_SESSION['shop']['cart'][$i->article_id]['user_amount'] = ( ( $i->data == 'p') ? ($amount+$i->article_amount) : ($amount-$i->article_amount) );
        } else {
            $_SESSION['shop']['cart'][$i->article_id]['user_amount'] = ( ( $i->data == 'p') ? ($amount+$i->article_amount) : ($amount) );
        }
        
        recalc_total_price();
        wd('index.php?shop-shoppingcart#article'.$i->article_id, 'Neuberechnung abgeschlossen!', 0);
        exit();
    break;
    
    case "delete":
        $i = $menu->get(3);
        
        /* Aus dem Warenkorb Löschen */
        unset($_SESSION['shop']['cart'][$i]);

        recalc_total_price();
        wd('index.php?shop-shoppingcart#article'.$i, 'Neuberechnung abgeschlossen!', 0);
        exit();
    break;

    case "clear":
        $_SESSION['shop']['price'] = shop_price(0);
        $_SESSION['shop']['cart'] = array();
        unset($_SESSION['shop']['order']);
        wd('index.php?shop', 'Warenkorb wurde geleert', 3);
        exit();
    break;
}

$design = new design ( $title , $hmenu );
$design->header();

if( is_array($_SESSION['shop']['cart']) ){
    
    $article_id = array();
    foreach ($_SESSION['shop']['cart'] as $key => $val){
        $article_id[] = $val['article_id'];
    }

    if( is_array($article_id) && !empty($article_id) ){
        $article = $core->db()->queryRows(standart_article_sql() . "
            WHERE a.article_id IN(".implode(',', $article_id).");
        ");
    }
}

$tpl->assign('article', $article);
$tpl->display('shopping_cart.tpl');


$design->footer();
?>