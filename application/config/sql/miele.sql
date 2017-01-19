--
-- MODULO DE COMISIONES
--
DROP TABLE IF EXISTS `comisiones`;
CREATE TABLE IF NOT EXISTS `comisiones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `status_id` INT(2) NOT NULL DEFAULT 1,
  `cotizaciones_id` INT(11) NOT NULL,
  `cuentas_id` INT(11) NULL DEFAULT NULL,
  `usuarios_id` INT(11) NULL DEFAULT NULL,
  `porcentaje` FLOAT NOT NULL,
  `monto` DOUBLE NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `eliminado` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categorias` (`id`, `nombre`, `activo`)
VALUES	(9, 'Comisiones', 1);

INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '9',  'comisiones',  'Permite el acceso al módulo de comisiones.',  '1'),
  (NULL ,  '9',  'comisiones_pagar',  'Permite cambiar el status de una comisión a Pagada.',  '1'),
  (NULL ,  '9',  'comisiones_cancelar',  'Permite cambiar el status de una comisión a Cancelada.',  '1');

--
-- 26-11-2013
--
DROP TABLE IF EXISTS `paquetes`;
CREATE TABLE IF NOT EXISTS `paquetes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `descuento` FLOAT DEFAULT NULL,
  `descuento_distribuidor` FLOAT DEFAULT NULL,
  `comision_vendedor` FLOAT DEFAULT NULL,
  `descuento_exhibicion` FLOAT DEFAULT NULL,
  `eliminado` TINYINT(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categorias` (`id`, `nombre`, `activo`)
VALUES	(10, 'Paquetes', 1);

INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,'10',  'paquetes',  'Permite el acceso al módulo de paquetes.','1'),
  (NULL ,'10',  'paquetes_agregar',  'Permite agregar un paquete','1'),
  (NULL ,'10',  'paquetes_editar',  'Permite editar un paquete','1'),
  (NULL ,'10',  'paquetes_eliminar',  'Permite eliminar un paquete.','1');


