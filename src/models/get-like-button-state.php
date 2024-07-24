<?php
    require('../../config/connection.php');
    require('../models/data.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_GET['id']) && isset($_SESSION['user'])) {
        $idLikedPost = $_GET['id'];
        $user = $_SESSION['user'];
        
        $getMyId = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetMyId = $conn->query($getMyId);

        if ($sqlGetId = mysqli_fetch_assoc($queryGetMyId)) {
            $myId = $sqlGetId['id'];
        }

        $sql = "SELECT btn_text FROM likes WHERE liked_by = $myId AND liked_id_post = $idLikedPost";
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