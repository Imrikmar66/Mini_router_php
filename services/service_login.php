<?php
$connected = false;
if( isset( $_POST["username"] ) && isset( $_POST["password"] ) ){

    $username = $_POST["username"];
    $password = sha1( $_POST["password"] . SALT );

    $user = getUser( $username, $password );
    if( $user ){ // Teste si utilisateur trouvé avec la requete (sinon null)

        $_SESSION["user"] = $user;
        $connected = true;

    }

}

if( $connected ){
    header("Location: ?page=home");
}
else {
    // unset pour détruire une variable
    // unset($_SESSION["user"]);

    session_unset(); // Detruit toute les variables de SESSION
    
    $error = urlencode("Identifiant ou mot de passe incorrect");
    header("Location: ?page=login&error=".$error);
}