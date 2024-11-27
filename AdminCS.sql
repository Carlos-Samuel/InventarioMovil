-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 04-10-2023 a las 01:37:38
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
(3, 'Bultos'),
(4, 'Lios'),
(6, 'Cajas'),
(7, 'Discos');

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
  `TerRaz` varchar(100) DEFAULT NULL COMMENT 'Razon Cliente',
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

--
-- Volcado de datos para la tabla `Facturas`
--

INSERT INTO `Facturas` (`vtaid`, `VtaNum`, `PrfId`, `vtafec`, `vtahor`, `TerId`, `TerNom`, `TerDir`, `TerTel`, `TerRaz`, `VenId`, `VenNom`, `CiuId`, `CiuNom`, `facObservaciones`, `facEstado`, `idAlistador`, `idVerificador`, `idEntregador`, `MomentoCarga`, `InicioAlistamiento`, `FinAlistamiento`, `InicioVerificacion`, `FinVerificacion`, `InicioEntrega`, `FinEntrega`, `Forzado`, `Forzador`, `ObservacionesFor`, `Justificacion`, `Embalaje`, `ObservacionesVer`) VALUES
(1, 30681, 26, '2023-08-12', '10:41:00', 299, 'FLOREZ BERMUDEZ ALBA CAROLINA', 'calle 36 26 76 cadena', '3143815275', 'SOLUPLASTICOS CADENA', 4, 'KATERINE', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(2, 45294, 11, '2023-08-12', '10:56:02', 11719, 'VILLALBA CRUZ MARTHA CECILIA', 'crra 24 n 4 c - 11 barrio alborada', '3114834652', 'FERREELECTRICOS SANTA MONICA', 4, 'KATERINE', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(3, 45295, 11, '2023-08-12', '11:43:01', 6, 'MOSTRADOR VENTAS', 'Cl 36 26 51 San Isidro', '', 'MOSTRADOR', 2, 'JESSICA', 0, 'DATO NO DISPONIBLE', 'MIRIAM TELLES            PAGO X NEQUI', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(4, 194890, 10, '2023-08-12', '10:56:42', 6, 'MOSTRADOR VENTAS', 'Cl 36 26 51 San Isidro', '', 'MOSTRADOR', 2, 'JESSICA', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(5, 30682, 26, '2023-08-12', '11:02:48', 5028, 'DEPOSITO Y FERRETERIA CASANARE SAS', 'CLL 36 N 17-40 SAN ISIDRO', '3102791041', 'DEPOSITO Y FERRETERIA CASANARE SAS', 8, 'ALEJANDRA', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(6, 194891, 10, '2023-08-12', '11:02:59', 6, 'MOSTRADOR VENTAS', 'Cl 36 26 51 San Isidro', '', 'MOSTRADOR', 9, 'THALIA PATIÑO', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(7, 194892, 10, '2023-08-12', '11:08:26', 6, 'MOSTRADOR VENTAS', 'Cl 36 26 51 San Isidro', '', 'MOSTRADOR', 9, 'THALIA PATIÑO', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(8, 194893, 10, '2023-08-12', '11:31:41', 6, 'MOSTRADOR VENTAS', 'Cl 36 26 51 San Isidro', '', 'MOSTRADOR', 8, 'ALEJANDRA', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(9, 30683, 26, '2023-08-12', '11:32:16', 4875, 'SUPER TRANSPORTES DE COLOMBIA', 'CLL 9 13 66 BRR NICOLINO MATTAR CUMARIBO VICHADA', '313 2937557', 'SUPER TRANSPORTES DE COLOMBIA', 4, 'KATERINE', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0'),
(19, 194894, 10, '2023-08-12', '11:37:19', 6, 'MOSTRADOR VENTAS', 'Cl 36 26 51 San Isidro', '', 'MOSTRADOR', 2, 'JESSICA', 0, 'DATO NO DISPONIBLE', '', 1, NULL, NULL, NULL, '2023-10-04 01:37:22', NULL, NULL, NULL, NULL, NULL, NULL, 0, '0', '0', NULL, '0', '0');

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

--
-- Volcado de datos para la tabla `Productos`
--

INSERT INTO `Productos` (`VtaId`, `VtaDetId`, `ProId`, `ProNom`, `ProUbica`, `ProPresentacion`, `ProCodBar`, `VtaCant`, `AlisCant`, `VerCant`) VALUES
(1, 1616392, 3529, 'REGISTRO DUCHA FULL PASO GRIVAL', 'DATO NO DISPONIBLE', 'UNIDAD', '', 1, 0, -1),
(1, 1616393, 644, 'EXTENSION CROMADA METALICA DUCHA 38 CMS', 'DATO NO DISPONIBLE', 'UNIDAD', '', 1, 0, -1),
(2, 1616400, 2550, 'ESTUCO X CUARTO', 'DATO NO DISPONIBLE', 'UNIDAD', '1-4E', 2, 0, -1),
(2, 1616401, 2412, 'CODO SANITARIO 1 1/2\" CXC', 'DATO NO DISPONIBLE', 'UNIDAD', 'CS11-2', 12, 0, -1),
(2, 1616402, 2859, 'SILICONA UK 50ML ULTRA GREY ALTA TEMPERATURA', 'P16B1B1', 'UNIDAD', '6973653174920', 1, 0, -1),
(3, 1616403, 1559, 'BROCHA MONA 1 1/2\" REPTOOLS', 'DATO NO DISPONIBLE', 'UNIDAD', '8402010', 5, 0, -1),
(3, 1616404, 3130, 'UNION UNIVERSAL SOLDADA 1/2\"', 'DATO NO DISPONIBLE', 'UNIDAD', '', 6, 0, -1),
(4, 1616405, 1523, 'SIFON LAVAMANOS VALVULA CROMAD', 'DATO NO DISPONIBLE', 'UNIDAD', '001599', 4, 0, -1),
(4, 1616406, 1495, 'MANIJA TANQUE CROMO RIOPLAST', 'DATO NO DISPONIBLE', 'UNIDAD', '0320011308', 5, 0, -1),
(5, 1616407, 3390, 'ENVASE VIDRIO  1000CC TTE B 63 MAYONE C/T', 'DATO NO DISPONIBLE', 'UNIDAD', 'VC5', 2, 0, -1),
(6, 1616415, 15, 'ENVASE COMPOTA VIDRIO 32 ML', 'DATO NO DISPONIBLE', 'UNIDAD', 'VC8', 3, 0, -1),
(7, 1616416, 1189, 'POMO UNIVERSAL PRISMA', 'DATO NO DISPONIBLE', 'UNIDAD', '', 1, 0, -1),
(8, 1616417, 4006, 'MACHETE COLIMA 18\" PULIDO 721H', 'DATO NO DISPONIBLE', 'UNIDAD', '7706912055673', 10, 0, -1),
(8, 1616418, 4052, 'MACHETE COLIMA 20\" PULIDO 721H', 'DATO NO DISPONIBLE', 'UNIDAD', '', 10, 0, -1),
(8, 1616419, 4010, 'TUBO CONDUIT ORIGINAL 1/2\" SEMIPESADO', 'DATO NO DISPONIBLE', 'UNIDAD', '', 200, 0, -1),
(8, 1616420, 2685, 'LLANTA BUGGI ANTIPINCHAZO AMARILLA 350-8', 'DATO NO DISPONIBLE', 'UNIDAD', '', 5, 0, -1),
(8, 1616421, 3921, 'BOMBILLO LED 12W UDUKE (HT80398)', 'P22B1A3', 'UNIDAD', '6973653171264', 36, 0, -1),
(8, 1616422, 2350, 'BOMBILLO LED 30W MULTIVOLTAJE', 'DATO NO DISPONIBLE', 'UNIDAD', '6973653171318', 12, 0, -1),
(8, 1616423, 2101, 'BOMBILLO LED 36W MULTIVOLTAJE', 'P22B1A4', 'UNIDAD', '', 1, 0, -1),
(8, 1616424, 166, 'BOMBILLO LED 45W MULTIVOLTAJE', 'P22B2A5', 'UNIDAD', '', 12, 0, -1),
(8, 1616425, 2343, 'PANEL LED 18W ULTRADELGADA MULTIVOLTAJE', 'DATO NO DISPONIBLE', 'UNIDAD', '6973877764624', 20, 0, -1),
(8, 1616426, 1249, 'CINTA ENMASCARAR UDUKE 2\" X 39 MTR', 'P16B2B3', 'UNIDAD', '', 24, 0, -1),
(8, 1616427, 399, 'CINTA ENMASCARAR 11/2 UDUKE X 40 MTR', 'P16B2B3', 'UNIDAD', '6973653178652', 24, 0, -1),
(8, 1616428, 390, 'CINTA ENMASCARAR 3M 1\" X 50MTS', 'P16B3B3', 'UNIDAD', '', 24, 0, -1),
(8, 1616429, 3449, 'VALVULA POZUELO PLASTICO SIN SOSCO 2    .\r\n', 'DATO NO DISPONIBLE', 'UNIDAD', '', 12, 0, -1),
(8, 1616430, 1451, 'VALVULA POZUELO COMBINADA 2', 'DATO NO DISPONIBLE', 'UNIDAD', '', 12, 0, -1),
(8, 1616431, 2584, 'CINTA PELIGRO X 500 MTS', 'DATO NO DISPONIBLE', 'UNIDAD', '', 6, 0, -1),
(8, 1616432, 1470, 'CIMBRA CON MINERAL UK', 'P17B3A4', 'UNIDAD', '', 6, 0, -1),
(8, 1616433, 1565, 'BROCHA MONA 4\" REPTOOLS', 'DATO NO DISPONIBLE', 'UNIDAD', '8404106', 36, 0, -1),
(8, 1616434, 456, 'BROCHA MONA 3\" REPTOOLS', 'DATO NO DISPONIBLE', 'UNIDAD', '', 36, 0, -1),
(8, 1616435, 3892, 'BROCHA UK 2 1/2\" MONA MANGO NEGRO', 'DATO NO DISPONIBLE', 'UNIDAD', '4680007014949', 36, 0, -1),
(8, 1616436, 2004, 'BOXER EVOLUCION ORIGINAL 200 CC', 'DATO NO DISPONIBLE', 'UNIDAD', '7707030289438', 10, 0, -1),
(8, 1616437, 4011, 'BOXER EVOLUCION ORIGINAL 375 CC', 'DATO NO DISPONIBLE', 'UNIDAD', '7707030250650', 10, 0, -1),
(8, 1616438, 3541, 'PELACABLE UDUKE DE 5\" (HT80008)', 'DATO NO DISPONIBLE', 'UNIDAD', '', 6, 0, -1),
(8, 1616439, 2243, 'PELACABLE UDUKE DE 6\" (HT80007)', 'DATO NO DISPONIBLE', 'UNIDAD', '', 6, 0, -1),
(8, 1616440, 4037, 'PELACABLE UDUKE DE 8\" CON CORTADOR', 'DATO NO DISPONIBLE', 'UNIDAD', '', 6, 0, -1),
(8, 1616441, 2983, 'GAFA DEPORTIVA LENTE NEGRO FINA', 'DATO NO DISPONIBLE', 'UNIDAD', '7257', 36, 0, -1),
(8, 1616442, 1838, 'GAFA DEPORTIVA TRANSPARENTE HT CON NORMA', 'DATO NO DISPONIBLE', 'UNIDAD', '7621', 36, 0, -1),
(8, 1616443, 3566, 'GANCHO GRAPADORA 12MM 1000PZS TOTAL', 'DATO NO DISPONIBLE', 'UNIDAD', '', 20, 0, -1),
(8, 1616444, 349, 'CEPILLO ACERO CARIBE MANGO PLASTICO', 'DATO NO DISPONIBLE', 'UNIDAD', '', 12, 0, -1),
(8, 1616445, 3449, 'BOMBILLO LED 20W.\r\n', 'DATO NO DISPONIBLE', 'UNIDAD', '', 24, 0, -1),
(8, 1616446, 3449, 'BOMBILLO LED 15W .\r\n', 'DATO NO DISPONIBLE', 'UNIDAD', '', 24, 0, -1),
(8, 1616447, 3449, 'BOMBILLO LED 50W.\r\n', 'DATO NO DISPONIBLE', 'UNIDAD', '', 12, 0, -1),
(8, 1616448, 1065, 'MINERAL ROJO X BULTO', 'DATO NO DISPONIBLE', 'UNIDAD', '2283', 2, 0, -1),
(8, 1616449, 1065, 'MINERAL ROJO X BULTO', 'DATO NO DISPONIBLE', 'UNIDAD', '2283', 2, 0, -1),
(8, 1616450, 3449, 'CINTA PELIGRO X 100 MTS  .\r\n', 'DATO NO DISPONIBLE', 'UNIDAD', '', 12, 0, -1),
(8, 1616451, 3449, 'FUNDA MECHETE 20\".\r\n', 'DATO NO DISPONIBLE', 'UNIDAD', '', 10, 0, -1),
(8, 1616452, 1148, 'PISTOLA CALAFATEO ESQUELETO NARANJA HT30528', 'DATO NO DISPONIBLE', 'UNIDAD', '', 12, 0, -1),
(8, 1616453, 3265, 'REMACHADORA SUPER SELECT TOTAL 10.5\"', 'DATO NO DISPONIBLE', 'UNIDAD', '', 6, 0, -1),
(8, 1616454, 649, 'SIFON EXTENSIBLE LAVAMANOS CROMADO CON CANASTILLA', 'DATO NO DISPONIBLE', 'UNIDAD', '', 24, 0, -1),
(8, 1616455, 2169, 'SOLDADURA 6013X1/8\" LINCOLN FERRETERA', 'DATO NO DISPONIBLE', 'UNIDAD', '', 40, 0, -1),
(8, 1616456, 1928, 'SOLDADURA 6013X3/32\" LINCOLN FERRETERA', 'DATO NO DISPONIBLE', 'UNIDAD', '', 40, 0, -1),
(8, 1616457, 2470, 'TERMINAL CONDUIT 1/2\" INDUMA', 'DATO NO DISPONIBLE', 'UNIDAD', '', 1000, 0, -1),
(9, 1616458, 539, 'DISCO FLAP ENERGY 4 1/2\" GR 60 ZIRCONIO', 'DATO NO DISPONIBLE', 'UNIDAD', '6973653178768', 10, 0, -1),
(9, 1616459, 2823, 'LLAVE LAVAMANOS ALETA CIERRE RAPIDO UDUKE HT1201', 'DATO NO DISPONIBLE', 'UNIDAD', '', 1, 0, -1),
(10, 1616460, 293, 'LINEA BK PLUS BLANCA TOMA DOBLE UDUKE', 'P16B3A6', 'UNIDAD', '', 20, 0, -1),
(10, 1616461, 3495, 'LINEA BK PLUS INCRUSTAR SWICHE SENCILLO CONMUTABLE', 'DATO NO DISPONIBLE', 'UNIDAD', '', 20, 0, -1);

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
(1, '1006873236', 'Carlos', 'Medina', 'csamuelrox@gmail.com', '$2y$10$zNV2Myto9CVJuFLXpWmPZui81y1lwwfF.NMuR9zi9s27hDVE53.1W'),
(2, '1112340543', 'Mariana', 'Medina Pardo', 'marianasdasd@gma', '827ccb0eea8a706c4c34a16891f84e7b'),
(3, '123456789', 'Miguel', 'Medina', 'miguel@gmail.com', '$2y$10$zNV2Myto9CVJuFLXpWmPZui81y1lwwfF.NMuR9zi9s27hDVE53.1W'),
(4, '1234567', 'Jorge Luis', 'Ramirez', 'jorge@gmail.com', '$2y$10$P81JChC2Vo.5leP36XzyseXYvtLLWEetdtm8wYWuiJqmK1.aMRBE.');

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
(2, 1),
(4, 2),
(3, 3),
(3, 4),
(4, 4),
(1, 5),
(2, 5),
(4, 6);

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
