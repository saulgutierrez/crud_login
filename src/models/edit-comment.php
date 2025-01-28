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
if (isset($_POST['commentText'],$_POST['commentId'])) {
    $comment_id = $_POST['commentId'];
    $comment = $_POST['commentText'];

    // Evitar inyeccion SQL
    $comment_id = htmlspecialchars($_POST['commentId'], ENT_QUOTES, 'UTF-8');
    $comment = htmlspecialchars($_POST['commentText'], ENT_QUOTES, 'UTF-8');

    $file_uploaded = false;
    $target_file = false;

    // Verificar si se ha subido un archivo y si no hay errores
    if (isset($_FILES['newCommentImage']) && $_FILES['newCommentImage']['error'] == UPLOAD_ERR_OK) {
       
        // Borramos la imagen del comentario anterior en caso de que exista, junto con el archivo asociado
        // Solo se puede tenr una foto por cada comentario
        $sql_get_image_comment = $conn->prepare("SELECT foto_comentario FROM comentarios WHERE id_comentario = ?");
        $sql_get_image_comment->bind_param('i', $comment_id);
        $sql_get_image_comment->execute();
        $sql_get_image_comment_result = $sql_get_image_comment->get_result();

        while ($row_image_comment = $sql_get_image_comment_result->fetch_assoc()) {
            $previous_image_comment = $row_image_comment['foto_comentario'];
            if (!empty($previous_image_comment)) {
                if (file_exists($previous_image_comment)) {
                    unlink($previous_image_comment);
                }
            }
        }

        // Crear nombre de archivo aleatorio
        $file_extension = pathinfo($_FILES["newCommentImage"]["name"], PATHINFO_EXTENSION);
        $random_filename = uniqid('img_', true) . '.' . $file_extension;
        $target_file = $target_dir . $random_filename;

        // Verificar tipo MIME del archivo
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_mime_type = mime_content_type($_FILES['newCommentImage']['tmp_name']);

        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $response['message'] = "Tipo de archivo no permitido.";
            echo json_encode($response);
            exit;
        }

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($_FILES["newCommentImage"]["tmp_name"], $target_file)) {
            $file_uploaded = true;
        } else {
            $response['message'] = "Error en la subida del archivo.";
            $response['file_error'] = $_FILES['newCommentImage']['error'];
            echo json_encode($response);
            exit;
        }
    }

    try {
        if ($file_uploaded) {
            $sql = $conn->prepare("UPDATE comentarios SET comentario = ?, foto_comentario = ? WHERE id_comentario = ?");
            $sql->bind_param('ssi', $comment, $target_file, $comment_id);
        } else {
            $sql = $conn->prepare("UPDATE comentarios SET comentario = ? WHERE id_comentario = ?");
            $sql->bind_param('si', $comment, $comment_id);
        }

        if ($sql->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Los datos se han guardado en la base de datos';
            $response['comment'] = $comment;
            if ($file_uploaded) {
                $response['message'] .= " El archivo se ha subido correctamente";
                $response['image'] = $target_file;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error al ejecutar la consulta " . $sql->error;
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