<?php
require '../config.php';

require INC_PATH . "/userData.php";
require LIB_PATH . "/MyPokémonUserDatabase.php";
require LIB_PATH . "/PokeAPI.php";

session_start();
if (isset($userData) && isset($_SESSION['pokemon'])) {
    $pokemon = $_SESSION['pokemon'];
    $db = new MyPokémonUserDatabase;
    $db->create();
    $result = $db->favoritePokemon($currentUserId, $pokemon->getId());
    echo json_encode([
        'result' => $result,
    ]);
}
?>