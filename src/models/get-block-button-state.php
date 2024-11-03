<?php
    require('../../config/connection.php');
    require('../models/session.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_GET['id']) && isset($_SESSION['user'])) {
        $idBlockedUser = $_GET['id'];
        $user = $_SESSION['user'];

        $getMyId = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetMyId = $conn->query($getMyId);

        if ($sqlGetId = mysqli_fetch_assoc($queryGetMyId)) {
            $myId = $sqlGetId['id'];
        }

        $sql = "SELECT btn_text FROM user_blocks WHERE blocker_id = $myId AND blocked_id = $idBlockedUser";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $isBlocked = $result->num_rows > 0;

        echo json_encode([
            "status" => $isBlocked ? 'blocked' : 'unblocked'
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid ID"
        ]);
    }
?>