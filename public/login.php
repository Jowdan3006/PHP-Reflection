<?php

require_once __DIR__ .'/../src/config.php'; 
require_once LIB_PATH . "/MyPokémonUserDatabase.php";

if (isset($_GET['s']) || isset($_GET['error'])) {
    session_start();
} else {
    session_start();
    session_unset();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = false;
    $errorMessage = '?s=';
    if (!empty($_POST['user'])) {
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
        $_SESSION['user'] = $user;
    } else {
        $errorMessage .= 'user';
        $error = true;
    }
    if(!empty($_POST['pass'])) {
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    } else {
        $errorMessage .= 'pass';
        $error = true;
    }

    if ($error) {
        header("Location:login.php".$errorMessage);
        exit;
    }

    if(!empty($_POST['user']) && !empty($_POST['pass'])) {
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $type = 'email';
        } else {
            $type = 'username';
        }
        $db = new MyPokémonUserDatabase;
        $db->create();
        $db->login($user, $pass, $type);
    }
}


$pageTitle = "MYPHPokémon - Login";
require_once INC_PATH . "/head.php";

?>
<body>
    <?php
    require_once INC_PATH . "/header.php";
    ?>
    <div class="container">
        <?php 
        if (isset($_GET['s'])) {
            if (strpos($_GET['s'], 'user') !== false) {
                echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                echo "Please enter a Username. </div>";
            }
            if (strpos($_GET['s'], 'pass') !== false) {
                echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                echo "Please enter a password. </div>";
            }
        }
        if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">
                <?php 
                    switch ($_GET['error']) {
                        case 'error':
                            echo "Something went wrong! Try again.";
                            break;
                        case 'email':
                            echo "The email and password you entered did not match our records. Please double-check and try again.";
                            break;
                        case 'user':
                            echo "The username and password you entered did not match our records. Please double-check and try again.";
                            break;
                        default:
                            echo "Something went wrong! Try again.";
                            break;
                    }
                ?>
            </div>
            <?php } ?>
        <form method="post" action="login.php" style="width: 500px; margin:auto; margin-top: 25px;">
            <div class="form-group">
                <label for="user">Username or Email</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Username or Email" 
                    <?php if(isset($_SESSION['user'])) { echo 'value="'.$_SESSION['user'].'"';} ?>
                >
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="pass" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <?php
    require_once INC_PATH . "/footer.php";
    ?>
    </body>
</html>