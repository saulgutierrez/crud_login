<?php
    if (isset($_GET['category'])) {
        $category = htmlspecialchars($_GET['category']);

        $url = "https://freetogame.com/api/games?category=". urlencode($category);
        $response = file_get_contents($url);

        if ($response == FALSE) {
            die('Error al consultar la API del juego');
        }

        $data = json_decode($response, TRUE);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die('Error al decodificar el JSON de juegos: ' .json_last_error_msg());
        }

        foreach ($data as $item): ?>
            <div class="card">
                <?php if (!empty($item['thumbnail'])): ?>
                    <img src="<?php echo htmlspecialchars($item['thumbnail']); ?>" alt="Imagen del juego">
                <?php endif; ?>
                <div class="card-title" data-id=<?php echo htmlspecialchars($item['id']); ?>>
                    <?php echo htmlspecialchars($item['title']); ?>
                </div>
                <div class="card-description"><?php echo htmlspecialchars($item['short_description']); ?></div>
            </div>
        <?php endforeach;
    }
?>