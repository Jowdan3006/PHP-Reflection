let currentPoke = ''
let currentVal = ''

$('.form-check-input').on('click', (e) => {
    console.log(e);
    if ($(e.currentTarget).val() != currentVal) {
        $('.profile-thumbnails').toggleClass('overlay-grey');
        switch (e.target.id) {
            case 'grav':
                $(".profile-picture").html('<img data-pokeId="null" src="'+gravImage+'"/>');
                currentVal = $(e.currentTarget).val()
                break;
            case 'poke':
                if (currentPoke != null && currentPoke != 'unidentified' && currentPoke != '') {
                    $(".profile-picture").html(currentPoke);
                }
                currentVal = $(e.currentTarget).val()
                break;
        }
    }
});

$('.profile-thumb').on('click', (e) => {
    if ($('.form-check-input#poke')[0].checked) {
        let img = $(e.currentTarget).html();
        let pokeId = $(img).attr('data-pokeId');
        $(".profile-picture").html(img);
        $("#pokeId").attr('value', pokeId);
        currentPoke = img;
    }
});