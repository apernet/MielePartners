<?php $this->load->view('email/header'); ?>
	<p>SUGERENCIA DE USUARIO CON LOS SIGUIENTES DATOS</p>
	<p><b>Nombre de usuario: </b><?php echo $usuario->usuario; ?></p>
	<p><b>Correo electr&oacute;nico: </b><?php echo $usuario->email; ?></p>
	<p><b>Asunto: </b><?php echo $titulo; ?></p>
	<p><b>Comentarios: </b></p>
	<p><?php echo $sugerencia; ?></p>
	<p><b>Fecha: </b><?php ver_fecha_hora($fecha); ?></p>
<?php $this->load->view('email/footer'); ?>