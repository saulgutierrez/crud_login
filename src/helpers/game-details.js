$(document).ready(function () {
    $('.card-title').click(function () {
        var gameId = $(this).data('id');
        window.location.href = '../src/views/game-details.php?id=' + gameId;
    });
});