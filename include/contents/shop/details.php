<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

defined ('main') or die ( 'no direct access' );

$design = new design ( $title , $hmenu );
$design->header();

shop_bar();

$article = $core->db()->queryRows(
    standart_article_sql()  
    ."
    WHERE a.article_id = '".$menu->get(2)."'
    ORDER BY a.article_name ASC;
    "
);

$tpl->assign('cart', session_shoppingCart());
$tpl->assign('article', $article);
$tpl->display('details.tpl');

$design->footer();
?>