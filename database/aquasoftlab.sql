-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-03-2025 a las 21:40:32
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aquasoftlab`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `cedula_empleado` varchar(50) NOT NULL,
  `cod_rol` int(50) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `password` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`cedula_empleado`, `cod_rol`, `nombre`, `apellido`, `telefono`, `password`) VALUES
('1234567811', NULL, 'dahiana', 'londono', '3112345681', '$2y$10$icy6kmJRLWSq0lX5EK0p5OHKF3.VLcNKvMsqI6JEILHocqvR3Drxq'),
('1234567892', 10, 'fulano', 'detal', '3112345678', '$2y$10$Br9xjhchAtd/MoUEzwqx6O5/cyQonceUSXCWPObUbGkc9O2CXhPi2'),
('1234567893', NULL, 'eliana', 'sierra', '3112345678', '$2y$10$3xncWWN07JG9FNKql.PICOfAAKUpR2QkODIYpnQQjWSM6sjkZStoG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `cod_empresa` varchar(50) NOT NULL,
  `nombre_empresa` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo_electronico` varchar(50) NOT NULL,
  `cedula_reprelegal` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`cod_empresa`, `nombre_empresa`, `direccion`, `telefono`, `correo_electronico`, `cedula_reprelegal`) VALUES
('emp001', 'panquesito', 'carrera 1 con 2b', '3112345678', 'panquesito@gmail.com', '1234567800'),
('emp002', 'buscalibre', 'carrera 10 # 100', '3112345680', 'buscalibreCol@gmail.com', '1234567800'),
('emp003', 'elevatetech', 'carrera 8 # 100', '3112345680', 'elevatetech@gmail.com', '1234567801'),
('emp004', 'electric', 'carrera 9 # 100', '3112345680', 'electric@gmail.com', '1234567801'),
('emp005', 'pantech', 'carrera 11 # 100', '3112345680', 'pantech@gmail.com', '1234567900');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `muestra`
--

CREATE TABLE `muestra` (
  `cod_muestra` varchar(50) NOT NULL,
  `hora_toma` time(6) NOT NULL,
  `info_muestreo` varchar(50) NOT NULL,
  `equipos` varchar(50) NOT NULL,
  `id_pto` varchar(50) NOT NULL,
  `cd_ot` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `naturalezamuestra`
--

CREATE TABLE `naturalezamuestra` (
  `id_pto` varchar(50) NOT NULL,
  `nombre_pto` varchar(50) NOT NULL,
  `parametros` varchar(50) NOT NULL,
  `naturaleza_muestra` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordendetrabajo`
--

CREATE TABLE `ordendetrabajo` (
  `cod_ot` varchar(50) NOT NULL,
  `cod_plan_muestreo` varchar(50) NOT NULL,
  `observaciones` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planmuestreo`
--

CREATE TABLE `planmuestreo` (
  `cod_planmuestreo` varchar(50) NOT NULL,
  `empresa.nit_cedula` varchar(50) NOT NULL,
  `fecha_muestreo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reprelegal`
--

CREATE TABLE `reprelegal` (
  `cedula_reprelegal` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reprelegal`
--

INSERT INTO `reprelegal` (`cedula_reprelegal`, `nombre`, `telefono`) VALUES
('1234567800', 'john smith', '3000000001'),
('1234567801', 'Anders Doe', '3000000002'),
('1234567900', 'Anders Doe', '3000000012');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `cod_rol` int(50) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`cod_rol`, `nombre_rol`) VALUES
(10, 'admin'),
(11, 'editor');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`cedula_empleado`),
  ADD KEY `cod_rol` (`cod_rol`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`cod_empresa`),
  ADD KEY `cedula_reprelegal` (`cedula_reprelegal`);

--
-- Indices de la tabla `muestra`
--
ALTER TABLE `muestra`
  ADD PRIMARY KEY (`cod_muestra`);

--
-- Indices de la tabla `naturalezamuestra`
--
ALTER TABLE `naturalezamuestra`
  ADD PRIMARY KEY (`id_pto`);

--
-- Indices de la tabla `ordendetrabajo`
--
ALTER TABLE `ordendetrabajo`
  ADD PRIMARY KEY (`cod_ot`);

--
-- Indices de la tabla `planmuestreo`
--
ALTER TABLE `planmuestreo`
  ADD PRIMARY KEY (`cod_planmuestreo`);

--
-- Indices de la tabla `reprelegal`
--
ALTER TABLE `reprelegal`
  ADD PRIMARY KEY (`cedula_reprelegal`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`cod_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `cod_rol` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`cod_rol`) REFERENCES `roles` (`cod_rol`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`cedula_reprelegal`) REFERENCES `reprelegal` (`cedula_reprelegal`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
