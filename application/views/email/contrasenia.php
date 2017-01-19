<?php $this->load->view('email/header'); ?>
	<p>Estimad@ <b><?php echo $usuario->nombre_completo; ?></b>,</p>
	<p>En respuesta a su solicitud, a continuaci&oacute;n le enviamos su nombre de usuario y contraseña para acceder al sistema <b><?php echo $this->config->item('proyecto');?></b>:</p>
	<br/>
	<p>Nombre de usuario: <b><?php echo $usuario->usuario; ?></b></p>
	<p>Contraseña: <b><?php echo $usuario->contrasena; ?></b></p>
<?php $this->load->view('email/footer'); ?>