<?php
require('../../config/connection.php');

// Iniciar sesión si no se ha iniciado ya
if (!isset($_SESSION)) {
    session_start();
}

// Establecer el encabezado de contenido como JSON para asegurar que el cliente lo interprete correctamente
header('Content-Type: application/json');

// Directorio donde se almacenan las imágenes; se crea si no existe
$target_dir = "../views/uploads/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Inicialización de la respuesta
$response = array();

// Verificar que se han recibido los datos necesarios desde el frontend
if (isset($_POST['id_user'], $_POST['id_post'], $_POST['user'], $_POST['post_title'], $_POST['post_content'])) {
    $idPost = $_POST['id_post'];
    $idPerfil = $_POST['id_user'];
    $username = $_POST['user'];
    $postTitle = $_POST['post_title'];
    $postContent = $_POST['post_content'];

    $file_uploaded = false;
    $target_file = null;

    // Verificar si se ha subido un archivo y si no hay errores
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $filename;

        // Verificar tipo MIME del archivo
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_mime_type = mime_content_type($_FILES['file']['tmp_name']);

        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $response['message'] = "Tipo de archivo no permitido.";
            echo json_encode($response);
            exit;
        }

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_uploaded = true;
        } else {
            $response['message'] = "Error en la subida del archivo.";
            $response['file_error'] = $_FILES['file']['error'];
            echo json_encode($response);
            exit;
        }
    }

    try {
        // Preparar la consulta y vincular los parámetros
        if ($file_uploaded) {
            $sql = $conn->prepare("UPDATE post SET id_autor = ?, autor_post = ?, titulo_post = ?, contenido_post = ?, foto_post = ? WHERE id_post = ?");
            $sql->bind_param("issssi", $idPerfil, $username, $postTitle, $postContent, $target_file, $idPost);
        } else {
            $sql = $conn->prepare("UPDATE post SET id_autor = ?, autor_post = ?, titulo_post = ?, contenido_post = ? WHERE id_post = ?");
            $sql->bind_param("isssi", $idPerfil, $username, $postTitle, $postContent, $idPost);
        }

        // Ejecutar la consulta
        if ($sql->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Los datos se han guardado en la base de datos.";
            if ($file_uploaded) {
                $response['message'] .= " El archivo se ha subido correctamente.";
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error al ejecutar la consulta: " . $sql->error;
        }
        $sql->close();
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = "Exception: " . $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "No se recibieron los datos desde el frontend.";
}

$conn->close();
echo json_encode($response);
?>
