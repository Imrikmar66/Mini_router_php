<?php
session_start();

define("DB_HOST", "localhost");
define("DB_NAME", "shop");
define("DB_USER", "root");
define("DB_PASS", "root");

define("PRODUCTS_BY_PAGE", 2);

function isLogged(){

    return isset( $_SESSION["user"] );

}

function connectionRequired(){
    
    if( !isset( $_SESSION["user"] ) ){
        
        header("Location: ?page=login");
        die();
    }

}

function getConnection(){

    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if( $errors = mysqli_connect_error($connection) ){
        $errors = utf8_encode($errors);
        header("Location: ?page=login&error=" . $errors); 
        die();
    }

    return $connection;

}

function getUsers(){

    // starwars
    // football

    $connection = getConnection();
    $sql = "SELECT username, password FROM users";
    
    $results = mysqli_query($connection, $sql);

    mysqli_close( $connection );
    
    $users = [];
    
    while( $row = mysqli_fetch_assoc($results) ){

        $users[] = $row;

    }
    
    return $users;

}

function getUser( $username, $password ){

    // $connection = getConnection();

    // $sql = "SELECT * FROM users WHERE username='" . $username . "' AND password='". $password."'";

    // $results = mysqli_query( $connection, $sql );
    // $user = mysqli_fetch_assoc( $results );
    
    // return $user; //null | id,name,password

    $connection = getConnection();

    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    // Prépare une requête avec des ? en inconnues
    $statement = mysqli_prepare( $connection, $sql );

    // Remplace les ? par les variables (+ sécurité)
    mysqli_stmt_bind_param( $statement, "ss", $username, $password );

    // Exécution de la requête
    mysqli_stmt_execute( $statement );

    // On associe des variables aux colonnes récupérées
    mysqli_stmt_bind_result($statement, $b_id, $b_username, $b_password);

    // On prend le premier enregistrement ( les variables associées précédemment vont être mises à jour )
    mysqli_stmt_fetch($statement);

    $user = null;
    if( $b_id ){
        $user = [
            "id" => $b_id,
            "username" => $b_username,
            "password" => $b_password
        ];
    }

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $user;

}

function getProducts( $page_index = 0 ){

    $connection = getConnection();
    $sql = "SELECT * FROM products LIMIT ?, ?";

    $start_index = $page_index * PRODUCTS_BY_PAGE;
    $end_index = PRODUCTS_BY_PAGE;

    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "ii", $start_index, $end_index);
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $b_id, $b_label, $b_price, $b_image_url );

    $products = [];
    while( mysqli_stmt_fetch( $statement ) ) {
        
        $products[] = [
            "id" => $b_id,
            "label" => utf8_encode( $b_label ),
            "price" => $b_price,
            "image_url" => $b_image_url
        ];

    }
    
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $products;
}

function countProducts(){

    $connection = getConnection();
    $sql = "SELECT COUNT(*) as number FROM products";
    $results = mysqli_query( $connection, $sql );
    $result = mysqli_fetch_assoc( $results );
    mysqli_close( $connection );

    return $result["number"];

}

function addToCart( $id_user, $id_product ){

    // Vérifier si la liaison existe
    // Insert / Update en fonction de la présence

    $connection = getConnection();
    $sql = "SELECT COUNT(*) as number FROM carts WHERE id_user=? AND id_product=?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "ii", $id_user, $id_product);
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $b_number );
    mysqli_stmt_fetch($statement);
    
    // $b_number 0 ou 1
    if( $b_number ){

        $sql = "UPDATE carts SET quantity=quantity+1 WHERE id_user=? AND id_product=?";

    }
    else {
        
        // Par default, le champs quantity est configuré sur 1
        $sql = "INSERT INTO carts (id_user, id_product) VALUES (?, ?)";

    }

    mysqli_stmt_close( $statement );

    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "ii", $id_user, $id_product);
    mysqli_stmt_execute( $statement );
    $error = mysqli_error( $connection );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    if( $error ){
        return false;
    }
    else {
        return true;
    }

}

function debug( $arg, $printr = false ){
    
    if( $printr ){
        echo "<pre>";
        print_r($arg);
        echo "</pre>";
    }
    else {
        var_dump( $arg );
    }
    
    die();

}

// /* COOKIE */

// $cart = [
//     [
//         "name" => "rocket",
//         "price" => 15,
//         "image" => "rocket.jpg"
//     ],
//     [
//         "name" => "groot",
//         "price" => 18,
//         "image" => "groot.jpg"
//     ]
// ];

// //JSON.stringify -> json_encode
// $str_cart = json_encode($cart);
// echo $str_cart;
// setcookie("TEST", $str_cart, time() + 3600*24);

// //JSON.parse -> json_decode
// $decode_cart = json_decode( $_COOKIE["TEST"] );
// var_dump( $decode_cart );