-- paquetes_productos
DROP TABLE IF EXISTS `paquetes_productos`;
CREATE TABLE IF NOT EXISTS `paquetes_productos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `productos_id` INT(11) NOT NULL,
  `paquetes_id` INT(11) NOT NULL DEFAULT 1,
  `cantidad` int DEFAULT NULL,
  `eliminado` TINYINT(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,'10',  'paquetes_productos_eliminar',  'Permite eliminar un producto del paquete.','1');

-- agregar descuentos a la tabla productos_categorias
ALTER TABLE  `productos_categorias` ADD  `descuento_base` FLOAT NULL AFTER  `nombre` ,
ADD  `descuento_exhibicion` FLOAT NULL AFTER  `descuento_base`;

-- agregar catalogo Referidos
INSERT INTO `categorias` (`id` ,`nombre` ,`activo`) VALUES (11 ,  'Referidos',  '1');

-- Agregar Permisos de Referidos
INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  (NULL, '11', 'referidos', 'Permite ver a los referidos', '1'),
  (NULL, '11', 'referidos_agregar', 'Permite agregar un referido.', '1');

--
-- Estructura de tabla para la tabla `referidos`
--
DROP TABLE IF EXISTS `referidos`;
CREATE TABLE IF NOT EXISTS `referidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) DEFAULT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `distribuidores_id` int(11) DEFAULT NULL,
  `vendedores_id` int(11) DEFAULT NULL,
  `vigencia` datetime NOT NULL,
  `eliminado` TINYINT(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Campo de clave para la tabla de categoria
-- 20131127
--
ALTER TABLE productos_categorias ADD COLUMN clave VARCHAR(20) DEFAULT NULL AFTER parent_id ;

-- AMPLIO LONGITUD DE CAMPO FOLIO DE COTIZACION PARA LAS EDICIONES SE PONGA UN - CONSECUTIVO
ALTER TABLE cotizaciones CHANGE folio_cuentas folio_cuentas VARCHAR(13) NOT NULL DEFAULT '';

--
-- Campos para descuentos en cuentas
-- 20131127
--
ALTER TABLE cuentas ADD COLUMN distribuidor TINYINT DEFAULT 0 AFTER sucursal_fisica;
ALTER TABLE cuentas ADD COLUMN credito TINYINT DEFAULT 0 AFTER sucursal_fisica;
ALTER TABLE cuentas ADD COLUMN descuento_espacio DECIMAL(5,2) DEFAULT 0 AFTER distribuidor;
ALTER TABLE cuentas ADD COLUMN descuento_monto DECIMAL(5,2) DEFAULT 0 AFTER distribuidor;
ALTER TABLE cuentas ADD COLUMN descuento_cooperacion DECIMAL(5,2) DEFAULT 0 AFTER distribuidor;
ALTER TABLE cuentas ADD COLUMN descuento_transicion DECIMAL(5,2) DEFAULT 0 AFTER distribuidor;


--
-- Campos para descuentos en categorias
-- 20131128
--
ALTER TABLE `productos_categorias` DROP COLUMN `descuento_base`;
ALTER TABLE `productos_categorias` ADD COLUMN `descuento_base` DECIMAL(5,2) DEFAULT 0;
ALTER TABLE `productos_categorias` DROP COLUMN `descuento_exhibicion`;
ALTER TABLE `productos_categorias` ADD COLUMN `descuento_exhibicion` DECIMAL(5,2) DEFAULT 0;

--
-- Campos para descuentos en paquetes
-- 20131128
--
ALTER TABLE `paquetes` DROP COLUMN `descuento`;
ALTER TABLE `paquetes` ADD COLUMN `descuento` DECIMAL(5,2) DEFAULT 0;
ALTER TABLE `paquetes` DROP COLUMN `descuento_distribuidor`;
ALTER TABLE `paquetes` ADD COLUMN `descuento_distribuidor` DECIMAL(5,2) DEFAULT 0;
ALTER TABLE `paquetes` DROP COLUMN `descuento_exhibicion`;
ALTER TABLE `paquetes` ADD COLUMN `descuento_exhibicion` DECIMAL(5,2) DEFAULT 0;
ALTER TABLE `paquetes` DROP COLUMN `comision_vendedor`;
ALTER TABLE `paquetes` ADD COLUMN `comision_vendedor` DECIMAL(5,2) DEFAULT 0;

-- referidos editar.
INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  (NULL, '11', 'referidos_editar', 'Permite editar un referido.', '1');

UPDATE `funciones` set descripcion='Permite administrar referidos.' WHERE id=57;

--
-- Campos para el precio en las cotizaciones
-- 20131128
--
ALTER TABLE cotizaciones_productos ADD COLUMN precio DECIMAL(9,2) DEFAULT 0;

UPDATE `funciones` set descripcion='Permite administrar referidos.' WHERE id=57;

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  (NULL, '11', 'referidos_mostrar_distribuidores', 'Permite seleccionar el distribuidor.', '1'),
  (NULL, '11', 'referidos_mostrar_vendedores', 'Permite seleccionar el vendedor.', '1'),
  (NULL, '11', 'referidos_mostrar_todos', 'Permite mostrar todos los referidos.', '1'),
  (NULL, '11', 'referidos_mostrar_por_distribuidor', 'Permite mostrar todos los referidos del distribuidor.', '1'),
  (NULL, '11', 'referidos_mostrar_propios', 'Permite mostrar todos los referidos del vendedor.', '1');

ALTER TABLE paquetes ADD  descripcion varchar(200) DEFAULT NULL;
ALTER TABLE paquetes ADD fotos_id TINYINT(1) NOT NULL DEFAULT '0' AFTER descripcion;

DROP TABLE IF EXISTS `cotizaciones`;

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuentas_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT '1',
  `folio` varchar(10) DEFAULT NULL,
  `folio_cuentas` varchar(13) DEFAULT '',
  `fecha` date DEFAULT NULL,
  `fecha_compra` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `razon_social` varchar(250) DEFAULT NULL,
  `nombre` varchar(25) DEFAULT NULL,
  `apellido_paterno` varchar(25) DEFAULT NULL,
  `apellido_materno` varchar(25) DEFAULT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `estado` varchar(30) DEFAULT NULL,
  `municipio` varchar(30) DEFAULT NULL,
  `codigo_postal` int(11) DEFAULT NULL,
  `asentamiento` varchar(30) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero_exterior` int(11) DEFAULT NULL,
  `numero_interior` int(11) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `nombre_contacto` varchar(100) DEFAULT NULL,
  `telefono_particular` varchar(30) DEFAULT NULL,
  `telefono_celular` varchar(30) DEFAULT NULL,
  `entrega_estado` varchar(50) DEFAULT NULL,
  `entrega_asentamiento` varchar(50) DEFAULT NULL,
  `entrega_municipio` varchar(50) DEFAULT NULL,
  `entrega_codigo_postal` int(10) DEFAULT NULL,
  `entrega_calle` varchar(50) DEFAULT NULL,
  `entrega_numero_exterior` int(11) DEFAULT NULL,
  `entrega_numero_interior` int(11) DEFAULT NULL,
  `forma_pago_id` int(11) DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `iva_porcentaje` int(11) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `observaciones` text,
  `condiciones_pago_id` int(11) DEFAULT NULL,
  `nombre_comprador` varchar(20) DEFAULT '',
  `paterno_comprador` varchar(20) DEFAULT '',
  `materno_comprador` varchar(20) DEFAULT '',
  `email_comprador` varchar(40) DEFAULT '',
  `telefono_comprador` varchar(15) DEFAULT '',
  `fecha_nacimiento_comprador` varchar(5) DEFAULT NULL,
  `fecha_aniversario_comprador` varchar(5) DEFAULT NULL,
  `tipo_persona_id` int(1) DEFAULT NULL,
  `anio_nacimiento_comprador` int(4) DEFAULT NULL,
  `eliminado` int(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `folio_compra` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cuentas_categorias`;

CREATE TABLE `cuentas_categorias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cuentas_id` int(11) DEFAULT NULL,
  `categorias_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cuentas_paquetes`;

CREATE TABLE `cuentas_paquetes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cuentas_id` int(11) DEFAULT NULL,
  `paquetes_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `paquetes` DROP COLUMN `productos_id`;
ALTER TABLE productos_tipos_accesorios CHANGE COLUMN modified modified DATETIME DEFAULT NULL;
ALTER TABLE productos_tipos_accesorios CHANGE COLUMN modified_bye modified_by INT DEFAULT NULL;

ALTER TABLE cotizaciones_accesorios ADD COLUMN precio DECIMAL(9,2) DEFAULT 0;

TRUNCATE usuarios;
INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `grupos_id`, `cuentas_id`, `apellido_paterno`, `apellido_materno`, `nombre`, `email`, `telefono`, `celular`, `ayuda`, `activo`, `eliminado`, `created`, `created_by`, `modified`, `modified_by`)
VALUES
  (1,'remery','6468a4c9e4740e298f59a87cebcf17e3',1,1,'Emery','Pérez de León','Ricardo','remery@blackcore.com.mx','+52 (55) 50195041',NULL,1,1,0,'2011-08-15 00:00:00',1,'2011-08-15 21:34:06',1),
  (2,'fquiroz','ebc0340e5eb57ca1974209c93333bb14',1,1,'Quiroz','Rivas','Luis Fabian','fquiroz@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-15 21:59:55',1,'2011-08-15 22:01:43',1),
  (3,'jgonzalez','ebc0340e5eb57ca1974209c93333bb14',1,1,'Gonzalez','Leocadio','Joel','jgonzalez@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-15 22:02:12',1,'2011-08-15 22:03:29',1),
  (4,'acalderon','ebc0340e5eb57ca1974209c93333bb14',1,1,'Calderon','Arcos','Aline','jhernandez@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-24 20:19:51',1,'2011-08-24 20:20:31',1),
  (5,'vhuerta','ebc0340e5eb57ca1974209c93333bb14',1,1,'Huerta','Hernandez','Victor R.','vhuerta@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-24 20:19:51',1,'2011-08-24 20:20:31',1),
  (6,'mparra','ebc0340e5eb57ca1974209c93333bb14',1,1,'Parra','Benítez','Marco','mparra@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-24 20:19:51',1,'2011-08-24 20:20:31',1),
  (7,'lmendez','ebc0340e5eb57ca1974209c93333bb14',1,1,'Mendez','Miranda','Luis Andres','lmendez@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-24 20:19:51',1,'2011-08-24 20:20:31',1),
  (8,'rsoladana','ebc0340e5eb57ca1974209c93333bb14',1,1,'Soladana','Ocejo','Luis Rodrigo','rsoladana@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-24 20:19:51',1,'2011-08-24 20:20:31',1);

ALTER TABLE cuentas CHANGE COLUMN cuenta_clabe cuenta_clabe VARCHAR(25) DEFAULT '';
ALTER TABLE cuentas CHANGE COLUMN cuenta_bancaria cuenta_bancaria VARCHAR(25) DEFAULT '';

ALTER TABLE productos CHANGE eliminado eliminado INT DEFAULT NULL;
ALTER TABLE productos CHANGE created created DATETIME DEFAULT NULL;
ALTER TABLE productos CHANGE created_by created_by INT DEFAULT NULL;
ALTER TABLE productos CHANGE modified modified DATETIME DEFAULT NULL;
ALTER TABLE productos CHANGE modified_by modified_by INT DEFAULT NULL;

ALTER TABLE fotografias_productos CHANGE nombre_original nombre_original VARCHAR(200) DEFAULT NULL;
ALTER TABLE fotografias_productos CHANGE extension extension VARCHAR(200) DEFAULT NULL;

ALTER TABLE accesorios CHANGE tipo_moneda_id tipo_moneda_id INT DEFAULT NULL;
ALTER TABLE accesorios CHANGE modified modified DATETIME DEFAULT NULL;
ALTER TABLE accesorios CHANGE modified_by modified_by INT DEFAULT NULL;

ALTER TABLE referidos CHANGE vigencia vigencia DATETIME DEFAULT NULL;
INSERT INTO `catalogos` (`id`, `nombre`, `descripcion`)
VALUES
  (NULL, 'estados_envio', 'Estados con envío gratuito.');

INSERT INTO `elementos` (`id`, `catalogos_id`, `clave`, `valor`, `activo`)
VALUES
  (NULL, 5, 'DISTRITO FEDERAL', 'DISTRITO FEDERAL', 1),
  (NULL, 5, 'MEXICO', 'MEXICO', 1),
  (NULL, 5, 'QUERETARO', 'QUERETARO', 1),
  (NULL, 5, 'JALISCO', 'JALISCO', 1),
  (NULL, 5, 'NAYARIT', 'NAYARIT', 1),
  (NULL, 5, 'PUEBLA', 'PUEBLA', 1),
  (NULL, 5, 'NUEVO LEON', 'NUEVO LEON', 1),
  (NULL, 5, 'BAJA CALIFORNIA SUR', 'BAJA CALIFORNIA SUR', 1),
  (NULL, 5, 'GUANAJUATO', 'GUANAJUATO', 1),
  (NULL, 5, 'YUCATAN', 'YUCATAN', 1),
  (NULL, 5, 'QUINTANA ROO', 'QUINTANA ROO', 1);

ALTER TABLE cotizaciones CHANGE COLUMN entrega_numero_exterior entrega_numero_exterior VARCHAR(20) DEFAULT NULL;
ALTER TABLE sesiones CHANGE COLUMN user_data user_data TEXT DEFAULT NULL;
--
-- CAMPOS PARA GUARDAR REFERIDOS EN LA COTIZACION
--
ALTER TABLE cotizaciones ADD referido_distribuidor_id INT(11) DEFAULT NULL;
ALTER TABLE cotizaciones ADD referido_vendedor_id INT(11) DEFAULT NULL;

-- DESCUENTO OPCIONAL
ALTER TABLE  `productos_categorias` ADD  `descuento_opcional` FLOAT NULL AFTER  `descuento_exhibicion`;

-- BANDERA EN LA COTIZACION PARA APLICAR O NO DESCUENTOS DE PAQUETE Y OPCIONAL
ALTER TABLE cotizaciones ADD descuento_opcional TINYINT(1) DEFAULT 0;
ALTER TABLE cotizaciones ADD descuento_paquete TINYINT(1) DEFAULT 0;

-- BANDERA PARA SABER QUE PORCENTAJE DE COMISION SE LE DA AL DISTRIBUIDOR POR REFERIR UN CLIENTE
ALTER TABLE cotizaciones ADD referido_porcentaje_comision TINYINT(1) DEFAULT 0;
-- COMISION DEL VENDEDOR POR CATEGORIA
ALTER TABLE productos_categorias ADD comision_vendedor DECIMAL(5,2) DEFAULT 0;

-- PERMISOS DE VISIBILIDAD DE COMISONES
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '9',  'comisiones_propias',  'Permite ver únicamente las comisiones de un usuario.',  '1'),
  (NULL ,  '9',  'comisiones_cuenta',  'Permite ver las comisiones de la cuenta.',  '1'),
  (NULL ,  '9',  'comisiones_todas',  'Permite ver todas las comisiones.',  '1');

-- AGREGO ROL DE VENTA DIRECTA
ALTER TABLE cuentas ADD COLUMN venta_directa TINYINT DEFAULT 0 AFTER distribuidor;

-- AJUSTE DE PAQUETES SE CREA TABLA DE PAQUETES POR CATEGORIA
CREATE TABLE `paquetes_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paquetes_id` int(11) NOT NULL,
  `categorias_id` int(11) NOT NULL DEFAULT '1',
  `cantidad` int(11) DEFAULT NULL,
  `indice` tinyint(4) NOT NULL DEFAULT '0',
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- BORRO TABLA DE PAQUETES PRODUCTOS YA QUE ESTOS SE FORMAN POR CATEGORIAS
DROP TABLE IF EXISTS `paquetes_productos`;

-- AGREGO CAMPOS DE FECHA DE ENTREGA E INSTALACION
ALTER TABLE cotizaciones ADD entrega_fecha_tentativa DATETIME NULL AFTER entrega_numero_interior;
ALTER TABLE cotizaciones ADD entrega_fecha_instalacion DATETIME NULL AFTER entrega_fecha_tentativa;

-- AMPLIO CAMPO DE DESCRIPCION EN PAQUETES
ALTER TABLE paquetes CHANGE  descripcion descripcion TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

-- AGREGO CAMPO DE TIPO DE PERSONA PARA FACTURACION EN CUENTAS
ALTER TABLE cuentas ADD tipo_persona_id TINYINT(1) NOT NULL;

-- CAMPOS DE DIRECCIÓN PARA LA SUCURSAL EN CUENTAS
ALTER TABLE cuentas
ADD `sucursal_estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `sucursal_municipio` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `sucursal_asentamiento` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `sucursal_codigo_postal` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `sucursal_calle` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `sucursal_numero_exterior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `sucursal_numero_interior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;

-- CAMPOS PARA CAPTURAR DIRECCIÓN DE INSTALACIÓN
ALTER TABLE cotizaciones
ADD `instalacion_nombre_contacto` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_telefono_particular` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_telefono_celular` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_municipio` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_asentamiento` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_codigo_postal` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_calle` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_numero_exterior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
ADD `instalacion_numero_interior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;

-- PARA USUARIOS MULTICUENTA
DROP TABLE IF EXISTS `usuarios_cuentas`;
CREATE TABLE `usuarios_cuentas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuarios_id` int(11) DEFAULT NULL,
  `cuentas_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- CAMBIO TIPO DE DATO PARA QUE MANTENGA DOS DECIMALES EL PRECIO DE LOS PRODUCTOS
ALTER TABLE productos CHANGE precio precio DECIMAL(13,2) NOT NULL;

-- AGREGO CAMPO PARA NOMBRE DE VENDEDOR COMO CAMPO ABIERTO
ALTER TABLE cotizaciones ADD nombre_vendedor VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

-- PARA PODER COLOCAR EL NOMBRE DEL VENDEDOR MANUAL
ALTER TABLE referidos ADD vendedor VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER vendedores_id;
ALTER TABLE cotizaciones CHANGE referido_vendedor_id  referido_vendedor VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

-- AMPLIO CAMPO DE clave EN TABLA DE ELEMTNOS YA QUE CORTA LA CLAVE DEL ESTADO Y NO VALIDA CORRECTAMENTE
ALTER TABLE elementos CHANGE clave clave VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

UPDATE `elementos` SET clave='DISTRITO FEDERAL' WHERE id=18;
UPDATE `elementos` SET clave='BAJA CALIFORNIA SUR' WHERE id=25;
UPDATE `elementos` SET clave='QUINTANA ROO' WHERE id=28;

ALTER TABLE cotizaciones CHANGE codigo_postal codigo_postal VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE cotizaciones CHANGE entrega_codigo_postal entrega_codigo_postal VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE cotizaciones CHANGE instalacion_codigo_postal instalacion_codigo_postal VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

-- PERMISOPARA VER COLUMNA DE REFERENCIAS
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '7',  'cotizaciones_ver_referencia',  'Permite ver si una cotización está referenciada o no.',  '1');

