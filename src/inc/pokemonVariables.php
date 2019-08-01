<?php 
session_start();
if (isset($_SESSION['pokemonList'])) {
    $pokemonList = $_SESSION['pokemonList'];
} else {
    $pokemonList = new PokeAPI(null, 'list');
}

$db = new MyPokémonUserDatabase;
$db->create();

if (isset($userData)) {
    $profile = $db->getProfile($currentUserId);
    $gravImage = get_gravatar($currentUserEmail, 320);
    if ($profile) {
        if ($profile['type'] == '1') {
            $profileImage = $pokemonList->getImage($profile['pokemon_id']);
        } else {
            $profileImage = false;
        }
    } else {
        $profileImage = false;
    }
    
    $pokemonIdArray = $db->getFavoritePokemon($currentUserId);
    if (!isset($_SESSION['pokemonIdArray']) || $_SESSION['pokemonIdArray'] != $pokemonIdArray) {
        $_SESSION['pokemonIdArray'] = $pokemonIdArray;
    }
    
}

?>