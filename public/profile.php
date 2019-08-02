<?php 
require_once __DIR__ .'/../src/config.php';

$pageTitle = "MYPHPokémon";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once INC_PATH . "/userData.php";
require_once LIB_PATH . "/PokeAPI.php";
require_once INC_PATH . "/pokemonVariables.php";

if (!isset($userData)) {
    header('Location:login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['profilePic']) && isset($_POST['pokeId']) && isset($userData)) {
        $profilePic = filter_input(INPUT_POST, 'profilePic', FILTER_SANITIZE_STRING);
        if ($profilePic != 'null') {
            $pokeId = filter_input(INPUT_POST, 'pokeId', FILTER_SANITIZE_NUMBER_INT);
        } else {
            $pokeId = null;
        }

        $db->setProfile($currentUserId, $profilePic, $pokeId);
        header('Location:index.php');
    }
}
?>

    <body>

        <?php
        $activePage = 'profile';
        require_once INC_PATH . "/header.php";
        ?>

        <div class="container">
            <div class="row">
                <div class="col-md-6 ">
                    <div class="profile-picture img-thumbnail">
                        <img src="<?php echo $profileImage ? $profileImage : $gravImage?>">
                    </div>
                </div>
                <form method="post" action="profile.php" class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="profilePic" id="grav" value="grav" <?php echo $profileImage ? '' : 'checked' ?>>
                        <label class="form-check-label" for="grav">Use Gravatar</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="profilePic" id="poke" value="poke" <?php echo $profileImage ? 'checked' : '' ?>>
                        <label class="form-check-label" for="poke">Use favorite Pokémon</label>
                    </div>
                    <div class="profile-thumbnails d-flex <?php echo $profileImage ? '' : 'overlay-grey' ?>">
                        <?php
                        if (!empty($pokemonIdArray)) {
                            foreach($pokemonIdArray as $pokeId) {
                                echo "<div class='profile-thumb img-thumbnail'><img data-pokeId='".$pokeId."' src='". $pokemonList->getImage($pokeId)."'/></div>";
                            }
                        } else {
                            echo "<p><small><br>You must favorite some Pokemon to use them as a profile image.</small></p>";
                        }
                        ?>
                    </div>
                    <input id="pokeId" name="pokeId" value="<?php echo $profile['pokemon_id'] ? $profile['pokemon_id'] : 'null' ?>" hidden>
                    <button type="submit" class="btn btn-primary">Apply changes</button>
                </form>
            </div>
        </div>

        <?php
        require_once INC_PATH . "/footer.php";
        echo '<script> let gravImage = "'.$gravImage.'"</script>';
        echo "<script src='". JS_PATH . "/profile.js"."'></script>";
        ?>
    </body>
</html>