-- REESTRUCTUTA TABLAS DE COTIZACIONES, COTIZACIONES PRODUCTOS, COTIZACIONES ACCESORIOS
DROP TABLE IF EXISTS `cotizaciones`;
CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folio` varchar(10) DEFAULT NULL,
  `folio_cuentas` varchar(13) DEFAULT '',
  `folio_compra` varchar(50) DEFAULT NULL,
  `cuentas_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT '1',
  `importe_total` double DEFAULT NULL,
  `descuento_cliente` double DEFAULT NULL,
  `descuento_distribuidor` double DEFAULT NULL,
  `descuento_paquete_distribuidor` double DEFAULT NULL,
  `subtotal_cliente` double DEFAULT NULL,
  `subtotal_distribuidor` double DEFAULT NULL,
  `envio` double DEFAULT NULL,
  `iva_cliente` double DEFAULT NULL,
  `iva_distribuidor` double DEFAULT NULL,
  `total_cliente` double DEFAULT NULL,
  `total_distribuidor` double DEFAULT NULL,
  `referido_distribuidor_id` int(11) DEFAULT NULL,
  `referido_vendedor` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `referido_porcentaje_comision` tinyint(1) DEFAULT '0',
  `descuento_opcional` tinyint(1) DEFAULT '0',
  `descuento_paquete` tinyint(1) DEFAULT '0',
  `nombre_vendedor` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date DEFAULT NULL,
  `condiciones_pago_id` int(11) DEFAULT NULL,
  `forma_pago_id` int(11) DEFAULT NULL,
  `razon_social` varchar(250) DEFAULT NULL,
  `nombre` varchar(25) DEFAULT NULL,
  `apellido_paterno` varchar(25) DEFAULT NULL,
  `apellido_materno` varchar(25) DEFAULT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `tipo_persona_id` int(1) DEFAULT NULL,
  `estado` varchar(30) DEFAULT NULL,
  `municipio` varchar(30) DEFAULT NULL,
  `codigo_postal` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `asentamiento` varchar(30) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero_exterior` int(11) DEFAULT NULL,
  `numero_interior` int(11) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `nombre_comprador` varchar(20) DEFAULT '',
  `paterno_comprador` varchar(20) DEFAULT '',
  `materno_comprador` varchar(20) DEFAULT '',
  `email_comprador` varchar(40) DEFAULT '',
  `telefono_comprador` varchar(15) DEFAULT '',
  `fecha_nacimiento_comprador` varchar(5) DEFAULT NULL,
  `fecha_aniversario_comprador` varchar(5) DEFAULT NULL,
  `anio_nacimiento_comprador` int(4) DEFAULT NULL,
  `nombre_contacto` varchar(100) DEFAULT NULL,
  `telefono_particular` varchar(30) DEFAULT NULL,
  `telefono_celular` varchar(30) DEFAULT NULL,
  `entrega_estado` varchar(50) DEFAULT NULL,
  `entrega_asentamiento` varchar(50) DEFAULT NULL,
  `entrega_municipio` varchar(50) DEFAULT NULL,
  `entrega_codigo_postal` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `entrega_calle` varchar(50) DEFAULT NULL,
  `entrega_numero_exterior` varchar(20) DEFAULT NULL,
  `entrega_numero_interior` int(11) DEFAULT NULL,
  `entrega_fecha_tentativa` datetime DEFAULT NULL,
  `entrega_fecha_instalacion` datetime DEFAULT NULL,
  `instalacion_nombre_contacto` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_telefono_particular` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_telefono_celular` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_municipio` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_asentamiento` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_codigo_postal` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_calle` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_numero_exterior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instalacion_numero_interior` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `observaciones` text,
  `fecha_compra` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `eliminado` int(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cotizaciones_productos`;
CREATE TABLE `cotizaciones_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(9,2) DEFAULT '0.00',
  `descuento_cliente` double DEFAULT NULL,
  `descuento_distribuidor` double DEFAULT NULL,
  `importe_cliente` double DEFAULT NULL,
  `importe_distribuidor` double DEFAULT NULL,
  `observaciones` text,
  `timestamp` varchar(10) NOT NULL,
  `eliminado` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cotizaciones_accesorios`;
CREATE TABLE `cotizaciones_accesorios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) DEFAULT NULL,
  `productos_id` int(11) DEFAULT NULL,
  `accesorios_id` int(11) DEFAULT NULL,
  `tipos_accesorios_id` int(11) DEFAULT NULL,
  `cotizaciones_productos_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(9,2) DEFAULT '0.00',
  `descuento_cliente` double DEFAULT NULL,
  `descuento_distribuidor` double DEFAULT NULL,
  `importe_cliente` double DEFAULT NULL,
  `importe_distribuidor` double DEFAULT NULL,
  `timestamp` varchar(10) NOT NULL,
  `eliminado` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- 2014/04/10
-- AGREGO CAMPOS AL MÓDULO DE REFERENCIADOS
ALTER TABLE referidos CHANGE vendedor vendedor_nombre VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE referidos ADD vendedor_paterno VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER vendedor_nombre;
ALTER TABLE referidos ADD vendedor_materno VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER vendedor_paterno;

ALTER TABLE referidos ADD instalacion_estado VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE referidos ADD instalacion_municipio VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE referidos ADD instalacion_asentamiento VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE referidos ADD instalacion_calle VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE referidos ADD instalacion_numero_exterior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE referidos ADD instalacion_numero_interior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE referidos ADD instalacion_codigo_postal VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;

-- AJUSTO NOMBRE DEL VENDEDOR EN COTIZACIONES SEPARADO NOMBRE Y APELLIDOS
ALTER TABLE cotizaciones CHANGE nombre_vendedor vendedor_nombre VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE cotizaciones ADD vendedor_paterno VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER vendedor_nombre;
ALTER TABLE cotizaciones ADD vendedor_materno VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER vendedor_paterno;

ALTER TABLE cotizaciones CHANGE referido_vendedor referido_vendedor_nombre VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE cotizaciones ADD referido_vendedor_paterno VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER referido_vendedor_nombre;
ALTER TABLE cotizaciones ADD referido_vendedor_materno VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER referido_vendedor_paterno;

-- 2014/04/14
-- INCREMENTO LONGITUD DE FOLIO YA QUE LO ESTABA CORTANDO
ALTER TABLE cotizaciones CHANGE folio_cuentas folio_cuentas VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

-- 2014/06/26
-- AJUSTES CLOUDFILES
ALTER TABLE  `cuentas` ADD  `distribuidor_logo_orden` INT NOT NULL DEFAULT  '0';
ALTER TABLE  `productos_categorias` ADD  `imagen_orden` INT NOT NULL DEFAULT  '0';
ALTER TABLE  `accesorios` ADD  `imagen_orden` INT NOT NULL DEFAULT  '0';
ALTER TABLE  `tipos_accesorios` ADD  `imagen_orden` INT NOT NULL DEFAULT  '0';
ALTER TABLE  `banners` ADD  `imagen_orden` INT NOT NULL DEFAULT  '0';
ALTER TABLE  `paquetes` ADD  `imagen_orden` INT NOT NULL DEFAULT  '0';
ALTER TABLE cotizaciones ADD recibo_pago_cdn VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD orden_firmada_cdn VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD recibo_pago_orden INT NULL DEFAULT  '0';
ALTER TABLE cotizaciones ADD orden_firmada_orden INT NULL DEFAULT  '0';


-- 2014/07/10
-- AJUSTES MIELE SHOP
-- AGREGO CAMPOS AL USUARIO PARA GUARDAR LA DIRECCION DE FACTURACION Y EL ROL QUE IDENTIFIQUE SI ES CLIENTE EXTERNO
ALTER TABLE  `usuarios` ADD  `cliente_externo` TINYINT(1) NULL DEFAULT NULL;

-- DIRECCION DE FACTURACION DEL USUARIO
ALTER TABLE usuarios ADD tipo_persona_id INT(1) NULL DEFAULT NULL;
ALTER TABLE usuarios ADD rfc VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD razon_social VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD estado VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD municipio VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD asentamiento VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD calle VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD numero_exterior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD numero_interior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD codigo_postal VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;

-- DIRECCION DE ENTREGA DEL USUARIO
ALTER TABLE usuarios ADD entrega_estado VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD entrega_municipio VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD entrega_asentamiento VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD entrega_calle VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD entrega_numero_exterior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD entrega_numero_interior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD entrega_codigo_postal VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;


-- DIRECCION DE INSTALACION DEL USUARIO
ALTER TABLE usuarios ADD instalacion_estado VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_municipio VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_asentamiento VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_calle VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_numero_exterior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_numero_interior VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_codigo_postal VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_nombre_contacto VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_telefono_particular VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE usuarios ADD instalacion_telefono_celular VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;

-- 2014/07/21
-- AGREGO EL CAMPO DE TERMINOS Y CONDICIONES YA QUE NO SE ESTA GUARDANDO.
ALTER TABLE cotizaciones ADD `acepta_terminos` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD `datos_correctos` TINYINT(1) NULL DEFAULT NULL;
-- ALTER TABLE cotizaciones ADD `terminos_condiciones` TINYINT(1) NULL DEFAULT NULL;

-- TABLA PARA REGISTRAR PAGOS DE VENTA EN LÍNEA
DROP TABLE IF EXISTS `cotizaciones_pagos`;
CREATE TABLE `cotizaciones_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) DEFAULT NULL,
  `vpc_MerchTxnRef` varchar(40) NOT NULL,
  `vpc_Merchant` varchar(16) NOT NULL,
  `vpc_OrderInfo` varchar(34) DEFAULT NULL,
  `vpc_Amount` int(12) NOT NULL,
  `vpc_Currency` varchar(3) NOT NULL DEFAULT 'MNX',
  `vpc_Locale` varchar(5) NOT NULL DEFAULT 'es',
  `vpc_Version` varchar(8) NOT NULL DEFAULT '1',
  `vpc_Command` varchar(16) NOT NULL DEFAULT 'pay',
  `vpc_Message` varchar(255) DEFAULT NULL,
  `vpc_TxnResponseCode` varchar(1) DEFAULT NULL,
  `vpc_ReceiptNo` varchar(12) DEFAULT NULL,
  `vpc_AcpResponseCode` varchar(3) DEFAULT NULL,
  `vpc_TransactionNo` BIGINT(19) DEFAULT NULL,
  `vpc_BatchNo` INT(8) DEFAULT NULL,
  `vpc_AuthorizedId` varchar(6) DEFAULT NULL,
  `vpc_Card` varchar(2) DEFAULT NULL,
  `eliminado` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

INSERT INTO `grupos` (`id`, `nombre`, `activo`)
VALUES
  (13, 'EXTERNOS', 1);

INSERT INTO `cuentas` (`id`, `nombre`, `consecutivo`, `consecutivo_compra`, `razon_social`, `rfc`, `codigo_postal`, `asentamiento`, `municipio`, `estado`, `pais`, `calle`, `numero_exterior`, `numero_interior`, `telefono`, `email`, `activo`, `clave`, `cuenta_clabe`, `cuenta_bancaria`, `sucursal`, `sucursal_fisica`, `credito`, `distribuidor`, `venta_directa`, `descuento_transicion`, `descuento_cooperacion`, `descuento_monto`, `descuento_espacio`, `eliminado`, `created`, `created_by`, `modified`, `modified_by`, `tipo_persona_id`, `sucursal_estado`, `sucursal_municipio`, `sucursal_asentamiento`, `sucursal_codigo_postal`, `sucursal_calle`, `sucursal_numero_exterior`, `sucursal_numero_interior`, `distribuidor_logo_orden`)
VALUES
  (NULL, 'Externos', 0, 0, 'Ventas al Público', 'XXXXXXXXXXX', '54449', 'SAN JOSE EL VIDRIO', 'NICOLAS ROMERO', 'MEXICO', 'MEXICO', 'Morelos', '1', NULL, '+(52) 55 5019 5041', 'fabian@blackcore.com.mx', 1, 'BLACK00001', '1111111111', '11111', 'BANAMEX', NULL, 0, 1, 0, 0.00, 0.00, 0.00, 0.00, 0, '2011-08-15 21:09:51', 1, '2014-05-16 10:15:09', 2, 1, 'MEXICO', 'NAUCALPAN DE JUAREZ', 'LOMAS VERDES 3A SECCION', '53125', 'MORELOS', '10', NULL, 0);

