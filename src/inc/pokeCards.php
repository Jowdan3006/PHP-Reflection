<div class="card">
    <?php if (isset($userData)) { ?>
        <p class="favorite-count with-icon"><?php echo $db->getCountFavoritePokemon($pokemon->getId($id), false) ?: 0 ?></p>
        <?php if ($pokemonIdArray && in_array($pokemon->getId($id), $pokemonIdArray)) {
            echo '<i class="poke-fav fas fa-star" data-poke-array-index="'.$id.'"></i>';
        } else {
            echo '<i class="poke-fav far fa-star" data-poke-array-index="'.$id.'"></i>';
        } 
    } else {
        echo '<i class="poke-fav fas fa-star"></i>'; ?>
        <p class="favorite-count"><?php echo $db->getCountFavoritePokemon($pokemon->getId($id), false) ?: 0 ?></p>
    <?php } ?>
    <div class="card-img-top">
        <img class="img-fluid" src="<?php echo $pokemon->getImage($id); ?>" alt="Image of Pokémon <?php echo $pokemon->getName($id)?>">
    </div>
    <div class="card-body">
        <h5 class="card-title"><?php echo $pokemon->getName($id); echo ' #'.$pokemon->getId($id); ?></h5>
        <div id="FlavorText<?php echo $id ?>" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $flavorCount = 0;
                foreach($pokemon->getFlavorText(true, $id) as $flavorValue) {
                if ($flavorCount == 0) {
                    echo '<div class="carousel-item active">';
                    $flavorCount++;
                } else {
                    echo '<div class="carousel-item">'; 
                }
                    echo '<p class="d-block w-100" style="padding: 0 15px;">'.$flavorValue['text'].'</p>';
                    echo '<p class="d-block w-100" style="padding: 0 15px;">';
                    if (count($flavorValue['version']) > 1) {
                        foreach ($flavorValue['version'] as $version) {
                            echo '<span class="badge badge-pill version-'.$version.'">'.$version.'</span>';
                        }
                    }else {
                        echo '<span class="badge badge-pill version-'.$flavorValue['version'][0].'">'.$flavorValue['version'][0].'</span>';
                    }
                    echo '</p>';
                echo '</div>';
                } 
                ?>
            </div>
            <a class="carousel-control-prev" href="#FlavorText<?php echo $id ?>" role="button" data-slide="prev">
                <i class="fas fa-chevron-left"></i>
            </a>
            <a class="carousel-control-next" href="#FlavorText<?php echo $id ?>" role="button" data-slide="next">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
        <a href="<?php echo "https://pokemondb.net/pokedex/".$pokemon->getName($id).'"'; ?>" class="btn <?php echo 'type-'; foreach ($pokemonList->getPokemonType($pokemon->getId($id)) as $type) { echo $type; }; ?>" target="_blank"><span>View on Pokémon Database</span></a>
    </div>
</div>