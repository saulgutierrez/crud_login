<?php
require('../../config/connection.php');
require('../models/session.php');

if (!isset($_SESSION)) {
    session_start();
}

$username = $_SESSION['user'];

// Obtener el id del usuario
$sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = ?";
$stmtGetIdUser = $conn->prepare($sqlGetIdUser);
$stmtGetIdUser->bind_param("s", $username);
$stmtGetIdUser->execute();
$result = $stmtGetIdUser->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idUser = $row['id'];
}

// Recibimos el id del usuario que deseamos bloquear
if (isset($_POST['id']) && isset($_SESSION['user']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action === 'block') {
        // Verificar si ya existe un bloqueo
        $sqlCheckBlock = "SELECT * FROM user_blocks WHERE blocker_id = ? AND blocked_id = ?";
        $stmtCheckBlock = $conn->prepare($sqlCheckBlock);
        $stmtCheckBlock->bind_param("ii", $idUser, $id);
        $stmtCheckBlock->execute();
        $resultCheckBlock = $stmtCheckBlock->get_result();

        if ($resultCheckBlock->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Ya has bloqueado a este usuario']);
        } else {
            // Si no existe, crear el registro
            $sqlInsertBlock = "INSERT INTO user_blocks (blocker_id, blocked_id, btn_text) VALUES (?, ?, 'Blocked')";
            $stmtInsertBlock = $conn->prepare($sqlInsertBlock);
            $stmtInsertBlock->bind_param("ii", $idUser, $id);

            if ($stmtInsertBlock->execute()) {
                echo json_encode(['status' => 'blocked']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while inserting']);
            }
        }
    } elseif ($action === 'unblock') {
        // Verificar si ya existe un bloqueo
        $sqlCheckBlock = "SELECT * FROM user_blocks WHERE blocker_id = ? AND blocked_id = ?";
        $stmtCheckBlock = $conn->prepare($sqlCheckBlock);
        $stmtCheckBlock->bind_param("ii", $idUser, $id);
        $stmtCheckBlock->execute();
        $resultCheckBlock = $stmtCheckBlock->get_result();

        if ($resultCheckBlock->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'No has bloqueado a este usuario']);
        } else {
            // Eliminar bloqueo
            $sqlDeleteBlock = "DELETE FROM user_blocks WHERE blocker_id = ? AND blocked_id = ?";
            $stmtDeleteBlock = $conn->prepare($sqlDeleteBlock);
            $stmtDeleteBlock->bind_param("ii", $idUser, $id);

            if ($stmtDeleteBlock->execute()) {
                echo json_encode(['status' => 'unblocked']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while unblocking']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
}
?>