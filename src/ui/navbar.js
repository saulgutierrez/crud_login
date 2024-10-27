document.querySelector('.menu').addEventListener('click', function () {
    document.querySelectorAll('.navbar li:not(.menu)').forEach(function (item) {
        item.classList.toggle('hidden');
    });
});

let squareMenu = document.querySelector('.square-menu');
let menuOpciones = document.querySelector('.menu-opciones');

document.querySelector('.menu').addEventListener('click', function () {
    squareMenu.classList.toggle('hidden');
});

document.querySelector('.menu').addEventListener('click', function () {
    menuOpciones.classList.toggle('hidden');
})