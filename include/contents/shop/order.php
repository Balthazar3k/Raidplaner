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
            $_SESSION['shop']['order']['order_type'] = $menu->get(3);
        break;
        case 'address':
            $_SESSION['shop']['order']['order_address'] = $menu->get(3);
        break;
        case 'payment':
            $_SESSION['shop']['order']['order_payment'] = $menu->get(3);
        break;
        case 'reset':
            unset($_SESSION['shop']['order'][$menu->get(3)]);
        break;
        case 'clear':
            unset($_SESSION['shop']['order']);
        break;
        
        case 'success':
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
   /* Login oder Registrieren */
}
?>
