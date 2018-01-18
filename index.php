<?php 
    require "functions.php";

    /* Service */
    // mon/url.php?service=nom_du_service

    if( isset( $_GET["service"] ) ){

        $service = $_GET["service"];

        switch( $service ){

            case "login": 
                include "services/service_login.php";
                break;
            case "cart":
                include "services/service_cart.php";
                break;
            default :
                header("Location: ?page=login");

        }
        
        die();

    }

    /* Pages */

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
        case "shop":
            connectionRequired();
            $page_file = "pages/shop.php";
            break;
        case "cart":
            connectionRequired();
            $page_file = "pages/cart.php";
            break;
        case "admin":
            connectionRequired( ADMIN );
            $page_file = "pages/admin.php";
            break;
        default:
            $page_file = "pages/404.php";

    }

    include "commons/header.php";
    include $page_file;
    include "commons/footer.php";

?>