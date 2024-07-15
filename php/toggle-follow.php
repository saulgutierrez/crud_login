<?php
    require('connection.php');
    require('data.php');

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

        $sqlGetInfo = "SELECT id, usuario, nombre, apellido, fotografia FROM usuarios WHERE id = '$id'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        $sqlGetIdSeguidor = "SELECT id FROM usuarios WHERE usuario = '$nombreSeguidor'";
        $queryGetIdSeguidor = mysqli_query($conn, $sqlGetIdSeguidor);

        if ($sqlGetProfile = mysqli_fetch_assoc($queryGetInfo)) {
            $idPerfilSeguido = $sqlGetProfile['id'];
            $usuarioPerfilSeguido = $sqlGetProfile['usuario'];
            $nombrePerfilSeguido = $sqlGetProfile['nombre'];
            $apellidoPerfilSeguido = $sqlGetProfile['apellido'];
            $fotoPerfilSeguido = $sqlGetProfile['fotografia'];
            $rutaFotoPorDefecto = "../img/profile-default.svg";
            $isEmptyFoto = !empty($fotoPerfilSeguido) ? $fotoPerfilSeguido : $rutaFotoPorDefecto;
        }

        if ($sqlGetIdSeguidor = mysqli_fetch_assoc($queryGetIdSeguidor)) {
            $idSeguidor = $sqlGetIdSeguidor['id'];
        }
        if ($action === 'follow') {
            $sql = "INSERT INTO siguiendo (id_seguidor, id_seguido, nombre_usuario_seguido, nombre_seguido, apellido_seguido, foto_seguido, btn_text) VALUES ('$idSeguidor', '$idPerfilSeguido', '$usuarioPerfilSeguido', '$nombrePerfilSeguido', '$apellidoPerfilSeguido', '$isEmptyFoto', 'Siguiendo')";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {
                echo json_encode(['status' => 'followed']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while inserting']);
            }
        } elseif ($action === 'unfollow') {
            $sql = "DELETE FROM siguiendo WHERE id_seguido = '$idPerfilSeguido'";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {
                echo json_encode(['status' => 'unfollowed']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while deleting']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
    }

?>