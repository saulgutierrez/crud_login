let info = document.querySelector('.info');
let posts = document.querySelector('.posts');
let infoPerfil = document.querySelector('.info-perfil');
let comments = document.querySelector('.comments');
const postsScreen = document.querySelectorAll('.post-card');
const postContent = document.querySelectorAll('.post-content');

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