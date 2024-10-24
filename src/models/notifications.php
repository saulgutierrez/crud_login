<?php
    require('../../config/connection.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    // Verificar si se esta solicitando la cantidad de notificaciones no leidas
    if (isset($_POST['action']) && $_POST['action'] == 'obtener_no_leidos') {
        // Obtener el ID del usuario de la sesión
        $user = $_SESSION['user'];
        $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = ?";
        $stmt = $conn->prepare($sqlGetIdUser);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $id_fetch = $result->fetch_assoc();
        $id = $id_fetch['id'];
        no_leidos($id); // Llamar a la función que obtiene la cantidad de notificaciones no leídas
    }

    // Al hacer click sobre el icono de la notificacion, se envia accion desde el fichero AJAX,
    // para recuperar y mostrar las notificaciones
    if (isset($_POST['action']) && $_POST['action'] == 'obtener_notificaciones') {
        // Obtener el ID del usuario de la sesión
        $user = $_SESSION['user'];
        $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = ?";
        $stmt = $conn->prepare($sqlGetIdUser);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $id_fetch = $result->fetch_assoc();
        $id = $id_fetch['id'];
        // obtener las notificaciones del usuario
        $notificaciones = obtener_notificaciones($id);

        // Generar el HTML de las notificaciones
        if (empty($notificaciones)) {
            echo "<div class='notification-container'>
                    <div>No hay notificaciones</div>
                </div>";
        } else {
            foreach ($notificaciones as $notificacion) {
                // Determinar la clase según el estado de la notificación
                $claseLeida = $notificacion['leida'] ? 'notificacion-leida' : 'notificacion-no-leida';

                echo "<a href='view-post.php?id={$notificacion['post_id']}&notif_id={$notificacion['id_notificacion']}' class='notification-container $claseLeida'>
                        <div>{$notificacion['mensaje']}</div>
                        <div>{$notificacion['fecha_notificacion']}</div>
                    </a>";
            }
        }
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
            $mensaje = "A $likeUsername le gusta tu publicación";
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
            $mensaje = "$commentFollowUsername comentó tu post";
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

        $query = "SELECT * FROM notificaciones WHERE id = ? ORDER BY fecha_notificacion DESC";
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

    // Funcion para obtener el numero de notificaciones no leidas
    function no_leidos($id) {
        global $conn;
        
        $query = "SELECT COUNT(*) AS unread_count FROM notificaciones WHERE id = ? AND leida = 0";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        echo json_encode(['unread_count' => $row['unread_count'],
                            "usuario_id" => $id
                        ]);
    }