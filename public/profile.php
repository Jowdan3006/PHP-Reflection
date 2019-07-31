<?php 
require_once __DIR__ .'/../src/config.php';

$pageTitle = "MYPHPokémon";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once LIB_PATH . "/PokeAPI.php";

$db = new MyPokémonUserDatabase;
$db->create();

session_start();
if (isset($_SESSION['pokemonList'])) {
    $pokemonList == $_SESSION['pokemonList'];
} else {
    $pokemonList = new PokeAPI(null, 'list');
}

?>

    <body>

        <?php
        $activePage = 'profile';
        require_once INC_PATH . "/header.php";
        ?>

        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <img style="float: right;" src="<?php echo get_gravatar($currentUserEmail, 320)?>">
                </div>
                <form class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                        <label class="form-check-label" for="exampleRadios1">Use Gravatar</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                        <label class="form-check-label" for="exampleRadios2">Use Pokémon</label>
                    </div>
                </form>
            </div>
        </div>

        <?php
        require_once INC_PATH . "/footer.php";
        ?>
    </body>
</html>