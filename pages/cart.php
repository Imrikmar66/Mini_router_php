<h1> Panier: </h1>
<?php 
    if( isset( $_SESSION["cart"] ) ){

        foreach( $_SESSION["cart"] as $product ) {

            $html_product = '<div style="border: 1px solid black; margin: 5px;">';
                $html_product .= '<h4>' . $product["label"] . '</h4>';
                $html_product .= '<img src="assets/imgs/' . $product["image_url"] . '" width="200px" />';
                $html_product .= '<p>' . $product["price"] . ' €</p>';
                $html_product .= '<p> Quantity: ' . $product["quantity"] . '</p>';            
            $html_product .= '</div>';  

            echo $html_product;
        }

    }
?>