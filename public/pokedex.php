<?php 
require_once __DIR__ .'/../src/config.php'; 

$pageTitle = "MYPHPokémon - Pokédex";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once LIB_PATH . "/PokeAPI.php";
$message = '';
if (isset($_GET['s'])) {
    $pokemon = new PokeAPI($_GET['s']);
    if (!$pokemon->getPokemon()) {
        $message = 'No Pokémon found with name of "'.$_GET['s'].'"';
    }
}
?>

    <body>

        <?php
        require_once INC_PATH . "/header.php";
        ?>
        <div class="container">
            <form method="get" action="pokedex.php" class="form-inline justify-content-center" style="margin-bottom: 1rem;">
                <input class="form-control mr-sm-2" style="min-width: 250px" type="search" placeholder="Enter Pokémon name or ID" aria-label="Search" name="s">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>

        <?php 
        if (empty($message)) {
            if (isset($_GET['s'])) { 
                include INC_PATH . "/pokeCard.php";
            }
        } else {
            echo '<p style="text-align: center;">';
            echo $message;
            echo '</p>';
        }
        ?>

        </div>

        <?php
        require_once INC_PATH . "/footer.php";
        ?>
    </body>
</html>