<?php
require_once __DIR__ .'/../config.php';
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
        $userPass = $this->getValue('password', 'username', $user);
        var_dump($userPass);
        exit;
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
            header("Location:login.php?r=success");
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

    private function checkForValues($where, $value) {
        $sql = "SELECT user_id FROM users WHERE $where = ? ";
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

    private function getValue($what, $where, $value) {
        $sql = "SELECT $what FROM users WHERE $where = ? ";
        try {
            $result = $this->db->prepare($sql);
            $result->bindParam(1, $value, PDO::PARAM_STR);
            $result->execute();
        } catch (Exception $e) {
            echo "<pre>Error when checking database";
            echo $e->getMessage();
            echo "</pre>";
        }
        $responce = $result->fetch(PDO::FETCH_ASSOC);
        return $responce[$what];
    }

    private function createJWT($user, $type) {

    }
}
?>