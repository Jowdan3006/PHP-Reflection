<?php 
require_once __DIR__ .'/../src/config.php'; 

$pageTitle = "MYPHPokémon - Favourites";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once LIB_PATH . "/PokeAPI.php";

session_start();
$db = new MyPokémonUserDatabase;
$db->create();
unset($_SESSION['pokemonList']);
unset($_SESSION['pokemon']);

if (isset($_SESSION['pokemonList'])) {
    $pokemonList == $_SESSION['pokemonList'];
} else {
    $pokemonList = new PokeAPI(null, 'list');
}

// var_dump($pokemonList);

if (isset($userData)) {
    $pokemonIdArray = $db->getFavoritePokemon($currentUserId);
}

foreach ($pokemonIdArray as $pokemon) {
    $pokemonArray[] = $pokemonList->getPokemon()[$pokemon];
}

// var_dump($pokemonArray);

$message = '';
$limit = 3;
if (isset($_GET['o'])) {
    $offset = $_GET['o'];
} else {
    $offset = 0;
}

$offsetLimit = $offset + $limit;

if (isset($_SESSION['pokemon']) && $_SESSION['pokemon']->getType() == 'set')  {
    $pokemon = $_SESSION['pokemon'];
} else {
    $pokemon = new PokeAPI($pokemonArray, 'set');
}

if (!isset($pokemon) || !isset($pokemonList)) {
    $message = 'Please search for a Pokémon type.';
} else {
    $_SESSION['pokemon'] = $pokemon;
}
// var_dump($favoritePokemon);
?>

    <body>

        <?php
        require_once INC_PATH . "/header.php";
        ?>
        <div class="container">
            <?php 
            if (empty($message) && isset($_SESSION['pokemon'])) {
                if (is_array($pokemon->getPokemon())) {
                    $pokeCount = count($pokemon->getPokemon());
                    if ($pokeCount > $limit) {
                        $pages = ceil($pokeCount / $limit);
                        echo '<div style="display: flex; flex-wrap: wrap;" class="pagination '.$pokemon->getType().'" >';
                        for ($i = 0; $i < $pages; $i++) {
                            $url = "favorites.php?o=$i";
                            echo '<a href="'.$url.'" class="btn btn-primary btn-sm" role="button">'.$i.'</a>';
                        }
                        echo '</div>';
                    }
                    echo '<div class="card-deck" id="pokeCards">';
                    $count = 0;
                    $id = ($offset * $limit) + $count;
                    while ($count < $limit && $id <= ($pokeCount - 1)) {
                        $id = ($offset * $limit) + $count;
                        include INC_PATH . "/pokeCards.php";
                        $count++;
                    }
                    echo "</div>";
                } else {
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