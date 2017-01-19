<?php $this->load->view('email/header'); ?>
    <p>SUGERENCIA DE USUARIO CON LOS SIGUIENTES DATOS</p>
    <p><b>Nombre: </b><?php echo $comentario->nombre; ?></p>
    <p><b>Apellido paterno: </b><?php echo $comentario->apellido_paterno; ?></p>
    <p><b>Apellido materno: </b><?php echo $comentario->apellido_materno; ?></p>
    <p><b>Correo electr&oacute;nico: </b><?php echo $comentario->email; ?></p>
    <p><b>Tel&eacute;fono: </b><?php echo $comentario->telefono; ?></p>
    <p><b>Celular</b>: </b><?php echo $comentario->celular; ?></p>
    <p><b>Comentario: </b></p>
    <p><?php echo  $comentario->comentario; ?></p>
<?php $this->load->view('email/footer'); ?>