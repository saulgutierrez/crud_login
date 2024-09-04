<?php
    require('../../config/connection.php');
    require('../models/data.php');
    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: ../index.php');
         exit();
     }
     # Si existe, tomamos su nombre de usuario
    $username = $_SESSION['user'];
    # Si llego una categoria desde el frontend, la capturamos y filtramos los registros en consecuencia
    # En caso contrario, mostramos todos los registros.
    $category = isset($_POST['category']) ? $_POST['category'] : '';

    if (!empty($category)) {
        $sql = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE autor_post != '$username' AND id_categoria = '$category' ORDER BY fecha_publicacion DESC";
    } else {
        $sql = "SELECT p.id_post, p.id_autor, p.autor_post, p.titulo_post, p.contenido_post, p.foto_post, p.fecha_publicacion, u.fotografia FROM post p JOIN usuarios u ON p.id_autor = u.id WHERE p.autor_post != '$username' ORDER BY p.fecha_publicacion DESC";
    }
    $result = $conn->query($sql);

    // Verificar si hay resultados
if ($result->num_rows > 0) {
    $counter = 0;
    while ($row = $result->fetch_assoc()) {
        $counter++;
        $id_post = $row['id_post'];
        $id = $row['id_autor'];
        $autor = $row['autor_post'];
        $titulo = $row['titulo_post'];
        $contenido = $row['contenido_post'];
        $foto = $row['foto_post'];
        $hasImage = !empty($foto) ? 'imgBoxPost' : 'noImage';
        $fecha = $row['fecha_publicacion'];
        $foto_perfil = $row['fotografia'];

        // Consulta para obtener el numero de "likes" de cada post
        $likes_sql = "SELECT COUNT(*) as like_count FROM likes WHERE liked_id_post = '$id_post'";
        $likes_result = $conn->query($likes_sql);
        $likes_row = $likes_result->fetch_assoc();
        $like_count = $likes_row['like_count'];


        // Mostramos los registros usando PHP, para actualizar la base de datos de forma dinámica, sin necesidad de
        // recargar la página
        echo '<div class="post-card" data-href="view-post.php?id='.$id_post.'">';
        echo '<div class="post-card-top">';
        echo '<div class="post-card-top-main-content">';
        echo '<div class="imgBoxProfileImage"><img src="'. $foto_perfil .'"></div>';
        echo '<h2><a href="profile.php?id=' . $id . '" onclick="event.stopPropagation();">' . $autor . '</a></h2>';
        echo '</div>';
        echo '<div>' . $fecha . '</div>';
        echo '<a class="like-button" data-id="'.$id_post.'">Like</a>';
        echo '<a href="#" class="like-count" data-id="'.$id_post.'" data-toggle="modal" data-target="#likesModal">'.$like_count.'</a>';
        echo '</div>';
        echo '<h3>' . $titulo . '</h3>';
        echo '<div class="text-content">' . $contenido . '</div>';
        echo '<div class="' . $hasImage . '">';
        if (!empty($foto)) {
            echo '<img src="' . $foto . '" alt="">';
        }
        echo '</div>';
        echo '</div>';
    }
} else {
    echo 'No hay registros';
}

// Cerrar conexión
$conn->close();
?>