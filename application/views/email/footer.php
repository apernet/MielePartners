			<br/>
			<p>Puede acceder al sistema siguiendo el siguiente v&iacute;nculo:</p>
			<p><a href='<?php echo @$externo?$this->config->item('shop_url'):$this->config->item('url'); ?>'><?php echo @$externo?$this->config->item('shop_url'):$this->config->item('url'); ?></a><p>
			<p><i>NOTA: Si el v&iacute;nculo no funciona, copie y pegue la URL entera en la barra de direcciones de su navegador.</i></p>
			<br/>
			<p>Un cordial saludo,</p>
			<p>Administrador de <?php echo $this->config->item('proyecto');?></p> 
			<br/>
			<p><i>Por favor no responda a este correo electr&oacute;nico, para cualquier aclaraci&oacute;n o comentario acerca del presente p&oacute;ngase en contacto con el administrador del sistema.</i></p>
		</div>
	</div>
</body>
</html>