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
            $sqlInsertLike = "INSERT INTO likes (liked_by, liked_id_post, btn_text) VALUES(?, ?, 'Liked')";
            $stmtInsertLike = $conn->prepare($sqlInsertLike);
            $stmtInsertLike->bind_param("ii", $idUser, $id);

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