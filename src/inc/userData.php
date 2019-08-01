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
                setcookie("user", $jwt, $expire, '/', getenv("MY_DB_HOST"), FALSE, TRUE);
                setcookie("logged", 'true', time() + 7200 , '/', getenv("MY_DB_HOST"), FALSE, TRUE);
            }
        }
    } catch (Exception $e) {
        unset($_COOKIE['user']);
    }

    function get_gravatar( $email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }
}
?>