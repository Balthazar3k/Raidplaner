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
        case 'payment':
            $_SESSION['shop']['order']['order_payment'] = $menu->get(3);
        break;
    }
    
    $_SESSION['shop']['order']['order_user'] = $_SESSION['authid'];
    $_SESSION['shop']['order']['order_price'] = $_SESSION['shop']['price'];

    // Order Type
    if( !isset($_SESSION['shop']['order']['order_type']) ){
        include('include/contents/shop/order_type.php');
        exit();
    }
    
    // Address
    if( !isset($_SESSION['shop']['order']['order_address']) ){
        include('include/contents/shop/order_address.php');
        exit();
    }
    
    // Payment
    if( !isset($_SESSION['shop']['order']['order_payment']) ){
        include('include/contents/shop/payment_type.php');
        exit();
    }
    
    // Confirm
    if( !isset($_SESSION['shop']['order']['order_confirm']) ){
        include('include/contents/shop/order_confirm.php');
        exit();
    }
    
} else {
   /* Login oder Registrieren */
}
?>
