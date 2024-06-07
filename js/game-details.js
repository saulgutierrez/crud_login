$(document).ready(function () {
    $('.card-title').click(function () {
        var gameId = $(this).data('id');
        window.location.href = 'php/game-details.php?id=' + gameId;
    });
});