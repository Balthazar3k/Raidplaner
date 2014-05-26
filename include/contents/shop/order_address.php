<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

switch($menu->get(2)){
    case 'saveAddress':
        $core->func()->ar('Hallo Welt!');
        $status = array();
        foreach($_POST as $key => $val ){
            $status[$key] = (bool) !empty($val);
        }
        
        if( !in_array(false, $status) ){
            
            $_POST['address'] = $_SESSION['authid'];
            
            if( $menu->get(3) ){
                $core->db()->singel()->update('shop_address')->fields($_POST)->where('address_id', $menu->get(3))->init();
            } else {
                $core->db()->singel()->insert('shop_address')->fields($_POST)->init();
            }
        } else {
            $tpl->assign('status', $status);
            $edit = $_POST;
        }
    break;
    
    case 'deleteAddress':
        $core->db()->singel()->delete('shop_address')->where('address_id', $menu->get(3))->init();
    break;

    case 'editAddress':
        $edit = $core->db()->select('*')->from('shop_address')->where('address_id', $menu->get(3))->row();
    break;
}

$design = new design ( $title , $hmenu );
$design->header();

$tpl->assign('edit', $edit);
$tpl->assign('address', $core->db()
        ->select('*')
        ->from('shop_address')
        ->rows()
);

$tpl->display('order_address.tpl');

$design->footer();
?>