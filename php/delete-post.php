<?php
require('connection.php');

// Solución a error: "ya había iniciado una sesión ignorando session_start()"
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['id'])) {
    $idPost = $_POST['id'];

    // Recuperar la ruta del archivo asociado al registro
    $sql = $conn->prepare("SELECT foto_post FROM post WHERE id_post = ?");
    $sql->bind_param('i', $idPost);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = $row['foto_post'];

        // Eliminar el archivo del servidor si existe
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Eliminar el registro de la base de datos
        $sql = $conn->prepare("DELETE FROM post WHERE id_post = ?");
        $sql->bind_param('i', $idPost);
        
        if ($sql->execute()) {
            $response['status'] = 'success';
            $response['message'] = "El registro y el archivo asociado se han eliminado correctamente.";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error al eliminar el registro: " . $sql->error;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "No se encontró el registro.";
    }

    $sql->close();
} else {
    $response['status'] = 'error';
    $response['message'] = "No se proporcionó el ID del post.";
}

$conn->close();
echo json_encode($response);
?>
