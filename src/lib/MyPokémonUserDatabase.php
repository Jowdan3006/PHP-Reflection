<?php
require_once __DIR__ .'/../config.php';
use \Firebase\JWT\JWT;
class MyPokémonUserDatabase
{
    private $type;
    private $host;
    private $name;
    private $username;
    private $password;
    private $charset;
    private $attributes = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    public $db;

    public function __construct()
    {
        $this->type = getenv('MY_DB_TYPE');
        $this->host = getenv('MY_DB_HOST');
        $this->name = getenv('MY_DB_NAME');
        $this->username = getenv('MY_DB_USERNAME');
        $this->password = getenv('MY_DB_PASSWORD');
        $this->charset = getenv('MY_DB_CHARSET');
    }
    public function create()
    { 
        $dsn = "$this->type:host=$this->host;dbname=$this->name;charset=$this->charset";
        try {
            $pdo = new PDO($dsn, $this->username, $this->password, $this->attributes);
        } catch (PDOException $e) {
            echo "<pre>Cannot connect to Database";
            echo $e->getMessage();
            echo "</pre>";
            exit;
        }
        $this->db = $pdo;
    }

    public function register($user, $email, $pass) {
        $criteria = ['username' => $user, 'email' => $email];
        $available = $this->checkForUser($criteria);
        if ($available !== false) {
            switch ($available) {
                case 3: header("Location:register.php?error=both"); exit;
                case 2: header("Location:register.php?error=user"); exit;
                case 1: header("Location:register.php?error=email"); exit;
                default: header("Location:register.php?error=error"); exit;
            }
        }
        $sql = ("INSERT INTO users (username, email, password) VALUES ( ? , ? , ? ) ");
        try {
            $reg = $this->db->prepare($sql);
            $reg->bindParam(1, $user, PDO::PARAM_STR);
            $reg->bindParam(2, $email, PDO::PARAM_STR);
            $reg->bindParam(3, $pass, PDO::PARAM_STR);
            $reg->execute();
        } catch (Exception $e) {
            echo "<pre>Error when registering user";
            echo $e->getMessage();
            echo "</pre>";
        }
        $this->createJWT($user, 'username');
        header('Location:index.php?r=reg');
    }

    public function login($user, $pass, $type) {
        if ($type == 'user') {
            $type = 'username';
        }
        $available = $this->checkForUser([$type => $user]);
        if ($available === false) {
            switch ($type) {
                case 'username':
                    header("Location:login.php?error=user");
                    break;
                case 'email':
                    header("Location:login.php?error=email");
                    break;
                default:
                    header("Location:login.php?error=error");
                    break;
            }
            exit;
        }
        $userPass = $this->getValue('password', $type, $user);
        $check = password_verify($pass, $userPass);
        if ($check) {
            $this->createJWT($user, $type);
            header("Location:index.php?r=success");
        } else {
            switch ($type) {
                case 'username':
                    header("Location:login.php?error=user");
                    break;
                case 'email':
                    header("Location:login.php?error=email");
                    break;
                default:
                    header("Location:login.php?error=error");
                    break;
            }
        }
    }

    public function resetPassword($username, $email, $pass, $admin = false) {
        if ($admin) {
            $criteria = ['username' => $username, 'email' => $email];
            $available = $this->checkForUser($criteria);
            switch ($available) {
                case 3: 
                    $this->changeValue('password', $pass, 'username', $username);
                    header("Location:resetPass.php?r=success");
                    break;

                case 2: header("Location:resetPass.php?error=miss"); exit;
                case 1: header("Location:resetPass.php?error=miss"); exit;
                case false: header("Location:resetPass.php?error=miss"); exit;
                default: header("Location:resetPass.php?error=error"); exit;
            }
        } else {
            header("Location:resetPass.php?error=admin");
            exit;
        }
    }
    private function insertValues($columns, $values, $into = 'favorite_pokemon') {
        $sql = "INSERT INTO $into (";
        foreach ($columns as $column) {
            $sql .= "$column ,";
        }
        $sql = substr($sql, 0, -1) . ") VALUES (";
        for ($i = 0; $i < count($values); $i++) {
            $sql .= '? ,';
        }
        $sql = substr($sql, 0, -1) . ")";
        try {
            $reg = $this->db->prepare($sql);
            for ($i = 1; $i <= count($values); $i++) {
                $reg->bindParam($i, $values[$i-1], PDO::PARAM_STR);
            }
            $reg->execute();
            return true;
        } catch (Exception $e) {
            echo "<pre>Error inserting user into favorites";
            echo $e->getMessage();
            echo "</pre>";
            return false;
        }
    }
    private function changeValue($what, $value, $where, $who, $from = 'users') {
        $sql = ("UPDATE $from SET $what = ? WHERE $where = ?");
        try {
            $reg = $this->db->prepare($sql);
            $reg->bindParam(1, $value, PDO::PARAM_STR);
            $reg->bindParam(2, $who, PDO::PARAM_STR);
            $reg->execute();
            return true;
        } catch (Exception $e) {
            echo "<pre>Error when registering user";
            echo $e->getMessage();
            echo "</pre>";
            return false;
        }
    }

