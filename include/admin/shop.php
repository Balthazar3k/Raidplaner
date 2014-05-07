<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$core->header()->get('font-awesome', 'jquery', 'core', 'bootstrap');
$tpl = $core->smarty();
$tpl->assign('menu', $menu->menu_ar);

//$core->func()->ar($menu->menu_ar);

switch($menu->get(1)){
    default: $shopModule = 'category.php'; break;
    case 'category': $shopModule = 'category.php'; break;
    case 'article': $shopModule = 'article.php'; break;
}

if( !empty( $shopModule )){
    include 'include/admin/shop/'.$shopModule;
}

/* Set last Category ID */
$_SESSION['shop']['last_category'] = $menu->get(2);

/*
function categorys_array(){
    global $core;
    $array = array();
            
    $res = $core->db()->select('category_id', 'category_sub', 'category_name')
            ->from('article_category')
            ->order(array('category_name' => 'ASC'))
            ->where('category_sub')
            ->rows();
    
    foreach( $res as $val ){
        if( $val['category_sub'] == 0 ) {
            $array[$val['category_id']][$val['category_sub']] = $val;
        } else {
            $array[$val['category_sub']][$val['category_id']] = $val;
        }
    }
    
    return $array;
} */
?>