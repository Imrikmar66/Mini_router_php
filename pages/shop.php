<form action="?service=cart" method="POST">
<?php 
    foreach( getProducts() as $key => $product ) {

        $html_product = '<div style="border: 1px solid black; margin: 5px;">';
            $html_product .= '<input type="checkbox" name="' . $product["name"] . '" value="' . $key . '">';
            $html_product .= '<h4>' . $product["name"] . '</h4>';
            $html_product .= '<img src="assets/' . $product["image"] . '" width="200px" />';
            $html_product .= '<p>' . $product["price"] . ' €</p>';
        $html_product .= '</div>';  

        echo $html_product;
    }
?>

    <input type="submit" value="Ajouter au panier">

</form>
