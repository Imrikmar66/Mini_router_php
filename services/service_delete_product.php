<?php 
if( isset($_GET["id"]) ){

    $id = $_GET["id"];

    if( deleteProductById( $id ) ){
        $message = "Suppression réussie !";
    }
    else {
        $message = "Erreur lors de la supression";
    }

    header("Location: ?page=admin&message=".$message);
    die();

}
else {
    header("Location: ?page=admin");
    die();
}