<?php $this->load->view('email/header'); ?>
			<p>Estimad@ <b><?php echo $usuario->nombre_completo; ?></b>,</p>
			<p>En respuesta a su solicitud, a continuaci&oacute;n le enviamos su nombre de usuario y contraseña para acceder a <b>Míele - Shop<?php //echo $this->config->item('proyecto');?></b>:</p>
			<br/>
			<p>Nombre de usuario: <b><?php echo $usuario->usuario; ?></b></p>
			<p>Contraseña: <b><?php echo $usuario->contrasena; ?></b></p>
			<br/>
			
			<p>La liga para ingresar a Miele - Shop es <a href="<?php echo $this->config->item('shop_url')?>frontends/autenticacion"><?php echo $this->config->item('shop_url')?>frontends/autenticacion</a>,</p>
			<br/>
			<p>Un cordial saludo,</p>
			<p>Administrador de <?php echo $this->config->item('proyecto');?></p> 
			<br/>
			<p><i>Este email fue enviado por un sistema autom&aacute;tico. Por favor no responda este email. Para entrar en contacto con nosotros, por favor entre con su cuenta de usuario registrado.</i></p>
		</div>
	</div>
</body>
</html>