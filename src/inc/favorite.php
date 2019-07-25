<?php
session_start();
require '../config.php';
require LIB_PATH . "/PokeAPI.php";
require LIB_PATH . "/MyPokémonUserDatabase.php";
require inc_PATH . "/userData.php";

if (isset($userData) && isset($_SESSION['pokemon'])) {
    $pokemon = $_SESSION['pokemon'];
    $db = new MyPokémonUserDatabase;

    $db->favoritePokemon($currentUserId, $pokemon->getId());
    echo json_encode([
        'color' => 'blue',
    ]);
}
?>