# ************************************************************
# DATA SISTEMA BASE - BLACKCORE
# ************************************************************

# Dump of table categorias
# ------------------------------------------------------------

INSERT INTO `categorias` (`id`, `nombre`, `activo`)
VALUES
	(1,'Sin categoría',1),
	(2,'Configuración',1);

# Dump of table cuentas
# ------------------------------------------------------------

INSERT INTO `cuentas` (`id`, `nombre`, `razon_social`, `rfc`, `codigo_postal`, `asentamiento`, `municipio`, `estado`, `pais`, `calle`, `numero_exterior`, `numero_interior`, `telefono`, `email`, `activo`, `eliminado`, `created`, `created_by`, `modified`, `modified_by`)
VALUES
	(1,'Blackcore','Sistemas Blackcore de México S.A. de C.V.','SBM090813PP4',NULL,NULL,NULL,NULL,'MEXICO',NULL,NULL,NULL,'+(52) 55 5019 5041','contacto@blackcore.com.mx',1,0,'2011-08-15 21:09:51',1,'2011-08-15 21:12:19',1);

# Dump of table funciones
# ------------------------------------------------------------


INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`)
VALUES
	(1,2,'usuarios','Administración de usuarios',1),
	(2,2,'grupos','Administración de grupos',0),
	(3,2,'permisos','Administración de permisos',0),
	(4,1,'logs','Consulta de logs del sistema',0),
	(5,1,'catalogos','Administrar catálogos del sistema',0),
	(6,1,'elementos','Administrar elementos de los catálogos del sistema',0),
	(7,1,'menu_panel_control','Visualizar menu Panel de control.',0),
	(8,1,'cuentas','Administración de cuentas',0),
	(9,1,'noticias','Permite administrar las noticias que se muestran a los usuarios en el sistema.',0),
	(10,1,'categorias','Administrar las categorias de las funciones',0);

# Dump of table grupos
# ------------------------------------------------------------

INSERT INTO `grupos` (`id`, `nombre`, `activo`)
VALUES
	(1,'Blackcore',1);

# Dump of table permisos
# ------------------------------------------------------------

INSERT INTO `permisos` (`id`, `grupos_id`, `funciones_id`)
VALUES
	(NULL,1,1),
	(NULL,1,2),
	(NULL,1,3),
	(NULL,1,4),
	(NULL,1,5),
	(NULL,1,6),
	(NULL,1,7),
	(NULL,1,8),
	(NULL,1,9),
	(NULL,1,10);


# Dump of table usuarios
# ------------------------------------------------------------

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `grupos_id`, `cuentas_id`, `apellido_paterno`, `apellido_materno`, `nombre`, `email`, `telefono`, `celular`, `ayuda`, `activo`, `eliminado`, `created`, `created_by`, `modified`, `modified_by`)
VALUES
	(1,'remery','6468a4c9e4740e298f59a87cebcf17e3',1,1,'Emery','Pérez de León','Ricardo','remery@blackcore.com.mx','+52 (55) 50195041',NULL,1,1,0,'2011-08-15 00:00:00',1,'2011-08-15 21:34:06',1),
	(2,'bmiranda','94fb8d7c58ba5ea007a7e658ebee463c',1,1,'Miranda','Uribe','Brenda','bmiranda@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-15 21:59:55',1,'2011-08-15 22:01:43',1),
	(3,'jgonzalez','3e4a8ce3b0ffd62212556475f1be7214',1,1,'Gonzalez','Leocadio','Joel','jgonzalez@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-15 22:02:12',1,'2011-08-15 22:03:29',1),
	(4,'jhernandez','2d7e2611a52315655a1a9e5bab254456',1,1,'Hernandez','Arreta','Jakim','jhernandez@blackcore.com.mx','+(52) 55 5019 5041',NULL,0,1,0,'2011-08-24 20:19:51',1,'2011-08-24 20:20:31',1);
	
--
-- CATEGORIA DE PRODUCTOS
--

INSERT INTO  `categorias` (`id`, `nombre`, `activo`) 
VALUES ( 3, 'Productos',  '1');
	
	
--
-- PERMISOS PARA PRODUCTOS.
--

INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES 
(NULL ,  '3',  'productos',  'Administrar Productos.',  '1'), 
(NULL ,  '3',  'productos_agregar',  'Permite agregar un producto.',  '1'),
(NULL ,  '3',  'productos_editar',  'Permite editar un producto.',  '1'), 
(NULL ,  '3',  'productos_eliminar',  'Permite eliminar un producto.',  '1'), 
(NULL ,  '3',  'productos_activar',  'Permite activar/desactivar un producto.',  '1');

--
-- CATEGORIA DE CATEGORIAS
--

INSERT INTO  `categorias` (`id`, `nombre`, `activo`) 
VALUES ( 5, 'Categorias de Productos',  '1');


--
-- PERMISOS PARA CATEGORIAS DE PRODUCTOS.
--

INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES
(NULL ,  '5',  'categorias_productos', 'Administrar las categorias de los productos.', 1),
(NULL ,  '5',  'categorias_agregar',  'Permite agregar una categoria.',  '1'), 
(NULL ,  '5',  'categorias_editar',  'Permite editar una categoria.',  '1'),
(NULL ,  '5',  'categorias_eliminar',  'Permite eliminar una categoria.',  '1'), 
(NULL ,  '5',  'categorias_activar',  'Permite activar/desactivar una categoria.',  '1');

--
-- CATALOGO Y ELEMENTOS DE FOTOGRAFIAS DE LOS PRODUCTOS.
--

INSERT INTO `catalogos` (`id`, `nombre`, `descripcion`)VALUES
(1, 'fotografias', 'fotografias');

INSERT INTO `elementos` (`id`, `catalogos_id`, `clave`, `valor`, `activo`) VALUES
(NULL, 1, '1', 'PRINCIPAL', 1),
(NULL, 1, '2', 'PLANO', 1),
(NULL, 1, '3', 'GALERIA', 1);

--
-- INSERTA LA CATEGORIA DE BANNERS.
--
INSERT INTO `categorias` (`id` , `nombre` , `activo`) VALUES 
(6 , 'Banners', '1');

--
-- PERMISOS PARA VISUALIZAR BANNERS.
--

INSERT INTO `funciones` (`id` , `categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES 
(NULL , '6', 'banners', 'Administrar Banners', '1'),
(NULL , '6', 'banners_agregar', 'Permite agregar un banner', '1'), 
(NULL , '6', 'banners_editar', 'Permite editar un banner', '1'),
(NULL , '6', 'banners_activar', 'Permite activar/desactivar un banner', '1'), 
(NULL , '6', 'banners_eliminar', 'Permite eliminar un banner', '1');

