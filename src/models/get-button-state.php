<?php
    require('../../config/connection.php');
    require('../models/data.php');

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json; charset=UTF-8");

    if (!isset($_SESSION)) {
        session_start();
    }

    // Enviamos ambos parametros, necesario para fijar correctamente la relación entre usuarios
    // y evitar mensajes erronéos en los que se muestra que seguimos a un usuario 
    // que en realidad no seguimos.
    if (isset($_GET['id']) && isset($_SESSION['user'])) {
        $id = $_GET['id'];
        $user = $_SESSION['user'];

        $getMyId = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetMyId = $conn->query($getMyId);

        if ($sqlGetId = mysqli_fetch_assoc($queryGetMyId)) {
            $myId = $sqlGetId['id'];
        }

        $sql = "SELECT btn_text FROM siguiendo WHERE id_seguidor = $myId AND id_seguido = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $isFollowing = $result->num_rows > 0;

        // $btnText = $row['btn_text'];

        echo json_encode([
            "status" => $isFollowing ? 'following' : 'not_following'
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid ID"
        ]);
    }
?>