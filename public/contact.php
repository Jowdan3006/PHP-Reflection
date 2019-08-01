<?php 
require_once __DIR__ .'/../src/config.php';

$pageTitle = "MYPHPokémon - Contact us";
require_once INC_PATH . "/head.php";
require_once LIB_PATH . "/MyPokémonUserDatabase.php";
require_once INC_PATH . "/userData.php";
require_once LIB_PATH . "/PokeAPI.php";
require_once INC_PATH . "/pokemonVariables.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['s']) || !isset($_GET['error'])) {
    unset($_SESSION['name']);
    unset($_SESSION['email']);
    unset($_SESSION['type']);
    unset($_SESSION['text']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = false;
    $errorMessage = '?s=';
    if (!empty($_POST['name'])) {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $_SESSION['name'] = $name;
    } else {
        $errorMessage .= 'name';
        $error = true;
    }
    if(!empty($_POST['email'])) {
        $userEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $_SESSION['email'] = $userEmail;
    } else {
        $errorMessage .= 'email';
        $error = true;
    }
    if(!empty($_POST['type'])) {
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $_SESSION['type'] = $type;
    } else {
        $errorMessage .= 'type';
        $error = true;
    }
    if(!empty($_POST['text'])) {
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
        $_SESSION['text'] = $text;
    } else {
        $errorMessage .= 'text';
        $error = true;
    }

    if ($error) {
        header("Location:contact.php".$errorMessage);
        exit;
    }

    if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['type']) && !empty($_POST['text'])) {
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = getenv('GMAIL_USERNAME');
            $mail->Password = getenv('GMAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';                                  
            $mail->Port = 587;                                   
        
            $mail->setFrom('myphpokemon@gmail.com', $name);
            $mail->addReplyTo($userEmail, $name);
            $mail->addAddress('myphpokemon@gmail.com', 'MYPHPokemon');

            $mail->Subject = $type;
            $mail->Body    = $text;
        
            $mail->send();

            header('Location:index.php?r=sent');
        } catch (Exception $e) {
            header('Location:contact.php?error=error');
        }
    }
}
?>

    <body>

        <?php
        $activePage = 'contact';
        require_once INC_PATH . "/header.php";
        ?>
        <div class="container">
        <?php 
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                echo "Something went wrong! Please resend or try again later.</div>";
            }
            if (isset($_GET['s'])) {
                if (strpos($_GET['s'], 'name') !== false) {
                    echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                    echo "Please enter a name. </div>";
                }
                if (strpos($_GET['s'], 'email') !== false) {
                    echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                    echo "Please enter an email address. </div>";
                }
                if (strpos($_GET['s'], 'type') !== false) {
                    echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                    echo "Please select a category. </div>";
                }
                if (strpos($_GET['s'], 'text') !== false) {
                    echo '<div class="alert alert-warning" role="alert" style="width: 500px; margin: auto; margin-top: 25px;">';
                    echo "Please describe your issue. </div>";
                }
            }
        ?>
            <form method="post" action="contact.php" style="padding: 0 10rem; margin-top: 1rem;">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Full name</label>
                        <input name="name" type="text" class="form-control" id="name" placeholder="Professor Oak"
                        <?php if(isset($_SESSION['name'])) { echo 'value="'.$_SESSION['name'].'"';} ?>
                        >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email address</label>
                        <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com"
                        <?php if(isset($_SESSION['email'])) { echo 'value="'.$_SESSION['email'].'"';} ?>
                        >
                    </div>
                </div>
                <label for="type">Select a category</label>
                <div class="form-group form-inline">
                    <select name="type" class="form-control" id="type">
                        <option value="">Select a category</option>
                        <option <?php if(isset($_SESSION['type'])) { echo $_SESSION['type'] == "Suggest a feature" ? 'selected' : '' ;}?>>Suggest a feature</option>
                        <option <?php if(isset($_SESSION['type'])) { echo $_SESSION['type'] == "Account issues" ? 'selected' : '' ;}?>>Account issues</option>
                        <option <?php if(isset($_SESSION['type'])) { echo $_SESSION['type'] == "Missing Pokémon" ? 'selected' : '' ;}?> value="Missing Pokemon">Missing Pokémon</option>
                        <option <?php if(isset($_SESSION['type'])) { echo $_SESSION['type'] == "I encountered an issue" ? 'selected' : '' ;}?>>I encountered an issue</option>
                        <option <?php if(isset($_SESSION['type'])) { echo $_SESSION['type'] == "Other" ? 'selected' : '' ;}?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="text">Please describe here.</label>
                    <textarea name="text" class="form-control" id="text" rows="3" placeholder="What was my grandsons name again?"><?php if(isset($_SESSION['text'])) { echo $_SESSION['text'];} ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>

        <?php
        require_once INC_PATH . "/footer.php";
        ?>
    </body>
</html>