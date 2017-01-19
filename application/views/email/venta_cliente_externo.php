<?php $this->load->view('email/header'); ?>
    <p>Hola <?php echo $cotizaciones->nombre_contacto?>.</p>
    <p>Gracias por comprar a trav&eacute;s de Miele Shop.</p>
    <p>Su pedido se encuentra en estatus de: <?php echo $cotizaciones->status_compra?>.</p>
    <p>En breve su asesor de ventas Miele confirmará su env&iacute;o.</p>
    <div>
        <?php $this->load->view('email/pedido_detalle'); ?>
    </div>
<br/>
<p>El tiempo para procesar su orden es de 1 a 2 d&iacute;as h&aacute;biles.</p>
<p>Posteriormente el tiempo de entrega en Ciudad de M&eacute;xico y &aacute;rea metropolitana es de 2 a 3 d&iacute;as h&aacute;biles. Para el resto de la rep&uacute;blica el tiempo es de 3 a 5 d&iacute;as h&aacute;biles.<p>
<p>Considere que en d&iacute;as festivos este tiempo puede variar.</p>
<br/>
<p>Un cordial saludo,</p>
<p>Administrador de <?php echo $this->config->item('proyecto');?></p>
<br/>
<p><i>Por favor no responda a este correo electr&oacute;nico, para cualquier aclaraci&oacute;n o comentario acerca del presente p&oacute;ngase en contacto con el administrador del sistema.</i></p>
</div>
</div>
</body>
</html>