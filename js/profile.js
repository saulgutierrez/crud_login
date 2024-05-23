let info = document.querySelector('.info');
let posts = document.querySelector('.posts');
let infoPerfil = document.querySelector('.info-perfil');
let comments = document.querySelector('.comments');
const postsScreen = document.querySelectorAll('.post-card');
const postContent = document.querySelectorAll('.post-content');
const squareProfile = document.querySelectorAll('.square-menu-perfil');
const menu = document.querySelectorAll('.menu-opciones');
const menuIcon = document.querySelectorAll('.menu-icon');

    info.style.backgroundColor = "hsl(211, 100%, 50%)"; 
    info.style.borderRadius = "20px";

info.addEventListener("click", () => {
    info.style.backgroundColor = "hsl(211, 100%, 50%)"; 
    info.style.borderRadius = "20px";
    posts.style.backgroundColor = "hsl(240, 7%, 8%)";
    comments.style.backgroundColor = "hsl(240, 7%, 8%)";
    infoPerfil.style.display = "flex";
    postsScreen.forEach(element => {
        element.style.display = "none";
    });
    postContent.forEach(element => {
        element.style.display = "none";
    })
});

posts.addEventListener("click", () => {
    posts.style.backgroundColor = "hsl(211, 100%, 50%)";
    posts.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(240, 7%, 8%)";
    comments.style.backgroundColor = "hsl(240, 7%, 8%)";
    infoPerfil.style.display = "none";
    postsScreen.forEach(element => {
        element.style.display = "flex";
    });
    postContent.forEach(element =>  {
        element.style.display = "flex";
    });
});

comments.addEventListener("click", () => {
    comments.style.backgroundColor = "hsl(211, 100%, 50%)";
    comments.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(240, 7%, 8%)";
    posts.style.backgroundColor = "hsl(240, 7%, 8%)";
    infoPerfil.innerHTML = "";
});

menuIcon.forEach((elemento, index) => {
    const id = 'icono-' + (index + 1);
    elemento.id = id;
});

menu.forEach((elemento, index) => {
    const id = "menu-" + (index + 1);
    elemento.id = id;
});

squareProfile.forEach((elemento, index) => {
    const id = 'square-' + (index + 1);
    elemento.id = id;
});

document.addEventListener('DOMContentLoaded', () => {
    const iconos = document.querySelectorAll('.menu-icon');

    iconos.forEach(icono => {
        icono.addEventListener('click', () => {
            const iconoID = icono.id;

            const menuID = 'menu-' + iconoID.split('-')[1];
            const squareID = 'square-' + iconoID.split('-')[1];

            const menu = document.getElementById(menuID);
            const square = document.getElementById(squareID);

            if (menu.classList.contains('visible')) {
                menu.classList.remove('visible');
                
            } else {
                document.querySelectorAll('.menu-opciones').forEach(m => {
                    m.classList.remove('visible');
                });
                menu.classList.add('visible');
            }

            if (square.classList.contains('visible')) {
                square.classList.remove('visible');
            } else {
                document.querySelectorAll('.square-menu-perfil').forEach(s => {
                    s.classList.remove('visible');
                });
                square.classList.add('visible');
            }
        });
    });
});

// Obtener los elementos del DOM
var modal = document.getElementById("myModal");
var btn = document.getElementById("openModalBtn");
var span = document.getElementsByClassName("close")[0];
var form = document.getElementById("modalForm");

// Abrir el modal al hacer clic en el botón
btn.onclick = function(event) {
    event.preventDefault();
    modal.style.display = "block";
}

// Cerrar el modal al hacer clic en la 'X'
span.onclick = function() {
    modal.style.display = "none";
}

// Cerrar el modal al hacer clic fuera de la ventana modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Prevenir el comportamiento predeterminado del formulario al enviar
form.onsubmit = function(event) {
    event.preventDefault();
    // Aquí puedes agregar la lógica para manejar el formulario, como enviarlo mediante AJAX
    console.log("Formulario enviado");
    modal.style.display = "none"; // Cerrar el modal después de enviar el formulario
}