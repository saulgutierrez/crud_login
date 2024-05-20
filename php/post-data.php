<?php
    require('conexion.php');

    # Solucion a error: "ya habia iniciado una sesion ignorando session_start()"
    if (!isset($_SESSION)) {
        session_start();
    }
    
    # Si se recibieron datos desde el frontend, los almacenamos para consulta
    if (isset($_POST['id_user'], $_POST['post_title'], $_POST['post_content'])) {
        $idPerfil = $_POST['id_user'];
        $username = $_POST['user'];
        $postTitle = $_POST['post_title'];
        $postContent = $_POST['post_content'];

        $sql = "INSERT INTO post (id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post) VALUES ('', '$idPerfil', '$username', '$postTitle', '$postContent', '')";

        if ($conn->query($sql) == TRUE) {
            echo 0; # Insercion con exito
        } else {
            echo 1; # No se ejecuto la sentencia SQL
        }
        $conn->close();
    } else {
        echo 2; # No se recibieron los datos desde el frontend
        $conn->close();
    }
?>