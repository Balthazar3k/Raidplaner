<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );

$core->header()->get('core');
$tpl = $core->smarty();
$tpl->assign('menu', $menu->menu_ar);

switch($menu->get(1)){
    default: $shopModule = 'article.php'; break;
    case 'ajax': $shopModule = 'ajax.php'; break;
}

if( !empty( $shopModule )){
    include 'include/contents/shop/'.$shopModule;
}

/* Set last Category ID */
if( $_SESSION['shop']['last_category'] != $menu->get(2) ){
    $_SESSION['shop']['last_category'] = $menu->get(2);
} else {
    $_SESSION['shop']['last_category'] = 0;
}

function standart_article_sql(){
    return "
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
    ";
}
?>