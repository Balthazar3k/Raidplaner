<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

if( loggedin() ){
    
    switch($menu->get(2)){
        case 'type':
            $_SESSION['shop']['order']['order_type'] = $menu->get(3);
        break;
        case 'address':
            $_SESSION['shop']['order']['order_address'] = $menu->get(3);
        break;
    }
    
    $core->func()->ar($_SESSION['shop']);
    
    $_SESSION['shop']['order']['order_user'] = $_SESSION['authid'];
    $_SESSION['shop']['order']['order_price'] = $_SESSION['shop']['price'];

    // Order Type
    if( !isset($_SESSION['shop']['order']['order_type']) ){
        include('include/contents/shop/ordertype.php');
    }
    
    // Address
    if( !isset($_SESSION['shop']['order']['order_address']) ){
        include('include/contents/shop/address.php');
    }
    
} else {
   /* Login oder Registrieren */
}

//unset($_SESSION['shop']['order']['order_type']);
unset($_SESSION['shop']['order']['order_address']);
$core->func()->ar($_SESSION['shop']);



?>
