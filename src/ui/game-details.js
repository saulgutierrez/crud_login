$(document).ready(function () {
    // El contenedor escucha el evento, y lo delega al componente hijo, el titulo de un card,
    // por lo que aunque cambie el estado del DON, el evento sigue ejecutandose
    $('.card-container').on('click', '.card-title', function () {
        var gameId = $(this).data('id');
        window.location.href = '../src/views/game-details.php?id=' + gameId;
    });
});