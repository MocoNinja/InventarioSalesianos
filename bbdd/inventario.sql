-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 09-05-2017 a las 09:32:56
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

--
-- Volcado de datos para la tabla `Impresoras`
--

INSERT INTO `Impresoras` (`idMaterial`, `tipo`, `consumible`) VALUES
(3, 'Tinta', 'Brother');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Incidencias`
--

CREATE TABLE `Incidencias` (
  `idIncidencia` int(11) NOT NULL,
  `incidencia` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fechaIncidencia` datetime NOT NULL,
  `fechaSolucion` datetime DEFAULT NULL,
  `solucion` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `idMaterial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Incidencias`
--

INSERT INTO `Incidencias` (`idIncidencia`, `incidencia`, `fechaIncidencia`, `fechaSolucion`, `solucion`, `idMaterial`) VALUES
(1, 'Explosíon de la PIA', '2017-05-03 00:00:00', '2017-05-20 00:00:00', 'Cambiarla', 1),
(2, 'VIRUS!!!!', '2017-05-03 00:00:00', NULL, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Licencias`
--

CREATE TABLE `Licencias` (
  `idSoftware` int(11) NOT NULL,
  `idMaterial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Licencias`
--

INSERT INTO `Licencias` (`idSoftware`, `idMaterial`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Marcas`
--

CREATE TABLE `Marcas` (
  `idMarca` int(11) NOT NULL,
  `Nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Marcas`
--

INSERT INTO `Marcas` (`idMarca`, `Nombre`) VALUES
(1, 'Samsung'),
(2, 'ASUS'),
(3, 'NVIDIA');

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
  `fechaBaja` datetime DEFAULT NULL,
  `estado` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Materiales`
--

INSERT INTO `Materiales` (`idMaterial`, `nombre`, `idProveedor`, `idMarca`, `modelo`, `numeroSerie`, `cantidad`, `fechaEntrada`, `idAutorizador`, `idUbicacion`, `numeroInterno`, `garantia`, `fechaBaja`, `estado`, `observaciones`) VALUES
(1, 'Ordenador 1', 2, 2, 'Ordenador sencillito 1', '213ascsg123', 2, '2017-02-14 13:06:23', 3, 1, 'sd234242fw2', 1, '0000-00-00 00:00:00', 'Bien', 'Va bien'),
(2, 'Monitor 1', 2, 1, 'Monitor cutrón', '213asf324fd', 10, '2017-05-05 00:00:00', 2, 1, 'sdcswef234d2e23', 0, NULL, 'Bien', 'nada'),
(3, 'Impresora 1', 2, 3, 'sd3242weffw', '1', 23, '2017-05-11 00:00:00', 1, 1, 'wqedw342dw', 1, NULL, '', ''),
(4, 'Tornillos', 1, 1, 'Tornillos de los buenos', '2131fcw3242d', 3423423, '2017-05-09 00:00:00', 2, 1, 'sdfwef2342', 1, NULL, '', 'Que nose piefan!!!!11!');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Monitores`
--

CREATE TABLE `Monitores` (
  `idMaterial` int(11) NOT NULL,
  `tamanyo` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(35) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Monitores`
--

INSERT INTO `Monitores` (`idMaterial`, `tamanyo`, `tipo`) VALUES
(2, '20 pulgadas', 'Monitor de pie');

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

--
-- Volcado de datos para la tabla `Ordenadores`
--

INSERT INTO `Ordenadores` (`idMaterial`, `placa`, `procesador`, `memoriaRAM`, `discoDuro`, `tarjetas`, `identificador`, `dominio`) VALUES
(1, 'ASUS', 'i5 3Ghz', '8 GB DDR4', 'SSD 150 GB', 'Nvidia 1080 GTX', '23fsfdsf34sd', '1h');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Proveedores`
--

CREATE TABLE `Proveedores` (
  `idProveedor` int(11) NOT NULL,
  `nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Proveedores`
--

INSERT INTO `Proveedores` (`idProveedor`, `nombre`) VALUES
(1, 'Talleres Agapito'),
(2, 'M4st3rH4x0r Systems');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Software`
--

CREATE TABLE `Software` (
  `idLicencia` int(11) NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tipolicencia` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fechaCaducidad` datetime DEFAULT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Software`
--

INSERT INTO `Software` (`idLicencia`, `descripcion`, `tipolicencia`, `cantidad`, `fechaCaducidad`, `observaciones`) VALUES
(2, 'Codeblocks', 'GNU', 100, NULL, 'Programar C/C++'),
(3, 'Windows XP', 'Propietaria', 10, '2017-05-31 00:00:00', 'El único sistema decente de Mocosoft');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ubicaciones`
--

CREATE TABLE `Ubicaciones` (
  `idUbicacion` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreAntiguo` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Ubicaciones`
--

INSERT INTO `Ubicaciones` (`idUbicacion`, `nombre`, `nombreAntiguo`) VALUES
(1, 'Aula 00', '00 aluA'),
(2, 'Aula 01', '10 aluA');

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
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`idUsuario`, `nombre`, `apellidos`, `rol`, `username`, `password`) VALUES
(1, 'Federico', 'García Pérez', 'administrador', 'fgp', 'fgp'),
(2, 'Dolores', 'Fuertes Debarriga', 'sat', 'dfd', 'dfd'),
(3, 'Lucas', 'Lucas Lucas', 'profesor', 'lll', 'lll');

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
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `Incidencias`
--
ALTER TABLE `Incidencias`
  MODIFY `idIncidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `Marcas`
--
ALTER TABLE `Marcas`
  MODIFY `idMarca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `Materiales`
--
ALTER TABLE `Materiales`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `Ordenadores`
--
ALTER TABLE `Ordenadores`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `Software`
--
ALTER TABLE `Software`
  MODIFY `idLicencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `Ubicaciones`
--
ALTER TABLE `Ubicaciones`
  MODIFY `idUbicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
