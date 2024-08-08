$(document).ready(function() {
    function loadPosts(category = '') {
        $.ajax({
            url: 'fetch-posts.php',
            method: 'POST',
            data: { category: category },
            success: function(data) {
                $('#registros').html(data);

                // Adjuntar eventos a los nuevos elementos
                $('.post-card').on('click', function() {
                    window.location.href = $(this).data('href');
                });

                $('.like-button').each(function() {
                    var id = $(this).data('id');
                    getLikeButtonState(this, id);
                });

                $('.like-button').on('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    var id = $(this).data('id');
                    toggleLike(this, id);
                });

                $('.like-count').on('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    var id_post = $(this).data('id');

                    $.ajax({
                        type: "POST",
                        url: "../models/load-likes.php",
                        data: { id_post: id_post },
                        success: function(response) {
                            console.log("Response from server:", response);
                            var likesList = $('#likesList');
                            likesList.empty();

                            try {
                                var users = JSON.parse(response);
                                console.log("Parsed users:", users);
                                if (Array.isArray(users)) {
                                    users.forEach(function(user) {
                                        var userLink;
                                        // Usamos la variable pasada desde PHP, que corresponde a nuestro id para evaluar:
                                        // Si nuestro id, es igual al id del campo liked_by de la tabla de likes,
                                        // redireccionamos a la pantalla para gestionar nuestro perfil.
                                        // En caso contrario, mostramos el perfil del usuario correspondiente.
                                        if (user.liked_by == authUserId) {
                                            userLink = '<a class="liked-usernames" href="profile.php?user='+user.usuario+'">'+ user.usuario +'</a>';
                                        } else {
                                            userLink = '<a class="liked-usernames" href="profile.php?id=' + user.liked_by + '">' + user.usuario + '</a>';
                                        }
                                        likesList.append('<li class="list-group-item">' + userLink + '</li>');
                                    });
                                } else {
                                    likesList.append('<li class="list-group-item">No likes yet</li>');
                                }
                            } catch (e) {
                                likesList.append('<li class="list-group-item">Error loading likes</li>');
                            }

                            $('#likesModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            alert('Error loading likes: ' + error);
                        }
                    });
                });
            }
        });
    }

    function toggleLike(button, id) {
        var isLiked = $(button).hasClass('liked-btn');
        $.ajax({
            type: "POST",
            url: "../models/toggle-like.php",
            data: { id: id, action: isLiked ? 'unlike' : 'like' },
            success: function(response) {
                if (isLiked) {
                    $(button).text('Like').removeClass('liked-btn');
                } else {
                    $(button).text('Liked').addClass('liked-btn');
                }
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
    }

    function getLikeButtonState(button, id) {
        $.ajax({
            url: '../models/get-like-button-state.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'liked') {
                    $(button).text('Liked').addClass('liked-btn');
                } else {
                    $(button).text('Like').removeClass('liked-btn');
                }
            },
            error: function(error) {
                console.log('Error recuperando el estado del botón', error);
            }
        });
    }

    // Cargar todos los posteos al cargar la página
    loadPosts();

    // Filtrar los posteos por categoría al hacer clic en las categorías
    $('#category-menu').on('click', 'summary a', function(e) {
        e.preventDefault();
        var category = $(this).data('category');
        // Eliminar la clase a la etiqueta <a> seleccionada
        $('#category-menu a').removeClass('selected-category');
        // Agregar la clase a la etiqueta a seleccionada
        $(this).addClass('selected-category');
        loadPosts(category);
    });

    setInterval(loadPosts, 60000); // Carga los posts cada minuto
});
