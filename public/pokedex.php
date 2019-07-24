<?php 
require __DIR__ .'/../src/config.php'; 

$pageTitle = "MYPHPokémon - Pokédex";
require INC_PATH . "/head.php";
require LIB_PATH . "/MyPokémonUserDatabase.php";
require LIB_PATH . "/PokeAPI.php";

session_start();
if (isset($_SESSION['pokemonList'])) {
    $pokemonList == $_SESSION['pokemonList'];
} else {
    $pokemonList = new PokeAPI(null, null, true);
}

$message = '';
$filter = '';
$limit = 3;
if (isset($_GET['o'])) {
    $offset = $_GET['o'];
} else {
    $offset = 0;
}
$offsetLimit = $offset + $limit;

if (isset($_GET['s']) && (isset($_GET['filter']))) {
    $search = $_GET['s'];
    $filter = $_GET['filter'];

    if (isset($_SESSION['pokemon']) && ($filter == 'ran' || $filter == 'type') && !empty($search) && ($_SESSION['pokemon']->getType() == $search) && (isset($_SESSION['filter']) && $_SESSION['filter'] == $filter)) {
        $pokemon = $_SESSION['pokemon'];
        if ($filter == 'ran') {
            $pokemon->randomPokemon();
        }
    } else if (empty($filter) || $filter == 'ran' || empty($search)) {
        $pokemon = new PokeAPI($search, null, null, true);
    } else if ($filter == 'nid') {
        $pokemon = new PokeAPI($search);
    } else if ($filter == 'type') {
        $pokemon = new PokeAPI(null, $search);
    }

    if (!$pokemon->getPokemon() && $filter == 'type') {
        $message = 'No Pokémon found with type of "'.$search.'"';
    } else if (!$pokemon->getPokemon()) {
        $message = 'No Pokémon found with name of "'.$search.'"';
    }
    $_SESSION['filter'] = $filter;
    $_SESSION['pokemon'] = $pokemon;
} else {
    session_unset();
}
?>

    <body>
        <?php
        require_once INC_PATH . "/header.php";
        ?>
        <div class="container">
            <form method="get" action="pokedex.php" style="margin-bottom: 1rem;">
            <div class="form-inline justify-content-center" style="margin-bottom: 1rem;">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filter" id="nameOrID" value="nid" checked>
                    <label class="form-check-label" for="nameOrID">Name or ID</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filter" id="type" value="type" <?php echo ($filter == 'type') ? 'checked' : '' ?> >
                    <label class="form-check-label" for="type">Type</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filter" id="random" value="ran" <?php echo ($filter == 'ran') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="random">Random</label>
                </div>
            </div>
            <div class="form-inline justify-content-center">
                <input class="form-control mr-sm-2" style="min-width: 250px" type="search" placeholder="Enter Pokémon name or ID" aria-label="Search" name="s"
                    <?php echo (!empty($search)) ? 'value="'.$search.'"' : '' ?>
                >
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </div>
            </form>

        <?php 
        
        if (empty($message) && isset($_SESSION['pokemon'])) {
            if (is_array($pokemon->getPokemon())) {
                $pokeCount = count($pokemon->getPokemon());
                $pages = ceil($pokeCount / $limit);
                echo '<div style="display: flex; flex-wrap: wrap;" class="pagination '.$pokemon->getType().'" >';
                for ($i = 0; $i < $pages; $i++) {
                    $url = "pokedex.php?filter=$filter&s=$search&o=$i";
                    echo '<a href="'.$url.'" class="btn btn-primary btn-sm" role="button">'.$i.'</a>';
                }
                echo '</div>';
                echo '<div class="card-deck" id="pokeCards">';
                $count = 0;
                $id = ($offset * $limit) + $count;
                while ($count < $limit && $id < ($pokeCount - 1)) {
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