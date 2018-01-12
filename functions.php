<?php
session_start();

function isLogged(){

    return isset( $_SESSION["user"] );

}

function connectionRequired(){
    
    if( !isset( $_SESSION["user"] ) ){
        
        header("Location: ?page=login");
        die();
    }

}

function getUsers(){

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

    return $users;

}

function getProducts(){

    $products = [
        [
            "name" => "rocket",
            "price" => 15,
            "image" => "rocket.jpg"
        ],
        [
            "name" => "groot",
            "price" => 18,
            "image" => "groot.jpg"
        ],
        [
            "name" => "starlord",
            "price" => 12,
            "image" => "starlord.jpg"
        ]
    ];

    return $products;

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