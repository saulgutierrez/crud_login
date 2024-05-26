<?php
    require('conexion.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['comment-input'])) {
        $idPost = $_POST['id-post'];
        $idAutorPost = $_POST['id-autor-post'];
        $autorComentario = $_POST['autor-comentario'];
        $comentario = $_POST['comment-input'];

        $sql = "INSERT INTO comentarios (id_post, id_autor, id_comentario, autor_comentario, comentario) VALUES ('$idPost', '$idAutorPost', '', '$autorComentario', '$comentario')";

        if ($conn->query($sql) == TRUE) {
            echo 0; # Insercion con exito
        } else {
            echo 1; # No se ejecutó la senetencia SQL
        }
        $conn->close();
    } else {
        echo 2;
        $conn->close(); # Los datos no llegaron desde el frontend
    }
?>