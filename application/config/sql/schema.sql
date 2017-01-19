# ************************************************************
# SCHEMA SISTEMA BASE - BLACKCORE
# ************************************************************

# Dump of table catalogos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `catalogos`;

CREATE TABLE IF NOT EXISTS `catalogos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table categorias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cuentas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cuentas`;

CREATE TABLE IF NOT EXISTS `cuentas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `razon_social` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `rfc` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `asentamiento` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `pais` varchar(15) DEFAULT 'MEXICO',
  `calle` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero_exterior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero_interior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(70) NOT NULL DEFAULT '',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table elementos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elementos`;

CREATE TABLE IF NOT EXISTS `elementos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalogos_id` int(11) NOT NULL,
  `clave` varchar(10) CHARACTER SET latin1 NOT NULL,
  `valor` varchar(255) CHARACTER SET latin1 NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table funciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `funciones`;

CREATE TABLE IF NOT EXISTS `funciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorias_id` int(11) NOT NULL DEFAULT '1',
  `funcion` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `publico` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `funcion` (`funcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table grupos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `grupos`;

CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `logs`;

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipos_id` int(11) NOT NULL,
  `usuarios_id` int(11) DEFAULT NULL,
  `mensaje` varchar(255) NOT NULL,
  `datos` text,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table noticias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `noticias`;

CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `fecha` date NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `inicio` tinyint(1) NOT NULL DEFAULT '0',
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table permisos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permisos`;

CREATE TABLE IF NOT EXISTS `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupos_id` int(11) NOT NULL,
  `funciones_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table sepomex
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sepomex`;

CREATE TABLE IF NOT EXISTS `sepomex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_postal` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `asentamiento` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tipo_asentamiento` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `municipio` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `estado` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ciudad` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion_codigo_postal` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave_estado` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave_oficina` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave_codigo_postal` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clave_tipo_asentamiento` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave_municipio` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave_asentamiento` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zona` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave_ciudad` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clave_estado` (`clave_estado`),
  KEY `clave_municipio` (`clave_municipio`),
  KEY `clave_asentamiento` (`clave_asentamiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table sesiones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sesiones`;

CREATE TABLE IF NOT EXISTS `sesiones` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table usuarios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(40) NOT NULL,
  `contrasena` varchar(40) NOT NULL,
  `grupos_id` int(11) NOT NULL,
  `cuentas_id` int(11) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `celular` varchar(100) DEFAULT NULL,
  `ayuda` tinyint(1) NOT NULL DEFAULT '1',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- ESTRUCTURA DE LA TABLA DE PRODUCTOS
--

CREATE TABLE `productos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categorias_id` int(11) NOT NULL,
  `descripcion` text CHARACTER SET latin1 NOT NULL,
  `costo` float NOT NULL,
  `precio` float NOT NULL,
  `activo` int(1) DEFAULT '1',
  `eliminado` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Estructura de tabla para la tabla `productos_categorias`
--

CREATE TABLE IF NOT EXISTS `productos_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `descripcion` varchar(4096) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `eliminado` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


--
-- ESTRUCTURA DE LA TABLA FOTOGRAFIAS DE LOS REPORTES
--

CREATE TABLE `fotografias_productos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `productos_id` int(11) DEFAULT NULL,
  `tipos_id` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `eliminado` int(1) DEFAULT '0',
  `activo` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET=utf8;


--
-- AJUSTE EN LA TABLA PRODUCTOS_CATEGORIAS
--

ALTER TABLE  `productos_categorias` CHANGE  `parent_id`  `parent_id` INT( 11 ) NULL;
ALTER TABLE  `productos_categorias` CHANGE  `activo`  `activo` TINYINT( 1 ) NOT NULL DEFAULT  '1';


--
-- AJUSTE DE LA TABLA PRODUCTOS
--
ALTER TABLE `productos` ADD `nombre` VARCHAR( 150 ) NOT NULL AFTER `id` ;

--
-- ESTRUCTURA DE LA TABLA BANNERS
--

CREATE TABLE `banners` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categorias_id` int(11) DEFAULT NULL,
  `productos_id` int(11) DEFAULT NULL,
  `activo` int(11) DEFAULT '1',
  `eliminado` int(11) DEFAULT '0',
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- AJUSTE DE LA TABLA PRODUCTOS
--

ALTER TABLE `productos` ADD `item` INT( 11 ) NULL AFTER `precio` ,
ADD `measurements` TEXT NULL AFTER `item` ,
ADD `materials` TEXT NULL AFTER `measurements` ,
ADD `more_information` TEXT NULL AFTER `materials` ,
ADD `features` TEXT NULL AFTER `more_information` ,
ADD `care_assembly` TEXT NULL AFTER `features`;
ALTER TABLE `productos` ADD `precio_oferta` FLOAT NULL AFTER `precio`;
ALTER TABLE `productos` CHANGE `precio` `precio` DECIMAL( 10, 2 )  NOT NULL;
ALTER TABLE `productos` CHANGE `precio_oferta` `precio_oferta` DECIMAL( 10, 2 ) NULL DEFAULT NULL ;

--
-- ESTRUCTURA DE LA TABLA COTIZACIONES
--

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuentas_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `iva` float DEFAULT NULL,
  `iva_porcentaje` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `eliminado` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- ESTRUCTURA DE LA TABLA COTIZACIONES PRODUCTOS
--

CREATE TABLE `cotizaciones_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  `eliminado` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- AJUSTE EN LA TABLA  PRODUCTOS
--

ALTER TABLE `productos` CHANGE `item` `item` VARCHAR( 20 ) NULL DEFAULT NULL ;
ALTER TABLE `productos` CHANGE `costo` `costo` FLOAT NOT NULL ,
CHANGE `precio` `precio` FLOAT NOT NULL ,
CHANGE `precio_oferta` `precio_oferta` FLOAT NULL DEFAULT NULL ;


--
-- AJUSTES 2012/01/12
--
ALTER TABLE `productos`  DROP COLUMN `precio_oferta`;


--
-- AJUSTES TABLA COTIZACIONES 2012/01/12
--
ALTER TABLE `cotizaciones` ADD `razon_social` VARCHAR( 250 ) NULL DEFAULT NULL AFTER `fecha` ,
ADD `rfc` VARCHAR( 20 ) NULL DEFAULT NULL AFTER `razon_social` ,
ADD `estado` VARCHAR( 30 ) NULL DEFAULT NULL AFTER `rfc` ,
ADD `municipio` VARCHAR( 30 ) NULL DEFAULT NULL AFTER `estado` ,
ADD `codigo_postal` INT( 11 ) NULL DEFAULT NULL AFTER `municipio` ,
ADD `asentamiento` VARCHAR( 30 ) NULL DEFAULT NULL AFTER `codigo_postal` ,
ADD `calle` VARCHAR( 100 ) NULL DEFAULT NULL AFTER `asentamiento` ,
ADD `numero_exterior` INT( 11 ) NULL DEFAULT NULL AFTER `calle` ,
ADD `numero_interior` INT( 11 ) NULL DEFAULT NULL AFTER `numero_exterior`;

--
-- AJUSTES TABLA COTIZACIONES_PRODUCTOS 2012/01/12
--

ALTER TABLE `cotizaciones_productos` ADD `cantidad` INT( 11 ) NULL AFTER `productos_id` ,
ADD `observaciones` TEXT NULL AFTER `cantidad` ;
ALTER TABLE `cotizaciones_productos` ADD `descuento` DECIMAL NULL AFTER `cantidad`;
ALTER TABLE `cotizaciones` DROP `iva` ,DROP `iva_porcentaje` ;
ALTER TABLE `cotizaciones` ADD `observaciones` TEXT NULL AFTER `total` ;
ALTER TABLE `cotizaciones` ADD `folio` INT( 11 ) NULL AFTER `cuentas_id` ;
ALTER TABLE `cotizaciones` CHANGE `cuentas_id` `cuentas_id` INT( 11 ) NULL ;



--
-- CONSECUTIVOS COTIZACIONES
--
CREATE TABLE `consecutivos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anio` int(11) NOT NULL,
  `cotizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- FOLIO COTIZACIONES
--
ALTER TABLE `cotizaciones` CHANGE `folio` `folio` VARCHAR( 10 ) NULL DEFAULT NULL ;

ALTER TABLE `cotizaciones_productos` CHANGE `descuento` `descuento` FLOAT NULL DEFAULT NULL ;

ALTER TABLE `cotizaciones` ADD `email` VARCHAR( 120 ) NULL AFTER `numero_interior` ,
ADD `telefono` VARCHAR( 100 ) NULL AFTER `email` ;


--
-- AJUSTE PRODUCTOS
--
ALTER TABLE  `productos` ADD  `tipo_moneda_id` INT( 11 ) NULL AFTER  `precio`;


--
-- AJUSTES TABLA BANNERS 2012/01/20
--
ALTER TABLE  `banners` ADD  `banner_principal` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `productos_id`;

--
-- AJUSTES TABLA PRODUCTOS Fotografias 2012/01/20
--
ALTER TABLE  `fotografias_productos` ADD  `nombre_original` INT( 200 ) NOT NULL;
ALTER TABLE  `fotografias_productos` ADD  `extension` INT( 200 ) NOT NULL;

--
-- AJUSTES TABLA PRODUCTOS 
--
ALTER TABLE  `productos` CHANGE  `costo`  `costo` FLOAT NULL;
ALTER TABLE `productos`
  DROP `measurements`,
  DROP `materials`,
  DROP `more_information`,
  DROP `features`,
  DROP `care_assembly`;
  
--
-- ESTRUCTURA DE LA TABLA ACESORIOS 
--
  CREATE TABLE `accesorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modelo` varchar(255)  NOT NULL DEFAULT '',
  `item` int(30) NOT NULL,
  `precio` float NOT NULL,
  `tipo_moneda_id` int(11) NOT NULL,
  `descripcion` text  NOT NULL,
  `eliminado` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- AJUSTES TABLA ACCESORIOS 
--
ALTER TABLE  `accesorios` ADD  `productos_id` INT NULL AFTER  `modelo`;

--
-- ESTRUCTURA DE LA TABLA TIPOS ACCESORIOS 
--
CREATE TABLE `tipos_accesorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(256) NOT NULL,
  `descripcion` varchar(4096) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `eliminado` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- AJUSTES DE LA TABLA ACESORIOS 
--
ALTER TABLE `accesorios` ADD `tipos_accesorios_id` INT NOT NULL AFTER `productos_id`;
ALTER TABLE `accesorios` DROP `productos_id`;
ALTER TABLE `accesorios` ADD `activo` INT( 1 ) NOT NULL DEFAULT '1' AFTER `descripcion` ;

--
-- ESTRUCTURA DE LA TABLA ACESORIOS FOTOGRAFIAS 
--
CREATE TABLE `fotografias_accesorios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `productos_id` int(11) DEFAULT NULL,
  `tipos_id` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `eliminado` int(1) DEFAULT '0',
  `activo` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `nombre_original` int(200) NOT NULL,
  `extension` int(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- AJUSTES  
--
ALTER TABLE `fotografias_accesorios` CHANGE `productos_id` `accesorios_id` INT( 11 ) NULL DEFAULT NULL ;
ALTER TABLE `cuentas` CHANGE `email` `email` VARCHAR( 70 ) CHARACTER SET utf8  NULL DEFAULT '';

--
-- ESTRUCTURA DE LA TABLA PRODUCTOS_TIPOS_ACCESORIOS
--
CREATE TABLE `productos_tipos_accesorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipos_accesorios_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  `eliminado` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_bye` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- AJUSTES DE LA TABLA COTIZACIONES
--
ALTER TABLE `cotizaciones` ADD `nombre_contacto` VARCHAR( 100 ) NULL AFTER `telefono` ,
ADD `telefono_particular` INT( 20 ) NULL AFTER `nombre_contacto` ,
ADD `telefono_celular` INT( 20 ) NULL AFTER `telefono_particular` ,
ADD `entrega_estado` VARCHAR( 50 ) NULL AFTER `telefono_celular` ,
ADD `entrega_asentamiento` VARCHAR( 50 ) NULL AFTER `entrega_estado` ,
ADD `entrega_municipio` VARCHAR( 50 ) NULL AFTER `entrega_asentamiento` ,
ADD `entrega_codigo_postal` INT( 10 ) NULL AFTER `entrega_municipio` ,
ADD `entrega_calle` VARCHAR( 50 ) NULL AFTER `entrega_codigo_postal` ,
ADD `entrega_numero_exterior` INT( 11 ) NULL AFTER `entrega_calle` ,
ADD `entrega_numero_interior` INT( 11 ) NULL AFTER `entrega_numero_exterior`;

--
-- AJUSTES DE LA TABLA COTIZACIONES 
--
ALTER TABLE `cotizaciones` CHANGE `telefono_particular` `telefono_particular` VARCHAR( 30 ) NULL DEFAULT NULL ,
CHANGE `telefono_celular` `telefono_celular` VARCHAR( 30 ) NULL DEFAULT NULL;



--
-- AJUSTES DE TABLAS
--
ALTER TABLE `cuentas` ADD `consecutivo` INT NULL AFTER `nombre`;
ALTER TABLE `cotizaciones` ADD `folio_cuentas` INT( 11 ) NOT NULL AFTER `folio`;
ALTER TABLE `cotizaciones` CHANGE `folio_cuentas` `folio_cuentas` VARCHAR( 11 ) NOT NULL ;
ALTER TABLE  `productos` ADD  `modelo` VARCHAR( 50 ) NULL AFTER  `nombre`;
ALTER TABLE `accesorios` ADD `nombre` VARCHAR( 100 ) NULL AFTER `modelo`;
ALTER TABLE `cuentas` ADD `clave` VARCHAR( 20 ) NULL AFTER `activo` ,
ADD `cuenta_clabe` INT NULL AFTER `clave` ,
ADD `cuenta_bancaria` INT NULL AFTER `cuenta_clabe` ,
ADD `sucursal` VARCHAR( 50 ) NULL AFTER `cuenta_bancaria` ,
ADD `sucursal_fisica` VARCHAR( 100 ) NULL AFTER `sucursal` ;
ALTER TABLE `cotizaciones` ADD `iva_procentaje` INT NULL AFTER `subtotal`;


--
-- ESTRUCTURA DE LA TABLA COTIZACIONES ACCESORIOS
--	
	
CREATE TABLE `cotizaciones_accesorios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) DEFAULT NULL,
  `productos_id` int(11) DEFAULT NULL,
  `accesorios_id` int(11) DEFAULT NULL,
  `tipos_accesorios_id` int(11) DEFAULT NULL,
  `eliminado` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;

--
-- CAMBIO TABLA EN TABLA CONSECUTIVO
--	
ALTER TABLE  `consecutivos` ADD  `consecutivo` INT( 11 ) NOT NULL AFTER  `cotizacion`;


--
-- CAMBIO TABLA EN TABLA COTIZACIONES
--	
ALTER TABLE  `cotizaciones` ADD  `status_id` INT( 11 ) NOT NULL DEFAULT  '1' AFTER  `cuentas_id`;
ALTER TABLE `cotizaciones` CHANGE `iva_procentaje` `iva_porcentaje` INT( 11 ) NULL DEFAULT NULL ;

ALTER TABLE `productos_tipos_accesorios` ADD `tipo_id` INT NOT NULL AFTER `productos_id` ;
ALTER TABLE `productos_tipos_accesorios` CHANGE `tipo_id` `tipos_id` INT( 11 ) NULL;

--
-- AJUSTES
--	

ALTER TABLE `productos_tipos_accesorios` CHANGE `tipos_id` `obligatorio_id` INT( 1 ) NULL DEFAULT '0';
ALTER TABLE `cotizaciones` ADD `fecha_compra` DATE NULL AFTER `fecha` ;
ALTER TABLE `cotizaciones` ADD `folio_compra` VARCHAR( 50 ) NULL ;
ALTER TABLE `cuentas` ADD `consecutivo_compra` INT( 11 ) NULL DEFAULT '0' AFTER `consecutivo` ;
ALTER TABLE `cotizaciones` ADD `nombre` VARCHAR( 25 ) NULL AFTER `razon_social` ,
ADD `apellido_paterno` VARCHAR( 25 ) NULL AFTER `nombre` ,
ADD `apellido_materno` VARCHAR( 25 ) NULL AFTER `apellido_paterno` ;

ALTER TABLE  `cotizaciones` ADD  `forma_pago_id` INT( 11 ) NULL AFTER  `entrega_numero_interior`;
ALTER TABLE  `cotizaciones` ADD  `fecha_entrega` DATE NULL AFTER  `fecha_compra`;
ALTER TABLE  `cotizaciones` ADD  `condiciones_pago_id` INT NULL AFTER  `observaciones`;

ALTER TABLE `cotizaciones_accesorios` ADD `cantidad` INT NULL AFTER `tipos_accesorios_id`;
ALTER TABLE `cotizaciones_accesorios` ADD `cotizaciones_productos_id` INT( 11 ) NULL AFTER `tipos_accesorios_id` ;

--
-- SKU
--

ALTER TABLE `accesorios` CHANGE `item` `item` VARCHAR( 30 ) NOT NULL ;

--
-- TIMESTAMP PRODUCTO IDENTIFICADOR
--

ALTER TABLE `cotizaciones_productos` ADD `timestamp` VARCHAR( 10 ) NOT NULL AFTER `observaciones` ;
ALTER TABLE `cotizaciones_accesorios` ADD `timestamp` VARCHAR( 10 ) NOT NULL AFTER `id` ;

--
-- agregar campo nombre del vendedor
--

ALTER TABLE  `cotizaciones` ADD  `nombre_vendedor` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `condiciones_pago_id`;
ALTER TABLE  `cotizaciones` ADD  `paterno_vendedor` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `nombre_vendedor` ,
ADD  `materno_vendedor` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `paterno_vendedor`;

--
-- agregar campos para el comprador
--
ALTER TABLE  `cotizaciones` ADD  `nombre_comprador` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `materno_vendedor` ,
ADD  `paterno_comprador` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `nombre_comprador` ,
ADD  `materno_comprador` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `paterno_comprador` ,
ADD  `email_comprador` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `materno_comprador` ,
ADD  `telefono_comprador` VARCHAR( 15 ) NOT NULL AFTER  `email_comprador` ,
ADD  `fecha_nacimiento_comprador` VARCHAR( 5 ) NULL AFTER  `telefono_comprador` ,
ADD  `anio_nacimiento_comprador` INT( 4 ) NULL AFTER  `fecha_nacimiento_comprador`,
ADD  `fecha_aniversario_comprador` VARCHAR( 5 ) NULL AFTER  `fecha_nacimiento_comprador`;

ALTER TABLE `productos_categorias` ADD `foto_id` tinyint(1) NULL AFTER `descripcion`;
ALTER TABLE  `cotizaciones` ADD `tipo_persona_id` INT( 1 ) NULL  DEFAULT NULL AFTER  `fecha_aniversario_comprador`;

ALTER TABLE `elementos` CHANGE `valor` `valor` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; 

ALTER TABLE  `usuarios` ADD  `admin` BOOLEAN NULL AFTER  `ayuda`,
ADD  `vendedor` BOOLEAN NULL AFTER  `admin`;

ALTER TABLE  `cotizaciones` ADD  `usuario_id` INT( 11 ) NOT NULL AFTER  `cuentas_id`;

UPDATE  `funciones` SET  `funcion` =  'cotizaciones_individuales',
`descripcion` =  'Permite visualizar únicamente las cotizaciones a su cargo.' WHERE  `funciones`.`id` =44;

