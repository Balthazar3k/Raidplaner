<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );

$ajax = array();

switch ($menu->get(2)){
    case "shoppingCart":
        
        /* Calc Price & List Article ID in Session */
        
        /* FEHLER */
        
        $_SESSION['shop']['price'] += shop_price(($_POST['user_amount'] / $_POST['article_amount'])*$_POST['article_grossprice'], 2);
        $_SESSION['shop']['cart'][] = array(
            'article_id' => $_POST['article_id'], 
            'article_amount' => $_POST['user_amount']
        );
        
        
        /* Send Json */
        exit(json_encode(session_shoppingCart()));
        
    break;
    
    case 'search':
        $article = $core->db()->queryRows(
            standart_article_sql()  
            ."
            WHERE a.article_name LIKE '%". escape($_POST['search'], string) ."%'
            ORDER BY a.article_name ASC;
            "
        );

        $tpl->assign('article', $article);
        $tpl->display('shop_search.tpl');
        exit();
    break;
}
?>