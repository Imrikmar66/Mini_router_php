<?php
session_start();

define("SALT", "QWONQULqF0");

define("DB_HOST", "localhost");
define("DB_NAME", "shop");
define("DB_USER", "root");
define("DB_PASS", "root");

define("PRODUCTS_BY_PAGE", 2);

// Grants
define("CAN_CREATE_PRODUCT", 1);
define("CAN_UPDATE_PRODUCT", 2);
define("CAN_DELETE_PRODUCT", 3);

// Roles
define("SUPER_ADMIN", 1);
define("ADMIN", 2);
define("USER", 3);

//Files
define("IMAGE_MAX_SIZE", 2097152);

function isLogged( $as_role = USER ){

    return ( 
        isset( $_SESSION["user"] ) 
        && $_SESSION["user"]["id_role"] <= $as_role 
    );

}

function connectionRequired( $as_role = USER ){
    
    if( !isset( $_SESSION["user"] ) ){

        header("Location: ?page=login");
        die();
    }
    else if( !isLogged( $as_role ) ){

        $error = "Vous n'avez les autorisations nécessaires !";
        header("Location: ?page=login&error=".$error);
        die();

    }

}

// $_SESSION["user"] = id / username / password / id_role
function isGranted( $id_role, $id_grant ){

    $connection = getConnection();
    $sql = "SELECT COUNT(*)
    FROM link_role_grant
    WHERE link_role_grant.id_role = ? 
    AND link_role_grant.id_grant = ?";

    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "ii", $id_role, $id_grant );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );

    return (boolean)$result; //cast converting
    
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

/**** USERS *****/

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
    mysqli_stmt_bind_result($statement, $b_id, $b_username, $b_password, $b_id_role);

    // On prend le premier enregistrement ( les variables associées précédemment vont être mises à jour )
    mysqli_stmt_fetch($statement);

    $user = null;
    if( $b_id ){
        $user = [
            "id" => $b_id,
            "username" => $b_username,
            "password" => $b_password,
            "id_role" => $b_id_role
        ];
    }

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $user;

}

/***** PRODUCTS ******/

function getProducts( $index_page = 0 ){

    $connection = getConnection();
    $sql = "SELECT * FROM products LIMIT ?, ?";

    $start_index = $index_page * PRODUCTS_BY_PAGE;
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

function getProductById( $id ){

    $connection = getConnection();
    $sql = "SELECT * FROM products WHERE id=?";

    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "i", $id );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $id, $label, $price, $image_url );
    mysqli_stmt_fetch( $statement );

    $product = [
        "id" => $id,
        "label" => $label,
        "price" => $price,
        "image_url" => $image_url
    ];

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $product;

}

function update_product( $id, $label, $price, $image_url = false ){

    $connection = getConnection();
    $statement;
    
    if( $image_url != false ){

        $sql = "UPDATE products SET label=?, price=?, image_url=? WHERE id=?";
        $statement = mysqli_prepare( $connection, $sql );
        mysqli_stmt_bind_param( $statement, "sdsi", $label, $price, $image_url, $id );

    }
    else {

        $sql = "UPDATE products SET label=?, price=? WHERE id=?";
        $statement = mysqli_prepare( $connection, $sql );
        mysqli_stmt_bind_param( $statement, "sdi", $label, $price, $id );

    }

    mysqli_stmt_execute( $statement );

    // -1 erreur | 0 aucun changement | > 0 nombre de lignes affectées
    $edited = mysqli_stmt_affected_rows( $statement );
    
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $edited;

}

function deleteProductById( $id ){

    $connection = getConnection();
    $sql = "DELETE FROM products WHERE id=?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "i", $id );
    mysqli_stmt_execute( $statement );
    
    $deleted = mysqli_stmt_affected_rows( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($deleted > 0);

}

function countProducts(){

    $connection = getConnection();
    $sql = "SELECT COUNT(*) as number FROM products";
    $results = mysqli_query( $connection, $sql );
    $result = mysqli_fetch_assoc( $results );
    mysqli_close( $connection );

    return $result["number"];

}

/* 
    $product = [
        "label",
        "price",
        "image_url"
    ]
 */
function createProduct( $product ){

    $connection = getConnection();
    $sql = "INSERT INTO products VALUES (null, ?, ?, ?)";

    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( 
        $statement, 
        "sds", 
        $product["label"],
        $product["price"],
        $product["image_url"] 
    );
    mysqli_stmt_execute( $statement );
    $inserted = mysqli_stmt_affected_rows( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($inserted > 0);

}

/****** CART ******/

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

function getCart( $id_user ){

    $connection = getConnection();
    $sql = "SELECT products.*, carts.quantity
        FROM carts
        JOIN products
        ON carts.id_product = products.id
        WHERE carts.id_user=?";
    
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, 'i', $id_user );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $id, $label, $price, $image_url, $quantity );

    $cart = [];
    while( mysqli_stmt_fetch( $statement ) ){

        $cart[] = [
            "id" => $id,
            "label" => $label,
            "price" => $price,
            "image_url" => $image_url,
            "quantity" => $quantity
        ];

    }

    return $cart;

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