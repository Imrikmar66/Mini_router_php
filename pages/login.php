<h1> Login </h1>

<?php

    if( isset($_GET["error"]) ){
        echo "<div class='error'>" . $_GET["error"] . "</div>";
    }
    
?>

<form action="?service=login" method="POST">

    <label>Username :</label>
    <input type="text" name="username"> <br>

    <label>Password :</label>
    <input type="password" name="password"> <br>

    <input type="submit" value="Connexion">

</form>
