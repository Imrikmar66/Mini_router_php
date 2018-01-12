<?php
session_start();

function connectionRequired(){
    
    if( !isset( $_SESSION["user"] ) ){
        
        header("Location: ?page=login");
        die();
    }

}