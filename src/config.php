<?php 
/*
    Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL);

/*
    Creating constants for include folder path.
*/
defined("INC_PATH")
    or define("INC_PATH", realpath(dirname(__FILE__) . '/inc'));

defined("LIB_PATH")
    or define("LIB_PATH", realpath(dirname(__FILE__) . '/lib'));

defined("PUB_PATH")
    or define("PUB_PATH", realpath(dirname(__FILE__) . '../public'));

/*
    composer autoloader.
*/
require __DIR__."/../vendor/autoload.php";

/*
    Initialize custom environmental variables in .env file.
*/

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
?>