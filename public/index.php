<?php 
require_once __DIR__ .'/../src/config.php';

$pageTitle = "MYPHPokémon";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once INC_PATH . "/userData.php";
require_once LIB_PATH . "/PokeAPI.php";
require_once INC_PATH . "/pokemonVariables.php";

$pokemonTopArray = $db->getTopPokemon();
if (!isset($_SESSION['pokemonTopArray'])) {
    $_SESSION['pokemonTopArray'] = $pokemonTopArray;
}

if (!empty($pokemonTopArray)) {
    foreach ($pokemonTopArray as $pokemon) {
        $pokemonArray[] = $pokemonList->getPokemon()[$pokemon];
    }
    if (isset($_SESSION['pokemon']) && $_SESSION['pokemon']->getType() == 'set' && $_SESSION['pokemonTopArray'] == $pokemonTopArray) {
        $pokemon = $_SESSION['pokemon'];
    } else {
        $pokemon = new PokeAPI($pokemonArray, 'set');
        $_SESSION['pokemon'] = $pokemon;
        $_SESSION['pokemonTopArray'] = $pokemonTopArray;
    }
} else {
    $message = 'There are no Rankings yet!';
}
?>

    <body>

        <?php
        $activePage = 'home';
        require_once INC_PATH . "/header.php";
        ?>

        <div class="container">
            <?php
            if (isset($_GET['r'])) {
                switch ($_GET['r']) {
                    case 'sent': ?>
                        <div class="container" id="headerAlert">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">Email has been sent.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <?php break;
                }
            }
            if (empty($message) && isset($_SESSION['pokemon'])) {
                    echo '<div class="card-deck" id="pokeCards">';
                    $count = 0;
                    $limit= 3;
                    $pokeCount = count($pokemonTopArray);
                    while ($count < $limit && $count < $pokeCount) {
                        $id = $count;
                        include INC_PATH . "/pokeCards.php";
                        $count++;
                    }
                    echo "</div>";
            } else {
                echo '<p class="error-message">';
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