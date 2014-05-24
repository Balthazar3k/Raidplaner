<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

if( loggedin() ){
    
    $_SESSION['shop']['order'] = array(
        'order_user' => $_SESSION['authid'],
        'order_price' => $_SESSION['shop']['price'] 
    );

    // Order Type
    if( !isset($_SESSION['shop']['order']['order_type']) ){
        include('include/contents/shop/ordertype.php');
        exit('Order Type');
    }
    
    // Address
    if( !isset($_SESSION['shop']['order']['order_address']) ){
        include('include/contents/shop/address.php');
        exit('Adresse auswählen');
    }
    
} else {
   /* Login  oder Registrieren */
}

?>
