<?php
require('../../config/connection.php');

session_start();

# Si se recibieron datos desde el frontend, los almacenamos para inserción
if (isset($_POST['user'], $_POST['password'])) {
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $rutaFotoPorDefecto = "../../public/img/profile-default.svg";

    $cryptPass = sha1($pass); # Encriptamos la contraseña

    # Realizamos un chequeo para evitar nombres de usuario duplicados
    $sql_check = "SELECT COUNT(*) AS count FROM usuarios WHERE usuario = ?";
    $statement_check = $conn->prepare($sql_check);
    $statement_check->bind_param("s", $user);
    $statement_check->execute();
    $result_check = $statement_check->get_result();
    $row = $result_check->fetch_assoc();

    # Si el nombre de usuario es único, insertamos en la base de datos
    if ($row['count'] == 0) {
        $sql = "INSERT INTO usuarios (usuario, contrasenia, nombre, apellido, correo, telefono, fecha_nacimiento, genero, fotografia) VALUES (?, ?, '', '', '', '', '', '', ?)";
        $statement = $conn->prepare($sql);
        $statement->bind_param("sss", $user, $cryptPass, $rutaFotoPorDefecto);

        if ($statement->execute()) {
            $_SESSION['user'] = $user;
            echo 0;
        } else {
            echo "Error al insertar registro";
        }
        $statement->close();
    } else {
        echo 1;
    }
    $statement_check->close();
} else {
    echo "Error de comunicación con el servidor: " . $conn->error;
}
$conn->close();
?>
