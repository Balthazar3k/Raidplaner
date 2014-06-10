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

$hmenu = $title = 'Details von ' . $article['article_name'];
$design = new design ( $title , $hmenu);
$design->header();

shop_bar();

$tpl->assign('a', $article);
$tpl->display('index_details.tpl');

$design->footer();
?>