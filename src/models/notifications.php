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

        if ($result->num_rows > 0) {
            // Crear la notificacion si el usuario sigue al que le dio like
            $mensaje = "A tu post le ha dado like el usuario ID $usuarioLike";
            $tipo = "like";

            $query = "INSERT INTO notificaciones(id, tipo_notificacion, mensaje, leida) VALUES(?, ?, ?, 0)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iss', $idUser, $tipo, $mensaje);
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

        if ($result->num_rows > 0) {
            // Crear la notificacion si el usuario sigue al que comento
            $mensaje = "Han comentado en tu post el usuario ID $usuarioComentario";
            $tipo = "comentario";

            $query = "INSERT INTO notificaciones(id, tipo_notificacion, mensaje, leida) VALUES(?, ?, ?, 0)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iss', $usuarioComentario, $tipo, $mensaje);
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

    // Funcion para marcar una notificacion como leida
    function marcar_notificacion_como_leida($notificacion_id) {
        global $conn;

        $query = "UPDATE notificaciones SET leida = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $notificacion_id);
        $stmt->execute();
    }
?>