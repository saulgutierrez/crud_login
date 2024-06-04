<?php include "includes/navbar.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <?php
        # Hacemos uso de una API gratuita para mostrar datos de los juegos
        $url = "https://www.freetogame.com/api/games";
        $response = file_get_contents($url);

        if ($response == FALSE) {
            die("Error al consumir la API");
        }

        $data = json_decode($response, TRUE);
    ?>
    <div class="card-container">
        <?php foreach ($data as $item): ?>
        <div class="card">
            <?php if (!empty($item['thumbnail'])): ?>
                <img src="<?php echo htmlspecialchars($item['thumbnail']);?>" alt="Imagen">
            <?php endif; ?>
            <div class="card-title"><?php echo htmlspecialchars($item['title']); ?></div>
            <div class="card-description"><?php echo htmlspecialchars($item['short_description']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>