<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <title>Games DB</title>
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Games DB</a>
        <form id="search-form">
            <input type="text" placeholder="Buscar" id="search-input">
            <button type="submit">Buscar</button>
        </form>
        <ul class="navbar">
            <li class="explore hidden"><a href="#">Explorar</a></li>
            <li class="blog hidden"><a href="../src/views/blog-index.php">Blog</a></li>
            <li class="forum hidden"><a href="../src/views/login.php">Foro</a></li>
            <li class="menu"><img src="../public/svg/menu.svg" class="menu-icon" alt=""></li>
            <div class="square-menu hidden"></div>
            <div class="menu-opciones hidden"></div>
        </ul>
    </nav>
    <script src="../src/helpers/navbar.js"></script>
</body>
</html>