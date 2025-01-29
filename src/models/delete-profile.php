<?php
    require('../../config/connection.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    header('Content-Type: application/json');
    $response = array();

    if (isset($_POST['user'], $_POST['confirm-delete'], $_POST['id_user'])) {
        $user = $_POST['user'];
        $pass = $_POST['confirm-delete'];
        $id_user = $_POST['id_user'];
    
        // Escapar valores y cifrar la contraseña
        $cryptPass = sha1($pass);
    
        // Comprobar si el usuario y la contraseña son válidos
        $sql_check = "SELECT COUNT(*) AS count, fotografia FROM usuarios WHERE usuario = ? AND contrasenia = ?";
        $statement_check = $conn->prepare($sql_check);
        $statement_check->bind_param("ss", $user, $cryptPass);
        $statement_check->execute();
        $result_check = $statement_check->get_result();
        $row = $result_check->fetch_assoc();
    
        if ($row['count'] == 1) {
            // Obtener la ruta de la imagen de perfil
            $file_path = $row['fotografia'];
            $default_image_path = "../../public/img/profile-default.svg";

            // Eliminar fotografías de los posteos del usuario
            $sql_get_photo_post = "SELECT foto_post FROM post WHERE id_autor = ?";
            $statement_get_posts = $conn->prepare($sql_get_photo_post);
            $statement_get_posts->bind_param("i", $id_user);
            $statement_get_posts->execute();
            $result_posts = $statement_get_posts->get_result();
            while ($row_post = $result_posts->fetch_assoc()) {
                if (file_exists($row_post['foto_post'])) {
                    unlink($row_post['foto_post']);
                }
            }
    
            // Eliminar el perfil del usuario
            $sql_delete_user = "DELETE FROM usuarios WHERE usuario = ? AND contrasenia = ?";
            $statement_delete_user = $conn->prepare($sql_delete_user);
            $statement_delete_user->bind_param("ss", $user, $cryptPass);
            $statement_delete_user->execute();
    
            // Eliminar la imagen de perfil del servidor si no es la predeterminada
            if (file_exists($file_path) && $file_path !== $default_image_path) {
                unlink($file_path);
            }

            // Eliminar posteos del usuario
            $sql_delete_posts = "DELETE FROM post WHERE autor_post = ?";
            $statement_delete_posts = $conn->prepare($sql_delete_posts);
            $statement_delete_posts->bind_param("s", $user);
            $statement_delete_posts->execute();
    
            // Eliminar fotografías de los comentarios del usuario
            $sql_get_comments = "SELECT foto_comentario FROM comentarios WHERE autor_comentario = ?";
            $statement_get_comments = $conn->prepare($sql_get_comments);
            $statement_get_comments->bind_param("s", $user);
            $statement_get_comments->execute();
            $result_comments = $statement_get_comments->get_result();
            while ($row_comment = $result_comments->fetch_assoc()) {
                if (file_exists($row_comment['foto_comentario'])) {
                    unlink($row_comment['foto_comentario']);
                }
            }

            // Eliminar comentarios del usuario
            $sql_delete_comments = "DELETE FROM comentarios WHERE autor_comentario = ?";
            $statement_delete_comments = $conn->prepare($sql_delete_comments);
            $statement_delete_comments->bind_param("s", $user);
            $statement_delete_comments->execute();
    
            // Eliminar comentarios en posteos del usuario
            $sql_delete_post_comments = "DELETE FROM comentarios WHERE id_autor = ?";
            $statement_delete_post_comments = $conn->prepare($sql_delete_post_comments);
            $statement_delete_post_comments->bind_param("i", $id_user);
            $statement_delete_post_comments->execute();
    
            // Eliminar al usuario de la lista de seguidores
            $sql_delete_following = "DELETE FROM siguiendo WHERE id_seguido = ?";
            $statement_delete_following = $conn->prepare($sql_delete_following);
            $statement_delete_following->bind_param("i", $id_user);
            $statement_delete_following->execute();
    
            // Finalizar sesión y responder
            session_destroy();
            echo 0;
        } else {
            echo 1;
        }
    } else {
        echo "Error de comunicación con el servidor";
    }
    
    $conn->close();
    
?>