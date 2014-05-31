<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );

$ajax = array();

switch ($menu->get(2)){
    case "shoppingCart":
        
        /* Calc Price & List Article ID in Session */       
        $user_price = shop_price((($_POST['user_amount'] / $_POST['article_amount'])*$_POST['article_grossprice']));
        $_SESSION['shop']['price'] += $user_price;
               
        $_SESSION['shop']['cart'][$_POST['article_id']] = array(
            'article_id' => $_POST['article_id'], 
            'article_amount' => $_POST['article_amount'],
            'article_price' => $_POST['article_grossprice'],
            'user_amount' => $_POST['user_amount'] + $_SESSION['shop']['cart'][$_POST['article_id']]['user_amount'],
            'user_price' => $user_price + $_SESSION['shop']['cart'][$_POST['article_id']]['price']
        );
        
        
        /* Send Json */
        exit(json_encode(session_shoppingCart()));
        
    break;

    case 'clearShoppingCart':
        $_SESSION['shop']['price'] = shop_price(0);
        $_SESSION['shop']['cart'] = array();
        unset($_SESSION['shop']['order']);
        exit(json_encode(session_shoppingCart()));
    break;
    
    case 'search':
        $search = str_replace(' ', '%', htmlentities($_POST['search']));

        $article = $core->db()->queryRows(
            standart_article_sql()  
            ."
            WHERE (
                a.article_name LIKE '%". $search ."%' OR 
                a.article_description LIKE '%". $search ."%' OR
                c.category_name LIKE '%". $search ."%' OR
                c.category_description LIKE '%". $search ."%'
            )
            ORDER BY a.article_name ASC;
            "
        );

        $tpl->assign('article', $article);
        $tpl->display('shop_search.tpl');
        exit();
    break;
}
?>