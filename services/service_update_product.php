<?php 
if( isset( $_GET["id"] ) 
    && isset( $_POST["label"] ) 
    && isset( $_POST["price"] )
){
    
    $id = $_GET["id"];
    $label = $_POST["label"];
    $price = $_POST["price"];

    $image = $_FILES["image"];
    $error = false;
    $update_image = false;

    //Check label
    if( strlen( $label ) < 1 || strlen( $label ) > 255 ){
        $message = "Nombre de caractères incorrect pour le label !";
        $error = true;
    }

    //Check price
    if( !filter_var( $price, FILTER_VALIDATE_FLOAT ) ){
        $message = "Le prix doit être un nombre.";
        $error = true;
    }

    if( strlen( $image["name"] ) > 0 ) {

        if( 
            $image["type"] != "image/jpeg"
            && $image["type"] != "image/jpg"
            && $image["type"] != "image/png"
        ) {
            $message = "Le type de fichier est mauvais. Veuillez charger une image de type jpg, jpeg ou png.";
            $error = true;
        }
        else if( $image["size"] > IMAGE_MAX_SIZE ) {
            $message = "Le fichier est trop lourd. Il ne doit pas dépasser " . ( IMAGE_MAX_SIZE / 1024 / 1024 ) . " Mo.";
            $error = true;
        }
        else {
            $update_image = true;
        }

    }

    //Gestion si erreur ou pas
    if( $error ){
        header("Location: ?page=update_product&id=".$id."&message=".$message);
        die();
    }
    else {

        if( $update_image ){

            $name = pathinfo( $image["name"], PATHINFO_FILENAME );
            $ext = pathinfo($image["name"], PATHINFO_EXTENSION);

            $image_name = uniqid( $name . "_" ) . "." . $ext;

            if( update_product( $id, $label, $price, $image_name ) ) {
                move_uploaded_file( $image["tmp_name"], "products_image/" . $image_name );
                $message = "Le produit a été mis à jour";
            }  
            else {
                $message = "Erreur lors de l'insertion";
            }

        }
        else {

            if( update_product( $id, $label, $price ) ) {
                $message = "Le produit a été mis à jour";
            }
            else {
                $message = "Erreur lors de l'insertion";
            }

        }

        header("Location: ?page=admin&message=".$message);
        die();

    }
    
}
else {
    header("Location: ?page=admin");
    die();
}