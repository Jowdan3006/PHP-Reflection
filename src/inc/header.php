<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-bottom: 1rem;">
        <div class="container">
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
                        <a class="nav-link" href="<?php echo PUB_PATH . "index.php" ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo PUB_PATH . "pokedex.php" ?>">Pokédex</a>
                    </li>
                    <?php if (isset($userData)) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo PUB_PATH . "favourites.php" ?>">Favourites</a>
                    </li>
                    <?php } ?>
                </ul>
                <ul class="nav navbar-nav ml-auto w-100 justify-content-end">
                    <?php if (isset($userData)) { ?>
                        <li class="nav-item">
                            Welcome, <?php echo $userData->data->username ?><a class="nav-link" href="<?php echo PUB_PATH . "logout.php" ?>" style="display: inline; padding: 0; font-size: 85%;">(Log out)</a>
                        </li>
                    <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo PUB_PATH . "login.php" ?>">Login</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="<?php echo PUB_PATH . "register.php" ?>">Register</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>