<?php
require '../config.php';
require LIB_PATH . "/PokeAPI.php";

session_start();
if (isset($_SESSION['pokemonList'])) {
    $pokemonList = $_SESSION['pokemonList'];
} else {
    $pokemonList = new PokeAPI(null, 'list');
}

if (isset($_SESSION['pokemonNames'])) {
    $pokemonNames = $_SESSION['pokemonNames'];
} else {
    $pokemonNames = $pokemonList->getnames();
}

if (isset($_POST['search'])) {
    $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
}
$searchSize = strlen($search);
$string = [['rank' => 0, 'name' => null],['rank' => 0, 'name' => null],['rank' => 0, 'name' => null],['rank' => 0, 'name' => null],['rank' => 0, 'name' => null]];
foreach ($pokemonNames as $name) {
    if (strpos($name, $search) === 0 || strpos($name, $search)) {
        $pos = strpos($name, $search);
        if ($pos != 0) {
            $pos = 1 / $pos;
        } else {
            $pos = 1;
        }
        $nameSize = strlen($name);
        $sizeDiff = $searchSize / $nameSize;
        $rank = $pos + $sizeDiff;
        if ($rank > $string[0]['rank']) {
            $string[0] = ['rank' => $rank, 'name' => $name];
        } else if ($rank > $string[1]['rank']) {
            $string[1] = ['rank' => $rank, 'name' => $name];
        } else if ($rank > $string[2]['rank']) {
            $string[2] = ['rank' => $rank, 'name' => $name];
        } else if ($rank > $string[3]['rank']) {
            $string[3] = ['rank' => $rank, 'name' => $name];
        } else if ($rank > $string[4]['rank']) {
            $string[4] = ['rank' => $rank, 'name' => $name];
        }
    }
}
echo json_encode([
    'string' => $string
]);


?>