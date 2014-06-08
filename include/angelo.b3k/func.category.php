<?php
function shop_categoryString($id){
    global $core;
    
    $ar = array();
    
    $cat = $core->db()
            ->select('category_id', 'category_sub', 'category_name')
            ->from('shop_category')
            ->where('category_id', $id)
            ->row();
    
    if( ((integer) $cat['category_sub']) ){
        $res = shop_categoryString($cat['category_sub']);
        $ar = array_merge($ar, $res);
        $ar[] = $cat;
    } else {
        $ar[] = $cat;
    }
    
    return $ar;
}

function shop_hmenu($name, $url, $id){
    global $tpl;
    
    $tpl->assign('url', $url);
    $tpl->assign('name', $name);
    $tpl->assign('hmenu', shop_categoryString($id));
    return $tpl->fetch('hmenu_category.tpl');
}
?>

