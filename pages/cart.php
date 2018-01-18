<h1> Panier: </h1>
<?php 

    $id_user = $_SESSION["user"]["id"];
    $cart = getCart( $id_user );
    $total = 0;

    foreach( $cart as $product ) {

        $price = $product["price"] * $product["quantity"];
        $total += $price;

        $html_product = '<div style="border: 1px solid black; margin: 5px;">';
            $html_product .= '<h4>' . $product["label"] . '</h4>';
            $html_product .= '<img src="products_image/' . $product["image_url"] . '" width="200px" />';
            $html_product .= '<p>' . $price . ' €</p>';
            $html_product .= '<p> Quantity: ' . $product["quantity"] . '</p>';            
        $html_product .= '</div>';  

        echo $html_product;
    }

    echo "<p> Total: " . $total . " €</p>";

?>