<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

if(count($_SESSION['shop']['cart']) == 0 ){
    wd('index.php?shop', 'W&auml;hlen Sie erst ein paar Produkte aus.', 3);
    exit();
}

if( loggedin() ){
    
    switch($menu->get(2)){
        case 'type':
            $type_id = escape($menu->get(3), 'integer');
            $type = order_type($type_id);
            
            // Überprüfen ob Rechte nicht übergangen werden
            if($type['permission']){
                $_SESSION['shop']['order']['order_type'] = $type_id;
            } else {
                unset($_SESSION['shop']['order']['order_type']);
            }
        break;
        case 'address':
            $address_id = escape($menu->get(3), 'integer');
            
            $isset = $core->db()
                    ->select('address_id')
                    ->from('shop_address')
                    ->where(array('address_uid' => $_SESSION['authid'], 'address_id' => $address_id))
                    ->cell();
            
            // Überprüfen ob es die Adresse vom User ist.
            if($isset){
                $_SESSION['shop']['order']['order_address'] = $address_id;
            } else {
                unset($_SESSION['shop']['order']['order_address']);
            }
        break;
        case 'payment': 
            $payment_id = escape($menu->get(3), 'integer');
            $payment = payment_type($payment_id);
            
            // Überprüfen ob Rechte nicht übergangen werden
            if( $_SESSION['authright'] <= $payment['permission'] ){
                $_SESSION['shop']['order']['order_payment'] = $payment_id;
            } else {
                unset($_SESSION['shop']['order']['order_payment']);
            }
        break;
        case 'reset':
            unset($_SESSION['shop']['order'][$menu->get(3)]);
        break;
        case 'clear':
            unset($_SESSION['shop']['order']);
        break;
        
        case 'success':
            //$_SESSION['shop']['order']['order_payment'] = 0;
            include('include/contents/shop/order_success.php');
            exit();
        break;
    }
    
    $_SESSION['shop']['order']['order_user'] = $_SESSION['authid'];
    $_SESSION['shop']['order']['order_price'] = $_SESSION['shop']['price'];
    
    // Order Type
    if( !isset($_SESSION['shop']['order']['order_type']) ){
        include('include/contents/shop/order_type.php');
    } else if ( !isset($_SESSION['shop']['order']['order_address']) ){
        include('include/contents/shop/order_address.php');
    } else if ( !isset($_SESSION['shop']['order']['order_payment']) ){
        include('include/contents/shop/payment_type.php');
    } else if ( !isset($_SESSION['shop']['order']['order_confirm']) ){
        include('include/contents/shop/order_confirm.php');
    }
    
} else {
    // Wenn der User nicht eingeloggt ist!
    $design = new design ( $title , $hmenu );
    $design->header();

    $tpl->display('logreg.tpl');

    $design->footer();
}
?>
