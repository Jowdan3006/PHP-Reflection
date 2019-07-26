$('.fa-star').on('click', (e) => {
    
    if (typeof $(e.target).val() !== 'undefined') {
        var buttonPokeId = $(e.target).data('pokeArrayIndex');
    } else {
        var buttonPokeId = null;
    }
    $.ajax({
        type: 'POST',
        url: '../src/inc/favorite.php',
        data: { 'buttonPokeId' : buttonPokeId },
        success: (response) => {
            const jsonData = JSON.parse(response);
            if (jsonData.result >= 0) {
                $(e.target).toggleClass('far fas');
                $(e.target).parent().children('.favorite-count').text(jsonData.result);
            }
        }
    });
})