    private function checkForUser($values = []) {
        $checkValues = [];
        foreach ($values as $type => $value) {
            $checkValues[] = $this->checkForValues($type, $value);
            if (count($values) == 1) {
                if (!$checkValues[0]) {
                    return false;
                }
                return true;
            }
        }
        if ($checkValues[0] && $checkValues[1]) {
            return 3;
        }
        if ($checkValues[0]) {
            return 2;
        }
        if ($checkValues[1]) {
            return 1;
        }
        return false;
    }

    private function checkForValues($where, $value, $from = 'users') {
        $sql = "SELECT user_id FROM $from WHERE $where = ? ";
        try {
            $check = $this->db->prepare($sql);
            $check->bindParam(1, $value, PDO::PARAM_STR);
            $check->execute();
        } catch (Exception $e) {
            echo "<pre>Error when checking database";
            echo $e->getMessage();
            echo "</pre>";
        }
        if ($check->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

    private function getValue($what, $where, $value, $from = 'users') {
        $sql = "SELECT $what FROM $from WHERE $where = ? ";
        try {
            $result = $this->db->prepare($sql);
            $result->bindParam(1, $value, PDO::PARAM_STR);
            $result->execute();
        } catch (Exception $e) {
            echo "<pre>Error when checking database";
            echo $e->getMessage();
            echo "</pre>";
        }
        $response = $result->fetch(PDO::FETCH_ASSOC);
        if ($what == '*') {
            return $response;
        }
        return $response[$what];
    }

    private function getValues($what, $where, $value, $from = 'users') {
        $sql = "SELECT $what FROM $from WHERE $where = ? ";
        try {
            $result = $this->db->prepare($sql);
            $result->bindParam(1, $value, PDO::PARAM_STR);
            $result->execute();
        } catch (Exception $e) {
            echo "<pre>Error when checking database";
            echo $e->getMessage();
            echo "</pre>";
        }
        $response = $result->fetchall(PDO::FETCH_ASSOC);
        return $response[0];
    }

    private function createJWT($user, $type) {
        if ($type == 'username') {
            $userData = $this->getValues('user_id, username, email', 'username', $user);
        } else {
            $userData = $this->getValue('user_id, username, email', 'email', $user);
        }
        $time = time();
        $expire = $time + 3600;
        $token = array(
            "iss" => getenv("MY_DB_NAME"),
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expire,
            "data" => array(
                "id" => $userData['user_id'],
                "email" => $userData['email'],
                "username" => $userData['username']
            )
        );

        $jwt = JWT::encode($token, getenv("SECRET_PASSWORD"), 'HS256');
        setcookie("user", $jwt, $expire, '/', 'localhost', FALSE, TRUE);
        setcookie("logged", 'true', time() + 86400 , '/', 'localhost', FALSE, TRUE);
    }

    public function favoritePokemon($user, $pokemonId)
    {
        $userEntry = $this->getValue('*', 'user_id', $user, 'favorite_pokemon');
        $change = false;
        $insert = false;
        $add = false;
        if ($userEntry) {
            if (!empty($userEntry['pokemon_id'])) {
                $pokemonIdArray = explode(', ', $userEntry['pokemon_id']);
                if (($pokemonIdKey = array_search($pokemonId, $pokemonIdArray)) !== FALSE) {
                    unset($pokemonIdArray[$pokemonIdKey]);
                    $add = false;
                } else {
                    $pokemonIdArray[] = $pokemonId;
                    $add = true;
                }
                $pokemonIdString = implode(', ', $pokemonIdArray);
            } else {
                $pokemonIdString = $pokemonId;
                $add = true;
            }
            $change = $this->changeValue('pokemon_id', $pokemonIdString, 'user_id', $user, 'favorite_pokemon');
        } else {
            $pokemonIdString = $pokemonId;
            $insert = $this->insertValues(['user_id', 'pokemon_id'], [$user, $pokemonIdString]);
        }
        if ($insert || ($change && $add)) {
            return true;
        } else {
            return false;
        }

        return null;
    }
}
?>