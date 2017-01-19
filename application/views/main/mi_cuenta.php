<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo?></h1>
<p class="msg info">Los campos requeridos estan marcados con <span class="req">*</span></p>
<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post">
<!-- INFORMACION GENERAL -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Informaci&oacute;n general</div>
<div>
<table class="table_form">
	<tbody>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Nombre de usuario:</label>
			<div class="div_info"><?php echo set_value('usuario', @$r->usuario); ?></div>
		</td>
		<td>
			<label>Contrase&ntilde;a:</label>
			<input id="cambiar_contrasena" type="checkbox"  />
			&iquest;Cambiar contrase&ntilde;a?
			<br/>
			<input id="contrasena" name="contrasena" type="password" disabled="disabled" class="opacity"/>
			<?php echo  form_error('contrasena');?>
		</td>
		
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>Nombre(s):</label>
			<input id="nombre" name="nombre" value="<?php echo set_value('nombre', @$r->nombre); ?>" />
			<?php echo  form_error('nombre');?>
		</td>
		<td>
			<label><span class="req">*</span>Apellido paterno:</label>
			<input id="apellido_paterno" name="apellido_paterno" value="<?php echo set_value('apellido_paterno', @$r->apellido_paterno); ?>" />
			<?php echo  form_error('apellido_paterno');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Apellido materno:</label>
			<input id="apellido_materno" name="apellido_materno" value="<?php echo set_value('apellido_materno', @$r->apellido_materno); ?>" />
			<?php echo  form_error('apellido_materno');?>
		</td>
		<td>
			<label><span class="req">*</span>Correo electr&oacute;nico:</label>
			<input id="email" name="email" value="<?php echo set_value('email', @$r->email); ?>" />
			<?php echo  form_error('email');?>
		</td>
	</tr>
	<tr>
		<td>
			<label>Tel&eacute;fono:</label>
			<input id="telefono" name="telefono" value="<?php echo set_value('telefono', @$r->telefono); ?>" />
			<?php echo  form_error('telefono');?>
		</td>
		<td>
			<label>Celular:</label>
			<input id="celular" name="celular" value="<?php echo set_value('celular', @$r->celular); ?>" />
			<?php echo  form_error('celular');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label>Ayuda:</label>
			<input name="ayuda" value="0" type="hidden" />
			<input type="checkbox" name="ayuda" value="1" <?php echo set_checkbox('ayuda','1',(@$r->ayuda)?TRUE:FALSE); ?> />
			<?php echo  form_error('ayuda');?>
		</td>
		<td>&nbsp;</td>
	</tr>
	</tbody>
</table>
</div>
<!-- FIN INFORMACION GENERAL -->

<div class="footer_form">		
	<span class="boton fl"><input type="submit" value="Guardar" id="guardar" /></span>
	<a href="<?php echo site_url('main/usuarios');?>" class="fl p5 ml5">Cancelar</a>
	<div class="clearblock">&nbsp;</div>
</div>
</form>
<script type="text/javascript">
<!--
$(function(){
	$('#cambiar_contrasena').change(function(){
		if(this.checked)
		{
			$('#contrasena').removeAttr('disabled');
			$('#contrasena').toggleClass('opacity');
		}			
		else
		{	
			$('#contrasena').attr('disabled','disabled');
			$('#contrasena').toggleClass('opacity');
		}
	});	
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>