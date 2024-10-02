<?php
    require('../../config/connection.php');
    require('../models/session.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['user']) && isset($_POST['id'])) {
        $user = $_SESSION['user'];
        $id = $_POST['id'];

        $getMyId = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetMyId = $conn->query($getMyId);

        if ($sqlGetId = mysqli_fetch_assoc($queryGetMyId)) {
            $myId = $sqlGetId['id'];
        }

        $sql = "SELECT btn_text FROM siguiendo WHERE id_seguidor = $myId AND id_seguido = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $isFollowing = $result->num_rows > 0;

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