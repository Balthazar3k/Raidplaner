<?php
function standart_article_sql($select = NULL){
    return "
        SELECT
            SQL_CALC_FOUND_ROWS /* SELECT FOUND_ROWS() */
            a.*, b.*, c.*, 
            
            ".( !empty($select) ? $select.',' : NULL)."

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
        'priceSum' => shop_price($_SESSION['shop']['price']),
        'articleNum' => count($_SESSION['shop']['cart']) 
    );
}

function shop_price($price){   
    global $allgAr;
    $price = sprintf("%01.2f", $price);
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

function order_progressbar(){
    global $core, $tpl;
    $tpl->display('order_progressbar.tpl');
}

function recalc_total_price(){
    $data = array();
    foreach($_SESSION['shop']['cart'] as $id => $val ){
        $data[$id] = $_SESSION['shop']['cart'][$id]['user_price'] = round((($val['user_amount'] / $val['article_amount']) * $val['article_price']), 2);
    }
    
    $_SESSION['shop']['price'] = array_sum($data);
}

function order_type($get = false){
    
    $types = array(
        1 => array(
            'title' => 'Hofladen Balzers',
            'type' => 'Selbstabholer',
            'city' => 'Balzers',
            'message' => 'Im Laden selber Abholen',
            'permission' => true
        ),
        2 => array(
            'title' => 'Gem&uuml;eslada in Vaduz',
            'type' => 'Selbstabholer',
            'city' => 'Vaduz',
            'message' => 'Im Gem&uuml;eslada Vaduz selber Abholen',
            'permission' => true
        ),
        3 => array(
            'title' => 'Hauslieferung',
            'type' => 'Lieferung',
            'city' => 'Hofladen',
            'message' => 'Die Ware wird Geliefert, die Lieferreichweite liegt bei 15km',
            'permission' => ($_SESSION['shop']['price'] >= 15)
        )
    );
    
    if( $get ){
        return $types[$get];
    } else {
        return $types;
    }
} 	


function payment_type($get = false){
    
    $types = array(
        1 => array(
            'title' => 'Barzahlung',
            'type' => 'Bar Bezahlen',
            'permission' => -1,
            'message' => 'Bar Zahlen bei Lieferung oder Abholung'
        ),
        2 => array(
            'title' => 'Auf Rechnung Kaufen',
            'type' => 'Rechnung',
            'permission' => -1,
            'message' => 'Auf Rechnung, Sie bekommen ein &uuml;berweisungsschein'
        ),
        3 => array(
            'title' => 'Anschreiben Lassen',
            'type' => 'Anschreiben',
            'permission' => -4,
            'message' => 'Kosten werden Angeschrieben, Rechnung wird am ende eines Monats verschickt'
        )
    );
    
    if( $get ){
        return $types[$get];
    } else {
        return $types;
    }
} 
?>