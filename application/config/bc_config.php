<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* GENERAL */
$config['proyecto']='Miele';
$config['pass_length']=8;
$config['mostrar_detalles']=FALSE;
$config['busqueda_avanzada']=FALSE;
$config['bloquear_noticias']=FALSE; //DETENER 5 SEGUNDOS LAS NOTICIAS EN DASHBOARD
//$config['url']='http://www.miele.com';
//$config['url']='http://mielepartners.com.mx';
$config['header_color']='#E20A17'; // COLOR DEL HEADER EN HEX PARA EL HEADER DE LOS MAILS
/* FIN GENERAL */

/* DEBUG Y CACHE */
$config['mantenimiento']=FALSE;
$config['debug']=TRUE;
$config['debug_mail']=FALSE;
$config['cache_output']=FALSE;
$config['cache_time']=10; // Tiempo que duran los archivos en cache en minutos
$config['firebug']=FALSE;
$config['log_level']=FALSE; // ACTIVA EL LOG PARA CADA QUE SE GUARDA UN REGISTRO DEL AVALUO
/* FIN DEBUG Y CACHE */

/* VIGENCIA CUPON */
$config['vigencia_folio_cupon'] = 31536000; // Cantidad en segundos de un año
$config['clave_codificacion'] = '61ackc0r3Mi313Sh0pÇup0ne2';
$config['iv'] = 'ZO/xW3gGct8=';

/* IMAGENES */
$config['image_size']=600;
$config['thumb_size']=150;
/* FIN IMAGENES */

/* EMAIL */
$config['email']=array('info@miele.com.mx','Miele');
$config['mail_bcc']=array('');//array('brenda.hernandez@miele.com.mx');
$config['bcc_blackcore']=FALSE; // ACTIVAR SI PARA QUE ENVIE COPIA DE LOS CORREOS A BLACKCORE
/* FIN EMAIL */

/* DISEÑO */
$config['jquery_css']='redmond'; // PARA JQUERY UI CARPETA DEL CSS DEFAULT (redmond)
/* FIN DISEÑO */


/* IVA PORCENTAJE*/
$config['iva_porcentaje']=16;
$config['adicional_tarjeta']=.02;
$config['descuento']=99999999999999999999999999;
$config['monto_descuento']=0.05;

/* VARIABLES DE CALCULO */
$config['dias_vigencia']=180; //VIGENCIA DE REFERIDOS
$config['envio']=0.08; //PORCENTAJE  DE ENVIO
$config['envio_DF_consumibles']=100;
$config['envio_foraneo_consumibles']=200;
$config['productos_envio']=3; // NUMERO DE PRODUCTOS QUE GENERAN GASTOS DE ENVIO (SI ES MAYOR O IGUAL NO GENERA GASTO DE ENVIO)
$config['consumibles_porcentaje_envio']=5; // PORCENTAJE DE TOTAL DE LA COMPRA QUE SE PAGARÁ DE ENVÍO PARA LOS CONSUMIBLES
/* FIN VARIABLES DE CALCULO */

/* PAGINADOR */
$config['por_pagina']=10;
$config['front_por_pagina']=9;

$config['paginador_config']	= array(
	'full_tag_open'  => '<ul class="pagination">',
	'full_tag_close' => '</ul>',
	'first_link'	 => '&laquo;',
	'first_tag_open' => '<li>',
	'first_tag_close'=> '</li>',
	'last_link'	 	 => '&raquo;',
	'last_tag_open'	 => '<li>',
	'last_tag_close' => '</li>',
	'next_link'	 	 => '&gt;',
	'next_tag_open'	 => '<li>',
	'next_tag_close' => '</li>',
	'prev_link'	 	 => '&lt;',
	'prev_tag_open'	 => '<li>',
	'prev_tag_close' => '</li>',
	'cur_tag_open'   => '<li class="disabled"><span>',
	'cur_tag_close'  => '</span></li>',
	'num_tag_open'   => '<li>',
	'num_tag_close'  => '</li>',
	'per_page'=>$config['por_pagina']
);
/* FIN PAGINADOR */

/* RACKSPACE */
$config['rackspace'] = array(
		'username' => 'rezorte',
		'tenantName' => '717941',
		'apiKey' => '15accd0268babac4ef317fb8c9ceb919',
		'password' => 'xI70wVTY'
);
$config['rackspace_url'] = 'https://identity.api.rackspacecloud.com/v2.0/';

/* FIN CONFIGURACION RACKSPACE */

/* Calificar compras de miele partners */
$config['calificar_miele_partners'] = FALSE;
/* Fin calificar compras de miele partners */

/* Intentos para enviar el mail con la liga para calificar */
$config['calificaciones_email_intentos'] = 1;
/* Fin Intentos para enviar el mail con la liga para calificar */

include('domain_selector.php');
$config['domain']=$domain;
include_once("bc_config_{$unidad}.php");


$config['rackspace'] = array(
	'username' => 'blackcore_miele',
	'tenantName' => '717941',
	'apiKey' => 'b9a6757c17964bd3a0d219bbe145abf4',
	'password' => '3My^AW@g&7c+,yV;'
);

$config['notificaciones_referidos']=TRUE;

/* End of file bc_config.php */
/* Location: ./application/config/bc_config.php */
