<?php require_once INC_PATH . "/userData.php"; ?>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-bottom: 1rem;">
        <div class="container">
            <?php 
            if (isset($expiredSession) && $expiredSession && isset($_COOKIE['logged'])) { 
                setcookie('logged', "", time(), '/');?>
            <div class="container" id="expiredSession">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">Session has expired.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <?php } ?>
            <a class="navbar-brand d-flex" href="<?php echo PUB_PATH . "index.php" ?>">
                <div class="logo"></div>
                MYPHPokémon
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse w-100" id="collapsingNavbar">
                <ul class="navbar-nav w-100">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pokedex.php">Pokédex</a>
                    </li>
                    <?php if (isset($userData)) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="favorites.php">Favourites</a>
                    </li>
                    <?php } ?>
                </ul>
                <ul class="nav navbar-nav ml-auto w-100 justify-content-end">
                    <?php if (isset($userData)) { ?>
                        <li class="nav-item">
                            Welcome, <?php echo $userData->data->username ?><a class="nav-link" href="logout.php" style="display: inline; padding: 0; font-size: 85%;">(Log out)</a>
                        </li>
                    <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>