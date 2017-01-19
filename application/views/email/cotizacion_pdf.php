<?php $this->load->view('email/header'); ?>
				<p>Estimad@ Usuario,</p>
				<p>Adjunto encontrar&aacute; el archivo con la cotizaci&oacute;n realizada, con folio <b><?php echo $cotizacion->folio_cuentas; ?></b></p>
				<br/>
				<p>Un cordial saludo,</p>
				<p>Administrador de <?php echo $this->config->item('proyecto');?></p> 
				<br/>
				<p><i>Por favor no responda a este correo electr&oacute;nico. Para cualquier duda con respecto a esta cotizaci&oacute;n, favor de contactar a su asesor de ventas.</i></p>
			</div>
		</div>
	</body>
</html>