<?php
    require('../../config/connection.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    function notificar_like($post_id, $usuarioLike) {
        global $conn;
        // Obtener el ID del usuario dueño del post
        $query = "SELECT id_autor FROM post WHERE id_post = ?";
        $statement = $conn->prepare($query);
        $statement->bind_param("i", $post_id);
        $statement->execute();
        $result = $statement->get_result();
        $post = $result->fetch_assoc();
        $idUser = $post['id_autor'];

        // Verificar si el usuario dueño del post sigue al usuario que dio like
        $query = "SELECT * FROM siguiendo WHERE id_seguidor = ? AND id_seguido = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $idUser, $usuarioLike);
        $stmt->execute();
        $result = $stmt->get_result();

        // Obtenemos el nombre de usuario para incorporarlo en la notificacion
        if ($result->num_rows > 0) {
            $getUsernameFollowQuery = "SELECT usuario FROM usuarios WHERE id = ?";
            $statementGetUsernameFollow = $conn->prepare($getUsernameFollowQuery);
            $statementGetUsernameFollow->bind_param("i", $usuarioLike);
            $statementGetUsernameFollow->execute();
            $resultGetUsernameFollow = $statementGetUsernameFollow->get_result();
            while ($rowGetUsernameFollow = $resultGetUsernameFollow->fetch_assoc()) {
                $likeUsername = $rowGetUsernameFollow['usuario'];
            }

            // Crear la notificacion si el usuario sigue al que le dio like
            $mensaje = "A tu post le ha dado like el usuario $likeUsername";
            $tipo = "like";

            $query = "INSERT INTO notificaciones(id, tipo_notificacion, post_id, mensaje, leida) VALUES(?, ?, ?, ?, 0)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('isis', $idUser, $tipo, $post_id, $mensaje);
            $stmt->execute();
        }
    }

    function notificar_comentario($post_id, $usuarioComentario) {
        global $conn;

        // Obtener el id del usuario dueño del post
        $query = "SELECT id_autor FROM post WHERE id_post = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        $idUser = $post['id_autor'];

        // Verificar si el usuario dueño del post sigue al usuario que comento
        $query = "SELECT * FROM siguiendo WHERE id_seguidor = ? AND id_seguido = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $idUser, $usuarioComentario);
        $stmt->execute();
        $result = $stmt->get_result();

        // Obtenemos el nombre de usuario para incorporarlo a la notificacion
        if ($result->num_rows > 0) {
            $getUsernameFollowQueryComment = "SELECT usuario FROM usuarios WHERE id = ?";
            $statementGetUsernameFollowComment = $conn->prepare($getUsernameFollowQueryComment);
            $statementGetUsernameFollowComment->bind_param("i", $usuarioComentario);
            $statementGetUsernameFollowComment->execute();
            $resultGetUsernameFollowComment = $statementGetUsernameFollowComment->get_result();
            while ($rowGetUsernameFollowComment = $resultGetUsernameFollowComment->fetch_assoc()) {
                $commentFollowUsername = $rowGetUsernameFollowComment['usuario'];
            }
            // Crear la notificacion si el usuario sigue al que comento
            $mensaje = "Han comentado en tu post el usuario $commentFollowUsername";
            $tipo = "comentario";

            $query = "INSERT INTO notificaciones(id, tipo_notificacion, post_id, mensaje, leida) VALUES(?, ?, ?, ?, 0)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('isis', $idUser, $tipo, $post_id, $mensaje);
            $stmt->execute();
        }
    }

    // Funcion para obtener las notificaciones de un usuario
    function obtener_notificaciones($usuario_id) {
        global $conn;

        $query = "SELECT * FROM notificaciones WHERE id = ? AND leida = 0 ORDER BY fecha_notificacion DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }

        return $notificaciones;
    }