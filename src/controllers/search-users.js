$(document).ready(function () {
    // Ejecutar la búsqueda cuando el usuario escribe en el campo
    $('#searchQuery').on("input", function () {
        performSearch();
    });

    // Ejecutar la búsqueda cuando se hace clic en el boton de busqueda
    $('#searchForm').on("submit", function () {
        performSearch();
    });

    function performSearch() {
        let query = $('#searchQuery').val();

        // Evitar busquedas con campos vacios
        if (query.length > 0) {
            $.ajax({
                url     :   '../models/search-user.php',
                type    :   'GET',
                data    :   { query: query },
                success  :   function (data) {
                    $('#searchResults').html(data);
                },
                error   :   function (xhr, status, error) {
                    console.log("Error en la busqueda: " + error);
                }
            });
        } else {
            // Limpiar los resultados si el campo esta vacio
            $('#searchResults').empty();
        }
    }
});