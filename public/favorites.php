<?php 
require_once __DIR__ .'/../src/config.php'; 

$pageTitle = "MYPHPokémon - Favourites";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once LIB_PATH . "/PokeAPI.php";
?>

    <body>

        <?php
        require_once INC_PATH . "/header.php";
        ?>

        <?php
        require_once INC_PATH . "/footer.php";
        ?>
    </body>
</html>