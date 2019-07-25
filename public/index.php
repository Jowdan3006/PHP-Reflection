<?php 
require_once __DIR__ .'/../src/config.php';

$pageTitle = "MYPHPokémon";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once LIB_PATH . "/PokeAPI.php";


$pokemon = new PokeAPI;
session_start();
if (isset($_SESSION['pokemon'])) {
    unset($_SESSION['pokemon']);
}
$_SESSION['pokemon'] = $pokemon;
?>

    <body>

        <?php
        require_once INC_PATH . "/header.php";
        ?>

        <div class="container">
            <?php include INC_PATH . "/pokeCard.php"; ?>
        </div>

        <?php
        require_once INC_PATH . "/footer.php";
        ?>
    </body>
</html>