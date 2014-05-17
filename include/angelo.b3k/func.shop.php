<?php

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

function session_shoppingCart(){
    return array(
        'priceSum' => ( !empty($_SESSION['shop']['price']) ? $_SESSION['shop']['price'] : 0 ),
        'articleNum' => count($_SESSION['shop']['cart']) 
    );
}

function shop_price($price){   
    global $allgAr;
    $price = sprintf("%01.2f", $price);
    $price = str_replace('.', ',', $price);
    return $price;
}

function shop_category(){
    global $core, $tpl, $menu;
    
    $categoryID = (empty($menu->get(2)) ? 0 : $menu->get(2));

    $category = $core->db()
            ->select('*')
            ->from('shop_category')
            ->where('category_sub', $categoryID)
            ->rows();
    
    $tpl->assign('category', $category);
    $tpl->display('shop_category.tpl');
}

function shop_bar(){
    global $core, $tpl;
    
    $tpl->assign('cart', session_shoppingCart());
    $tpl->display('shop_bar.tpl');
}

?>