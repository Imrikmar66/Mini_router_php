<html>
    <head></head>
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
                </ul>
            </nav>
        <?php } ?>