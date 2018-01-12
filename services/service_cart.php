<?php 

    if( !isset( $_SESSION["cart"] ) ){
        $_SESSION["cart"] = [];
    }

    foreach( $_POST as $item ){

        $product = getProducts()[$item];
        $product_index = -1;
        foreach( $_SESSION["cart"] as $key => $cart_item ){

            if( $cart_item["name"] == $product["name"] ){
                $product_index = $key;
                break;
            }

        }

        if( $product_index >= 0 ){
            $_SESSION["cart"][$product_index]["quantity"] ++;
        }
        else {

            $product["quantity"] = 1;
            $_SESSION["cart"][] = $product;

        }

    }

    header("Location: ?page=cart");