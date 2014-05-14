<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );

$design = new design ( $title , $hmenu );
$design->header();

$categoryID = (empty($menu->get(2)) ? 0 : $menu->get(2));

$category = $core->db()
        ->select('*')
        ->from('shop_category')
        ->where('category_sub', $categoryID)
        ->rows();

$article = $core->db()->queryRows(
    standart_article_sql()  
    ."
    WHERE a.article_category = '".$categoryID."'
    ORDER BY a.article_name ASC;
    "
);


$units = $core->db()
        ->select('*')
        ->from('shop_units')
        ->rows();

$tpl->assign('cart', session_shoppingCart());
$tpl->assign('category', $category);
$tpl->assign('article', $article);
$tpl->assign('units', $units);

$tpl->display('article.tpl');

$design->footer();
?>