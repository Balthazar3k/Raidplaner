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

$article = $core->db()->queryRows("
    SELECT
        SQL_CALC_FOUND_ROWS /* SELECT FOUND_ROWS() */
        a.*, b.*, c.*,
        
        /* Calc Price with Tax */
        ROUND(((a.article_netprice*a.article_tax)/100),2) AS article_taxprice,
        ROUND((((a.article_netprice*a.article_tax)/100)+article_netprice),2) AS article_taxnetprice,

        /* Calc Price with Discount */
        ROUND(((a.article_netprice*a.article_discount)/100),2) AS article_discountprice,
        ROUND((((a.article_netprice*a.article_discount)/100)-a.article_netprice),2) AS article_discountnetprice,

        /* Calc final Price */
        ROUND(((a.article_netprice-(a.article_netprice*a.article_discount)/100)+((a.article_netprice*a.article_tax)/100)),2) AS article_grossprice
    
    FROM prefix_shop_articles AS a
        LEFT JOIN prefix_shop_units AS b ON a.article_unit = b.unit_id
        LEFT JOIN prefix_shop_category AS c ON a.article_category = c.category_id
    WHERE a.article_category = '".$categoryID."'
    ORDER BY a.article_name ASC;
");

$units = $core->db()
        ->select('*')
        ->from('shop_units')
        ->rows();

$tpl->assign('category', $category);
$tpl->assign('article', $article);
$tpl->assign('units', $units);

$tpl->display('article.tpl');

$design->footer();
?>