<?php
require '../config.php';

require INC_PATH . "/userData.php";
require LIB_PATH . "/MyPokémonUserDatabase.php";
require LIB_PATH . "/PokeAPI.php";

session_start();

if (isset($_POST['buttonPokeId'])) {
    $buttonPokeId = filter_input(INPUT_POST, 'buttonPokeId', FILTER_SANITIZE_NUMBER_INT);
}
if (isset($userData) && isset($_SESSION['pokemon'])) {
    $pokemon = $_SESSION['pokemon'];
    $db = new MyPokémonUserDatabase;
    $db->create();
    if (isset($buttonPokeId)) {
        $pokeId = $pokemon->getId($buttonPokeId);
    } else {
        $pokeId = $pokemon->getId();
    }
    $result = $db->favoritePokemon($currentUserId, $pokeId);
    echo json_encode([
        'result' => $result,
    ]);
}
?>