-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 10-09-2024 a las 03:25:20
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crud_login`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(255) NOT NULL,
  `descripcion_categoria` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `descripcion_categoria`) VALUES
(1, 'MMORPG', 'Videojuegos de rol que permiten a miles de jugadores introducirse en un mundo virtual de forma simultánea a través de internet e interactuar entre ellos.'),
(2, 'Shooter', 'Tienen la característica común de permitir controlar un personaje que, por norma general, dispone de un arma que puede ser disparada a voluntad.'),
(3, 'Strategy', 'Requieren que el jugador ponga en práctica sus habilidades de planeamiento para maniobrar, gestionando recursos de diverso tipo para conseguir la victoria.'),
(4, 'MOBA', 'Los jugadores controlan a un solo personaje, generalmente con poderes y capacidades únicas en un equipo, que compite contra otro equipo de jugadores, normalmente del mismo número. El objetivo es destruir la estructura principal del equipo oponente con la ayuda de unidades controladas por computadora generadas periódicamente que avanzan a lo largo de caminos establecidos.'),
(5, 'Racing', 'Se imitan competencias entre vehículos. Usualmente el objetivo es recorrer cierta distancia o ir de un sitio hacia otro en el menor tiempo posible, como en el automovilismo y el motociclismo.'),
(6, 'Sports', 'Simula el campo de deporte. Algunos videojuegos resaltan en realidad el campo de juego, mientras que otros destacan la estrategia detrás del deporte.'),
(7, 'Social', 'Subgénero de los videojuegos de simulación de vida que exploran las interacciones sociales entre distintas vidas artificiales.'),
(8, 'Sandbox', 'Es un estilo de videojuego caracterizado por dar al jugador un alto grado libertad para ser creativo a la hora de completar tareas hacia un objetivo dentro del juego, o simplemente para jugar sin restricciones.'),
(9, 'Open-World', ' Es aquel que ofrece al jugador la posibilidad de moverse libremente por un mundo virtual y alterar cualquier elemento a su voluntad.'),
(10, 'Survival', 'Ambientado en un ambiente hostil, intenso y de mundo abierto, donde los jugadores generalmente comienzan con equipos mínimos y se les exige que recolecten recursos, herramientas de artesanía, armas y refugio, y sobrevivan el mayor tiempo posible.'),
(11, 'PVP', 'Estos títulos enfrentan a personas en un escenario competitivo. Estimulan el desarrollo de habilidades y la formulación de estrategias y pueden fomentar enormes espectros de habilidad entre los jugadores.'),
(12, 'PVE', 'El término PvE son las siglas en inglés de Player vs Environment y se refiere al jugador contra el entorno. En este caso, el jugador de un videojuego compite contra el mundo del juego y el ordenador, no contra otros jugadores.'),
(13, 'Pixel', 'Forma de arte digital que utiliza imágenes pequeñas y detalladas creadas con píxeles individuales. A diferencia de la gráfica de alta resolución, el pixel art se enfoca en la estética retro, emulando los gráficos de videojuegos y ordenadores de las décadas pasadas.'),
(14, 'Voxel', 'Utilizan gráficos basados en cubos para crear mundos en 3D'),
(16, 'Turn-based', 'Tipo de juego en el que los jugadores se turnan para realizar sus movimientos o tomar decisiones. Se caracteriza por una mecánica de juego secuencial, donde cada jugador tiene la oportunidad de actuar o tomar decisiones antes de que el siguiente jugador tome su turno.'),
(17, 'First-person', 'Suelen estar basados ​​en avatares, en los que el juego muestra lo que el avatar del jugador vería con sus propios ojos. Por lo tanto, en muchos juegos los jugadores no suelen ver el cuerpo del avatar, aunque sí pueden ver sus armas o manos.'),
(18, 'Third-person', 'Muestra al protagonista desde una perspectiva por encima del hombro o detrás de la espalda.'),
(19, 'Top-down', 'La perspectiva que ve el jugador es desde arriba, mirando hacia abajo, también conocida como vista de pájaro o vista de helicóptero.'),
(21, 'Space', 'Los juegos de exploración espacial se pueden encontrar en varios géneros, incluidos los de estrategia, simulación, acción, disparos en primera persona y juegos de rol, lo que brinda a los jugadores una experiencia de juego diversa.'),
(23, 'Side-scroller', 'Juego visto desde un ángulo de cámara lateral donde la pantalla sigue al jugador mientras se mueve hacia la izquierda o la derecha.'),
(24, 'Superhero', 'Subgénero de ficción especulativa que examina las aventuras, personalidades y ética de luchadores contra el crimen disfrazados, conocidos como superhéroes, que a menudo poseen poderes sobrehumanos y luchan contra criminales con poderes similares conocidos como supervillanos.'),
(25, 'Permadeath', 'No existen vidas ni puntos de guardado, una vez muerto el personaje se elimina permanentemente del juego, por lo que es necesario comenzar desde el principio.'),
(26, 'Card', 'Cualquier juego que utiliza naipes como dispositivo principal con el que se juega, ya sean cartas de diseño tradicional o creadas específicamente para el juego'),
(27, 'Battle-royale', 'Combina los elementos de un videojuego de supervivencia con la jugabilidad de un último jugador en pie.'),
(28, 'MMO', 'Videojuegos en línea con una gran cantidad de jugadores que interactúan en el mismo mundo de juego en línea'),
(29, 'MMOFPS', 'Videojuego de disparos en tiempo real, donde el juego muestra lo que vería el avatar con sus propios ojos, en la que una gran cantidad de jugadores interactúan simultáneamente entre sí en un mundo virtual.'),
(30, 'MMOTPS', 'Videojuego de disparos en tiempo real, donde el juego muestra al protagonista en una perspectiva por encima del hombro, en la que una gran cantidad de jugadores interactúan simultáneamente entre sí en un mundo virtual.'),
(31, '3D', 'Los objetos, personajes y entornos se representan en tres dimensiones, lo que significa que tienen profundidad, altura y anchura, en lugar de verse en una superficie plana.'),
(32, '2D', 'Representa imágenes usando el ancho y el largo del espacio, pero no la profundidad.'),
(33, 'Anime', 'Videojuegos con estilo visual basado en películas y programas televisivos de animación japonesa.'),
(34, 'Fantasy', 'Subgénero de la ficción fantástica que conecta a los jugadores con un mundo artístico totalmente alejado de la realidad.'),
(35, 'Sci-fi', 'Los juegos de ciencia ficción suelen incluir temas relacionados con salvar planetas, galaxias o el universo de la destrucción a manos de otros humanos, inteligencia artificial o seres extraterrestres hostiles, y culminan en emocionantes escenas. A veces incluyen la exploración pacífica de lo desconocido como una parte menor del juego, pero rara vez giran en torno a ella, a diferencia de las obras de ciencia ficción de otros medios.'),
(36, 'Fighting', 'Videojuego que se basa en manejar un luchador o un grupo de luchadores, ya sea dando golpes, usando poderes mágicos o armas, arrojando objetos o ejecutando proyecciones.'),
(37, 'Action-RPG', 'Tipo de videojuegos del género RPG que se caracterizan por ofrecer combates en tiempo real. Estos juegos ofrecen un sistema de combate similar a los de tipo hack and slash o a los de tipo shooter.'),
(38, 'Action', 'Se enfocan en desafiar la velocidad, destreza y tiempo de reacción del jugador.'),
(39, 'Military', 'Juego de estrategia en el que dos o más jugadores comandan fuerzas armadas opuestas en una simulación de un conflicto armado.'),
(40, 'Martial-Arts', 'Videojuego que se basa en manejar un luchador o un grupo de luchadores, utilizando diversas técnicas de arte marcial para combatir contra un oponente.'),
(41, 'Flight', 'Un videojuego de simulación de vuelo se refiere a la simulación de varios aspectos del vuelo o del entorno de vuelo para fines distintos al entrenamiento de vuelo o el desarrollo de aeronaves.'),
(42, 'Low-spec', 'Videojuegos simples que funcionarán en dispositivos de gama muy baja.'),
(43, 'Tower-Defense', 'Subgénero del videojuego de estrategia en el que el objetivo es defender los territorios o las posesiones de un jugador mediante la obstrucción de los atacantes enemigos, que generalmente se logra mediante la colocación de estructuras defensivas alrededor y en su trayectoria de ataque.'),
(44, 'Horror', 'Género de videojuego centrado en la ficción de terror y que normalmente está diseñado para asustar al jugador. '),
(45, 'MMORTS', 'Videojuegos de estrategia en tiempo real que permiten a miles de jugadores introducirse en un mundo virtual de forma simultánea a través de Internet, e interactuar entre ellos en tiempo real.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_post` int(255) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL,
  `id_autor_comentario` int(11) NOT NULL,
  `autor_comentario` varchar(255) NOT NULL,
  `comentario` text NOT NULL,
  `foto_comentario` text NOT NULL,
  `fecha_publicacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `liked_by` int(11) NOT NULL,
  `liked_id_post` int(255) NOT NULL,
  `btn_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes_comentarios`
--

CREATE TABLE `likes_comentarios` (
  `id_like_comentario` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL,
  `btn_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `tipo_notificacion` varchar(50) NOT NULL,
  `mensaje` text NOT NULL,
  `leida` tinyint(1) NOT NULL,
  `fecha_notificacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

CREATE TABLE `post` (
  `id_post` int(255) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `autor_post` varchar(255) NOT NULL,
  `titulo_post` varchar(255) NOT NULL,
  `contenido_post` text NOT NULL,
  `foto_post` text NOT NULL,
  `fecha_publicacion` text NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `siguiendo`
--

CREATE TABLE `siguiendo` (
  `id_seguidor` int(11) NOT NULL,
  `id_seguido` int(11) NOT NULL,
  `nombre_usuario_seguido` varchar(200) NOT NULL,
  `nombre_seguido` varchar(255) NOT NULL,
  `apellido_seguido` varchar(255) NOT NULL,
  `foto_seguido` text NOT NULL,
  `btn_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `contrasenia` varchar(200) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(255) DEFAULT NULL,
  `fotografia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_autor` (`id_autor`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD KEY `liked_by` (`liked_by`,`liked_id_post`),
  ADD KEY `liked_id_post` (`liked_id_post`);

--
-- Indices de la tabla `likes_comentarios`
--
ALTER TABLE `likes_comentarios`
  ADD PRIMARY KEY (`id_like_comentario`),
  ADD KEY `id` (`id`),
  ADD KEY `id_comentario` (`id_comentario`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_autor` (`id_autor`);

--
-- Indices de la tabla `siguiendo`
--
ALTER TABLE `siguiendo`
  ADD KEY `id_seguidor` (`id_seguidor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `likes_comentarios`
--
ALTER TABLE `likes_comentarios`
  MODIFY `id_like_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`liked_by`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`liked_id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `likes_comentarios`
--
ALTER TABLE `likes_comentarios`
  ADD CONSTRAINT `likes_comentarios_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_comentarios_ibfk_2` FOREIGN KEY (`id_comentario`) REFERENCES `comentarios` (`id_comentario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `siguiendo`
--
ALTER TABLE `siguiendo`
  ADD CONSTRAINT `siguiendo_ibfk_1` FOREIGN KEY (`id_seguidor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
