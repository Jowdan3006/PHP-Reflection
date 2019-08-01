<?php 
require_once __DIR__ .'/../src/config.php'; 

$pageTitle = "MYPHPokémon - Pokédex";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once INC_PATH . "/userData.php";
require_once LIB_PATH . "/PokeAPI.php";
require_once INC_PATH . "/PokemonVariables.php";

$pokeTypes = ['normal', 'fighting', 'flying', 'poison', 
            'ground', 'rock', 'bug', 'ghost', 'steel', 
            'fire', 'water', 'grass', 'electric', 'psychic', 
            'ice', 'dragon', 'dark', 'fairy'];
$message = '';
$filter = '';
$limit = 3;
if (isset($_GET['o'])) {
    $offset = $_GET['o'] - 1;
} else {
    $offset = 0;
}

if (isset($_GET['s']) && (isset($_GET['filter']))) {
    $search = strtolower(filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING));
    $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING);

    if (isset($_SESSION['pokemon']) && ($filter == 'ran' || $filter == 'type') && !empty($search) && 
        $_SESSION['pokemon']->getType() == $search && isset($_SESSION['filter']) && $_SESSION['filter'] == $filter && $search != 'null') {
        $pokemon = $_SESSION['pokemon'];
        if ($filter == 'ran') {
            $pokemon->randomPokemon();
        }
    }
    if (!($filter == 'type' && empty($search)) || $search == 'null') {
        $pokemon = new PokeAPI($search, $filter);

        $_SESSION['filter'] = $filter;
        $_SESSION['pokemon'] = $pokemon;
    }

        if (!isset($pokemon) || ($search == 'null' && $filter != 'ran')) {
            $message = 'Please search for a Pokémon type.';
        } else if (!$pokemon->getPokemon() && $filter == 'type') {
            $message = 'No Pokémon found with type of "'.$search.'".';
        } else if (!$pokemon->getPokemon()) {
            $message = 'No Pokémon found with name of "'.$search.'".';
        }
} else {
    unset($_SESSION['pokemon']);
}
?>

    <body>
        <?php
        $activePage = 'pokedex';
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
                <span id="pokedex-search-box">
                    <div class="dropdown-menu"></div>
                    <?php if ($filter == 'nid' || $filter == '') { ?>
                    <input class="form-control mr-sm-2" autocomplete="off" id="form-nameOrID" style="min-width: 250px" type="search" placeholder="Enter a Pokémon <?php echo $filter == 'type' ? 'type' : 'name or ID' ?> " aria-label="Search" name="s"
                        <?php echo (!empty($search)) ? 'value="'.$search.'"' : '' ?>
                    >
                    <?php } else if ($filter == 'type') { ?>
                        <div class="input-group type-select mr-sm-2">
                            <select class="custom-select" id="select-type" name="s">
                                <?php if (empty($search)) {
                                    echo '<option value="null" selected>Choose Type</option>';
                                } else {
                                    echo '<option value="null">Choose Type</option>';
                                }
                                foreach ($pokeTypes as $type) {
                                    if ($type == $search) {
                                        echo '<option value="'.$type.'" selected>'.ucfirst($type).'</option>';
                                    } else {
                                        echo '<option value="'.$type.'">'.ucfirst($type).'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php } else if ($filter == 'ran') { ?>
                        <div class="input-group random-select mr-sm-2">
                            <select class="custom-select" id="select-random" name="s">
                            <?php if (empty($search)) {
                                    echo '<option value="null" selected>Random</option>';
                                } else {
                                    echo '<option value="null">Random</option>';
                                }
                                foreach ($pokeTypes as $type) {
                                    if ($type == $search) {
                                        echo '<option value="'.$type.'" selected>'.ucfirst($type).'</option>';
                                    } else {
                                        echo '<option value="'.$type.'">'.ucfirst($type).'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php } ?>
                </span>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </div>
            </form>

        <?php 
        
        if (empty($message) && isset($_SESSION['pokemon'])) {
            if (is_array($pokemon->getPokemon())) {
                $pokeCount = count($pokemon->getPokemon());
                $pages = ceil($pokeCount / $limit);
                echo '<div class="pagination '.$pokemon->getType().'" >';
                for ($i = 1; $i <= $pages; $i++) {
                    $url = "pokedex.php?filter=$filter&s=$search&o=$i";
                    if ($i == ($offset + 1)) {
                        echo '<a href="'.$url.'" class="btn btn-sm disabled" role="button">'.$i.'</a>';
                    } else {
                        echo '<a href="'.$url.'" class="btn btn-sm" role="button">'.$i.'</a>';
                    }
                }
                echo '</div>';
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
        echo "<script src='". JS_PATH . "/pokedex.js"."'></script>";
        ?>
    </body>
</html>