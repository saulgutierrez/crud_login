<?php
require('connection.php');

// Iniciar sesion si no se ha iniciado ya
if (!isset($_SESSION)) {
    session_start();
}

// Establecer el encabezado de contenido como JSON para asegurar que el cliente lo interprete correctamente
header('Content-Type: application/json');

// Directorio donde se almacenan las imágenes; se crea si no existe
$target_dir = "uploads/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Inicializacion de la respuesta
$response = array();

// Verificar si se han recibido los datos necesarios desde el frontend
if (isset($_POST['user'], $_POST['password'])) {
    $id = $_POST['id'];
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $fechaNacimiento = $_POST['fechanacimiento'];
    $genero = $_POST['genero'];
    $cryptPass = sha1($pass);

    // Bandera para verificar si se ha subido un archivo
    $file_uploaded = false;
    $target_file = null;

    // Verificar que el nombre de usuario seleccionado sea único
    $sql_check = "SELECT COUNT(*) AS count FROM usuarios WHERE usuario = ?";
    $statement_check = $conn->prepare($sql_check);
    $statement_check->bind_param('s', $user);
    $statement_check->execute();
    $result_check = $statement_check->get_result();
    $row = $result_check->fetch_assoc();

    if ($row['count'] == 0) {
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
                $sql = $conn->prepare("UPDATE usuarios SET usuario = ?, contrasenia = ?, nombre = ?, apellido = ?, correo = ?, telefono = ?, fecha_nacimiento = ?, genero = ?, fotografia = ? WHERE id = ?");
                $sql->bind_param('sssssssssi', $user, $cryptPass, $nombre, $apellido, $correo, $telefono, $fechaNacimiento, $genero, $target_file, $id);
            } else {
                $sql = $conn->prepare("UPDATE usuarios SET usuario = ?, contrasenia = ?, nombre = ?, apellido = ?, correo = ?, telefono = ?, fecha_nacimiento = ?, genero = ? WHERE id = ?");
                $sql->bind_param('ssssssssi', $user, $cryptPass, $nombre, $apellido, $correo, $telefono, $fechaNacimiento, $genero, $id);
            }

            $sql2 = $conn->prepare("UPDATE post SET autor_post = ? WHERE id_autor = ?");
            $sql2->bind_param('si', $user, $id);
            $result2 = $sql2->execute();

            if ($result2) {
                $_SESSION['user'] = $user;
                $response['status'] = 'success';
                $response['message'] = "Se ha actualizado el nombre de usuario en sus posteos";
            } else {
                $response['status'] = 'error';
                $response['message'] = "Error al actualizar el nombre de usuario en los posteos";
            }
            $sql2->close();

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
            $response['message'] = "Excepción: " . $e->getMessage();
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
echo json_encode($response['status']);
exit;
?>
