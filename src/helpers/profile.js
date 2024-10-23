let info = document.querySelector('.info');
let posts = document.querySelector('.posts');
let infoPerfil = document.querySelector('.info-perfil');
let comments = document.querySelector('.comments');
let following = document.querySelector('.following');
let photos = document.querySelector('.photos');
const followingScreen = document.querySelectorAll('.following-card');
const followingContent = document.querySelectorAll('.following-content');
const followers = document.querySelector('.followers');
const followerScreen = document.querySelectorAll('.follower-card');
const followerContent = document.querySelectorAll('.follower-content');
const likes = document.querySelector('.likes');
const likesScreen = document.querySelectorAll('.likes-card');
const likesContent = document.querySelectorAll('.likes-content');
const postsScreen = document.querySelectorAll('.post-card');
const postContent = document.querySelectorAll('.post-content');
const squareProfile = document.querySelectorAll('.square-menu-perfil');
const menu = document.querySelectorAll('.menu-opciones');
const menuIcon = document.querySelectorAll('.menu-icon');
const commentsScreen = document.querySelectorAll('.comment-card');
const commentsContent = document.querySelectorAll('.comment-content');
const menuIconComments = document.querySelectorAll('.menu-icon-comments');
const menuComments = document.querySelectorAll('.menu-opciones-comments');
const squareComments = document.querySelectorAll('.square-menu-perfil-comments')
const photosContent = document.querySelector('.photos-content');

    info.style.backgroundColor = "hsl(211, 100%, 50%)"; 
    info.style.borderRadius = "20px";
    photosContent.style.display = "none";

info.addEventListener("click", () => {
    info.style.backgroundColor = "hsl(211, 100%, 50%)"; 
    info.style.borderRadius = "20px";
    posts.style.backgroundColor = "hsl(210, 4%, 10%)";
    comments.style.backgroundColor = "hsl(210, 4%, 10%)";
    following.style.backgroundColor = "hsl(210, 4%, 10%)";
    followers.style.backgroundColor = "hsl(210, 4%, 10%)";
    likes.style.backgroundColor = "hsl(210, 4%, 10%)";
    photos.style.backgroundColor = "hsl(210, 4%, 10%)";
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
    followingScreen.forEach(element => {
        element.style.display = "none";
    });
    followingContent.forEach(element => {
        element.style.display = "none";
    });
    followerScreen.forEach(element => {
        element.style.display = "none";
    });
    followerContent.forEach(element => {
        element.style.display = "none";
    });
    likesScreen.forEach(element => {
        element.style.display = "none";
    })
    likesContent.forEach(element => {
        element.style.display = "none";
    });
    photosContent.style.display = "none";
});

posts.addEventListener("click", () => {
    posts.style.backgroundColor = "hsl(211, 100%, 50%)";
    posts.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(210, 4%, 10%)";
    comments.style.backgroundColor = "hsl(210, 4%, 10%)";
    following.style.backgroundColor = "hsl(210, 4%, 10%)";
    followers.style.backgroundColor = "hsl(210, 4%, 10%)";
    likes.style.backgroundColor = "hsl(210, 4%, 10%)";
    photos.style.backgroundColor = "hsl(210, 4%, 10%)";
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
    followingScreen.forEach(element => {
        element.style.display = "none";
    });
    followingContent.forEach(element => {
        element.style.display = "none";
    });
    followerScreen.forEach(element => {
        element.style.display = "none";
    });
    followerContent.forEach(element => {
        element.style.display = "none";
    });
    likesScreen.forEach(element => {
        element.style.display = "none";
    })
    likesContent.forEach(element => {
        element.style.display = "none";
    });
    photosContent.style.display = "none";
});

comments.addEventListener("click", () => {
    comments.style.backgroundColor = "hsl(211, 100%, 50%)";
    comments.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(210, 4%, 10%)";
    posts.style.backgroundColor = "hsl(210, 4%, 10%)";
    following.style.backgroundColor = "hsl(210, 4%, 10%)";
    followers.style.backgroundColor = "hsl(210, 4%, 10%)";
    likes.style.backgroundColor = "hsl(210, 4%, 10%)";
    photos.style.backgroundColor = "hsl(210, 4%, 10%)";
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
    });
    followingScreen.forEach(element => {
        element.style.display = "none";
    });
    followingContent.forEach(element => {
        element.style.display = "none";
    });
    followerScreen.forEach(element => {
        element.style.display = "none";
    });
    followerContent.forEach(element => {
        element.style.display = "none";
    });
    likesScreen.forEach(element => {
        element.style.display = "none";
    })
    likesContent.forEach(element => {
        element.style.display = "none";
    });
    photosContent.style.display = "none";
});

following.addEventListener("click", () => {
    following.style.backgroundColor = "hsl(211, 100%, 50%)";
    following.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(210, 4%, 10%)";
    posts.style.backgroundColor = "hsl(210, 4%, 10%)";
    comments.style.backgroundColor = "hsl(210, 4%, 10%)";
    followers.style.backgroundColor = "hsl(210, 4%, 10%)";
    likes.style.backgroundColor = "hsl(210, 4%, 10%)";
    photos.style.backgroundColor = "hsl(210, 4%, 10%)";
    infoPerfil.style.display = "none";
    followingScreen.forEach(element => {
        element.style.display = "flex";
    });
    followingContent.forEach(element => {
        element.style.display = "flex";
    });
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
    followerScreen.forEach(element => {
        element.style.display = "none";
    });
    followerContent.forEach(element => {
        element.style.display = "none";
    });
    likesScreen.forEach(element => {
        element.style.display = "none";
    })
    likesContent.forEach(element => {
        element.style.display = "none";
    });
    photosContent.style.display = "none";
});

