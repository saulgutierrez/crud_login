<?php
require('../../config/connection.php');
require('../models/data.php');

// Establecer la cabecera de respuesta para permitir solicitudes desde cualquier origen (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION)) {
    session_start();
}

// Recibimos el id del usuario que deseamos seguir
if (isset($_POST['id']) && isset($_SESSION['user']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $nombreSeguidor = $_SESSION['user'];
    $action = $_POST['action'];

    // Obtener información del perfil seguido
    $sqlGetInfo = "SELECT id, usuario, nombre, apellido, fotografia FROM usuarios WHERE id = ?";
    $stmtGetInfo = $conn->prepare($sqlGetInfo);
    $stmtGetInfo->bind_param("i", $id);
    $stmtGetInfo->execute();
    $resultGetInfo = $stmtGetInfo->get_result();

    // Obtener el ID del seguidor
    $sqlGetIdSeguidor = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmtGetIdSeguidor = $conn->prepare($sqlGetIdSeguidor);
    $stmtGetIdSeguidor->bind_param("s", $nombreSeguidor);
    $stmtGetIdSeguidor->execute();
    $resultGetIdSeguidor = $stmtGetIdSeguidor->get_result();

    if ($sqlGetProfile = $resultGetInfo->fetch_assoc()) {
        $idPerfilSeguido = $sqlGetProfile['id'];
        $usuarioPerfilSeguido = $sqlGetProfile['usuario'];
        $nombrePerfilSeguido = $sqlGetProfile['nombre'];
        $apellidoPerfilSeguido = $sqlGetProfile['apellido'];
        $fotoPerfilSeguido = $sqlGetProfile['fotografia'];
        $rutaFotoPorDefecto = "../../public/img/profile-default.svg";
        $isEmptyFoto = !empty($fotoPerfilSeguido) ? $fotoPerfilSeguido : $rutaFotoPorDefecto;
    }

    if ($sqlGetIdSeguidor = $resultGetIdSeguidor->fetch_assoc()) {
        $idSeguidor = $sqlGetIdSeguidor['id'];
    }

    if ($idSeguidor == $idPerfilSeguido) {
        die('No puedes seguirte a ti mismo');
    }

    if ($action === 'follow') {
        // Verificar si ya existe una relación
        $sqlCheckFollow = "SELECT * FROM siguiendo WHERE id_seguidor = ? AND id_seguido = ?";
        $stmtCheckFollow = $conn->prepare($sqlCheckFollow);
        $stmtCheckFollow->bind_param("ii", $idSeguidor, $idPerfilSeguido);
        $stmtCheckFollow->execute();
        $resultCheckFollow = $stmtCheckFollow->get_result();

        if ($resultCheckFollow->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Ya estás siguiendo a este usuario']);
        } else {
            // Insertar relación si no existe
            $sqlInsertFollow = "INSERT INTO siguiendo (id_seguidor, id_seguido, nombre_usuario_seguido, nombre_seguido, apellido_seguido, foto_seguido, btn_text) 
                                VALUES (?, ?, ?, ?, ?, ?, 'Siguiendo')";
            $stmtInsertFollow = $conn->prepare($sqlInsertFollow);
            $stmtInsertFollow->bind_param("iissss", $idSeguidor, $idPerfilSeguido, $usuarioPerfilSeguido, $nombrePerfilSeguido, $apellidoPerfilSeguido, $isEmptyFoto);
            
            if ($stmtInsertFollow->execute()) {
                echo json_encode(['status' => 'followed']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while inserting']);
            }
        }
    } elseif ($action === 'unfollow') {
        // Verificar si existe una relación 
        $sqlCheckFollow = "SELECT * FROM siguiendo WHERE id_seguidor = ? AND id_seguido = ?";
        $stmtCheckFollow = $conn->prepare($sqlCheckFollow);
        $stmtCheckFollow->bind_param("ii", $idSeguidor, $idPerfilSeguido);
        $stmtCheckFollow->execute();
        $resultCheckFollow = $stmtCheckFollow->get_result();

        if ($resultCheckFollow->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'No estás siguiendo a este usuario']);
        } else {
            // Eliminar la relación
            $sqlDeleteFollow = "DELETE FROM siguiendo WHERE id_seguidor = ? AND id_seguido = ?";
            $stmtDeleteFollow = $conn->prepare($sqlDeleteFollow);
            $stmtDeleteFollow->bind_param("ii", $idSeguidor, $idPerfilSeguido);

            if ($stmtDeleteFollow->execute()) {
                echo json_encode(['status' => 'unfollowed']);
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
