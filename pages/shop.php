<?php 

    $index_page = 0;
    if( isset( $_GET["index_page"] ) ){
        $index_page = $_GET["index_page"];
    }

?>
<?php 
    $products = getProducts( $index_page );

    if( count($products) ){

        $html_product = '<form action="?service=cart" method="POST">';

            // Génération des articles
            foreach( $products as $key => $product ) {

                $html_product .= '<div style="border: 1px solid black; margin: 5px;">';
                    $html_product .= '<input type="checkbox" name="' . $product["label"] . '" value="' . $key . '">';
                    $html_product .= '<h4>' . $product["label"] . '</h4>';
                    $html_product .= '<img src="assets/' . $product["image_url"] . '" width="200px" />';
                    $html_product .= '<p>' . $product["price"] . ' €</p>';
                $html_product .= '</div>';  

            }

            $html_product .= '<input type="submit" value="Ajouter au panier">';
        $html_product .= '</form>';

        // Génération de la liste des pages
        $html_product .= '<ul>';

        $nb_pages = intval( count($products) / PRODUCTS_BY_PAGE );

        for( $i=0; $i <= $nb_pages; $i++ ){

            $html_product .= '<li>';
                $html_product .= '<a href="?page=shop&index_page=' . $i .'" >' ;
                    $html_product .= ($i + 1);
                $html_product .= '</a>';
            $html_product .= '</li>';

        }

        $html_product .= '</ul>';

        echo $html_product;
    }
    else {
        echo "<div> Aucun article trouvé ! </div>";
    }
?>
