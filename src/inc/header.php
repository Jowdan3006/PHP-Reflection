<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <?php 
            if (isset($expiredSession) && $expiredSession && isset($_COOKIE['logged'])) { 
                setcookie('logged', "", time(), '/');?>
            <div class="container" id="headerAlert">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">Session has expired.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <?php } ?>
            <a class="navbar-brand d-flex" href="index.php">
                <div class="logo"></div>
                MYPHPokémon
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse w-100" id="collapsingNavbar">
                <ul class="navbar-nav w-100">
                    <li class="nav-item <?php echo $activePage == 'home' ? 'active' : '' ?>">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'pokedex' ? 'active' : '' ?>" href="pokedex.php">Pokédex</a>
                    </li>
                    <?php if (isset($userData)) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'favorites' ? 'active' : '' ?>" href="favorites.php">Favourites</a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'contact' ? 'active' : '' ?>" href="contact.php">Contact us</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav ml-auto w-100 justify-content-end">
                    <?php if (isset($userData)) { ?>
                        <li class="nav-item">
                            <p>Welcome, <?php echo $userData->data->username ?><a class="nav-link" href="logout.php">(Log out)</a></p>
                        </li>
                        <li class="nav-item">
                            <a href="profile.php" class="nav-link img-thumbnail <?php echo $activePage == 'profile' ? 'profile-highlight' : '' ?>">
                                <img class="profile-image-header" src="<?php echo isset($profileImage) && $profileImage ? $profileImage : get_gravatar($currentUserEmail, 40)?>">
                            </a>
                        </li>
                    <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'login' ? 'active' : '' ?>" href="login.php">Login</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link <?php echo $activePage == 'register' ? 'active' : '' ?>" href="register.php">Register</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>