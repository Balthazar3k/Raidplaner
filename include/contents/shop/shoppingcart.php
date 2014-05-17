<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

defined ('main') or die ( 'no direct access' );

$design = new design ( $title , $hmenu );
$design->header();

if(is_array($_SESSION['shop']['cart']['article_id'])) {
    $article = $core->db()->queryRows(standart_article_sql() . "
        WHERE a.article_id IN(".implode(',', $_SESSION['shop']['cart']['article_id']).");
    ");
}



$tpl->assign('article', $article);
$tpl->display('shoppingcart.tpl');
$core->func()->ar($_SESSION['shop'], $article);

$design->footer();
?>