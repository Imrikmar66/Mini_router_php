<?php 
    $id_role = $_SESSION["user"]["id_role"];
?>
<h1> Adminstration </h1>

<?php if( isGranted( $id_role, CAN_CREATE_PRODUCT ) ){ ?> 

    <h2> Ajouter un produit : </h2>
    <!-- utiliser enctype="multipart/form-data" 
    à chaque fois que je gère des fichiers (input:file) -->
    <form action="?service=create_product" method="POST" enctype="multipart/form-data" >

        <label>
            <span> Label </span>
            <input type="text" name="label">
        </label>

        <label>
            <span> Prix </span>
            <input type="number" name="price">
        </label>

        <label>
            <span> Image </span>
            <input type="file" name="image" >
        </label>

        <input type="submit" value="Créer">

    </form>

<?php } ?>