<?php 
    $id_role = $_SESSION["user"]["id_role"];

    if( isset( $_GET["message"] ) ){
        echo "<div class='msg'>" . $_GET["message"] . "</div>";
    }
    
    $label = "";
    $price = "";
    if( isset( $_SESSION["fields"] ) ){
        $label = $_SESSION["fields"]["label"];
        $price = $_SESSION["fields"]["price"];
    }
?>
<h1> Administration </h1> 

<?php if( isGranted( $id_role, CAN_CREATE_PRODUCT ) ){ ?> 

    <!-- 
       ****** GESTION CREATION DES PRODUITS ******
    -->

    <h2> Ajouter un produit : </h2>
    <!-- utiliser enctype="multipart/form-data" 
    à chaque fois que je gère des fichiers (input:file) -->
    <form action="?service=create_product" method="POST" enctype="multipart/form-data" >

        <label>
            <span> Label </span>
            <input type="text" name="label" value="<?php echo $label ?>" >
        </label>

        <label>
            <span> Prix </span>
            <input type="number" name="price" step="0.01" value="<?php echo $price ?>" >
        </label>

        <label>
            <span> Image </span>
            <input type="file" name="image" >
        </label>

        <input type="submit" value="Créer">

    </form>

<?php } ?>

<?php 
if( isGranted( $id_role, CAN_UPDATE_PRODUCT ) ){ 

    $index_page = 0;
    if( isset( $_GET["index_page"] ) ){
        $index_page = $_GET["index_page"];
    }

    /****** GESTION UPDATE ******/  
    
    $html_product = '';

    foreach ( getProducts( $index_page ) as $product ) {
        
        $html_product .= '<div style="border: 1px solid black; margin: 5px;">';

            $html_product .= '<h4>' . $product["label"] . '</h4>';
            $html_product .= '<img src="products_image/' . $product["image_url"] . '" width="200px" />';
            $html_product .= '<p>' . $product["price"] . ' €</p>';

            $html_product .= '<a href="?page=update_product&id='.$product["id"].'" > Editer </a>';
            
            if( isGranted( $id_role, CAN_DELETE_PRODUCT ) ) {
                $html_product .= '<a href="?service=delete_product&id='.$product["id"].'" > Supprimer </a>';
            }

        $html_product .= '</div>';


    }

    $nb_pages = ceil( countProducts() / PRODUCTS_BY_PAGE );
    $html_product .= '<ul>';
    
    for( $i=0; $i < $nb_pages; $i++ ){

        $html_product .= '<li>';
            $html_product .= '<a href="?page=admin&index_page=' . $i .'" >' ;
                $html_product .= ($i + 1);
            $html_product .= '</a>';
        $html_product .= '</li>';

    }

    $html_product .= '</ul>';

    echo $html_product;
    
} 
?>