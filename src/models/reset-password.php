<?php
    require('../../config/connection.php');

    // Verificar si la peticion es valida
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usernameOrEmail = $_POST['userOrEmail'];
        $recoveryCode = $_POST['recoveryCode'];
        $newPassword = $_POST['newPassword'];

        // Consulta para encontrar el usuario con el nombre de usuario o email
        $sql = "SELECT id, contrasenia FROM usuarios WHERE (usuario = ? OR correo = ?) LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $userId = $user['id'];

            // Verificar el codigo de recuperacion en la tabla codigos_recuperacion
            $sqlCode = "SELECT * FROM codigos_recuperacion WHERE usuario_id = ? AND codigo = ? AND expiracion > NOW() AND usado = 0 LIMIT 1";
            $stmtCode = $conn->prepare($sqlCode);
            $stmtCode->bind_param("is", $userId, $recoveryCode);
            $stmtCode->execute();
            $resultCode = $stmtCode->get_result();
            $recoveryRow = $resultCode->fetch_assoc();

            if ($recoveryRow) {
                // Codigo valido, actualizar la contraseña
                $newPasswordHash = sha1($newPassword);

                $sqlUpdate = "UPDATE usuarios SET contrasenia = ? WHERE id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("si", $newPasswordHash, $userId);

                if ($stmtUpdate->execute()) {
                    // Marcar el codigo como usado
                    $sqlMarkUsed = "UPDATE codigos_recuperacion SET usado = 1 WHERE id = ?";
                    $stmtMarkUsed = $conn->prepare($sqlMarkUsed);
                    $stmtMarkUsed->bind_param("i", $recoveryRow['id']);
                    $stmtMarkUsed->execute();

                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Codigo de recuperacion invalido o expirado']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Peticion no valida']);
    }
?>