ALTER TABLE cotizaciones ADD successIndicator VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL;

-- 2014-08-14
ALTER TABLE cotizaciones ADD pago_realizado int(4) DEFAULT NULL;

ALTER TABLE usuarios CHANGE contrasena contrasena VARCHAR(40) NULL DEFAULT NULL;

-- Campos para respuesta de banamex
ALTER TABLE cotizaciones_pagos ADD vpc_3DSXID varchar(30) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_3DSenrolled varchar(1) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_VerStatus varchar(1) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_AVSRequestCode varchar(100) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_AcqCSCRespCode varchar(100) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_CSCResultCode varchar(100) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_VerSecurityLevel varchar(20) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_VerType varchar(20) DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_CardNum varchar(20) DEFAULT NULL;

-- 2014-08-18
-- Banco en que se realizo el pago
ALTER TABLE cotizaciones ADD bancos_id int(11) DEFAULT NULL;

INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '7',  'cotizaciones_externos_filtrar',  'Permite filtrar por status las cotizaciones de usuarios externos.',  '1'),
  (NULL ,  '7',  'cotizaciones_validar_pago',  'Permite validar manualmente el pago de una cotización de un usuario externo.',  '1');

-- 2014-09-01
-- Guardar el numero de mensualidades
ALTER TABLE cotizaciones ADD mensualidades int(11) DEFAULT NULL;

-- 2014-09-02
-- Comentarios de seccion de contactenos
CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido_paterno` varchar(100) DEFAULT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `celular` varchar(25) DEFAULT NULL,
  `comentario` text,
  `eliminado` int(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 2014-09-02
-- Permisos para los comentarios dejados por clientes
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '1',  'comentarios',  'Permite administrar los comentarios enviados por los clientes.',  '1'),
  (NULL ,  '1',  'comentarios_eliminar',  'Permite eliminar los comentarios enviados por los clientes.',  '1');

-- 2014-09-03
-- Campo para descripcion de categorias
ALTER TABLE productos_categorias ADD COLUMN informacion_general text DEFAULT NULL AFTER descripcion ;

-- 2014-09-03
ALTER TABLE usuarios CHANGE contrasena contrasena varchar(40) DEFAULT NULL;

-- 2014-09-18
-- AJUSTE PARA GUIAS MECANICAS
ALTER TABLE productos ADD guia_mecanica_orden INT(5) DEFAULT NULL;
ALTER TABLE productos ADD guia_mecanica_extension varchar(5) DEFAULT NULL;

-- 2014-10-01
-- CAMPO PARA CAPTURAR DATO DE IBS
ALTER TABLE cotizaciones ADD ibs INT(11) NULL DEFAULT NULL;
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  (SELECT id FROM categorias WHERE nombre='cotizaciones'),  'cotizaciones_agregar_ibs',  'Permite capturar el IBS de una cotización.',  '1');

-- 2014-10-05
-- AJUSTE PARA MANUALES
ALTER TABLE productos ADD manual_orden INT(5) DEFAULT NULL;
ALTER TABLE productos ADD manual_extension varchar(5) DEFAULT NULL;

-- 2014-10-16
-- PERMISO PARA ENVIAR POR EMAIL LA COTIZACION
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '7',  'cotizaciones_enviar_email',  'Permite enviar las cotizaciones por email a los clientes.',  '1');

-- CAMBIO TIPO DE DATO PARA QUE MANTENGA DOS DECIMALES EL PRECIO DE LOS PRODUCTOS
ALTER TABLE accesorios CHANGE precio precio DECIMAL(13,2) NOT NULL;

-- FUNCION PARA PODER EXPORTAR PRODUCTOS A EXCEL
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '3',  'productos_exportar',  'Permite exportar el listado de productos a Excel.',  '1');ALTER TABLE productos ADD manual_extension varchar(5) DEFAULT NULL;

-- 2014-11-02
-- AJUSTE PARA GUIAS MECANICAS Y MANUALES EN ACCESORIOS
ALTER TABLE accesorios ADD guia_mecanica_orden INT(5) DEFAULT NULL;
ALTER TABLE accesorios ADD guia_mecanica_extension varchar(5) DEFAULT NULL;
ALTER TABLE accesorios ADD manual_orden INT(5) DEFAULT NULL;
ALTER TABLE accesorios ADD manual_extension varchar(5) DEFAULT NULL;

-- 2014-11-06
ALTER TABLE cotizaciones_pagos CHANGE vpc_AcpResponseCode vpc_AcqResponseCode VARCHAR(3) NULL DEFAULT NULL;
ALTER TABLE cotizaciones_pagos CHANGE vpc_AuthorizedId vpc_AuthorizeId VARCHAR(6) NULL DEFAULT NULL;

-- CAMPOS FALTANTES DE PAGOS BANAMEX
ALTER TABLE cotizaciones_pagos ADD vpc_3DSECI VARCHAR(5) NULL DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_3DSstatus VARCHAR(5) NULL DEFAULT NULL;
ALTER TABLE cotizaciones_pagos ADD vpc_VerToken VARCHAR(30) NULL DEFAULT NULL;

-- PARA TRATAR TODAS LAS VENTAS DE MIELE SHOP COMO VENTA DIRECTA
UPDATE cuentas SET distribuidor=0, venta_directa=1 where nombre='Externos';
UPDATE productos_categorias SET activo=0 WHERE nombre='Accesorios';
UPDATE productos SET activo=0 WHERE nombre='Accesorios';

-- CAMPO PARA QUE EL CLIENTE ACEPTE LA DISPONIBILIDAD EN INVENTARIO
ALTER TABLE cotizaciones ADD acepta_disponibilidad TINYINT(1) NULL DEFAULT NULL;

-- 2014-10-16
-- PERMISO PARA ENVIAR POR EMAIL LA COTIZACION
-- @autor Fabian Quiroz
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
  (NULL ,  '7',  'cotizaciones_descargar_archivos',  'Permite descargar los archivos de una cotización (Recibo de Pago, Orden Firmada).',  '1');

-- 2015-01-08
-- Permiso para agregar, editar, activar o desactivar cupones.
-- @autor Roger Morales
-- agregar categoria Cupones
INSERT INTO `categorias` (`id` ,`nombre` ,`activo`) VALUES (null,  'Cupones',  '1');

INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Cupones'), 'cupones', 'Administrar cupones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Cupones'), 'cupones_agregar', 'Permite agregar un cupón.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Cupones'), 'cupones_editar', 'Permite editar un cupón.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Cupones'), 'cupones_eliminar', 'Permite eliminar un cupón.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Cupones'), 'cupones_activar', 'Permite activar/desactivar un cupón.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Cupones'), 'cupones_exportar', 'Permite exportar los folios de un cupón.', '1');

-- Creación de la tabla cupones
CREATE TABLE `cupones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alianza_id` int(11) NOT NULL,
  `vigencia_desde` datetime NOT NULL,
  `vigencia_hasta` datetime NOT NULL,
  `porcentaje_descuento` decimal(5,2) NOT NULL,
  `meses_sin_intereses` int(3) NULL,
  `numero_folios` int(11) NOT NULL,
  `activo` int(1) NOT NULL DEFAULT '1',
  `eliminado` int(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Creación de la tabla cupones_folios
CREATE TABLE IF NOT EXISTS `cupones_folios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folio` varchar(50) DEFAULT NULL,
  `cupones_id` int(11) NOT NULL,
  `alianza_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- Creación de la tabla cupones_categorias
CREATE TABLE IF NOT EXISTS `cupones_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cupones_id` int(11) NOT NULL,
  `productos_categorias_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- Creación de la tabla cupon_productos
CREATE TABLE IF NOT EXISTS `cupones_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cupones_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- Creación de la tabla cupon_accesorios
CREATE TABLE IF NOT EXISTS `cupones_accesorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cupones_id` int(11) NOT NULL,
  `accesorios_id` int(11) NOT NULL,
  `consumible` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- Columna para diferencias accesorios de consumibles
ALTER TABLE `accesorios` ADD `consumible` int(1) NOT NULL DEFAULT 0 AFTER `descripcion`;

INSERT INTO `catalogos` (`id`, `nombre`, `descripcion`)
VALUES
  (NULL, 'alianzas', 'Compañias que se unen a nosotros en promociones.');

INSERT INTO `elementos` (`id`, `catalogos_id`, `clave`, `valor`, `activo`)
VALUES
  (NULL, (SELECT id FROM catalogos WHERE nombre = 'alianzas'), '1', 'MIELE', 1),
  (NULL, (SELECT id FROM catalogos WHERE nombre = 'alianzas'), '2', 'AUDI', 1);

-- 2015-01-12
-- Permiso para agregar, editar, activar o desactivar gastos de envío.
-- @autor Fabian Quiroz
INSERT INTO `categorias` (`id` ,`nombre` ,`activo`) VALUES (null,  'Gastos de Envío',  '1');

INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Gastos de Envío'), 'gastos_envio', 'Administrar gastos de envío.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Gastos de Envío'), 'gastos_envio_agregar', 'Permite agregar gastos de envío.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Gastos de Envío'), 'gastos_envio_editar', 'Permite editar gastos de envío.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Gastos de Envío'), 'gastos_envio_eliminar', 'Permite eliminar gastos de envío.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Gastos de Envío'), 'gastos_envio_activar', 'Permite activar/desactivar gastos de envío.', '1');

CREATE TABLE gastos_envios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  estado VARCHAR(50) NOT NULL,
  productos_porcentaje DOUBLE(5,2) NULL DEFAULT 0,
  productos_monto_fijo DOUBLE(8,2) NULL DEFAULT 0,
  productos_numero INT(2) NULL DEFAULT NULL,
  accesorios_porcentaje DOUBLE(5,2) NULL DEFAULT 0,
  accesorios_monto_fijo DOUBLE(8,2) NULL DEFAULT 0,
  accesorios_numero INT(2) NULL DEFAULT NULL,
  consumibles_porcentaje DOUBLE(5,2) NULL DEFAULT 0,
  consumibles_monto_fijo DOUBLE(8,2) NULL DEFAULT 0,
  consumibles_numero INT(2) NULL DEFAULT NULL,
  activo int(1) NOT NULL DEFAULT '1',
  eliminado int(1) NOT NULL DEFAULT '1',
  created datetime NOT NULL,
  created_by int(11) NOT NULL,
  modified datetime DEFAULT NULL,
  modified_by int(11) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE gastos_envios ADD consumibles_monto_minimo_porcentaje DOUBLE(8,2) NULL DEFAULT 0 AFTER consumibles_numero;

-- 2015-01-12
-- AJUSTE PARA MANUALES
ALTER TABLE productos ADD autocad_orden INT(5) DEFAULT NULL;
ALTER TABLE productos ADD autocad_extension varchar(5) DEFAULT NULL;

-- 2015-01-13
-- Permiso para agregar, editar, activar o desactivar gastos de envío.
-- @autor Fabian Quiroz
ALTER TABLE productos ADD sin_envio TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE cotizaciones_productos ADD sin_envio TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE cotizaciones_accesorios ADD consumible TINYINT(1) NULL DEFAULT 0;

