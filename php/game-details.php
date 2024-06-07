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
    <h1><?php echo htmlspecialchars($gameData['title']); ?></h1>
    <img src="<?php echo htmlspecialchars($gameData['thumbnail']); ?>" alt="Imagen del juego">
    <p><?php echo htmlspecialchars($gameData['description']);?></p>
    <p><?php echo htmlspecialchars($gameData['genre']);?></p>
    <p><?php echo htmlspecialchars($gameData['platform']);?></p>
    <p><?php echo htmlspecialchars($gameData['publisher']);?></p>
    <p><?php echo htmlspecialchars($gameData['developer']);?></p>
    <p><?php echo htmlspecialchars($gameData['release_date']);?></p>
    <p><?php echo htmlspecialchars($gameData['minimum_system_requirements']['os']);?></p>
    <p><?php echo htmlspecialchars($gameData['minimum_system_requirements']['processor']);?></p>
    <p><?php echo htmlspecialchars($gameData['minimum_system_requirements']['memory']);?></p>
    <p><?php echo htmlspecialchars($gameData['minimum_system_requirements']['graphics']);?></p>
    <p><?php echo htmlspecialchars($gameData['minimum_system_requirements']['storage']);?></p>
    <img src="<?php echo htmlspecialchars($gameData['screenshots'][0]['image']); ?>" alt="Imagen del juego">
    <img src="<?php echo htmlspecialchars($gameData['screenshots'][1]['image']); ?>" alt="Imagen del juego">
    <img src="<?php echo htmlspecialchars($gameData['screenshots'][2]['image']); ?>" alt="Imagen del juego">
</body>
</html>