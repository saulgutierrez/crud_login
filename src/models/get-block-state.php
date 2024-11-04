<?php
    require('../../config/connection.php');
    require('../models/session.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    function isUserBlocked($userId, $blockedUserId, $conn) {
        $query = "SELECT * FROM user_blocks WHERE (blocker_id = ? AND blocked_id = ?) OR (blocker_id = ? AND blocked_id = ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiii", $userId, $blockedUserId, $blockedUserId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
?>