<?php
    if (!isset($_GET['id'])) {
        die('ID del juego no especificado.');
    }

    $gameId = htmlspecialchars($_GET['id']);
    $url = "https://www.freetogame.com/api/game?id=" . $gameId;

    try {
        $response = file_get_contents($url);
        if ($response === FALSE) {
            throw new Exception("Error al consumir la API de detalles del juego");
        }
        $gameData = json_decode($response, TRUE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/game-details.css">
    <title><?php echo htmlspecialchars($gameData['title']); ?></title>
</head>
<body>
    <?php include "../includes/navbar.php"; ?>
    <!-- Reestructuracion del codigo que consume la API, con comprobaciones, para evitar warnings en caso
        de que existan campos vacions con informacion no disponible. -->
    <div class="main-container">
        <h1 class="titulo"><?php echo isset($gameData['title']) ? htmlspecialchars($gameData['title']) : 'Título no disponible'; ?></h1>

        <?php if (isset($gameData['thumbnail']) && !empty($gameData['thumbnail'])) { ?>
            <div class="header">
                <div class="main-image-container">
                    <img src="<?php echo htmlspecialchars($gameData['thumbnail']); ?>" alt="Imagen del juego">
                </div>
                <p><?php echo isset($gameData['description']) ? htmlspecialchars($gameData['description']) : 'Descripción no disponible'; ?></p>
            </div>
        <?php } else { ?>
            <p>Imagen del juego no disponible</p>
        <?php } ?>
        <div class="genre">
            <p>Genre</p>
            <p><?php echo isset($gameData['genre']) ? htmlspecialchars($gameData['genre']) : 'Género no disponible'; ?></p>
        </div>
        <div class="platform">
            <p>Platform</p>
            <p><?php echo isset($gameData['platform']) ? htmlspecialchars($gameData['platform']) : 'Plataforma no disponible'; ?></p>
        </div>
        <div class="publisher">
            <p>Publisher</p>
            <p><?php echo isset($gameData['publisher']) ? htmlspecialchars($gameData['publisher']) : 'Publicador no disponible'; ?></p>
        </div>
        <div class="developer">
            <p>Developer</p>
            <p><?php echo isset($gameData['developer']) ? htmlspecialchars($gameData['developer']) : 'Desarrollador no disponible'; ?></p>
        </div>
        <div class="release-date">
            <p>Release Date</p>
            <p><?php echo isset($gameData['release_date']) ? htmlspecialchars($gameData['release_date']) : 'Fecha de lanzamiento no disponible'; ?></p>
        </div>

        <?php if (isset($gameData['minimum_system_requirements'])) { ?>
            <p><?php echo isset($gameData['minimum_system_requirements']['os']) ? htmlspecialchars($gameData['minimum_system_requirements']['os']) : 'Requisitos mínimos del sistema operativo no disponibles'; ?></p>
            <p><?php echo isset($gameData['minimum_system_requirements']['processor']) ? htmlspecialchars($gameData['minimum_system_requirements']['processor']) : 'Requisitos mínimos del procesador no disponibles'; ?></p>
            <p><?php echo isset($gameData['minimum_system_requirements']['memory']) ? htmlspecialchars($gameData['minimum_system_requirements']['memory']) : 'Requisitos mínimos de memoria no disponibles'; ?></p>
            <p><?php echo isset($gameData['minimum_system_requirements']['graphics']) ? htmlspecialchars($gameData['minimum_system_requirements']['graphics']) : 'Requisitos mínimos de gráficos no disponibles'; ?></p>
            <p><?php echo isset($gameData['minimum_system_requirements']['storage']) ? htmlspecialchars($gameData['minimum_system_requirements']['storage']) : 'Requisitos mínimos de almacenamiento no disponibles'; ?></p>
        <?php } else { ?>
            <p>Requisitos mínimos del sistema no disponibles</p>
        <?php } ?>

        <div class="img-container">
            <?php if (!empty($gameData['screenshots']) && is_array($gameData['screenshots'])) {
                // Verificar y mostrar cada imagen si existe
                for ($i = 0; $i < 3; $i++) {
                    if (isset($gameData['screenshots'][$i]['image'])) {
                        echo '<img src="' . htmlspecialchars($gameData['screenshots'][$i]['image']) . '" alt="Imagen del juego">';
                    }
                }
            } else { ?>
                No se encontraron imágenes del juego
            <?php } ?>
        </div>
    </div>
</body>
</html>