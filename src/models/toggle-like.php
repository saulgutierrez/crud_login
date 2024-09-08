<?php
require('../../config/connection.php');
require('session.php');
require('notifications.php');

if (!isset($_SESSION)) {
    session_start();
}

$username = $_SESSION['user'];

// Obtener el ID del usuario
$sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = ?";
$stmtGetIdUser = $conn->prepare($sqlGetIdUser);
$stmtGetIdUser->bind_param("s", $username);
$stmtGetIdUser->execute();
$result = $stmtGetIdUser->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idUser = $row['id'];
}

if (isset($_POST['id']) && isset($_POST['action']) && isset($_SESSION['user'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    // Obtener detalles del post
    $sqlGetPostDetails = "SELECT autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE id_post = ?";
    $stmtGetPostDetails = $conn->prepare($sqlGetPostDetails);
    $stmtGetPostDetails->bind_param("i", $id);
    $stmtGetPostDetails->execute();
    $getPostDetailsQuery = $stmtGetPostDetails->get_result();

    if ($getPostDetailsQuery->num_rows > 0) {
        $rowGetPostDetails = $getPostDetailsQuery->fetch_assoc();
        $autorLikedPost = $rowGetPostDetails['autor_post'];
        $tituloLikedPost = $rowGetPostDetails['titulo_post'];
        $contenidoLikedPost = $rowGetPostDetails['contenido_post'];
        $fotoLikedPost = $rowGetPostDetails['foto_post'];
        $fechaPublicacionLikedPost = $rowGetPostDetails['fecha_publicacion'];
    }

    if ($action === 'like') {
        // Verificar si ya existe un like
        $sqlCheckLike = "SELECT * FROM likes WHERE liked_by = ? AND liked_id_post = ?";
        $stmtCheckLike = $conn->prepare($sqlCheckLike);
        $stmtCheckLike->bind_param("ii", $idUser, $id);
        $stmtCheckLike->execute();
        $resultCheckLike = $stmtCheckLike->get_result();

        if ($resultCheckLike->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Ya has dado like a este post']);
        } else {
            // Si no existe, insertar el like
            $sqlInsertLike = "INSERT INTO likes (liked_by, liked_id_post, autor_liked_post, titulo_liked_post, contenido_liked_post, foto_liked_post, fecha_publicacion_liked_post, btn_text) VALUES(?, ?, ?, ?, ?, ?, ?, 'Liked')";
            $stmtInsertLike = $conn->prepare($sqlInsertLike);
            $stmtInsertLike->bind_param("iisssss", $idUser, $id, $autorLikedPost, $tituloLikedPost, $contenidoLikedPost, $fotoLikedPost, $fechaPublicacionLikedPost);

            if ($stmtInsertLike->execute()) {
                notificar_like($id, $idUser);
                echo json_encode(['status' => 'liked']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while inserting']);
            }
        }
    } elseif ($action === 'unlike') {
        // Verificar si ya existe un like
        $sqlCheckLike = "SELECT * FROM likes WHERE liked_by = ? AND liked_id_post = ?";
        $stmtCheckLike = $conn->prepare($sqlCheckLike);
        $stmtCheckLike->bind_param("ii", $idUser, $id);
        $stmtCheckLike->execute();
        $resultCheckLike = $stmtCheckLike->get_result();

        if ($resultCheckLike->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'No has dado like a este post']);
        } else {
            // Eliminar like
            $sqlDeleteLike = "DELETE FROM likes WHERE liked_by = ? AND liked_id_post = ?";
            $stmtDeleteLike = $conn->prepare($sqlDeleteLike);
            $stmtDeleteLike->bind_param("ii", $idUser, $id);

            if ($stmtDeleteLike->execute()) {
                echo json_encode(['status' => 'unliked']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while deleting']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
}
?>
