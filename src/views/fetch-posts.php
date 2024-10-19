<?php
    require('../../config/connection.php');
    require('../models/session.php');
    require '../../public/libs/Carbon/autoload.php';

    use Carbon\Carbon;
    Carbon::setLocale('es');

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
        $sql = "SELECT p.id_post, p.id_autor, p.autor_post, p.titulo_post, p.contenido_post, p.foto_post, p.fecha_publicacion, u.fotografia FROM post p JOIN usuarios u ON p.id_autor = u.id WHERE p.autor_post != '$username' AND p.id_categoria = '$category' ORDER BY fecha_publicacion DESC";
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
        $fecha = Carbon::parse($row['fecha_publicacion']);
        $fechaFormateada = $fecha->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm a');
        $foto_perfil = $row['fotografia'];

        // Consulta para obtener el numero de "likes" de cada post
        $likes_sql = "SELECT COUNT(*) as like_count FROM likes WHERE liked_id_post = '$id_post'";
        $likes_result = $conn->query($likes_sql);
        $likes_row = $likes_result->fetch_assoc();
        $like_count = $likes_row['like_count'];

        // Consulta para obtener el numero de comentarios de cada post
        $comments_sql = "SELECT COUNT(*) as comment_count FROM comentarios WHERE id_post = '$id_post'";
        $comments_result = $conn->query($comments_sql);
        $comments_row = $comments_result->fetch_assoc();
        $comment_count = $comments_row['comment_count'];


        // Mostramos los registros usando PHP, para actualizar la base de datos de forma dinámica, sin necesidad de
        // recargar la página
        echo '<div class="post-card" data-href="view-post.php?id='.$id_post.'">';
        echo '<div class="post-card-top">';
        echo '<div class="post-card-top-main-content">';
        echo '<div class="imgBoxProfileImage"><img src="'. $foto_perfil .'"></div>';
        echo '<h2><a href="profile.php?id=' . $id . '" data-id="' . $id . '" data-autor="' . $autor . '" data-foto="' . $foto_perfil . '" onclick="event.stopPropagation();">' . $autor . '</a></h2>';
        echo '</div>';
        echo '<div class="fecha">' . $fecha->diffForHumans() . '</div>';
        echo '<div class="fecha-formateada">' . $fechaFormateada . '</div>';
        echo '</div>';
        echo '<div class="imgBoxComment">';
        echo '<img src="../../public/svg/comment.svg">';
        echo '</div>';
        echo '<a class="like-button" data-id="'.$id_post.'">Like</a>';
        echo '<div class="comment-count">'. $comment_count .'</div>';
        echo '<a href="#" class="like-count" data-id="'.$id_post.'" data-toggle="modal" data-target="#likesModal">'.$like_count.'</a>';
        echo '<div class="imgBoxLike">';
        echo '<img src="../../public/svg/heart.svg">';
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