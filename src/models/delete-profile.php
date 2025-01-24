<?php
    require('../../config/connection.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['user'], $_POST['confirm-delete'])) {
        $user = $_POST['user'];
        $pass = $_POST['confirm-delete'];
        $id_user = $_POST['id_user'];

        # Evitar inyeccion SQL
        $user = htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8');
        $pass = htmlspecialchars($_POST['confirm-delete'], ENT_QUOTES, 'UTF-8');
        $id_user = htmlspecialchars($_POST['id_user'], ENT_QUOTES, 'UTF-8');

        $cryptPass = sha1($pass);

        $sql_check = "SELECT COUNT(*) AS count, fotografia FROM usuarios WHERE usuario = '$user' AND contrasenia = '$cryptPass'";
        $statement_check = $conn->prepare($sql_check);
        $statement_check->execute();
        $result_check = $statement_check->get_result();
        $row = $result_check->fetch_assoc();
        
        if ($row['count'] == 1) {
            // Obtener la ruta de la imagen de perfil
            $file_path = $row['fotografia'];
            // Ruta de la imagen por defecto
            $default_image_path = "../../public/img/profile-default.svg";

            // Eliminar el perfil del usuario
            $sql = "DELETE FROM usuarios WHERE usuario = '$user' AND contrasenia = '$cryptPass'";
            $result = $conn->query($sql);

            // Eliminamos la imagen del usuario del servidor en caso de que exista
            if (file_exists($file_path) && $file_path !== $default_image_path) {
                unlink($file_path);
            }

            // Eliminar fotografías de los posteos del usuario
            $sql2 = "SELECT foto_post FROM post WHERE autor_post = '$user'";
            $statement2 = $conn->prepare($sql2);
            $statement2->execute();
            $result2 = $statement2->get_result();
            while ($row2 = $result2->fetch_assoc()) {
                if (file_exists($row2['foto_post'])) {
                    unlink($row2['foto_post']);
                }
            }
            // Eliminar posteos del usuario
            $sql3 = "DELETE FROM post WHERE autor_post = '$user'";
            $result3 = $conn->query($sql3);

            // Eliminar fotografías de los comentarios del usuario
            $sql4 = "SELECT foto_comentario FROM comentarios WHERE autor_comentario = '$user'";
            $statement4 = $conn->prepare($sql4);
            $statement4->execute();
            $result4 = $statement4->get_result();
            while ($row4 = $result4->fetch_assoc()) {
                if (file_exists($row4['foto_comentario'])) {
                    unlink($row4['foto_comentario']);
                }
            }
            // Eliminar comentarios del usuario
            $sql5 = "DELETE FROM comentarios WHERE autor_comentario = '$user'";
            $result5 = $conn->query($sql5);

            // Eliminar comentarios en posteos del usuario
            $sql6 = "DELETE FROM comentarios WHERE id_autor = '$id_user'";
            $result6 = $conn->query($sql6);

            // Eliminar el usuario de la lista de seguidores de otros usuarios
            $sql7 = "DELETE FROM siguiendo WHERE id_seguido = '$id_user'";
            $result7 = $conn->query($sql7);

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