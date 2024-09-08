<?php
require('../../config/connection.php'); // Incluye el archivo de conexión a la base de datos
require('notifications.php');

if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json');

$response = array();

// Directorio donde se guardará el archivo
$target_dir = "../views/uploads/";

// Asegúrate de que el directorio exista y tenga permisos adecuados
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true); // Crea el directorio si no existe
}

// Verifica si se recibió el campo de comentario desde el frontend
if (isset($_POST['comment-input'])) {
    $idPost = $_POST['id-post'];
    $idAutorPost = $_POST['id-autor-post'];
    $idAutorComentario = $_POST['id-autor-comentario'];
    $autorComentario = $_POST['autor-comentario'];
    $comentario = $_POST['comment-input'];
    $fecha = $_POST['comment-time'];

    $file_uploaded = false;

    // Verifica si se subió un archivo y si no hubo errores
    if (isset($_FILES['file-input']) && $_FILES['file-input']['error'] == UPLOAD_ERR_OK) {
        // Create random filename
        $file_extension = pathinfo($_FILES["file-input"]["name"], PATHINFO_EXTENSION);
        $random_filename = uniqid('img_', true) . '.' . $file_extension;
        $target_file = $target_dir . $random_filename;

        // Verifica el tipo MIME del archivo
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_mime_type = mime_content_type($_FILES['file-input']['tmp_name']);

        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $response['status'] = 'error';
            $response['message'] = "Tipo de archivo no permitido.";
            echo json_encode($response);
            exit;
        }

        // Intenta mover el archivo al directorio de destino
        if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $target_file)) {
            $file_uploaded = true;
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error en la subida del archivo.";
            echo json_encode($response);
            exit;
        }
    }

    try {
        $conn->begin_transaction();

        // Prepara la consulta SQL dependiendo de si se subió un archivo o no
        if ($file_uploaded) {
            $sql = $conn->prepare("INSERT INTO comentarios (id_post, id_autor, id_autor_comentario, autor_comentario, comentario, foto_comentario, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("iiissss", $idPost, $idAutorPost, $idAutorComentario, $autorComentario, $comentario, $target_file, $fecha);
            notificar_comentario($idPost, $idAutorComentario);
        } else {
            $sql = $conn->prepare("INSERT INTO comentarios (id_post, id_autor, id_autor_comentario, autor_comentario, comentario, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->bind_param("iiisss", $idPost, $idAutorPost, $idAutorComentario, $autorComentario, $comentario, $fecha);
            notificar_comentario($idPost, $idAutorComentario);
        }

        // Ejecuta la consulta SQL
        if (!$sql->execute()) {
            throw new Exception("Error al actualizar comentarios: " . $sql->error);
        }

        // Después de insertar el comentario, consulta la imagen del autor
        $sqlImagenAutor = $conn->prepare("SELECT fotografia FROM usuarios WHERE id = ?");
        $sqlImagenAutor->bind_param("i", $idAutorComentario);
        $sqlImagenAutor->execute();
        $result = $sqlImagenAutor->get_result();

        if ($result->num_rows > 0) {
            $getImagenAutor = $result->fetch_assoc();
            $imagenAutor = $getImagenAutor['fotografia'];
        }

        // Confirma la transacción si todo fue exitoso
        $conn->commit();

        // Prepara la respuesta
        $response['status'] = "success";
        $response['message'] = "Los datos se han guardado en la base de datos.";
        if ($file_uploaded) {
            $response['message'] .= " El archivo se ha subido correctamente.";
        }

        // Almacenamos las respuestas del servidor, para enviar al frontend y procesarlas
        $response['autorComentario'] = $autorComentario;
        $response['fechaComentario'] = $fecha;
        $response['comentario'] = $comentario;
        $response['imagenAutor'] = $imagenAutor;

    } catch (Exception $e) {
        // Revierte la transacción en caso de error
        $conn->rollback();
        $response['status'] = 'error';
        $response['message'] = "Excepción: " . $e->getMessage();
        error_log($e->getMessage());
    } finally {
        // Cierra la conexión a la base de datos
        $conn->close();
    }
} else {
    // Si no se recibieron los datos desde el frontend
    $response['status'] = 'error';
    $response['message'] = "No se recibieron los datos desde el frontend.";
}

// Devuelve la respuesta como JSON
echo json_encode($response);
exit;
?>
