<?php
    require('connection.php');
    require('data.php');

    // Establecer la cabecera de respuesta para permitir solicitudes desde cualquier origen (CORS)
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");

    if (!isset($_SESSION)) {
        session_start();
    }

    // Recibimos el id del usuario que deseamos seguir
    if (isset($_POST['id']) && isset($_SESSION['user']) && isset($_POST['btnText'])) {
        $id = $_POST['id'];
        $nombreSeguidor = $_SESSION['user'];
        $btnText = $_POST['btnText'];

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
        
        $sql = "INSERT INTO siguiendo (id_seguidor, id_seguido, nombre_usuario_seguido, nombre_seguido, apellido_seguido, foto_seguido, btn_text) VALUES ('$idSeguidor', '$idPerfilSeguido', '$usuarioPerfilSeguido', '$nombrePerfilSeguido', '$apellidoPerfilSeguido', '$isEmptyFoto', '$btnText')";
        $result = mysqli_query($conn, $sql);

        if ($result == TRUE) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }

?>