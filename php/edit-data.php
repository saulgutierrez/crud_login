<?php
    require('conexion.php');

    # Solucion a error: "ya habia iniciado una sesion ignorando session_start()"
    if (!isset($_SESSION)) {
        session_start();
    }
    
    # Si se recibieron datos desde el frontend, los almacenamos para consulta
    if (isset($_POST['user'])) {
        $id = $_POST['id'];
        $user = $_POST['user'];
        $pass = $_POST['password'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $fechaNacimiento = $_POST['fechanacimiento'];
        $genero = $_POST['genero'];
        // $foto = $_POST['foto'];
        $cryptPass = sha1($pass);

        // Evitar que se repita el usuario
        // $evitaDuplicado = "SELECT * FROM usuarios WHERE usuario = '$user'";
        // $resultado = $conn->query($evitaDuplicado);

        // if ($resultado->num_rows == 0) {
            $sql = "UPDATE usuarios SET usuario = '$user', contrasenia = '$cryptPass', nombre = '$nombre', apellido = '$apellido', correo = '$correo', telefono = '$telefono', fecha_nacimiento = '$fechaNacimiento', genero = '$genero' WHERE id = '$id'";
            $result = $conn->query($sql);

            if ($result == TRUE) {
                $_SESSION['user'] = $user;
                echo 0;
            } else {
                echo 1;
            }
        // }
    } else {
        echo 2; // Datos no llegaron desde el frontend
    }
?>