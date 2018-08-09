-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-08-2018 a las 16:04:23
-- Versión del servidor: 10.1.30-MariaDB
-- Versión de PHP: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `movilidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bienes`
--

CREATE TABLE `bienes` (
  `idbienes` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipo` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `imagen` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `valor` decimal(11,2) DEFAULT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `categorias_bienes_idcategorias_bienes` int(11) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `bienes`
--

INSERT INTO `bienes` (`idbienes`, `codigo`, `nombre`, `descripcion`, `tipo`, `imagen`, `valor`, `condicion`, `categorias_bienes_idcategorias_bienes`, `stock`) VALUES
(9, 1, 'Semaforo auto', 'Nuevos semaforos 2018', 'metal', '1533739877.jpg', '35.00', 1, 6, 46);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `bienes_por_categorias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `bienes_por_categorias` (
`codigo` int(11)
,`nombre` varchar(45)
,`descripcion` varchar(45)
,`tipo` varchar(45)
,`imagen` varchar(45)
,`valor` decimal(11,2)
,`condicion` tinyint(4)
,`stock` int(11)
,`cateroria` varchar(45)
,`idbienes` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_bienes`
--

CREATE TABLE `categorias_bienes` (
  `idcategorias_bienes` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias_bienes`
--

INSERT INTO `categorias_bienes` (`idcategorias_bienes`, `nombre`, `estado`) VALUES
(6, 'SEMAFORO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_especies`
--

CREATE TABLE `categorias_especies` (
  `idcategorias_especies` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_egreso_bienes`
--

CREATE TABLE `detalle_egreso_bienes` (
  `iddetalle_egreso_bienes` int(11) NOT NULL,
  `cantidad` varchar(45) DEFAULT NULL,
  `precio` decimal(11,2) DEFAULT NULL,
  `egreso_bienes_idegreso_bienes` int(11) DEFAULT NULL,
  `bienes_idbienes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_egreso_bienes`
--

INSERT INTO `detalle_egreso_bienes` (`iddetalle_egreso_bienes`, `cantidad`, `precio`, `egreso_bienes_idegreso_bienes`, `bienes_idbienes`) VALUES
(9, '1', '35.00', 4, 9),
(10, '1', '35.00', 5, 9),
(11, '1', '35.00', 6, 9);

--
-- Disparadores `detalle_egreso_bienes`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockEgresoBienes` AFTER INSERT ON `detalle_egreso_bienes` FOR EACH ROW BEGIN
 UPDATE bienes SET bienes.stock = bienes.stock - NEW.cantidad 
 WHERE bienes.idbienes = NEW.bienes_idbienes;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_egreso_especies`
--

CREATE TABLE `detalle_egreso_especies` (
  `iddetalle_egreso_especies` int(11) NOT NULL,
  `cantidad` varchar(45) DEFAULT NULL,
  `desde` varchar(45) DEFAULT NULL,
  `hasta` varchar(45) DEFAULT NULL,
  `egreso_especies_idegreso_especies` int(11) NOT NULL,
  `especies_idespecies` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `detalle_egreso_especies`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockEgresoEspecies` AFTER INSERT ON `detalle_egreso_especies` FOR EACH ROW BEGIN
 UPDATE especies SET especies.stock = especies.stock - NEW.cantidad,     especies.desde=NEW.hasta 
 WHERE especies.idespecies = NEW.especies_idespecies;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ingreso_bienes`
--

CREATE TABLE `detalle_ingreso_bienes` (
  `iddetalle_ingreso_bienes` int(11) NOT NULL,
  `consto_unitario` decimal(11,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `bienes_idbienes` int(11) NOT NULL,
  `ingreso_bienes_idingreso_bienes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_ingreso_bienes`
--

INSERT INTO `detalle_ingreso_bienes` (`iddetalle_ingreso_bienes`, `consto_unitario`, `cantidad`, `bienes_idbienes`, `ingreso_bienes_idingreso_bienes`) VALUES
(17, '35.00', 49, 9, 10);

--
-- Disparadores `detalle_ingreso_bienes`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockIngresoBienes` AFTER INSERT ON `detalle_ingreso_bienes` FOR EACH ROW BEGIN
 UPDATE bienes SET bienes.stock = bienes.stock + NEW.cantidad 
 WHERE bienes.idbienes = NEW.bienes_idbienes;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ingreso_especies`
--

CREATE TABLE `detalle_ingreso_especies` (
  `iddetalle_ingreso_especies` int(11) NOT NULL,
  `ingreso_especies_idingreso_especies` int(11) NOT NULL,
  `especies_idespecies` int(11) NOT NULL,
  `cantidad` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `desde` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `hasta` varchar(45) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Disparadores `detalle_ingreso_especies`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockIngresoEspecies` AFTER INSERT ON `detalle_ingreso_especies` FOR EACH ROW BEGIN
 UPDATE especies SET especies.stock = especies.stock + NEW.cantidad, especies.hasta=NEW.hasta 
 WHERE especies.idespecies = NEW.especies_idespecies;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso_bienes`
--

CREATE TABLE `egreso_bienes` (
  `idegreso_bienes` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `lugar` varchar(200) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `personas_idcajeros` int(11) NOT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `numero_egreso` varchar(45) NOT NULL,
  `calle` varchar(250) NOT NULL,
  `interseccion` varchar(250) NOT NULL,
  `proyectos_idproyectos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `egreso_bienes`
--

INSERT INTO `egreso_bienes` (`idegreso_bienes`, `fecha`, `total`, `lugar`, `descripcion`, `personas_idcajeros`, `usuario_idusuario`, `estado`, `numero_egreso`, `calle`, `interseccion`, `proyectos_idproyectos`) VALUES
(4, '2018-08-08', '35.00', 'MOVILIDAD', 'NNNNNNNNNNNNNNN', 6, 1, 'Aceptado', '6576', '2 DE MAYO', 'GUAYAQUIL', 1),
(5, '2018-08-08', '35.00', 'MOVILIDAD', 'JKLHGJKV', 6, 1, 'Aceptado', '456879', 'TYUIOUUY', 'YUUIOGUI', 2),
(6, '2018-08-08', '35.00', 'guhguh', 'gyuhbguhbg', 6, 1, 'Aceptado', '56789', 'yhgbyuhgbu', 'yhgbyhugbuh', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso_especies`
--

CREATE TABLE `egreso_especies` (
  `idegreso_especies` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `numero_documento` varchar(45) DEFAULT NULL,
  `ubicacion` varchar(45) DEFAULT NULL,
  `detalle` varchar(200) DEFAULT NULL,
  `total` decimal(11,0) DEFAULT NULL,
  `condicion` varchar(45) DEFAULT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  `personas_idcajeros` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies`
--

CREATE TABLE `especies` (
  `idespecies` int(11) NOT NULL,
  `categorias_especies_idcategorias_especies` int(11) NOT NULL,
  `codigo` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `imagen` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1',
  `stock` int(11) DEFAULT NULL,
  `hasta` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `desde` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `especies_por_categoria`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `especies_por_categoria` (
`idespecies` int(11)
,`codigo` varchar(45)
,`nombre` varchar(45)
,`descripcion` varchar(45)
,`imagen` varchar(45)
,`condicion` tinyint(4)
,`stock` int(11)
,`hasta` varchar(45)
,`desde` varchar(45)
,`categoria` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_bienes`
--

CREATE TABLE `ingreso_bienes` (
  `idingreso_bienes` int(11) NOT NULL,
  `ubicacion` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `detalle` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numero_ingreso` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `usuario_idusuario` int(11) DEFAULT NULL,
  `n_documento` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ingreso_bienes`
--

INSERT INTO `ingreso_bienes` (`idingreso_bienes`, `ubicacion`, `detalle`, `numero_ingreso`, `fecha`, `estado`, `total`, `usuario_idusuario`, `n_documento`) VALUES
(10, 'MOVILDAD', 'BODEGA', '657', '2018-08-08', 'Aceptado', '1715.00', 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_especies`
--

CREATE TABLE `ingreso_especies` (
  `idingreso_especies` int(11) NOT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero_docuemnto` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ubicacion` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `detalle` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `total` decimal(11,2) NOT NULL,
  `condicion` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `idpermiso` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`idpermiso`, `nombre`) VALUES
(1, 'Escritorio'),
(2, 'Categorias'),
(3, 'Catalogos'),
(4, 'Especies'),
(5, 'Bienes'),
(6, 'Acceso'),
(7, 'Consultas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `idcajeros` int(11) NOT NULL,
  `cedula` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `funcion` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`idcajeros`, `cedula`, `nombre`, `funcion`, `condicion`) VALUES
(6, '0987678998767', 'DANNY GARCIA', 'CAJERO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `idproyectos` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`idproyectos`, `nombre`, `descripcion`, `fecha`, `condicion`) VALUES
(1, 'Ninguno', 'no pertenece a un proyecto', '2018-08-08', 1),
(2, 'VIVA TUNGURAHUA', 'PROYECTO DEEE AJAI IDJIJD JHDUHFUIOEJFIOEJH JEHFUEHFK', '2018-08-08', 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_detalle_egreso_bienes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_detalle_egreso_bienes` (
`iddetalle_egreso_bienes` int(11)
,`precio` decimal(11,2)
,`cantidad` varchar(45)
,`egreso_bienes_idegreso_bienes` int(11)
,`nombre` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_detalle_egreso_especies`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_detalle_egreso_especies` (
`iddetalle_egreso_especies` int(11)
,`cantidad` varchar(45)
,`desde` varchar(45)
,`hasta` varchar(45)
,`egreso_especies_idegreso_especies` int(11)
,`nombre` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_detalle_ingreso_bienes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_detalle_ingreso_bienes` (
`iddetalle_ingreso_bienes` int(11)
,`consto_unitario` decimal(11,2)
,`cantidad` int(11)
,`nombre` varchar(45)
,`ingreso_bienes_idingreso_bienes` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_detalle_ingreso_especies`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_detalle_ingreso_especies` (
`iddetalle_ingreso_especies` int(11)
,`ingreso_especies_idingreso_especies` int(11)
,`cantidad` varchar(45)
,`desde` varchar(45)
,`hasta` varchar(45)
,`nombre` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_egreso_bienes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_egreso_bienes` (
`idegreso_bienes` int(11)
,`fecha` date
,`total` decimal(11,2)
,`lugar` varchar(200)
,`descripcion` varchar(300)
,`estado` varchar(45)
,`numero_egreso` varchar(45)
,`calle` varchar(250)
,`interseccion` varchar(250)
,`usuario` varchar(100)
,`nombre` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_egreso_especies`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_egreso_especies` (
`idegreso_especies` int(11)
,`fecha` date
,`numero_documento` varchar(45)
,`ubicacion` varchar(45)
,`detalle` varchar(200)
,`total` decimal(11,0)
,`condicion` varchar(45)
,`persona` varchar(45)
,`nombre` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_ingreso_bienes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_ingreso_bienes` (
`idingreso_bienes` int(11)
,`ubicacion` varchar(45)
,`detalle` varchar(45)
,`numero_ingreso` varchar(45)
,`fecha` date
,`estado` varchar(25)
,`total` decimal(11,2)
,`n_documento` varchar(45)
,`nombre` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `r_ingreso_especies`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `r_ingreso_especies` (
`idingreso_especies` int(11)
,`fecha` date
,`numero_docuemnto` varchar(45)
,`ubicacion` varchar(45)
,`detalle` varchar(45)
,`total` decimal(11,2)
,`condicion` varchar(45)
,`nombre` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento_egreso_bienes`
--

CREATE TABLE `seguimiento_egreso_bienes` (
  `idseguimiento_egrese_bienes` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `egreso_bienes_idegreso_bienes` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_documento` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `num_documento` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cargo` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `login` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `imagen` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `condicion` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `cargo`, `login`, `clave`, `imagen`, `condicion`) VALUES
(1, 'DANNY GARCIA', 'CEDULA', '0504353210', 'LATACUNGA', '0996269763', 'dannyggg23@gmail.com', 'admin', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '1487132068.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_permiso`
--

CREATE TABLE `usuario_permiso` (
  `idusuario_permiso` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario_permiso`
--

INSERT INTO `usuario_permiso` (`idusuario_permiso`, `idusuario`, `idpermiso`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view1`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `view1` (
`idegreso_bienes` int(11)
,`fecha` date
,`total` decimal(11,2)
,`lugar` varchar(200)
,`descripcion` varchar(300)
,`estado` varchar(45)
,`numero_egreso` varchar(45)
,`calle` varchar(250)
,`interseccion` varchar(250)
,`proyecto` varchar(45)
,`idcajeros` int(11)
,`usuario` varchar(100)
,`cajero` varchar(45)
,`cedula_cajero` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `bienes_por_categorias`
--
DROP TABLE IF EXISTS `bienes_por_categorias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bienes_por_categorias`  AS  select `bienes`.`codigo` AS `codigo`,`bienes`.`nombre` AS `nombre`,`bienes`.`descripcion` AS `descripcion`,`bienes`.`tipo` AS `tipo`,`bienes`.`imagen` AS `imagen`,`bienes`.`valor` AS `valor`,`bienes`.`condicion` AS `condicion`,`bienes`.`stock` AS `stock`,`categorias_bienes`.`nombre` AS `cateroria`,`bienes`.`idbienes` AS `idbienes` from (`bienes` join `categorias_bienes` on((`bienes`.`categorias_bienes_idcategorias_bienes` = `categorias_bienes`.`idcategorias_bienes`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `especies_por_categoria`
--
DROP TABLE IF EXISTS `especies_por_categoria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `especies_por_categoria`  AS  select `especies1`.`idespecies` AS `idespecies`,`especies1`.`codigo` AS `codigo`,`especies1`.`nombre` AS `nombre`,`especies1`.`descripcion` AS `descripcion`,`especies1`.`imagen` AS `imagen`,`especies1`.`condicion` AS `condicion`,`especies1`.`stock` AS `stock`,`especies1`.`hasta` AS `hasta`,`especies1`.`desde` AS `desde`,`categorias_especies`.`nombre` AS `categoria` from (`categorias_especies` join `especies` `especies1` on((`categorias_especies`.`idcategorias_especies` = `especies1`.`categorias_especies_idcategorias_especies`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_detalle_egreso_bienes`
--
DROP TABLE IF EXISTS `r_detalle_egreso_bienes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_detalle_egreso_bienes`  AS  select `detalle_egreso_bienes`.`iddetalle_egreso_bienes` AS `iddetalle_egreso_bienes`,`detalle_egreso_bienes`.`precio` AS `precio`,`detalle_egreso_bienes`.`cantidad` AS `cantidad`,`detalle_egreso_bienes`.`egreso_bienes_idegreso_bienes` AS `egreso_bienes_idegreso_bienes`,`bienes`.`nombre` AS `nombre` from (`detalle_egreso_bienes` join `bienes` on((`detalle_egreso_bienes`.`bienes_idbienes` = `bienes`.`idbienes`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_detalle_egreso_especies`
--
DROP TABLE IF EXISTS `r_detalle_egreso_especies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_detalle_egreso_especies`  AS  select `detalle_egreso_especies`.`iddetalle_egreso_especies` AS `iddetalle_egreso_especies`,`detalle_egreso_especies`.`cantidad` AS `cantidad`,`detalle_egreso_especies`.`desde` AS `desde`,`detalle_egreso_especies`.`hasta` AS `hasta`,`detalle_egreso_especies`.`egreso_especies_idegreso_especies` AS `egreso_especies_idegreso_especies`,`especies`.`nombre` AS `nombre` from (`detalle_egreso_especies` join `especies` on((`detalle_egreso_especies`.`especies_idespecies` = `especies`.`idespecies`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_detalle_ingreso_bienes`
--
DROP TABLE IF EXISTS `r_detalle_ingreso_bienes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_detalle_ingreso_bienes`  AS  select `detalle_ingreso_bienes`.`iddetalle_ingreso_bienes` AS `iddetalle_ingreso_bienes`,`detalle_ingreso_bienes`.`consto_unitario` AS `consto_unitario`,`detalle_ingreso_bienes`.`cantidad` AS `cantidad`,`bienes`.`nombre` AS `nombre`,`detalle_ingreso_bienes`.`ingreso_bienes_idingreso_bienes` AS `ingreso_bienes_idingreso_bienes` from (`detalle_ingreso_bienes` join `bienes` on((`detalle_ingreso_bienes`.`bienes_idbienes` = `bienes`.`idbienes`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_detalle_ingreso_especies`
--
DROP TABLE IF EXISTS `r_detalle_ingreso_especies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_detalle_ingreso_especies`  AS  select `detalle_ingreso_especies`.`iddetalle_ingreso_especies` AS `iddetalle_ingreso_especies`,`detalle_ingreso_especies`.`ingreso_especies_idingreso_especies` AS `ingreso_especies_idingreso_especies`,`detalle_ingreso_especies`.`cantidad` AS `cantidad`,`detalle_ingreso_especies`.`desde` AS `desde`,`detalle_ingreso_especies`.`hasta` AS `hasta`,`especies`.`nombre` AS `nombre` from (`detalle_ingreso_especies` join `especies` on((`detalle_ingreso_especies`.`especies_idespecies` = `especies`.`idespecies`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_egreso_bienes`
--
DROP TABLE IF EXISTS `r_egreso_bienes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_egreso_bienes`  AS  select `egreso_bienes`.`idegreso_bienes` AS `idegreso_bienes`,`egreso_bienes`.`fecha` AS `fecha`,`egreso_bienes`.`total` AS `total`,`egreso_bienes`.`lugar` AS `lugar`,`egreso_bienes`.`descripcion` AS `descripcion`,`egreso_bienes`.`estado` AS `estado`,`egreso_bienes`.`numero_egreso` AS `numero_egreso`,`egreso_bienes`.`calle` AS `calle`,`egreso_bienes`.`interseccion` AS `interseccion`,`usuario`.`nombre` AS `usuario`,`personas`.`nombre` AS `nombre` from ((`egreso_bienes` join `usuario` on((`egreso_bienes`.`usuario_idusuario` = `usuario`.`idusuario`))) join `personas` on((`egreso_bienes`.`personas_idcajeros` = `personas`.`idcajeros`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_egreso_especies`
--
DROP TABLE IF EXISTS `r_egreso_especies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_egreso_especies`  AS  select `egreso_especies`.`idegreso_especies` AS `idegreso_especies`,`egreso_especies`.`fecha` AS `fecha`,`egreso_especies`.`numero_documento` AS `numero_documento`,`egreso_especies`.`ubicacion` AS `ubicacion`,`egreso_especies`.`detalle` AS `detalle`,`egreso_especies`.`total` AS `total`,`egreso_especies`.`condicion` AS `condicion`,`personas`.`nombre` AS `persona`,`usuario`.`nombre` AS `nombre` from ((`egreso_especies` join `personas` on((`egreso_especies`.`personas_idcajeros` = `personas`.`idcajeros`))) join `usuario` on((`egreso_especies`.`usuario_idusuario` = `usuario`.`idusuario`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_ingreso_bienes`
--
DROP TABLE IF EXISTS `r_ingreso_bienes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_ingreso_bienes`  AS  select `ingreso_bienes`.`idingreso_bienes` AS `idingreso_bienes`,`ingreso_bienes`.`ubicacion` AS `ubicacion`,`ingreso_bienes`.`detalle` AS `detalle`,`ingreso_bienes`.`numero_ingreso` AS `numero_ingreso`,`ingreso_bienes`.`fecha` AS `fecha`,`ingreso_bienes`.`estado` AS `estado`,`ingreso_bienes`.`total` AS `total`,`ingreso_bienes`.`n_documento` AS `n_documento`,`usuario`.`nombre` AS `nombre` from (`ingreso_bienes` join `usuario` on((`ingreso_bienes`.`usuario_idusuario` = `usuario`.`idusuario`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `r_ingreso_especies`
--
DROP TABLE IF EXISTS `r_ingreso_especies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `r_ingreso_especies`  AS  select `ingreso_especies`.`idingreso_especies` AS `idingreso_especies`,`ingreso_especies`.`fecha` AS `fecha`,`ingreso_especies`.`numero_docuemnto` AS `numero_docuemnto`,`ingreso_especies`.`ubicacion` AS `ubicacion`,`ingreso_especies`.`detalle` AS `detalle`,`ingreso_especies`.`total` AS `total`,`ingreso_especies`.`condicion` AS `condicion`,`usuario`.`nombre` AS `nombre` from (`ingreso_especies` join `usuario` on((`ingreso_especies`.`usuario_idusuario` = `usuario`.`idusuario`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view1`
--
DROP TABLE IF EXISTS `view1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view1`  AS  select `egreso_bienes`.`idegreso_bienes` AS `idegreso_bienes`,`egreso_bienes`.`fecha` AS `fecha`,`egreso_bienes`.`total` AS `total`,`egreso_bienes`.`lugar` AS `lugar`,`egreso_bienes`.`descripcion` AS `descripcion`,`egreso_bienes`.`estado` AS `estado`,`egreso_bienes`.`numero_egreso` AS `numero_egreso`,`egreso_bienes`.`calle` AS `calle`,`egreso_bienes`.`interseccion` AS `interseccion`,`proyectos`.`nombre` AS `proyecto`,`personas`.`idcajeros` AS `idcajeros`,`usuario`.`nombre` AS `usuario`,`personas`.`nombre` AS `cajero`,`personas`.`cedula` AS `cedula_cajero` from (((`proyectos` join `egreso_bienes` on((`proyectos`.`idproyectos` = `egreso_bienes`.`proyectos_idproyectos`))) join `personas` on((`personas`.`idcajeros` = `egreso_bienes`.`personas_idcajeros`))) join `usuario` on((`usuario`.`idusuario` = `egreso_bienes`.`usuario_idusuario`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD PRIMARY KEY (`idbienes`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_bienes_categorias_bienes1_idx` (`categorias_bienes_idcategorias_bienes`);

--
-- Indices de la tabla `categorias_bienes`
--
ALTER TABLE `categorias_bienes`
  ADD PRIMARY KEY (`idcategorias_bienes`);

--
-- Indices de la tabla `categorias_especies`
--
ALTER TABLE `categorias_especies`
  ADD PRIMARY KEY (`idcategorias_especies`);

--
-- Indices de la tabla `detalle_egreso_bienes`
--
ALTER TABLE `detalle_egreso_bienes`
  ADD PRIMARY KEY (`iddetalle_egreso_bienes`),
  ADD KEY `fk_detalle_egreso_bienes_egreso_bienes1_idx` (`egreso_bienes_idegreso_bienes`),
  ADD KEY `fk_detalle_egreso_bienes_bienes1_idx` (`bienes_idbienes`);

--
-- Indices de la tabla `detalle_egreso_especies`
--
ALTER TABLE `detalle_egreso_especies`
  ADD PRIMARY KEY (`iddetalle_egreso_especies`),
  ADD KEY `fk_detalle_egreso_especies_egreso_especies1_idx` (`egreso_especies_idegreso_especies`),
  ADD KEY `fk_detalle_egreso_especies_especies1_idx` (`especies_idespecies`);

--
-- Indices de la tabla `detalle_ingreso_bienes`
--
ALTER TABLE `detalle_ingreso_bienes`
  ADD PRIMARY KEY (`iddetalle_ingreso_bienes`),
  ADD KEY `fk_detalle_ingreso_bienes_bienes1_idx` (`bienes_idbienes`),
  ADD KEY `fk_detalle_ingreso_bienes_ingreso_bienes1_idx` (`ingreso_bienes_idingreso_bienes`);

--
-- Indices de la tabla `detalle_ingreso_especies`
--
ALTER TABLE `detalle_ingreso_especies`
  ADD PRIMARY KEY (`iddetalle_ingreso_especies`),
  ADD KEY `fk_detalle_ingreso_especies_especies1_idx` (`especies_idespecies`),
  ADD KEY `fk_detalle_ingreso_especies_ingreso_especies1_idx` (`ingreso_especies_idingreso_especies`);

--
-- Indices de la tabla `egreso_bienes`
--
ALTER TABLE `egreso_bienes`
  ADD PRIMARY KEY (`idegreso_bienes`),
  ADD KEY `fk_egreso_bienes_personas1_idx` (`personas_idcajeros`),
  ADD KEY `fk_egreso_bienes_usuario1_idx` (`usuario_idusuario`),
  ADD KEY `fk_egreso_bienes_proyectos1_idx` (`proyectos_idproyectos`);

--
-- Indices de la tabla `egreso_especies`
--
ALTER TABLE `egreso_especies`
  ADD PRIMARY KEY (`idegreso_especies`),
  ADD KEY `fk_egreso_especies_usuario1_idx` (`usuario_idusuario`),
  ADD KEY `fk_egreso_especies_personas1_idx` (`personas_idcajeros`);

--
-- Indices de la tabla `especies`
--
ALTER TABLE `especies`
  ADD PRIMARY KEY (`idespecies`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_especies_categorias_especies_idx` (`categorias_especies_idcategorias_especies`);

--
-- Indices de la tabla `ingreso_bienes`
--
ALTER TABLE `ingreso_bienes`
  ADD PRIMARY KEY (`idingreso_bienes`),
  ADD KEY `fk_ingreso_bienes_usuario1_idx` (`usuario_idusuario`);

--
-- Indices de la tabla `ingreso_especies`
--
ALTER TABLE `ingreso_especies`
  ADD PRIMARY KEY (`idingreso_especies`),
  ADD KEY `fk_ingreso_especies_usuario1_idx` (`usuario_idusuario`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`idpermiso`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`idcajeros`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`idproyectos`);

--
-- Indices de la tabla `seguimiento_egreso_bienes`
--
ALTER TABLE `seguimiento_egreso_bienes`
  ADD PRIMARY KEY (`idseguimiento_egrese_bienes`),
  ADD KEY `fk_seguimiento_egrese_bienes_egreso_bienes1_idx` (`egreso_bienes_idegreso_bienes`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- Indices de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD PRIMARY KEY (`idusuario_permiso`),
  ADD KEY `fk_usuario_permiso_usuario1_idx` (`idusuario`),
  ADD KEY `fk_usuario_permiso_permiso1_idx` (`idpermiso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bienes`
--
ALTER TABLE `bienes`
  MODIFY `idbienes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `categorias_bienes`
--
ALTER TABLE `categorias_bienes`
  MODIFY `idcategorias_bienes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categorias_especies`
--
ALTER TABLE `categorias_especies`
  MODIFY `idcategorias_especies` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_egreso_bienes`
--
ALTER TABLE `detalle_egreso_bienes`
  MODIFY `iddetalle_egreso_bienes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `detalle_egreso_especies`
--
ALTER TABLE `detalle_egreso_especies`
  MODIFY `iddetalle_egreso_especies` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_ingreso_bienes`
--
ALTER TABLE `detalle_ingreso_bienes`
  MODIFY `iddetalle_ingreso_bienes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `detalle_ingreso_especies`
--
ALTER TABLE `detalle_ingreso_especies`
  MODIFY `iddetalle_ingreso_especies` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `egreso_bienes`
--
ALTER TABLE `egreso_bienes`
  MODIFY `idegreso_bienes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `egreso_especies`
--
ALTER TABLE `egreso_especies`
  MODIFY `idegreso_especies` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `idespecies` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingreso_bienes`
--
ALTER TABLE `ingreso_bienes`
  MODIFY `idingreso_bienes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ingreso_especies`
--
ALTER TABLE `ingreso_especies`
  MODIFY `idingreso_especies` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `idpermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `idcajeros` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `idproyectos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `seguimiento_egreso_bienes`
--
ALTER TABLE `seguimiento_egreso_bienes`
  MODIFY `idseguimiento_egrese_bienes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  MODIFY `idusuario_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD CONSTRAINT `fk_bienes_categorias_bienes1` FOREIGN KEY (`categorias_bienes_idcategorias_bienes`) REFERENCES `categorias_bienes` (`idcategorias_bienes`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_egreso_bienes`
--
ALTER TABLE `detalle_egreso_bienes`
  ADD CONSTRAINT `fk_detalle_egreso_bienes_bienes1` FOREIGN KEY (`bienes_idbienes`) REFERENCES `bienes` (`idbienes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_egreso_bienes_egreso_bienes1` FOREIGN KEY (`egreso_bienes_idegreso_bienes`) REFERENCES `egreso_bienes` (`idegreso_bienes`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_egreso_especies`
--
ALTER TABLE `detalle_egreso_especies`
  ADD CONSTRAINT `fk_detalle_egreso_especies_egreso_especies1` FOREIGN KEY (`egreso_especies_idegreso_especies`) REFERENCES `egreso_especies` (`idegreso_especies`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_egreso_especies_especies1` FOREIGN KEY (`especies_idespecies`) REFERENCES `especies` (`idespecies`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_ingreso_bienes`
--
ALTER TABLE `detalle_ingreso_bienes`
  ADD CONSTRAINT `fk_detalle_ingreso_bienes_bienes1` FOREIGN KEY (`bienes_idbienes`) REFERENCES `bienes` (`idbienes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_ingreso_bienes_ingreso_bienes1` FOREIGN KEY (`ingreso_bienes_idingreso_bienes`) REFERENCES `ingreso_bienes` (`idingreso_bienes`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_ingreso_especies`
--
ALTER TABLE `detalle_ingreso_especies`
  ADD CONSTRAINT `fk_detalle_ingreso_especies_especies1` FOREIGN KEY (`especies_idespecies`) REFERENCES `especies` (`idespecies`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_ingreso_especies_ingreso_especies1` FOREIGN KEY (`ingreso_especies_idingreso_especies`) REFERENCES `ingreso_especies` (`idingreso_especies`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `egreso_bienes`
--
ALTER TABLE `egreso_bienes`
  ADD CONSTRAINT `fk_egreso_bienes_personas1` FOREIGN KEY (`personas_idcajeros`) REFERENCES `personas` (`idcajeros`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_egreso_bienes_proyectos1` FOREIGN KEY (`proyectos_idproyectos`) REFERENCES `proyectos` (`idproyectos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_egreso_bienes_usuario1` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `egreso_especies`
--
ALTER TABLE `egreso_especies`
  ADD CONSTRAINT `fk_egreso_especies_personas1` FOREIGN KEY (`personas_idcajeros`) REFERENCES `personas` (`idcajeros`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_egreso_especies_usuario1` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `especies`
--
ALTER TABLE `especies`
  ADD CONSTRAINT `fk_especies_categorias_especies` FOREIGN KEY (`categorias_especies_idcategorias_especies`) REFERENCES `categorias_especies` (`idcategorias_especies`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ingreso_bienes`
--
ALTER TABLE `ingreso_bienes`
  ADD CONSTRAINT `fk_ingreso_bienes_usuario1` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ingreso_especies`
--
ALTER TABLE `ingreso_especies`
  ADD CONSTRAINT `fk_ingreso_especies_usuario1` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `seguimiento_egreso_bienes`
--
ALTER TABLE `seguimiento_egreso_bienes`
  ADD CONSTRAINT `fk_seguimiento_egrese_bienes_egreso_bienes1` FOREIGN KEY (`egreso_bienes_idegreso_bienes`) REFERENCES `egreso_bienes` (`idegreso_bienes`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD CONSTRAINT `fk_usuario_permiso_permiso1` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_permiso_usuario1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
