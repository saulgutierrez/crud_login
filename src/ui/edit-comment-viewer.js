document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("myModal");
    const commentInput = document.getElementById("commentInput");
    const commentImage = document.getElementById("commentImage");
    const newCommentImage = document.getElementById("newCommentImage");
    const closeButton = modal.querySelector(".close");
    const commentIdInput = document.getElementById('commentIdInput');
    const closeIcon = document.getElementById('close-icon');

    const openModalLinks = document.querySelectorAll(".openModalLink");

    openModalLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            const commentId = this.getAttribute('data-id');
            const commentText = this.getAttribute('data-comment');
            const imagePath = this.getAttribute('data-image');

            commentIdInput.value = commentId;
            commentInput.value = commentText;

            if (imagePath) {
                commentImage.src = imagePath;
                commentImage.style.display = "block";
            } else {
                commentImage.style.display = "none";
            }

            modal.style.display = "block";
        });
    });

    // Escucha cambios en el campo de archivo
    newCommentImage.addEventListener("change", function () {
        const file = newCommentImage.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                commentImage.src = e.target.result;
                commentImage.style.display = "block";
                closeIcon.style.display = "flex";
            };
            reader.readAsDataURL(file); // Lee el archivo y muestra la vista previa
        }
    });

    closeButton.onclick = function () {
        modal.style.display = "none";
    };

    closeIcon.addEventListener('click', function () {
        commentImage.src = "";
        newCommentImage.style.display = "none";
        this.style.display = "none";
        newCommentImage.value = "";
        commentImage.alt = "";
    });

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
});
