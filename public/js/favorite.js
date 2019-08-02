let alert = false;
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
            if (jsonData.result == 'full') {
                if (!alert) {
                    $('#headerAlert').append('<div class="alert alert-warning alert-dismissible fade show" role="alert">You have reached the limit of 20 favorite Pokemon.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    alert = true
                    closeAlert();
                }
            } else if (jsonData.result >= 0) {
                $(e.target).toggleClass('far fas');
                $(e.target).parent().children('.favorite-count').text(jsonData.result);
            }
        }
    });
})

function closeAlert() {
    $('#headerAlert').children('.alert').children('.close').on('click', () =>{
        alert = false;
    })
}