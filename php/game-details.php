<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle</title>
</head>
<body>
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

    <h1><?php echo htmlspecialchars($gameData['title']); ?></h1>
    <img src="<?php echo htmlspecialchars($gameData['thumbnail']); ?>" alt="Imagen del juego">
    <p><?php echo htmlspecialchars($gameData['description']); ?></p>
</body>
</html>