$('.favorite').on('click', (e) => {
    $.ajax({
        url: '../src/inc/favorite.php',
        success: (response) => {
            const jsonData = JSON.parse(response);
            $(e.target).css('color', jsonData.color);
            $('<p>'+ jsonData.pokemon +'</p>').appendTo(e.target);
        }
    });
})