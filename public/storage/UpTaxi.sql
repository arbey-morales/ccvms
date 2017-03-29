-- phpMyAdmin SQL Dump
-- version 4.4.13
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-08-2016 a las 17:57:29
-- Versión del servidor: 5.7.9
-- Versión de PHP: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `UpTaxi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Asignaciones`
--

CREATE TABLE IF NOT EXISTS `Asignaciones` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idOperador` int(11) NOT NULL,
  `idTurno` int(11) NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `terminado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` varchar(45) DEFAULT NULL,
  `terminadoAl` timestamp NULL DEFAULT NULL,
  `terminadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AsignacionUO`
--

CREATE TABLE IF NOT EXISTS `AsignacionUO` (
  `id` int(20) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `IdOperador` int(11) NOT NULL,
  `idTurno` int(11) NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `terminado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'None',
  `creadoUsuario` int(11) DEFAULT NULL,
  `terminadoAl` timestamp NULL DEFAULT NULL,
  `terminadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ChecksOperadores`
--

CREATE TABLE IF NOT EXISTS `ChecksOperadores` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idOperador` int(11) NOT NULL,
  `idMotivoCheckOutIncidente` int(11) DEFAULT NULL,
  `checkIn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `checkOut` timestamp NULL DEFAULT NULL,
  `checkOutIncidente` tinyint(1) DEFAULT '0' COMMENT '0 - Normal  1 - Con incidente',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` varchar(45) DEFAULT NULL,
  `terminadoAl` timestamp NULL DEFAULT NULL,
  `terminadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Clientes`
--

CREATE TABLE IF NOT EXISTS `Clientes` (
  `id` int(11) NOT NULL,
  `idLocalidad` int(11) NOT NULL,
  `direccion` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `numCliente` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `paterno` varchar(45) COLLATE latin1_spanish_ci DEFAULT NULL,
  `materno` varchar(45) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre` varchar(45) COLLATE latin1_spanish_ci DEFAULT NULL,
  `foto` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombreCompleto` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Estados`
--

CREATE TABLE IF NOT EXISTS `Estados` (
  `id` int(11) NOT NULL,
  `idPais` int(11) NOT NULL,
  `clave` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `abreviatura` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IncidenciasOperadores`
--

CREATE TABLE IF NOT EXISTS `IncidenciasOperadores` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idOperador` int(11) NOT NULL,
  `idMotivoIncidencia` int(11) NOT NULL,
  `fechaIncidencia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacion` text NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) NOT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) NOT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IncidenciasUnidades`
--

CREATE TABLE IF NOT EXISTS `IncidenciasUnidades` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idMotivoIncidencia` int(11) NOT NULL,
  `fechaIncidencia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacion` text NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) NOT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) NOT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LineasTelefonicas`
--

CREATE TABLE IF NOT EXISTS `LineasTelefonicas` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `numero` varchar(25) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creadousuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Listas`
--

CREATE TABLE IF NOT EXISTS `Listas` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `folio` varchar(13) DEFAULT NULL,
  `fechaInicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaFin` timestamp NULL DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) NOT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) NOT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Localidades`
--

CREATE TABLE IF NOT EXISTS `Localidades` (
  `id` int(11) NOT NULL,
  `idMunicipio` int(11) NOT NULL,
  `clave` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(110) COLLATE utf8_spanish_ci DEFAULT NULL,
  `latitud` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `longitud` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `altitud` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Marcas`
--

CREATE TABLE IF NOT EXISTS `Marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `slug` varchar(50) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Modelos`
--

