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

<?php if( isGranted( $id_role, CAN_UPDATE_PRODUCT ) ){ ?>
    <!-- 
       ****** GESTION UPDATE / DELETE DES PRODUITS ******
    -->

<?php } ?>