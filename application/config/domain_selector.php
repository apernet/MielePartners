<?php
$domain=$_SERVER['HTTP_HOST'];
switch($domain)
{
    case 'mieleshop':
    case 'mielepartners':
    case 'www.mielepartners.com.mx':
    case 'shop.miele.com.mx':
    case 'mielepartners.com.mx':
        $base_datos='miele';
        $unidad='miele';
        break;
    case 'mielepreview':
    case 'preview.mieleshop.com.mx':
        $base_datos='miele_prueba';
        $unidad='miele_prueba';
        break;
    case 'dev':
    default:
        $base_datos='default';
        $unidad='default';
        break;
}