CREATE TABLE IF NOT EXISTS `Modelos` (
  `id` int(11) NOT NULL,
  `idMarca` int(5) NOT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `slug` varchar(150) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MotivosAusencia`
--

CREATE TABLE IF NOT EXISTS `MotivosAusencia` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `nombre` varchar(250) DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MotivosCheckoutIncidente`
--

CREATE TABLE IF NOT EXISTS `MotivosCheckoutIncidente` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MotivosIncidencias`
--

CREATE TABLE IF NOT EXISTS `MotivosIncidencias` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `causaSuspencion` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - No  1 - Si',
  `tipo` int(2) NOT NULL COMMENT '1- Operador  2 - Unidad',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) NOT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) NOT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Municipios`
--

CREATE TABLE IF NOT EXISTS `Municipios` (
  `id` int(11) NOT NULL,
  `idEstado` int(11) NOT NULL,
  `clave` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Operadores`
--

CREATE TABLE IF NOT EXISTS `Operadores` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idLocalidad` int(11) NOT NULL,
  `tipoDocumento` int(11) NOT NULL,
  `paterno` varchar(30) DEFAULT NULL,
  `materno` varchar(20) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `numeroLicencia` varchar(20) DEFAULT NULL,
  `venceLicencia` date DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `numeroDocumento` varchar(45) DEFAULT NULL,
  `nombreCompleto` varchar(150) DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `licencia` varchar(250) NOT NULL,
  `comprobanteDomicilio` varchar(250) NOT NULL,
  `ine` varchar(250) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Paises`
--

CREATE TABLE IF NOT EXISTS `Paises` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `abreviatura` varchar(20) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PaseLista`
--

CREATE TABLE IF NOT EXISTS `PaseLista` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idCheckOperador` int(11) DEFAULT NULL,
  `idLista` int(11) DEFAULT NULL,
  `idMotivoAusencia` int(11) DEFAULT NULL,
  `presente` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 - No  1 - Si ',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

CREATE TABLE IF NOT EXISTS `permission_role` (
  `id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_user`
--

CREATE TABLE IF NOT EXISTS `permission_user` (
  `id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Propietarios`
--

CREATE TABLE IF NOT EXISTS `Propietarios` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idLocalidad` int(11) NOT NULL,
  `direccion` varchar(125) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `paterno` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `materno` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `email` varchar(80) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombreCompleto` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `foto` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `licencia` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `comprobanteDomicilio` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `ine` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Radios`
--

CREATE TABLE IF NOT EXISTS `Radios` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `numeroRadio` varchar(45) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RecorridoUnidad`
--

CREATE TABLE IF NOT EXISTS `RecorridoUnidad` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idCheckOperador` int(11) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

CREATE TABLE IF NOT EXISTS `role_user` (
  `id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Servicios`
--

CREATE TABLE IF NOT EXISTS `Servicios` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idCheckOperador` int(11) NOT NULL,
  `idOperador` int(11) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idUbicacion` int(11) NOT NULL,
  `idLineaTelefonica` int(11) NOT NULL,
  `fechaSolicitud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioSolicitud` int(11) DEFAULT NULL,
  `tiempoLlegada` float NOT NULL DEFAULT '0' COMMENT 'En minutos',
  `iniciado` tinyint(1) NOT NULL DEFAULT '1',
  `observaciones` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fechaLlegada` timestamp NULL DEFAULT NULL,
  `usuarioLlegada` int(11) DEFAULT NULL,
  `fechaAbordaje` timestamp NULL DEFAULT NULL,
  `usuarioAbordaje` int(11) DEFAULT NULL,
  `fechaFin` timestamp NULL DEFAULT NULL,
  `usuarioFin` int(11) DEFAULT NULL,
  `cancelado` tinyint(1) DEFAULT '0',
  `fechaCancelado` timestamp NULL DEFAULT NULL,
  `usuarioCancelado` int(11) DEFAULT NULL,
  `fechaModificado` timestamp NULL DEFAULT NULL,
  `usuarioModificado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Sitios`
--

CREATE TABLE IF NOT EXISTS `Sitios` (
  `id` int(11) NOT NULL,
  `idLocalidad` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SuspencionesOperadores`
--

CREATE TABLE IF NOT EXISTS `SuspencionesOperadores` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idOperador` int(11) NOT NULL,
  `idIncidenciaOperador` int(11) DEFAULT NULL,
  `fechaSuspencion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observaciones` text NOT NULL,
  `levantado` tinyint(1) NOT NULL DEFAULT '0',
  `fechaLevantado` timestamp NULL DEFAULT NULL,
  `usuarioLevantado` int(11) DEFAULT NULL,
  `observacionesLevantado` text NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NULL DEFAULT NULL,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SuspencionesUnidades`
--

CREATE TABLE IF NOT EXISTS `SuspencionesUnidades` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idIncidenciaUnidad` int(11) DEFAULT NULL,
  `fechaSuspencion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observaciones` text NOT NULL,
  `levantado` tinyint(1) NOT NULL DEFAULT '0',
  `fechaLevantado` timestamp NULL DEFAULT NULL,
  `usuarioLevantado` int(11) DEFAULT NULL,
  `observacionesLevantado` text NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NULL DEFAULT NULL,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TelefonosClientes`
--

CREATE TABLE IF NOT EXISTS `TelefonosClientes` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idTipoTelefono` int(11) NOT NULL,
  `numero` varchar(15) COLLATE latin1_spanish_ci DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TelefonosOperadores`
--

CREATE TABLE IF NOT EXISTS `TelefonosOperadores` (
  `id` int(11) NOT NULL,
  `idOperador` int(11) DEFAULT NULL,
  `idTipoTelefono` int(11) DEFAULT NULL,
  `numero` varchar(15) COLLATE latin1_spanish_ci DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TelefonosPropietarios`
--

CREATE TABLE IF NOT EXISTS `TelefonosPropietarios` (
  `id` int(11) NOT NULL,
  `idPropietario` int(11) NOT NULL,
  `idTipoTelefono` int(2) NOT NULL,
  `numero` varchar(25) COLLATE latin1_spanish_ci DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TelefonosUsuarios`
--

CREATE TABLE IF NOT EXISTS `TelefonosUsuarios` (
  `id` int(11) NOT NULL,
  `idUsuario` int(10) unsigned NOT NULL,
  `idTipoTelefono` int(11) NOT NULL,
  `numero` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TiposDocumento`
--

CREATE TABLE IF NOT EXISTS `TiposDocumento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TiposTelefonos`
--

CREATE TABLE IF NOT EXISTS `TiposTelefonos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE latin1_spanish_ci DEFAULT NULL,
  `abreviatura` varchar(10) COLLATE latin1_spanish_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TiposUsuarios`
--

CREATE TABLE IF NOT EXISTS `TiposUsuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Turnos`
--

CREATE TABLE IF NOT EXISTS `Turnos` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `nombre` varchar(250) DEFAULT NULL,
  `horaInicio` time DEFAULT NULL,
  `horaFin` time DEFAULT NULL,
  `duracion` int(2) NOT NULL DEFAULT '1',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ubicaciones`
--

CREATE TABLE IF NOT EXISTS `Ubicaciones` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idLocalidad` int(11) NOT NULL,
  `direccion` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `referencias` varchar(175) COLLATE latin1_spanish_ci DEFAULT NULL,
  `latitud` varchar(25) COLLATE latin1_spanish_ci DEFAULT NULL,
  `longitud` varchar(25) COLLATE latin1_spanish_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` datetime DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` datetime DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Unidades`
--

CREATE TABLE IF NOT EXISTS `Unidades` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idPropietario` int(11) NOT NULL,
  `idModelo` int(11) NOT NULL,
  `anioModelo` int(11) DEFAULT NULL,
  `numeroMovil` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `numeroEconomico` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `version` varchar(25) COLLATE latin1_spanish_ci DEFAULT NULL,
  `aireAcondicionado` tinyint(1) NOT NULL DEFAULT '0',
  `parrilla` tinyint(1) NOT NULL DEFAULT '0',
  `docPendientes` tinyint(1) NOT NULL DEFAULT '0',
  `detallesPendientes` varchar(45) COLLATE latin1_spanish_ci DEFAULT NULL,
  `foto` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `contrato` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `seguro` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `pagare` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idLocalidad` int(11) NOT NULL,
  `direccion` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paterno` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `materno` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foto` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) DEFAULT '0',
  `asRoot` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE IF NOT EXISTS `Usuarios` (
  `id` int(11) NOT NULL,
  `idSitio` int(11) NOT NULL,
  `idTipoUsuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `paterno` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `materno` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `password` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `rememberToken` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  `creadoAl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creadoUsuario` int(11) DEFAULT NULL,
  `modificadoAl` timestamp NULL DEFAULT NULL,
  `modificadoUsuario` int(11) DEFAULT NULL,
  `borradoAl` timestamp NULL DEFAULT NULL,
  `borradoUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Asignaciones`
--
ALTER TABLE `Asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_Asi_idx` (`idSitio`),
  ADD KEY `idOperador_Asi_idx` (`idOperador`),
  ADD KEY `idUnidad_Asi_idx` (`idUnidad`),
  ADD KEY `idTurno_AsiG_idx` (`idTurno`);

--
-- Indices de la tabla `AsignacionUO`
--
ALTER TABLE `AsignacionUO`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_idx` (`idSitio`),
  ADD KEY `idOperador_idx` (`IdOperador`),
  ADD KEY `idTurno_idx` (`idTurno`),
  ADD KEY `idUnidad_idx` (`IdUnidad`);

--
-- Indices de la tabla `ChecksOperadores`
--
ALTER TABLE `ChecksOperadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_Check_idx` (`idSitio`),
  ADD KEY `idUnidad_Check_idx` (`idUnidad`),
  ADD KEY `idOperador_Check_idx` (`idOperador`),
  ADD KEY `idMotivoCheckOutIncidente_Check_idx` (`idMotivoCheckOutIncidente`);

--
-- Indices de la tabla `Clientes`
--
ALTER TABLE `Clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idLocalidad_Cli_idx` (`idLocalidad`);

--
-- Indices de la tabla `Estados`
--
ALTER TABLE `Estados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idestados_UNIQUE` (`id`),
  ADD KEY `idPais_idx` (`idPais`);

--
-- Indices de la tabla `IncidenciasOperadores`
--
ALTER TABLE `IncidenciasOperadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idMotivoIncidencia_InciOpe_idx` (`idMotivoIncidencia`),
  ADD KEY `idOperador_InciOpe_idx` (`idOperador`),
  ADD KEY `idSitio_InciOpe_idx` (`idSitio`);

--
-- Indices de la tabla `IncidenciasUnidades`
--
ALTER TABLE `IncidenciasUnidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_IncUni_idx` (`idSitio`),
  ADD KEY `idMotivoIncidencia_IncUni_idx` (`idMotivoIncidencia`),
  ADD KEY `idUnidad_IncUni_idx` (`idUnidad`);

--
-- Indices de la tabla `LineasTelefonicas`
--
ALTER TABLE `LineasTelefonicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_LineaT_idx` (`idSitio`);

--
-- Indices de la tabla `Listas`
--
ALTER TABLE `Listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iSitio_List_idx` (`idSitio`);

--
-- Indices de la tabla `Localidades`
--
ALTER TABLE `Localidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idlocalidades_UNIQUE` (`id`),
  ADD KEY `idMunicipio_idx` (`idMunicipio`);

--
-- Indices de la tabla `Marcas`
--
ALTER TABLE `Marcas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `Modelos`
--
ALTER TABLE `Modelos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idMarca_idx` (`idMarca`),
  ADD KEY `slug` (`slug`);

--
-- Indices de la tabla `MotivosAusencia`
--
ALTER TABLE `MotivosAusencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_idx` (`idSitio`);

--
-- Indices de la tabla `MotivosCheckoutIncidente`
--
ALTER TABLE `MotivosCheckoutIncidente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_idx` (`idSitio`);

--
-- Indices de la tabla `MotivosIncidencias`
--
ALTER TABLE `MotivosIncidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idOperador_MotInci_idx` (`idSitio`);

--
-- Indices de la tabla `Municipios`
--
ALTER TABLE `Municipios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idmunicipios_UNIQUE` (`id`),
  ADD KEY `idEstado_idx` (`idEstado`);

--
-- Indices de la tabla `Operadores`
--
ALTER TABLE `Operadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `borradoAl_UNIQUE` (`borradoAl`),
  ADD KEY `idSitio_idx` (`idSitio`),
  ADD KEY `idLocalidad_Loc_fk_idx` (`idLocalidad`),
  ADD KEY `idTipoDocumento_idx` (`tipoDocumento`);

--
-- Indices de la tabla `Paises`
--
ALTER TABLE `Paises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `abreviatura_UNIQUE` (`abreviatura`),
  ADD KEY `idPais` (`id`);

--
-- Indices de la tabla `PaseLista`
--
ALTER TABLE `PaseLista`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCheckOperador_PaLi_idx` (`idCheckOperador`),
  ADD KEY `idMotivoAusencia_PaLi_idx` (`idMotivoAusencia`),
  ADD KEY `idSitio_PaLi_idx` (`idSitio`),
  ADD KEY `idLista_PL_idx` (`idLista`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indices de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indices de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_user_permission_id_index` (`permission_id`),
  ADD KEY `permission_user_user_id_index` (`user_id`);

--
-- Indices de la tabla `Propietarios`
--
ALTER TABLE `Propietarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_fk_idx` (`idSitio`),
  ADD KEY `idLocalidad_Pro_idx` (`idLocalidad`);

--
-- Indices de la tabla `Radios`
--
ALTER TABLE `Radios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_idx` (`idSitio`);

--
-- Indices de la tabla `RecorridoUnidad`
--
ALTER TABLE `RecorridoUnidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_idx` (`idSitio`),
  ADD KEY `idCheckOperador_RU_idx` (`idCheckOperador`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indices de la tabla `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_index` (`role_id`),
  ADD KEY `role_user_user_id_index` (`user_id`);

--
-- Indices de la tabla `Servicios`
--
ALTER TABLE `Servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_Ser_idx` (`idSitio`),
  ADD KEY `idCliente_Ser_idx` (`idCliente`),
  ADD KEY `idUnidad_Ser_idx` (`idUnidad`),
  ADD KEY `idUbicacion_Ser_idx` (`idUbicacion`),
  ADD KEY `idLineaTelefonica_idx` (`idLineaTelefonica`),
  ADD KEY `idOperador_Ser_idx` (`idOperador`),
  ADD KEY `idCheckOperador_Sev_idx` (`idCheckOperador`);

--
-- Indices de la tabla `Sitios`
--
ALTER TABLE `Sitios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idLocalidad_idx` (`idLocalidad`);

--
-- Indices de la tabla `SuspencionesOperadores`
--
ALTER TABLE `SuspencionesOperadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_SusOpe_idx` (`idSitio`),
  ADD KEY `idOperador_SusOpe_idx` (`idOperador`),
  ADD KEY `idIncidencia_SusOpe_idx` (`idIncidenciaOperador`);

--
-- Indices de la tabla `SuspencionesUnidades`
--
ALTER TABLE `SuspencionesUnidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_SusUni_idx` (`idSitio`),
  ADD KEY `idUnidad_SusUni_idx` (`idUnidad`),
  ADD KEY `idIncidenciaUnidad_SusUni_idx` (`idIncidenciaUnidad`);

--
-- Indices de la tabla `TelefonosClientes`
--
ALTER TABLE `TelefonosClientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTipoTelefono_idx` (`idTipoTelefono`),
  ADD KEY `idCliente_idx` (`idCliente`);

--
-- Indices de la tabla `TelefonosOperadores`
--
ALTER TABLE `TelefonosOperadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTipoTelefono_idx` (`idTipoTelefono`),
  ADD KEY `idOperador_TelOpe_idx` (`idOperador`) USING BTREE;

--
-- Indices de la tabla `TelefonosPropietarios`
--
ALTER TABLE `TelefonosPropietarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPropietario_idx` (`idPropietario`),
  ADD KEY `idTipoTelefono_idx` (`idTipoTelefono`);

--
-- Indices de la tabla `TelefonosUsuarios`
--
ALTER TABLE `TelefonosUsuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTipoTelefono_TelUsu_idx` (`idTipoTelefono`),
  ADD KEY `idUsuario_TelUsu_idx` (`idUsuario`);

--
-- Indices de la tabla `TiposDocumento`
--
ALTER TABLE `TiposDocumento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `TiposTelefonos`
--
ALTER TABLE `TiposTelefonos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `abreviatura` (`abreviatura`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `TiposUsuarios`
--
ALTER TABLE `TiposUsuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Turnos`
--
ALTER TABLE `Turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_Tn_idx` (`idSitio`);

--
-- Indices de la tabla `Ubicaciones`
--
ALTER TABLE `Ubicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCliente_Ubi_idx` (`idCliente`),
  ADD KEY `idLocalidad_Ubi_idx` (`idLocalidad`);

--
-- Indices de la tabla `Unidades`
--
ALTER TABLE `Unidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSitio_idx` (`idSitio`),
  ADD KEY `idPropietario_idx` (`idPropietario`),
  ADD KEY `IdModelo_idx` (`idModelo`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `idSitio_user_idx` (`idSitio`),
  ADD KEY `idLocalidad_User_idx` (`idLocalidad`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTipoUsuario_idx` (`idTipoUsuario`),
  ADD KEY `idSitio_idx` (`idSitio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Asignaciones`
--
ALTER TABLE `Asignaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `AsignacionUO`
--
ALTER TABLE `AsignacionUO`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ChecksOperadores`
--
ALTER TABLE `ChecksOperadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Clientes`
--
ALTER TABLE `Clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Estados`
--
ALTER TABLE `Estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `IncidenciasOperadores`
--
ALTER TABLE `IncidenciasOperadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `IncidenciasUnidades`
--
ALTER TABLE `IncidenciasUnidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `LineasTelefonicas`
--
ALTER TABLE `LineasTelefonicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Listas`
--
ALTER TABLE `Listas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Localidades`
--
ALTER TABLE `Localidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Marcas`
--
ALTER TABLE `Marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Modelos`
--
ALTER TABLE `Modelos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `MotivosAusencia`
--
ALTER TABLE `MotivosAusencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `MotivosCheckoutIncidente`
--
ALTER TABLE `MotivosCheckoutIncidente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `MotivosIncidencias`
--
ALTER TABLE `MotivosIncidencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Municipios`
--
ALTER TABLE `Municipios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Operadores`
--
ALTER TABLE `Operadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Paises`
--
ALTER TABLE `Paises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `PaseLista`
--
ALTER TABLE `PaseLista`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Propietarios`
--
ALTER TABLE `Propietarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Radios`
--
ALTER TABLE `Radios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `RecorridoUnidad`
--
ALTER TABLE `RecorridoUnidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Servicios`
--
ALTER TABLE `Servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Sitios`
--
ALTER TABLE `Sitios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `SuspencionesOperadores`
--
ALTER TABLE `SuspencionesOperadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `SuspencionesUnidades`
--
ALTER TABLE `SuspencionesUnidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TelefonosClientes`
--
ALTER TABLE `TelefonosClientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TelefonosOperadores`
--
ALTER TABLE `TelefonosOperadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TelefonosPropietarios`
--
ALTER TABLE `TelefonosPropietarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TelefonosUsuarios`
--
ALTER TABLE `TelefonosUsuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TiposDocumento`
--
ALTER TABLE `TiposDocumento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TiposTelefonos`
--
ALTER TABLE `TiposTelefonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TiposUsuarios`
--
ALTER TABLE `TiposUsuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Turnos`
--
ALTER TABLE `Turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Ubicaciones`
--
ALTER TABLE `Ubicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Unidades`
--
ALTER TABLE `Unidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