--
-- INSERTA LA CATEGORIA DE BANNERS.
--
INSERT INTO `categorias` (`id` , `nombre` , `activo`) VALUES 
(7 , 'Cotizaciones', '1');

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
(NULL , 7, 'cotizaciones', 'Administrar Cotizaciones', 1),
(NULL , 7, 'cotizaciones_editar', 'Permite editar una cotizacion', 1),
(NULL , 7, 'cotizaciones_agregar', 'Permite agregar una cotizacion', 1),
(NULL , 7, 'cotizaciones_eliminar', 'Permite eliminar una cotizacion', 1);

--
-- CATALOGO DE FOTOS PARA INFORMACION ADICIONAL
--
INSERT INTO `elementos` (`id` ,`catalogos_id` ,`clave` ,`valor` ,`activo`) VALUES 
(NULL , '1', '4', 'MEDIDAS', '1'), 
(NULL , '1', '5', 'MATERIALES', '1'),
(NULL , '1', '6', 'INFORMACION ADICIONAL', '1'),
(NULL , '1', '7', 'CARACTERISTICAS', '1'),
(NULL , '1', '8', 'CUIDADO Y ENSAMBLE', '1');


--
-- MENU DE ADMINISTRACION
--
INSERT INTO `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES 
(NULL , '1', 'menu_administracion', 'Activa el menú de administración', '1');

--
-- CATALOGO TIPO DE MONEDA
--

INSERT INTO  `catalogos` (`id` ,`nombre` ,`descripcion`) VALUES 
(NULL ,  'tipo_moneda',  'Tipo de moneda');

--
-- ELEMENTOS DEL CATALOGO TIPO DE MONEDA
--

INSERT INTO  `elementos` (`id` ,`catalogos_id` ,`clave` ,`valor` ,`activo`) VALUES 
(NULL ,  '2',  '1',  'M.N',  '1'), 
(NULL ,  '2',  '2',  'USD',  '1'),
(NULL ,  '2',  '3',  '€',  '1');

--
-- PERMISOS ACCESORIOS
--
INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
(NULL, 8, 'tipos_accesorios', 'Administrar tipos de accesorios', 1),
(NULL, 8, 'accesorios', 'Administrar accesorios', 1),
(NULL, 8, 'accesorios_agregar', 'Permite agregar un accesorio.', 1),
(NULL, 8, 'accesorios_editar', 'Permite editar un accesorio.', 1),
(NULL, 8, 'accesorios_activar', 'Pemite activar/desactiva un accesorio', 1),
(NULL, 8, 'accesorios_eliminar', 'Permite eliminar un accesorio', 1),
(NULL, 8, 'tipos_accesorios_agregar', 'Permite agregar un tipo de accesorio', 1),
(NULL, 8, 'tipos_accesorios_editar', 'Permite editar un tipo de accesorio', 1),
(NULL, 8, 'tipos_accesorios_activar', 'Permite activar/desactivar un tipo de accesorio', 1);

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES
(NULL, 7, 'cotizaciones_agregar_terminos', 'Permite agregar terminos y condiciones a una cotizacion', 1),
(NULL, 7, 'cotizaciones_enviar_compra', 'Permite procesar una cotización a una orden de compra.', 1),
(NULL, 7, 'cotizaciones_imprimir', 'Permite imprimir una cotizacion', 1);
	
INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`)
VALUES (NULL, 1, 'menu_miele', 'Visualizar menu Miele.', 1);


