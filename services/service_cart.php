<?php 

    // // Si le panier dans la session n'existe pas, je le défini en temps que tableau
    // if( !isset( $_SESSION["cart"] ) ){
    //     $_SESSION["cart"] = [];
    // }

    // // Pour chaque élément envoyé au serveur
    // foreach( $_POST as $item ){

    //     // On récupère l'élément correspondant dans notre "base de donnée"
    //     $product = getProducts()[$item];

    //     // On va chercher si l'élément existe déja dans mon panier en SESSION
    //     $product_index = -1;
    //     foreach( $_SESSION["cart"] as $key => $cart_item ){

    //         // Si l'élément existe déjà dans mon panier, je garde sa position dans le tableau en mémoire
    //         if( $cart_item["label"] == $product["label"] ){
    //             $product_index = $key;
    //             break;
    //         }

    //     }

    //     // Si la position de l'élément a été trouvé , on rajoute 1 à la quantité
    //     if( $product_index >= 0 ){
    //         $_SESSION["cart"][$product_index]["quantity"] ++;
    //     }
    //     // Sinon on créé l'élément dans le panier
    //     else {

    //         // Je créé la clé quantité ( initialisé à 1)
    //         $product["quantity"] = 1;

    //         // Je push dans le panier en SESSION
    //         $_SESSION["cart"][] = $product;

    //     }

    // }

    if( empty( $_POST ) ){
        header("Location: ?page=home");
        die();
    }
    else {

        $id_user = $_SESSION["user"]["id"];

        foreach( $_POST as $id_product ){

            addToCart( $id_user, $id_product );

        }

    }

    header("Location: ?page=cart");
    