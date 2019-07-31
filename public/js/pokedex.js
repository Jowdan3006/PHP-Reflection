pokeTypes = ['normal', 'fighting', 'flying', 'poison', 
            'ground', 'rock', 'bug', 'ghost', 'steel', 
            'fire', 'water', 'grass', 'electric', 'psychic', 
            'ice', 'dragon', 'dark', 'fairy'];

let searchBox = $('.form-control');
if (searchBox.length < 1) {
    searchBox = `<div class="dropdown-menu"></div><input autocomplete="off" class="form-control mr-sm-2" style="min-width: 250px" type="search" placeholder="Enter a Pokémon name or ID" aria-label="Search" name="s">`
} else {
    searchBox = searchBox[0].outerHTML + `<div class="dropdown-menu"></div>`;
}

let typeSearch = $('.type-select');
if (typeSearch.length < 1) {
    typeSearch = `
    <div class="input-group mr-sm-2">
        <select class="custom-select" id="select-type" name="s">
            <option value="null" selected>Choose Type</option>`
            pokeTypes.forEach(type => {
                typeSearch += `<option value="${type}">${type}</option>`
            });
        typeSearch += `</select>
    </div>`
} else {
    typeSearch = typeSearch[0].outerHTML;
}


let randomSearch = $('.random-select');
if (randomSearch.length < 1) {
    randomSearch = `
    <div class="input-group mr-sm-2">
        <select class="custom-select" id="select-type" name="s">
            <option value="null" selected>Random</option>`
            pokeTypes.forEach(type => {
                randomSearch += `<option value="${type}">${type}</option>`
            });
            randomSearch += `</select>
    </div>`
} else {
    randomSearch = randomSearch[0].outerHTML;
}

function dropdown() {
    $('#pokedex-search-box').on('keyup', $('.form-control'), (e) => {
        search = $(e.target).val();
        if (search == null || search == 'unidentified' || search == '') {
            $('.dropdown-menu').css('display', 'none');
            $('.dropdown-menu').html('');
        } else {
            $.ajax({
                type: 'POST',
                url: '../src/inc/search.php',
                data: { 'search' : search.toLowerCase() },
                success: (response) => {
                    const jsonData = JSON.parse(response);
                    let dropdown = '';
                    jsonData.string.forEach(string => {
                        if (string.name != null) {
                            dropdown += `<a class="dropdown-item" href="pokedex.php?filter=nid&s=${string.name}">${string.name}</a>`
                        }
                    })
                    if (jsonData.string[0].name != null) {
                        $('.dropdown-menu').css('display', 'block');
                        $('.dropdown-menu').html(dropdown);
                    } else {
                        $('.dropdown-menu').css('display', 'none');
                        $('.dropdown-menu').html('');
                    }
                }
            });
        }
    });
}

$('.form-check-input').on('click', (e) => {
    switch (e.target.id) {
        case 'random':
            $('#pokedex-search-box').html(randomSearch);
            break;
        case 'type':
            $('#pokedex-search-box').html(typeSearch);
            break;
        case 'nameOrID':
            $('#pokedex-search-box').html(searchBox);
            dropdown();
            break;
    }
});

$().ready(dropdown())