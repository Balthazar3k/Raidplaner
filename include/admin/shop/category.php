<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch ($menu->get(3)){
    case 'edit':
        $categoryEdit = $core->db()
            ->select('*')
            ->from('shop_category')
            ->where('category_id', $menu->get(4))
            ->row();
    break;

    case 'save':

        $core->upload()
            ->type('jpg', 'gif', 'jpeg', 'png')
            ->path('include/angelo.b3k/images/shop_category/');
        
        if($menu->get(4)){
            
            $_POST['category_image'] = $core->upload()
                ->name($menu->get(4))
                ->init();
            
            $core->db()->singel()
                ->update('shop_category')
                ->fields($_POST)
                ->where('category_id', $menu->get(4))
                ->init();
            
        } else {
            $last_id = $core->db()->queryRow("
                SELECT MAX(category_id) FROM prefix_shop_category;
            ");
            
            $_POST['category_image'] = $core->upload()
                ->name($last_id)
                ->init();
            
            $_POST['category_sub'] = $menu->get(2);
            $core->db()->singel()
                ->insert('shop_category')
                ->fields($_POST)
                ->init();
            
        }

    break;
    
    case 'delete':
        
        if( $menu->getA(4) == 't' ){
            
            $image = $core->db()
                ->select('category_image')
                ->from('shop_category')
                ->where('category_id', $menu->getE(4))
                ->cell();
            
            /* Lösche Image */
            @unlink($image);
            
            /* Lösche Kategorie */
            $core->db()->delete('shop_category')
                ->where('category_id', $menu->getE(4))
                ->init();
            
            /* Lösche Sub Kategorien */
            $core->db()->delete('shop_category')
                ->where('category_sub', $menu->getE(4))
                ->init();
            
        } else {
            $name = $core->db()
                ->select('category_name')
                ->from('shop_category')
                ->where('category_id', $menu->get(4))
                ->cell();
            
            echo $core->confirm()
                ->message('Möchten Sie wirklich die Kategorie "'.$name.'" löschen, alle Unterkategorien werden mitgelöscht?')
                ->onTrue('admin.php?shop-category-'.$menu->get(2).'-delete-t'.$menu->get(4))
                ->html('Aktion bestätigen!');
        }
               
    break;
    
    default:
        $categoryEdit = NULL;
    break;
}

$core->header()->get('font-awesome', 'jquery', 'core', 'bootstrap');

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();


$categoryID = (empty($menu->get(2)) ? 0 : $menu->get(2));
$articleCategory = $core->db()
        ->select('*')
        ->from('shop_category')
        ->where('category_sub', $categoryID)
        ->rows();


$tpl->assign('category', $articleCategory);

$tpl->assign('edit', array(
    'id' => $categoryID,
    'res' => $categoryEdit
));

$tpl->display('article_category.tpl');



$design->footer();
?>