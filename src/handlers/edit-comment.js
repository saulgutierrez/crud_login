document.getElementById("saveComment").onclick = function (event) {
    event.preventDefault();
    const formData = new FormData();
    formData.append("commentText", commentInput.value);
    formData.append("commentId", commentIdInput.value);

    if (newCommentImage.files[0]) {
        formData.append("newCommentImage", newCommentImage.files[0]);
    }

    // Peticion al backend para enviar los datos del formulario al endpoint
    fetch("../models/edit-comment.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    // Peticion al backend para actualizar el contenedor del comentario en el frontend
    .then(data => {
        const messageContainer = document.getElementById(`comentarioItem${commentIdInput.value}`);
        const modal = document.getElementById("myModal");
        
        if (messageContainer) {
            messageContainer.textContent = data.comment;
            modal.style.display = "none";
        }

        return fetch(`../models/get-comment.php?commentId=${commentIdInput.value}`);
    })
    .then(response => response.json())
    // Petición al backend para actualizar el campo de edicion de comentario de forma dinamica
    .then(updatedData => {
        commentInput.value = updatedData.commentText;
    })
    .then(response.text())
    .then(commentsHtml => {
        const commentContent = document.getElementById("comment-content");
        if (commentContent) {
            commentContent.innerHTML = commentsHtml;
        } else {
            console.error("No se encontró el contenedor #comment-content");
        }
    })
    .catch(error => console.error("Error:", error));
};
