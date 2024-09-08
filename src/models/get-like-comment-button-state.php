<?php
    require('../../config/connection.php');
    require('../models/session.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_GET['id']) && isset($_SESSION['user'])) {
        $idLikedComment = $_GET['id'];
        $user = $_SESSION['user'];

        $getMyId = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetId = $conn->query($getMyId);

        if ($sqlGetId = mysqli_fetch_assoc($queryGetId)) {
            $myId = $sqlGetId['id'];
        }

        $sql = "SELECT btn_text FROM likes_comentarios WHERE id = $myId AND id_comentario = $idLikedComment";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $isLiked = $result->num_rows > 0;

        echo json_encode([
            "status" => $isLiked ? 'liked' : 'unliked'
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid ID"
        ]);
    }
?>