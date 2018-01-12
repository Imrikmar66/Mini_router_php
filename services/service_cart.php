<?php 

    if( !isset( $_SESSION["cart"] ) ){
        $_SESSION["cart"] = [];
    }

    foreach( $_POST as $item ){

        $_SESSION["cart"][] = getProducts()[$item];

    }

    header("Location: ?page=cart");