--
-- CATALOGO 
--
INSERT INTO `catalogos` (`id` ,`nombre`,`descripcion`)VALUES 
(NULL , 'obligatorio_optativo', 'obligatorio_optativo');
INSERT INTO `elementos` (`id` ,`catalogos_id` ,`clave` ,`valor` ,`activo`)
VALUES (NULL , '3', '1', 'optativo', '1'), 
(NULL , '3', '2', 'obligatorio', '1');

--
-- CATEGORIA ACCESORIOS 
--
INSERT INTO `categorias` (`id`, `nombre`, `activo`)
VALUES	(8, 'Accesorios', 1);

--
-- ELEMENTO DE CATALOGO DE FOTOGRAFIA
--
INSERT INTO `elementos` (`id`, `catalogos_id`, `clave`, `valor`, `activo`)
VALUES (NULL, 1, '9', 'FICHA TECNICA', 1);

--
-- AJUSTE EN PERMISOS
--
DELETE FROM `funciones` WHERE `funciones`.`funcion` = 'cotizaciones_agregar_terminos';

--
-- AJUSTE EN PERMISOS
--
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES 
(44 ,  '7',  'cotizaciones_cuenta',  'Permite visualizar únicamente las cotizaciones de su cuenta.',  '1'), 
(45 ,  '7',  'cotizaciones_todas',  'Permite visualizar todas las cotizaciones.',  '1');
INSERT INTO  `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`) VALUES 
(46 ,  '7',  'cotizaciones_editar_compra',  'Permite editar una cotización en status de orden de compra.',  '1');

--
-- MODIFICAR E INSERNAR FUNCIONES FQUIROZ
--

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES 
(40, '7', 'cotizaciones_agregar_documentacion', 'Permite agregar documentación de una cotización', '1');

INSERT INTO `funciones` (`id`, `categorias_id`, `funcion`, `descripcion`, `publico`) VALUES 
(47, '7', 'cotizaciones_revision_orden_compra', 'Permite revisar una cotización para autorizarla', '1');

UPDATE `funciones` SET `descripcion` = 'Permite agregar documentación de una cotización' WHERE `funciones`.`id` =41;

INSERT INTO `catalogos` (`id` ,`nombre` ,`descripcion`)
VALUES ('4', 'tipo_persona_fiscal', 'Indica el tipo de persona fiscal');

INSERT INTO `elementos` (`id` ,`catalogos_id` ,`clave` ,`valor` ,`activo`)
VALUES (NULL , '4', '1', 'Persona Fisica', '1'), (NULL , '4', '2', 'Persona Fisica con Actividad Empresarial', '1'), (NULL , '4', '3', 'Persona Moral', '1');

INSERT INTO `funciones` (`id` ,`categorias_id` ,`funcion` ,`descripcion` ,`publico`)
VALUES (NULL ,  '7',  'cotizaciones_individuales',  'Permite ver las cotizaciones asiganadas a un usuario.',  '1');