<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

defined ('main') or die ( 'no direct access' );

$article = $core->db()->queryRow(
    standart_article_sql()  
    ."
    WHERE a.article_id = '".$menu->get(2)."'
    ORDER BY a.article_name ASC;
    "
);

$design = new design ( $title , 'Detail: <b>' . $article['article_name'] .'</b>');
$design->header();

shop_bar();

$tpl->assign('cart', session_shoppingCart());
$tpl->assign('article', $article);
$tpl->display('details.tpl');

$design->footer();
?>