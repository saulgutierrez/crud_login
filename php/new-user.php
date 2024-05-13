<?php
    require('conexion.php');

    session_start();
    # Si se recibieron datos desde el frontend, los almacenamos para insercion
    if (isset($_POST['new-user'], $_POST['new-password'])) {
        $user = $_POST['new-user'];
        $pass = $_POST['new-password'];
        # Insertamos en la base de datos
        $sql = "INSERT INTO usuarios (id, usuario, contrasenia, nombre, apellido, correo, telefono, fecha_nacimiento, genero, fotografia) VALUES ('', '$user', '$pass', '', '', '', '', '', '', '')";
        
        if ($conn->query($sql) == TRUE) {
            echo "Registro insertado correctamente";
        } else {
            echo "Error al insertar registro";
        }
        $conn->close();
    } else {
        echo "Error de comunicacion con el servidor: ".$conn->error;
        $conn->close();
    }
?>