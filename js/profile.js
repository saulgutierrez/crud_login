let info = document.querySelector('.info');
let posts = document.querySelector('.posts');
let infoPerfil = document.querySelector('.info-perfil');
let comments = document.querySelector('.comments');
const postsScreen = document.querySelectorAll('.post-card');
const postContent = document.querySelectorAll('.post-content');
const squareProfile = document.querySelectorAll('.square-menu-perfil');
const menu = document.querySelectorAll('.menu-opciones');
const menuIcon = document.querySelectorAll('.menu-icon');
const commentsScreen = document.querySelectorAll('.comment-card');
const commentsContent = document.querySelectorAll('.comment-content');

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
    });
    commentsScreen.forEach(element => {
        element.style.display = "none";
    });
    commentsContent.forEach(element => {
        element.style.display = "none";
    });
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
    commentsScreen.forEach(element => {
        element.style.display = "none";
    });
    commentsContent.forEach(element => {
        element.style.display = "none";
    });
});

comments.addEventListener("click", () => {
    comments.style.backgroundColor = "hsl(211, 100%, 50%)";
    comments.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(240, 7%, 8%)";
    posts.style.backgroundColor = "hsl(240, 7%, 8%)";
    infoPerfil.style.display = "none";
    commentsScreen.forEach(element => {
        element.style.display = "flex";
    });
    commentsContent.forEach(element => {
        element.style.display = "flex";
    });
    postsScreen.forEach(element => {
        element.style.display = "none";
    });
    postContent.forEach(element => {
        element.style.display = "none";
    })

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