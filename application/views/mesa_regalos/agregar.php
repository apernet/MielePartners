<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo?></h1>
<p class="msg info">Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Informaci&oacute;n general</div>
<div>
<table class="table_form">
<tbody>
	<tr>
		<td>
			<label><span class="req">*</span>Nombre:</label>
			<input id="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" />
			<?php echo  form_error('nombre');?>
		</td>
		<td>
			<label><span class="req">*</span>Folio:</label>
			<input id="folio" name="folio" value="<?php echo set_value('folio'); ?>" />
			<?php echo form_error('folio');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>E-mail:</label>
			<input id="email" name="email" value="<?php echo set_value('email'); ?>" />
			<?php echo form_error('email');?>
		</td>
		<td>
			<label><span class="req">*</span>Tel&eacute;fono:</label>
			<input id="telefono" name="telefono" value="<?php echo set_value('telefono'); ?>" />
			<?php echo form_error('telefono');?>
		</td>
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>C&oacute;digo postal:</label>
			<input id="codigo_postal" name="codigo_postal" value="<?php echo set_value('codigo_postal'); ?>" />
			<?php echo form_error('codigo_postal');?>
		</td>
		<td>
			<label><span class="req">*</span>Contrase&ntilde;a:</label>
			<input id="contrasena" name="contrasena" value="<?php echo set_value('contrasena'); ?>" />
			<?php echo form_error('contrasena');?>
		</td>
	</tr>
</tbody>
</table>
</div>
<div class="footer_form">		
	<span class="boton fl"><input type="submit" value="Guardar" id="guardar" /></span>
	<a href="<?php echo site_url('mesa_regalos/index');?>" class="fl p5 ml5">Cancelar</a>
	<div class="clearblock">&nbsp;</div>
</div>
</form>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } 