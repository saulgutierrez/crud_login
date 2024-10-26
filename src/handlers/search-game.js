$(document).ready(function () {
    $('#search-form').on('submit', function(event) {
        event.preventDefault();
        let searchQuery = $('#search-input').val().trim();

        if (searchQuery !== '') {
            $.ajax({
                url     :   'search-game.php',
                method  :   'GET',
                data    :   { query: searchQuery },
                success :   function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    let cardContainer = $('#card-container');
                    cardContainer.empty(); // Limpiar los resultados anteriores

                    if (data.length > 0) {
                        data.forEach(function(item) {
                            let card = `
                                <div class="card">
                                    <img src="${item.thumbnail}" alt="Imagen">
                                    <div class="card-title" data-id="${item.id}">${item.title}</div>
                                    <div class="card-description">${item.short_description}</div>
                                </div>`;
                            cardContainer.append(card);
                        });
                    } else {
                        cardContainer.append('<p>No se encontraron resultados</p>');
                    }
                }
            });
        }
    });
});