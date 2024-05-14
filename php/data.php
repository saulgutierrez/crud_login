<?php
    require('conexion.php');

    if (!isset($_SESSION)) {
        session_start();
    }
    
    # Si se recibieron datos desde el frontend, los almacenamos para consulta
    if (isset($_POST['user'], $_POST['password'])) {
        $user = $_POST['user'];
        $pass = $_POST['password'];
        # Consultamos si los datos coinciden en la base de datos
        $sql = "SELECT * FROM usuarios WHERE usuario = '$user' AND contrasenia = '$pass'";
        $result = $conn->query($sql);
        # Si existe alguna coincidencia, creamos una variable de sesion
        # para identificar al usuario y mantenerlo dentro del sistema
        if ($result->num_rows > 0) {
            if (!isset($_SESSION['user'])) {
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
            }
        } else {
            echo 'Usuario o contrasenia incorrectos';
        }   
    }
?>