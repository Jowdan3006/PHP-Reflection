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
    if(!empty($_POST['email'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $_SESSION['email'] = $email;
    } else {
        $errorMessage .= 'email';
        $error = true;
    }
    if (!empty($_POST['pass'])) {
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
        if (!empty($_POST['conf'])) {
            $conf = filter_input(INPUT_POST, 'conf', FILTER_SANITIZE_STRING);
        } else {
            $errorMessage .= 'conf';
            $error = true;
        }
    } else {
        $errorMessage .= 'pass';
        $error = true;
    }

    if ($error) {
        header("Location:register.php".$errorMessage);
        exit;
    }

    if(!empty($_POST['email']) && !empty($_POST['pass']) && !empty($_POST['user']) && !empty($_POST['conf'])) {
        if ($pass !== $conf) {
            header("Location:register.php?error=missmatch");
            exit;
        }
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
        $db = new MyPokémonUserDatabase;
        $db->create();
        $db->register($user, $email, $hashedPass);
    }
}


$pageTitle = "MYPHPokémon - Register";
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
                if (strpos($_GET['s'], 'email') !== false) {
                    echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                    echo "Please enter an email address. </div>";
                }
                if (strpos($_GET['s'], 'pass') !== false) {
                    echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                    echo "Please enter a password. </div>";
                }
                if (strpos($_GET['s'], 'conf') !== false) {
                    echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                    echo "Please confirm your password. </div>";
                }
            }
            if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">
                <?php 
                    switch ($_GET['error']) {
                        case 'error':
                            echo "Something went wrong! Try again.";
                            break;
                        case 'both':
                            echo "A user already exists with that email address and username.";
                            break;
                        case 'email':
                            echo "A user already exists with that email address.";
                            break;
                        case 'user':
                            echo "A user already exists with that username.";
                            break;
                        case 'missmatch':
                            echo "Passwords do not match!";
                            break;
                        default:
                            echo "Something went wrong! Try again.";
                            break;
                    }
                ?>
            </div>
            <?php } ?>
            <form method="post" action="register.php" style="width: 500px; margin:auto; margin-top: 25px;">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="user" placeholder="Username" 
                        <?php if(isset($_SESSION['user'])) { echo 'value="'.$_SESSION['user'].'"';} ?>
                    >
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email"
                        <?php if(isset($_SESSION['email'])) { echo 'value="'.$_SESSION['email'].'"';} ?>
                    >
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="pass" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="passwordConfirm">Confirm Password</label>
                    <input type="password" class="form-control" id="passwordConfirm" name="conf" placeholder="Confirm Password">
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <div>
                <?php 
                
                ?>
            </div>
        </div>

        <?php
        require_once INC_PATH . "/footer.php";
        ?>
    </body>
</html>