-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 16-01-2020 a las 12:16:58
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `empleadosnn`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE IF NOT EXISTS `departamentos` (
  `cod_dpto` varchar(4) NOT NULL DEFAULT '',
  `nombre_dpto` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`cod_dpto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`cod_dpto`, `nombre_dpto`) VALUES
('2B', 'Historia'),
('D001', 'Quimica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE IF NOT EXISTS `empleados` (
  `dni` varchar(9) NOT NULL DEFAULT '',
  `nombre` varchar(40) DEFAULT NULL,
  `apellido` varchar(40) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `salario` double DEFAULT NULL,
  PRIMARY KEY (`dni`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`dni`, `nombre`, `apellido`, `fecha_nac`, `salario`) VALUES
('123H', 'Jose', 'Torres', '1989-02-26', 1406.25),
('456L', 'Mario', 'Novoa', '1966-10-11', 900);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emple_depart`
--

CREATE TABLE IF NOT EXISTS `emple_depart` (
  `dni` varchar(9) NOT NULL DEFAULT '',
  `cod_dpto` varchar(4) NOT NULL DEFAULT '',
  `fecha_ini` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`dni`,`cod_dpto`,`fecha_ini`),
  KEY `fk_empledepart_coddpto` (`cod_dpto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `emple_depart`
--

INSERT INTO `emple_depart` (`dni`, `cod_dpto`, `fecha_ini`, `fecha_fin`) VALUES
('123H', '2B', '2009-01-25 00:00:00', NULL),
('456L', '2B', '2016-08-01 00:00:00', '2018-08-02 00:00:00');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `emple_depart`
--
ALTER TABLE `emple_depart`
  ADD CONSTRAINT `fk_empledepart_coddpto` FOREIGN KEY (`cod_dpto`) REFERENCES `departamentos` (`cod_dpto`),
  ADD CONSTRAINT `fk_empledepart_dni` FOREIGN KEY (`dni`) REFERENCES `empleados` (`dni`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
