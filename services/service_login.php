<?php
define("SALT", "QWONQULqF0");

$users = [
    [
        "name" => "Pierre",
        "password" => "e4953807b90944c5eb46ddcf68470b3ee7e76502" // sha1(starwarsQWONQULqF0)
    ],
    [
        "name" => "Paul",
        "password" => "d7c42d00268ebbcec2226ab0d8a8b42c13b68af9" // sha1(footballQWONQULqF0)
    ]
];

debug($users);

$connected = false;
if( isset( $_POST["username"] ) && isset( $_POST["password"] ) ){

    $username = $_POST["username"];
    $password = sha1( $_POST["password"] . SALT );

    foreach( $users as $user ){

        if( $user["name"] == $username && $user["password"] == $password ){
            $_SESSION["user"] = $user;
            $connected = true;
            break;
        }

    }

}

if( $connected ){
    header("Location: ?page=home");
}
else {
    // unset pour détruire une variable
    unset($_SESSION["user"]);
    
    $error = urlencode("Identifiant ou mot de passe incorrect");
    header("Location: ?page=login&error=".$error);
}