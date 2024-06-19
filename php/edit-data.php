<?php
    require('connection.php');

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

        $sql_check = "SELECT COUNT(*) AS count FROM usuarios WHERE usuario = '$user'";
        $statement_check = $conn->prepare($sql_check);
        $statement_check->execute();
        $result_check = $statement_check->get_result();
        $row = $result_check->fetch_assoc();

        if ($row['count'] == 0) {
            // Actualizar datos del usuario
            $sql = "UPDATE usuarios SET usuario = '$user', contrasenia = '$cryptPass', nombre = '$nombre', apellido = '$apellido', correo = '$correo', telefono = '$telefono', fecha_nacimiento = '$fechaNacimiento', genero = '$genero' WHERE id = '$id'";
            $result = $conn->query($sql);

            // Actualizar el nombre de usuario del autor de los post que ha realizado antes de cambiar su nombre
            $sql2 = "UPDATE post SET autor_post = '$user' WHERE id_autor = '$id'";
            $result2 = $conn->query($sql2);
    
            if ($result == TRUE && $result2 == TRUE) {
                $_SESSION['user'] = $user;
                echo 0;
            } else {
                echo 1;
            }
            $conn->close();
        } else {
            echo 2;
        }
    } else {
        echo 3; // Datos no llegaron desde el frontend
    }
?>