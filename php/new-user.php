<?php
    require('conexion.php');

    session_start();
    # Si se recibieron datos desde el frontend, los almacenamos para insercion
    if (isset($_POST['user'], $_POST['password'])) {
        $user = $_POST['user'];
        $pass = $_POST['password'];

        $cryptPass = sha1($pass);
        # Insertamos en la base de datos
        $sql = "INSERT INTO usuarios (id, usuario, contrasenia, nombre, apellido, correo, telefono, fecha_nacimiento, genero, fotografia) VALUES ('', '$user', '$cryptPass', '', '', '', '', '', '', '')";
        
        if ($conn->query($sql) == TRUE) {
            $_SESSION['user'] = $user;
            header('Location: dashboard.php');
        } else {
            echo "Error al insertar registro";
        }
        $conn->close();
    } else {
        echo "Error de comunicacion con el servidor: ".$conn->error;
        $conn->close();
    }
?>