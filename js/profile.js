let info = document.querySelector('.info');
let posts = document.querySelector('.posts');
let infoPerfil = document.querySelector('.info-perfil');
let comments = document.querySelector('.comments');
const postsScreen = document.getElementById('post-card');

    info.style.backgroundColor = "hsl(211, 100%, 50%)"; 
    info.style.borderRadius = "20px";

info.addEventListener("click", () => {
    info.style.backgroundColor = "hsl(211, 100%, 50%)"; 
    info.style.borderRadius = "20px";
    posts.style.backgroundColor = "hsl(240, 7%, 8%)";
    comments.style.backgroundColor = "hsl(240, 7%, 8%)";
    infoPerfil.style.display = "flex";
    postsScreen.style.display = "none";
});

posts.addEventListener("click", () => {
    posts.style.backgroundColor = "hsl(211, 100%, 50%)";
    posts.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(240, 7%, 8%)";
    comments.style.backgroundColor = "hsl(240, 7%, 8%)";
    postsScreen.style.display = "flex";
    infoPerfil.style.display = "none";
});

comments.addEventListener("click", () => {
    comments.style.backgroundColor = "hsl(211, 100%, 50%)";
    comments.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(240, 7%, 8%)";
    posts.style.backgroundColor = "hsl(240, 7%, 8%)";
    infoPerfil.innerHTML = "";

})