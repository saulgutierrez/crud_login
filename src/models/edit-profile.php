<?php
require('../../config/connection.php');

if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json');

$target_dir = "../views/uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$response = array();

if (isset($_POST['id'], $_POST['user'], $_POST['password'], $_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['telefono'], $_POST['fechanacimiento'], $_POST['genero'])) {
    $id = $_POST['id'];
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $fechaNacimiento = $_POST['fechanacimiento'];
    $genero = $_POST['genero'];
    $rutaImagenPorDefecto = "../../public/img/profile-default.svg";

    // Evitar inyeccion SQL
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $user = htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8');
    $pass = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $apellido = htmlspecialchars($_POST['apellido'], ENT_QUOTES, 'UTF-8');
    $correo = htmlspecialchars($_POST['correo'], ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8');
    $fechaNacimiento = htmlspecialchars($_POST['fechanacimiento'], ENT_QUOTES, 'UTF-8');
    $genero = htmlspecialchars($_POST['genero'], ENT_QUOTES, 'UTF-8');

    $cryptPass = sha1($pass);

    $file_uploaded = false;
    $target_file = null;

    $sql_check = "SELECT COUNT(*) AS count FROM usuarios WHERE usuario = ?";
    $statement_check = $conn->prepare($sql_check);
    $statement_check->bind_param('s', $user);
    $statement_check->execute();
    $result_check = $statement_check->get_result();
    $row = $result_check->fetch_assoc();

    if ($row['count'] == 0) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            
            // Borramos la imagen de perfil anterior en caso de que exista, junto con el archivo asociado
            // Solo se puede tener una imagen de perfil por cada cuenta
            $sql_get_image = $conn->prepare("SELECT fotografia FROM usuarios WHERE id = ?");
            $sql_get_image->bind_param('i', $id);
            $sql_get_image->execute();
            $sql_get_image_result = $sql_get_image->get_result();
            
            while ($row_image = $sql_get_image_result->fetch_assoc()) {
                $previous_image = $row_image['fotografia'];
                if (!empty($previous_image)) {
                    if (file_exists($previous_image) && ($previous_image !== $rutaImagenPorDefecto)) {
                        unlink($previous_image);
                    }
                } else {
                    $response['test'] = "Ruta de imagen vacia en la base de datos";
                    echo json_encode($response);
                    exit;
                }
            }

            // Create random filename
            $file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            $random_filename = uniqid('img_', true) . '.' . $file_extension;
            $target_file = $target_dir . $random_filename;

            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_mime_type = mime_content_type($_FILES['file']['tmp_name']);

            if (!in_array($file_mime_type, $allowed_mime_types)) {
                $response['message'] = "Tipo de archivo no permitido.";
                echo json_encode($response);
                exit;
            }

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
            $conn->begin_transaction();

            if ($file_uploaded) {
                $sql = $conn->prepare("UPDATE usuarios SET usuario = ?, contrasenia = ?, nombre = ?, apellido = ?, correo = ?, telefono = ?, fecha_nacimiento = ?, genero = ?, fotografia = ? WHERE id = ?");
                $sql->bind_param('sssssssssi', $user, $cryptPass, $nombre, $apellido, $correo, $telefono, $fechaNacimiento, $genero, $target_file, $id);
            } else {
                // En caso de no actualizar la foto de perfil, mantenemos la que usa la cuenta actualmente
                $sql_get_settled_image = $conn->prepare("SELECT fotografia FROM usuarios WHERE id = ?");
                $sql_get_settled_image->bind_param('i', $id);
                $sql_get_settled_image->execute();
                $sql_get_settled_image_result = $sql_get_settled_image->get_result();
                
                while ($row_settled_image = $sql_get_settled_image_result->fetch_assoc()) {
                    $previous_settled_image = $row_settled_image['fotografia'];
                }

                $sql = $conn->prepare("UPDATE usuarios SET usuario = ?, contrasenia = ?, nombre = ?, apellido = ?, correo = ?, telefono = ?, fecha_nacimiento = ?, genero = ?, fotografia = ? WHERE id = ?");
                $sql->bind_param('sssssssssi', $user, $cryptPass, $nombre, $apellido, $correo, $telefono, $fechaNacimiento, $genero, $previous_settled_image, $id);
            }

            if (!$sql->execute()) {
                throw new Exception("Error al actualizar usuarios: " . $sql->error);
            }

            $sql2 = $conn->prepare("UPDATE post SET autor_post = ? WHERE id_autor = ?");
            $sql2->bind_param('si', $user, $id);
            if (!$sql2->execute()) {
                throw new Exception("Error al actualizar los post: " . $sql2->error);
            }

            $sql3 = $conn->prepare("UPDATE comentarios SET autor_comentario = ? WHERE id_autor_comentario = ?");
            $sql3->bind_param('si', $user, $id);
            if (!$sql3->execute()) {
                throw new Exception("Error al actualizar los comentarios: " . $sql3->error);
            }

            if ($file_uploaded) {
                $sql4 = $conn->prepare("UPDATE siguiendo SET nombre_usuario_seguido = ?, nombre_seguido = ?, apellido_seguido = ?, foto_seguido = ? WHERE id_seguido = ?");
                $sql4->bind_param('ssssi', $user, $nombre, $apellido, $target_file, $id);
            } else {
                $sql4 = $conn->prepare("UPDATE siguiendo SET nombre_usuario_seguido = ?, nombre_seguido = ?, apellido_seguido = ?, foto_seguido = ? WHERE id_seguido = ?");
                $sql4->bind_param('ssssi', $user, $nombre, $apellido, $rutaImagenPorDefecto, $id);
            }
            if (!$sql4->execute()) {
                throw new Exception("Error al actualizar la tabla de seguidores: " . $sql4->error);
            }

            $conn->commit();

            $_SESSION['user'] = $user;
            $response['status'] = 'success';
            $response['message'] = "Los datos se han guardado en la base de datos.";
            if ($file_uploaded) {
                $response['message'] .= " El archivo se ha subido correctamente.";
            }
        } catch (Exception $e) {
            $conn->rollback();
            $response['status'] = 'error';
            $response['message'] = "Excepción: " . $e->getMessage();
            error_log($e->getMessage());
        } finally {
            $sql->close();
            $sql2->close();
            $sql3->close();
            $sql4->close();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'El nombre de usuario ya existe. Seleccione otro';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "No se recibieron los datos desde el frontend.";
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>