<?php
    require('connection.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['user'], $_POST['confirm-delete'])) {
        $user = $_POST['user'];
        $pass = $_POST['confirm-delete'];
        $id_user = $_POST['id_user'];   

        $cryptPass = sha1($pass);

        $sql_check = "SELECT COUNT(*) AS count, fotografia FROM usuarios WHERE usuario = '$user' AND contrasenia = '$cryptPass'";
        $statement_check = $conn->prepare($sql_check);
        $statement_check->execute();
        $result_check = $statement_check->get_result();
        $row = $result_check->fetch_assoc();
        
        if ($row['count'] == 1) {
            // Obtener la ruta de la imagen de perfil
            $file_path = $row['fotografia'];

            // Eliminar el perfil del usuario
            $sql = "DELETE FROM usuarios WHERE usuario = '$user' AND contrasenia = '$cryptPass'";
            $result = $conn->query($sql);

            // Eliminamos la imagen del usuario del servidor en caso de que exista
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Eliminar posteos del usuario
            $sql2 = "DELETE FROM post WHERE autor_post = '$user'";
            $result2 = $conn->query($sql2);

            // Eliminar comentarios del usuario
            $sql3 = "DELETE FROM comentarios WHERE autor_comentario = '$user'";
            $result3 = $conn->query($sql3);

            // Eliminar comentarios en posteos del usuario
            $sql4 = "DELETE FROM comentarios WHERE id_autor = '$id_user'";
            $result4 = $conn->query($sql4);

            session_destroy();
            echo 0;
        } else {
            echo 1;
        }
    } else {
        echo "Error de comunicacion con el servidor";
        $conn->close();
    }
?>