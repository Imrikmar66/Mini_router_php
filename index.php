<?php 
    require "functions.php";

    if( isset( $_GET["service"] ) ){

        $service = $_GET["service"];

        switch( $service ){

            case "login": 
                include "services/service_login.php";
                break;
            default :
                header("Location: ?page=home");

        }

        die();

    }

    $page = "home";
    $page_file = "";

    if( isset( $_GET["page"] ) ){
        $page = $_GET["page"];
    }

    switch( $page ){

        case "home":
            connectionRequired();
            $page_file = "pages/home.php";
            break;
        case "login":
            $page_file = "pages/login.php";
            break;
        default:
            $page_file = "pages/404.php";

    }

    include "commons/header.php";
    include $page_file;
    include "commons/footer.php";

?>