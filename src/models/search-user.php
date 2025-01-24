<?php
    require('../../config/connection.php');
    require('../models/session.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];

        if (!isset($_SESSION['user'])) {
            header('Location: ../index.php');
            exit();
        }

        $user = $_SESSION['user'];

        $sql = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $getUserIdQuery = $conn->query($sql);
        $getUserIdRow = $getUserIdQuery->fetch_assoc();
        $getUserId = $getUserIdRow['id'];

        // Evitar inyeccion SQL
        $searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');
        $searchQuery = "%$searchQuery%";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare("SELECT id, usuario, fotografia FROM usuarios WHERE usuario LIKE ?");
        $stmt->bind_param("s", $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            // Mostrar resultados
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<div class='imgBox'>";
                echo "<img src='" . $row['fotografia'] . "'>";
                echo "</div>";

                if ($row['id'] == $getUserId) {
                    echo "<a href='profile.php?user=".$row['usuario']."'>" .$row['usuario']. "</a>";
                } else {
                    echo "<a href='profile.php?id=" .$row['id']. "'>" .$row['usuario'] . "</a>";
                }
                echo "</div>";
            }
        } else {
            echo "No se encontraron resultados"; 
        }
        $stmt->close();
    }
    $conn->close();
?>