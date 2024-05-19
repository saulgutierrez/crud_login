<?php
    require('conexion.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['user'], $_POST['confirm-delete'])) {
        $user = $_POST['user'];
        $pass = $_POST['confirm-delete'];   

        $cryptPass = sha1($pass);

        $sql_check = "SELECT COUNT(*) AS count FROM usuarios WHERE usuario = '$user' AND contrasenia = '$cryptPass'";
        $statement_check = $conn->prepare($sql_check);
        $statement_check->execute();
        $result_check = $statement_check->get_result();
        $row = $result_check->fetch_assoc();
        
        if ($row['count'] == 1) {
            $sql = "DELETE FROM usuarios WHERE usuario = '$user' AND contrasenia = '$cryptPass'";
            $result = $conn->query($sql);
            session_destroy();
            echo 0;
        } else {
            echo 1;
        }
    } else {
        echo "Error de comunicacion con el servidor";
        $conn->close();
    }
?>