-- 2015-01-13
-- Cambio en la descripción del catalago alianzas.
-- @autor Roger Morales
UPDATE catalogos SET descripcion='Alianzas' WHERE nombre='alianzas';

DELETE FROM funciones WHERE funcion='cupones_eliminar';

DELETE FROM funciones where funcion IN('gastos_envio_activar','gastos_envio_eliminar','gastos_envio_agregar');

-- 2015-01-19
-- Campos para guardar registrod e cupones en la cotización.
-- @autor Fabian Quiroz
ALTER TABLE cotizaciones ADD cupones_id INT(11) NULL DEFAULT NULL, ADD folio_cupon VARCHAR(50) NULL DEFAULT NULL, ADD opcion_cupon_id TINYINT(1) NULL DEFAULT NULL,
ADD descuento_porcentaje_cupon DECIMAL(5,2) NULL DEFAULT NULL, ADD msi_cupon INT(3) NULL DEFAULT NULL, ADD descuento_cupon TINYINT(1) NULL DEFAULT NULL;

-- CAMPO PARA CONSIDERAR DESCUENTO A DISTRIBUIDORES POR CUPONES
ALTER TABLE cupones ADD descuento_distribuidor DECIMAL(5,2) NULL DEFAULT NULL AFTER porcentaje_descuento;
ALTER TABLE cupones_folios ADD usado TINYINT(1) NULL DEFAULT NULL;

ALTER TABLE cotizaciones ADD descuento_cliente_cupon DECIMAL(10,2) NULL DEFAULT NULL AFTER descuento_cupon;
ALTER TABLE cotizaciones ADD descuento_distribuidor_cupon DECIMAL(10,2) NULL DEFAULT NULL AFTER descuento_cliente_cupon;

ALTER TABLE cotizaciones CHANGE asentamiento asentamiento VARCHAR(100) NULL DEFAULT NULL;
ALTER TABLE cotizaciones CHANGE entrega_asentamiento entrega_asentamiento VARCHAR(100) NULL DEFAULT NULL;
ALTER TABLE cotizaciones CHANGE instalacion_asentamiento instalacion_asentamiento VARCHAR(100) NULL DEFAULT NULL;

-- 2015-02-24
-- Campos para aplicar descuentos en accesorios individuales desde TIPOS DE ACCESORIOS
-- @autor Fabian Quiroz
ALTER TABLE tipos_accesorios ADD descuento_base DECIMAL(10,2) NULL DEFAULT NULL AFTER descripcion;
ALTER TABLE tipos_accesorios ADD descuento_opcional DECIMAL(10,2) NULL DEFAULT NULL AFTER descuento_base;

-- 2015-03-02
-- TABLA PARA GUARDAR LAS CUENTAS QUE PARTICIPAN EN LOS CUPONES
-- @autor Fabian Quiroz
CREATE TABLE IF NOT EXISTS `cupones_cuentas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cupones_id` int(11) NOT NULL,
  `cuentas_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- 2015-03-16
-- CAMPO PARA CAPTURAR LA URL DE LOS BANNERS
-- @autor Fabian Quiroz
ALTER TABLE banners ADD url VARCHAR(255) NULL DEFAULT NULL;

-- 2015-05-04
-- AGREGO CAMPOS PARA REGISTRO DE PRODUCTOS EN COTIZACIONES
ALTER TABLE cotizaciones_productos ADD created DATETIME DEFAULT NULL;
ALTER TABLE cotizaciones_productos ADD created_by INT DEFAULT NULL;
ALTER TABLE cotizaciones_productos ADD modified DATETIME DEFAULT NULL;
ALTER TABLE cotizaciones_productos ADD modified_by INT DEFAULT NULL;

-- 2015-05-04
-- AGREGO CAMPOS PARA REGISTRAR LA MENSUALIDAD DE CUPONES EN CASO DE SER A MESES SIN INTERESES
-- @autor Fabian Quiroz
ALTER TABLE cotizaciones ADD mensualidad_cliente_cupon DECIMAL(10,2) NULL DEFAULT NULL AFTER msi_cupon;
ALTER TABLE cotizaciones ADD mensualidad_distribuidor_cupon DECIMAL(10,2) NULL DEFAULT NULL AFTER mensualidad_cliente_cupon;

-- 2015-05-04
-- QUERYS PARA ARREGLAR EL TEMA DE PRODUCTOS Y ACCESORIOS REPETIDOS

-- SELECT cotizaciones_id, productos_id, count(cotizaciones_id), eliminado
-- FROM cotizaciones_productos
-- WHERE eliminado=0
-- GROUP BY cotizaciones_id, productos_id, eliminado
-- ORDER BY 3 DESC;
--
-- UPDATE cotizaciones_productos
-- SET eliminado=1
-- WHERE id not in (
-- SELECT V.id
-- FROM
-- (
-- 	SELECT A.id
-- 	FROM
-- 	(SELECT id, cotizaciones_id, productos_id, eliminado
-- 	FROM cotizaciones_productos
-- 	WHERE eliminado=0
-- 	ORDER BY id DESC) as A
-- 	GROUP BY cotizaciones_id, productos_id
-- ) V
-- )
--
-- SELECT cotizaciones_id, count(cotizaciones_id), productos_id, accesorios_id, eliminado, cantidad
-- FROM cotizaciones_accesorios
-- WHERE eliminado=0
-- GROUP BY cotizaciones_id, productos_id, accesorios_id, eliminado, cantidad
-- ORDER BY 2 DESC;
--
-- UPDATE cotizaciones_accesorios
-- SET eliminado=1
-- WHERE id not in (
-- SELECT V.id
-- FROM
-- (
-- 	SELECT A.id
-- 	FROM
-- 	(SELECT id, cotizaciones_id, productos_id, accesorios_id, eliminado
-- 	FROM cotizaciones_accesorios
-- 	WHERE eliminado=0
-- 	ORDER BY id DESC) as A
-- 	GROUP BY cotizaciones_id, productos_id, accesorios_id
-- ) V
-- );


-- 2015-08-27
-- Permisos para agregar, editar, activar o desactivar promociones.
-- @autor Roger Morales
-- agregar categoria Cupones
INSERT INTO `categorias` (`id` ,`nombre` ,`activo`) VALUES (null,  'Promociones',  '1');

INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Promociones'), 'promociones', 'Administrar promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Promociones'), 'promociones_agregar', 'Permite agregar una promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Promociones'), 'promociones_editar', 'Permite editar una promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Promociones'), 'promociones_eliminar', 'Permite eliminar una promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Promociones'), 'promociones_activar', 'Permite activar/desactivar una promociones.', '1');

-- Create syntax for TABLE 'promociones'
CREATE TABLE `promociones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) DEFAULT NULL,
  `alianza_id` int(11) DEFAULT NULL,
  `vigencia_desde` datetime NOT NULL,
  `vigencia_hasta` datetime NOT NULL,
  `porcentaje_descuento` decimal(5,2) DEFAULT NULL,
  `monto_descuento` decimal(9,2) DEFAULT NULL,
  `meses_sin_intereses` int(3) DEFAULT NULL,
  `activo` int(1) NOT NULL DEFAULT '1',
  `eliminado` int(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'promociones_productos'
CREATE TABLE `promociones_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promociones_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'promociones_accesorios'
CREATE TABLE `promociones_accesorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promociones_id` int(11) NOT NULL,
  `accesorios_id` int(11) NOT NULL,
  `consumible` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'promociones_categorias'
CREATE TABLE `promociones_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promociones_id` int(11) NOT NULL,
  `productos_categorias_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'promociones_productos_regalo'
CREATE TABLE `promociones_productos_regalo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promociones_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'promociones_accesorios_regalo'
CREATE TABLE `promociones_accesorios_regalo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promociones_id` int(11) NOT NULL,
  `accesorios_id` int(11) NOT NULL,
  `consumible` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'promociones_categorias_regalo'
CREATE TABLE `promociones_categorias_regalo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promociones_id` int(11) NOT NULL,
  `productos_categorias_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'promociones_alianzas_regalo'
CREATE TABLE `promociones_alianzas_regalo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promociones_id` int(11) NOT NULL,
  `alianzas_id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 2015-09-02
-- Catalogo para las alianzas de promociones
-- @autor Roger Morales
-- agregar catalogo
INSERT INTO `catalogos` (`id`, `nombre`, `descripcion`)
VALUES
  (NULL, 'promociones_alianzas', 'Alianzas para promociones.');

INSERT INTO `elementos` (`id`, `catalogos_id`, `clave`, `valor`, `activo`)
VALUES
  (NULL, (SELECT id FROM catalogos WHERE nombre='promociones_alianzas'), '1', 'MIELE', 1),
  (NULL, (SELECT id FROM catalogos WHERE nombre='promociones_alianzas'), '2', 'AUDI', 1),
  (NULL, (SELECT id FROM catalogos WHERE nombre='promociones_alianzas'), '3', 'SANTANDER BANKING', 1);

ALTER TABLE promociones ADD COLUMN monto_minimo decimal(10,2) DEFAULT NULL AFTER monto_descuento ;

-- 2015-09-06
-- AJUSTE PARA PROMOCIONES
-- @autor Fabián Quiroz
ALTER TABLE cotizaciones ADD promocion TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD promocion_msi TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD promocion_msi_cliente DOUBLE NULL DEFAULT NULL AFTER promocion_msi;
ALTER TABLE cotizaciones ADD promocion_msi_distribuidor DOUBLE NULL DEFAULT NULL AFTER promocion_msi_cliente;
ALTER TABLE cotizaciones ADD promocion_fija DOUBLE NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD promocion_porcentaje DOUBLE NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD promocion_porcentaje_monto DOUBLE NULL DEFAULT NULL;
ALTER TABLE cotizaciones_productos ADD promocion TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE cotizaciones_accesorios ADD promocion TINYINT(1) NULL DEFAULT NULL;

CREATE TABLE `cotizaciones_alianzas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) NOT NULL,
  `alianzas_id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `eliminado` TINYINT(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE cotizaciones_alianzas ADD promociones_id INT(11) NOT NULL AFTER cotizaciones_id;

ALTER TABLE promociones_categorias ADD cantidad INT(5) NULL DEFAULT NULL;
ALTER TABLE promociones_categorias_regalo ADD cantidad INT(5) NULL DEFAULT NULL;

ALTER TABLE promociones_productos ADD cantidad INT(5) NULL DEFAULT NULL;
ALTER TABLE promociones_productos_regalo ADD cantidad INT(5) NULL DEFAULT NULL;

ALTER TABLE promociones_accesorios ADD cantidad INT(5) NULL DEFAULT NULL;
ALTER TABLE promociones_accesorios_regalo ADD cantidad INT(5) NULL DEFAULT NULL;

-- 2015-09-14
-- @autor Roger Morales
-- cambio de nombre del catalogo
DROP TABLE IF EXISTS promociones_categorias_regalo;
ALTER TABLE promociones_alianzas_regalo DROP codigo;
ALTER TABLE promociones DROP alianza_id;
ALTER TABLE promociones ADD descripcion_alianza VARCHAR(250) NULL DEFAULT NULL AFTER nombre;

-- 2015-09-23
-- @autor Roger Morales
-- Se agregaron columnas nuevas a productos_categorias
ALTER TABLE productos_categorias ADD video_id TINYINT(1) NULL DEFAULT NULL AFTER foto_id;
ALTER TABLE productos_categorias ADD video_orden INT NOT NULL DEFAULT '0' AFTER imagen_orden;

-- 2015-10-01
-- Permisos para agregar, editar, activar o desactivar alianzas para promociones.
-- @autor Roger Morales
-- agregar categoria Alianzas promociones
INSERT INTO `categorias` (`id` ,`nombre` ,`activo`) VALUES (null,  'Alianzas promociones',  '1');

INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Alianzas promociones'), 'alianzas_promociones', 'Administrar alianzas para promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Alianzas promociones'), 'alianzas_promociones_agregar', 'Permite agregar una alianza para promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Alianzas promociones'), 'alianzas_promociones_editar', 'Permite editar una alianza para promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Alianzas promociones'), 'alianzas_promociones_eliminar', 'Permite eliminar una alianza para promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Alianzas promociones'), 'alianzas_promociones_activar', 'Permite activar/desactivar una alianza para promociones.', '1'),
  ((SELECT id FROM categorias WHERE nombre='Alianzas promociones'), 'alianzas_promociones_exportar', 'Permite activar/desactivar una alianza para promociones.', '1');

-- Creación de la tabla alianza_promociones
CREATE TABLE `alianzas_promociones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) NOT NULL,
  `numero_folios` int(11) NOT NULL,
  `activo` int(1) NOT NULL DEFAULT '1',
  `eliminado` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE cupones CHANGE `eliminado` `eliminado` int(1) NOT NULL DEFAULT '0';
