<?php
require('../../config/connection.php');
require('generate-recovery-codes.php');

// Verificar si la petición es AJAX y que contiene los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION)) {
        session_start();
    }

    // Comprobar si el usuario está en la sesión
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];

        // Preparar la consulta para obtener el ID del usuario
        $getMyId = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $getMyId->bind_param("s", $user);
        $getMyId->execute();
        $result = $getMyId->get_result();

        // Verificar si el usuario existe
        if ($result->num_rows > 0) {
            $sqlGetId = $result->fetch_assoc();
            $myId = $sqlGetId['id'];

            // Generar los códigos de recuperación
            $codigos = generarCodigosRecuperacion(5, 8);

            // Almacenar los códigos en la base de datos
            almacenarCodigosEnDB($myId, $codigos, $conn);

            // Enviar los códigos generados de vuelta al frontend
            echo json_encode(['success' => true, 'codes' => $codigos]);
        } else {
            // En caso de que no se encuentre el usuario
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        $getMyId->close();
    } else {
        // En caso de que no haya sesión activa
        echo json_encode(['success' => false, 'message' => 'Sesión no iniciada']);
    }
} else {
    // Respuesta en caso de que no sea una petición POST
    echo json_encode(['success' => false, 'message' => 'Petición no válida']);
}

$conn->close();
?>
