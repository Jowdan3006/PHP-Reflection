$('.favorite').on('click', (e) => {
    $.ajax({
        url: '../src/inc/favorite.php',
        success: (response) => {
            const jsonData = JSON.parse(response);
            if (jsonData.result == true) {
                $(e.target).css('color', 'red');
            } else {
                $(e.target).css('color', 'black');
            }
        }
    });
})