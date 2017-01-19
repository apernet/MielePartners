<?php
/* EMAIL */
$config['mail_compra_venta_directa']='aldo.bandera@miele.com.mx'; 	// COMPRAS NUEVAS VENTA DIRECTA PARA MIELE PARTNERS
$config['mail_compra_venta_distribuidor']='aldo.bandera@miele.com.mx'; // COMPRAS NUEVAS DISTRIBUDIORES PARA MIELE PARTNERS
$config['mail_comentarios']=array('aldo.bandera@miele.com.mx');
$config['mail_venta_cliente_externo']=array('aldo.bandera@miele.com.mx'); // COMPRAS NUEVAS VENTA DIRECTA PARA MIELE SHOP
$config['nombre_venta_cliente_externo']='Paola Mesura'; // COMPRAS NUEVAS VENTA DIRECTA PARA MIELE SHOP
$config['mail_calificacion_compra']=array('aldo.bandera@miele.com.mx'); // Calificacion de productos

$config['url']='https://preview.mieleshop.com.mx/';
$config['shop_url']='https://preview.mieleshop.com.mx/';
$config['calificacion_url']='https://preview.mieleshop.com.mx/calificaciones/califica/';
$config['guias_mecanicas_zip_size'] = 5242880; // 5 MB
/* EMAIL FIN */

/* RACKSPACE */
$config['rackspace_container'] = 'MIELE_TEST';
$config['cdn'] = 'http://e73a2d8c1dbc6d00aeb1-7e024fc280aeb94c16ec8716e829f4c6.r14.cf1.rackcdn.com';
$config['cloudfiles'] = TRUE; // Bandera para cloudfiles
$config['cloudfiles_interno'] = false; // Bandera para usar cloudfiles por la red interna
/* RACKSPACE FIN */

/* PAGOS EN LINEA */

/* CATEGORIAS VIDEOS */
$config['max_size'] = 15728640;


$config['cuota_minima_meses_sin_intereses']=10000;

$config['american_express'] = array(
    'url' => 'https://gateway-na.americanexpress.com/api/rest/version/20/merchant/%s/session',
    'merchant' => 'TEST9352568548',
    'name' => 'MIELE SA DE CV ONLINE',
    'user' => 'merchant.TEST9352568548',
    'password' => '1165374ef7c047a1ec33314a3873a1b2',
    'data' => array(
        'order' => array('currency' => 'MXN'),
        'constraints' => array(
            'paymentPlans' => array(
                //'supported' => array('AMEX_PlanN'),
                'numberOfPayments' => 12
            )
        )
    )
);

$config['banamex'] = array(
    'url' => 'https://banamex.dialectpayments.com/vpcpay',
    'secure_hash' => '9F74FCDAD7E71632421F1B8F2ECEACBF',
    'data' => array(
        'vpc_Version' => 1,
        'vpc_Command' => 'pay',
        'vpc_Merchant' => 'TEST1031554',
        'vpc_Currency' => 'MXN',
        'vpc_Locale' => 'es_MX',
        'vpc_AccessCode'=>'89C12BAE',
        'vpc_ReturnURL'=> $config['shop_url'].'payments/callback'
    )
);
/* PAGOS EN LINEA FIN */
