<?php include "../src/views/includes/navbar.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/index.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="../src/ui/goto-top.js"></script>
    <script src="../src/ui/game-details.js"></script>
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
    <span class="ir-arriba">
        <img src="svg/arrow-up.svg" alt="" class="arrow-up-icon">
    </span>
    <div class="card-container" id="card-container">
        <?php foreach ($data as $item): ?>
        <div class="card">
            <?php if (!empty($item['thumbnail'])): ?>
                <img src="<?php echo htmlspecialchars($item['thumbnail']);?>" alt="Imagen">
            <?php endif; ?>
            <div class="card-title" data-id="<?php echo htmlspecialchars($item['id'])?>"><?php echo htmlspecialchars($item['title']); ?></div>
            <div class="card-description"><?php echo htmlspecialchars($item['short_description']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
<script src="../src/handlers/search-game.js"></script>
</html>