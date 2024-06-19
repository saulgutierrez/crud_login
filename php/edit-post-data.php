<?php
    require('connection.php');

    # Solucion a error: "ya habia iniciado una sesion ignorando session_start()"
    if (!isset($_SESSION)) {
        session_start();
    }
    
    # Si se recibieron datos desde el frontend, los almacenamos para consulta
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
        $id_post = $_POST['id_post'];
        $id_user = $_POST['id_user'];
        $post_title = $_POST['post_title'];
        $post_content = $_POST['post_content'];

        $sql = "UPDATE post SET titulo_post = '$post_title', contenido_post = '$post_content' WHERE id_post = '$id_post'";
        $result = $conn->query($sql);

        if ($result == TRUE) {
            echo 0;
        } else {
            echo 1;
        }
        $conn->close();
    } else {
        echo 2;
    }
?>