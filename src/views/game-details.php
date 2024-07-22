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
    <link rel="stylesheet" href="../../public/css/game-details.css">
    <title><?php echo htmlspecialchars($gameData['title']); ?></title>
</head>
<?php include "../views/includes/navbar-game-details.php"; ?>
<body>
    <!-- Reestructuracion del codigo que consume la API, con comprobaciones, para evitar warnings en caso
        de que existan campos vacions con informacion no disponible. -->
    <div class="main-container">

        <?php if (isset($gameData['thumbnail']) && !empty($gameData['thumbnail'])) { ?>
            <div class="header">
                <h1 class="titulo"><?php echo isset($gameData['title']) ? htmlspecialchars($gameData['title']) : 'Título no disponible'; ?></h1>
                <div class="top-container">
                    <div class="main-image-container">
                        <img src="<?php echo htmlspecialchars($gameData['thumbnail']); ?>" alt="Imagen del juego">
                    </div>
                    <div class="wrapper-features">
                        <div class="genre">
                            <p class="genre-design">Genre</p>
                            <p><?php echo isset($gameData['genre']) ? htmlspecialchars($gameData['genre']) : 'Género no disponible'; ?></p>
                        </div>
                        <div class="platform">
                            <p>Platform</p>
                            <p><?php echo isset($gameData['platform']) ? htmlspecialchars($gameData['platform']) : 'Plataforma no disponible'; ?></p>
                        </div>
                        <div class="publisher">
                            <p>Publisher</p>
                            <p class="publisher"><?php echo isset($gameData['publisher']) ? htmlspecialchars($gameData['publisher']) : 'Publicador no disponible'; ?></p>
                        </div>
                        <div class="developer">
                            <p>Developer</p>
                            <p><?php echo isset($gameData['developer']) ? htmlspecialchars($gameData['developer']) : 'Desarrollador no disponible'; ?></p>
                        </div>
                        <div class="release-date">
                            <p>Release Date</p>
                            <p class="release-date"><?php echo isset($gameData['release_date']) ? htmlspecialchars($gameData['release_date']) : 'Fecha de lanzamiento no disponible'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <p>Imagen del juego no disponible</p>
        <?php } ?>
        
        
        <div class="wrapper-features-description-images">
            <p class="game-description"><?php echo isset($gameData['description']) ? htmlspecialchars($gameData['description']) : 'Descripción no disponible'; ?></p>

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

        <?php if (isset($gameData['minimum_system_requirements'])) { ?>
            <h2>System requirements</h2>
                <div class="requirements">
                    <p>
                        <?php echo isset($gameData['minimum_system_requirements']['os']) ? htmlspecialchars($gameData['minimum_system_requirements']['os']) : 'Requisitos mínimos del sistema operativo no disponibles'; ?>
                        <img src="../../public/svg/os-icon.svg" alt="" class="os-icon">
                    </p>
                    <p>
                        <?php echo isset($gameData['minimum_system_requirements']['processor']) ? htmlspecialchars($gameData['minimum_system_requirements']['processor']) : 'Requisitos mínimos del procesador no disponibles'; ?>
                        <img src="../../public/svg/cpu-processor-icon.svg" alt="" class="processor-icon">
                    </p>
                    <p>
                        <?php echo isset($gameData['minimum_system_requirements']['memory']) ? htmlspecialchars($gameData['minimum_system_requirements']['memory']) : 'Requisitos mínimos de memoria no disponibles'; ?>
                        <img src="../../public/svg/ram-memory-icon.svg" alt="" class="memory-icon">
                    </p>
                    <p>
                        <?php echo isset($gameData['minimum_system_requirements']['graphics']) ? htmlspecialchars($gameData['minimum_system_requirements']['graphics']) : 'Requisitos mínimos de gráficos no disponibles'; ?>
                        <img src="../../public/svg/graphics-card-icon.svg" alt="" class="graphics-card-icon">
                    </p>
                    <p>
                        <?php echo isset($gameData['minimum_system_requirements']['storage']) ? htmlspecialchars($gameData['minimum_system_requirements']['storage']) : 'Requisitos mínimos de almacenamiento no disponibles'; ?>
                        <img src="../../public/svg/ssd-icon.svg" alt="" class="storage-icon">
                    </p>
                </div>
            <?php } else { ?>
                <div class="requirements">
                    <p>Requisitos mínimos del sistema no disponibles</p>
                </div>
            <?php } ?>
    </div>
</body>
</html>