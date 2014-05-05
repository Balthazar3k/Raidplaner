<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch ($menu->get(2)){
    case 'edit':
        $categoryEdit = $core->db()
            ->select('*')
            ->from('article_category')
            ->where('category_id', $menu->get(3))
            ->row();
    break;

    case 'save':

        $core->upload()
            ->type('jpg', 'gif', 'jpeg', 'png')
            ->path('include/angelo.b3k/images/shop_category/');
        
        if($menu->get(3)){
            
            $_POST['category_image'] = $core->upload()
                ->name($menu->get(3).'_'. $_POST['category_name'])
                ->init();
            
            $core->db()->singel()
                ->update('article_category')
                ->fields($_POST)
                ->where('category_id', $menu->get(3))
                ->init();
            
        } else {
            $last_id = $core->db()->queryRow("
                SELECT MAX(category_id) FROM prefix_article_category;
            ");
            
            $_POST['category_image'] = $core->upload()
                ->name($last_id.'_'. $_POST['category_name'])
                ->init();
            
            $_POST['category_sub'] = $menu->get(1);
            $core->db()->singel()
                ->insert('article_category')
                ->fields($_POST)
                ->init();
            
        }

    break;
    
    case 'delete':
        
        if( $menu->getA(3) == 't' ){
            
            $image = $core->db()
                ->select('category_image')
                ->from('article_category')
                ->where('category_id', $menu->getE(3))
                ->cell();
            
            /* Lösche Image */
            @unlink($image);
            
            /* Lösche Kategorie */
            $core->db()->delete('article_category')
                ->where('category_id', $menu->getE(3))
                ->init();
            
            /* Lösche Sub Kategorien */
            $core->db()->delete('article_category')
                ->where('category_sub', $menu->getE(3))
                ->init();
            
        } else {
            $name = $core->db()
                ->select('category_name')
                ->from('article_category')
                ->where('category_id', $menu->get(3))
                ->cell();
            
            echo $core->confirm()
                ->message('Möchten Sie wirklich die Kategorie "'.$name.'" löschen, alle Artikel und Unterkategorien werden mitgelöscht?')
                ->onTrue('admin.php?shop_category-'.$menu->get(1).'-delete-t'.$menu->get(3))
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


$categoryID = (empty($menu->get(1)) ? 0 : $menu->get(1));
$articleCategory = $core->db()
        ->select('*')
        ->from('article_category')
        ->where('category_sub', $categoryID)
        ->rows();

$tpl = $core->smarty();

$tpl->assign('category', $articleCategory);

$tpl->assign('edit', array(
    'id' => $categoryID,
    'res' => $categoryEdit
));

$tpl->display('article_category.tpl');



$design->footer();

function categorys($field, $category) {
    if( !is_array($category) ){
        return NULL;
    }
    
    $array = array();
    
    foreach( $category as $i => $v ){
        $array[$i] = '';
    }
}

?>