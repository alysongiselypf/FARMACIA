-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2026 a las 03:29:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `farmacia_db`
--
CREATE DATABASE IF NOT EXISTS `farmacia_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `farmacia_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

DROP TABLE IF EXISTS `cita`;
CREATE TABLE `cita` (
  `id` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_doctor` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','atendida','cancelada') NOT NULL DEFAULT 'pendiente',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cita`
--

INSERT INTO `cita` (`id`, `id_paciente`, `id_doctor`, `fecha_cita`, `hora_cita`, `motivo`, `estado`, `creado_en`) VALUES
(2, 1, 3, '2026-06-26', '11:00:00', 'Control General', 'atendida', '2026-06-25 23:52:11'),
(3, 1, 5, '2026-06-27', '13:30:00', 'Control General', 'atendida', '2026-06-26 00:56:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta`
--

DROP TABLE IF EXISTS `consulta`;
CREATE TABLE `consulta` (
  `id` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_doctor` int(11) NOT NULL,
  `fecha_consulta` date NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `diagnostico` text NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `consulta`
--

INSERT INTO `consulta` (`id`, `id_cita`, `id_paciente`, `id_doctor`, `fecha_consulta`, `motivo`, `diagnostico`, `creado_en`) VALUES
(1, 2, 1, 3, '2026-06-26', 'Control General', 'Paciente en buen estado general. Se realiza control médico de rutina, sin hallazgos de importancia. Se recomienda continuar con hábitos saludables y seguimiento periódico.', '2026-06-25 23:59:07'),
(2, 3, 1, 5, '2026-06-27', 'Control General', 'Evaluación de rutina, paciente estable y sin complicaciones.', '2026-06-26 01:02:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

DROP TABLE IF EXISTS `detalle_pedido`;
CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `nombre_producto` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id`, `id_pedido`, `nombre_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 'Amoxicilina', 1, 12.90, 12.90),
(2, 2, 'Paracetamol', 1, 10.00, 10.00),
(3, 2, 'Omeprazol', 1, 16.00, 16.00),
(4, 3, 'Paracetamol', 1, 10.00, 10.00),
(5, 3, 'Amoxicilina', 1, 12.90, 12.90),
(6, 3, 'Ibuprofeno', 1, 9.00, 9.00),
(7, 4, 'Loratadina', 1, 4.00, 4.00),
(8, 4, 'Omeprazol', 1, 16.00, 16.00),
(9, 4, 'Metformina', 1, 24.50, 24.50),
(10, 5, 'Paracetamol', 1, 10.00, 10.00),
(11, 5, 'Amoxicilina', 1, 12.90, 12.90),
(12, 5, 'Ibuprofeno', 1, 9.00, 9.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamento`
--

DROP TABLE IF EXISTS `medicamento`;
CREATE TABLE `medicamento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `clase` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `tipo` enum('medicamento','suplemento') DEFAULT 'medicamento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicamento`
--

INSERT INTO `medicamento` (`id`, `nombre`, `clase`, `stock`, `precio`, `imagen`, `tipo`) VALUES
(1, 'Paracetamol', 'Analgesico', 149, 10.00, 'im1.png', 'medicamento'),
(2, 'Amoxicilina', 'Antibiotico', 199, 12.90, 'im2.png', 'medicamento'),
(3, 'Ibuprofeno', 'Antiinflamatorio', 179, 9.00, 'im3.png', 'medicamento'),
(4, 'Omeprazol', 'Gastroprotector', 119, 16.00, 'im4.png', 'medicamento'),
(5, 'Loratadina', 'Antihistaminico', 119, 4.00, 'im5.png', 'medicamento'),
(6, 'Metformina', 'Antidiabetico', 299, 24.50, 'im6.png', 'medicamento'),
(7, 'Enalapril', 'Antihipertensivo', 133, 6.00, 'im7.png', 'medicamento'),
(8, 'Simvastatina', 'Hipolipemiante', 160, 37.50, 'im8.png', 'medicamento'),
(9, 'Furosemida', 'Diuretico', 90, 10.00, 'im9.png', 'medicamento'),
(10, 'Clopidogrel', 'Antiplaquetario', 75, 15.30, 'im10.png', 'medicamento'),
(11, 'Vitamina D', 'Suplemento vitamínico', 250, 50.90, 'im11.jpg', 'suplemento'),
(12, 'Omega-3', 'Suplemento', 200, 73.80, 'im12.jpg', 'suplemento'),
(13, 'Multivitaminico', 'Suplemento vitamínico', 200, 75.90, 'im13.jpg', 'suplemento'),
(14, 'Calcio', 'Suplemento mineral', 180, 47.90, 'im14.jpg', 'suplemento'),
(15, 'Magnesio', 'Suplemento mineral', 150, 94.90, 'im15.jpg', 'suplemento'),
(16, 'Zinc', 'Suplemento mineral', 220, 39.90, 'im16.jpg', 'suplemento'),
(17, 'Vitamina B12', 'Suplemento vitamínico', 160, 59.90, 'im17.jpg', 'suplemento'),
(18, 'Probioticos', 'Suplemento digestivo', 100, 93.90, 'im18.jpg', 'suplemento'),
(19, 'Vitamina C', 'Suplemento vitamínico', 270, 29.80, 'im19.jpg', 'suplemento'),
(20, 'Colageno', 'Suplemento', 140, 34.90, 'im20.jpg', 'suplemento'),
(21, 'Aspirina', 'Analgesico', 200, 8.50, 'img_1781924121_6a3601197d85e.png', 'medicamento'),
(22, 'Zinc Plus', 'Suplemento Mineral', 150, 45.90, 'img_1781924325_6a3601e5be57a.jpeg', 'suplemento'),
(23, 'Probióticos Digest', 'Suplemento digestivo', 90, 49.90, 'img_1781924996_6a360484e4999.jpeg', 'suplemento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

DROP TABLE IF EXISTS `pedido`;
CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `nombre_envio` varchar(100) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `estado` varchar(30) DEFAULT 'completado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `id_usuario`, `fecha`, `nombre_envio`, `direccion`, `ciudad`, `telefono`, `total`, `estado`) VALUES
(1, 1, '2026-06-18 20:21:08', 'Alyson Perez', 'Av. principal 123', 'Arequipa', '999888777', 12.90, 'completado'),
(2, 1, '2026-06-18 20:38:49', 'Juan Garcia', 'Av. peru 123', 'Arequipa', '999555111', 26.00, 'completado'),
(3, 1, '2026-06-18 21:15:31', 'Maria Medina', 'Av. arequipa 753', 'Arequipa', '999447586', 31.90, 'completado'),
(4, 1, '2026-06-18 21:18:54', 'Juana Castro', 'Av. progreso 489', 'Arequipa', '995847159', 44.50, 'completado'),
(5, 2, '2026-06-18 22:00:12', 'Alejandra Torres', 'Av. peru 123', 'Arequipa', '995847159', 31.90, 'completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

DROP TABLE IF EXISTS `receta`;
CREATE TABLE `receta` (
  `id` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `id_medicamento` int(11) NOT NULL,
  `dosis` varchar(255) NOT NULL,
  `instrucciones` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta`
--

INSERT INTO `receta` (`id`, `id_consulta`, `id_medicamento`, `dosis`, `instrucciones`, `creado_en`) VALUES
(1, 2, 18, '1 dosis al dia', 'Tomar antes de comer', '2026-06-26 01:02:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('paciente','doctor','administrador') NOT NULL DEFAULT 'paciente',
  `especialidad` varchar(100) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `tipo_documento`, `numero_documento`, `fecha_nacimiento`, `nombres`, `apellidos`, `telefono`, `password`, `rol`, `especialidad`, `id_departamento`, `creado_en`) VALUES
(1, 'DNI', '72471842', '2004-11-24', 'Alyson', 'Perez Flores', '999444777', '$2y$10$yZnO3FZ6Ny8eGLfjnysUSum7H0ojih8D2GNJo4IT7mRqgp8wtwNfy', 'paciente', NULL, NULL, '2026-06-18 22:39:19'),
(2, 'DNI', '60820045', '2005-11-12', 'Alejandra', 'Torres Garcia', '951478236', '$2y$10$BA4sZdLUXlgfWzNovrngM.BOsIDu3exmnwHl1ICM1UhHZd1t0vWSa', 'paciente', NULL, NULL, '2026-06-19 02:51:17'),
(3, 'DNI', '12345678', '1994-05-14', 'Luis', 'Fernandez', '956789123', '$2y$10$f/uoC/LQPYqu7nG.6OvuM.Z3IlfnTLcMdA6sNP9BFD6JGQGIRlMR2', 'doctor', 'Pediatria', NULL, '2026-06-20 00:55:34'),
(4, 'DNI', '45678901', '1997-05-18', 'Ana', 'Mendoza', '978123456', '$2y$10$SKWoBvGLqeCrPefWTn76BuR/HyggmqpyWqJ5ePnwBt7xJqiTWf2GC', 'administrador', NULL, NULL, '2026-06-20 00:57:58'),
(5, 'DNI', '78901234', '1995-08-15', 'Miguel', 'Herrera', '987123654', '$2y$10$iyLbVDQcKB3OzZdOyfGVFOaBuhojqifMYgNI8wz1RQd./A67mW3nu', 'doctor', 'Cardiologia', NULL, '2026-06-20 01:32:04');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_doctor` (`id_doctor`);

--
-- Indices de la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cita` (`id_cita`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_doctor` (`id_doctor`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `receta`
--
ALTER TABLE `receta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_medicamento` (`id_medicamento`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `receta`
--
ALTER TABLE `receta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`id_doctor`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `cita` (`id`),
  ADD CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `consulta_ibfk_3` FOREIGN KEY (`id_doctor`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`);

--
-- Filtros para la tabla `receta`
--
ALTER TABLE `receta`
  ADD CONSTRAINT `receta_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`),
  ADD CONSTRAINT `receta_ibfk_2` FOREIGN KEY (`id_medicamento`) REFERENCES `medicamento` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