ALTER TABLE promociones CHANGE `eliminado` `eliminado` int(1) NOT NULL DEFAULT '0';

CREATE TABLE `alianzas_folios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folio` varchar(50) DEFAULT NULL,
  `alianzas_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `usado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE alianzas_folios CHANGE `usado` `usado` INT(1) DEFAULT '0';
ALTER TABLE promociones DROP descripcion_alianza;
ALTER TABLE alianzas_promociones ADD `descripcion` VARCHAR(250) NULL DEFAULT NULL AFTER `numero_folios`;
ALTER TABLE promociones_alianzas_regalo ADD cantidad INT(5) NULL DEFAULT NULL;

ALTER TABLE cotizaciones_alianzas ADD nombre VARCHAR(100) NULL DEFAULT NULL AFTER alianzas_id;
ALTER TABLE cotizaciones_alianzas ADD descripcion VARCHAR(250) NULL DEFAULT NULL AFTER nombre;

ALTER TABLE cotizaciones ADD promocion_porcentaje_monto_distribuidor DOUBLE NULL DEFAULT NULL;
ALTER TABLE cotizaciones CHANGE promocion promociones_id INT(5) NULL DEFAULT NULL;

-- 2015-10-21
-- @autor Roger Morales
ALTER TABLE alianzas_promociones ADD `prefijo` VARCHAR(50) NULL DEFAULT NULL AFTER `nombre`;

-- 2015-11-03
-- @autor Fabian Quiroz
-- CAMPO NUEVO PARA PROMOCIONES - DESCUENTO DE CIERTO PORCENTAJE EN REGALOS DE PRODUCTOS
ALTER TABLE promociones_productos_regalo ADD porcentaje_regalo DECIMAL(5,2) NULL DEFAULT NULL;
ALTER TABLE cotizaciones ADD promocion_opcional_descuento DOUBLE NULL DEFAULT NULL;
ALTER TABLE promociones_accesorios_regalo ADD porcentaje_regalo DECIMAL(5,2) NULL DEFAULT NULL;

