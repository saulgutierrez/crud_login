<?php
    require('../../config/connection.php');

    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];

        // Evitar inyeccion SQL
        $searchQuery = htmlspecialchars($searchQuery);
        $searchQuery = "%$searchQuery%";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare("SELECT id, usuario FROM usuarios WHERE usuario LIKE ?");
        $stmt->bind_param("s", $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            // Mostrar resultados
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<a href='profile.php?id=" .$row['id']. "'>" .$row['usuario'] . "</a>";
                echo "</div>";
            }
        } else {
            echo "No se encontraron resultados"; 
        }
        $stmt->close();
    }
    $conn->close();
?>