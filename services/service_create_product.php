<?php 

if( 
    isset( $_POST["label"] )
    && isset( $_POST["price"] )
    && isset ( $_FILES["image"] )
 ){

    $label = $_POST["label"];
    $price = $_POST["price"];
    $image = $_FILES["image"];

    $_SESSION["fields"] = $_POST;
    
    //Check label
    if( strlen( $label ) < 1 || strlen( $label ) > 255 ){
        $message = "Nombre de caractères incorrect pour le label !";
        $_SESSION["fields"]["label"] = "";
    }

    //Check price
    else if( !filter_var( $price, FILTER_VALIDATE_FLOAT ) ){
        $message = "Le prix doit être un nombre.";
        $_SESSION["fields"]["price"] = "";
    }

    //Check image 
    else if( 
        $image["type"] != "image/jpeg"
        && $image["type"] != "image/jpg"
        && $image["type"] != "image/png"
    ) {
        $message = "Le type de fichier est mauvais. Veuillez charger une image de type jpg, jpeg ou png.";
    }
    else if( $image["size"] > IMAGE_MAX_SIZE ) {
        $message = "Le fichier est trop lourd. Il ne doit pas dépasser " . ( IMAGE_MAX_SIZE / 1024 / 1024 ) . " Mo.";
    }

    // All ok
    else {

        $name = pathinfo( $image["name"], PATHINFO_FILENAME );
        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);

        $image_name = uniqid( $name . "_" ) . "." . $ext;
        move_uploaded_file( $image["tmp_name"], "products_image/" . $image_name );

        $product = [
            "label" => $label,
            "price" => $price,
            "image_url" => $image_name
        ];

        if( createProduct( $product ) ){
            $message = "Le produit a bien été ajouté !";
            unset( $_SESSION["fields"] );
        }
        else {
            $message = "Une erreur est survenue lors de l'insertion.";
        }

    }

}
else {
    $message = "Il manque des données !";
}

header("Location: ?page=admin&message=". $message);