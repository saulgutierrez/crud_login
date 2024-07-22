<?php
    require('../../config/connection.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Recuperar la ruta del archivo asociado al registro
        $sql = $conn->prepare("SELECT foto_comentario FROM comentarios WHERE id_comentario = ?");
        $sql->bind_param('i', $id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $file_path = $row['foto_comentario'];

            // Eliminar el archivo del servidor si existe
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Eliminar el registro de la base de datos
            $sql = $conn->prepare("DELETE FROM comentarios WHERE id_comentario = ?");
            $sql->bind_param('i', $id);

            if ($sql->execute()) {
                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            echo 'No se encontro el registro';
        }
        $sql->close();
    } else {
        echo 'error';
        $sql->close();
    }
?>