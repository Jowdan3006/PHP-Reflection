<div class="card" id="pokeCard0">
    <p class="favorite-count"><?php echo $db->getCountFavoritePokemon($pokemon->getId(), false) ?: 0 ?></p>
    <?php if (isset($userData)) {
        if ($pokemonIdArray && in_array($pokemon->getId(), $pokemonIdArray)) {
            echo '<i class="poke-fav fas fa-star"></i>';
        } else {
            echo '<i class="poke-fav far fa-star"></i>';
        }
    } ?>
    <div class="card-img-top">
        <img src="<?php echo $pokemon->getImage(); ?>" alt="Card image cap">
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
    </div>
</div>