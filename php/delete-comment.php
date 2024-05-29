<?php
    require('conexion.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM comentarios WHERE id_comentario = '$id'";
        $result = $conn->query($sql);

        if ($result == TRUE) {
            echo 'success';
        } else {
            echo 'error';
        }
        $conn->close();
    } else {
        echo 'error';
        $conn->close();
    }
?>