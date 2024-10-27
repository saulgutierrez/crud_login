<?php
    # Obtenemos las categorias desde la API
    $categories_url = "https://www.freetogame.com/api/games";
    $categories_response = file_get_contents($categories_url);

    if ($categories_response == FALSE) {
        die("Error al consumir la API de categorias");
    }

    $categories_data = json_decode($categories_response, TRUE);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error al decodificar el JSON de categorias: " . json_last_error_msg());
    }

    # Extraemos las categorías únicas, las convertimos a minúsculas y reemplazamos espacios por guiones
    $categories = array_unique(array_map(function($genre) {
        return strtolower(str_replace(' ', '-', $genre));
    }, array_column($categories_data, 'genre')));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../public/js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <title>Games DB</title>
</head>
<body>
    <nav>
        <a href="index.php" class="logo">
            <div class="imgBox">
                <img src="../public/svg/gamepad.svg" alt="">
            </div>
            <div>Games DB</div>
        </a>
        <form id="search-form" class="search-form">
            <input type="text" placeholder="Ingrese un titulo..." id="search-input">
            <button type="submit" class="search-btn">
                <div class="imgBox">
                    <img src="../public/svg/search.svg" alt="">
                </div>
                <div>Buscar</div>
            </button>
        </form>
        <ul class="navbar">
            <div class="custom-select" style="width: 160px;">
                <select id="category-select" name="category-select">
                    <option value="">Free Games</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <li class="explore hidden">
                <a href="#">
                    <div class="imgBox">
                        <img src="../public/svg/explore.svg" alt="">
                    </div>
                    <div>Explorar</div>
                </a>
            </li>
            <li class="hidden">
                <a href="../src/views/faq.php">
                    <div class="imgBox">
                        <img src="../public/svg/faq.svg" alt="">
                    </div>
                    <div>FAQ</div>
                </a>
            </li>
            <li class="forum hidden">
                <a href="../src/views/login.php">
                    <div class="imgBox">
                        <img src="../public/svg/forum.svg" alt="">
                    </div>
                    <div>Foro</div>
                </a>
            </li>
            <li class="menu"><img src="../public/svg/menu.svg" class="menu-icon" alt=""></li>
            <div class="square-menu hidden"></div>
            <div class="menu-opciones hidden"></div>
        </ul>
    </nav>
    <script src="../src/ui/navbar.js"></script>
    <script src="../src/ui/custom-select-to-filter-game.js"></script>
    <script src="../src/handlers/filter-game-by-category.js"></script>
</body>
</html>