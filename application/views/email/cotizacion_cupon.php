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
					<p>Agradecemos su preferencia y confianza en nuestra marca, adjunto encontrar&aacute; un cup&oacute;n por la compra que realiz&oacute; con folio <b><?php echo $folio_compra?></b>.</p>
				</td>
			</tr>
			<tr>
				<td style="background-color: #FFFFFF;  padding: 20px;">
					<img src="<?php echo $cupon_ruta; ?>" width="100%" height="100%"/>
				</td>
			</tr>
			<tr>
				<td style="background-color: #FFFFFF;  padding: 20px;">
					<p>&nbsp;</p>
					<p>Un cordial saludo,</p>
					<p>Administrador de <?php echo $this->config->item('proyecto');?></p>
					<br/>
					<p><i>Por favor no responda a este correo electr&oacute;nico. Para cualquier duda con respecto a esta cotizaci&oacute;n, favor de contactar a su asesor de ventas.</i></p>
				</td>
			</tr>
			<tr>
				<td style="background-color: #FFFFFF;  padding: 20px;">
					Si no puede visualizar correctamente este correo por favor d&eacute; clic <a href="<?php echo $visualizar_cupon;?>">aquí</a>.
				</td>
			</tr>
		</table>
	</body>
</html>