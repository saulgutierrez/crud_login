<?php
    require('conexion.php');

    session_start();
    # Si se recibieron datos desde el frontend, los almacenamos para insercion
    if (isset($_POST['user'], $_POST['password'])) {
        $user = $_POST['user'];
        $pass = $_POST['password'];

        $cryptPass = sha1($pass); # Encriptamos la contrasenia

        # Realizamos un chequeo para evitar nombres de usuario duplicados
        $sql_check = "SELECT COUNT(*) AS count FROM usuarios WHERE usuario = '$user'";
        $statement_check = $conn->prepare($sql_check);
        $statement_check->execute();
        $result_check = $statement_check->get_result();
        $row = $result_check->fetch_assoc();

        # Si el nombre de usuario es unico, insertamos en la base de datos
        if ($row['count'] == 0) {
            $sql = "INSERT INTO usuarios (id, usuario, contrasenia, nombre, apellido, correo, telefono, fecha_nacimiento, genero, fotografia) VALUES ('', '$user', '$cryptPass', '', '', '', '', '', '', '')";
            
            if ($conn->query($sql) == TRUE) {
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
            } else {
                echo "Error al insertar registro";
            }
            $conn->close();
        } else {
            echo "Por favor, seleccione otro nombre de usuario";
        }
    } else {
        echo "Error de comunicacion con el servidor: ".$conn->error;
        $conn->close();
    }
?>