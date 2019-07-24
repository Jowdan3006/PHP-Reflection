<?php require_once __DIR__ .'/../config.php';
use \Firebase\JWT\JWT;

if (isset($_COOKIE['user'])) {
    $cookieData = $_COOKIE['user'];
    $userData = JWT::decode($cookieData, getenv("SECRET_PASSWORD"), array('HS256'));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/798d6237c7.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <title><?php echo $pageTitle ?></title>
</head>