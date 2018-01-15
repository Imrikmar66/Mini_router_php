<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>
    <body>

        <?php if( isLogged() ) { ?>
            <nav>
                <ul>
                    <li>
                        <a href="?page=login"> Changer d'utilisateur </a>
                    </li>
                    <li>
                        <a href="?page=home"> Home </a>
                    </li>
                    <li>
                        <a href="?page=shop"> Shop </a>
                    </li>
                    <li>
                        <a href="?page=cart"> Cart </a>
                    </li>
                </ul>
            </nav>
        <?php } ?>