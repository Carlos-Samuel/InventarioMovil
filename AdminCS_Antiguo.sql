-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 27-09-2023 a las 00:24:43
-- Versión del servidor: 5.7.42
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `AdminCS`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Bitacora`
--

CREATE TABLE `Bitacora` (
  `idBitacora` int(11) NOT NULL,
  `FechaHora` datetime NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `Accion` varchar(256) NOT NULL,
  `idFactura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Embalajes`
--

CREATE TABLE `Embalajes` (
  `idEmbalajes` int(11) NOT NULL,
  `Descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Embalajes`
--

INSERT INTO `Embalajes` (`idEmbalajes`, `Descripcion`) VALUES
(1, 'Bultos'),
(2, 'Lios'),
(3, 'Cajas'),
(4, 'Discos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Estados`
--

CREATE TABLE `Estados` (
  `idEstados` int(11) NOT NULL,
  `Descripcion` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Estados`
--

INSERT INTO `Estados` (`idEstados`, `Descripcion`) VALUES
(0, 'Devuelto'),
(1, 'Para Alistamiento'),
(2, 'Alistamiento Incompleto'),
(3, 'Para Verificacion '),
(4, 'Verificacion Incompleto'),
(5, 'Para Entrega'),
(6, 'Entrega Incompleto'),
(7, 'Entregado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Facturas`
--

CREATE TABLE `Facturas` (
  `vtaid` int(11) NOT NULL COMMENT 'Id Venta',
  `VtaNum` int(11) DEFAULT NULL COMMENT 'Número  factura',
  `PrfId` int(11) DEFAULT NULL COMMENT 'Id Prefijo',
  `vtafec` date DEFAULT NULL COMMENT 'Fecha factura',
  `vtahor` time DEFAULT NULL COMMENT 'Hora factura',
  `TerId` int(11) DEFAULT NULL COMMENT 'Id Cliente',
  `TerNom` varchar(100) DEFAULT NULL COMMENT 'Nombre cliente',
  `TerDir` varchar(200) DEFAULT NULL COMMENT 'Dirección cliente',
  `TerTel` varchar(50) DEFAULT NULL COMMENT 'Teléfono Cliente',
  `TerRaz` varchar(50) DEFAULT NULL COMMENT 'Razon Cliente',
  `VenId` int(11) DEFAULT NULL COMMENT 'Id Vendedor',
  `VenNom` varchar(100) DEFAULT NULL COMMENT 'Nombre Vendedor',
  `CiuId` int(11) DEFAULT NULL COMMENT 'Id Ciudad',
  `CiuNom` varchar(100) DEFAULT NULL COMMENT 'Nombre Ciudad',
  `facObservaciones` varchar(200) DEFAULT NULL COMMENT 'Observaciones',
  `facEstado` int(11) DEFAULT NULL COMMENT 'Estado en el manejo del inventario',
  `idAlistador` int(11) DEFAULT NULL COMMENT 'Id del usuario alistador',
  `idVerificador` int(11) DEFAULT NULL COMMENT 'Id del usuario verificador',
  `idEntregador` int(11) DEFAULT NULL COMMENT 'Id del usuario entregador',
  `MomentoCarga` datetime DEFAULT NULL COMMENT 'Fecha y hora de carga facturas',
  `InicioAlistamiento` datetime DEFAULT NULL COMMENT 'Fecha y hora de inicio de alistamiento',
  `FinAlistamiento` datetime DEFAULT NULL COMMENT 'Fecha y hora de fin de alistamiento',
  `InicioVerificacion` datetime DEFAULT NULL COMMENT 'Fecha y hora de inicio de verificacion',
  `FinVerificacion` datetime DEFAULT NULL COMMENT 'Fecha y hora de fin de verificacion',
  `InicioEntrega` datetime DEFAULT NULL COMMENT 'Fecha y hora de inicio de entrega',
  `FinEntrega` datetime DEFAULT NULL COMMENT 'Fecha y hora de fin de entrega',
  `Forzado` tinyint(1) DEFAULT '0' COMMENT 'Si se forzo el alistamiento',
  `Forzador` varchar(45) DEFAULT '0' COMMENT 'Cedula del usuario que permitio el forzado',
  `ObservacionesFor` varchar(100) DEFAULT '0' COMMENT 'Observaciones de forzado',
  `Justificacion` varchar(100) DEFAULT NULL COMMENT 'Justificacion de porque se dejo pendiente el alistamiento',
  `Embalaje` varchar(100) DEFAULT '0' COMMENT 'Embalajes',
  `ObservacionesVer` varchar(100) DEFAULT '0' COMMENT 'Observaciones verificacion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Permisos`
--

CREATE TABLE `Permisos` (
  `idPermisos` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Permisos`
--

INSERT INTO `Permisos` (`idPermisos`, `Nombre`, `Descripcion`) VALUES
(1, 'Alistamiento', 'Acceso a alistamiento'),
(2, 'Verificacion', 'Acceso a verificacion'),
(3, 'Entrega', 'Acceso a entrega'),
(4, 'Reportes', 'Acceso a todos los reportes'),
(5, 'Admin', 'Todos los permisos'),
(6, 'Forzado', 'Forzado de facturas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Productos`
--

CREATE TABLE `Productos` (
  `VtaId` int(11) DEFAULT NULL COMMENT 'Id Venta',
  `VtaDetId` int(11) NOT NULL COMMENT 'Id Detalle ventas',
  `ProId` int(11) DEFAULT NULL COMMENT 'Id Producto',
  `ProNom` varchar(200) DEFAULT NULL COMMENT 'Nombre producto',
  `ProUbica` varchar(50) DEFAULT NULL COMMENT 'Ubicacion producto',
  `ProPresentacion` varchar(50) DEFAULT NULL COMMENT 'Presentacion producto',
  `ProCodBar` varchar(50) DEFAULT NULL COMMENT 'Codigo de barras del producto',
  `VtaCant` double DEFAULT NULL COMMENT 'Cantidad factura',
  `AlisCant` double DEFAULT '0' COMMENT 'Cantidad alistada',
  `VerCant` double DEFAULT '-1' COMMENT 'Cantidad verificada'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `idUsuarios` int(11) NOT NULL,
  `Cedula` varchar(45) NOT NULL,
  `Nombres` varchar(45) NOT NULL,
  `Apellidos` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) NOT NULL,
  `Password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`idUsuarios`, `Cedula`, `Nombres`, `Apellidos`, `Correo`, `Password`) VALUES
(1, '1006873236', 'Carlos', 'Medina', 'csamuelrox@gmail.com', '$2y$10$zNV2Myto9CVJuFLXpWmPZui81y1lwwfF.NMuR9zi9s27hDVE53.1W');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios_tienen_permisos`
--

CREATE TABLE `Usuarios_tienen_permisos` (
  `idUsuarios` int(11) NOT NULL,
  `idPermisos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Usuarios_tienen_permisos`
--

INSERT INTO `Usuarios_tienen_permisos` (`idUsuarios`, `idPermisos`) VALUES
(1, 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Bitacora`
--
ALTER TABLE `Bitacora`
  ADD PRIMARY KEY (`idBitacora`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idFactura` (`idFactura`);

--
-- Indices de la tabla `Embalajes`
--
ALTER TABLE `Embalajes`
  ADD PRIMARY KEY (`idEmbalajes`);

--
-- Indices de la tabla `Estados`
--
ALTER TABLE `Estados`
  ADD PRIMARY KEY (`idEstados`);

--
-- Indices de la tabla `Facturas`
--
ALTER TABLE `Facturas`
  ADD PRIMARY KEY (`vtaid`),
  ADD KEY `idAlistador` (`idAlistador`),
  ADD KEY `idVerificador` (`idVerificador`),
  ADD KEY `idEntregador` (`idEntregador`),
  ADD KEY `facEstado` (`facEstado`);

--
-- Indices de la tabla `Permisos`
--
ALTER TABLE `Permisos`
  ADD PRIMARY KEY (`idPermisos`);

--
-- Indices de la tabla `Productos`
--
ALTER TABLE `Productos`
  ADD PRIMARY KEY (`VtaDetId`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`idUsuarios`);

--
-- Indices de la tabla `Usuarios_tienen_permisos`
--
ALTER TABLE `Usuarios_tienen_permisos`
  ADD PRIMARY KEY (`idUsuarios`,`idPermisos`),
  ADD KEY `Permisos_Usuarios_tienen_permisos_idPermisos_idx` (`idPermisos`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Bitacora`
--
ALTER TABLE `Bitacora`
  MODIFY `idBitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `Embalajes`
--
ALTER TABLE `Embalajes`
  MODIFY `idEmbalajes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `Productos`
--
ALTER TABLE `Productos`
  MODIFY `VtaDetId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id Detalle ventas', AUTO_INCREMENT=1616462;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `idUsuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Bitacora`
--
ALTER TABLE `Bitacora`
  ADD CONSTRAINT `Bitacora_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`idUsuarios`),
  ADD CONSTRAINT `Bitacora_ibfk_2` FOREIGN KEY (`idFactura`) REFERENCES `Facturas` (`vtaid`);

--
-- Filtros para la tabla `Facturas`
--
ALTER TABLE `Facturas`
  ADD CONSTRAINT `Facturas_ibfk_1` FOREIGN KEY (`idAlistador`) REFERENCES `Usuarios` (`idUsuarios`),
  ADD CONSTRAINT `Facturas_ibfk_2` FOREIGN KEY (`idVerificador`) REFERENCES `Usuarios` (`idUsuarios`),
  ADD CONSTRAINT `Facturas_ibfk_3` FOREIGN KEY (`idEntregador`) REFERENCES `Usuarios` (`idUsuarios`),
  ADD CONSTRAINT `Facturas_ibfk_4` FOREIGN KEY (`facEstado`) REFERENCES `Estados` (`idEstados`);

--
-- Filtros para la tabla `Usuarios_tienen_permisos`
--
ALTER TABLE `Usuarios_tienen_permisos`
  ADD CONSTRAINT `Permisos_Usuarios_tienen_permisos_idPermisos` FOREIGN KEY (`idPermisos`) REFERENCES `Permisos` (`idPermisos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Usuarios_Usuarios_tienen_permisos_idUsuarios` FOREIGN KEY (`idUsuarios`) REFERENCES `Usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Usuarios_tienen_permisos_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `Usuarios` (`idUsuarios`),
  ADD CONSTRAINT `Usuarios_tienen_permisos_ibfk_2` FOREIGN KEY (`idPermisos`) REFERENCES `Permisos` (`idPermisos`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
