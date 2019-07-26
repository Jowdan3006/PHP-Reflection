<?php
use \Firebase\JWT\JWT;
if (isset($_COOKIE['logged'])) {
    $expiredSession = true;
}
if (isset($_COOKIE['user'])) {
    $cookieData = $_COOKIE['user'];
    try { 
        $userData = JWT::decode($cookieData, getenv("SECRET_PASSWORD"), array('HS256'));
        if ($userData->iss != getenv('MY_DB_NAME')) {
            unset($_COOKIE['user']);
        } else {
            $expiredSession = false;
            $currentUserId = $userData->data->id;
            $currentUserEmail = $userData->data->email;
            $currentUserUsername = $userData->data->username;
            if ($userData->iat < (time() - 360)) {
                $time = time();
                $expire = $time + 3600;
                $token = array(
                    "iss" => getenv("MY_DB_NAME"),
                    "iat" => $time,
                    "nbf" => $time,
                    "exp" => $expire,
                    "data" => array(
                        "id" => $currentUserId,
                        "email" => $currentUserEmail,
                        "username" => $currentUserUsername
                    )
                );
                $jwt = JWT::encode($token, getenv("SECRET_PASSWORD"), 'HS256');
                setcookie("user", $jwt, $expire, '/', 'localhost', FALSE, TRUE);
                setcookie("logged", 'true', time() + 7200 , '/', 'localhost', FALSE, TRUE);
            }
        }
    } catch (Exception $e) {
        unset($_COOKIE['user']);
    }
}
?>