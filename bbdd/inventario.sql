-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 09-05-2017 a las 08:22:16
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Impresoras`
--

CREATE TABLE `Impresoras` (
  `idMaterial` int(11) NOT NULL,
  `tipo` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `consumible` varchar(25) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Incidencias`
--

CREATE TABLE `Incidencias` (
  `idIncidencia` int(11) NOT NULL,
  `fechaIncidencia` datetime NOT NULL,
  `fechaSolucion` datetime NOT NULL,
  `solucion` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `idMaterial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Licencias`
--

CREATE TABLE `Licencias` (
  `idSoftware` int(11) NOT NULL,
  `idMaterial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Marcas`
--

CREATE TABLE `Marcas` (
  `idMarca` int(11) NOT NULL,
  `Nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Materiales`
--

CREATE TABLE `Materiales` (
  `idMaterial` int(11) NOT NULL,
  `nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `idMarca` int(11) NOT NULL,
  `modelo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `numeroSerie` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `idAutorizador` int(11) NOT NULL,
  `idUbicacion` int(11) NOT NULL,
  `numeroInterno` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `garantia` tinyint(1) NOT NULL,
  `fechaBaja` datetime NOT NULL,
  `estado` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Monitores`
--

CREATE TABLE `Monitores` (
  `idMaterial` int(11) NOT NULL,
  `tamanyo` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(35) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ordenadores`
--

CREATE TABLE `Ordenadores` (
  `idMaterial` int(11) NOT NULL,
  `placa` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `procesador` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `memoriaRAM` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `discoDuro` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `tarjetas` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `identificador` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `dominio` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Proveedores`
--

CREATE TABLE `Proveedores` (
  `idProveedor` int(11) NOT NULL,
  `nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Software`
--

CREATE TABLE `Software` (
  `idLicencia` int(11) NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tipolicencia` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fechaCaducidad` datetime NOT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ubicaciones`
--

CREATE TABLE `Ubicaciones` (
  `idUbicacion` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreAntiguo` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rol` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Impresoras`
--
ALTER TABLE `Impresoras`
  ADD PRIMARY KEY (`idMaterial`);

--
-- Indices de la tabla `Incidencias`
--
ALTER TABLE `Incidencias`
  ADD PRIMARY KEY (`idIncidencia`);

--
-- Indices de la tabla `Licencias`
--
ALTER TABLE `Licencias`
  ADD PRIMARY KEY (`idSoftware`,`idMaterial`);

--
-- Indices de la tabla `Marcas`
--
ALTER TABLE `Marcas`
  ADD PRIMARY KEY (`idMarca`);

--
-- Indices de la tabla `Materiales`
--
ALTER TABLE `Materiales`
  ADD PRIMARY KEY (`idMaterial`);

--
-- Indices de la tabla `Monitores`
--
ALTER TABLE `Monitores`
  ADD PRIMARY KEY (`idMaterial`);

--
-- Indices de la tabla `Ordenadores`
--
ALTER TABLE `Ordenadores`
  ADD PRIMARY KEY (`idMaterial`);

--
-- Indices de la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  ADD PRIMARY KEY (`idProveedor`);

--
-- Indices de la tabla `Software`
--
ALTER TABLE `Software`
  ADD PRIMARY KEY (`idLicencia`);

--
-- Indices de la tabla `Ubicaciones`
--
ALTER TABLE `Ubicaciones`
  ADD PRIMARY KEY (`idUbicacion`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Impresoras`
--
ALTER TABLE `Impresoras`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Incidencias`
--
ALTER TABLE `Incidencias`
  MODIFY `idIncidencia` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Marcas`
--
ALTER TABLE `Marcas`
  MODIFY `idMarca` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Materiales`
--
ALTER TABLE `Materiales`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Monitores`
--
ALTER TABLE `Monitores`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Ordenadores`
--
ALTER TABLE `Ordenadores`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Software`
--
ALTER TABLE `Software`
  MODIFY `idLicencia` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Ubicaciones`
--
ALTER TABLE `Ubicaciones`
  MODIFY `idUbicacion` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
