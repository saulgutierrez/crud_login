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

    $sql = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE autor_post != '$username' ORDER BY fecha_publicacion DESC";
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
        $hasImage = !empty($foto) ? 'imgBox' : 'noImage';
        $fecha = $row['fecha_publicacion'];
        // Mostramos los registros usando PHP, para actualizar la base de datos de forma dinámica, sin necesidad de
        // recargar la página
        echo '<div class="post-card" data-href="view-post.php?id='.$id_post.'">';
        echo '<div class="post-card-top">';
        echo '<h2><a href="profile.php?id=' . $id . '" onclick="event.stopPropagation();">' . $autor . '</a></h2>';
        echo '<div>' . $fecha . '</div>';
        echo '<a class="like-button" data-id="'.$id_post.'">Like</a>';
        echo '</div>';
        echo '<h3>' . $titulo . '</h3>';
        echo '<div>' . $contenido . '</div>';
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