-- 2015-12-14
-- @Antolin Silva
-- tablas para el modulo de calificaciones
DROP TABLE IF EXISTS `calificaciones_email`;
CREATE TABLE IF NOT EXISTS `calificaciones_email` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) NOT NULL,
  `fecha_compra`  DATETIME DEFAULT NULL,
  `fecha_envio`  DATETIME DEFAULT NULL,
  `enviado` varchar(2) DEFAULT NULL,
  `concluido` varchar(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `calificaciones_productos`;
CREATE TABLE IF NOT EXISTS `calificaciones_productos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  `calificacion` varchar(2) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `calificado` varchar(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `calificaciones_accesorios`;
CREATE TABLE IF NOT EXISTS `calificaciones_accesorios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cotizaciones_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  `accesorios_id` int(11) NOT NULL,
  `calificacion` varchar(2) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `calificado` varchar(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2015-12-15
-- @Antolin Silva
-- Agregar la categoria de Reportes y permisos para el reporte de calificaciones.
INSERT INTO `categorias` (`id` ,`nombre` ,`activo`) VALUES (null,  'Reportes',  '1');

INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_calificaciones', 'Permite ver el menu reportes.', '1');
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_calificaciones_productos', 'Permite ver el reporte de la calificación de los productos.', '1');
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_calificaciones_accesorios', 'Permite ver el reporte de la calificación de los accesorios.', '1');
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_productos_exportar', 'Permite exportar el reporte de calificaciones de los productos.', '1');
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_accesorios_exportar', 'Permite exportar el reporte de calificaciones de los accesorios.', '1');

-- 2015-12-16
-- @Antolin Silva
-- Agregar columnas de modified;
ALTER TABLE calificaciones_email ADD COLUMN modified datetime DEFAULT NULL AFTER created_by;
ALTER TABLE calificaciones_accesorios ADD COLUMN modified datetime DEFAULT NULL AFTER created_by;
ALTER TABLE calificaciones_productos ADD COLUMN modified datetime DEFAULT NULL AFTER created_by;

-- 2015-12-16
-- @Antolin Silva
-- Agregar permisos del reporte general;
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_calificaciones_general', 'Permite ver el reporte general de las calificaciones.', '1');
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_general_exportar', 'Permite exportar el reporte de calificaciones general.', '1');

-- 2015-12-16
-- @Antolin Silva
-- Agregar como indece el campo productos_id en la tabla calificaciones_productos.
ALTER TABLE calificaciones_productos ADD INDEX `productos_id` ( `productos_id` );

-- 2015-12-16
-- @Antolin Silva
-- Agregar como indece el campo accesorios_id en la tabla calificaciones_accesorios.
ALTER TABLE calificaciones_accesorios ADD INDEX `accesorios_id` ( `accesorios_id` );

-- 2015-12-16
-- @Antolin Silva
-- Cambiar en el campo comentario el tipo de dato a TEXT.
ALTER TABLE calificaciones_productos CHANGE comentario comentario text;
ALTER TABLE calificaciones_accesorios CHANGE comentario comentario text;

-- 2015-12-27
-- @autor Fabian Quiroz
-- CAMPO NUEVO PARA REGISTRAR ENVIO A ALEMANIA DE GOOGLE ANALITYCS ECOMMERCE
ALTER TABLE cotizaciones ADD ecommerce_send TINYINT(1) NULL DEFAULT NULL;

-- 2016-01-04
-- @Author Antolin Silva
-- Agregar campo de eliminado en las calificaciones de productos y accesorios
ALTER TABLE calificaciones_productos ADD COLUMN eliminado INT(1) DEFAULT 0 NULL AFTER calificado;
ALTER TABLE calificaciones_accesorios ADD COLUMN eliminado INT(1) DEFAULT 0 NULL AFTER calificado;

-- 2016-01-04
-- @Author Antolin Silva
-- Permiso para eliminar comentarios ofensivos en los productos y accesorios
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Sin categoría'), 'eliminar_comentarios', 'Permite eliminar comentarios ofensivos de los productos.', '1');

-- 2016-01-07
-- @Author Antolin Silva
-- Cambiar nombre de columna eliminado por ocultar comentario
ALTER TABLE calificaciones_productos CHANGE eliminado ocultar_comentario INT(1) DEFAULT 0 NULL AFTER calificado;
ALTER TABLE calificaciones_accesorios CHANGE eliminado ocultar_comentario INT(1) DEFAULT 0 NULL AFTER calificado;

-- 2016-01-07
-- @Author Antolin Silva
-- Agregar indices a tablas de calificaciones_accesorios y calificaciones_productos
ALTER TABLE calificaciones_productos ADD INDEX `cotizaciones_id` ( `cotizaciones_id` );
ALTER TABLE calificaciones_accesorios ADD INDEX `cotizaciones_id` ( `cotizaciones_id` );

-- 2016-01-08
-- @Author Antolin Silva
-- Cambiar nombre de tabla calificaciones_productos a calificacion
RENAME TABLE calificaciones_productos TO calificaciones;

-- 2016-01-08
-- @Author Antolin Silva
-- Agregar columna de accesorios_id a la tabla de calificacion
ALTER TABLE calificaciones ADD COLUMN tabla VARCHAR(255) DEFAULT NULL AFTER productos_id;
ALTER TABLE calificaciones CHANGE productos_id elementos_id INT(1) DEFAULT 0 NULL AFTER cotizaciones_id;


-- 2016-01-08
-- @Author Rodrigo Soladana
-- elimino tabla de calificaciones accesorios
DROP TABLE IF EXISTS `calificaciones_accesorios`;
DELETE FROM funciones WHERE funcion='reporte_calificaciones_accesorios';
DELETE FROM funciones WHERE funcion='reporte_calificaciones_productos';
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_calificaciones_elementos', 'Permite consultar el reporte de las calificaciones.', '1');

-- 2016-01-11
-- @Author Antolin Silva
-- Eliminar permisos que ya no se usaron.
DELETE FROM funciones WHERE funcion='reporte_productos_exportar';
DELETE FROM funciones WHERE funcion='reporte_accesorios_exportar';

-- 2016-01-11
-- @Author Antolin Silva
-- Agregar permiso para exportar el reporte de elementos.
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Reportes'), 'reporte_elementos_exportar', 'Permite exportar en excel el reporte de las calificaciones de los elementos.', '1');

-- 2016-01-11
-- @Antolin Silva
-- Agregar como indece el campo elementos_id en la tabla calificaciones.
DROP INDEX productos_id ON calificaciones;
ALTER TABLE calificaciones ADD INDEX `elementos_id` ( `elementos_id` );

-- 2016-01-11
-- @Antolin Silva
-- Eliminar permiso de elimianr_comentario y agregar el permiso ocultar_comentario.
DELETE FROM funciones WHERE funcion='eliminar_comentarios';
INSERT INTO `funciones` (`categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  ((SELECT id FROM categorias WHERE nombre='Sin categoría'), 'ocultar_comentarios', 'Permite ocultar comentarios ofensivos de los productos.', '1');

-- 2016-01-22
-- @Author Antolin Silva
-- Columna para registrar el envio de mails para calificaciones.
ALTER TABLE calificaciones_email ADD COLUMN intentos INT(1) DEFAULT NULL AFTER concluido;

-- 2016-01-22
-- @autor Roger Morales
-- CUPONES POR EMAIL
DROP TABLE IF EXISTS `cupones_imagenes`;
CREATE TABLE IF NOT EXISTS `cupones_imagenes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cupones_id` INT(11) NOT NULL,
  `monto_inicial` DOUBLE NOT NULL,
  `monto_final` DOUBLE NOT NULL,
  `nombre_original` VARCHAR(200) DEFAULT NULL,
  `extension` VARCHAR(200) DEFAULT NULL,
  `created` DATETIME DEFAULT NULL,
  `created_by` INT(11) DEFAULT NULL,
  `eliminado` INT(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE cupones_folios ADD COLUMN cotizaciones_id INT(11) DEFAULT NULL AFTER created_by;

ALTER TABLE cotizaciones ADD COLUMN cupon_folio_enviado_id INT(11) DEFAULT NULL AFTER cupones_id;

ALTER TABLE productos ADD COLUMN ocultar TINYINT(1) DEFAULT 0 AFTER sin_envio;

ALTER TABLE cupones_imagenes ADD COLUMN productos_id INT(11) DEFAULT NULL AFTER monto_final;

ALTER TABLE cotizaciones ADD COLUMN cupon_enviado_id INT(11) DEFAULT NULL AFTER cupon_folio_enviado_id;

ALTER TABLE cotizaciones ADD COLUMN producto_regalo_id INT(11) DEFAULT NULL AFTER folio_cupon;

ALTER TABLE cupones_imagenes ADD COLUMN modified DATETIME DEFAULT NULL AFTER created_by;
ALTER TABLE cupones_imagenes ADD COLUMN modified_by INT(11) DEFAULT NULL AFTER modified;

ALTER TABLE cupones_folios ADD COLUMN producto_regalo_id INT(11) DEFAULT NULL AFTER cotizaciones_id;
ALTER TABLE cotizaciones ADD COLUMN `cupon_cliente_email` varchar(200) DEFAULT NULL AFTER ibs;

ALTER TABLE cupones_folios ADD COLUMN fecha_envio DATETIME DEFAULT NULL AFTER cotizaciones_id;
ALTER TABLE cupones_folios ADD COLUMN cupon_enviado_url VARCHAR(255) NULL DEFAULT NULL AFTER cotizaciones_id;

-- 2016-02-15
-- @autor Roger Morales
-- Se agregó una columna para productos categorias y otra para cotizaciones
ALTER TABLE productos_categorias ADD COLUMN cargo_recuperacion DECIMAL(5,2) DEFAULT NULL AFTER comision_vendedor;
ALTER TABLE cotizaciones ADD rescate_sucursal TINYINT(1) NULL DEFAULT 0 AFTER descuento_cupon;

-- 2016-02-19
-- @autor Roger Morales
-- Se agregó las columnas unidad_id y horas_iniciales para productos
ALTER TABLE productos ADD unidad_id TINYINT(1) NULL DEFAULT 1 AFTER precio;
ALTER TABLE accesorios ADD unidad_id TINYINT(1) NULL DEFAULT 1 AFTER nombre;
ALTER TABLE cotizaciones_productos ADD unidad_id TINYINT(1) NULL DEFAULT 1 AFTER productos_id;

INSERT INTO `catalogos` (`id`, `nombre`, `descripcion`)
VALUES
  (NULL, 'Unidades', 'Unidades de medida para los productos.');

INSERT INTO `elementos` (`id`, `catalogos_id`, `clave`, `valor`, `activo`)
VALUES
  (NULL, (SELECT id FROM catalogos WHERE nombre='Unidades' LIMIT 1), 1, 'Pieza(s)', 1),
  (NULL, (SELECT id FROM catalogos WHERE nombre='Unidades' LIMIT 1), 2, 'Hora(s)', 1);

CREATE TABLE `gastos_cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productos_id` int(11) NOT NULL,
  `horas_iniciales_aguascalientes` INT(11) DEFAULT NULL,
  `precio_inicial_aguascalientes` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_aguascalientes` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_baja_california` INT(11) DEFAULT NULL,
  `precio_inicial_baja_california` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_baja_california` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_baja_california_sur` INT(11) DEFAULT NULL,
  `precio_inicial_baja_california_sur` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_baja_california_sur` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_campeche` INT(11) DEFAULT NULL,
  `precio_inicial_campeche` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_campeche` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_chiapas` INT(11) DEFAULT NULL,
  `precio_inicial_chiapas` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_chiapas` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_chihuahua` INT(11) DEFAULT NULL,
  `precio_inicial_chihuahua` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_chihuahua` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_coahuila_de_zaragoza` INT(11) DEFAULT NULL,
  `precio_inicial_coahuila_de_zaragoza` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_coahuila_de_zaragoza` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_colima` INT(11) DEFAULT NULL,
  `precio_inicial_colima` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_colima` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_distrito_federal` INT(11) DEFAULT NULL,
  `precio_inicial_distrito_federal` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_distrito_federal` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_durango` INT(11) DEFAULT NULL,
  `precio_inicial_durango` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_durango` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_guanajuato` INT(11) DEFAULT NULL,
  `precio_inicial_guanajuato` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_guanajuato` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_guerrero` INT(11) DEFAULT NULL,
  `precio_inicial_guerrero` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_guerrero` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_hidalgo` INT(11) DEFAULT NULL,
  `precio_inicial_hidalgo` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_hidalgo` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_jalisco` INT(11) DEFAULT NULL,
  `precio_inicial_jalisco` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_jalisco` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_mexico` INT(11) DEFAULT NULL,
  `precio_inicial_mexico` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_mexico` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_michoacan_de_ocampo` INT(11) DEFAULT NULL,
  `precio_inicial_michoacan_de_ocampo` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_michoacan_de_ocampo` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_morelos` INT(11) DEFAULT NULL,
  `precio_inicial_morelos` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_morelos` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_nayarit` INT(11) DEFAULT NULL,
  `precio_inicial_nayarit` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_nayarit` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_nuevo_leon` INT(11) DEFAULT NULL,
  `precio_inicial_nuevo_leon` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_nuevo_leon` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_oaxaca` INT(11) DEFAULT NULL,
  `precio_inicial_oaxaca` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_oaxaca` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_puebla` INT(11) DEFAULT NULL,
  `precio_inicial_puebla` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_puebla` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_queretaro` INT(11) DEFAULT NULL,
  `precio_inicial_queretaro` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_queretaro` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_quintana_roo` INT(11) DEFAULT NULL,
  `precio_inicial_quintana_roo` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_quintana_roo` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_san_luis_potosi` INT(11) DEFAULT NULL,
  `precio_inicial_san_luis_potosi` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_san_luis_potosi` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_sinaloa` INT(11) DEFAULT NULL,
  `precio_inicial_sinaloa` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_sinaloa` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_sonora` INT(11) DEFAULT NULL,
  `precio_inicial_sonora` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_sonora` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_tabasco` INT(11) DEFAULT NULL,
  `precio_inicial_tabasco` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_tabasco` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_tamaulipas` INT(11) DEFAULT NULL,
  `precio_inicial_tamaulipas` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_tamaulipas` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_tlaxcala` INT(11) DEFAULT NULL,
  `precio_inicial_tlaxcala` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_tlaxcala` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_veracruz_de_ignacio_de_la_llave` INT(11) DEFAULT NULL,
  `precio_inicial_veracruz_de_ignacio_de_la_llave` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_veracruz_de_ignacio_de_la_llave` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_yucatan` INT(11) DEFAULT NULL,
  `precio_inicial_yucatan` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_yucatan` DOUBLE(10,2) DEFAULT '0.00',
  `horas_iniciales_zacatecas` INT(11) DEFAULT NULL,
  `precio_inicial_zacatecas` DOUBLE(10,2) DEFAULT '0.00',
  `precio_horas_extra_zacatecas` DOUBLE(10,2) DEFAULT '0.00',
  `activo` INT(1) NOT NULL DEFAULT '1',
  `eliminado` INT(1) NOT NULL DEFAULT '0',
  `created` DATETIME NOT NULL,
  `created_by` INT(11) NOT NULL,
  `modified` DATETIME DEFAULT NULL,
  `modified_by` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE cotizaciones CHANGE estado estado VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE cotizaciones CHANGE municipio municipio VARCHAR(100) NULL DEFAULT NULL;

INSERT INTO `gastos_cursos` (`id`, `productos_id`, `horas_iniciales_aguascalientes`, `precio_inicial_aguascalientes`, `precio_horas_extra_aguascalientes`, `horas_iniciales_baja_california`, `precio_inicial_baja_california`, `precio_horas_extra_baja_california`, `horas_iniciales_baja_california_sur`, `precio_inicial_baja_california_sur`, `precio_horas_extra_baja_california_sur`, `horas_iniciales_campeche`, `precio_inicial_campeche`, `precio_horas_extra_campeche`, `horas_iniciales_chiapas`, `precio_inicial_chiapas`, `precio_horas_extra_chiapas`, `horas_iniciales_chihuahua`, `precio_inicial_chihuahua`, `precio_horas_extra_chihuahua`, `horas_iniciales_coahuila_de_zaragoza`, `precio_inicial_coahuila_de_zaragoza`, `precio_horas_extra_coahuila_de_zaragoza`, `horas_iniciales_colima`, `precio_inicial_colima`, `precio_horas_extra_colima`, `horas_iniciales_distrito_federal`, `precio_inicial_distrito_federal`, `precio_horas_extra_distrito_federal`, `horas_iniciales_durango`, `precio_inicial_durango`, `precio_horas_extra_durango`, `horas_iniciales_guanajuato`, `precio_inicial_guanajuato`, `precio_horas_extra_guanajuato`, `horas_iniciales_guerrero`, `precio_inicial_guerrero`, `precio_horas_extra_guerrero`, `horas_iniciales_hidalgo`, `precio_inicial_hidalgo`, `precio_horas_extra_hidalgo`, `horas_iniciales_jalisco`, `precio_inicial_jalisco`, `precio_horas_extra_jalisco`, `horas_iniciales_mexico`, `precio_inicial_mexico`, `precio_horas_extra_mexico`, `horas_iniciales_michoacan_de_ocampo`, `precio_inicial_michoacan_de_ocampo`, `precio_horas_extra_michoacan_de_ocampo`, `horas_iniciales_morelos`, `precio_inicial_morelos`, `precio_horas_extra_morelos`, `horas_iniciales_nayarit`, `precio_inicial_nayarit`, `precio_horas_extra_nayarit`, `horas_iniciales_nuevo_leon`, `precio_inicial_nuevo_leon`, `precio_horas_extra_nuevo_leon`, `horas_iniciales_oaxaca`, `precio_inicial_oaxaca`, `precio_horas_extra_oaxaca`, `horas_iniciales_puebla`, `precio_inicial_puebla`, `precio_horas_extra_puebla`, `horas_iniciales_queretaro`, `precio_inicial_queretaro`, `precio_horas_extra_queretaro`, `horas_iniciales_quintana_roo`, `precio_inicial_quintana_roo`, `precio_horas_extra_quintana_roo`, `horas_iniciales_san_luis_potosi`, `precio_inicial_san_luis_potosi`, `precio_horas_extra_san_luis_potosi`, `horas_iniciales_sinaloa`, `precio_inicial_sinaloa`, `precio_horas_extra_sinaloa`, `horas_iniciales_sonora`, `precio_inicial_sonora`, `precio_horas_extra_sonora`, `horas_iniciales_tabasco`, `precio_inicial_tabasco`, `precio_horas_extra_tabasco`, `horas_iniciales_tamaulipas`, `precio_inicial_tamaulipas`, `precio_horas_extra_tamaulipas`, `horas_iniciales_tlaxcala`, `precio_inicial_tlaxcala`, `precio_horas_extra_tlaxcala`, `horas_iniciales_veracruz_de_ignacio_de_la_llave`, `precio_inicial_veracruz_de_ignacio_de_la_llave`, `precio_horas_extra_veracruz_de_ignacio_de_la_llave`, `horas_iniciales_yucatan`, `precio_inicial_yucatan`, `precio_horas_extra_yucatan`, `horas_iniciales_zacatecas`, `precio_inicial_zacatecas`, `precio_horas_extra_zacatecas`, `activo`, `eliminado`, `created`, `created_by`, `modified`, `modified_by`)
VALUES
  (35, 143, 3, 7750.00, 853.45, 3, 11198.28, 853.45, 3, 11198.28, 853.45, 3, 9474.14, 853.45, 3, 7750.00, 853.45, 3, 8612.07, 853.45, 3, 8612.07, 853.45, 3, 7750.00, 853.45, 2, 1715.52, 681.03, 3, 8612.07, 853.45, 3, 7750.00, 853.45, 3, 7750.00, 853.45, 3, 4301.72, 853.45, 3, 7750.00, 853.45, 2, 1715.52, 681.03, 3, 7750.00, 853.45, 3, 4301.72, 853.45, 3, 7750.00, 853.45, 3, 8612.07, 853.45, 3, 7750.00, 853.45, 3, 4301.72, 853.45, 3, 4301.72, 853.45, 3, 9474.14, 853.45, 3, 8612.07, 853.45, 3, 11198.28, 853.45, 3, 11198.28, 853.45, 3, 9474.14, 853.45, 3, 8612.07, 853.45, 3, 4301.72, 853.45, 3, 7750.00, 853.45, 3, 9474.14, 853.45, 3, 8612.07, 853.45, 1, 0, '2016-02-24 12:49:01', 347, NULL, NULL);

-- 2016-04-12
-- @autor Fabián Quiroz
-- Campo para registrar email de envió de guías mecánicas
ALTER TABLE cotizaciones ADD guia_mecanica_cliente_email VARCHAR(200) NULL DEFAULT NULL AFTER cupon_cliente_email;

-- 2016-04-12
-- @autor Fabián Quiroz
-- Campo para registrar el estado donde se impartira el evento
ALTER TABLE cotizaciones ADD evento_estado VARCHAR(100) NULL DEFAULT NULL;

-- 2016-05-31
-- @autor Roger Morales
-- Columnas para la tabla gastos cursos y modificación de la columna email_comprador de cotizaciones
ALTER TABLE gastos_cursos ADD horas_maximas_aguascalientes INT(11) DEFAULT NULL AFTER horas_iniciales_aguascalientes;
ALTER TABLE gastos_cursos ADD horas_maximas_baja_california INT(11) DEFAULT NULL AFTER horas_iniciales_baja_california;
ALTER TABLE gastos_cursos ADD horas_maximas_baja_california_sur INT(11) DEFAULT NULL AFTER horas_iniciales_baja_california_sur;
ALTER TABLE gastos_cursos ADD horas_maximas_campeche INT(11) DEFAULT NULL AFTER horas_iniciales_campeche;
ALTER TABLE gastos_cursos ADD horas_maximas_chiapas INT(11) DEFAULT NULL AFTER horas_iniciales_chiapas;
ALTER TABLE gastos_cursos ADD horas_maximas_chihuahua INT(11) DEFAULT NULL AFTER horas_iniciales_chihuahua;
ALTER TABLE gastos_cursos ADD horas_maximas_coahuila_de_zaragoza INT(11) DEFAULT NULL AFTER horas_iniciales_coahuila_de_zaragoza;
ALTER TABLE gastos_cursos ADD horas_maximas_colima INT(11) DEFAULT NULL AFTER horas_iniciales_colima;
ALTER TABLE gastos_cursos ADD horas_maximas_distrito_federal INT(11) DEFAULT NULL AFTER horas_iniciales_distrito_federal;
ALTER TABLE gastos_cursos ADD horas_maximas_durango INT(11) DEFAULT NULL AFTER horas_iniciales_durango;
ALTER TABLE gastos_cursos ADD horas_maximas_guanajuato INT(11) DEFAULT NULL AFTER horas_iniciales_guanajuato;
ALTER TABLE gastos_cursos ADD horas_maximas_guerrero INT(11) DEFAULT NULL AFTER horas_iniciales_guerrero;
ALTER TABLE gastos_cursos ADD horas_maximas_hidalgo INT(11) DEFAULT NULL AFTER horas_iniciales_hidalgo;
ALTER TABLE gastos_cursos ADD horas_maximas_jalisco INT(11) DEFAULT NULL AFTER horas_iniciales_jalisco;
ALTER TABLE gastos_cursos ADD horas_maximas_mexico INT(11) DEFAULT NULL AFTER horas_iniciales_mexico;
ALTER TABLE gastos_cursos ADD horas_maximas_michoacan_de_ocampo INT(11) DEFAULT NULL AFTER horas_iniciales_michoacan_de_ocampo;
ALTER TABLE gastos_cursos ADD horas_maximas_morelos INT(11) DEFAULT NULL AFTER horas_iniciales_morelos;
ALTER TABLE gastos_cursos ADD horas_maximas_nayarit INT(11) DEFAULT NULL AFTER horas_iniciales_nayarit;
ALTER TABLE gastos_cursos ADD horas_maximas_nuevo_leon INT(11) DEFAULT NULL AFTER horas_iniciales_nuevo_leon;
ALTER TABLE gastos_cursos ADD horas_maximas_oaxaca INT(11) DEFAULT NULL AFTER horas_iniciales_oaxaca;
ALTER TABLE gastos_cursos ADD horas_maximas_puebla INT(11) DEFAULT NULL AFTER horas_iniciales_puebla;
ALTER TABLE gastos_cursos ADD horas_maximas_queretaro INT(11) DEFAULT NULL AFTER horas_iniciales_queretaro;
ALTER TABLE gastos_cursos ADD horas_maximas_quintana_roo INT(11) DEFAULT NULL AFTER horas_iniciales_quintana_roo;
ALTER TABLE gastos_cursos ADD horas_maximas_san_luis_potosi INT(11) DEFAULT NULL AFTER horas_iniciales_san_luis_potosi;
ALTER TABLE gastos_cursos ADD horas_maximas_sinaloa INT(11) DEFAULT NULL AFTER horas_iniciales_sinaloa;
ALTER TABLE gastos_cursos ADD horas_maximas_sonora INT(11) DEFAULT NULL AFTER horas_iniciales_sonora;
ALTER TABLE gastos_cursos ADD horas_maximas_tabasco INT(11) DEFAULT NULL AFTER horas_iniciales_tabasco;
ALTER TABLE gastos_cursos ADD horas_maximas_tamaulipas INT(11) DEFAULT NULL AFTER horas_iniciales_tamaulipas;
ALTER TABLE gastos_cursos ADD horas_maximas_tlaxcala INT(11) DEFAULT NULL AFTER horas_iniciales_tlaxcala;
ALTER TABLE gastos_cursos ADD horas_maximas_veracruz_de_ignacio_de_la_llave INT(11) DEFAULT NULL AFTER horas_iniciales_veracruz_de_ignacio_de_la_llave;
ALTER TABLE gastos_cursos ADD horas_maximas_yucatan INT(11) DEFAULT NULL AFTER horas_iniciales_yucatan;
ALTER TABLE gastos_cursos ADD horas_maximas_zacatecas INT(11) DEFAULT NULL AFTER horas_iniciales_zacatecas;


ALTER TABLE cotizaciones CHANGE email_comprador email_comprador VARCHAR(60) DEFAULT '';

-- 2016-09-30
-- @autor Roger Morales
-- Permiso nuevo para exportar referidos
INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  (NULL,(SELECT id FROM categorias WHERE nombre='Referidos'), 'referidos_exportar', 'Permite exportar los registros de la sección referidos', '1');

INSERT INTO `catalogos` (`id`, `nombre`, `descripcion`)
VALUES
  (NULL, 'si_no', 'Si y No');

INSERT INTO `elementos` (`id`, `catalogos_id`, `clave`, `valor`, `activo`)
VALUES
  (NULL, (SELECT id FROM catalogos WHERE nombre = 'si_no'), '1', 'Sí', 1),
  (NULL, (SELECT id FROM catalogos WHERE nombre = 'si_no'), '2', 'No', 1);

ALTER TABLE referidos ADD vendedor_email VARCHAR(255) DEFAULT NULL AFTER vendedor_materno;
ALTER TABLE referidos ADD notificado INT(1) DEFAULT 0 AFTER vendedor_email;
ALTER TABLE referidos ADD fecha_envio datetime DEFAULT NULL AFTER notificado;
ALTER TABLE referidos ADD email_envio VARCHAR(255) DEFAULT NULL AFTER fecha_envio;

UPDATE referidos SET notificado = 1 WHERE vigencia < NOW();

-- 2016-10-06
-- @autor Roger Morales
-- Permiso nuevo para exportar referidos
ALTER TABLE cotizaciones ADD ofrecio_evento INT(1) DEFAULT 0;

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  (NULL,(SELECT id FROM categorias WHERE nombre='Cotizaciones'), 'cotizaciones_exportar', 'Permite exportar los registros de la sección cotizaciones', '1');

ALTER TABLE cotizaciones ADD descuento_paquete_id INT(11) DEFAULT NULL AFTER descuento_paquete;
ALTER TABLE cotizaciones ADD fecha_autorizacion DATETIME DEFAULT NULL AFTER descuento_paquete_id;

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  (NULL,(SELECT id FROM categorias WHERE nombre='Comisiones'), 'comisiones_exportar', 'Permite exportar los registros de la sección comisiones', '1');

ALTER TABLE comisiones ADD `created` datetime NOT NULL;
ALTER TABLE comisiones ADD `created_by` int(11) NOT NULL;
ALTER TABLE comisiones ADD `modified` datetime DEFAULT NULL;
ALTER TABLE comisiones ADD `modified_by` int(11) DEFAULT NULL;

-- Actualiza la fecha de creación de la comisión
UPDATE comisiones as com
  LEFT JOIN cotizaciones as cot ON com.cotizaciones_id = cot.id
SET com.created=cot.modified;

-- Actualiza la fecha de autorización
UPDATE cotizaciones SET fecha_autorizacion = modified WHERE status_id = 4;

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
  (NULL,(SELECT id FROM categorias WHERE nombre='Cotizaciones'), 'cotizaciones_autorizadas_reporte', 'Permite exportar el reporte de cotizaciones autorizadas', '1');

UPDATE cotizaciones as c
LEFT JOIN cupones_folios as cf ON c.cupon_folio_enviado_id = cf.id
SET c.folio_cupon = cf.folio WHERE c.cupon_folio_enviado_id IS NOT NULL;