<html>
<body style="background: #f9f9f9; color: #4F4F4F; font-family:tahoma,arial,helvetica,sans-serif; font-size:11px; margin:0px; padding:0px;">
<table style="margin: 10px auto; width: 800px; background-color: #FFFFFF;">
	<tr>
		<td style="background-color: <?php echo $this->config->item('header_color');?>; padding: 5px;">
			<?php $path =  $this->config->item('url').'img/admin_theme/Logo_MIELE.png';?>
			<img src="<?php echo $path;?>" alt="<?php echo $this->config->item('proyecto');?>"/>
		</td>
	</tr>
	<tr>
		<td style="background-color: #FFFFFF;  padding: 20px;">
			<p>Estimad@ Cliente Miele,</p>
			<p>Agradecemos su preferencia y confianza en nuestra marca, adjunto encontrar&aacute; una carta de Bienvenida, carta de servicio y las guías mecánicas de sus equipos adquiridos en la orden de venta No. <b><?php echo @$ibs?></b>.</p>
			<p><br/>Le sugerimos revisar las gu&iacute;as mec&aacute;nicas de los equipos para asegurar la correcta instalaci&oacute;n de los mismos.</p>
		</td>
	</tr>
	<tr>
		<td style="background-color: #FFFFFF;  padding: 20px;">
			<p><b>NOTA: Si no puede visualizar o abrir el archivo adjunto, consulte el siguiente enlace para descargar e instalar Winrar.</b></p>
			<p>https://www.winrar.es/descargas</p>
		</td>
	</tr>
	<tr>
		<td style="background-color: #FFFFFF;  padding: 20px;">
			<p>&nbsp;</p>
			<p>Un cordial saludo,</p>
			<p>Miele M&eacute;xico</p>
			<br/>
			<p><i>Por favor no responda a este correo electr&oacute;nico. Para cualquier duda con respecto a esta cotizaci&oacute;n, favor de contactar a su asesor de ventas.</i></p>
		</td>
	</tr>
</table>
</body>
</html>