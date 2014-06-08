<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

defined ('main') or die ( 'no direct access' );
include('include/angelo.b3k/func.category.php');

$categoryID = (empty($menu->get(2)) ? 0 : $menu->get(2));

$design = new design ( $title , shop_hmenu('Shop:', '?shop-article', $categoryID) );
$design->header();

shop_bar();
shop_category();

$categoryInfo = $core->db()
        ->select('*')
        ->from('shop_category')
        ->where('category_id', $categoryID)
        ->row();

$article = $core->db()->queryRows(
    standart_article_sql()  
    ."
    WHERE a.article_category = '".$categoryID."'
    ORDER BY a.article_name ASC;
    "
);

$tpl->assign('info', $categoryInfo);
$tpl->assign('article', $article);

$tpl->display('article.tpl');

$design->footer();
?>