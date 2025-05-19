-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2025 a las 00:53:46
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `urbanj`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `imagen_url` varchar(255) NOT NULL,
  `enlace` varchar(255) NOT NULL DEFAULT '#',
  `texto_alt` varchar(100) DEFAULT '',
  `orden` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `banners`
--

INSERT INTO `banners` (`id`, `imagen_url`, `enlace`, `texto_alt`, `orden`) VALUES
(4, 'assets/banner4.jpg', 'suscripcion.php', 'Suscríbete a nuestro boletín', 4),
(5, 'assets/banner5.jpg', 'acerca_de_nosotros.php', 'Conócenos mejor', 5),
(6, 'assets/banner_1747344716.png', 'https://www.canva.com/design/DAGnjKsZNA4/6gQSJT0KG4cEidpQ_lU83Q/edit', 'Mapa', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `calle` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `pais` varchar(100) NOT NULL,
  `codigo_postal` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id`, `usuario_id`, `alias`, `calle`, `ciudad`, `estado`, `pais`, `codigo_postal`) VALUES
(1, 1, 'asdasd', 'sdfsdf', 'sdfsdf', 'Chihuahua', 'España', '32546');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_producto`
--

CREATE TABLE `imagenes_producto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `imagen_url` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes_producto`
--

INSERT INTO `imagenes_producto` (`id`, `producto_id`, `imagen_url`, `orden`) VALUES
(14, 6, 'assets/gorra6.jpg', 1),
(16, 8, 'assets/ropa2.jpg', 1),
(19, 11, 'assets/ropa5.jpg', 1),
(20, 12, 'assets/ropa6.jpg', 1),
(21, 13, 'assets/novedad1.jpg', 1),
(22, 14, 'assets/novedad2.jpg', 1),
(23, 15, 'assets/novedad3.jpg', 1),
(24, 16, 'assets/novedad4.jpg', 1),
(25, 17, 'assets/novedad5.jpg', 1),
(26, 18, 'assets/novedad6.jpg', 1),
(27, 19, 'assets/taza1.jpg', 1),
(28, 20, 'assets/taza2.jpg', 1),
(29, 21, 'assets/taza3.jpg', 1),
(30, 22, 'assets/taza4.jpg', 1),
(31, 23, 'assets/taza5.jpg', 1),
(32, 24, 'assets/taza6.jpg', 1),
(33, 25, 'assets/camisa1.jpg', 1),
(34, 26, 'assets/camisa2.jpg', 1),
(35, 27, 'assets/camisa3.jpg', 1),
(36, 28, 'assets/camisa4.jpg', 1),
(37, 29, 'assets/camisa5.jpg', 1),
(38, 30, 'assets/camisa6.jpg', 1),
(39, 4, 'assets/prd4_681d3815a0ff9.jpeg', 2),
(40, 5, 'assets/prd5_681d3823a2ec4.jpeg', 1),
(41, 7, 'assets/prd7_681d382931f51.jpeg', 1),
(42, 9, 'assets/prd9_681d383172e69.jpeg', 1),
(44, 34, 'assets/prod_34_6822aff281872.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_productos`
--

CREATE TABLE `imagenes_productos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('Pendiente','Pagado','Enviado','Cancelado') DEFAULT 'Pendiente',
  `creado_at` datetime DEFAULT current_timestamp(),
  `actualizado_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `paqueteria` enum('FedEx','Estafeta','UPS') DEFAULT NULL,
  `guia_seguimiento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `estado`, `creado_at`, `actualizado_at`, `paqueteria`, `guia_seguimiento`) VALUES
(1, 1, 99.99, 'Enviado', '2025-05-13 18:39:52', '2025-05-13 18:59:20', 'FedEx', '45645645646');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, 29.99),
(2, 1, 2, 1, 39.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` enum('Gorras','Ropa','Novedades','Accesorios','Tasas','Camisas') NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `ventas` int(11) NOT NULL DEFAULT 0,
  `creado_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rating` decimal(2,1) NOT NULL DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `stock`, `ventas`, `creado_at`, `actualizado_at`, `rating`) VALUES
(4, 'Gorra Urbana 1', 'Gorra estilo snapback uni…', 19.99, 'Gorras', 20, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 4.6),
(5, 'Gorra Urbana 2', 'Gorra estilo trucker…', 17.99, 'Gorras', 25, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 0.7),
(6, 'Gorra Snapback', 'Snapback con logo bordado…', 21.99, 'Gorras', 15, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 4.7),
(7, 'Gorra Denim', 'Denim azul con visera curva…', 24.99, 'Gorras', 10, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 1.6),
(8, 'Gorra Camo', 'Camuflaje urbano resistente…', 22.50, 'Gorras', 12, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 3.6),
(9, 'Gorra Trucker', 'Trucker con red trasera…', 18.75, 'Gorras', 30, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 3.2),
(10, 'Camiseta Logo', 'Camiseta algodón 100% urbana…', 29.99, 'Ropa', 40, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 0.3),
(11, 'Hoodie Básico', 'Sudadera con capucha unisex…', 49.99, 'Ropa', 30, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 1.7),
(12, 'Chamarra Denim', 'Chaqueta denim estilo…', 79.99, 'Ropa', 15, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 2.7),
(13, 'Pantalón Jogger', 'Jogger de algodón slim…', 39.99, 'Ropa', 25, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 3.5),
(14, 'Sudadera Crew', 'Crewneck minimalista…', 44.99, 'Ropa', 20, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 4.4),
(15, 'Chaleco Urbano', 'Chaleco acolchado con líneas…', 54.99, 'Ropa', 10, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 1.6),
(16, 'Novedad 1', 'Lanzamiento edición especial…', 59.99, 'Novedades', 12, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 4.9),
(17, 'Novedad 2', 'Color exclusivo temporada…', 64.99, 'Novedades', 8, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 4.5),
(18, 'Novedad 3', 'Colección cápsula limitada…', 69.99, 'Novedades', 5, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 3.0),
(19, 'Novedad 4', 'Estilo gráfico urbano…', 39.99, 'Novedades', 20, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 1.6),
(20, 'Novedad 5', 'Combinación de materiales…', 49.99, 'Novedades', 15, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 3.7),
(21, 'Novedad 6', 'Serie de estampados únicos…', 54.99, 'Novedades', 10, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 4.0),
(22, 'Taza Logo', 'Taza cerámica logo UrbanJ…', 12.99, 'Tasas', 50, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 3.6),
(23, 'Taza Grafiti', 'Taza con arte grafiti…', 14.99, 'Tasas', 40, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 1.2),
(24, 'Taza Edición', 'Taza edición limitada…', 16.99, 'Tasas', 30, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 5.0),
(25, 'Taza Blanco', 'Taza blanca minimalista…', 11.50, 'Tasas', 60, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 1.4),
(26, 'Taza Negra', 'Taza negra mate urbana…', 13.75, 'Tasas', 45, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 2.0),
(27, 'Taza Premium', 'Taza premium con tapa…', 18.99, 'Tasas', 20, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 0.7),
(28, 'Camisa Polo', 'Polo de algodón transpirable…', 34.99, 'Camisas', 25, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 2.6),
(29, 'Camisa Manga Corta', 'Camisa manga corta gráfica…', 29.99, 'Camisas', 30, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 1.1),
(30, 'Camisa Manga Larga', 'Camisa manga larga básica…', 39.99, 'Camisas', 20, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 2.4),
(31, 'Camisa Denim', 'Camisa denim ligero…', 44.99, 'Camisas', 15, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 3.6),
(32, 'Camisa Sport', 'Camisa deportiva fit…', 32.50, 'Gorras', 18, 0, '2025-05-08 05:33:10', '2025-05-08 06:43:13', 1.2),
(33, 'Camisa Urbana', 'Camisa urbana oversize…', 36.75, 'Camisas', 22, 0, '2025-05-08 05:33:10', '2025-05-08 05:39:43', 4.9),
(34, 'dsfsfds', 'sdfsfdsdf', 65.55, 'Accesorios', 65, 0, '2025-05-13 02:35:30', '2025-05-13 02:35:30', 0.0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `texto` text DEFAULT NULL,
  `imagen_url` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`id`, `titulo`, `texto`, `imagen_url`, `orden`) VALUES
(1, 'Mega Descuentos de Verano', 'Hasta 50% en toda la sección de Gorras', 'assets/promo1.jpg', 1),
(2, 'Lanzamiento Nueva Línea Ropa', 'Descubre nuestra colección urbana primavera', 'assets/promo2.jpg', 2),
(3, 'Tazas Edición Limitada', 'Recoge tu taza exclusiva antes que se agoten', 'assets/promo3.jpg', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `calificacion` decimal(2,1) NOT NULL CHECK (`calificacion` between 0 and 5),
  `comentario` text NOT NULL,
  `fecha_resena` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`id`, `usuario_id`, `producto_id`, `calificacion`, `comentario`, `fecha_resena`, `estado`) VALUES
(1, 1, 7, 4.5, 'Me encanta esta gorra, ¡muy cómoda y estilosa!', '2025-05-08 00:06:12', 1),
(2, 2, 7, 5.0, 'Perfecto ajuste y buena calidad.', '2025-05-08 00:06:12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `direccion1` varchar(255) NOT NULL,
  `direccion2` varchar(255) DEFAULT NULL,
  `codigo_postal` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `usuario`, `pais`, `estado`, `ciudad`, `direccion1`, `direccion2`, `codigo_postal`, `correo`, `password`, `fecha_nacimiento`, `admin`) VALUES
(1, 'Gonzalo', 'Marquez', 'Gaytan', 'Dioscarmesi', 'Mexico', 'Chihuahua', 'Cd. Juarez', 'paseos del real', '', '32546', 'shadowcarmesi@gmail.com', '$2y$10$3WPJTQTyK5Kn.EbMAJnUf.hWicMPKHD3BgaVA4UKk/Mbc/ESpmFBC', '1997-05-30', 1),
(2, 'Juan', 'Pérez', 'Rodríguez', 'juanperez', 'México', 'Chihuahua', 'Ciudad Juárez', 'Calle Falsa 123', 'Col. Centro', '32000', 'juan.perez@example.com', '$2b$12$d5DeykBcWx47pMHNgiPxA.Vk6O1QbwV9EvM0Wlfftk8IrAQAtn3yC', '1985-06-15', 0),
(3, 'María', 'Gómez', 'López', 'mariagomez', 'México', 'Jalisco', 'Guadalajara', 'Av. Revolución 456', NULL, '44100', 'maria.gomez@example.com', '$2b$12$l.muwH9juHP/4/Au/KodNuiCry7rWtzgzfBplAi235ZJCBtcoZamC', '1990-02-28', 0),
(5, 'Lucía', 'Herrera', 'Martínez', 'luciaherrera', 'México', 'Nuevo León', 'Monterrey', 'Blvd. Constitución 1011', NULL, '64000', 'lucia.herrera@example.com', '$2b$12$6k.Aaf0KMLMVG9eis1E3z.AUBTcpSCNJWa4avMl6Z1wXG4zLo9Lum', '1995-07-20', 0),
(6, 'Andrés', 'Flores', 'Ramírez', 'andresflores', 'México', 'Puebla', 'Puebla', '5 de Mayo 202', 'Oficina 3', '72000', 'andres.flores@example.com', '$2b$12$VG1/zUtrRHz/cMOJEv1Vte3.MsFVEX38QFg9Bp1XAjnYXP5ReXaBC', '1988-04-12', 0),
(7, 'Arab', 'Ape', 'Midnight', 'Ejemplo', 'México', 'Chihuahua', 'Juarez', 'Paseos Encinos', 'Paseos del Real', '32546', 'ejemplo@ejemplo.com', '$2y$10$KzR1pt7E9Z96CMvvhtyfE.7wE1yMb6D8Rlo3zFf70AiqYyx7t3lze', '1997-05-30', 0),
(8, 'Arab', 'Ape', 'Midnight', 'Alzar', 'México', 'Chihuahua', 'Juarez', 'Paseos Encinos', 'Paseos del Real', '32546', 'esjemplo@esjemplo.com', '$2y$10$j5j3z2INA9XSr/EjK8i4xeXdVmTCkSwSMnBhpHBwTcmZg6TnBms3u', '1997-05-30', 0),
(9, 'Gonzalo', 'asda', 'Marquez', 'Ejemplo2', 'México', 'Chihuahua', 'Cd. Juarez', 'paseos del real', '', '32546', 'assdasd@ejemplo.com', '$2y$10$Aq/4PzD5/tN2i/.PTsRTv.zu7cqIX3tVR8YiQpjR5g0QJgY12oZ3u', '1997-05-30', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `idx_parent` (`parent_id`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_img_producto` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `fk_categoria_padre` FOREIGN KEY (`parent_id`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL;

--
-- Filtros para la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  ADD CONSTRAINT `imagenes_producto_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD CONSTRAINT `fk_img_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
