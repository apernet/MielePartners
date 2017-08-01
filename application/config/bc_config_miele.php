<?php
/* EMAIL */
$config['mail_compra_venta_directa']=array('montserrat.badiola@miele.com', 'leslye.manilla@miele.com');     // COMPRAS NUEVAS VENTA DIRECTA PARA MIELE PARTNERS
$config['mail_compra_venta_distribuidor']=array('paola.mesura@miele.com','elizabeth.carranza@miele.com'); // COMPRAS NUEVAS DISTRIBUDIORES PARA MIELE PARTNERS
$config['mail_comentarios']=array('info@miele.com.mx');
$config['mail_venta_cliente_externo']=array('leslye.manilla@miele.com'); // COMPRAS NUEVAS VENTA DIRECTA PARA MIELE SHOP
$config['mail_calificacion_compra']=array('aldo.bandera@miele.com.mx'); // Calificacion de productos
$config['nombre_venta_cliente_externo']='Leslye Manilla'; // COMPRAS NUEVAS VENTA DIRECTA PARA MIELE SHOP

$config['url']='http://www.mielepartners.com.mx/';
$config['shop_url']='https://shop.miele.com.mx/';
$config['calificacion_url']='https://www.mielepartners.com.mx/calificaciones/califica/';
$config['guias_mecanicas_zip_size'] = 5242880; // 5 MB
/* EMAIL FIN */

/* RACKSPACE */
$config['rackspace_container'] = 'MIELE';
$config['cdn'] = 'http://d0e525820b3a2f2e5028-b80e79d3e49c779f843e1b1326dc6e53.r23.cf1.rackcdn.com';
$config['cloudfiles'] = FALSE; // Bandera para cloudfiles
$config['cloudfiles_interno'] = FALSE; // Bandera para usar cloudfiles por la red interna
/* RACKSPACE FIN */

/* CATEGORIAS VIDEOS */
$config['max_size'] = 15728640;

/* PAGOS EN LINEA */
$config['cuota_minima_meses_sin_intereses']=10000;

/** PROMOCIONES */
$config['banamex_msi_vigencia']='2015-11-16 23:59:59';

$config['american_express'] = array(
    'url' => 'https://gateway-na.americanexpress.com/api/rest/version/20/merchant/%s/session',
    'merchant' => '9352568548',
    'name' => 'MIELE SA DE CV ONLINE',
    'user' => 'merchant.9352568548',
    'password' => '2e9c2b7d4466bac3feb994cf7b150da7',// 2e9c2b7d4466bac3feb994cf7b150da7 - 1165374ef7c047a1ec33314a3873a1b2
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
    'secure_hash' => 'CA31932656719BB4413C6C6B62DFC808',// CA31932656719BB4413C6C6B62DFC808 - 9F74FCDAD7E71632421F1B8F2ECEACBF
    'data' => array(
        'vpc_Version' => 1,
        'vpc_Command' => 'pay',
        'vpc_Merchant' => '1031554',
        'vpc_Currency' => 'MXN',
        'vpc_Locale' => 'es_MX',
        'vpc_AccessCode'=>'4D4BE4E2',// 4D4BE4E2 - 89C12BAE
        'vpc_ReturnURL'=> $config['shop_url'].'payments/callback'
    ),
    'msi_18_plan_id'=>'BPWOI1'
);
/* PAGOS EN LINEA FIN */
