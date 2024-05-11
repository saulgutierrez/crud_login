// Mostrar/ocultar dropdown de gestion de perfil
let square = document.querySelector('.square');
let dropdown = document.querySelector('.dropdown');
let identifier = document.querySelector(".identifier");

identifier.addEventListener("mouseover", function() {
    square.style.display = "flex";
    dropdown.style.display = "block"
});

dropdown.addEventListener("mouseleave", function() {
    square.style.display = "none";
    dropdown.style.display = "none";
});