function responsiveDesign() {
    if (window.innerWidth <= 768) {
        let newPost = document.querySelector('.new-post');
        let identifier = document.querySelector('.identifier');
        let profile = document.querySelector('.imgBox');
        let dropdown = document.querySelector('.dropdown');
        let square = document.querySelector('.square');

        newPost.classList.add('no-visible');
        identifier.style.display = "none";

        profile.addEventListener('click', function () {
            dropdown.classList.toggle('visible');
            square.classList.toggle('visible');
            newPost.classList.toggle('no-visible')
            newPost.classList.toggle('visibleNewPost');
        });
    } else {
        // Mostrar/ocultar dropdown de gestion de perfil
        let square = document.querySelector('.square');
        let dropdown = document.querySelector('.dropdown');
        let identifier = document.querySelector(".identifier");
        let newPost = document.querySelector('.new-post');

        identifier.style.display = "flex";

        identifier.addEventListener("mouseover", function() {
            square.style.display = "flex";
            dropdown.style.display = "block";
        });

        dropdown.addEventListener("mouseleave", function() {
            square.style.display = "none";
            dropdown.style.display = "none";
        });
    }
}

window.addEventListener('resize', responsiveDesign);
responsiveDesign();