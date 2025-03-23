const suggestionsBtn = document.querySelector('#suggestions-btn');
const recordsBtn = document.querySelector('#all-btn');
const records = document.querySelector('#registros');

suggestionsBtn.addEventListener("click", function () {
    $.ajax({
        url: '../models/run-knn-post-suggestions.php',
        type: 'POST',
        data: { usuario_id: $(this).data('id') },
        dataType: 'json',
        success: function (response) {
            $('#suggestions').html("");
            if (response.length > 0) {
                records.style.display = "none";
                suggestions.style.display = "flex";
                suggestionsBtn.classList.add('suggestions-btn');
                recordsBtn.classList.remove('all-btn');
                response.forEach(function (post) {
                    $('#suggestions').append(`
                        <div class="recommendation" onclick="location.href='view-post.php?id=${post.id_post}'">
                            <div class="authorContainer">
                                <div class="imgBox">
                                    <img src="${post.fotografia}" alt="Avatar">
                                </div>
                                <div class="username" onclick="event.stopPropagation(); location.href='profile.php?id=${post.id_autor}'">${post.autor_post}</div>
                            </div>
                            <div class="card-body">
                                <h3>${post.titulo_post}</h3>
                                <p>${post.contenido_post}</p>
                            </div>
                        </div>
                    `);
                });
            } else {
                $('#suggestions').append(`
                    <div class="alert alert-warning" role="alert">
                        No se encontraron sugerencias para este usuario.
                    </div>
                `);
            }
        },
        error: function (error) {
            console.log(error);
        }
    });

});

recordsBtn.addEventListener("click", function () {
    const container = document.getElementById("suggestions");
    const suggestionsBtn = document.querySelector('#suggestions-btn');
    this.classList.add('all-btn');
    suggestionsBtn.classList.remove('suggestions-btn');
    container.style.display = "none";
    records.style.display = "flex";
});