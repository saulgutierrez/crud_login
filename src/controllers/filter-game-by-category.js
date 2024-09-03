$(document).ready(function () {
    $('#category-select').change(function () {
        var selectedCategory = $(this).val();

        if (selectedCategory) {
            $.ajax({
                url     :   '../src/models/fetch-games.php',
                tyoe    :   'GET',
                data    :   { category: selectedCategory },
                success :   function (response) {
                    $('#card-container').html(response);
                },
                error   :   function () {
                    alert('Error al cargar los juegos');
                }
            });
        }
    });
});