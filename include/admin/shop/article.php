<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch ($menu->get(3)){
    case 'edit':
        $articleEdit = $core->db()
            ->select('*')
            ->from('shop_articles')
            ->where('article_id', $menu->get(4))
            ->row();
    break;

    case 'save':
        
        $core->upload()
            ->type('jpg', 'gif', 'jpeg', 'png')
            ->path('include/angelo.b3k/images/shop_articles/');
        
        /* Change Price to Decimal */
        $_POST['article_netprice'] = str_replace(',','.', $_POST['article_netprice']);
        
        if($menu->get(4)){
            
            $_POST['article_image'] = $core->upload()
                ->name($menu->get(4))
                ->init();
            
            $core->db()->singel()
                ->update('shop_articles')
                ->fields($_POST)
                ->where('article_id', $menu->get(4))
                ->init();
            
        } else {
            $last_id = $core->db()->queryRow("
                SELECT MAX(article_id) FROM prefix_shop_articles;
            ");
            
            $_POST['article_image'] = $core->upload()
                ->name($last_id)
                ->init();
            
            $_POST['article_category'] = $menu->get(2);
            $core->db()->singel()
                ->insert('shop_articles')
                ->fields($_POST)
                ->init();
            
        }

    break;
    
    case 'delete':
        
        if( $menu->getA(4) == 't' ){
            
            $image = $core->db()
                ->select('article_image')
                ->from('shop_articles')
                ->where('article_id', $menu->getE(4))
                ->cell();
            
            /* Lösche Image */
            @unlink($image);
            
            /* Lösche Kategorie */
            $core->db()->delete('shop_articles')
                ->where('article_id', $menu->getE(4))
                ->init();
            
        } else {
            $name = $core->db()
                ->select('article_name')
                ->from('shop_articles')
                ->where('article_id', $menu->get(4))
                ->cell();
            
            echo $core->confirm()
                ->message('Möchten Sie wirklich den Artikel "'.$name.'" löschen?')
                ->onTrue('admin.php?shop-article-'.$menu->get(2).'-delete-t'.$menu->get(4))
                ->onFalse('admin.php?shop-article-'.$menu->get(2))
                ->panel('Aktion bestätigen!');
        }
               
    break;
    
    default:
        $articleEdit = NULL;
    break;
}

$design = new design ( 'Admins Area', 'Admins Area', 2 );
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

//$core->func()->ar($article);

$tpl->assign('category', $category);
$tpl->assign('article', $article);
$tpl->assign('units', $units);
$tpl->assign('edit', array(
    'id' => $articleID,
    'res' => $articleEdit
));

$tpl->display('article_treat.tpl');



$design->footer();
?>