followers.addEventListener("click", () => {
    followers.style.backgroundColor = "hsl(211, 100%, 50%)";
    followers.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(210, 4%, 10%)";
    posts.style.backgroundColor = "hsl(210, 4%, 10%)";
    comments.style.backgroundColor = "hsl(210, 4%, 10%)";
    following.style.backgroundColor = "hsl(210, 4%, 10%)";
    likes.style.backgroundColor = "hsl(210, 4%, 10%)";
    photos.style.backgroundColor = "hsl(210, 4%, 10%)";
    infoPerfil.style.display = "none";
    followerScreen.forEach(element => {
        element.style.display = "flex";
    });
    followerContent.forEach(element => {
        element.style.display = "flex";
    });
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
    followingScreen.forEach(element => {
        element.style.display = "none";
    });
    followingContent.forEach(element => {
        element.style.display = "none";
    });
    likesScreen.forEach(element => {
        element.style.display = "none";
    })
    likesContent.forEach(element => {
        element.style.display = "none";
    });
    photosContent.style.display = "none";
});

likes.addEventListener('click', () => {
    likes.style.backgroundColor = "hsl(211, 100%, 50%)";
    likes.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(210, 4%, 10%)";
    posts.style.backgroundColor = "hsl(210, 4%, 10%)";
    comments.style.backgroundColor = "hsl(210, 4%, 10%)";
    following.style.backgroundColor = "hsl(210, 4%, 10%)";
    followers.style.backgroundColor = "hsl(210, 4%, 10%)";
    photos.style.backgroundColor = "hsl(210, 4%, 10%)";
    infoPerfil.style.display = "none";
    likesScreen.forEach(element => {
        element.style.display = "flex";
    });
    likesContent.forEach(element => {
        element.style.display = "flex";
    });
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
    followingScreen.forEach(element => {
        element.style.display = "none";
    });
    followingContent.forEach(element => {
        element.style.display = "none";
    });
    followerScreen.forEach(element => {
        element.style.display = "none";
    });
    followerContent.forEach(element => {
        element.style.display = "none";
    });
    photosContent.style.display = "none";
});

menuIcon.forEach((elemento, index) => {
    const id = 'icono-' + (index + 1);
    elemento.id = id;
});

menuIconComments.forEach((elemento, index) => {
    const id = 'icono-comments-' + (index + 1);
    elemento.id = id;
})

menu.forEach((elemento, index) => {
    const id = "menu-" + (index + 1);
    elemento.id = id;
});

menuComments.forEach((elemento, index) => {
    const id = "menu-comments-" + (index + 1);
    elemento.id = id;
});

squareProfile.forEach((elemento, index) => {
    const id = 'square-' + (index + 1);
    elemento.id = id;
});

squareComments.forEach((elemeneto, index) => {
    const id = 'square-comments-' + (index + 1);
    elemeneto.id = id;
})

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

    const iconosComments = document.querySelectorAll('.menu-icon-comments');

    iconosComments.forEach(iconoComment => {
        iconoComment.addEventListener('click', () => {
            const iconoCommentID = iconoComment.id;

            const menuCommentID = 'menu-comments-' + iconoCommentID.split('-')[2];
            const squareCommentID = 'square-comments-' + iconoCommentID.split('-')[2];

            const menuComment = document.getElementById(menuCommentID);
            const squareComment = document.getElementById(squareCommentID);

            if (menuComment.classList.contains('visible')) {
                menuComment.classList.remove('visible');
            } else {
                document.querySelectorAll('.menu-opciones-comments').forEach(moc => {
                    moc.classList.remove('visible');
                });
                menuComment.classList.add("visible");
            }

            if (squareComment.classList.contains('visible')) {
                squareComment.classList.remove('visible');
            } else {
                document.querySelectorAll('.square-menu-perfil-comments').forEach (smpc => {
                    smpc.classList.remove('visible');
                });
                squareComment.classList.add('visible');
            }
        });
    });
});

photos.addEventListener('click', () => {
    photos.style.backgroundColor = "hsl(211, 100%, 50%)";
    photos.style.borderRadius = "20px";
    info.style.backgroundColor = "hsl(210, 4%, 10%)";
    posts.style.backgroundColor = "hsl(210, 4%, 10%)";
    comments.style.backgroundColor = "hsl(210, 4%, 10%)";
    following.style.backgroundColor = "hsl(210, 4%, 10%)";
    followers.style.backgroundColor = "hsl(210, 4%, 10%)";
    likes.style.backgroundColor = "hsl(210, 4%, 10%)";
    infoPerfil.style.display = "none";
    likesScreen.forEach(element => {
        element.style.display = "none";
    });
    likesContent.forEach(element => {
        element.style.display = "none";
    });
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
    followingScreen.forEach(element => {
        element.style.display = "none";
    });
    followingContent.forEach(element => {
        element.style.display = "none";
    });
    followerScreen.forEach(element => {
        element.style.display = "none";
    });
    followerContent.forEach(element => {
        element.style.display = "none";
    });

    photosContent.style.display = "flex";
});