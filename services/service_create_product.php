<?php 

if( 
    isset( $_POST["label"] )
    && isset( $_POST["price"] )
    && isset ( $_FILES["image"] )
 ){

    $image = $_FILES["image"];

    if( 
        $image["type"] != "image/jpeg"
        && $image["type"] != "image/jpg"
        && $image["type"] != "image/png"
    ) {
        $message = "Le type de fichier est mauvais. Veuillez charger une image de type jpg, jpeg ou png.";
    }
    else if( $image["size"] > IMAGE_MAX_SIZE ) {
        $message = "Le fichier est trop lourd. Il ne doit pas dépasser " . ( IMAGE_MAX_SIZE / 1024 / 1024 ) . " Mo.";
    }
    else {

        $name = pathinfo( $image["name"], PATHINFO_FILENAME );
        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);

        $image_name = uniqid( $name . "_" ) . "." . $ext;
        move_uploaded_file( $image["tmp_name"], "products_image/" . $image_name );

        $product = [
            "label" => $_POST["label"],
            "price" => $_POST["price"],
            "image_url" => $image_name
        ];

        if( createProduct( $product ) ){
            $message = "Le produit a bien été ajouté !";
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