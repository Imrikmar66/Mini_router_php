<?php 

if( isset( $_GET["id"] ) ){
    
    $id = $_GET["id"];
    $product = getProductById( $id );
    ?> 

    <h2> Edition du produit <?php echo $product["label"] ?> </h2>
    
    <form action="?service=update_product&id=<?php echo $id ?>" method="POST" enctype="multipart/form-data" >

            <label>
                <span> Label </span>
                <input type="text" name="label" value="<?php echo $product["label"] ?>" >
            </label>

            <label>
                <span> Prix </span>
                <input type="number" name="price" step="0.01" value="<?php echo $product["price"] ?>" >
            </label>

            <label>
                <span> Image </span>
                <input type="file" name="image" >
            </label>

            <input type="submit" value="Editer">

    </form>

    <?php 

    }
    else {
        header("Location: ?page=admin");
        die();
    }

?>