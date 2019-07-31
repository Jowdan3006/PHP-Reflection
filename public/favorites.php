<?php 
require_once __DIR__ .'/../src/config.php'; 

$pageTitle = "MYPHPokémon - Favourites";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once LIB_PATH . "/PokeAPI.php";

session_start();
$db = new MyPokémonUserDatabase;
$db->create();

if (isset($_SESSION['pokemonList'])) {
    $pokemonList == $_SESSION['pokemonList'];
} else {
    $pokemonList = new PokeAPI(null, 'list');
}

if (isset($userData)) {
    $pokemonIdArray = $db->getFavoritePokemon($currentUserId);
    if (!isset($_SESSION['pokemonIdArray'])) {
        $_SESSION['pokemonIdArray'] = $pokemonIdArray;
    }
}

$message = '';
$limit = 3;
if (isset($_GET['o'])) {
    $offset = $_GET['o'];
} else {
    $offset = 0;
}

if (!empty($pokemonIdArray)) {
    foreach ($pokemonIdArray as $pokemon) {
        $pokemonArray[] = $pokemonList->getPokemon()[$pokemon];
    }
    if (isset($_SESSION['pokemon']) && $_SESSION['pokemon']->getType() == 'set' && $_SESSION['pokemonIdArray'] == $pokemonIdArray) {
        $pokemon = $_SESSION['pokemon'];
    } else {
        $pokemon = new PokeAPI($pokemonArray, 'set');
        $_SESSION['pokemon'] = $pokemon;
        $_SESSION['pokemonIdArray'] = $pokemonIdArray;
    }
} else {
    $message = 'You have no favorite Pokemon.';
}

?>

    <body>

        <?php
        $activePage = 'favorites';
        require_once INC_PATH . "/header.php";
        ?>
        <div class="container">
            <?php 
            if (empty($message) && isset($_SESSION['pokemon'])) {
                    $pokeCount = count($pokemon->getPokemon());
                    if ($pokeCount > $limit) {
                        $pages = ceil($pokeCount / $limit);
                        echo '<div style="display: flex; flex-wrap: wrap;" class="pagination '.$pokemon->getType().'" >';
                        for ($i = 0; $i < $pages; $i++) {
                            $url = "favorites.php?o=$i";
                            echo '<a href="'.$url.'" class="btn btn-primary btn-sm" role="button">'.($i + 1).'</a>';
                        }
                        echo '</div>';
                    }
                    echo '<div class="card-deck" id="pokeCards">';
                    $count = 0;
                    $id = ($offset * $limit) + $count;
                    while ($count < $limit && $id < $pokeCount) {
                        include INC_PATH . "/pokeCards.php";
                        $count++;
                        $id = ($offset * $limit) + $count;
                    }
                    echo "</div>";
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