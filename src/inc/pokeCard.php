<div class="card" id="pokeCard0" style="width: 450px; margin: auto;">
    <div style="padding: 25px">
        <img class="card-img-top" max-height="350px" src="<?php echo $pokemon->getImage(); ?>" alt="Card image cap">
    </div>
    <div class="card-body">
        <h5 class="card-title" style="margin-left: 15px;"><?php echo $pokemon->getName(); ?></h5>
        <div id="FlavorText" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $flavorCount = 0;
                foreach($pokemon->getFlavorText(true) as $flavorValue) {
                if ($flavorCount == 0) {
                    echo '<div class="carousel-item active">';
                    $flavorCount++;
                } else {
                    echo '<div class="carousel-item">'; 
                }
                    echo '<p class="d-block w-100" style="padding: 0 15px;">'.$flavorValue['text'].'</p>';
                    echo '<p class="d-block w-100" style="padding: 0 15px;"> From: ';
                    if (count($flavorValue['version']) > 1) {
                        foreach ($flavorValue['version'] as $version) {
                            echo '<span class="badge version-'.$version.'">'.$version.'</span>';
                        }
                    } else {
                        echo '<span class="badge version-'.$flavorValue['version'][0].'">'.$flavorValue['version'][0].'</span>';
                    }
                    echo '</p>';
                echo '</div>';
                } 
                ?>
            </div>
            <a class="carousel-control-prev" href="#FlavorText" role="button" data-slide="prev" style="left: -35px;">
                <i class="fas fa-chevron-left" style="color: black;"></i>
            </a>
            <a class="carousel-control-next" href="#FlavorText" role="button" data-slide="next" style="right: -35px;">
                <i class="fas fa-chevron-right" style="color: black;"></i>
            </a>
        </div>
        <a href="<?php echo "https://pokemondb.net/pokedex/".$pokemon->getName().'"'; ?>" class="btn btn-primary" target="_blank" style="margin-left: 15px;">View on Pokémon Database</a>
        <button class="favorite" ><i class="fas fa-star"></button></i>
    </div>
</div>