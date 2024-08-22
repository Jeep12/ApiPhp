-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-08-2024 a las 00:05:05
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
-- Base de datos: `rotiseria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre_categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre_categoria`) VALUES
(2, 'Bebida'),
(3, 'Bebida Alcoholica'),
(9, 'Comida'),
(10, 'Postre'),
(11, 'COMIDA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_comida_fk` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `comentario` varchar(535) NOT NULL,
  `valoracion` int(11) NOT NULL,
  `fecha` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_comida_fk`, `usuario`, `comentario`, `valoracion`, `fecha`) VALUES
(167, 40, 'Juan', 'asd', 3, '23/11/2021'),
(168, 40, 'Juan', 'asd', 3, '23/11/2021'),
(171, 47, 'Juan', 'Muy buena', 3, '24/11/2021'),
(174, 41, 'admin', 'Comentario helado', 1, '24/11/2021'),
(175, 41, 'admin', 'Comentario helado 2', 1, '24/11/2021'),
(176, 39, 'admin', 'Comentario pizza', 5, '24/11/2021'),
(177, 42, 'admin', 'Comentario sandwich 1', 5, '24/11/2021'),
(178, 42, 'admin', 'Comentario sandwich 2', 5, '24/11/2021'),
(179, 42, 'admin', 'Comentario sandwich 3', 5, '24/11/2021'),
(180, 49, 'admin', 'WDEQWEQW', 4, '23/10/2023'),
(181, 49, 'admin', 'WDEQWEQW', 4, '23/10/2023'),
(182, 39, 'admin', 'hghghgh', 4, '23/10/2023'),
(183, 39, 'admin', 'hghghgh', 4, '23/10/2023'),
(184, 39, 'admin', 'hghghgh', 4, '23/10/2023'),
(185, 39, 'admin', 'hghghgh', 4, '23/10/2023'),
(186, 39, 'admin', 'hghghgh', 4, '23/10/2023'),
(187, 39, 'admin', 'Hola', 3, '23/10/2023'),
(188, 39, 'admin', 'Hola', 3, '23/10/2023'),
(189, 39, 'admin', 'Hola', 3, '23/10/2023'),
(190, 39, 'admin', 'Hola', 3, '23/10/2023'),
(191, 39, 'admin', 'Hola', 3, '23/10/2023'),
(192, 39, 'admin', 'Hola', 3, '23/10/2023'),
(193, 39, 'admin', 'Hola', 3, '23/10/2023'),
(194, 39, 'admin', 'Hola', 3, '23/10/2023'),
(195, 39, 'admin', 'Hola', 3, '23/10/2023'),
(196, 39, 'admin', 'Hola', 3, '23/10/2023'),
(197, 39, 'admin', 'Hola', 3, '23/10/2023'),
(198, 39, 'admin', 'Hola', 3, '23/10/2023'),
(199, 39, 'admin', 'Hola', 3, '23/10/2023'),
(200, 39, 'admin', 'Hola', 3, '23/10/2023'),
(201, 39, 'admin', 'weqw', 2, '23/10/2023'),
(202, 39, 'admin', 'weqw', 2, '23/10/2023'),
(203, 50, 'admin', 'MUY PUTA', 1, '23/10/2023'),
(204, 43, 'admin', 'sadasdass', 4, '6/4/2024');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comidas`
--

CREATE TABLE `comidas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `categoria_fk` int(11) NOT NULL,
  `detalle` varchar(535) NOT NULL,
  `imagen` varchar(400) DEFAULT NULL,
  `precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comidas`
--

INSERT INTO `comidas` (`id`, `titulo`, `categoria_fk`, `detalle`, `imagen`, `precio`) VALUES
(39, 'Pizza a la española', 9, 'Pizza española, 50 g de salami\r\n50 g de chorizo\r\n50 g de beicon (tocino o tocineta)\r\n100 g de queso emmental\r\n150 g de queso mozzarella\r\n200 ml de tomate triturado o salsa de tomate\r\nPimienta\r\nOrégano\r\nSal\r\nAceite de oliv', 'images/piza.jpg', 720),
(40, 'Empanadas Jamon y Queso', 9, 'Empanadas de jamon y queso, fritas en grasa. el precio es por empanada', 'images/empanadas.jpg', 140),
(41, 'Helado Chino', 10, 'Helado marca Chino de todos los gustos. el precio es por 1/4', 'images/heladochino.jpg', 400),
(42, 'Sandwich de Carne', 9, 'Sandwich de mila de carne vacuna, con jamon, queso, tomate, lechuga y pan fresco', 'images/sandwich-de-milanesa.jpg', 755),
(43, 'Cerveza Quilmes', 3, 'Cerveza quilmes rubia de litro.', 'images/quilmes-cervezajpg.jpg', 250),
(44, 'Coca Cola clasica', 2, 'Coca cola de 1litro y medio clasica', 'images/cocacola.jpg', 250),
(45, 'Empanadas de carne', 9, 'Empanadas de carne, con aceitunas,huevo,morron,cebolla. el precio es por empanada', 'images/empanadacarne.jpg', 120),
(47, 'Tarta ', 9, 'Tarta de jamon y queso. ', 'images/tarta.jpg', 560),
(49, 'KEVIN EL PUTO', 3, 'PUTO ', NULL, 1),
(50, 'AYLEN ', 10, 'PUTA', 'images/alas.png', 1),
(51, 'abigail', 3, 'trola', 'images/bg2.jpg', 1),
(52, 'pizza', 11, 'ASa', NULL, 111);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `mensaje` varchar(535) NOT NULL,
  `fecha` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `mensaje`, `fecha`) VALUES
(9, 'Cerrado', 'El dia de hoy el delivery se encuntra cerrado.\r\nVolveremos a abrir en febrero', '23-11-2021'),
(10, 'OFERTA PIZZA JAMON Y QUESO', 'Tenemos 2 x 1 la pizza de jamon y queso, no se la pierda\r\nclick  <a href=\"productos\">aqui</a> para ir a ver productos', '23-11-2021'),
(11, 'CERVEZA SIN STOCK', 'La cerveza linea Quilmes actualmente no tiene stock', '23-11-2021'),
(12, 'Cigarrillos', 'Pronto venderemos cigarrillos tambien', '23-11-2021'),
(13, 'Nuevo telefono de contacto', 'Tenemos otro nuevo telefono de contacto, tel: 2938 - 02429393', '23-11-2021'),
(14, 'Noticia numero no se', 'alalalla', '23-11-2021'),
(15, 'kevin el trolazo', 'trolazoo', '23-10-2023');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `nick` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `emailVerified` tinyint(1) DEFAULT 0,
  `code_verification` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `admin`, `nick`, `nombre`, `apellido`, `direccion`, `telefono`, `email`, `password`, `emailVerified`, `code_verification`) VALUES
(81, 0, 'pruebaemail', 'email1', 'emai', 'Calle Falsa 123   ', '123456789', 'encabojuan@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$MEl5clpvQVlncG51eVZlcQ$+oMwqw/5HIACWRkhdXMrmhw6uue9gp/HoR3LmOC7nV0', 1, '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comida_fk` (`id_comida_fk`);

--
-- Indices de la tabla `comidas`
--
ALTER TABLE `comidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_fk` (`categoria_fk`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT de la tabla `comidas`
--
ALTER TABLE `comidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_comida_fk`) REFERENCES `comidas` (`id`);

--
-- Filtros para la tabla `comidas`
--
ALTER TABLE `comidas`
  ADD CONSTRAINT `comidas_ibfk_1` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
